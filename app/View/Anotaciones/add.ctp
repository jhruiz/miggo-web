<?php $this->layout='inicio'; ?>
<div class="anotaciones form">
<?php echo $this->Form->create('Anotacione'); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Nota'); ?></b></h2></legend>
                <?php echo $this->Form->input('usuario_id', array('type' => 'hidden', 'value' => $usuarioId)); ?>
                
                <div class="form-group form-inline">
                    <?php echo $this->Form->input('cliente_id', array('class' => 'form-control')); ?>
                </div>
                
                <div class="form-group form-inline">
                    <?php echo $this->Form->input('descripcion', array('label' => 'Nota', 'class' => 'form-control', 'placeholder' => 'Agrege la nota para el cliente')); ?>
                </div>                

	</fieldset><br>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>

		<li><?php echo $this->Html->link(__('Lista Anotaciones'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
	</ul>
</div>
