<?php $this->layout = 'inicio';?>
<div class="plantaservicios view">
<legend><h2><b><?php echo __('Planta de servicio'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Nombre Planta de Servicio'); ?></dt>
		<dd>
			<?php echo h($plantaservicio['Plantaservicio']['descripcion']); ?>
			&nbsp;
		</dd><br>
	</dl>
</div>