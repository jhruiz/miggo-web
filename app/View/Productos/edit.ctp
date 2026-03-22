<?php $this->layout = 'inicio'; ?>
<?php echo ($this->Html->script('productos/productos.js')); ?>
<?php echo ($this->Html->css('productos/general.css')); ?>
<?php echo ($this->Html->css('productos/geleriaimagenes.css')); ?>

<div class="productos form container-fluid" style="padding: 20px;">
    <?php echo $this->Form->create('Producto', array('type' => 'file', 'class' => 'form-horizontal')); ?>
    <?php echo $this->Form->input('id'); ?>
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '4', 'id' => 'menuvert')) ?>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h3 class="card-title mb-0"><b><i class="fa fa-cube"></i> <?php echo __('Editar de Producto'); ?></b></h3>
        </div>
        
        <div class="card-body bg-light-gray">
            <fieldset>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" style="margin-right: 5px;">
                            <label><b>Código</b></label>
                            <?php echo $this->Form->input('codigo', array('label' => false, 'class' => 'form-control input-lg', 'placeholder' => 'Código del Producto')); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" style="margin-left: 5px;">
                            <label><b>Referencia / SKU</b></label>
                            <?php echo $this->Form->input('referencia', array('label' => false, 'class' => 'form-control input-lg', 'placeholder' => 'Referencia del Producto')); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>Nombre del Producto</b></label>
                            <?php echo $this->Form->input('descripcion', array('label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre Comercial')); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" style="margin-right: 5px;">
                            <label><b>Categoría</b></label>
                            <?php echo $this->Form->input('categoria_id', array('class' => 'form-control', 'label' => false)); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" style="margin-left: 5px;">
                            <label><b>Marca</b></label>
                            <?php echo $this->Form->input('marca', array('class' => 'form-control', 'label' => false, 'placeholder' => 'Marca del Producto')); ?>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-danger"><b>Stock Mínimo</b></label>
                            <?php echo $this->Form->input('existenciaminima', array('label' => false, 'class' => 'form-control', 'placeholder' => '0')); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-primary"><b>Stock Máximo</b></label>
                            <?php echo $this->Form->input('existenciamaxima', array('label' => false, 'class' => 'form-control', 'placeholder' => '0')); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="margin-top: 25px;">
                            <div class="switch-container" style="display: inline-flex; align-items: center; margin-right: 20px;">
                                <label class="switch">
                                    <?php echo $this->Form->checkbox('mostrarencatalogo'); ?>
                                    <span class="slider round"></span>
                                </label>
                                <span style="margin-left: 10px; font-weight: 500;">Mostrar en Marketplace</span>
                            </div>
                            <div class="switch-container" style="display: inline-flex; align-items: center;">
                                <label class="switch">
                                    <?php echo $this->Form->checkbox('inventario'); ?>
                                    <span class="slider round"></span>
                                </label>
                                <span style="margin-left: 10px; font-weight: 500;">Controlar Inventario</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <label class="text-info"><b><i class="fa fa-file-text-o"></i> Descripción Detallada para Marketplace</b></label>
                    <?php echo $this->Form->input('desc_extensa', array(
                        'label' => false, 
                        'id' => 'summernote', // ID para activar el editor
                        'class' => 'form-control'
                    )); ?>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b><i class="fa fa-tags"></i> Palabras Clave (SEO)</b></label>
                            <div class="input-group">
                                <input type="text" id="tagInput" class="form-control" placeholder="Escribe y presiona Agregar">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="addTag">Agregar</button>
                                </span>
                            </div>
                            <div id="tagsContainer" style="margin-top: 10px;"></div>
                            <?php echo $this->Form->input('palabras_clave', array('type' => 'hidden', 'id' => 'hiddenTags', 'value' => $palabrasClavePrevias)); ?>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fa fa-camera"></i> Galería de Imágenes</label>
                            <div class="upload-area border-dashed p-4 text-center bg-white" style="border: 2px dashed #ccc; border-radius: 10px;">
                                <i class="fa fa-cloud-upload fa-3x text-muted"></i>
                                <p class="mb-2">Selecciona múltiples imágenes para este producto</p>
                                <?php echo $this->Form->input('imagenes.', array('type' => 'file', 'multiple' => 'multiple', 'label' => false, 'accept' => '.jpg, .jpeg', 'required' => false, 'class' => 'form-control-file d-inline-block')); ?>
                                <small class="text-muted d-block mt-2">Formatos permitidos: JPG, JPEG (Máx 1MB cada una)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-12">
                <label><b><i class="fa fa-images"></i> Galería de Imágenes Actual</b></label>
                <div id="galeria-actual" class="row" style="background: #f9f9f9; padding: 20px; border-radius: 10px; border: 1px solid #eee; margin: 5px 0 20px 0;">
                    <?php if (!empty($imagenesActuales)): ?>
                        <?php foreach ($imagenesActuales as $img): ?>
                            <?php 
                                $urlImagen = $this->webroot . "img/productos/{$empresaId}/" . $img['Imagenesitem']['url']; 
                            ?>
                            <div class="col-xs-6 col-sm-3 col-md-2" id="foto-container-<?php echo $img['Imagenesitem']['id']; ?>">
                                <div class="thumbnail" style="position: relative; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); overflow: hidden;">
                                    <img src="<?php echo $urlImagen; ?>" style="height: 100px; width: 100%; object-fit: cover;">
                                    
                                    <button type="button" 
                                            class="btn btn-danger btn-xs" 
                                            style="position: absolute; top: 5px; right: 5px; border-radius: 50%; opacity: 0.9;"
                                            onclick="eliminarImagenItem(<?php echo $img['Imagenesitem']['id']; ?>)">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-md-12 text-center text-muted">
                            <i class="fa fa-info-circle"></i> Este producto aún no tiene imágenes asociadas.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="panel-footer bg-white text-right" style="background: #fff; padding: 20px;">
            <?php echo $this->Form->submit('Actualizar Producto', array('class' => 'btn btn-primary btn-lg px-5 shadow-sm')); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>