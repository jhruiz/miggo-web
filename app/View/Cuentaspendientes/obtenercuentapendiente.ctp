<?php $this->layout=false;?>
<?php echo ($this->Html->script('cuentaspendientes/cuentaspendientes.js')); ?>
<legend><center><h4><?php echo __('Pago de Cuenta Pendiente'); ?></h4></center></legend>
    <div class="row" id="pagarCuenta"> 
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-8">                                
                <?php echo $this->Form->input('totalCuenta', array('id' => 'totalCuenta', 'value' => $datosCuentaPendiente['Cuentaspendiente']['totalobligacion'], 'readonly' => 'readonly', 'class' => 'form-control numericPrice'))?><br>                
                <?php echo $this->Form->input('totalPago', array('id' => 'totalPago', 'value' => $datosCuentaPendiente['Cuentaspendiente']['totalobligacion'], 'class' => 'form-control numericPrice', 'onblur' => 'validarPagoPendiente()'))?><br>
                <?php echo $this->Form->input('totalRestante', array('id' => 'totalRestante', 'value' => '0', 'class' => 'form-control numericPrice', 'readonly' => 'readonly'))?><br>
                <?php echo $this->Form->input('cuenta_id', array('id' => 'cuentaId', 'class' => 'form-control', 'type' => 'select', 'options' => $listaCuentas, 'empty' => 'Seleccione Una..', 'onchange' => 'validarSaldoCuenta();'))?><br>
                <?php echo $this->Form->input('cuentapendiente_id', array('type' => 'hidden', 'value' => $cuentaId)); ?>
        </div>
        <div class="col-md-2">&nbsp;</div>
    </div>
    <button id="btn_pagarcuenta" class="btn btn-primary center-block" onclick="pagarCuentaPendienteProv();">Pagar</button>