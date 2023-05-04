<h1 class="nombre-pagina">Crear Cuenta</h1>

<p class="descripcion-pagina">Llená los campos de este formulario para crear una cuenta.</p>


<!-- Este es el formulario destinado a la creación de cuentas. Hay que resaltar que los inputs poseen un value que hace 
referencia al valor almacenado en el objeto en memoria. Esto es para que al momento de recargar la página, o de que se envien
datos a través del formulario que no pasen la validación, no se pierdan los campos que se llenaron.-->


<?php
    // Mensajes de error
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form class="formulario" method="POST" action="/crear-cuenta">
    
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" value="<?php echo s($usuario->nombre) ?>">
    </div>
        
    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text" id="apellido" name="apellido" placeholder="Tu apellido" value="<?php echo s($usuario->apellido) ?>">
    </div>
        
    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono" placeholder="Tu teléfono" value="<?php echo s($usuario->telefono) ?>">
    </div>
        
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Tu email" value="<?php echo s($usuario->email) ?>">
    </div>
        
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password">
    </div>

    <input type="submit" class="boton" value="Crear Cuenta">
</form>

<div class="acciones">
    <a href="/">¿Ya tenés una cuenta? Inicia Sesión.</a>

    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>