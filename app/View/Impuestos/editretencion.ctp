<?php $this->layout = 'inicio'; ?>
<?php echo $this->Html->script('ubicacion/obtenerubicacion'); ?>
<?php echo $this->Html->script('impuestos/retenciones'); ?>
<?php
// Si ya se usó en facturas, solo se editan nombre, cuenta contable, vigente hasta y estado
$bloqueados = $enUso
    ? array('tax_id', 'aplica_a', 'departamento_id', 'ciudadesmiggo_id', 'condicion_declarante', 'tipo_valor', 'valor', 'base_minima_uvt', 'vigente_desde')
    : array();
?>
<div class="impuestos form">
    <?php echo $this->element('Impuestos/pestanas', array('activa' => 'retenciones')); ?>
    <?php echo $this->Form->create('Retencionventa', array('url' => array('controller' => 'impuestos', 'action' => 'editretencion', $this->request->data['Retencionventa']['id']))); ?>
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '20', 'id' => 'menuvert', 'name' => 'menuvert')); ?>
    <?php echo $this->Form->input('id'); ?>
    <fieldset>
        <legend><h2><b><?php echo __('Editar retención'); ?></b></h2></legend>
        <?php if ($enUso): ?>
            <div class="alert alert-info">
                Esta retención ya se usó en facturas, así que su tarifa, base mínima, vigencia inicial y combinación no se pueden cambiar.
                Para cambiar la tarifa o la base mínima use
                <?php echo $this->Html->link(__('Nueva vigencia'), array('action' => 'nuevavigencia', $this->request->data['Retencionventa']['id'])); ?>.
            </div>
        <?php endif; ?>
        <?php echo $this->element('Impuestos/form_retencion', array(
            'bloqueados'    => $bloqueados,
            'sugerirCuenta' => false,
        )); ?>
    </fieldset>
    <?php echo $this->Form->submit(__('Guardar'), array('class' => 'btn btn-primary', 'div' => false)); ?>
    <?php echo $this->Html->link(__('Cancelar'), array('action' => 'retenciones'), array('class' => 'btn btn-default')); ?>
    <?php echo $this->Form->end(); ?>
</div>
