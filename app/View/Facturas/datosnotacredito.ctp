<?php 
$this->layout=false;
echo ($this->Html->script('facturas/pagonotacredito.js'));
echo $this->Html->css('facturas/facturar.css', array('rel' => 'stylesheet', 'media' => 'all'));
?>
<div class="form_contenedor_pagos">
    <div class="row contenedor_metodos_pagos"> 
        <div class="col-md-12">                                
            <?php echo $this->Form->input('totalFactura', array('id' => 'totalFactura', 'value' => $ttalFact, 'type' => 'hidden'))?><br>
            <?php echo $this->Form->input('factura_id', array('id' => 'facturaId', 'value' => $facturaId, 'type' => 'hidden'))?><br>
            <?php echo $this->Form->input('syncdian', array('id' => 'syncdian', 'value' => $syncDian, 'type' => 'hidden'))?><br>
            <div class="col-md-6"><label>TOTAL</label></div>
            <div class="col-md-6"><label id="ttalFactura"><?php echo h(number_format($ttalFact, 2));?></label></div><br>
            <div class="col-md-6"><label>RESTANTE</label></div>
            <div class="col-md-6"><label id="restante"><?php echo h(number_format($ttalFact, 2));?></label></div><br><br>
        </div>

        <div class="contenedor_pagos">
            <div class="dv_tip_val_pago form-group form-inline col-md-12">
                <div class="col-md-6">
                    <label>Tipo</label>
                    <?php echo $this->Form->input('tipopago', array('label' => '','type' => 'select', 'options' => $lstTipPagos, 'class' => 'form-control method_fact'));?>                    
                </div>
                <div class="col-md-6">
                    <label>Valor</label>
                    <?php echo $this->Form->input('valorFactura', array('label' => '', 'tupe' => 'number', 'value' => '', 'class' => 'form-control numericPrice valueFact'))?>
                </div>
                <div class="del_pay_meth" style="cursor: pointer;"><i class="fa fa-trash"></i></div>
                <legend>&nbsp</legend>                
            </div>                                                       
        </div>

    </div>
    <div class="container-flui">
        <div class="col-md-6">
            <button id="btn_notacredito_m" class="btn btn-primary center-block">Generar Nota Crédito</button>
        </div>
        <div class="col-md-6">
            <button id="btn_agregar" class="btn btn-primary center-block">Agregar Método</button>
        </div>    
    </div>

    <div class="icon-container" style="display: none;">
        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
        <span class="icon-text" id="fact_status">Generando nota crédito en Miggo</span>
    </div>
</div>