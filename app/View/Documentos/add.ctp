<div class="documentos form">
<?php echo $this->Form->create('Documento'); ?>
	<fieldset>
		<legend><?php echo __('Add Documento'); ?></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '28', 'id' => 'menuvert'))?>
	<?php
		echo $this->Form->input('tiposdocumento_id');
		echo $this->Form->input('codigo');
		echo $this->Form->input('empresa_id');
		echo $this->Form->input('usuario_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Documentos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Tiposdocumentos'), array('controller' => 'tiposdocumentos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tiposdocumento'), array('controller' => 'tiposdocumentos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Empresas'), array('controller' => 'empresas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Empresa'), array('controller' => 'empresas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Usuarios'), array('controller' => 'usuarios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Usuario'), array('controller' => 'usuarios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Anotaciones'), array('controller' => 'anotaciones', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Anotacione'), array('controller' => 'anotaciones', 'action' => 'add')); ?> </li>
	</ul>
</div>
