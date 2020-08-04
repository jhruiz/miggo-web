<?php $this->layout = 'inicio';?>
<div class="marcavehiculos view">
<legend><h2><b><?php echo __('Marca Vehículo'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Nombre Marca'); ?></dt>
		<dd>
			<?php echo h($marcavehiculo['Marcavehiculo']['descripcion']); ?>
			&nbsp;
		</dd><br>
	</dl>
</div>