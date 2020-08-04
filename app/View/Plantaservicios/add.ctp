<?php $this->layout = 'inicio';?>
<div class="plantaservicios form">
<?php echo $this->Form->create('Plantaservicio', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Planta de Servicio'); ?></b></h2></legend>
                <div class="form-group">
                    <label for="PlantaservicioDescripcion">Nombre Planta de Servicio</label>
                    <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre Planta de Servicio')); ?>
                </div>
        </fieldset><br>
<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
</div>