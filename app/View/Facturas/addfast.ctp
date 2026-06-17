<?php $this->layout = 'inicio';?>
<?php 
    echo $this->Html->css('facturas/factfast.css', array('rel' => 'stylesheet'));
?>

<?php echo ($this->Html->script('facturas/imprimirticketfast.js')); ?>
<?php echo ($this->Html->script('facturas/facturas.js')); ?>
<?php echo ($this->Html->script('facturas/tiposPago.js')); ?>
<?php echo ($this->Html->script('facturas/abonos.js')); ?>
<?php echo ($this->Html->script('bandeja/gestionBandejas.js')); ?>
<?php echo ($this->Html->script('facturas/calcularValoresProducto.js')); ?>
<?php echo ($this->Html->script('facturas/syncdian.js')); ?>
<?php echo ($this->Html->script('abonos/gestionabonos.js')); ?>
<?php echo ($this->Html->script('facturas/factfast.js')); ?>

<?php echo $this->Form->create('Factura', array('id' => 'FacturaAddForm')); ?>
<?php echo $this->Form->input('idcliente', array('type' => 'hidden', 'class' => 'id_cliente')); ?>
<?php echo $this->Form->input('empresa', array('type' => 'hidden', 'value' => $empresaId, 'id' => 'empresaId')); ?>
<?php echo $this->Form->input('usuario', array('type' => 'hidden', 'value' => $usuarioId, 'id' => 'usuarioId')); ?>
<?php echo $this->Form->input('tipoFactura', array('type' => 'hidden', 'value' => $infoEmpresa['Empresa']['syncdian'], 'id' => 'tipoFactura')); ?>
<?php echo $this->Form->input('syncdian', array('type' => 'hidden', 'value' => $infoEmpresa['Empresa']['syncdian'], 'id' => 'syncdian')); ?>
<?php echo $this->Form->input('prefacturaId', array('label' => '', 'value' => '', 'type' => 'hidden', 'id' => 'prefacturaId')); ?>
<?php echo $this->Form->input('esFacturaDV', array('type' => 'hidden', 'value' => $esFactura, 'id' => 'esFacturaDV')) ?>

<div style="display:none;">
    <?php echo $this->Form->input('nitcliente'); ?>
    <?php echo $this->Form->input('telefonocliente'); ?>
    <?php echo $this->Form->input('dircliente'); ?>
    <?php echo $this->Form->input('rapidanombre'); ?>
    <?php echo $this->Form->input('rapidanit'); ?>
    <?php echo $this->Form->input('rapidatelefono'); ?>
    <?php echo $this->Form->input('rapidadireccion'); ?>
    <?php echo $this->Form->input('f_ref_or'); ?>
    <?php echo $this->Form->input('n_ref_or'); ?>
    <textarea id="obs_fact" name="data[Factura][observacion]"></textarea>
</div>

