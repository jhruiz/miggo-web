<?php $this->layout = 'inicio';?>
<div class="marcavehiculos form">
<?php echo $this->Form->create('Marcavehiculo', array('type' => 'post', 'class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Marca de Vehículo'); ?></b></h2></legend>
                <?php echo $this->Form->input('id'); ?>
                <div class="col-md-12">
					<label>Nombre Marca</label>
                    <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre Marca')); ?>
                </div>
	</fieldset><br>
	<div class="container-fluid">
		<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
	</div>
</div>