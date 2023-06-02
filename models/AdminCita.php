<?php 

namespace Controller;

use Model\ActiveRecord;

class AdminCita extends ActiveRecord {
    protected static $tabla = "citas_servicio";
    protected static $columnasDB = ["id", "hora", "cliente", "email", "telefono", "servicio", "precio"];

    public $id;
    public $hora;
    public $cliente;
    public $email;
    public $telefono;
    public $servicio;


    
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args["id"];
        $this->hora = $args["hora"];
        $this->cliente = $args["cliente"];
        $this->email = $args["email"];
        $this->telefono = $args["telefono"];
        $this->servicio = $args["servicio"];
        $this->precio = $args["precio"];
    }
    
}