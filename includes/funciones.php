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

function isAuth() {
    if (!isset($_SESSION["login"])) {
        header("location: /");
    }
}