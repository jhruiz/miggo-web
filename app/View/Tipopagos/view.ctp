<?php $this->layout='inicio'; ?>
<div class="tipopagos view">
<legend><h2><b><?php echo __('Tipo de Pago'); ?></b></h2></legend>
	<dl>
		<dt class="text-info"><?php echo __('Nombre del Tipo de Pago'); ?></dt>
		<dd>
			<?php echo h($tipopago['Tipopago']['descripcion']); ?>
			&nbsp;
                </dd><br>
		<dt class="text-info"><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo $this->Html->link($tipopago['Estado']['descripcion'], array('controller' => 'estados', 'action' => 'view', $tipopago['Estado']['id'])); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Cuenta'); ?></dt>
		<dd>
			<?php echo h(!empty($listCtas[$tipopago['Tipopago']['cuenta_id']]) ? $listCtas[$tipopago['Tipopago']['cuenta_id']] : "SIN ASIGNAR"); ?>
			&nbsp;
		</dd><br>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Tipo de Pago'), array('action' => 'edit', $tipopago['Tipopago']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Tipos de Pago'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Tipo de Pago'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Cargueinventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cargue Inventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Descargue Inventarios'), array('controller' => 'descargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Descargue Inventario'), array('controller' => 'descargueinventarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
