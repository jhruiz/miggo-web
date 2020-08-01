<div class="facturasdetalles form">
<?php echo $this->Form->create('Facturasdetalle'); ?>
	<fieldset>
		<legend><?php echo __('Edit Facturasdetalle'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('factura_id');
		echo $this->Form->input('deposito_id');
		echo $this->Form->input('producto_id');
		echo $this->Form->input('cantidad');
		echo $this->Form->input('costoventa');
		echo $this->Form->input('costototal');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Facturasdetalle.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Facturasdetalle.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Facturasdetalles'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Facturas'), array('controller' => 'facturas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Factura'), array('controller' => 'facturas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Depositos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Deposito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipopagos'), array('controller' => 'tipopagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipopago'), array('controller' => 'tipopagos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
	</ul>
</div>
