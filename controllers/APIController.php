<?php

namespace Controller;

use Model\Servicio;

class APIController {
    public static function index(){
        $servicios = Servicio::all();
        json_encode($servicios);
    }
}