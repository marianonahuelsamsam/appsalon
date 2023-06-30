<?php 

namespace Controller;

use MVC\Router;
use Model\AdminCita;


class AdminController {
    public static function index(Router $router) {
        isAdmin();

        /* Se realiza esta comprobación para evitar mostrar información de errores en caso de que el usuario 
        modifique el query string en la barra de direcciones.
        El If comprueba que esté definido $_GET["fecha] y que la fecha contenida tenga una extensión de 10. */

        if ( isset($_GET["fecha"]) && strlen($_GET["fecha"]) === 10) {
            $fecha = $_GET["fecha"];
        } else {
            $fecha = date("Y-m-d");
        }
        
        // Guardamos dentro de $fechas día, mes y año de forma independiente y lo asignamos a variables.
        $fechas = explode("-", $fecha);
        $dia = $fechas[2];
        $mes = $fechas[1];
        $año = $fechas[0];
        
        // Chequeamos que sea una fecha válida.
        if (!checkdate($mes, $dia, $año)) {
            header("location: /404");
        }

        // Consulta a la base de datos.
        $consulta = $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citas_servicios ";
        $consulta .= " ON citas_servicios.citasId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citas_servicios.serviciosId ";
        $consulta .= " WHERE fecha =  '$fecha' ";

        $citas = AdminCita::SQL($consulta);

        $router->render("admin/index", [
            "nombre" => $_SESSION["nombre"],
            "citas" => $citas,
            "fecha" => $fecha
        ]);
    }
}