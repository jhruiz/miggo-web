<?php echo ($this->Html->script('facturas/facturas.js')); ?>
<?php echo ($this->Html->script('usuarios/paginainicio.js'));?>
<?php
    $this->layout='inicio';
?>
<div class='container-fluid'>


    <?php if( $infoResolucion['porFecha'] != "" || $infoResolucion['porDias'] != "" ) { ?>
        <div class="alert alert-danger" role="alert" style="margin-top: 15px;">
            <?php if( isset($infoResolucion['porFecha']) && $infoResolucion['porFecha'] != "" ) { ?>
                <div>
                    <?php echo($infoResolucion['porFecha']); ?>
                </div>
            <?php } ?>

            <?php if( isset($infoResolucion['porDias']) && $infoResolucion['porDias'] != "" ) { ?>
                <div>
                    <?php echo($infoResolucion['porDias']); ?>
                </div>
            <?php } ?>

        </div> 
    <?php } ?>

    <div class='row'>    
        <div class="form-group col-md-3">
            <label for="fechaInicial">Fecha Inicial</label><br>
            <input id="fechaInicial" autocomplete="off" class="date form-control" placeholder="Fecha inicial" type="text">
        </div>

        <div class="form-group col-md-3">
            <label for="fechaFinal">Fecha Final</label><br>
            <input id="fechaFinal" autocomplete="off" class="date form-control" placeholder="Fecha final" type="text">
        </div>    
    </div>

</div>
<div id="graficos" style="magin:20px;"></div>