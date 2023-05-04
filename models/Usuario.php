<?php

namespace Model;

class Usuario extends ActiveRecord {

    protected static $tabla = "usuarios";
    protected static $columnasDB = ["id", "nombre", "apellido", "email", "password", "telefono", "admin", "confirmado", "token"];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {
        
        $this->id = $args["id"] ?? NULL;
        $this->nombre = $args["nombre"] ?? "";
        $this->apellido = $args["apellido"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->password = $args["password"] ?? "";
        $this->telefono = $args["telefono"] ?? "";
        $this->admin = $args["admin"] ?? 0;
        $this->confirmado = $args["confirmado"] ?? 0;
        $this->token = $args["token"] ??"";

    }
    
    public function validarNuevaCuenta() {

        $campos = ["nombre","apellido", "email"];
        foreach($campos as $campo) {
            if(!$this->$campo) {
                self::$alertas["error"][] = "El $campo es obligatorio";
            }
        }

        if(!$this->password || strlen($this->password) < 6) {
            self::$alertas["error"][] = "La contraseña es obligatoria y debe ser mayor a 6 caracteres";
        };

        return self::$alertas;

    }

    public function validarUsuario() {
        if(!$this->email) {
            self::$alertas["error"][] = "El email es obligatorio";
        }
        if(!$this->password) {
            self::$alertas["error"][] = "La contraseña es obligatoria";
        }

        return self::$alertas;
    }

    public function validarEmail() {
        if(!$this->email) {
            self::$alertas["error"][] = "El email es obligatorio";
        }

        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password) {
            self::$alertas["error"][] = "El Password es obligatorio";
        }
        if(strlen($this->password) < 6) {
            self::$alertas["error"][] = "El Password debe contener al menos 6 caracteres";
        }

        return self::$alertas;
    }

    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1;" ;

        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas["error"][] = "Este email ya ha sido registrado";
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
        $resultado = password_verify($password, $this->password) ;
        
        if (!$resultado || !$this->confirmado) {
            self::$alertas["error"][] = "Contraseña incorrecta o cuenta no verificada";
        } else {
            return true;
        }
    }


}