<?php $this->layout = 'inicio';?>
<div class="tipodepositos form">
<?php echo $this->Form->create('Tipodeposito', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Tipo de Bodega'); ?></b></h2></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '8', 'id' => 'menuvert')) ?>
                <div class="row">
                    <div class="form-group">
                        <label for="TipodepositoDescripcion">Nombre</label>
                        <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre del Tipo de Bodega')); ?>
                    </div>
                </div><br>
        <?php
echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId));
?>
	</fieldset>
<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
</div>
