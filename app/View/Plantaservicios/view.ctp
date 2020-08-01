<?php $this->layout='inicio'; ?>
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
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Planta Servicio'), array('action' => 'edit', $plantaservicio['Plantaservicio']['id'])); ?> </li>
	</ul>
</div>
