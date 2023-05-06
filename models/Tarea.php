<?php

namespace Model;

class Tarea extends ActiveRecord
{
    protected static $tabla = 'tareas';
    protected static $columnasDB = ['id', 'nombre', 'estado', 'proyectoUrl'];

    public $id;
    public $nombre;
    public $estado;
    public $proyectoUrl;

    public function __construct($args = [])
    {
        session_start();

        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->estado = $args['estado'] ?? 0;
        $this->proyectoUrl = $args['proyectoUrl'] ?? '';
    }
}
