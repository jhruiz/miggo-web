<?php $this->layout = 'inicio';?>
<div class="depositosUsuarios form">
<?php echo $this->Form->create('DepositosUsuario', array('type' => 'post', 'class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Agregar Usuario - Depósito'); ?></b></h2></legend>
                <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '17', 'id' => 'menuvert')) ?>

                <div class="row">
                    <div class="form-group">
                        <label for="DepositosUsuarioUsuarioId">Usuario</label>
                        <?php echo $this->Form->input('usuario_id', array('class' => 'form-control', 'label' => '')); ?>
                    </div>
                </div><br>

                <div class="row">
                    <div class="form-group">
                        <label for="DepositosUsuarioDepositoId">Depósito</label>
                        <?php echo $this->Form->input('deposito_id', array('label' => '', 'class' => 'form-control')); ?>
                    </div>
                </div>

                    <?php echo $this->Form->input('empresa_id', array('type' => 'hidden', 'value' => $empresaId)); ?>
	</fieldset><br><br>
<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
</div>
