<?php $this->layout = 'inicio';?>
<div class="itemsgastos form">
<?php echo $this->Form->create('Itemsgasto', array('type' => 'post')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Item'); ?></b></h2></legend>
                <?php echo $this->Form->input('id'); ?>
                <div class="form-group">
                    <label for="ItemsgastoDescripcion">Nombre Item</label>
                    <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre del Item')); ?>
                </div>
	</fieldset><br>
<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
</div>