<?php

namespace Controllers;

use Model\Proyecto;

class TareaController
{
    public static function index()
    {
    }

    public static function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            session_start();

            $proyectoUrl = $_POST['proyectoUrl'];

            $proyecto = Proyecto::where('url', $proyectoUrl);

            if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al Agregar la Tarea'
                ];

                echo json_encode($respuesta);

                return;
            } else {
                $respuesta = [
                    'tipo' => 'exito',
                    'mensaje' => 'Tarea Agregada Correctamente'
                ];

                echo json_encode($respuesta);

                return;   
            }
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
