<?php $this->layout='inicio'; ?>
<div class="paises form">
<?php echo $this->Form->create('Paise'); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Pais'); ?></b></h2></legend>
                <div class="form-group form-inline">
                    <?php echo $this->Form->input('descripcion', array('label' => 'Nombre Pais', 'class' => 'form-control', 'placeholder' => 'Nombre del Pais')); ?>
                </div>
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>

		<li><?php echo $this->Html->link(__('Lista Paises'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Ciudades'), array('controller' => 'ciudades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Ciudad'), array('controller' => 'ciudades', 'action' => 'add')); ?> </li>
	</ul>
</div>
