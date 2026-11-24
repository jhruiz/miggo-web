<?php $this->layout = 'inicio'; ?>
<?php echo $this->Html->script('ubicacion/obtenerubicacion'); ?>
<?php echo $this->Html->script('impuestos/retenciones'); ?>
<?php
$fmt = function ($n) {
    return rtrim(rtrim(number_format((float) $n, 4, ',', '.'), '0'), ',');
};
// La combinación (tributo, tipo de ítem, ciudad, condición, tipo de valor) se conserva
$bloqueados = array('tax_id', 'aplica_a', 'departamento_id', 'ciudadesmiggo_id', 'condicion_declarante', 'tipo_valor');
?>
<div class="impuestos form">
    <?php echo $this->element('Impuestos/pestanas', array('activa' => 'retenciones')); ?>
    <?php echo $this->Form->create('Retencionventa', array('url' => array('controller' => 'impuestos', 'action' => 'nuevavigencia', $anterior['id']))); ?>
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '20', 'id' => 'menuvert', 'name' => 'menuvert')); ?>
    <fieldset>
        <legend><h2><b><?php echo __('Nueva vigencia'); ?></b></h2></legend>
        <div class="alert alert-info">
            Vigencia actual de <b><?php echo h($anterior['nombre']); ?></b>:
            tarifa <?php echo h($fmt($anterior['valor']) . ($anterior['tipo_valor'] === 'POR_MIL' ? ' por mil' : ' %')); ?>,
            base mínima <?php echo h($fmt($anterior['base_minima_uvt'])); ?> UVT,
            desde <?php echo h($anterior['vigente_desde']); ?><?php echo !empty($anterior['vigente_hasta']) ? ' hasta ' . h($anterior['vigente_hasta']) : ''; ?>.
            <br>Al guardar, la vigencia actual quedará hasta el día anterior a la nueva fecha <b>Vigente desde</b>, y se creará una nueva con los valores de este formulario.
        </div>
        <?php echo $this->element('Impuestos/form_retencion', array(
            'bloqueados'    => $bloqueados,
            'sugerirCuenta' => false,
        )); ?>
    </fieldset>
    <?php echo $this->Form->submit(__('Crear nueva vigencia'), array('class' => 'btn btn-primary', 'div' => false)); ?>
    <?php echo $this->Html->link(__('Cancelar'), array('action' => 'retenciones'), array('class' => 'btn btn-default')); ?>
    <?php echo $this->Form->end(); ?>
</div>
