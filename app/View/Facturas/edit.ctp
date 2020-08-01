<div class="facturas form">
<?php echo $this->Form->create('Factura'); ?>
	<fieldset>
		<legend><?php echo __('Edit Factura'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('codigo');
		echo $this->Form->input('cliente_id');
		echo $this->Form->input('empresa_id');
		echo $this->Form->input('usuario_id');
		echo $this->Form->input('fechavence');
		echo $this->Form->input('tipopago_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Factura.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Factura.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Facturas'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Empresas'), array('controller' => 'empresas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Empresa'), array('controller' => 'empresas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipopagos'), array('controller' => 'tipopagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipopago'), array('controller' => 'tipopagos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Facturasdetalles'), array('controller' => 'facturasdetalles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Facturasdetalle'), array('controller' => 'facturasdetalles', 'action' => 'add')); ?> </li>
	</ul>
</div>
