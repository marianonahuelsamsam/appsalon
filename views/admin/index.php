<?php
@include_once __DIR__ . "/../templates/barra_sesion.php"
?>

<h1 class="nombre-pagina">Panel de Administración</h1>

<h2>Buscar Citas</h2>

<div class="busqueda"> 
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" placeholder="Fecha a buscar" value="<?php echo $fecha ?>">    
        </div>
    </form>
</div>

<!-- 
    A través del siguiente contenedor mostraremos las citas del día.
    Recibimos, del controlador, un arreglo. Dentro del mismo tendremos como objetos cada una de las citas.
    El arreglo con las citas lo recibimos con el nombre "citas".
-->

<?php if(count($citas) === 0) : ?>
    <h2>No Hay Citas en Esta Fecha</h2>
<?php endif; ?>


<div id="citas-admin">

    <ul class="citas">
        <?php // Inicialización de variables (para evitar errores).
            $idAnterior = 0; // Nos sirve para evitar repetir datos como id, hora, cliente, etc. Solo mostrar servicios.
            $numeroCita = 1; // Cabecera de cada cita.
        ?>

        <!-- Recorremos el arreglo de citas -->
        <?php foreach ($citas as $key => $cita) :?>
            <!-- Solo se imprimen los datos de ID, hora, cliente, email y teléfono si el creador de la cita es distinto -->
            <?php if($idAnterior !== $cita->id) :?>
                <?php $precioTotal = 0; ?>
                <li>
                    <h3>Cita <?php echo $numeroCita++ ?> </h3>
                    <p> Id: <span><?php echo $cita->id ?></span> </p> 
                    <p> Hora: <span><?php echo $cita->hora ?></span> </p> 
                    <p> Cliente: <span><?php echo $cita->cliente ?></span> </p> 
                    <p> Email: <span><?php echo $cita->email ?></span> </p> 
                    <p> Teléfono: <span><?php echo $cita->telefono ?></span> </p> 
                </li>
                
                <h3>Servicios</h3>

            <?php $idAnterior = $cita->id; endif // Fin de If y almacenamos el id que recorrimos?> 
            
            <p class=""> <span> <?php echo $cita->servicio . " " . $cita->precio;?> </span> </p>
        
            <?php 
                // Sumatoria del precio, solo vuelve a cero cuando cambia el ID recorrido.
                $precioTotal += $cita->precio;

                /*
                    De la base de datos recibimos un arreglo con cada cita como un objeto.
                    En el foreach definimos $key para saber por cuál índice de ese arreglo vamos.
                    En las siguientes líneas lo que hacemos es guardar el id actual dentro de $registroActual.
                    También guardamos el id de la siguiente cita dentro $registroSiguiente.
                    La función esUltimo() lo que hace es comparar ambos ID.
                    En caso de no coincidir, significa que sumamos el precio del último servicio del id.
                    Entonces, imprimimos en pantalla el precio actual.
                    Cuando el foreach vuelva a recorrer el arreglo, reinicirá el precio a 0 (ver inicialización de variable)
                */
                $registroActual = $cita->id;
                $registroSiguiente = $citas[$key + 1]->id ?? 0;

                if(esUltimo($registroActual, $registroSiguiente)) : ?>
                    <p class="total"> Total: <span><?php echo "$" . $precioTotal; ?></span> </p>

                    <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id ?>">

                        <input type="submit" class="boton-eliminar" value="Eliminar">
                    </form>
                <?php endif; ?>
        <?php endforeach; // Fin de Foreach?>
    </ul>

</div>

<?php 
    $script = '<script src="build/js/buscadorFechaAdmin.js"> </script>';
?>