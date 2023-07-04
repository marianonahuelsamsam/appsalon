<?php

namespace Controller;

use Model\Servicio;
use MVC\Router;

class ServicioController {
    /* En esta función simplemente consultamos la base de datos para recibir todos los servicios, enviarlos a la vista 
    Y en ella mostrarlos. */
    public static function index(Router $router) {
        isAdmin();
        //Pasar el nombre de usuario para mostrarlo en la barra de sesión.
        $nombre = $_SESSION["nombre"];

        // Consutamos todos los servicios disponibles para enviarlos en el render.
        $servicios = Servicio::all();

        $router->render("servicios/index", [
            "nombre" => $nombre,
            "servicios" => $servicios
        ]);
    }

    // Procesamiento de datos del formulario para crear nuevos servicios y almacenarlos en la base de datos.
    public static function crear(Router $router) {
        isAdmin();
        //Pasar el nombre de usuario para mostrarlo en la barra de sesión.
        $nombre = $_SESSION["nombre"];
        // Inicializamos el arreglo de las alertas.
        $alertas = [];

        $servicio = new Servicio();

        // POST
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $servicio->sincronizar($_POST);

            // Validamos que todos los campos hayan sido llenados.
            $alertas = $servicio->validarServicio();

            // Si no hay elementos en el arreglo de alerts, todos los campos han sido escritos y procedemos:
            if(empty($alertas)) {
                $servicio->guardar(); 
                Servicio::setAlerta("exito", "El servicio fue guardado correctamente");
                
                // Vaciamos $servicio para limpiar los inputs.
                $servicio = [];
            } 
        }  

        // Se obtienen las alertas en caso de que hayamos seteado alguna.
        $alertas = Servicio::getAlertas();
        

        $router->render("servicios/crear", [
            "nombre" => $nombre,
            "alertas" => $alertas,
            "nombreServicio" => $servicio->nombre ?? "",
            "precio" => $servicio->precio ?? ""
        ]);
    }

    /* En esta función obtendremos el servicio a actualizar a través de GET por medio de una query string generada
    en /servicios por el botón "actualizar" (recurrir a servicios/index.php para corroborar). */
    public static function actualizar(Router $router) {
        isAdmin();
        //Pasar el nombre de usuario para mostrarlo en la barra de sesión.
        $nombre = $_SESSION["nombre"];

        // Obtenemos el id desde get.
        $id = $_GET["id"];  
        $servicio = Servicio::find($id);

        // Si el id no es número o si no coincide con un registro de la base de datos, redirigimos al index de servicios.
        if(!is_numeric($id) || !$servicio) header("location: /servicios");

        // Inicializamos el arreglo de alertas.
        $alertas = [];

        // POST
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $servicio->sincronizar($_POST);

            $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar();

                header("location: /servicios?id=$servicio->id");
            }
        }

        $router->render("servicios/actualizar", [
            "nombre" => $nombre,
            "alertas" => $alertas,
            "nombreServicio" => $servicio->nombre ?? "",
            "precio" => $servicio->precio ?? ""
        ]);
    }

    public static function eliminar() {
        isAdmin();
        
        if($_SERVER["REQUEST_METHOD"] === "POST") {           
            $id = $_POST["id"];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header("location: /servicios?resultado=2");

        }
    }


}