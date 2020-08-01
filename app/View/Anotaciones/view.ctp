<?php $this->layout='inicio'; ?>
<div class="anotaciones view">
<legend><h2><b><?php echo __('Notas'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Nota'); ?></dt>
		<dd>
			<?php echo h($anotacione['Anotacione']['descripcion']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo $this->Html->link($anotacione['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $anotacione['Usuario']['id'])); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($anotacione['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $anotacione['Cliente']['id'])); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Fecha'); ?></dt>
		<dd>
			<?php echo h($anotacione['Anotacione']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Notas'), array('action' => 'edit', $anotacione['Anotacione']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Notas'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Nota'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
	</ul>
</div>
