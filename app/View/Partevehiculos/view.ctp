<?php $this->layout='inicio'; ?>
<div class="partevehiculos view">
<legend><h2><b><?php echo __('Partes de Vehículo'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Parte del vehículo'); ?></dt>
		<dd>
			<?php echo h($partevehiculo['Partevehiculo']['descripcion']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Extra'); ?></dt>
		<dd>
			<?php echo h(!empty($partevehiculo['Partevehiculo']['extra']) ? "SI" : "NO"); ?>
			&nbsp;
		</dd><br>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Parte Vehículo'), array('action' => 'edit', $partevehiculo['Partevehiculo']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Partes Vehículo'), array('action' => 'index')); ?> </li>
	</ul>
</div>
