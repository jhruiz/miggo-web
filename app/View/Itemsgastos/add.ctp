<?php $this->layout = 'inicio';?>
<div class="itemsgasto form">
<?php echo $this->Form->create('Itemsgasto', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Item para Gasto'); ?></b></h2></legend>
                <div class="form-group">
                    <label for="ItemsgastoDescripcion">Nombre Item</label>
                    <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre del Item')); ?>
                </div>
	</fieldset><br>
<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
</div>