<?php $this->layout='inicio'; ?>
<div class="licencias form">
<?php echo $this->Form->create('Licencia'); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Licencia'); ?></b></h2></legend>
                <?php echo $this->Form->input('id'); ?>
                <div class="form-group form-inline">
                    <?php echo $this->Form->input('cantidaddias', array('label' => 'Cantidad Días', 'class' => 'form-control', 'placeholder' => 'Duración de la Licencia')); ?>
                </div>                
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Licencias'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
