<?php

namespace Model;

class Usuario extends ActiveRecord
{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    /** Validación para cuentas nuevas */
    public function validarNuevaCuenta()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del Usuario es Obligatorio';
        }

        if (!$this->email) {
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }

        if (!$this->password) {
            self::$alertas['error'][] = 'El Password no Puede Estar Vacio';
        }

        if (strlen($this->password) < 5) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }

        if ($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Los password son diferentes';
        }

        return self::$alertas;
    }

    /** Valida un Email */
    public function validarEmail()
    {
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El Email no Válido';
        }

        return self::$alertas;
    }

    /** Hashea el Password */
    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    /** Generar un Token */
    public function crearToken()
    {
        $this->token = uniqid();
    }

    /** Validar el Password */
    public function validarPassword()
    {
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password no Puede Estar Vacio';
        }

        if (strlen($this->password) < 5) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }
}
