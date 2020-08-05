<?php $this->layout = 'inicio';?>
<div class="impuestos view">
<legend><h2><b><?php echo __('Impuesto'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '20', 'id' => 'menuvert')) ?>
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