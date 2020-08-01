<?php $this->layout='inicio'; ?>
<div class="depositosUsuarios view">
<legend><h2><b><?php echo __('Usuario - Depósito'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '17', 'id' => 'menuvert'))?>
	<dl>
		<dt class="text-info"><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo h($depositosUsuario['Usuario']['nombre']); ?>
			&nbsp;
		</dd><br>
                
		<dt class="text-info"><?php echo __('Depósito'); ?></dt>
		<dd>
			<?php echo h($depositosUsuario['Deposito']['descripcion']); ?>
			&nbsp;
		</dd><br>
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h3></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Editar Usuario - Depósito'), array('action' => 'edit', $depositosUsuario['DepositosUsuario']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Lista Usuarios - Depósitos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nuevo Usuario - Depósito'), array('action' => 'add')); ?> </li>
	</ul>
</div>
