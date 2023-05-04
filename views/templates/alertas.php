<?php
foreach ($alertas as $key => $mensaje) :

    foreach($mensaje as $mensaje) :

?>

    <p class="alerta <?php echo $key === 'error' ? 'error' : 'exito' ?>"> 
        <?php echo $mensaje; ?> 
    </p>

<?php   
        endforeach;
        
    endforeach;
?>