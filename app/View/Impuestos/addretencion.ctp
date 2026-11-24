<?php $this->layout = 'inicio'; ?>
<?php echo $this->Html->script('ubicacion/obtenerubicacion'); ?>
<?php echo $this->Html->script('impuestos/retenciones'); ?>
<div class="impuestos form">
    <?php echo $this->element('Impuestos/pestanas', array('activa' => 'retenciones')); ?>
    <?php echo $this->Form->create('Retencionventa', array('url' => array('controller' => 'impuestos', 'action' => 'addretencion'))); ?>
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '20', 'id' => 'menuvert', 'name' => 'menuvert')); ?>
    <fieldset>
        <legend><h2><b><?php echo __('Nueva retención'); ?></b></h2></legend>
        <?php echo $this->element('Impuestos/form_retencion', array(
            'bloqueados'    => array(),
            'sugerirCuenta' => true,
        )); ?>
    </fieldset>
    <?php echo $this->Form->submit(__('Guardar'), array('class' => 'btn btn-primary', 'div' => false)); ?>
    <?php echo $this->Html->link(__('Cancelar'), array('action' => 'retenciones'), array('class' => 'btn btn-default')); ?>
    <?php echo $this->Form->end(); ?>
</div>
