<?php echo ($this->Html->script('bandeja/gestionBandejas')); ?>
<?php echo ($this->Html->script('ubicacion/obtenerubicacion')); ?>
<?php $this->layout = 'inicio';?>

<div class="depositos form container-fluid" style="padding: 20px;">
    <?php echo $this->Form->create('Deposito', array('type' => 'post', 'class' => 'form-horizontal')); ?>
    
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '15', 'id' => 'menuvert')) ?>
    <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h3 class="card-title mb-0"><b><i class="fa fa-university"></i> <?php echo __('Gestión de Bodega'); ?></b></h3>
        </div>
        
        <div class="card-body bg-light-gray">
            <fieldset>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Nombre de la Bodega</label>
                            <?php echo $this->Form->input('descripcion', array('label' => false, 'class' => 'form-control form-control-lg', 'placeholder' => 'Nombre de la Bodega')); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Tipo de Bodega</label>
                            <?php echo $this->Form->input('tipodeposito_id', array('label' => false, 'class' => 'form-control', 'empty' => 'Seleccione Tipo')); ?>
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
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Dirección</label>
                            <?php echo $this->Form->input('direccion', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Dirección de la Bodega')); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Teléfono</label>
                            <?php echo $this->Form->input('telefono', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Teléfono')); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Encargado</label>
                            <?php echo $this->Form->input('usuario_id', array('label' => false, 'class' => 'form-control', 'empty' => 'Seleccione Encargado')); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Régimen</label>
                            <?php echo $this->Form->input('regimene_id', array('label' => false, 'class' => 'form-control', 'empty' => 'Seleccione Régimen')); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="margin-top: 25px;">
                            <div class="switch-container" style="display: inline-flex; align-items: center; margin-right: 20px;">
                                <label class="switch">
                                    <?php echo $this->Form->checkbox('estadisticas'); ?>
                                    <span class="slider round"></span>
                                </label>
                                <label style="margin-left: 10px; font-weight: 500;" class="font-weight-bold">Ver en estadísticas</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fa fa-sticky-note-o"></i> Nota</label>
                            <?php echo $this->Form->input('nota', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Agregar nota para la Bodega')); ?>
                        </div>
                    </div>
                </div>

            </fieldset>
        </div>
        
        <div class="card-footer bg-white text-right py-3">
            <?php echo $this->Form->submit('Guardar Bodega', array('class' => 'btn btn-primary btn-lg px-5 shadow-sm')); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>