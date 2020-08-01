<?php $this->layout='inicio'; ?>
<div class="partevehiculos form">
<?php echo $this->Form->create('Partevehiculo', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Parte de Vehículo'); ?></b></h2></legend>
                <div class="col-md-12" style='margin-bottom:20px;'>
					<label>Parte de Vehículo</label><br>
                    <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Parte de Vehículo')); ?>
                </div>
                <div class="col-md-12">
					<label>Extra</label>
                    <?php echo $this->Form->input('extra', array('label' => '', 'type' => 'checkbox', 'class' => 'form-control')); ?>
                </div>                
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Partes de Vehículo'), array('action' => 'index')); ?></li>
	</ul>
</div>
