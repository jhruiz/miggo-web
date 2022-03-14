<?php $this->layout = 'inicio';?>
<div class="tipopagos form">
<?php echo $this->Form->create('Tipopago', array('type' => 'post', 'class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Tipo de Pago'); ?></b></h2></legend>
                <?php echo $this->Form->input('id'); ?>

            <div class="form-group">
                <label for="TipopagoDescripcion">Nombre</label>
                <?php echo $this->Form->input('descripcion', array('label' => '', 'class' => 'form-control', 'placeholder' => 'Nombre del Tipo de Pago')); ?>
            </div><br><br>


            <div class="form-group">
                <label for="TipopagoEstadoId">Estado</label>
                <?php echo $this->Form->input('estado_id', array('label' => '', 'class' => 'form-control')); ?>
            </div><br><br>

            <div class="form-group">
                <label for="TipopagoCuenta">Cuenta</label>
                <?php echo $this->Form->input('cuenta', array(
    'label' => '',
    'name' => 'data[Tipopago][cuenta_id]',
    'type' => 'select',
    'options' => $listCtas,
    'empty' => 'Sin Cuenta',
    'class' => 'form-control',
    'default' => $default,
)); ?>
            </div><br><br>

            <div class="form-group">
                <label for="TipopagoContabilizar">Crédito Interno</label>
                <?php echo $this->Form->input('contabilizar', array('label' => '', 'name' => 'data[Tipopago][contabilizar]', 'type' => 'checkbox', 'class' => 'form-control')); ?>
            </div>

                <div class="form-group form-inline">
                    <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
                </div>
	</fieldset>
<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
</div>