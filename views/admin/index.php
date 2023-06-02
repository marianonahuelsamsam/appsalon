<?php
@include_once __DIR__ . "/../templates/barra_sesion.php"
?>

<h1 class="nombre-pagina">Panel de Administración</h1>

<h2>Buscar Citas</h2>

<div class="busqueda"> 
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" placeholder="Fecha a buscar">    
        </div>
    </form>
</div>

<div id="citas-admin"></div>