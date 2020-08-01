<div class="prefacturasdetalles view">
<h2><?php echo __('Prefacturasdetalle'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($prefacturasdetalle['Prefacturasdetalle']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cantidad'); ?></dt>
		<dd>
			<?php echo h($prefacturasdetalle['Prefacturasdetalle']['cantidad']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Costoventa'); ?></dt>
		<dd>
			<?php echo h($prefacturasdetalle['Prefacturasdetalle']['costoventa']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cargueinventario'); ?></dt>
		<dd>
			<?php echo $this->Html->link($prefacturasdetalle['Cargueinventario']['id'], array('controller' => 'cargueinventarios', 'action' => 'view', $prefacturasdetalle['Cargueinventario']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Prefactura'); ?></dt>
		<dd>
			<?php echo $this->Html->link($prefacturasdetalle['Prefactura']['id'], array('controller' => 'prefacturas', 'action' => 'view', $prefacturasdetalle['Prefactura']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Prefacturasdetalle'), array('action' => 'edit', $prefacturasdetalle['Prefacturasdetalle']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Prefacturasdetalle'), array('action' => 'delete', $prefacturasdetalle['Prefacturasdetalle']['id']), null, __('Are you sure you want to delete # %s?', $prefacturasdetalle['Prefacturasdetalle']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Prefacturasdetalles'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prefacturasdetalle'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargueinventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargueinventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Prefacturas'), array('controller' => 'prefacturas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prefactura'), array('controller' => 'prefacturas', 'action' => 'add')); ?> </li>
	</ul>
</div>
