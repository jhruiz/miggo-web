<?php $this->layout = 'inicio';?>
<div class="depositosUsuarios view">
<legend><h2><b><?php echo __('Usuario - Depósito'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '17', 'id' => 'menuvert')) ?>
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
