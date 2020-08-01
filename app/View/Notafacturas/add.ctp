<?php $this->layout='inicio'; ?>
<div class="notafacturas form">
<?php echo $this->Form->create('Notafactura'); ?>
	<fieldset>
            <legend><h2><b><?php echo __('Agregar Nota'); ?></b></h2></legend>
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '36', 'id' => 'menuvert'))?>
            <div class="form-group">
                <?php echo $this->Form->input('descripcion', array('label' => 'Nota', 'class' => 'form-control', 'placeholder' => 'Nota para Factura')); ?>
                <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresa));?>
            </div>
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div>