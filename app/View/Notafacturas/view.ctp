<?php $this->layout = 'inicio';?>
<div class="notafacturas view">
<legend><h2><b><?php echo __('Nota Factura'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '36', 'id' => 'menuvert')) ?>
	<dl>
		<dt class="text-info"><?php echo __('Nota'); ?></dt>
		<dd>
			<?php echo h($notafactura['Notafactura']['descripcion']); ?>
			&nbsp;
		</dd><br>
	</dl>
</div>