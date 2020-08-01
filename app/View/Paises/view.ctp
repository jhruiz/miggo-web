<?php $this->layout='inicio'; ?>
<div class="paises view">
<legend><h2><b><?php echo __('Pais'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Nombre Pais'); ?></dt>
		<dd>
			<?php echo h($paise['Paise']['descripcion']); ?>
			&nbsp;
		</dd><br>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Pais'), array('action' => 'edit', $paise['Paise']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Paises'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Pais'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Ciudades'), array('controller' => 'ciudades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Ciudad'), array('controller' => 'ciudades', 'action' => 'add')); ?> </li>
	</ul>
</div>
