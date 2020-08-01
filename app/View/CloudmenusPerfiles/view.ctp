<?php $this->layout='inicio'; ?>
<div class="cloudmenusPerfiles view">
<legend><h2><b><?php echo __('Menús - Perfiles'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Menú'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cloudmenusPerfile['Cloudmenu']['descripcion'], array('controller' => 'cloudmenus', 'action' => 'view', $cloudmenusPerfile['Cloudmenu']['id'])); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Perfil'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cloudmenusPerfile['Perfile']['descripcion'], array('controller' => 'perfiles', 'action' => 'view', $cloudmenusPerfile['Perfile']['id'])); ?>
			&nbsp;
		</dd><br>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Menú - Perfil'), array('action' => 'edit', $cloudmenusPerfile['CloudmenusPerfile']['id'])); ?> </li>		
		<li><?php echo $this->Html->link(__('Lista Menú - Perfiles'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Menú - Perfile'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Menús'), array('controller' => 'cloudmenus', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Menú'), array('controller' => 'cloudmenus', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Perfiles'), array('controller' => 'perfiles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Perfil'), array('controller' => 'perfiles', 'action' => 'add')); ?> </li>
	</ul>
</div>
