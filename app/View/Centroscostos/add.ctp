<?php $this->layout = 'inicio'; ?>
<?php echo ($this->Html->css('centroscostos/add.css')); ?>

<div class="centroscostos form container-fluid p-4">
    <?php echo $this->Form->create('Centroscosto', array('type' => 'post')); ?>

    <div class="form-miggo-container shadow-sm">
        
        <!-- TÍTULO PRINCIPAL CON ÍCONO -->
        <div class="miggo-title-header">
            <i class="fa fa-cubes"></i> Nuevo Centro de Costo
        </div>

        <div class="row">
            <!-- NOMBRE DEL CENTRO DE COSTO -->
            <div class="col-md-8 mb-4">
                <label class="miggo-label">
                    NOMBRE <span class="text-danger">*</span>
                </label>
                <?php echo $this->Form->input('nombre', array(
                    'label'       => false,
                    'class'       => 'form-control miggo-input',
                    'placeholder' => 'Ej: Nombre o código del centro de costo',
                    'required'    => true
                )); ?>
            </div>

            <!-- INTERRUPTOR EDITABLE (SWITCH) -->
            <div class="col-md-4 mb-4">
                <label class="miggo-label">EDITABLE</label>
                <div>
                    <label class="custom-switch-miggo">
                        <?php echo $this->Form->input('editable', array(
                            'type'    => 'checkbox', 
                            'label'   => false, 
                            'checked' => true,
                            'div'     => false
                        )); ?>
                        <span class="slider-miggo"></span>
                        <span class="switch-label-text">Permitir modificar/eliminar</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- SELECCIÓN DE EMPRESAS -->
            <div class="col-md-12 mb-4">
                <label class="miggo-label">EMPRESAS</label>
                <?php echo $this->Form->input('empresas', array(
                    'label'   => false,
                    'type'    => 'select',
                    'class'   => 'form-control select2 miggo-input',
                    'options' => $empresas,
                )); ?>
            </div>
        </div>

        <hr style="border-top: 1px solid #edf2f7; margin: 20px 0;">

        <!-- BOTÓN DE GUARDAR -->
        <div class="row">
            <div class="col-md-12">
                <?php echo $this->Form->submit('Guardar', array(
                    'class' => 'btn btn-miggo-save',
                    'div'   => false
                )); ?>
            </div>
        </div>

    </div>

    <?php echo $this->Form->end(); ?>
</div>