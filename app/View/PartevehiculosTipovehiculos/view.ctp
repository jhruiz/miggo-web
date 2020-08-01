<?php $this->layout='inicio'; ?>
<div class="partevehiculostipovehiculos view">
<legend><h2><b><?php echo __('Tipo Vehículo - Parte Vehículo'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Tipo vehículo'); ?></dt>
		<dd>
			<?php echo h($arrTipParVehiculo['0']['TV']['descripcion']); ?>
			&nbsp;
		</dd><br>
                
		<dt class="text-info"><?php echo __('Parte vehículo'); ?></dt>
		<dd>
			<?php echo h($arrTipParVehiculo['0']['PV']['descripcion']); ?>
			&nbsp;
		</dd><br>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Tipo Vehículo - Parte Vehículo'), array('action' => 'edit', $arrTipParVehiculo['0']['PartevehiculosTipovehiculo']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista  Tipos Vehículos - Partes Vehículos'), array('action' => 'index')); ?> </li>
	</ul>
</div>
