<?php $this->layout='inicio'; ?>
<div class="perfiles view">
<legend><h2><b><?php echo __('Perfil'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Nombre del Perfil'); ?></dt>
		<dd>
			<?php echo h($perfile['Perfile']['descripcion']); ?>
			&nbsp;
		</dd><br>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Perfil'), array('action' => 'edit', $perfile['Perfile']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Perfiles'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Perfil'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
