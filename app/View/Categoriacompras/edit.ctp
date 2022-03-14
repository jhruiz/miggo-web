<?php $this->layout='inicio'; ?>
<div class="categoriacompras form">
<?php echo $this->Form->create('Categoriacompra', array('class' => 'form-inline', 'type' => 'post')); ?>
	<fieldset>
		<legend><?php echo __('Editar Categoría Compra'); ?></legend>
	<?php
		echo $this->Form->input('id');		
	?>
                
        <div class="row"> 
            <div class="form-group">
                <label for="CategoriacompraDescripcion">Nombre</label>
                <?php echo $this->Form->input('descripcion', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre de la Categoría')); ?>
            </div>
        </div><br>                  
	</fieldset>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div>