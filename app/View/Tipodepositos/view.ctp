<?php $this->layout = 'inicio';?>
<div class="tipodepositos view">
<legend><h2><b><?php echo __('Tipo de Bodega'); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '8', 'id' => 'menuvert')) ?>
	<dl>
		<dt class="text-info"><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($tipodeposito['Tipodeposito']['descripcion']); ?>
			&nbsp;
		</dd>
	</dl>
</div>