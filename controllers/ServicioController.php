<?php

namespace Controller;

use MVC\Router;

class ServicioController {
    public static function index(Router $router) {
        
        $router->render("servicios/index", [
            
        ]);
    }
    public static function crear(Router $router) {
        echo "Desde crear ";
    }
    public static function actualizar(Router $router) {
        echo "Desde actualizar";
    }
    public static function eliminar() {
        echo "Desde eliminar";
    }


}