<?php 
$this->layout=false;
echo ($this->Html->script('facturas/tiposPago.js'));
?>
<div class="form_contenedor_pagos">
    <div class="row contenedor_metodos_pagos"> 
        <div class="col-md-12">                                
            <?php echo $this->Form->input('totalventa', array('id' => 'totalVentaTipos', 'value' => $totalFacturar, 'type' => 'hidden'))?><br>
            <div class="col-md-6"><label>TOTAL</label></div>
            <div class="col-md-6"><label id="ttalFactura"><?php echo h(number_format($totalFacturar, 2));?></label></div><br>
            <div class="col-md-6"><label>RESTANTE</label></div>
            <div class="col-md-6"><label id="restante"><?php echo h(number_format($totalFacturar, 2));?></label></div><br><br>
        </div>
        <legend>&nbsp</legend>    
        <div class="contenedor_pagos">
            <div class="dv_tip_val_pago form-group form-inline col-md-12">
                <div class="col-md-6">
                    <label>Tipo</label>
                    <?php echo $this->Form->input('tipopago', array('label' => '','tupe' => 'select', 'options' => $listTipPag, 'class' => 'form-control method_fact', 'style' => 'max-width:20vw !important'));?>                    
                </div>
                <div class="col-md-6">
                    <label>Valor</label>
                    <?php echo $this->Form->input('valorFactura', array('label' => '', 'tupe' => 'number', 'value' => '', 'class' => 'form-control numericPrice valueFact'))?>
                </div>
                <div class="col-md-6 divValueClientPaid">
                    <label>Paga con: </label>
                    <?php echo $this->Form->input('pago', array('label' => '', 'tupe' => 'number', 'value' => '0', 'class' => 'form-control numericPrice valueClientPaid'))?>
                </div>
                <div class="col-md-6 divDevolution">
                    <label>Devolución</label><br>
                    <?php echo $this->Form-> label('devolucion',"0", array('id'=>"devolucion", 'class'=>'control-label numericDevolution devolution'))?>
                </div>
                <div class="col-md-12">
                    <i class="fa fa-trash del_pay_meth" style="cursor: pointer"></i>
                </div>    
                <legend>&nbsp</legend>    

            </div>                                                       
        </div>

    </div>
    <div class="container-flui">
        <div class="col-md-6">
            <button id="btn_facturar_m" class="btn btn-primary center-block">Facturar</button>
        </div>
        <div class="col-md-6">
            <button id="btn_agregar" class="btn btn-primary center-block">Agregar Método</button>
        </div>    
    </div>
</div>