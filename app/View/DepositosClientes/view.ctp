<?php $this->layout='inicio'; ?>
<div class="depositosClientes view">
<legend><h2><b><?php echo __('Cliente - Depósito'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '16', 'id' => 'menuvert'))?>
	<dl>
		<dt class="text-info"><?php echo __('Depósito'); ?></dt>
		<dd>
			<?php echo h($depositosCliente['Deposito']['descripcion']); ?>
			&nbsp;
		</dd><br>
		<dt class="text-info"><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo h($depositosCliente['Cliente']['nombre']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
