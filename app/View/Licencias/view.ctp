<?php $this->layout='inicio'; ?>
<div class="licencias view">
<legend><h2><b><?php echo __('Licencia'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Cantidad Días de Licencia'); ?></dt>
		<dd>
			<?php echo h($licencia['Licencia']['cantidaddias']); ?>
			&nbsp;
		</dd><br>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Licencia'), array('action' => 'edit', $licencia['Licencia']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Licencias'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Licencia'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
