<?php $this->layout='inicio'; ?>
<div class="descargueinventarios view">
<h2><?php echo __('Descargueinventario'); ?></h2>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '18', 'id' => 'menuvert'))?>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($descargueinventario['Descargueinventario']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Producto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($descargueinventario['Producto']['descripcion'], array('controller' => 'productos', 'action' => 'view', $descargueinventario['Producto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deposito'); ?></dt>
		<dd>
			<?php echo $this->Html->link($descargueinventario['Deposito']['descripcion'], array('controller' => 'depositos', 'action' => 'view', $descargueinventario['Deposito']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cantidaddescargue'); ?></dt>
		<dd>
			<?php echo h($descargueinventario['Descargueinventario']['cantidaddescargue']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo $this->Html->link($descargueinventario['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $descargueinventario['Usuario']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($descargueinventario['Descargueinventario']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipopago'); ?></dt>
		<dd>
			<?php echo $this->Html->link($descargueinventario['Tipopago']['descripcion'], array('controller' => 'tipopagos', 'action' => 'view', $descargueinventario['Tipopago']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Descargueinventario'), array('action' => 'edit', $descargueinventario['Descargueinventario']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Descargueinventario'), array('action' => 'delete', $descargueinventario['Descargueinventario']['id']), null, __('Are you sure you want to delete # %s?', $descargueinventario['Descargueinventario']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Descargueinventarios'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Descargueinventario'), array('action' => 'add')); ?> </li>
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
