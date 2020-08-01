<?php $this->layout='inicio'; ?>
<div class="proveedores view">
<legend><h2><b><?php echo __('Proveedores'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '27', 'id' => 'menuvert'))?>
	<dl>
		<dt class="text-info"><?php echo __('Nit'); ?></dt>
		<dd>
			<?php echo h($proveedore['Proveedore']['nit']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($proveedore['Proveedore']['nombre']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Dirección'); ?></dt>
		<dd>
			<?php echo h($proveedore['Proveedore']['direccion']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Teléfono'); ?></dt>
		<dd>
			<?php echo h($proveedore['Proveedore']['telefono']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Ciudad'); ?></dt>
		<dd>
			<?php echo $this->Html->link($proveedore['Ciudade']['descripcion'], array('controller' => 'ciudades', 'action' => 'view', $proveedore['Ciudade']['id'])); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Página Web'); ?></dt>
		<dd>
			<?php echo h($proveedore['Proveedore']['paginaweb']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('E-mail'); ?></dt>
		<dd>
			<?php echo h($proveedore['Proveedore']['email']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Celular'); ?></dt>
		<dd>
			<?php echo h($proveedore['Proveedore']['celular']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Días Crédito'); ?></dt>
		<dd>
			<?php echo h($proveedore['Proveedore']['diascredito']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Límite Crédito'); ?></dt>
		<dd>
			$ <?php echo h(number_format($proveedore['Proveedore']['limitecredito'],2)); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Observaciones'); ?></dt>
		<dd>
			<?php echo h($proveedore['Proveedore']['observaciones']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo $this->Html->link($proveedore['Usuario']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $proveedore['Usuario']['id'])); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo $this->Html->link($proveedore['Estado']['descripcion'], array('controller' => 'estados', 'action' => 'view', $proveedore['Estado']['id'])); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Fecha'); ?></dt>
		<dd>
			<?php echo h($proveedore['Proveedore']['created']); ?>
			&nbsp;
		</dd><br>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Proveedor'), array('action' => 'edit', $proveedore['Proveedore']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Proveedores'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Proveedor'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Ciudades'), array('controller' => 'ciudades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Ciudade'), array('controller' => 'ciudades', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
