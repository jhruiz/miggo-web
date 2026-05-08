<?php echo ($this->Html->script('facturas/facturas.js')); ?>
<?php echo ($this->Html->script('usuarios/paginainicio.js'));?>
<?php
    $this->layout='inicio';
?>

<div class="container-fluid" style="padding-top: 20px;">

    <div class="card-moderna" style="padding: 15px; margin-bottom: 20px;">
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