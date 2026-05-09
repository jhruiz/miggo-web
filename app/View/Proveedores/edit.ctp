<?php $this->layout = 'inicio'; ?>
<?php echo ($this->Html->script('proveedores/proveedores.js')); ?>
<?php echo ($this->Html->script('ubicacion/obtenerubicacion')); ?>

<div class="proveedores form container-fluid" style="padding: 20px;">
    <?php echo $this->Form->create('Proveedore', array('type' => 'post', 'class' => 'form-horizontal')); ?>
    
    <?php echo $this->Form->input('id'); ?>
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '27', 'id' => 'menuvert')) ?>
    <?php echo $this->Form->input('usuario_id', array('type' => 'hidden', 'value' => $usuarioId)); ?>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h3 class="card-title mb-0"><b><i class="fa fa-edit"></i> <?php echo __('Editar Proveedor'); ?></b></h3>
        </div>
        
        <div class="card-body bg-light-gray">
            <fieldset>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">NIT / Identificación</label>
                            <?php echo $this->Form->input('nit', array('label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nit del Proveedor')); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Nombre o Razón Social</label>
                            <?php echo $this->Form->input('nombre', array('label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre del Proveedor')); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Estado</label>
                            <?php echo $this->Form->input('estado_id', array('label' => false, 'class' => 'form-control select2')); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">País</label>
                            <?php echo $this->Form->input('pais_id', array('label' => false, 'class' => 'form-control select2 selectPais', 'empty' => 'Seleccione País', 'options' => $paises, 'onchange' => 'obtenerDptos();')); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Departamento</label>
                            <?php echo $this->Form->input('departamento_id', array('label' => false, 'class' => 'form-control select2 selectDpto', 'empty' => 'Seleccione Departamento', 'onchange' => 'obtenerCiudades();')); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Ciudad</label>
                            <?php echo $this->Form->input('ciudade_id', array('label' => false, 'class' => 'form-control select2 selectCiudad', 'empty' => 'Seleccione Ciudad')); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Dirección</label>
                            <?php echo $this->Form->input('direccion', array('label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Dirección del Proveedor')); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Teléfono</label>
                            <div class="input-group">
                                <?php echo $this->Form->input('telefono', array('label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Teléfono')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Celular</label>
                            <div class="input-group">
                                <?php echo $this->Form->input('celular', array('label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Celular')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">E-Mail</label>
                            <?php echo $this->Form->input('email', array('label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'E-Mail Proveedor')); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Régimen</label>
                            <?php echo $this->Form->input('regimene_id', array('label' => false, 'type' => 'select', 'options' => $regimen, 'class' => 'form-control', 'id' => 'regimeneId', 'empty' => 'Seleccione Régimen')); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Días de Crédito</label>
                            <?php echo $this->Form->input('diascredito', array('label' => false, 'class' => 'form-control number', 'placeholder' => 'Días de Crédito')); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Límite de Crédito</label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <?php echo $this->Form->input('limitecredito', array('label' => false, 'class' => 'form-control numericPrice', 'placeholder' => 'Límite de Crédito')); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Página Web</label>
                            <?php echo $this->Form->input('paginaweb', array('label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Sitio Web')); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Observaciones</label>
                            <?php echo $this->Form->input('observaciones', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Observaciones adicionales')); ?>
                        </div>
                    </div>
                </div>

            </fieldset>
        </div>
        
        <div class="card-footer bg-white text-right py-3">
            <?php echo $this->Html->link(__('Cancelar'), array('action' => 'index'), array('class' => 'btn btn-outline-secondary mr-2')); ?>
            <?php echo $this->Form->submit('Actualizar Proveedor', array('class' => 'btn btn-primary btn-lg px-5 shadow-sm', 'div' => false)); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>