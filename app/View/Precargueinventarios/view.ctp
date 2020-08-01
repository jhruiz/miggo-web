<div class="precargueinventarios view">
<h2><?php echo __('Precargueinventario'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($precargueinventario['Precargueinventario']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Producto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($precargueinventario['Producto']['descripcion'], array('controller' => 'productos', 'action' => 'view', $precargueinventario['Producto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deposito'); ?></dt>
		<dd>
			<?php echo $this->Html->link($precargueinventario['Deposito']['descripcion'], array('controller' => 'depositos', 'action' => 'view', $precargueinventario['Deposito']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Costoproducto'); ?></dt>
		<dd>
			<?php echo h($precargueinventario['Precargueinventario']['costoproducto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cantidad'); ?></dt>
		<dd>
			<?php echo h($precargueinventario['Precargueinventario']['cantidad']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Preciomaximo'); ?></dt>
		<dd>
			<?php echo h($precargueinventario['Precargueinventario']['preciomaximo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Preciominimo'); ?></dt>
		<dd>
			<?php echo h($precargueinventario['Precargueinventario']['preciominimo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Precioventa'); ?></dt>
		<dd>
			<?php echo h($precargueinventario['Precargueinventario']['precioventa']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo $this->Html->link($precargueinventario['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $precargueinventario['Usuario']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo $this->Html->link($precargueinventario['Estado']['descripcion'], array('controller' => 'estados', 'action' => 'view', $precargueinventario['Estado']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Proveedore'); ?></dt>
		<dd>
			<?php echo $this->Html->link($precargueinventario['Proveedore']['nombre'], array('controller' => 'proveedores', 'action' => 'view', $precargueinventario['Proveedore']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipopago'); ?></dt>
		<dd>
			<?php echo $this->Html->link($precargueinventario['Tipopago']['descripcion'], array('controller' => 'tipopagos', 'action' => 'view', $precargueinventario['Tipopago']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Numerofactura'); ?></dt>
		<dd>
			<?php echo h($precargueinventario['Precargueinventario']['numerofactura']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Precargueinventario'), array('action' => 'edit', $precargueinventario['Precargueinventario']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Precargueinventario'), array('action' => 'delete', $precargueinventario['Precargueinventario']['id']), null, __('Are you sure you want to delete # %s?', $precargueinventario['Precargueinventario']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Precargueinventarios'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Precargueinventario'), array('action' => 'add')); ?> </li>
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
