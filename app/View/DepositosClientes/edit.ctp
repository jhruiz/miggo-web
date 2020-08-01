<?php $this->layout='inicio'; ?>
<div class="depositosClientes form">
<?php echo $this->Form->create('DepositosCliente', array('class' => 'form-inline')); ?>
	<fieldset>
		<legend><h2><b><?php echo __('Editar Cliente - Depósito'); ?></b></h2></legend>
		<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '16', 'id' => 'menuvert'))?>
                <?php echo $this->Form->input('id'); ?>
                
                <div class="row"> 
                    <div class="form-group">
                        <label for='DepositosClienteClienteId'>Cliente</label>
                        <?php echo $this->Form->input('cliente_id', array('class' => 'form-control', 'label' => '')); ?>
                    </div>   
                </div><br>   
                
                <div class="row"> 
                    <div class="form-group"> 
                        <label for="DepositosClienteDepositoId">Depósito</label>
                        <?php echo $this->Form->input('deposito_id', array('class' => 'form-control', 'label' => '')); ?>
                    </div>
                </div>
             
	</fieldset><br><br>
<?php echo $this->Form->submit('Guardar',array('class'=>'btn btn-primary'));?>
</div><br>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Clientes - Depósitos'), array('action' => 'index')); ?></li>
	</ul>
</div>
