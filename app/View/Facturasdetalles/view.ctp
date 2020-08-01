<div class="facturasdetalles view">
<h2><?php echo __('Facturasdetalle'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($facturasdetalle['Facturasdetalle']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Factura'); ?></dt>
		<dd>
			<?php echo $this->Html->link($facturasdetalle['Factura']['id'], array('controller' => 'facturas', 'action' => 'view', $facturasdetalle['Factura']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deposito'); ?></dt>
		<dd>
			<?php echo $this->Html->link($facturasdetalle['Deposito']['descripcion'], array('controller' => 'depositos', 'action' => 'view', $facturasdetalle['Deposito']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Producto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($facturasdetalle['Producto']['descripcion'], array('controller' => 'productos', 'action' => 'view', $facturasdetalle['Producto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cantidad'); ?></dt>
		<dd>
			<?php echo h($facturasdetalle['Facturasdetalle']['cantidad']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Costoventa'); ?></dt>
		<dd>
			<?php echo h($facturasdetalle['Facturasdetalle']['costoventa']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Costototal'); ?></dt>
		<dd>
			<?php echo h($facturasdetalle['Facturasdetalle']['costototal']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Facturasdetalle'), array('action' => 'edit', $facturasdetalle['Facturasdetalle']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Facturasdetalle'), array('action' => 'delete', $facturasdetalle['Facturasdetalle']['id']), null, __('Are you sure you want to delete # %s?', $facturasdetalle['Facturasdetalle']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Facturasdetalles'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Facturasdetalle'), array('action' => 'add')); ?> </li>
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
