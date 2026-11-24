<?php
/**
 * Campos del formulario de retención (crear, editar y nueva vigencia).
 *
 * Variables:
 *   $bloqueados       campos que se muestran deshabilitados
 *   $tributos         [taxId => 'ReteIVA (05)']
 *   $tributosCodigo   [taxId => '05']
 *   $departamentos    [id => nombre] (Colombia)
 *   $ciudades         [id => nombre] del departamento seleccionado
 *   $dptoSeleccionado id del departamento seleccionado
 *   $uvt, $uvtAnio    UVT del año actual (null si falta)
 *   $sugerirCuenta    true para proponer la cuenta según el tributo
 */
$bloqueados = isset($bloqueados) ? $bloqueados : array();
$bloq = function ($campo) use ($bloqueados) {
    return in_array($campo, $bloqueados, true);
};
?>
<script>
    window.RET_TRIBUTOS_CODIGO = <?php echo json_encode($tributosCodigo); ?>;
    window.RET_UVT = <?php echo json_encode($uvt ? (float) $uvt : 0); ?>;
    window.RET_UVT_ANIO = <?php echo (int) $uvtAnio; ?>;
    window.RET_SUGERIR_CUENTA = <?php echo !empty($sugerirCuenta) ? 'true' : 'false'; ?>;
</script>
<?php if ($bloq('tipo_valor')): ?>
    <span id="tipoValorBloqueado" style="display:none;"></span>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="RetencionventaNombre">Nombre *</label>
            <?php echo $this->Form->input('nombre', array(
                'label' => false, 'type' => 'text', 'class' => 'form-control', 'maxlength' => 150,
                'placeholder' => 'Ej: ReteFuente servicios - declarante',
                'disabled' => $bloq('nombre'),
            )); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="taxId">Tributo DIAN *</label>
            <?php echo $this->Form->input('tax_id', array(
                'label' => false, 'type' => 'select', 'options' => $tributos, 'id' => 'taxId',
                'empty' => '-- Seleccione --', 'class' => 'form-control',
                'disabled' => $bloq('tax_id'),
            )); ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="RetencionventaAplicaA">Se aplica a *</label>
            <?php echo $this->Form->input('aplica_a', array(
                'label' => false, 'type' => 'select', 'class' => 'form-control',
                'options' => array('PRODUCTO' => 'Productos', 'SERVICIO' => 'Servicios', 'TODOS' => 'Todos'),
                'disabled' => $bloq('aplica_a'),
            )); ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Calcula sobre</label>
            <p class="form-control-static" id="calculaSobre">—</p>
        </div>
    </div>
</div>

<div class="row solo-reteica" style="display:none;">
    <div class="col-md-4">
        <div class="form-group">
            <label for="RetencionventaDepartamentoId">Departamento *</label>
            <?php echo $this->Form->input('departamento_id', array(
                'label' => false, 'type' => 'select', 'options' => $departamentos,
                'value' => $dptoSeleccionado, 'empty' => 'Seleccione Departamento',
                'class' => 'form-control selectDpto', 'onchange' => 'obtenerCiudades();',
                'disabled' => $bloq('departamento_id'),
            )); ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="RetencionventaCiudadesmiggoId">Ciudad de la ReteICA *</label>
            <?php echo $this->Form->input('ciudadesmiggo_id', array(
                'label' => false, 'type' => 'select', 'options' => $ciudades,
                'empty' => 'Seleccione Ciudad', 'class' => 'form-control selectCiudad',
                'disabled' => $bloq('ciudadesmiggo_id'),
            )); ?>
        </div>
    </div>
</div>

<div class="row solo-retefuente" style="display:none;">
    <div class="col-md-4">
        <div class="form-group">
            <label for="RetencionventaCondicionDeclarante">Condición de la empresa</label>
            <?php echo $this->Form->input('condicion_declarante', array(
                'label' => false, 'type' => 'select', 'class' => 'form-control',
                'options' => array('TODOS' => 'Todos', 'DECLARANTE' => 'Declarante de renta', 'NO_DECLARANTE' => 'No declarante'),
                'disabled' => $bloq('condicion_declarante'),
            )); ?>
            <p class="help-block">Depende de si la empresa que vende declara renta.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Tipo de valor *</label>
            <div>
                <?php echo $this->Form->radio('tipo_valor', array(
                    'PORCENTAJE' => ' Porcentaje',
                    'POR_MIL'    => ' Por mil',
                ), array(
                    'legend' => false, 'separator' => '&nbsp;&nbsp;&nbsp;',
                    'disabled' => $bloq('tipo_valor'),
                )); ?>
            </div>
            <?php echo $this->Form->error('tipo_valor'); ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="RetencionventaValor">Tarifa *</label>
            <div class="input-group">
                <?php echo $this->Form->input('valor', array(
                    'label' => false, 'type' => 'text', 'class' => 'form-control', 'div' => false,
                    'placeholder' => 'Ej: 2,5', 'error' => false,
                    'disabled' => $bloq('valor'),
                )); ?>
                <span class="input-group-addon unidad-tarifa">%</span>
            </div>
            <?php echo $this->Form->error('valor'); ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="baseMinimaUvt">Base mínima (UVT)</label>
            <?php echo $this->Form->input('base_minima_uvt', array(
                'label' => false, 'type' => 'text', 'class' => 'form-control', 'id' => 'baseMinimaUvt',
                'placeholder' => '0 = sin base mínima',
                'disabled' => $bloq('base_minima_uvt'),
            )); ?>
            <p class="help-block" id="baseEnPesos"></p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="RetencionventaVigenteDesde">Vigente desde *</label>
            <?php echo $this->Form->text('vigente_desde', array(
                'type' => 'date', 'class' => 'form-control',
                'disabled' => $bloq('vigente_desde'),
            )); ?>
            <?php echo $this->Form->error('vigente_desde'); ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="RetencionventaVigenteHasta">Vigente hasta</label>
            <?php echo $this->Form->text('vigente_hasta', array(
                'type' => 'date', 'class' => 'form-control',
                'disabled' => $bloq('vigente_hasta'),
            )); ?>
            <?php echo $this->Form->error('vigente_hasta'); ?>
            <p class="help-block">Vacío = sin fecha de fin.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="cuentaContable">Cuenta contable</label>
            <?php echo $this->Form->input('cuenta_contable', array(
                'label' => false, 'type' => 'text', 'class' => 'form-control', 'id' => 'cuentaContable',
                'maxlength' => 20, 'placeholder' => 'Ej: 135515',
                'disabled' => $bloq('cuenta_contable'),
            )); ?>
            <p class="help-block">Sugeridas: 135515 ReteFuente, 135517 ReteIVA, 135518 ReteICA.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="checkbox">
            <label>
                <?php echo $this->Form->checkbox('activo', array('disabled' => $bloq('activo'))); ?>
                Activa
            </label>
        </div>
    </div>
</div>
