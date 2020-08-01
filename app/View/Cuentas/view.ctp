<?php $this->layout='inicio'; ?>
<div class="cuentas view">
<legend><h2><b><?php echo __('Cuenta'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '39', 'id' => 'menuvert'))?>
	<dl>
		<dt class="text-info"><?php echo __('Nombre Cuenta'); ?></dt>
		<dd>
			<?php echo h($cuenta['Cuenta']['descripcion']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Número de Cuenta'); ?></dt>
		<dd>
			<?php echo h($cuenta['Cuenta']['numerocuenta']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Saldo'); ?></dt>
		<dd>
			<?php echo h(number_format($cuenta['Cuenta']['saldo'],2)); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Fecha Creación'); ?></dt>
		<dd>
			<?php echo h($cuenta['Cuenta']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Cuenta'), array('action' => 'edit', $cuenta['Cuenta']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Cuentas'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nueva Cuenta'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Gastos'), array('controller' => 'gastos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Gasto'), array('controller' => 'gastos', 'action' => 'add')); ?> </li>
	</ul>
</div>