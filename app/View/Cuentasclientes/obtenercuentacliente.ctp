<?php $this->layout=false;?>
<?php echo ($this->Html->script('cuentasclientes/cuentasclientes.js')); ?>
<legend><center><h4><?php echo __('Pago de Cuenta Pendiente'); ?></h4></center></legend>
    <div class="row" id="pagarCuenta"> 
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-8">                                
                <?php echo $this->Form->input('totalCuenta', array('id' => 'totalCuenta', 'value' => $datosCuentaPendiente['Cuentascliente']['totalobligacion'], 'readonly' => 'readonly', 'class' => 'form-control numberPrice'))?><br>                
                <?php echo $this->Form->input('totalPago', array('id' => 'totalPago', 'value' => $datosCuentaPendiente['Cuentascliente']['totalobligacion'], 'class' => 'form-control numberPrice', 'onblur' => 'validarPagoCuenta()'))?><br>
                <?php echo $this->Form->input('totalRestante', array('id' => 'totalRestante', 'value' => '', 'class' => 'form-control numberPrice', 'readonly' => 'readonly'))?><br>
                <?php echo $this->Form->input('tipopago_id', array('id' => 'tipopagoId', 'label' => 'Tipo Pago', 'class' => 'form-control', 'type' => 'select', 'options' => $listaTiposPagos, 'empty' => 'Seleccione Una..'))?><br>
                <?php echo $this->Form->input('cuentapendiente_id', array('type' => 'hidden', 'value' => $cuentaId)); ?>
        </div>
        <div class="col-md-2">&nbsp;</div>
    </div>
    <button id="btn_pagarcuenta" class="btn btn-primary center-block" onclick="pagarCuentaPendiente();">Pagar</button>