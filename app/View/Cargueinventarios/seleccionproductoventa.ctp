<?php 
$this->layout=false;
echo ($this->Html->script('seleccionproductoventa/seleccionproductoventa.js'));
?>
<legend><center><h4><?php echo __('Información del Producto'); ?></h4></center></legend>           
<section class="main row">
    <div class="col-md-6">                
        <div class="thumbnail">
            <?php if($arrProducto['Producto']['imagen'] == ""){ ?>
                <?php echo $this->Html->image('png/image-4.png', array('alt' => 'CakePHP', 'style' => 'max-width: 250px; max-height: 250px;')); ?>  
            <?php }else{?>
            <img src="<?php echo $urlImgProducto . $arrProducto['Producto']['empresa_id'] . "/" . $arrProducto['Producto']['imagen'];?>" class="img-responsive img-rounded center-block" style="max-width: 250px; max-height: 250px;" />
            <?php }?>     
        <div class="caption">
            <legend><h4><b><?php echo $arrProducto['Producto']['descripcion'] . " - " . $arrProducto['Producto']['codigo']; ?></b></h4></legend>
            Existencia Actual: <?php echo $arrProducto['Cargueinventario']['existenciaactual']; ?> <br>
            Precio de Venta: $ <?php echo number_format($arrProducto['Cargueinventario']['precioventa'],2); ?> <br>
            Precio Máximo: $ <?php echo number_format($arrProducto['Cargueinventario']['preciomaximo'],2); ?> <br>
            Precio Mínimo: $ <?php echo number_format($arrProducto['Cargueinventario']['preciominimo'],2); ?>            
        </div>                
        </div>
        <legend>&nbsp;</legend>
        <b>Impuestos</b><br>        
        <?php foreach ($arrImpuestos as $imp):?>
        <?php echo $imp['Impuesto']['descripcion'] . " : " . $imp['Impuesto']['valor'] . '%';?><br>
        <?php endforeach;?>        
    </div>

    <div class="col-md-6">        
        <input type="hidden" id="precioMinimo" value="<?php echo $arrProducto['Cargueinventario']['preciominimo'];?>">
        <input type="hidden" id="cantidadProducto" value="<?php echo $arrProducto['Cargueinventario']['existenciaactual'];?>">
        <input type="hidden" id="cargueinventarioId" value="<?php echo $arrProducto['Cargueinventario']['id'];?>">  
        <input type="hidden" id="nombreProducto" value="<?php echo $arrProducto['Producto']['descripcion'];?>">
        <input type="hidden" id="codigoProducto" value="<?php echo $arrProducto['Producto']['codigo'];?>">
        <input type="hidden" id="impuesto" value="<?php echo !empty($arrImpuestos) ? $arrImpuestos['0']['Impuesto']['valor'] : '0';?>">
        <input type="hidden" id="prcImpuesto" value="<?php echo $prcImpuesto;?>">
        
        <div class="form-group form-inline"> 
            <label>Cantidad</label><br>
            <div class="input-group">
                <span class="input-group-addon">#</span>                    
                <?php echo $this->Form->input('cantidadventa', array(
                    'label' => '', 
                    'class' => 'form-control quant_sale', 
                    'placeholder' => 'Cantidad', 
                    'value' => '1', 
                    'onblur' => 'validarCantidadStock();'
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
                    'onblur' => 'calcularTotales();',
                    'value' => $arrProducto['Cargueinventario']['precioventa'])); ?>
            </div>
        </div> 

        <div class="form-group form-inline"> 
            <label>Precio de Venta A.I.</label><br>
            <div class="input-group">
                <span class="input-group-addon">$</span>                    
                <?php echo $this->Form->input('precioventaCI', array(
                    'label' => '', 
                    'class' => 'form-control numericPrice salePriceCI', 
                    'placeholder' => 'Precio de Venta', 
                    'value' => $vlrAntesImp,                     
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
                    'onblur' => 'calcularDescuentoPorPorcentaje();')); ?>
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
                    'onblur' => 'calcularDescuentoPorValor();')); ?>
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
                    'value' => $vlrImpuesto )); ?>
            </div>
        </div>        
        
        <div class="form-group form-inline"> 
            <label>Total con IVA</label><br>
            <div class="input-group">
                <span class="input-group-addon">$</span>                    
                <?php echo $this->Form->input('valorConIva', array(
                    'label' => '', 
                    'class' => 'form-control numericPrice', 
                    'placeholder' => 'Valor Descuento', 
                    'disabled' => true,
                    'value' => intval($vlrAntesImp + $vlrImpuesto) )); ?>
            </div>
        </div>               
    </div>
</section>    
<div class="container-fluid">
    <button  id="btn_guardarEst" class="btn btn-primary center-block" onclick="agregarProductoFactura()">Agregar</button>
</div>

