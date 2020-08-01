<div class="utilidades form">
<?php echo $this->Form->create('Utilidade'); ?>
	<fieldset>
		<legend><?php echo __('Edit Utilidade'); ?></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '34', 'id' => 'menuvert'))?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('cargueinventario_id');
		echo $this->Form->input('cantidad');
		echo $this->Form->input('precioventa');
		echo $this->Form->input('utilidadbruta');
		echo $this->Form->input('utilidadporcentual');
		echo $this->Form->input('empresa_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Utilidade.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Utilidade.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Utilidades'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Cargueinventarios'), array('controller' => 'cargueinventarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargueinventario'), array('controller' => 'cargueinventarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Empresas'), array('controller' => 'empresas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Empresa'), array('controller' => 'empresas', 'action' => 'add')); ?> </li>
	</ul>
</div>
