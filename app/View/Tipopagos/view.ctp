<?php $this->layout = 'inicio';?>
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