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

<div id="citas-admin">

    <ul class="citas">
        <?php $idAnterior = 0; ?>
        <?php foreach ($citas as $cita) :?>
            <?php if($idAnterior !== $cita->id) :?>
                <li>
                    <p> Id: <span><?php echo $cita->id ?></span> </p> 
                    <p> Hora: <span><?php echo $cita->hora ?></span> </p> 
                    <p> Cliente: <span><?php echo $cita->cliente ?></span> </p> 
                    <p> Email: <span><?php echo $cita->email ?></span> </p> 
                    <p> Teléfono: <span><?php echo $cita->telefono ?></span> </p> 
                    
                </li>
                

                <h3>Servicios</h3>
            <?php $idAnterior = $cita->id; endif?>

                <p class=""> <?php echo $cita->servicio . " " . $cita->precio;?> </p>
        <?php endforeach; ?>
    </ul>

</div>