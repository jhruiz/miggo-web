<?php $this->layout=false;?>
<?php echo ($this->Html->script('cuentasclientes/cuentasclientes.js')); ?>
<?php
// Valor sugerido en dinero: saldo - retenciones pendientes (sin bajar de 0)
$saldoCxc = (float) $datosCuentaPendiente['Cuentascliente']['totalobligacion'];
$totalRetPend = 0;
foreach ($retencionesPendientes as $rp) {
    $totalRetPend += $rp['pendiente'];
}
$dineroSugerido = max(0, round($saldoCxc - $totalRetPend, 2));
?>
<legend><center><h4><?php echo __('Pago de Cuenta Pendiente'); ?></h4></center></legend>
    <div class="row" id="pagarCuenta"> 
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-8">                                
                <?php echo $this->Form->input('totalCuenta', array('id' => 'totalCuenta', 'value' => $datosCuentaPendiente['Cuentascliente']['totalobligacion'], 'readonly' => 'readonly', 'class' => 'form-control numberPrice'))?><br>

                <?php if (!empty($retencionesPendientes)) { ?>
                    <div id="bloqueRetencionesCxc" class="well well-sm">
                        <label><?php echo __('Retenciones que practica el cliente'); ?></label>
                        <?php foreach ($retencionesPendientes as $rp) { ?>
                            <div class="form-inline" style="margin-bottom: 5px;">
                                <label style="min-width: 110px;"><?php echo h($rp['etiqueta']); ?></label>
                                <input type="text" class="form-control valueRetencionCxc" style="max-width: 160px;"
                                       data-tributo="<?php echo h($rp['tributo']); ?>" data-etiqueta="<?php echo h($rp['etiqueta']); ?>"
                                       value="<?php echo number_format($rp['pendiente'], 2, '.', ''); ?>" onblur="validarPagoCuenta()">
                            </div>
                        <?php } ?>
                        <small class="text-muted"><?php echo __('Valores pendientes por aplicar. Cámbielos si el cliente retuvo otro valor, o ponga 0 si no retuvo. No entran a ninguna caja ni banco.'); ?></small>
                    </div>
                <?php } ?>

                <?php echo $this->Form->input('totalPago', array('id' => 'totalPago', 'label' => !empty($retencionesPendientes) ? 'Total Pago (dinero recibido)' : null, 'value' => !empty($retencionesPendientes) ? $dineroSugerido : $datosCuentaPendiente['Cuentascliente']['totalobligacion'], 'class' => 'form-control numberPrice', 'onblur' => 'validarPagoCuenta()'))?><br>
                <?php echo $this->Form->input('totalRestante', array('id' => 'totalRestante', 'value' => '', 'class' => 'form-control numberPrice', 'readonly' => 'readonly'))?><br>
                <?php echo $this->Form->input('tipopago_id', array('id' => 'tipopagoId', 'label' => 'Tipo Pago', 'class' => 'form-control', 'type' => 'select', 'options' => $listaTiposPagos, 'empty' => 'Seleccione Una..'))?><br>
                <?php echo $this->Form->input('cuentapendiente_id', array('type' => 'hidden', 'value' => $cuentaId)); ?>
        </div>
        <div class="col-md-2">&nbsp;</div>
    </div>
    <button id="btn_pagarcuenta" class="btn btn-primary center-block" onclick="pagarCuentaPendiente();">Pagar</button>
