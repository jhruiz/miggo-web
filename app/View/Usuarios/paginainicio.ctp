<?php echo ($this->Html->script('facturas/facturas.js')); ?>
<?php echo ($this->Html->script('usuarios/paginainicio.js'));?>
<?php
    $this->layout='inicio';
?>
<div class='container-fluid'>

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