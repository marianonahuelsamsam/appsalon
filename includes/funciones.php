<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// Proteger las rutas privadas de usuarios no autenticados.

function isAuth() : void {
    if (!isset($_SESSION["login"])) {
        header("location: /");
    }
}

// Función creada para la vista de Admin. Compara dos id.
function esUltimo($registroActual, $registroSiguiente) {
    if($registroActual !== $registroSiguiente) {
        return true;
    }

    return false;
}