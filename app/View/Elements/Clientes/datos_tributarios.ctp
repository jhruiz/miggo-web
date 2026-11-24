<?php
/**
 * Bloque "Datos tributarios" del cliente (alta y edición).
 *
 * Variables:
 *   $regimenes         [id => descripción]
 *   $responsabilidades [id => 'O-13 · Gran contribuyente']
 *   $marcadasCliente   ids de responsabilidades marcadas
 */
echo $this->Html->script('clientes/datos_tributarios');
?>
<hr>
<h4 style="margin-bottom: 5px;"><b><i class="fa fa-balance-scale text-primary"></i> <?php echo __('Datos tributarios'); ?></b></h4>
<p class="text-muted" style="margin-bottom: 20px;">
    <?php echo __('Indican qué retenciones le practica este cliente a la empresa en las facturas electrónicas. Si no marca ninguna, no se calcula ninguna retención.'); ?>
</p>

<div class="row">
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label class="font-weight-bold"><?php echo __('Tipo de persona'); ?></label>
            <?php echo $this->Form->input('tipo_persona', array(
                'label' => false, 'class' => 'form-control', 'div' => false, 'empty' => 'Sin definir',
                'options' => array(
                    'NATURAL'         => 'Persona natural',
                    'JURIDICA'        => 'Persona jurídica',
                    'ENTIDAD_PUBLICA' => 'Entidad pública',
                ),
            )); ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label class="font-weight-bold"><?php echo __('Régimen'); ?></label>
            <?php echo $this->Form->input('regimene_id', array(
                'label' => false, 'class' => 'form-control', 'div' => false, 'empty' => 'Sin definir',
                'options' => $regimenes,
            )); ?>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group mb-3">
            <label class="font-weight-bold"><?php echo __('DV'); ?></label>
            <input type="text" id="clienteDv" class="form-control" readonly
                   value="<?php echo isset($this->request->data['Cliente']['dv']) ? h($this->request->data['Cliente']['dv']) : ''; ?>">
            <p class="help-block" style="margin-bottom: 0;"><?php echo __('Se calcula del NIT.'); ?></p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label class="font-weight-bold"><?php echo __('Retenciones que practica este cliente'); ?></label>
            <div class="checkbox">
                <label><?php echo $this->Form->checkbox('practica_retefuente', array('hiddenField' => true)); ?> ReteFuente</label>
            </div>
            <div class="checkbox">
                <label><?php echo $this->Form->checkbox('practica_reteiva', array('hiddenField' => true)); ?> ReteIVA</label>
            </div>
            <div class="checkbox">
                <label><?php echo $this->Form->checkbox('practica_reteica', array('hiddenField' => true)); ?> ReteICA
                    <span class="text-muted">(usa la ciudad del cliente)</span></label>
            </div>
            <?php echo $this->Form->error('practica_reteica', null, array('class' => 'text-danger')); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label class="font-weight-bold"><?php echo __('Responsabilidades fiscales del RUT'); ?></label>
            <input type="hidden" name="data[Tiposresponsabilidade][Tiposresponsabilidade]" value="">
            <?php foreach ($responsabilidades as $respId => $respNombre): ?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="data[Tiposresponsabilidade][Tiposresponsabilidade][]"
                               value="<?php echo (int) $respId; ?>"
                               <?php echo in_array((int) $respId, $marcadasCliente, true) ? 'checked' : ''; ?>>
                        <?php echo h($respNombre); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="checkbox">
            <label>
                <?php echo $this->Form->checkbox('datos_tributarios_revisados', array('hiddenField' => true)); ?>
                <?php echo __('Datos tributarios revisados'); ?>
                <span class="text-muted">(márquelo cuando confirme con el cliente lo anterior)</span>
            </label>
        </div>
    </div>
</div>
