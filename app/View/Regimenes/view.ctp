<?php $this->layout='inicio'; ?>
<div class="regimenes view">
<legend><h2><b><?php echo __('Régimen'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($regimene['Regimene']['descripcion']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Régimen'), array('action' => 'edit', $regimene['Regimene']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Régimen'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Régimen'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Depósitos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Depósito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
	</ul>
</div>