<div class="miggo-pos-container">

    <div class="pos-main-layout">
        
        <div class="pos-left-panel">
            
            <div class="pos-operational-header mb-3">
                <div class="pos-top-cards-row">
                    <?php if (!empty($topSales)): ?>
                        <?php foreach ($topSales as $val): ?>
                            <div class="pos-mini-card" onclick="agregarProductoPorClick('<?php echo $val['Producto']['codigo']; ?>')">
                                
                                <div class="mini-card-photo-wrapper">
                                    <?php 
                                        $nombreImagen = !empty($val['Producto']['imagen']) ? "productos/{$empresaId}/" . $val['Producto']['imagen'] : 'productos/no-image-placeholder.jpg';
                                        
                                        echo $this->Html->image( $nombreImagen, array(
                                            'class' => 'img-responsive mini-card-img-thumb',
                                            'onerror' => "this.src='" . $this->webroot . "img/productos/default.png';"
                                        )); 
                                    ?>
                                </div>

                                <div class="mini-card-info">
                                    <span class="mini-card-title"><?php echo $val['Producto']['descripcion'] . ' (' . $val['Producto']['codigo'] . ')'; ?></span><br>
                                    <span class="mini-card-meta">Más vendido hoy</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="pos-search-grid mb-3">
                <div class="row m-0 align-items-center">
                    
                    <div class="col-sm-5 pl-0 pr-3 position-relative">
                        <div class="search-wrapper-pos">
                            <i class="fa fa-user search-icon-inside text-primary"></i>
                            <?php echo $this->Form->input('datoscliente', array('label' => false, 'div' => false, 'class' => 'form-control pos-input-search', 'autocomplete' => 'off', 'placeholder' => 'Buscar o asignar cliente...', 'id' => 'FacturaDatoscliente', 'onkeyup' => 'fnObtenerDatosCliente();')); ?>
                        </div>
                        <div id="datosCliente" class="pos-predictive-dropdown list-group"></div>
                    </div>
                    
                    <div class="col-sm-5 px-2 position-relative">
                        <div class="search-wrapper-pos">
                            <i class="fa fa-search search-icon-inside text-success"></i>
                            <?php echo $this->Form->input('producto', array('label' => false, 'div' => false, 'class' => 'form-control pos-input-search', 'autocomplete' => 'off', 'placeholder' => 'Buscar Artículo o Código...', 'id' => 'FacturaProducto', 'onkeyup' => 'obtenerDatosProductoVentaRapida(event);')); ?>
                        </div>
                        <div id="datosProducto" class="pos-predictive-dropdown list-group"></div>
                    </div>
                    
                    <div class="col-sm-2 pr-0 text-right">
                        <div class="stock-indicator-badge">
                            Stock: <b id="lbl-stock-dinamico">0 un.</b>
                        </div>
                    </div>
                </div>
                
                <div style="display:none;">
                    <?php echo $this->Form->input('productoventarapida', array('label' => false, 'id' => 'FacturaProductoventarapida')); ?>
                    <div id="datosProductoventarapida"></div>
                </div>
            </div>

            <div class="pos-grid-products-wrapper">
                <table class="table-pos-grid">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Visualización</th>
                            <th style="width: 35%;">Descripción del Producto</th>
                            <th style="width: 15%;" class="text-center">Cant.</th>
                            <th style="width: 20%;" class="text-right">Precio Unitario</th>
                            <th style="width: 10%;" class="text-center">% Dcto</th>
                            <th style="width: 5%;" class="text-center">&nbsp;</th> 
                        </tr>
                    </thead>
                    <tbody id="productosFacturas"></tbody>
                </table>
            </div>

            <div class="pos-bottom-dock mt-3">
                <div class="row m-0 align-items-center">
                    
                    <div class="col-sm-6 p-2 border-right-dock">
                        <div class="dock-section-title mb-3"><i class="fa fa-credit-card"></i> Formas de Pago Aplicadas</div>

                        <div class="mt-3">
                            <button id="btn_pago_exacto" class="btn btn-miggo-add-pay w-100" type="button">
                                <i class="fa fa-money"></i> Pago exacto
                            </button>
                        </div>
                        
                        <div class="contenedor_pagos">
                            <div class="dv_tip_val_pago row m-0 align-items-end mb-2">
                                <div class="col-xs-5 pl-0 pr-2 form-group-pos mb-0">
                                    <label class="pos-pay-label">Tipo de Pago</label>
                                    <?php echo $this->Form->input('tipopago', array('label' => '','type' => 'select', 'options' => $lstTipPagos, 'class' => 'form-control pos-input method_fact'));?>                    
                                </div>
                                <div class="col-xs-5 px-2 form-group-pos mb-0">
                                    <label class="pos-pay-label">Valor Recibido</label>
                                    <?php echo $this->Form->input('valorFactura', array('label' => '', 'type' => 'text', 'value' => '', 'class' => 'form-control pos-input numericPrice valueFact'))?>
                                </div>
                                <div class="col-xs-2 pl-2 pr-0 text-center">
                                    <button type="button" class="btn-pos-pay-delete del_pay_meth" title="Eliminar método">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>                         
                        </div>

                        <div class="mt-3">
                            <button id="btn_abonos" class="btn btn-miggo-add-pay w-100" type="button">
                                <i class="fa fa-plus-circle"></i> Agregar otro método de pago
                            </button>
                        </div>
                        
                        <div class="pos-payment-rows" id="tBodAbonos" style="display:none;"></div>
                    </div>

                    <div class="col-sm-6 p-2 pl-4">
                        <div class="dock-section-title mb-3"><i class="fa fa-calculator"></i> Liquidación de Caja</div>
                        <div class="recaudo-summary-box">
                            <div class="rec-line"><span>Total Factura:</span> <b class="thTFCIVA">$ 0.00</b></div>
                            <div class="rec-line"><span>Monto Recibido:</span> <b class="thTUnitVR">$ 0.00</b></div>
                            <div class="rec-line total-missing"><span>Saldo Faltante:</span> <span class="text-danger font-weight-bold">$ 0.00</span></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="pos-right-panel">
            
            <div class="right-section-box mb-3">
                <label class="pos-right-label-title"><i class="fa fa-file-text-o"></i> Tipo de Documento</label>
            </div>
            
            <div class="custom-radio-pos mb-4">
                <label><input type="radio" id="doc-fe" name="doc_selector" onclick="cambiarTipoFactura()"> Factura Electrónica (DIAN)</label>
                <label><input type="radio" id="doc-dv" name="doc_selector" onclick="cambiarTipoFactura()"> Documento de Venta (POS)</label>
            </div>

            <div class="financial-panel">
                <div class="fin-row">
                    <span class="fin-label">Subtotal Neto</span>
                    <span class="fin-value thTTotalVR">$ 0.00</span>
                </div>

                <div class="fin-row border-top-fin pt-3 mt-3 dv_descuento">
                    <span class="fin-label">Descuento</span>
                    <span class="fin-value thDtto">$ 0.00</span>
                </div>
                
                <div class="fin-row border-top-fin pt-3 mt-3 dv_imp_iva">
                    <span class="fin-label">Impuestos IVA</span>
                    <span class="fin-value thIVA">$ 0.00</span>
                </div>
                
                <div class="fin-row border-top-fin pt-3 mt-3 dv_imp_ica">
                    <span class="fin-label">Impuestos ICA</span>
                    <span class="fin-value thICA">$ 0.00</span>
                </div>

                <div class="fin-row border-top-fin pt-3 mt-3 dv_imp_bolsa">
                    <span class="fin-label">Impuestos Bolsa</span>
                    <span class="fin-value thBolsa">$ 0.00</span>
                </div>

                <div class="fin-row hidden-fields" style="display:none;">
                    <span class="thDtto">0</span><span class="thPorcICA">0</span><span class="thICA">0</span><span class="thValBolsa">0</span>
                </div>

                <div class="fin-row total-row mt-4 pt-3" style="border-top: 2px dashed #b8c9d4;">
                    <span class="fin-label-grand">Total a pagar</span>
                    <span class="fin-value-large thTFCIVA">$ 0.00</span>
                </div>
                
                <div class="text-muted small mt-3 font-weight-bold">Artículos en Canasta: <b class="thCantidadArticulos text-dark">0</b></div>
            </div>

            <div class="mt-4">
                <button class="btn btn-miggo-action-success w-100" id="finalizar_compra" type="button" onclick="facturarMediosPagos();">
                    <i class="fa fa-flash"></i> Finalizar Compra
                </button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>

<div id="div_producto" style="display:none;"></div>
<div id="div_facturar" style="display:none;"></div>
<div id="div_abono" style="display:none;"></div>
<div id="dv_img_emp" style="display:none;">
    <div style="float:center;" align="center">
        <img src="<?php echo $urlImg . $infoEmpresa['Empresa']['id'] . '/' . $infoEmpresa['Empresa']['imagen'];?>" 
        class="img-responsive img-thumbnail center-block" width="200">  
    </div>
</div>  
