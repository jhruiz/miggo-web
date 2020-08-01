<?php $this->layout='inicio'; ?>
<div class="licenciasUsuarios view">
<legend><h2><b><?php echo __('Licencia - Usuario'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Inicio de Licencia'); ?></dt>
		<dd>
			<?php echo h($licenciasUsuario['LicenciasUsuario']['fechainicio']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Fin de Licencia'); ?></dt>
		<dd>
			<?php echo h($licenciasUsuario['LicenciasUsuario']['fechafin']); ?>
			&nbsp;
                </dd><br>
		<dt class="text-info"><?php echo __('Días Licencia'); ?></dt>
		<dd>
			<?php echo $this->Html->link($licenciasUsuario['Licencia']['cantidaddias'], array('controller' => 'licencias', 'action' => 'view', $licenciasUsuario['Licencia']['id'])); ?>
			&nbsp;
                </dd><br>
		<dt class="text-info"><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo $this->Html->link($licenciasUsuario['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $licenciasUsuario['Usuario']['id'])); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo $this->Html->link($licenciasUsuario['Estado']['descripcion'], array('controller' => 'estados', 'action' => 'view', $licenciasUsuario['Estado']['id'])); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Código Licencia'); ?></dt>
		<dd>
			<?php echo h($licenciasUsuario['LicenciasUsuario']['codigo']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Licencia - Usuario'), array('action' => 'edit', $licenciasUsuario['LicenciasUsuario']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Licencias - Usuarios'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Licencia - Usuario'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Licencias'), array('controller' => 'licencias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Licencia'), array('controller' => 'licencias', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
