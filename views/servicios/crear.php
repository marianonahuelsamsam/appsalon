<?php
@include_once __DIR__ . "/../templates/barra_sesion.php";
?>

<h1 class="nombre-pagina">Crear Servicio</h1> 
<p class="descripcion-pagina">Crea un nuevo servicio llenando los campos del formulario</p>
<?php include_once __DIR__ . "/../templates/alertas.php" ?>
<form action="/servicios/crear" class="formulario" method="POST">
    <?php include_once __DIR__ . "/formulario.php" ?>

    <input type="submit" value="Guardar Servicio" class="boton">
</form>