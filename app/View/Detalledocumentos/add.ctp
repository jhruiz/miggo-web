<div class="detalledocumentos form">
<?php echo $this->Form->create('Detalledocumento'); ?>
	<fieldset>
		<legend><?php echo __('Add Detalledocumento'); ?></legend>
	<?php
		echo $this->Form->input('producto_id');
		echo $this->Form->input('depositoorigen_id');
		echo $this->Form->input('depositodestino_id');
		echo $this->Form->input('costoproducto');
		echo $this->Form->input('cantidad');
		echo $this->Form->input('preciomaximo');
		echo $this->Form->input('preciominimo');
		echo $this->Form->input('precioventa');
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

		<li><?php echo $this->Html->link(__('List Detalledocumentos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Proveedores'), array('controller' => 'proveedores', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Proveedore'), array('controller' => 'proveedores', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipopagos'), array('controller' => 'tipopagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipopago'), array('controller' => 'tipopagos', 'action' => 'add')); ?> </li>
	</ul>
</div>
