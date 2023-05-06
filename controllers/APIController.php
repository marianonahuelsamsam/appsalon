<?php

namespace Controller;

use Model\Servicio;

class APIController {
    public static function index(){
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }
}