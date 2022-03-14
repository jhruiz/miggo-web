<?php $this->layout='inicio'; ?>
<div class="partevehiculos form">
<?php echo $this->Form->create('Partevehiculo', array('type' => 'post', 'class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Parte Vehículo'); ?></b></h2></legend>
                <?php echo $this->Form->input('id'); ?>
                <div class="col-md-12">
                    <label>Parte Vehículo</label><br>
                    <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Parte Vehículo')); ?>
                </div>
                <div class="col-md-12">
                    <label>Extra</label> 
                    <?php echo $this->Form->input('extra', array('label' => '', 'type' => 'checkbox', 'class' => 'form-control')); ?>
                </div>                 
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div>