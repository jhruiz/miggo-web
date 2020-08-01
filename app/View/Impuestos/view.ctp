<?php $this->layout='inicio'; ?>
<div class="impuestos view">
<legend><h2><b><?php echo __('Impuesto'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '20', 'id' => 'menuvert'))?>
	<dl>
		<dt class="text-info"><?php echo __('Nombre del Impuesto'); ?></dt>
		<dd>
			<?php echo h($impuesto['Impuesto']['descripcion']); ?>
			&nbsp;
		</dd><br>
                
		<dt class="text-info"><?php echo __('Valor del Impuesto'); ?></dt>
		<dd>
			<?php echo h($impuesto['Impuesto']['valor']); ?>
			&nbsp;
		</dd>                
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Impuesto'), array('action' => 'edit', $impuesto['Impuesto']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Impuestos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Impuesto'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Cargue Inventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Cargue Inventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
	</ul>
</div>
