<?php
$this->layout = 'inicio';
echo ($this->Html->script('cargarinventario/cargarinvplano.js'));
?>

<?php echo $this->Form->create('Cargueplano', array('type' => 'file')); ?>
<div class="cargueiplano">
    <legend><h2><?php echo __('Cargue de Inventario'); ?></h2></legend>
    	<!--Enlaces de acción -->
		<div class="actions">
            <button type="button" class="btn btn-primary">
            <?php echo $this->Html->link(__('Lista Cargue de Inventarios'), array('action' => 'index'), ["style" => "color:white;"]); ?>
            </button>
        </div>
        <div class="col-md-12">
            
            <div class="alert alert-info alert-dismissable">
                <strong><h2>INSTRUCCIONES</h2></strong>
                <h5>1. Por favor, valide que el documento no tenga separadores de miles en los campos de pesos.</h5>
                <h5>2. Si los valores en pesos tienen decimales separados por comas (,), por favor, cambielos por punto (.).</h5>
                <h5>3. Por favor, Valide que en cada columna, el registro no tenga espacios al principio o al final del texto.</h5>
                <h5>4. Si el producto cuenta con impuesto, agregue el código del mismo, de lo contrario, deben ir las letras "na".</h5>
            </div>
        
        </div>
        <div class="col-md-12">
            <div class="x_panel">
                <div class="col-md-6">
                    <b>DESCARGAR PLANTILLA</b>
                    <img src="/img/png/excel.png" alt="..." style="width: 80px; height: 80px;" id='plantilla'>
                </div>
                <div class="col-md-6">
                    <?php echo $this->Form->input('cargarInventario', array('type' => 'file')); ?><br>
                    <?php echo $this->Form->submit('Cargar', array('class' => 'btn btn-primary')); ?>
                </div>
            </div>

            <?php if (!empty($mensaje)) {?>
                <div class="alert <?php if (empty($errorCsv)){ echo "alert-info"; }else{ echo "alert-warning"; } ?> alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <strong><h2><?php echo base64_decode($mensaje); ?>
                  <?php if (!empty($errorCsv)) {?> <button type="button" class="btn btn-warning" onclick="downloadCsvError('<?php echo $errorCsv; ?>')"><i class="fa fa-download"></i> Descargar</button> <?php }?> </h2></strong>
                </div>
            <?php }?>
        </div>
    </div>
</form>
