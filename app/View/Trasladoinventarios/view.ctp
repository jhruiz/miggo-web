<div class="trasladoinventarios view">
<h2><?php echo __('Trasladoinventario'); ?></h2>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '38', 'id' => 'menuvert'))?>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($trasladoinventario['Trasladoinventario']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Producto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($trasladoinventario['Producto']['descripcion'], array('controller' => 'productos', 'action' => 'view', $trasladoinventario['Producto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Depositoorigen Id'); ?></dt>
		<dd>
			<?php echo h($trasladoinventario['Trasladoinventario']['depositoorigen_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Depositodestino Id'); ?></dt>
		<dd>
			<?php echo h($trasladoinventario['Trasladoinventario']['depositodestino_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cantidadtraslado'); ?></dt>
		<dd>
			<?php echo h($trasladoinventario['Trasladoinventario']['cantidadtraslado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo $this->Html->link($trasladoinventario['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $trasladoinventario['Usuario']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Empresa'); ?></dt>
		<dd>
			<?php echo $this->Html->link($trasladoinventario['Empresa']['nombre'], array('controller' => 'empresas', 'action' => 'view', $trasladoinventario['Empresa']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($trasladoinventario['Trasladoinventario']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Trasladoinventario'), array('action' => 'edit', $trasladoinventario['Trasladoinventario']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Trasladoinventario'), array('action' => 'delete', $trasladoinventario['Trasladoinventario']['id']), null, __('Are you sure you want to delete # %s?', $trasladoinventario['Trasladoinventario']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Trasladoinventarios'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Trasladoinventario'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Empresas'), array('controller' => 'empresas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Empresa'), array('controller' => 'empresas', 'action' => 'add')); ?> </li>
	</ul>
</div>
