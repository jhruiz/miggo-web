<?php $this->layout='inicio'; ?>
<div class="cargueinventarios form">
<?php echo $this->Form->create('Cargueinventario'); ?>
	<fieldset>
		<legend><?php echo __('Edit Cargueinventario'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('producto_id');
		echo $this->Form->input('deposito_id');
		echo $this->Form->input('costoproducto', array('label' => 'Valor'));
		echo $this->Form->input('existenciaactual', array('label' => 'Existencia Actual'));
		echo $this->Form->input('preciomaximo', array('label' => 'Precio Máximo'));
		echo $this->Form->input('preciominimo', array('label' => 'Precio Mínimo'));
		echo $this->Form->input('precioventa', array('label' => 'Precio de Venta');
		echo $this->Form->input('impuesto_id');
		echo $this->Form->input('usuario_id');
		echo $this->Form->input('estado_id');
		echo $this->Form->input('tipopago_id');
	?>
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div>
<div class="actions">
	<legend><h3><?php echo __('Acciones'); ?></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista de Cargue Inventarios'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Lista Productos'), array('controller' => 'productos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Producto'), array('controller' => 'productos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Depositos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Deposito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Impuestos'), array('controller' => 'impuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Impuesto'), array('controller' => 'impuestos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Tipo Pagos'), array('controller' => 'tipopagos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Tipo Pago'), array('controller' => 'tipopagos', 'action' => 'add')); ?> </li>
	</ul>
</div>
