<?php $this->layout = 'inicio';?>
<div class="depositosClientes form">
<?php echo $this->Form->create('DepositosCliente', array('type' => 'post', 'class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Cliente - Bodega'); ?></b></h2></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '16', 'id' => 'menuvert')) ?>
                <?php echo $this->Form->input('id'); ?>

                <div class="row">
                    <div class="form-group">
                        <label for='DepositosClienteClienteId'>Cliente</label>
                        <?php echo $this->Form->input('cliente_id', array('class' => 'form-control', 'label' => '')); ?>
                    </div>
                </div><br>

                <div class="row">
                    <div class="form-group">
                        <label for="DepositosClienteDepositoId">Bodega</label>
                        <?php echo $this->Form->input('deposito_id', array('class' => 'form-control', 'label' => '')); ?>
                    </div>
                </div>

	</fieldset><br><br>
<?php echo $this->Form->submit('Guardar', array('class' => 'btn btn-primary')); ?>
</div>
