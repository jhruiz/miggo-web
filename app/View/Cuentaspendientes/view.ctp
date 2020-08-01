<div class="cuentaspendientes view">
<h2><?php echo __('Cuentaspendiente'); ?></h2>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '29', 'id' => 'menuvert'))?>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($cuentaspendiente['Cuentaspendiente']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Documento'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cuentaspendiente['Documento']['codigo'], array('controller' => 'documentos', 'action' => 'view', $cuentaspendiente['Documento']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Producto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cuentaspendiente['Producto']['descripcion'], array('controller' => 'productos', 'action' => 'view', $cuentaspendiente['Producto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deposito'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cuentaspendiente['Deposito']['descripcion'], array('controller' => 'depositos', 'action' => 'view', $cuentaspendiente['Deposito']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Costoproducto'); ?></dt>
		<dd>
			<?php echo h($cuentaspendiente['Cuentaspendiente']['costoproducto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cantidad'); ?></dt>
		<dd>
			<?php echo h($cuentaspendiente['Cuentaspendiente']['cantidad']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Proveedore'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cuentaspendiente['Proveedore']['nombre'], array('controller' => 'proveedores', 'action' => 'view', $cuentaspendiente['Proveedore']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Numerofactura'); ?></dt>
		<dd>
			<?php echo h($cuentaspendiente['Cuentaspendiente']['numerofactura']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($cuentaspendiente['Cuentaspendiente']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cuentaspendiente['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $cuentaspendiente['Usuario']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cuentaspendiente'), array('action' => 'edit', $cuentaspendiente['Cuentaspendiente']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Cuentaspendiente'), array('action' => 'delete', $cuentaspendiente['Cuentaspendiente']['id']), null, __('Are you sure you want to delete # %s?', $cuentaspendiente['Cuentaspendiente']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Cuentaspendientes'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cuentaspendiente'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Documentos'), array('controller' => 'documentos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Documento'), array('controller' => 'documentos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Depositos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Deposito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Proveedores'), array('controller' => 'proveedores', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Proveedore'), array('controller' => 'proveedores', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
