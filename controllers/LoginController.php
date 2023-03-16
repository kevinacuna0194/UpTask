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
        }

        /** Render a la vista */
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión'
        ]);
    }

    public static function logout()
    {
        echo "Desde Logout...";
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        /** Render a la vista */
        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Password'
        ]);
    }

    public static function reestablecer(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        /** Render a la vista */
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password'
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
        /** Render a la vista */
        $router->render('auth/confirmar', [
            'titulo' => 'Confirme tu Cuenta UpTask'
        ]);
    }
}
