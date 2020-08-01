<?php $this->layout='inicio'; ?>
<div class="itemsgastos form">
<?php echo $this->Form->create('Itemsgasto', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Item'); ?></b></h2></legend>
                <?php echo $this->Form->input('id'); ?>
                <div class="form-group">
                    <label for="ItemsgastoDescripcion">Nombre Item</label>
                    <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre del Item')); ?>
                </div>
	</fieldset><br>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Items'), array('action' => 'index')); ?></li>
	</ul>
</div>
