<?php 
$this->layout=false;
echo ($this->Html->script('seleccionproductoventa/seleccionproductoventa.js'));
?>
<legend><center><h4><?php echo __('Información del Producto1'); ?></h4></center></legend>           
<section class="main row">
    <div class="col-md-6">                
        <div class="thumbnail">
        <div class="caption">
            <legend><h4><b><?php echo $arrProducto['Producto']['descripcion'] . " - " . $arrProducto['Producto']['codigo']; ?></b></h4></legend>
            Existencia Actual: <?php echo $arrProducto['Cargueinventario']['existenciaactual']; ?> <br>
            Precio de Venta: $ <?php echo number_format($arrProducto['Cargueinventario']['precioventa'],2); ?> <br>
            Precio Máximo: $ <?php echo number_format($arrProducto['Cargueinventario']['preciomaximo'],2); ?> <br>
            Precio Mínimo: $ <?php echo number_format($arrProducto['Cargueinventario']['preciominimo'],2); ?>            
        </div>                
        </div>

        <?php if($esFactura == '1') {?>
            <legend>&nbsp;</legend>
            <b>Impuestos</b><br>        
            <?php foreach ($arrImpuestos as $imp):?>

            <?php 
                $valor = $imp['IMP']['valoprc'] == '0' ? '$' . $imp['IMP']['valor'] : $imp['IMP']['valor'] . '%';
            ?>

            <?php echo $imp['TX']['name'] . ": " . $valor;?><br>
            <?php endforeach;?>  
            

        <?php } ?>
    </div>

    <div class="col-md-6">        
        <input type="hidden" id="precioVenta" value="<?php echo $arrProducto['Cargueinventario']['precioventa'];?>">
        <input type="hidden" id="precioMinimo" value="<?php echo $arrProducto['Cargueinventario']['preciominimo'];?>">
        <input type="hidden" id="cantidadProducto" value="<?php echo $arrProducto['Cargueinventario']['existenciaactual'];?>">
        <input type="hidden" id="cargueinventarioId" value="<?php echo $arrProducto['Cargueinventario']['id'];?>">  
        <input type="hidden" id="nombreProducto" value="<?php echo $arrProducto['Producto']['descripcion'];?>">
        <input type="hidden" id="codigoProducto" value="<?php echo $arrProducto['Producto']['codigo'];?>">
        <input type="hidden" id="vtaInventario" value="<?php echo $arrProducto['Producto']['inventario'];?>">
        <input type="hidden" id="prcIVA" value="<?php echo ($dataImpuestos['tasaIvaDecimal'])?>">
        <input type="hidden" id="prcINC" value="<?php echo ($dataImpuestos['tasaIncDecimal'])?>">
        <input type="hidden" id="valINCBolsa" value="<?php echo ($dataImpuestos['tasaIncBolsa'])?>">
        
        <div class="form-group form-inline"> 
            <label>Cantidad</label><br>
            <div class="input-group">
                <span class="input-group-addon">#</span>                    
                <?php echo $this->Form->input('cantidadventa', array(
                    'label' => '', 
                    'class' => 'form-control quant_sale', 
                    'placeholder' => 'Cantidad', 
                    'value' => '1', 
                    'onchange' => 'recalculoValores();'
                    )); ?>
            </div>
        </div> 
        
        <div class="form-group form-inline"> 
            <label>Precio de Venta U.</label><br>
            <div class="input-group">
                <span class="input-group-addon">$</span>                    
                <?php echo $this->Form->input('precioventa', array(
                    'label' => '', 
                    'class' => 'form-control numericPrice salePriceWI', 
                    'placeholder' => 'Precio de Venta', 
                    'onchange' => 'recalculoValores();',
                    'value' => $arrProducto['Cargueinventario']['precioventa'])); ?>
            </div>
        </div> 

        <div class="form-group form-inline"> 
            <label>Valor Base</label><br>
            <div class="input-group">
                <span class="input-group-addon">$</span>                    
                <?php echo $this->Form->input('precioventaCI', array(
                    'label' => '', 
                    'class' => 'form-control numericPrice salePriceCI', 
                    'placeholder' => 'Precio de Venta', 
                    'value' => $dataImpuestos['valorBase'],                     
                    'disabled' => true)); ?>
            </div>
        </div> 
        
        <div class="form-group form-inline"> 
            <label>Porcentaje Descuento</label><br><br>
            <div class="input-group">
                <span class="input-group-addon">%</span>                    
                <?php echo $this->Form->input('porcentaje', array(
                    'label' => '', 
                    'class' => 'form-control porcent_discount', 
                    'placeholder' => 'Porcentaje Descuento', 
                    'value' => '0',
                    'min' => '0',
                    'max' => '100',
                    'onchange' => 'recalculoValores();')); ?>
            </div>
        </div>
        
        <div class="form-group form-inline"> 
            <label>Descuento</label><br>
            <div class="input-group">
                <span class="input-group-addon">$</span>                    
                <?php echo $this->Form->input('descuento', array(
                    'label' => '', 
                    'class' => 'form-control numericPrice val_discount', 
                    'placeholder' => 'Valor Descuento', 
                    'value' => '0',                   
                    'disabled' => true)); ?>
            </div>
        </div>       
        
        <div class="form-group form-inline"> 
            <label>Valor IVA</label><br>
            <div class="input-group">
                <span class="input-group-addon">$</span>                    
                <?php echo $this->Form->input('valorIva', array(
                    'label' => '', 
                    'class' => 'form-control numericPrice', 
                    'placeholder' => 'Valor Descuento', 
                    'disabled' => true,
                    'value' => $dataImpuestos['valorIva'] )); ?>
            </div>
        </div>        
        
        <div class="form-group form-inline"> 
            <label>Valor ICA</label><br>
            <div class="input-group">
                <span class="input-group-addon">$</span>                    
                <?php echo $this->Form->input('valorIca', array(
                    'label' => '', 
                    'class' => 'form-control numericPrice',
                    'disabled' => true,
                    'value' => $dataImpuestos['valorInc'] )); ?>
            </div>
        </div>        
        
        <div class="form-group form-inline"> 
            <label>Valor INC Bolsas</label><br>
            <div class="input-group">
                <span class="input-group-addon">$</span>                    
                <?php echo $this->Form->input('valorIncBosa', array(
                    'label' => '', 
                    'class' => 'form-control numericPrice',
                    'disabled' => true,
                    'value' => $dataImpuestos['tasaIncBolsa'] )); ?>
            </div>
        </div>        
        
        <div class="form-group form-inline"> 
            <label>Total con Impuestos</label><br>
            <div class="input-group">
                <span class="input-group-addon">$</span>                    
                <?php echo $this->Form->input('valorConIva', array(
                    'label' => '', 
                    'class' => 'form-control numericPrice', 
                    'disabled' => true,
                    'value' => $dataImpuestos['valorBase'] + $dataImpuestos['valorIva'] + $dataImpuestos['valorInc']) ); ?>
            </div>
        </div>               
    </div>
</section>    
<div class="container-fluid">
    <button  id="btn_guardarEst" class="btn btn-primary center-block" onclick="agregarProductoFactura()">Agregar</button>
</div>

