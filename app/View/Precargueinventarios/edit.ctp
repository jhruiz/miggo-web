<div class="precargueinventarios form">
<?php echo $this->Form->create('Precargueinventario'); ?>
	<fieldset>
		<legend><?php echo __('Edit Precargueinventario'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('producto_id');
		echo $this->Form->input('deposito_id');
		echo $this->Form->input('costoproducto');
		echo $this->Form->input('cantidad');
		echo $this->Form->input('preciomaximo');
		echo $this->Form->input('preciominimo');
		echo $this->Form->input('precioventa');
		echo $this->Form->input('usuario_id');
		echo $this->Form->input('estado_id');
		echo $this->Form->input('proveedore_id');
		echo $this->Form->input('tipopago_id');
		echo $this->Form->input('numerofactura');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Precargueinventario.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Precargueinventario.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Precargueinventarios'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Depositos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Deposito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Estados'), array('controller' => 'estados', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Estado'), array('controller' => 'estados', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Proveedores'), array('controller' => 'proveedores', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Proveedore'), array('controller' => 'proveedores', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipopagos'), array('controller' => 'tipopagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipopago'), array('controller' => 'tipopagos', 'action' => 'add')); ?> </li>
	</ul>
</div>
