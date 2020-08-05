<?php $this->layout='inicio'; ?>
<div class="marcavehiculos form">
<?php echo $this->Form->create('Marcavehiculo', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Marca de Vehículo'); ?></b></h2></legend>
                <div class="col-md-12">
					<label>Nombre Marca</label><br>
                    <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre Marca')); ?>
                </div>
	</fieldset><br>
	<div class="container-fluid">
		<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
	</div>
</div>