<?php $this->layout = 'inicio';?>
<div class="tipovehiculos view">
<legend><h2><b><?php echo __('Tipos de Vehículos'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Tipo de Vehículo'); ?></dt>
		<dd>
			<?php echo h($tipovehiculo['Tipovehiculo']['descripcion']); ?>
			&nbsp;
		</dd><br>
	</dl>
</div>