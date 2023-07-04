<?php
@include_once __DIR__ . "/../templates/barra_sesion.php";
?>

<h1 class="nombre-pagina">Servicios</h1> 
<p class="descripcion-pagina">Administra los servicios</p>

<?php $idServicioActualizado = $_GET["id"] ?? ""; ?>

<ul class="lista-servicios">
    <?php foreach($servicios as $servicio) : ?>
        
        <li>
            <p>Nombre: <span><?php echo $servicio->nombre ?></span></p>
            <p>Precio: $<span><?php echo $servicio->precio ?></span></p>

            <?php if($servicio->id === $idServicioActualizado) : ?>
                <p class="alerta exito"> <span>Servicio actualizado</span> </p>
            <?php endif ?>    
            
            <div class="acciones">
                <a href="/servicios/actualizar?id=<?php echo $servicio->id; ?>" class="boton">Actualizar</a>

                <form action="/servicios/eliminar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $servicio->id; ?>">
                    <input type="submit" class="boton-eliminar" value="Eliminar">
                </form>
                
            </div>
        </li>

    <?php endforeach ?>
</ul>
