<div class="facturasdetalles index">
	<h2><?php echo __('Facturasdetalles'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('factura_id'); ?></th>
			<th><?php echo $this->Paginator->sort('deposito_id'); ?></th>
			<th><?php echo $this->Paginator->sort('producto_id'); ?></th>
			<th><?php echo $this->Paginator->sort('cantidad'); ?></th>
			<th><?php echo $this->Paginator->sort('costoventa'); ?></th>
			<th><?php echo $this->Paginator->sort('costototal'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($facturasdetalles as $facturasdetalle): ?>
	<tr>
		<td><?php echo h($facturasdetalle['Facturasdetalle']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($facturasdetalle['Factura']['id'], array('controller' => 'facturas', 'action' => 'view', $facturasdetalle['Factura']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($facturasdetalle['Deposito']['descripcion'], array('controller' => 'depositos', 'action' => 'view', $facturasdetalle['Deposito']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($facturasdetalle['Producto']['descripcion'], array('controller' => 'productos', 'action' => 'view', $facturasdetalle['Producto']['id'])); ?>
		</td>
		<td><?php echo h($facturasdetalle['Facturasdetalle']['cantidad']); ?>&nbsp;</td>
		<td><?php echo h($facturasdetalle['Facturasdetalle']['costoventa']); ?>&nbsp;</td>
		<td><?php echo h($facturasdetalle['Facturasdetalle']['costototal']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $facturasdetalle['Facturasdetalle']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $facturasdetalle['Facturasdetalle']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $facturasdetalle['Facturasdetalle']['id']), null, __('Are you sure you want to delete # %s?', $facturasdetalle['Facturasdetalle']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Facturasdetalle'), array('action' => 'add')); ?></li>
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
