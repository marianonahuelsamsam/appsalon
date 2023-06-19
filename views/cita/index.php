<?php
@include_once __DIR__ . "/../templates/barra_sesion.php"
?>
<h1 class="nombre-pagina">Crear Una Nueva Cita</h1>

<p class="descripcion-pagina">Elige tus servicios y coloca tus datos</p>

<!-- Contenedor general de la sección cita -->
<div id="app">
    <!-- Barra de navegación de la sección -->
    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Información Cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>   
    <!-- Al presionar en cada seccion se navegará exclusivamente por alguno de los siguientes contenedor, 
    nunca se mostrarán todos juntos (mediante Javascript). -->
    
    <!-- Servicios -->
    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios a continuación</p>
        <div id="servicios" class="listado-servicios"></div>
    </div>
    <!-- Información Cita -->
    <div id="paso-2" class="seccion">
        <h2>Tus datos y cita</h2>
        <p class="text-center">Coloca tus datos y fecha de cita</p>

        <form class="formulario">
            <div class="campo">
                <label for="nombre">Tu Nombre</label>
                <input type="text" id="nombre" placeholder="Tu Nombre" value="<?php echo $nombre ?>" disabled>
            </div>
            <div class="campo">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" min="<?php echo date("Y-m-d", strtotime("+1 day")) ?>">
            </div>
            <div class="campo">
                <label for="hora">Hora</label>
                <input type="time" id="hora">
            </div>
            <input type="hidden" id="usuarioId" value="<?php echo $id ?>">

        </form>
    </div>
    <!-- Resumen -->
    <div id="paso-3" class="seccion cita-resumen">
        <h2>Resumen</h2>
        <p class="text-center">Verifica que la información sea correcta</p>
    </div>

    <!-- Flechas de paginaión -->
    <div class="paginacion">
        <button class="boton" id="anterior">&laquo; Anterior</button>
        <button class="boton" id="siguiente">SIguiente &raquo;</button>
    </div>
</div> 
<!-- Fin contenedor principal de la sección. -->

<?php 
// Incluimos el script
$script = "<script src='build/js/app.js'></script>" . 
"<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
?>