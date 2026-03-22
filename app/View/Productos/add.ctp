<?php $this->layout = 'inicio';?>
<?php echo ($this->Html->script('productos/productos.js')); ?>
<?php echo ($this->Html->css('productos/general.css')); ?>

<div class="productos form container-fluid" style="padding: 20px;">
    <?php echo $this->Form->create('Producto', array('type' => 'file', 'class' => 'form-horizontal')); ?>
    
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h3 class="card-title mb-0"><b><i class="fa fa-cube"></i> <?php echo __('Gestión de Producto'); ?></b></h3>
        </div>
        
        <div class="card-body bg-light-gray">
            <fieldset>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Código del Producto</label>
                            <?php echo $this->Form->input('codigo', array('label' => false, 'class' => 'form-control form-control-lg', 'placeholder' => 'Ej: PROD-001')); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Referencia / SKU</label>
                            <?php echo $this->Form->input('referencia', array('label' => false, 'class' => 'form-control form-control-lg', 'placeholder' => 'Referencia del Producto')); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Nombre Comercial</label>
                            <?php echo $this->Form->input('descripcion', array('label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre claro para el cliente')); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Categoría</label>
                            <?php echo $this->Form->input('categoria_id', array('class' => 'form-control select2', 'label' => false)); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Marca</label>
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
                            <label class="control-label"><b><i class="fa fa-tags"></i> Palabras Clave (SEO / Búsqueda)</b></label>
                            
                            <div class="input-group">
                                <input type="text" id="tagInput" class="form-control" placeholder="Escribe una palabra y presiona Agregar">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="addTag">Agregar</button>
                                </span>
                            </div>
                            
                            <div id="tagsContainer" style="margin-top: 10px;"></div>
                            
                            <?php echo $this->Form->input('palabras_clave', array('type' => 'hidden', 'id' => 'hiddenTags')); ?>
                            <p class="help-block"><small>Ejemplo: Si es un 'Bisturí', agrega: Cortapapel, Cúter.</small></p>
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
                                <?php echo $this->Form->input('imagenes.', array('type' => 'file', 'multiple' => 'multiple', 'label' => false, 'accept' => '.jpg, .jpeg', 'class' => 'form-control-file d-inline-block')); ?>
                                <small class="text-muted d-block mt-2">Formatos permitidos: JPG, JPEG (Máx 1MB cada una)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
        
        <div class="card-footer bg-white text-right py-3">
            <?php echo $this->Form->submit('Guardar Producto', array('class' => 'btn btn-primary btn-lg px-5 shadow-sm')); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
