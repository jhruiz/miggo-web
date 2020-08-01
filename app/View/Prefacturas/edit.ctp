<div class="prefacturas form">
<?php echo $this->Form->create('Prefactura'); ?>
	<fieldset>
		<legend><?php echo __('Edit Prefactura'); ?></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '30', 'id' => 'menuvert'))?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('usuario_id');
		echo $this->Form->input('cliente_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Prefactura.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Prefactura.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Prefacturas'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Prefacturasdetalles'), array('controller' => 'prefacturasdetalles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prefacturasdetalle'), array('controller' => 'prefacturasdetalles', 'action' => 'add')); ?> </li>
	</ul>
</div>
