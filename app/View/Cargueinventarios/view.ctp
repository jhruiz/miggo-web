<?php $this->layout='inicio'; ?>
<div class="cargueinventarios view">
<legend><h2><?php echo __('Cargueinventario'); ?></h2></legend>
	<dl>
		<dt><?php echo __('Producto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cargueinventario['Producto']['descripcion'], array('controller' => 'productos', 'action' => 'view', $cargueinventario['Producto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deposito'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cargueinventario['Deposito']['descripcion'], array('controller' => 'depositos', 'action' => 'view', $cargueinventario['Deposito']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valor'); ?></dt>
		<dd>
			<?php echo h($cargueinventario['Cargueinventario']['costoproducto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Existencia Actual'); ?></dt>
		<dd>
			<?php echo h($cargueinventario['Cargueinventario']['existenciaactual']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Precio Máximo'); ?></dt>
		<dd>
			<?php echo h($cargueinventario['Cargueinventario']['preciomaximo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Precio Mínimo'); ?></dt>
		<dd>
			<?php echo h($cargueinventario['Cargueinventario']['preciominimo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Precio de Venta'); ?></dt>
		<dd>
			<?php echo h($cargueinventario['Cargueinventario']['precioventa']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Impuesto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cargueinventario['Impuesto']['descripcion'], array('controller' => 'impuestos', 'action' => 'view', $cargueinventario['Impuesto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cargueinventario['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $cargueinventario['Usuario']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha'); ?></dt>
		<dd>
			<?php echo h($cargueinventario['Cargueinventario']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cargueinventario['Estado']['descripcion'], array('controller' => 'estados', 'action' => 'view', $cargueinventario['Estado']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipo de Pago'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cargueinventario['Tipopago']['descripcion'], array('controller' => 'tipopagos', 'action' => 'view', $cargueinventario['Tipopago']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<legend><h3><?php echo __('Acciones'); ?></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Cargue de Inventario'), array('action' => 'edit', $cargueinventario['Cargueinventario']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Cargue de Inventarios'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cargue de Inventario'), array('action' => 'add')); ?> </li>
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
