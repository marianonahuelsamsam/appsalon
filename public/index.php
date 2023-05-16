<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controller\APIController;
use Controller\CitaController;
use Controller\LoginController;

$router = new Router();
// Zona pública 

// Sesión
$router->get("/", [LoginController::class, "login"]);
$router->post("/", [LoginController::class, "login"]);
$router->get("/logout", [LoginController::class, "logout"]);

// Recuperar Password
$router->get("/olvide", [LoginController::class, "olvide"]);
$router->post("/olvide", [LoginController::class, "olvide"]);
$router->get("/recuperar", [LoginController::class, "recuperar"]);
$router->post("/recuperar", [LoginController::class, "recuperar"]);

// Crear cuenta
$router->get("/crear-cuenta", [LoginController::class, "crear"]);
$router->post("/crear-cuenta", [LoginController::class, "crear"]);

// Confirmación de cuenta
$router->get("/mensajeConfirmacion", [LoginController::class, "mensajeConfirmacion"]);
$router->get("/confirmar-cuenta", [LoginController::class, "confirmar"]);

// Zona privada
$router->get("/cita", [CitaController::class, "index"]);

// API Servicios
$router->get("/api/servicios", [APIController::class, "index"]);
$router->post("/api/cita", [APIController::class, "guardar"]);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();