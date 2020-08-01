<?php $this->layout='inicio'; ?>
<div class="tipovehiculos view">
<legend><h2><b><?php echo __('Tipos de Vehículos'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Tipo de Vehículo'); ?></dt>
		<dd>
			<?php echo h($tipovehiculo['Tipovehiculo']['descripcion']); ?>
			&nbsp;
		</dd><br>
	</dl>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Tipo de Vehículo'), array('action' => 'edit', $tipovehiculo['Tipovehiculo']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Tipos de Vehículos'), array('action' => 'index')); ?> </li>
	</ul>
</div>
