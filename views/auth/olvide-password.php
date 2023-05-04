<h1 class="descripcion-pagina">Reestablecer Password</h1>

<p class="descripcion-pagina">Ingresá el email en el que querés recibir las instrucciones pra reestablecer tu contraseña.</p>

<?php
    // Mensajes de error
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/olvide" method="POST" class="formulario">

    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            placeholder="Tu Email"
            name="email"
            value="<?php echo $auth->email;?>"
        />
    </div>

    <input type="submit" class="boton" value="Recibir Instrucciones">

</form>

<div class="acciones">
    <a href="/">¿Ya tenés una cuenta? Inicia Sesión.</a>

    <a href="/crear-cuenta">¿Aún no tenés una cuenta? Crear una.</a>
</div>