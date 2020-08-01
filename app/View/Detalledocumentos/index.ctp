<div class="detalledocumentos index">
	<h2><?php echo __('Detalledocumentos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('producto_id'); ?></th>
			<th><?php echo $this->Paginator->sort('depositoorigen_id'); ?></th>
			<th><?php echo $this->Paginator->sort('depositodestino_id'); ?></th>
			<th><?php echo $this->Paginator->sort('costoproducto'); ?></th>
			<th><?php echo $this->Paginator->sort('cantidad'); ?></th>
			<th><?php echo $this->Paginator->sort('preciomaximo'); ?></th>
			<th><?php echo $this->Paginator->sort('preciominimo'); ?></th>
			<th><?php echo $this->Paginator->sort('precioventa'); ?></th>
			<th><?php echo $this->Paginator->sort('proveedore_id'); ?></th>
			<th><?php echo $this->Paginator->sort('tipopago_id'); ?></th>
			<th><?php echo $this->Paginator->sort('numerofactura'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($detalledocumentos as $detalledocumento): ?>
	<tr>
		<td><?php echo h($detalledocumento['Detalledocumento']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($detalledocumento['Producto']['descripcion'], array('controller' => 'productos', 'action' => 'view', $detalledocumento['Producto']['id'])); ?>
		</td>
		<td><?php echo h($detalledocumento['Detalledocumento']['depositoorigen_id']); ?>&nbsp;</td>
		<td><?php echo h($detalledocumento['Detalledocumento']['depositodestino_id']); ?>&nbsp;</td>
		<td><?php echo h($detalledocumento['Detalledocumento']['costoproducto']); ?>&nbsp;</td>
		<td><?php echo h($detalledocumento['Detalledocumento']['cantidad']); ?>&nbsp;</td>
		<td><?php echo h($detalledocumento['Detalledocumento']['preciomaximo']); ?>&nbsp;</td>
		<td><?php echo h($detalledocumento['Detalledocumento']['preciominimo']); ?>&nbsp;</td>
		<td><?php echo h($detalledocumento['Detalledocumento']['precioventa']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($detalledocumento['Proveedore']['nombre'], array('controller' => 'proveedores', 'action' => 'view', $detalledocumento['Proveedore']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($detalledocumento['Tipopago']['descripcion'], array('controller' => 'tipopagos', 'action' => 'view', $detalledocumento['Tipopago']['id'])); ?>
		</td>
		<td><?php echo h($detalledocumento['Detalledocumento']['numerofactura']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $detalledocumento['Detalledocumento']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $detalledocumento['Detalledocumento']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $detalledocumento['Detalledocumento']['id']), null, __('Are you sure you want to delete # %s?', $detalledocumento['Detalledocumento']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Detalledocumento'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Proveedores'), array('controller' => 'proveedores', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Proveedore'), array('controller' => 'proveedores', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipopagos'), array('controller' => 'tipopagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipopago'), array('controller' => 'tipopagos', 'action' => 'add')); ?> </li>
	</ul>
</div>
