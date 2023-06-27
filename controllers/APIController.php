<?php

namespace Controller;

use Model\Cita;
use Model\citaServicio;
use Model\Servicio;

class APIController {
    public static function index(){
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar() {
        // Guarda la cita y recibe el id.
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        // Asignamos el id de la cita a la siguiente variable.
        $idCitas = $resultado["id"];
    
        // Creamos un arreglo con los id de los servicios seleccionados
        $idServicios = explode(",", $_POST["servicios"]);

        foreach ($idServicios as $idServicio) {
            $args = [   
                "citasId" => $idCitas,
                "serviciosId" => $idServicio
            ];

            $citasServicios = new citaServicio($args);
            $citasServicios->guardar();
        }
 
        echo json_encode($resultado);
    }

    public static function eliminar() {
        if($_SERVER["REQUEST_METHOD"] === "POST" ) {
            $id = $_POST["id"];

            $cita = Cita::find($id);
            $cita->eliminar();

            header("location: " . $_SERVER["HTTP_REFERER"]);
        }
    }
}