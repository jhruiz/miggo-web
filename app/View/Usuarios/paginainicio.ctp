<?php echo ($this->Html->script('facturas/facturas.js')); ?>
<?php echo ($this->Html->script('usuarios/paginainicio.js'));?>
<?php
    $this->layout='inicio';
?>

<div class="container-fluid"">

    <div class="card-moderna">

        <?php if(!empty($infoResolucion['porFecha']) || !empty($infoResolucion['porDias'])) { ?>
            <?php foreach( $infoResolucion as $key => $val ) { ?>

                <?php if( !empty($val) ) { ?>
                    <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    <?php echo($val); ?>
                    </div>
                <?php } ?>
            
            <?php } ?>
        <?php } ?>

        <div class="row">
            <div class="form-group col-md-3 mb-0">
                <label class="small"><b>FECHA INICIAL</b></label>
                <input id="fechaInicial" class="date form-control" placeholder="YYYY-MM-DD" type="text">
            </div>
            <div class="form-group col-md-3 mb-0">
                <label class="small"><b>FECHA FINAL</b></label>
                <input id="fechaFinal" class="date form-control" placeholder="YYYY-MM-DD" type="text">
            </div>
        </div>
    </div>

    <div id="graficos" class="row"></div>

</div>