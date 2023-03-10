<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;

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
        /** Instanciar el modelo de Usuarios */
        $usuario = new Usuario;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarNuevaCuenta();

            debuguear($alertas);
        }

        /** Render a la vista */
        $router->render('auth/crear', [
            'titulo' => 'Crea tu Cuenta en UpTask',
            'usuario' => $usuario
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
