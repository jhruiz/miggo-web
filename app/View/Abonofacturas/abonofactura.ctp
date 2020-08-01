<?php 
$this->layout=false;
echo ($this->Html->script('facturas/abonos.js'));
?>
<legend><center><h4><?php echo __('Abonoa a factura'); ?></h4></center></legend>
    <div class="row" id="credicontado"> 
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-8">                                
                <?php echo $this->Form->input('totalventa', array('id' => 'totalVenta', 'value' => $ttales, 'readonly' => 'readonly', 'class' => 'form-control numericPrice'))?><br>
                <?php echo $this->Form->input('tipopagos', array('label' => 'Tipo pago', 'type' => 'select', 'options' => $arrTiposPago, 'class' => 'form-control'));?><br>
                <?php echo $this->Form->input('abono', array('id' => 'ttalAbono', 'value' => '', 'class' => 'form-control numericPrice'))?><br>
        </div>
        <div class="col-md-2">&nbsp;</div>
    </div><br><br>
    <button id="btn_facturar" class="btn btn-primary center-block" onclick="realizarAbono()">Abonar</button>