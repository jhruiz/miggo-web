<?php $this->layout = 'inicio';?>
<?php echo ($this->Html->script('productos/productos.js')); ?>
    <div class="productos form">

    <?php echo $this->Form->create('Producto', array('type' => 'file', 'class' => 'form-inline')); ?>
        <fieldset>
            <legend><h2><b><?php echo __('Agregar Producto'); ?></b></h2></legend>
                <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '4', 'id' => 'menuvert')) ?>

                <div class="row">
                    <div class="form-group">
                        <label for="ProductoCodigo">Código</label>
                        <?php echo $this->Form->input('codigo', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Código del Producto')); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="ProductoReferencia">Referencia</label>
                        <?php echo $this->Form->input('referencia', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Referencia del Producto')); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="ProductoDescripcion">Nombre</label>
                        <?php echo $this->Form->input('descripcion', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre del Producto')); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="ProductoCategoriaId">Categoría</label>
                        <?php echo $this->Form->input('categoria_id', array('class' => 'form-control', 'label' => '')); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group form-inline">
                        <label for="ProductoMarca">Marca</label>
                        <?php echo $this->Form->input('marca', array('class' => 'form-control', 'label' => '', 'placeholder' => 'Marca del Producto')); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="ProductoExistenciaminima">Existencia Mínima</label>
                        <?php echo $this->Form->input('existenciaminima', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Cantidad Mínima Permitida')); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="ProductoExistenciamaxima">Existencia Máxima</label>
                        <?php echo $this->Form->input('existenciamaxima', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Cantidad Máxima Permitida')); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Mostrar en Catálogo</label>
                    <?php echo $this->Form->input('mostrarencatalogo', array('label' => '', 'type' => 'checkbox', 'class' => 'form-control')); ?>
                    <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)) ?>
                </div><br>

                <div class="form-group">
                    <label>Vender con inventario</label>
                    <?php echo $this->Form->input('inventario', array('label' => '', 'type' => 'checkbox', 'class' => 'form-control')); ?>
                </div>                

                <div class="row">
                    <div class="form-group">
                        <?php echo $this->Form->input('imagen', array('type' => 'file')); ?>
                        <p class="help-block">Máximo 1MB</p>
                    </div>
                </div>
	</fieldset>
        <br>
        <?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
    </div>