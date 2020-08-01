<?php $this->layout='inicio'; ?>
<div class="descargueinventarios form">
<?php echo $this->Form->create('Descargueinventario'); ?>
	<fieldset>
		<legend><?php echo __('Edit Descargueinventario'); ?></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '18', 'id' => 'menuvert'))?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('producto_id');
		echo $this->Form->input('deposito_id');
		echo $this->Form->input('cantidaddescargue');
		echo $this->Form->input('usuario_id');
		echo $this->Form->input('tipopago_id');
	?>
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Descargueinventario.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Descargueinventario.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Descargueinventarios'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Depositos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Deposito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipopagos'), array('controller' => 'tipopagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipopago'), array('controller' => 'tipopagos', 'action' => 'add')); ?> </li>
	</ul>
</div>
