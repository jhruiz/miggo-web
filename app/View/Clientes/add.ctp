<?php $this->layout = 'inicio'; ?>
<?php echo ($this->Html->script('bandeja/gestionBandejas')); ?>
<?php echo ($this->Html->script('clientes/clientes.js')); ?>
<?php echo ($this->Html->script('ubicacion/obtenerubicacion')); ?>

<div class="clientes form container-fluid" style="padding: 20px;">
    <?php echo $this->Form->create('Cliente', array('type' => 'post', 'class' => 'form-horizontal')); ?>
    
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '14', 'id' => 'menuvert')) ?>
    <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
    <?php echo $this->Form->input('usuario_id', array('type' => 'hidden', 'value' => $usuarioId)); ?>
    <?php echo $this->Form->input('estado_id', array('type' => 'hidden', 'value' => '1')); ?>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h3 class="card-title mb-0"><b><i class="fa fa-user-plus"></i> <?php echo __('Agregar Nuevo Cliente'); ?></b></h3>
        </div>
        
        <div class="card-body bg-light-gray">
            <fieldset>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Tipo Identificación</label>
                            <?php echo $this->Form->input("tipoidentificacione_id", array(
                                'label' => false,
                                'type' => 'select',
                                'options' => $tipoIdent,
                                'class' => 'form-control select2',
                                'empty' => 'Seleccione Tipo'
                            )); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">NIT / Cédula</label>
                            <?php echo $this->Form->input('nit', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Número de identificación')); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Nombre Completo / Razón Social</label>
                            <?php echo $this->Form->input('nombre', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'placeholder' => 'Nombre del Cliente')); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">País</label>
                            <?php echo $this->Form->input('pais_id', array(
                                'label' => false, 
                                'class' => 'form-control select2 selectPais', 
                                'empty' => 'Seleccione País', 
                                'options' => $paises, 
                                'onchange' => 'obtenerDptos();'
                            )); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Departamento</label>
                            <?php echo $this->Form->input('departamento_id', array(
                                'label' => false, 
                                'class' => 'form-control select2 selectDpto', 
                                'empty' => 'Seleccione Departamento', 
                                'onchange' => 'obtenerCiudades();'
                            )); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Ciudad</label>
                            <?php echo $this->Form->input('ciudadesmiggo_id', array(
                                'label' => false, 
                                'class' => 'form-control select2 selectCiudad', 
                                'empty' => 'Seleccione Ciudad'
                            )); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Dirección</label>
                            <?php echo $this->Form->input('direccion', array('label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Dirección de entrega/cobro', 'autocomplete' => 'off')); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Teléfono</label>
                            <div class="input-group">
                                <?php echo $this->Form->input('telefono', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Fijo', 'autocomplete' => 'off')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Celular</label>
                            <div class="input-group">
                                <?php echo $this->Form->input('celular', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Celular')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Email</label>
                            <?php echo $this->Form->input('email', array('label' => false, 'class' => 'form-control', 'placeholder' => 'correo@ejemplo.com', 'autocomplete' => 'off')); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Bodega Asignada</label>
                            <?php echo $this->Form->input('deposito_id', array('label' => false, 'class' => 'form-control select2', 'empty' => 'Seleccione Bodega')); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Clasificación</label>
                            <?php echo $this->Form->input("clasificacioncliente_id", array(
                                'label' => false,
                                'type' => 'select',
                                'options' => $clasificacion,
                                'class' => 'form-control select2',
                                'empty' => 'Seleccione Clasificación'
                            )); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Límite de crédito</label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <?php echo $this->Form->input('limitecredito', array('label' => false, 'class' => 'form-control numericPrice', 'placeholder' => 'Monto')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Días Crédito</label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <?php echo $this->Form->input('diascredito', array('label' => false, 'class' => 'form-control number', 'placeholder' => 'Días')); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Fecha de Cumpleaños</label>
                            <input name="data[Cliente][cumpleanios]" class="date form-control" placeholder="AAAA-MM-DD" id="cumpleanios">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Sitio Web</label>
                            <?php echo $this->Form->input('paginaweb', array('label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'http://...')); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Observaciones</label>
                            <?php echo $this->Form->input('observaciones', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Notas internas')); ?>
                        </div>
                    </div>
                </div>

            </fieldset>
        </div>
        
        <div class="card-footer bg-white text-right py-3">
            <?php echo $this->Html->link(__('Cancelar'), array('action' => 'index'), array('class' => 'btn btn-outline-secondary mr-2')); ?>
            <?php echo $this->Form->submit('Guardar Cliente', array('class' => 'btn btn-primary btn-lg px-5 shadow-sm', 'div' => false)); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>