<?php $this->layout = 'inicio';?>
<div class="categoriacompras form">
<?php echo $this->Form->create('Categoriacompra', array('type' => 'post', 'class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Categoría de Compras'); ?></b></h2></legend>
                <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'label' => 'Usuario', 'value' => $empresaId)); ?>

                <div class="row">
                    <div class="form-group">
                        <label for="CategoriacompraDescripcion">Nombre</label>
                        <?php echo $this->Form->input('descripcion', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre de la Categoría')); ?>
                    </div>
                </div>
	</fieldset><br>
<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
</div>
