<div class="prefacturasdetalles index">
	<h2><?php echo __('Prefacturasdetalles'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('cantidad'); ?></th>
			<th><?php echo $this->Paginator->sort('costoventa'); ?></th>
			<th><?php echo $this->Paginator->sort('cargueinventario_id'); ?></th>
			<th><?php echo $this->Paginator->sort('prefactura_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($prefacturasdetalles as $prefacturasdetalle): ?>
	<tr>
		<td><?php echo h($prefacturasdetalle['Prefacturasdetalle']['id']); ?>&nbsp;</td>
		<td><?php echo h($prefacturasdetalle['Prefacturasdetalle']['cantidad']); ?>&nbsp;</td>
		<td><?php echo h($prefacturasdetalle['Prefacturasdetalle']['costoventa']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($prefacturasdetalle['Cargueinventario']['id'], array('controller' => 'cargueinventarios', 'action' => 'view', $prefacturasdetalle['Cargueinventario']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($prefacturasdetalle['Prefactura']['id'], array('controller' => 'prefacturas', 'action' => 'view', $prefacturasdetalle['Prefactura']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $prefacturasdetalle['Prefacturasdetalle']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $prefacturasdetalle['Prefacturasdetalle']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $prefacturasdetalle['Prefacturasdetalle']['id']), null, __('Are you sure you want to delete # %s?', $prefacturasdetalle['Prefacturasdetalle']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Prefacturasdetalle'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Cargueinventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargueinventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Prefacturas'), array('controller' => 'prefacturas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prefactura'), array('controller' => 'prefacturas', 'action' => 'add')); ?> </li>
	</ul>
</div>
