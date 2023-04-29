<?php

namespace Controllers;

use classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController
{
    public static function login(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $alertas = [];

            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarLogin();

            if (empty($alertas)) {
                /** Verificar que el usuario exista */
                $usuario = Usuario::where('email', $usuario->email);

                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El Usuario No Existe o No Esta Confirmado');
                } else {
                    /** El Usuario existe */
                    if (password_verify($_POST['password'], $usuario->password)) {
                        /** Iniciar la Sesión*/
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        /* debuguear($_SESSION);
                            array(4) {
                            ["id"]=>
                            string(2) "12"
                            ["nombre"]=>
                            string(6) " Kevin"
                            ["email"]=>
                            string(17) "correo@correo.com"
                            ["login"]=>
                            bool(true)
                            }
                        */

                        /** Redireccionar */
                        header('Location: /dashboard');
                    } else {
                        Usuario::setAlerta('error', 'Password Incorrecto');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();

        /** Render a la vista */
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout()
    {
        /** Traer información de la Sesión que esté en el servidor */
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function crear(Router $router)
    {
        $alertas = [];
        /** Instanciar el modelo de Usuarios */
        $usuario = new Usuario;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarNuevaCuenta();

            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email); // NULL (Si no existe)

                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El Usuario ya está registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    /** Hashear Password */
                    $usuario->hashPassword();

                    /** Eliminar password2*/
                    unset($usuario->password2);

                    /** Generar el Token */
                    $usuario->crearToken();

                    /** Crear nuevo usuario */
                    $resultado = $usuario->guardar();

                    /** Enviar Email */
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        /** Render a la vista */
        $router->render('auth/crear', [
            'titulo' => 'Crea tu Cuenta en UpTask',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if (empty($alertas)) {
                /** Buscar el Usuario */
                $usuario = Usuario::where('email', $usuario->email);

                if ($usuario && $usuario->confirmado) {

                    /** 1- Generar un nuevo Token*/
                    $usuario->crearToken();
                    unset($usuario->password2);

                    /** 2- Actualizar el usuario */
                    $usuario->guardar();

                    /** 3- Enviar email */
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    /** 4- Imprimir alerta */
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
                } else {
                    Usuario::setAlerta('error', 'Usuario no Existe o no Esta Confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        /** Render a la vista */
        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Password',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router)
    {
        $token = s($_GET['token']); // string(13) "644841e889397"

        $mostrar = true;

        if (!$token) header('Location: /');

        /** Identificar el Usuario con el token */
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido');
            $mostrar = false;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            /** Añadir el nuevo password */
            $usuario->sincronizar($_POST);

            /** Validar Password */
            $alertas = $usuario->validarPassword();

            if (empty($alertas)) {
                /** Hashear el nuevo password */
                $usuario->hashPassword();

                /** Eliminar el token (Un solo uso))*/
                $usuario->token = null;

                /** Guardar el usuario en la BD */
                $resultado = $usuario->guardar();

                /** Redireccionar */
                if ($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        /** Render a la vista */
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router)
    {
        /** Render a la vista */
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamnete'
        ]);
    }

    public static function confirmar(Router $router)
    {
        /** Leer Token de la URL */
        $token = s($_GET['token']);
        if (!$token) header('Llocation: /');

        /** Encontrar al usuario con el Token */
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            /** No se encontró usuario con ese Token */
            Usuario::setAlerta('error', 'Token no Válido');
        } else {
            /** Confirmar la cuenta */
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);

            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        $alertas = Usuario::getAlertas();

        /** Render a la vista */
        $router->render('auth/confirmar', [
            'titulo' => 'Confirme tu Cuenta UpTask',
            'alertas' => $alertas
        ]);
    }
}
