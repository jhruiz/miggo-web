<div class="cuentasclientes form">
<?php echo $this->Form->create('Cuentascliente'); ?>
	<fieldset>
		<legend><?php echo __('Edit Cuentascliente'); ?></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '35', 'id' => 'menuvert'))?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('documento_id');
		echo $this->Form->input('deposito_id');
		echo $this->Form->input('cliente_id');
		echo $this->Form->input('usuario_id');
		echo $this->Form->input('empresa_id');
		echo $this->Form->input('totalobligacion');
		echo $this->Form->input('factura_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Cuentascliente.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Cuentascliente.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Cuentasclientes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Documentos'), array('controller' => 'documentos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Documento'), array('controller' => 'documentos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Depositos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Deposito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Empresas'), array('controller' => 'empresas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Empresa'), array('controller' => 'empresas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Facturas'), array('controller' => 'facturas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Factura'), array('controller' => 'facturas', 'action' => 'add')); ?> </li>
	</ul>
</div>
