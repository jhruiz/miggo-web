<div class="trasladoinventarios form">
<?php echo $this->Form->create('Trasladoinventario'); ?>
	<fieldset>
		<legend><?php echo __('Edit Trasladoinventario'); ?></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '38', 'id' => 'menuvert'))?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('producto_id');
		echo $this->Form->input('depositoorigen_id');
		echo $this->Form->input('depositodestino_id');
		echo $this->Form->input('cantidadtraslado');
		echo $this->Form->input('usuario_id');
		echo $this->Form->input('empresa_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Trasladoinventario.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Trasladoinventario.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Trasladoinventarios'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Empresas'), array('controller' => 'empresas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Empresa'), array('controller' => 'empresas', 'action' => 'add')); ?> </li>
	</ul>
</div>
