<h1 class="descripcion-pagina">Recuperar Password</h1>

<p class="descripcion-pagina">Colocá tu nuevo password a continuación</p>

<?php
    // Mensajes de error
    include_once __DIR__ . "/../templates/alertas.php";
?>

<?php if ($error === true) {
    return;
} ?>
<form class="formulario" method="POST">

    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Tu Password">
    </div>

    <input type="submit" value="Guardar Nuevo Password" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tenés una cuenta? Inicia Sesión.</a>

    <a href="/crear">¿Aún no tienes una cuenta? Crea una.</a>
</div>