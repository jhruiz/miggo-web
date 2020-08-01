<?php $this->layout='inicio'; ?>
<div class="tipovehiculos form">
<?php echo $this->Form->create('Tipovehiculo', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Tipo de Vehículo'); ?></b></h2></legend>
                <div class="form-group form-inline">
					<label>Tipo de Vehículo</label><br>
                    <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Tipo de Vehículo')); ?>
                </div>
	</fieldset><br>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Tipos de Vehículos'), array('action' => 'index')); ?></li>
	</ul>
</div>
