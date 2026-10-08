<?php
/**
 * Campos compartidos del formulario de Retenciones (add / edit).
 * Variables esperadas: $cuentas, $categorias, $tiposTarifa, $empresaId
 */
?>
<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
            <?php echo $this->Form->input('nombre', array(
                'label'       => false,
                'class'       => 'form-control',
                'placeholder' => 'Ej: Retefuente 2.5%',
            )); ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label class="font-weight-bold">Porcentaje <span class="text-danger">*</span></label>
            <div class="input-group">
                <?php echo $this->Form->input('porcentaje', array(
                    'label'       => false,
                    'class'       => 'form-control',
                    'placeholder' => '0.000',
                    'type'        => 'text',
                )); ?>
                <span class="input-group-addon">%</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label class="font-weight-bold">Estado</label>
            <div style="padding-top:8px;">
                <label class="checkbox-inline">
                    <?php echo $this->Form->input('activo', array(
                        'type'    => 'checkbox',
                        'label'   => false,
                        'checked' => (isset($this->request->data['Retencione']['activo']) ? $this->request->data['Retencione']['activo'] == '1' : true),
                    )); ?>
                    Activa
                </label>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="font-weight-bold">Categoría de Retención <span class="text-danger">*</span></label>
            <?php echo $this->Form->input('categoriaretencion_id', array(
                'label'   => false,
                'type'    => 'select',
                'class'   => 'form-control select2',
                'empty'   => 'Seleccione...',
                'options' => $categorias,
            )); ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="font-weight-bold">Tipo de Tarifa <span class="text-danger">*</span></label>
            <?php echo $this->Form->input('tipotarifa_id', array(
                'label'   => false,
                'type'    => 'select',
                'class'   => 'form-control select2',
                'empty'   => 'Seleccione...',
                'options' => $tiposTarifa,
            )); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="font-weight-bold">Cuenta Contable (Ventas) <span class="text-danger">*</span></label>
            <?php echo $this->Form->input('cuentaventa_id', array(
                'label'   => false,
                'type'    => 'select',
                'class'   => 'form-control select2',
                'empty'   => 'Seleccione cuenta...',
                'options' => $cuentas,
            )); ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label class="font-weight-bold">Cuenta Contable (Compras) <span class="text-danger">*</span></label>
            <?php echo $this->Form->input('cuentacompra_id', array(
                'label'   => false,
                'type'    => 'select',
                'class'   => 'form-control select2',
                'empty'   => 'Seleccione cuenta...',
                'options' => $cuentas,
            )); ?>
        </div>
    </div>
</div>

<?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
