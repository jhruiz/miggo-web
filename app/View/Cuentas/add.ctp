<?php $this->layout = 'inicio';?>
<?php echo ($this->Html->script('obtenermenu/obtenermenu.js')); ?>
<?php echo ($this->Html->script('cuentas/cuentas.js')); ?>
<div class="cuentas form">
<?php echo $this->Form->create('Cuenta', array('type' => 'post', 'class' => 'form-inline')); ?>
	<fieldset>
            <legend><h2><b><?php echo __('Agregar Cuenta'); ?></b></h2></legend>
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '39', 'id' => 'menuvert')) ?>

            <div class="col-md-12" style="margin-bottom: 20px;">

                <div class="form-group col-md-4">
                    <label for="CuentaDescripcion">Nombre Cuenta</label>
                    <?php echo $this->Form->input('descripcion', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Nombre de la Cuenta')); ?>
                </div>

                <div class="form-group col-md-4">
                    <label for="CuentaNumerocuenta">Número Cuenta</label>
                    <?php echo $this->Form->input('numerocuenta', array('label' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Número de la Cuenta')); ?>
                </div>

                <div class="form-group col-md-4">
                    <label for="CuentaSaldo">Saldo</label>
                        <?php echo $this->Form->input('saldo', array('label' => '', 'type' => 'text', 'class' => 'form-control numericPrice', 'placeholder' => 'Saldo de la Cuenta')); ?>
                </div>

                <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
            </div>


	</fieldset>
<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
</div>