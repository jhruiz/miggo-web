<?php $this->layout='inicio'; ?>
<div class="ciudades view">
<legend><h2><b><?php echo __('Ciudad'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($ciudade['Ciudade']['descripcion']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('País'); ?></dt>
		<dd>
			<?php echo $this->Html->link($ciudade['Paise']['descripcion'], array('controller' => 'paises', 'action' => 'view', $ciudade['Paise']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Ciudad'), array('action' => 'edit', $ciudade['Ciudade']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Ciudades'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Ciudad'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Paises'), array('controller' => 'paises', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo País'), array('controller' => 'paises', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Depósitos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Depósito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
	</ul>
</div>