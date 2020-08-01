<?php $this->layout='inicio'; ?>
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
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Marca'), array('action' => 'edit', $marcavehiculo['Marcavehiculo']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Marcas'), array('action' => 'index')); ?> </li>
	</ul>
</div>
