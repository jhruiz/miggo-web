<div class="prefacturasdetalles form">
<?php echo $this->Form->create('Prefacturasdetalle'); ?>
	<fieldset>
		<legend><?php echo __('Add Prefacturasdetalle'); ?></legend>
	<?php
		echo $this->Form->input('cantidad');
		echo $this->Form->input('costoventa');
		echo $this->Form->input('cargueinventario_id');
		echo $this->Form->input('prefactura_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Prefacturasdetalles'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Cargueinventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargueinventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Prefacturas'), array('controller' => 'prefacturas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prefactura'), array('controller' => 'prefacturas', 'action' => 'add')); ?> </li>
	</ul>
</div>
