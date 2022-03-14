<?php $this->layout = 'inicio';?>
<div class="tipodepositos form">
<?php echo $this->Form->create('Tipodeposito', array('type' => 'post', 'class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Tipo de Depósito'); ?></b></h2></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '8', 'id' => 'menuvert')) ?>
	<?php echo $this->Form->input('id'); ?>
                <div class="form-group">
                    <label for="TipodepositoDescripcion">Nombre</label>
                    <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre del Tipo de Depósito')); ?>
                </div>
	<?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
	</fieldset><br>
<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
</div>