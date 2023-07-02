<?php @include_once __DIR__ . "/../templates/barra_sesion.php"; ?>

<h1 class="nombre-pagina">Actualizar Servicio</h1> 
<p class="descripcion-pagina">Actualiza el servicio modificando los campos del formulario</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<form class="formulario" method="POST">
    <?php include_once __DIR__ . "/formulario.php" ?>

    <input type="submit" value="Actualizar Servicio" class="boton">
</form>