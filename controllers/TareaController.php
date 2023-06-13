<?php

namespace Controllers;

use Model\Tarea;
use Model\Proyecto;

class TareaController
{
    public static function index()
    {   
        /** Leer URL */
        $proyectoUrl = $_GET['url'];
        if (!$proyectoUrl) header('Location: /dashboard');

        /** Identificar Proyecto */
        $proyecto = Proyecto::where('url', $proyectoUrl);
        session_start();

        if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) header('Location: /404');

        /** Obtener todas las tareas asignadas al proyecto */
        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);

        echo json_encode(['tareas' => $tareas]);
    }

    public static function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            session_start();

            $proyectoId = $_POST['proyectoId'];

            $proyecto = Proyecto::where('url', $proyectoId);

            if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al Agregar la Tarea'
                ];

                echo json_encode($respuesta);

                return;
            }

            /** Todo bien, instanciar y cear la tarea */
            $tarea = new Tarea($_POST);

            // Sobreescribir valor para que se inserte correctamnete en la BD.
            $tarea->proyectoId = $proyecto->id;

            $resultado = $tarea->guardar();

            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea Creada Correctamente'
            ];

            echo json_encode($respuesta);
        }
    }

    public static function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }

    public static function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }
}
