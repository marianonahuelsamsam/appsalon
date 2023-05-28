<?php

namespace Model;

class citaServicio extends ActiveRecord {
    protected static $tabla = "citas_servicios";
    protected static $columnasDB = ["id", "citasId", "serviciosId"];

    public $id;
    public $citasId;
    public $serviciosId;

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? null;
        $this->citasId = $args["citasId"] ?? "";
        $this->serviciosId = $args["serviciosId"] ?? "";
    }
}