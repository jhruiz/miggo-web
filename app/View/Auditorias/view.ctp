<?php $this->layout='inicio'; ?>
<div class="auditorias view">
<legend><h2><b><?php echo __('Auditorias'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo $this->Html->link($auditoria['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $auditoria['Usuario']['id'])); ?>
			&nbsp;
                </dd><br>
		<dt class="text-info"><?php echo __('Acción'); ?></dt>
		<dd>
			<?php echo h($auditoria['Auditoria']['accion']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Descripción'); ?></dt>
		<dd>
			<?php echo h($auditoria['Auditoria']['descripcion']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Fecha'); ?></dt>
		<dd>
			<?php echo h($auditoria['Auditoria']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Auditorias'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
