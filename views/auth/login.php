<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php
    // Mensajes de error
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form class="formulario" method="post" action="/">

    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            placeholder="Tu Email"
            name="email"
            value="<?php echo $email ?>"
        />
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            placeholder="Tu password"
            name="password"
        />
    </div>

    <input type="submit" class="boton" value="Iniciar Sesión">
    
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tenés una cuenta? Creá una.</a>

    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>