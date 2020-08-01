<div class="cuentasclientes view">
<h2><?php echo __('Cuentascliente'); ?></h2>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '35', 'id' => 'menuvert'))?>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($cuentascliente['Cuentascliente']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Documento'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cuentascliente['Documento']['codigo'], array('controller' => 'documentos', 'action' => 'view', $cuentascliente['Documento']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deposito'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cuentascliente['Deposito']['descripcion'], array('controller' => 'depositos', 'action' => 'view', $cuentascliente['Deposito']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cuentascliente['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $cuentascliente['Cliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($cuentascliente['Cuentascliente']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cuentascliente['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $cuentascliente['Usuario']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Empresa'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cuentascliente['Empresa']['nombre'], array('controller' => 'empresas', 'action' => 'view', $cuentascliente['Empresa']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Totalobligacion'); ?></dt>
		<dd>
			<?php echo h($cuentascliente['Cuentascliente']['totalobligacion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Factura'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cuentascliente['Factura']['id'], array('controller' => 'facturas', 'action' => 'view', $cuentascliente['Factura']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cuentascliente'), array('action' => 'edit', $cuentascliente['Cuentascliente']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Cuentascliente'), array('action' => 'delete', $cuentascliente['Cuentascliente']['id']), null, __('Are you sure you want to delete # %s?', $cuentascliente['Cuentascliente']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Cuentasclientes'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cuentascliente'), array('action' => 'add')); ?> </li>
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
