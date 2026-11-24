<?php $this->layout = 'inicio'; ?>
<?php echo ($this->Html->script('bandeja/gestionBandejas')); ?>
<?php echo ($this->Html->script('ubicacion/obtenerubicacion')); ?>

<div class="empresas form container-fluid" style="padding: 20px;">
    <?php echo $this->Form->create('Empresa', array('type' => 'file', 'class' => 'form-horizontal')); ?>
    
    <?php echo $this->Form->input('id'); ?>
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '41', 'id' => 'menuvert')) ?>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h3 class="card-title mb-0"><b><i class="fa fa-edit text-primary"></i> <?php echo __('Editar Datos de la Empresa'); ?></b></h3>
        </div>
        
        <div class="card-body bg-light-gray" style="background-color: #f8f9fa; padding: 30px;">
            <fieldset>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold" style="color: #555;"><?php echo __('Nombre / Razón Social'); ?></label>
                            <?php echo $this->Form->input('nombre', array('label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre Empresa')); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold" style="color: #555;"><?php echo __('NIT'); ?></label>
                            <?php echo $this->Form->input('nit', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Nit Empresa')); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold" style="color: #555;"><?php echo __('Representante Legal'); ?></label>
                            <?php echo $this->Form->input('representantelegal', array('label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre Representante')); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold" style="color: #555;"><?php echo __('País'); ?></label>
                            <?php echo $this->Form->input('pais_id', array(
                                'label' => false, 
                                'class' => 'form-control select2 selectPais', 
                                'empty' => 'Seleccione País', 
                                'options' => $paises, 
                                'default' => $ubicacion['0']['P']['id'],
                                'onchange' => 'obtenerDptos();'
                            )); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold" style="color: #555;"><?php echo __('Departamento'); ?></label>
                            <?php echo $this->Form->input('departamento_id', array(
                                'label' => false, 
                                'class' => 'form-control select2 selectDpto', 
                                'options' => $departamentos, 
                                'default' => $ubicacion['0']['D']['id'],
                                'onchange' => 'obtenerCiudades();'
                            )); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold" style="color: #555;"><?php echo __('Ciudad'); ?></label>
                            <?php echo $this->Form->input('ciudade_id', array(
                                'label' => false, 
                                'class' => 'form-control select2 selectCiudad', 
                                'options' => $ciudades, 
                                'default' => $ubicacion['0']['Ciudadesmiggo']['id']
                            )); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold" style="color: #555;"><?php echo __('Dirección'); ?></label>
                            <?php echo $this->Form->input('direccion', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Dirección Principal')); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold" style="color: #555;"><?php echo __('Teléfonos (1 y 2)'); ?></label>
                            <div class="input-group">
                                <?php echo $this->Form->input('telefono1', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Principal')); ?>
                                <?php echo $this->Form->input('telefono2', array('label' => false, 'class' => 'form-control', 'placeholder' => 'Secundario')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold" style="color: #555;"><?php echo __('E-mail Corporativo'); ?></label>
                            <?php echo $this->Form->input('email', array('label' => false, 'class' => 'form-control', 'placeholder' => 'correo@empresa.com')); ?>
                        </div>
                    </div>
                </div>

                <hr>
                <h4 style="margin-bottom: 5px;"><b><i class="fa fa-balance-scale text-primary"></i> <?php echo __('Perfil tributario'); ?></b></h4>
                <p class="text-muted" style="margin-bottom: 20px;">
                    <?php echo __('Se usa para calcular las retenciones que practican los clientes en las facturas de venta.'); ?>
                </p>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold" style="color: #555;"><?php echo __('Tipo de persona'); ?></label>
                            <?php echo $this->Form->input('tipo_persona', array(
                                'label' => false, 'class' => 'form-control', 'div' => false,
                                'options' => array('JURIDICA' => 'Persona jurídica', 'NATURAL' => 'Persona natural'),
                            )); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold" style="color: #555;"><?php echo __('¿Declarante de renta?'); ?></label>
                            <?php echo $this->Form->input('declarante_renta', array(
                                'label' => false, 'class' => 'form-control', 'div' => false,
                                'options' => array('1' => 'Sí, es declarante de renta', '0' => 'No es declarante de renta'),
                            )); ?>
                            <p class="help-block" style="margin-bottom: 0;"><?php echo __('Define la tarifa de ReteFuente que le aplican (declarante o no declarante).'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold" style="color: #555;"><?php echo __('Responsabilidades fiscales del RUT'); ?></label>
                            <input type="hidden" name="data[Tiposresponsabilidade][Tiposresponsabilidade]" value="">
                            <?php foreach ($responsabilidades as $respId => $respNombre): ?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="data[Tiposresponsabilidade][Tiposresponsabilidade][]"
                                               value="<?php echo (int) $respId; ?>"
                                               <?php echo in_array((int) $respId, $marcadas, true) ? 'checked' : ''; ?>>
                                        <?php echo h($respNombre); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="font-weight-bold" style="color: #555;"><?php echo __('Logo de la Empresa'); ?></label>
                            <div class="p-3 bg-white border rounded">
                                <?php echo $this->Form->input('imagen', array('type' => 'file', 'label' => false, 'class' => 'form-control-file')); ?>
                                <small class="text-muted"><i class="fa fa-info-circle"></i> Máximo 1MB (PNG, JPG)</small>
                            </div>
                        </div>
                    </div>
                </div>

            </fieldset>
        </div>
        
        <div class="card-footer bg-white text-right py-3">
            <?php echo $this->Html->link(__('Cancelar'), array('action' => 'view', $this->request->data['Empresa']['id']), array('class' => 'btn btn-outline-secondary mr-2')); ?>
            <?php echo $this->Form->submit('Actualizar Empresa', array('class' => 'btn btn-primary btn-lg px-5 shadow-sm', 'div' => false)); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>