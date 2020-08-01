<div class="detalledocumentos view">
<h2><?php echo __('Detalledocumento'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($detalledocumento['Detalledocumento']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Producto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($detalledocumento['Producto']['descripcion'], array('controller' => 'productos', 'action' => 'view', $detalledocumento['Producto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Depositoorigen Id'); ?></dt>
		<dd>
			<?php echo h($detalledocumento['Detalledocumento']['depositoorigen_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Depositodestino Id'); ?></dt>
		<dd>
			<?php echo h($detalledocumento['Detalledocumento']['depositodestino_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Costoproducto'); ?></dt>
		<dd>
			<?php echo h($detalledocumento['Detalledocumento']['costoproducto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cantidad'); ?></dt>
		<dd>
			<?php echo h($detalledocumento['Detalledocumento']['cantidad']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Preciomaximo'); ?></dt>
		<dd>
			<?php echo h($detalledocumento['Detalledocumento']['preciomaximo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Preciominimo'); ?></dt>
		<dd>
			<?php echo h($detalledocumento['Detalledocumento']['preciominimo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Precioventa'); ?></dt>
		<dd>
			<?php echo h($detalledocumento['Detalledocumento']['precioventa']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Proveedore'); ?></dt>
		<dd>
			<?php echo $this->Html->link($detalledocumento['Proveedore']['nombre'], array('controller' => 'proveedores', 'action' => 'view', $detalledocumento['Proveedore']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipopago'); ?></dt>
		<dd>
			<?php echo $this->Html->link($detalledocumento['Tipopago']['descripcion'], array('controller' => 'tipopagos', 'action' => 'view', $detalledocumento['Tipopago']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Numerofactura'); ?></dt>
		<dd>
			<?php echo h($detalledocumento['Detalledocumento']['numerofactura']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Detalledocumento'), array('action' => 'edit', $detalledocumento['Detalledocumento']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Detalledocumento'), array('action' => 'delete', $detalledocumento['Detalledocumento']['id']), null, __('Are you sure you want to delete # %s?', $detalledocumento['Detalledocumento']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Detalledocumentos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Detalledocumento'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Proveedores'), array('controller' => 'proveedores', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Proveedore'), array('controller' => 'proveedores', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipopagos'), array('controller' => 'tipopagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipopago'), array('controller' => 'tipopagos', 'action' => 'add')); ?> </li>
	</ul>
</div>
