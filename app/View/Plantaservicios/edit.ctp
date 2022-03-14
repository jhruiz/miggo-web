<?php $this->layout = 'inicio';?>
<div class="plantaservicios form">
<?php echo $this->Form->create('Plantaservicio', array('type' => 'post', 'class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Planta de Servicios'); ?></b></h2></legend>
                <?php echo $this->Form->input('id'); ?>
                <div class="form-group">
                    <label for="PlantaservicioDescripcion">Nombre Planta de Servicios</label>
                    <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre Planta de Servicio')); ?>
                </div>
	</fieldset><br>
<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
</div>