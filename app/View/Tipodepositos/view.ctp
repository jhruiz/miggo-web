<?php $this->layout='inicio'; ?>
<div class="tipodepositos view">
<legend><h2><b><?php echo __('Tipo de Depósito'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '8', 'id' => 'menuvert'))?> 
	<dl>
		<dt class="text-info"><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($tipodeposito['Tipodeposito']['descripcion']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Tipo Depósito'), array('action' => 'edit', $tipodeposito['Tipodeposito']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Tipo Depósitos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Tipo Depósito'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Depósitos'), array('controller' => 'depositos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Depósito'), array('controller' => 'depositos', 'action' => 'add')); ?> </li>
	</ul>
</div>
