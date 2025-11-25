<?php

namespace Model;

class Usuario extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token', 'intentos'];

    public $id; ///
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;
    public $intentos;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
        $this->intentos = $args['intentos'] ?? '0';
    }

    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta() {
        // 1. Validaciones de NOMBRE
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        } elseif (strlen($this->nombre) > 60) {
            self::$alertas['error'][] = 'El Nombre es muy largo.';
        }

        // 2. Validaciones de APELLIDO
        if(!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido es Obligatorio';
        } elseif (strlen($this->apellido) > 60) {
            self::$alertas['error'][] = 'El Apellido es muy largo.';
        }

        // 3. Validaciones de TELÉFONO
        if(!$this->telefono) {
            self::$alertas['error'][] = 'El Teléfono es Obligatorio';
        } elseif (!preg_match('/^[0-9]{10}$/', $this->telefono)) {
            self::$alertas['error'][] = 'El teléfono debe contener exactamente 10 números';
        }
        // 4. Validaciones de EMAIL
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        } else {
            // Validación de longitud (Basado en tu BD actual, aunque recomiendo ampliarlo)
            if(strlen($this->email) > 60) {
                self::$alertas['error'][] = 'El Email es muy largo.';
            }
            
            // Validación de sintaxis (formato correcto)
            if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                self::$alertas['error'][] = 'El Email no tiene un formato válido';
            } else {
                // Validación de Dominio (DNS)
                list($user, $domain) = explode('@', $this->email);
                if (!checkdnsrr($domain, 'MX')) {
                    self::$alertas['error'][] = 'El dominio del correo no existe o no puede recibir emails';
                }
            }
        }
        // 5. Validaciones de PASSWORD
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        } elseif (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        } elseif (strlen($this->password) > 30) {
            // Nota: El hash de bcrypt siempre es de 60 chars, pero la entrada del usuario no debería ser kilométrica
            self::$alertas['error'][] = 'El password es demasiado largo.';
        }

        return self::$alertas;
    }


    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }

        return self::$alertas;
    }
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    // Revisa si el usuario ya existe
    public function existeUsuario() {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas['error'][] = 'El Usuario ya esta registrado';
        }

        return $resultado;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);
        
        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Password Incorrecto o tu cuenta no ha sido confirmada';
        } else {
            return true;
        }
    }

}