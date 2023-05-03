<?php

namespace Controllers;

use Model\Proyecto;
use MVC\Router;

class DashboardController
{
    public static function index(Router $router)
    {
        session_start();
        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router)
    {
        session_start();
        isAuth();

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);

            /** Validación */
            $alertas = $proyecto->validarProyecto();

            if (empty($alertas)) {
                /** Generar una URL única */
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                /** Almacenar creador de Proyecto */
                $proyecto->propietarioId = $_SESSION['id'];

                /** Guardar Proyecto */
                $proyecto->guardar();

                /** Redireccionar */
                header('Location: /proyecto?url=' . $proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router)
    {
        session_start();
        isAuth();

        /** Leer URL */
        $token = $_GET['url'];
        
        if(!$token) header('Location: /dashboard');

        $proyecto = Proyecto::where('url', $token);

        /** Revisar que la persona que visita el proyecto, es quien lo creo */
        if($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil(Router $router)
    {
        session_start();
        isAuth();

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil'
        ]);
    }
}
