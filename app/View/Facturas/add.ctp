<?php $this->layout = 'inicio';?>
<?php echo ($this->Html->script('facturas/facturas.js')); ?>
<?php echo ($this->Html->script('facturas/abonos.js')); ?>
<?php echo ($this->Html->script('bandeja/gestionBandejas')); ?>
<?php echo ($this->Html->script('facturas/imprimirFactura')); ?>
<?php echo ($this->Html->script('facturas/calcularValoresProducto.js')); ?>
<?php echo ($this->Html->script('facturas/syncdian.js')); ?>
<?php echo ($this->Html->script('abonos/gestionabonos.js')); ?>
<?php echo $this->Form->create('Factura'); ?>
<div class="container body">
<div class="main_container">

<br>
<div class="x_panel">
        <div class="x_title">
                    <h2><?php if($esFactura == '1') { echo __('Factura electrónica'); } else { echo __('Documento de venta'); } ?></h2>
                 </div>
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '31', 'id' => 'menuvert')) ?>
            <?php echo $this->Form->input('ttalAbonos', array('type' => 'hidden', 'value' => '31', 'class' => 'ttalAbonos', 'value' => 0)) ?>
            <?php echo $this->Form->input('esFacturaDV', array('type' => 'hidden', 'value' => $esFactura, 'id' => 'esFacturaDV')) ?>
            
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#registrado" aria-controls="registrado" data-toggle="tab" role="tab">Cliente Registrado</a></li>
                    <li role="presentation"><a href="#ventarapida" aria-controls="ventarapida" data-toggle="tab" role="tab">Venta Rápida</a></li>
                </ul>
            </div>

            <div class="tab-content">

                <!--Inicia el div para facturar productos a los usuarios registrados en la aplicacion-->
                <div role="tabpanel" class="tab-pane active" id="registrado">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo $this->Form->input('empresa', array('type' => 'hidden', 'value' => $empresaId, 'id' => 'empresaId')); ?>
                                <?php echo $this->Form->input('idcliente', array('type' => 'hidden', 'class' => 'id_cliente')); ?>
                                <?php echo $this->Form->input('usuario', array('type' => 'hidden', 'value' => $usuarioId, 'id' => 'usuarioId')); ?>
                                <?php echo $this->Form->input('pagocredito', array('type' => 'hidden', 'value' => '0', 'id' => 'pagocredito')); ?>
                                <?php echo $this->Form->input('pagocontado', array('type' => 'hidden', 'value' => '0', 'id' => 'pagocontado')); ?>

                                <div class="form-group">
                                    <label>Cliente</label><br>
                                        <?php echo $this->Form->input('datoscliente', array('label' => false, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Cliente')); ?>
                                        <div id="datosCliente" style="position:absolute; z-index:1;"></div>
                                </div>

                                <div class="form-group">
                                    <label>Teléfono</label><br>
                                    <?php echo $this->Form->input('telefonocliente', array('label' => false, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Teléfono', 'onblur' => 'actualizarTelefonoCliente();')); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nit</label><br>
                                    <?php echo $this->Form->input('nitcliente', array('label' => false, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Nit', 'onblur' => 'actualizarNitCliente();')); ?>
                                </div>

                                <div class="form-group">
                                    <label>Dirección</label><br>
                                    <?php echo $this->Form->input('dircliente', array('label' => false, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Dirección', 'onblur' => 'actualizarDireccionCliente();')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <legend>&nbsp;</legend>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Producto</label><br>
                                        <?php echo $this->Form->input('producto', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Selección de Producto', 'onkeyup' => 'fnObtenerDatosProducto(event);')); ?>
                                        <div id="datosProducto" style="position:absolute; z-index:1;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!--Finaliza el div para facturar productos a los usuarios registrados en la aplicacion-->

                <!--Inicia el div para facturar productos a los usuarios de venta rapida, es decir, no se guardan en la aplicacion-->
                <div role="tabpanel" class="tab-pane" id="ventarapida">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Nombre *</label><br>
                                        <?php echo $this->Form->input('rapidanombre', array('label' => false, 'class' => 'form-control rapida', 'name' => 'data[Rapida][rapidanombre]', 'autocomplete' => 'off', 'placeholder' => 'Nombre del Cliente', 'value' => 'consumidor final', 'onfocus' => 'limpirarFormulariosRegistrados()')); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Nit/C.C *</label><br>
                                        <?php echo $this->Form->input('rapidanit', array('label' => false, 'class' => 'form-control rapida', 'name' => 'data[Rapida][rapidanit]', 'autocomplete' => 'off', 'placeholder' => 'Nit/C.C del Cliente', 'value' => '222222222', 'onblur' => 'activarFiltroProductoVentaRapida();')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Teléfono</label><br>
                                        <?php echo $this->Form->input('rapidatelefono', array('label' => false, 'class' => 'form-control rapida', 'name' => 'data[Rapida][rapidatelefono]', 'autocomplete' => 'off', 'placeholder' => 'Teléfono del Cliente')); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Dirección</label><br>
                                        <?php echo $this->Form->input('rapidadireccion', array('label' => false, 'class' => 'form-control rapida', 'name' => 'data[Rapida][rapidadireccion]', 'autocomplete' => 'off', 'placeholder' => 'Dirección del Cliente')); ?>
                                </div>
                            </div>
                        </div>

                    </div>


                    <legend>&nbsp;</legend>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Producto</label><br>
                                        <?php echo $this->Form->input('productoventarapida', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Selección de Producto', 'onkeyup' => 'fnObtenerDatosProductoVentaRapida(event);')); ?>
                                        <div id="datosProductoventarapida" style="position:absolute; z-index:1;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                    <legend>&nbsp;</legend>
                    <div class="table-responsive">
                        <div class="container-fluid">
                            <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                                <th><?php echo ('Nombre'); ?></th>
                                                <th><?php echo ('Código'); ?></th>
                                                <th><?php echo ('Cantidad'); ?></th>
                                                <th><?php echo ('Precio unitario'); ?></th>
                                                <th><?php echo ('Precio unitario base'); ?></th>
                                                <th><?php echo ('% Dtto'); ?></th>
                                                <th><?php echo ('Descuento'); ?></th>
                                                <th><?php echo ('IVA'); ?></th>
                                                <th><?php echo ('%'); ?></th>
                                                <th><?php echo ('INC'); ?></th>
                                                <th><?php echo ('%'); ?></th>
                                                <th><?php echo ('INC bolsa'); ?></th>
                                                <th><?php echo ('Total línea'); ?></th>
                                                <th>&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody id="productosFacturas"></tbody>
                                <!-- <tbody id="propinas">
                                    <tr>
                                        <th class="text-right"><input id="tienePropina" type="checkbox"></th>
                                        <th colspan="2"><b>PROPINA</b></th>
                                        <th class="text-right"><input class="propina" type="text"></input></th>
                                        <th colspan="9">&nbsp</th>
                                    </tr>
                                </tbody> -->

                                <tbody id="imp_bolsa">
                                    <tr>
                                        <th colspan="11">&nbsp</th>
                                        <th><b>INC Bolsa</b></th>
                                        <th class="text-right"><?php echo $this->Form->input('inp_imp_bolsa', array('type' => 'text', 'label' => false, 'class' => 'form-control numericPrice', 'value' => '', 'id' => 'inp_imp_bolsa', 'disabled' => true)); ?></th>
                                    </tr>
                                </tbody>

                                <tbody id="totalFacturas">
                                    <tr>
                                        <th>&nbsp</th>
                                        <th colspan="2"><b>TOTAL</b></th>
                                        <th class="text-right"><b class="thTUnit"></b></th>
                                        <th class="text-right"><b class="thTTotal"></b></th>
                                        <th>&nbsp;</th>
                                        <th class="text-right"><b class="thDtto"></b></th>
                                        <th class="text-right"><b class="thIVA"></b></th>
                                        <th class="text-right"><b class="thPorcIVA"></b></th>
                                        <th class="text-right"><b class="thICA"></b></th>
                                        <th class="text-right"><b class="thPorcICA"></b></th>
                                        <th class="text-right"><b class="thValBolsa"></b></th>
                                        <th class="text-right"><b class="thTFCIVA"></b></th>
                                    </tr>
                                </tbody>
                                <tbody id="tBodAbonos"></tbody>
                            </table>
                        </div>
                    </div>
                    <legend>&nbsp;</legend>

                    <div class="row">
                        <div class="form-group col-md-2">
                            <?php echo $this->Form->input('vendedor', array('label' => 'Vendedor', 'type' => 'select', 'options' => $vendedor, 'class' => 'form-control', 'default' => $usuarioId)); ?>
                        </div>

                        <div class="form-group col-md-2">
                            <?php echo $this->Form->input('canalventa', array('label' => 'Canal de ventas', 'type' => 'select', 'options' => $canalventas, 'class' => 'form-control',   'empty' => 'Seleccione una...')); ?>
                        </div>
                        
                        <div class="form-group col-md-2">
                            <?php echo $this->Form->input('notafactura', array('label' => 'Nota Factura', 'type' => 'select', 'options' => $notaFactura, 'class' => 'form-control', 'empty' => 'Seleccione una...')); ?>
                        </div>

                        <div class="form-group col-md-2">
                            <?php echo $this->Form->input('f_ref_or', array('label' => 'Fecha Orden', 'type' => 'text', 'class' => 'date form-control', 'autocomplete' => 'off', 'placeholder' => 'Fecha Orden')); ?>
                        </div>
                        
                        <div class="form-group col-md-2">
                            <?php echo $this->Form->input('n_ref_or', array('label' => 'Prefijo - No. Orden', 'type' => 'text', 'autocomplete' => 'off', 'class' => 'form-control', 'placeholder' => 'Número de Orden')); ?>
                        </div>
                    </div>

                    <legend>&nbsp;</legend>
                    
                    <div class="row">
                        <!-- <div class="col-md-7"> -->
                            <label for="obs_fact">Observaciones</label>
                            <textarea id="obs_fact" name="data[Factura][observacion]" class="md-textarea form-control" rows="3"></textarea>
                            <?php echo $this->Form->input('empresaRelacionada', array('label' => '', 'class' => 'form-control', 'type' => 'hidden')); ?>
                        <!-- </div> -->
                    </div>

                    <?php echo $this->Form->input('prefacturaId', array('label' => '', 'value' => '', 'type' => 'hidden', 'id' => 'prefacturaId')); ?>

                    </form>
                </div><!-- class="container body-->
            </div><!-- class="main_container">-->


        <div class="container-fluid">
            <div class="col-md-3"><button id="btn_abonos" class="btn btn-primary" type="button">Abonar</button></div>
            <div class="col-md-3"><button id="btn_facturar" class="btn btn-primary" type="button" onclick="facturarProductos();">Facturar</button></div>
            <div class="col-md-3"><a href="#" class="btn btn-primary active" role="button" aria-pressed="true" id="imprimirCot">Imprimir</a></div>
            <div class="col-md-3">
                <a href="#" class="wppSendPF" target="">
                    <img src="<?php echo $urlImgWP; ?>" class="img-responsive" width="35">
                </a>
            </div>
        </div>

<div id="div_producto"></div>
<div id="div_facturar"></div>
<div id="div_abono"></div>
<div id="dv_emp">
    <div id="dv_img_emp">
        <div style="float:center;" align="center">
            <img src="<?php echo $urlImg . $arrEmprea['Empresa']['id'] . '/' . $arrEmprea['Empresa']['imagen'];?>" 
            class="img-responsive img-thumbnail center-block" width="200">  
        </div>
    </div>  
</div>