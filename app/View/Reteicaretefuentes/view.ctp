<?php $this->layout='inicio'; ?>
<div class="cargueinventarios view">
<legend><h2><?php echo __('Retefuente - Reteica'); ?></h2></legend>
	<dl>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($reteicaretefuentes['Reteicaretefuente']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Porcentaje'); ?></dt>
		<dd>
			<?php echo h($reteicaretefuentes['Reteicaretefuente']['porcentaje'] . '%'); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipo'); ?></dt>
		<dd>
			<?php echo $reteicaretefuentes['Reteicaretefuente']['reteica'] == '1' ? h('Reteica') : h('Retefuente'); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha'); ?></dt>
		<dd>
			<?php echo h($reteicaretefuentes['Reteicaretefuente']['created']); ?>
			&nbsp;
		</dd>		
	</dl>
</div>
<div class="actions">
	<legend><h3><?php echo __('Acciones'); ?></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Retefuente - Reteica'), array('action' => 'edit', $reteicaretefuentes['Reteicaretefuente']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Retefuente - Reteica'), array('action' => 'index')); ?> </li>
	</ul>
</div>
