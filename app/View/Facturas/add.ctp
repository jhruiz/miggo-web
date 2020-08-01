<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('facturas/facturas.js')); ?>
<?php echo ($this->Html->script('facturas/abonos.js')); ?>
<?php echo ($this->Html->script('bandeja/gestionBandejas'));  ?>
<?php echo ($this->Html->script('facturas/imprimirFactura'));  ?>
<?php echo ($this->Html->script('facturas/calcularValoresProducto.js'));  ?>
<?php echo $this->Form->create('Factura'); ?>
<div class="container body">
<div class="main_container">
  <div class="x_panel">
        <div class="x_title">
                    <h2><?php echo __('Venta de Productos'); ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                  
                  </li>
                  <li class="dropdown">
                   
                  </li>
                
                  </li>
                </ul>
                 </div>
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '31', 'id' => 'menuvert'))?>
            <?php echo $this->Form->input('ttalAbonos', array('type' => 'hidden', 'value' => '31', 'class' => 'ttalAbonos', 'value' => 0))?>
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#registrado" aria-controls="registrado" data-toggle="tab" role="tab">Cliente Registrado</a></li>
                    <li role="presentation"><a href="#nuevo" aria-controls="nuevo" data-toggle="tab" role="tab">Cliente Nuevo</a></li>
                    <li role="presentation"><a href="#ventarapida" aria-controls="ventarapida" data-toggle="tab" role="tab">Venta Rápida</a></li>                        
                </ul>
            </div>
            
            <div class="tab-content">
                
                <!--Inicia el div para facturar productos a los usuarios registrados en la aplicacion-->
                <div role="tabpanel" class="tab-pane active" id="registrado">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo $this->Form->input('empresa', array('type' => 'hidden', 'value' => $empresaId, 'id' => 'empresaId'));?>
                                <?php echo $this->Form->input('idcliente', array('type' => 'hidden', 'class' => 'id_cliente'));?>
                                <?php echo $this->Form->input('usuario', array('type' => 'hidden', 'value' => $usuarioId, 'id' => 'usuarioId'));?>
                                <?php echo $this->Form->input('pagocredito', array('type' => 'hidden', 'value' => '0', 'id' => 'pagocredito'));?>
                                <?php echo $this->Form->input('pagocontado', array('type' => 'hidden', 'value' => '0', 'id' => 'pagocontado'));?>

                                <div class="form-group">  
                                    <label>Cliente</label><br>  
                                        <?php echo $this->Form->input('datoscliente', array('label' => false, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Cliente')); ?>
                                        <div id="datosCliente" style="position:absolute; z-index:1;"></div>
                                </div>

                                <div class="form-group">
                                    <label>Nit</label><br>
                                    <?php echo $this->Form->input('nitcliente', array('label' => false, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Nit', 'onblur' => 'actualizarNitCliente();')); ?>
                                </div>

                                <div class="form-group">
                                    <label>Teléfono</label><br>
                                    <?php echo $this->Form->input('telefonocliente', array('label' => false, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Teléfono', 'onblur' => 'actualizarTelefonoCliente();')); ?>
                                </div>                           
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dirección</label><br>
                                    <?php echo $this->Form->input('dircliente', array('label' => false, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Dirección', 'onblur' => 'actualizarDireccionCliente();')); ?>
                                </div>

                                <div class="form-group">
                                    <label>Días</label><br>
                                    <?php echo $this->Form->input('diascredcliente', array('label' => false, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Días Límite Crédito', 'onblur' => 'actualizarDiasLimite();')); ?>
                                </div>

                                <div class="form-group">
                                    <label>Límite</label><br>
                                    <?php echo $this->Form->input('limitecredcliente', array('label' => false, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Límite de Crédito', 'onblur' => 'actualizarCreditoLimite();')); ?>
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
                
                <!--Inicia el div para facturar productos a los usuarios que se van a registrar como nuevos en la aplicacion-->
                <div role="tabpanel" class="tab-pane" id="nuevo"><br>
                    <div class="container-fluid">
                        <?php echo $this->Form->input('prefactura', array('type' => 'hidden', 'class' => 'nuevo', 'value' => '', 'id' => 'prefacturaId'));?>
                        <div class="row">
                            <div class="col-md-4">                    
                                <div class="form-group ">  
                                    <label>Nombre *</label><br>                                
                                        <?php echo $this->Form->input('nuevonombre', array('label' => false, 'class' => 'form-control nuevo', 'name' => 'data[Nuevo][nuevonombre]', 'autocomplete' => 'off', 'placeholder' => 'Nombre del Cliente Nuevo', 'onblur' => 'limpirarFormularios();')); ?>                                                                  
                                </div>              
                            </div>

                            <div class="col-md-4">                    
                                <div class="form-group ">  
                                    <label>Nit/C.C *</label><br>                                
                                        <?php echo $this->Form->input('nuevonit', array('label' => false, 'class' => 'form-control nuevo', 'name' => 'data[Nuevo][nuevonit]', 'autocomplete' => 'off', 'placeholder' => 'Nit/C.C del Cliente Nuevo', 'onblur' => 'activarFiltroProductoClienteNuevo();')); ?>                               
                                </div>              
                            </div>

                            <div class="col-md-4">                    
                                <div class="form-group ">  
                                    <label>Dirección *</label><br>                                
                                        <?php echo $this->Form->input('nuevodireccion', array('label' => false, 'class' => 'form-control nuevo', 'name' => 'data[Nuevo][nuevodireccion]', 'autocomplete' => 'off', 'placeholder' => 'Dirección Cliente Nuevo', 'onblur' => 'activarFiltroProductoClienteNuevo();')); ?>                              
                                </div>              
                            </div>                        
                        </div>

                        <div class="row">
                            <div class="col-md-4">                    
                                <div class="form-group ">  
                                    <label>Teléfono</label><br>                                
                                        <?php echo $this->Form->input('nuevotelefono', array('label' => false, 'class' => 'form-control nuevo', 'name' => 'data[Nuevo][nuevotelefono]', 'autocomplete' => 'off', 'placeholder' => 'Teléfono Cliente Nuevo')); ?>     
                                </div>              
                            </div>

                            <div class="col-md-4">                    
                                <div class="form-group ">  
                                    <label>Celular</label><br>                                
                                        <?php echo $this->Form->input('nuevocelular', array('label' => false, 'class' => 'form-control nuevo', 'name' => 'data[Nuevo][nuevocelular]', 'autocomplete' => 'off', 'placeholder' => 'Celular Cliente Nuevo')); ?>                               
                                </div>              
                            </div>

                            <div class="col-md-4">                    
                                <div class="form-group ">  
                                    <label>Email</label><br>                                
                                        <?php echo $this->Form->input('nuevoemail', array('label' => false, 'class' => 'form-control nuevo', 'name' => 'data[Nuevo][nuevoemail]', 'autocomplete' => 'off', 'placeholder' => 'Email Cliente Nuevo')); ?>                              
                                </div>              
                            </div>                        
                        </div> 

                        <div class="row">
                            <div class="col-md-4">                    
                                <div class="form-group ">  
                                    <label>Página Web</label><br>                                
                                        <?php echo $this->Form->input('nuevopaginaweb', array('label' => false, 'class' => 'form-control nuevo', 'name' => 'data[Nuevo][nuevopaginaweb]',  'autocomplete' => 'off', 'placeholder' => 'Página Web Cliente Nuevo')); ?>                                                                  
                                </div>              
                            </div>

                            <div class="col-md-4">                    
                                <div class="form-group ">  
                                    <label>Días de Crédito *</label><br>                                
                                        <?php echo $this->Form->input('nuevodiscredito', array('label' => false, 'class' => 'form-control nuevo', 'name' => 'data[Nuevo][nuevodiascredito]',  'autocomplete' => 'off', 'placeholder' => 'Días de Crédito', 'onblur' => 'activarFiltroProductoClienteNuevo();')); ?>                               
                                </div>              
                            </div>

                            <div class="col-md-4">                    
                                <div class="form-group ">  
                                    <label>Límite de Crédito *</label><br>                                
                                        <?php echo $this->Form->input('nuevolimitecredito', array('label' => false, 'class' => 'form-control nuevo', 'name' => 'data[Nuevo][nuevolimitecredito]',  'autocomplete' => 'off', 'placeholder' => 'Límite de Crédito', 'onblur' => 'activarFiltroProductoClienteNuevo();')); ?>                              
                                </div>              
                            </div>                        
                        </div>    

                        <div class="row">
                            <div class="col-md-4">                    
                                <div class="form-group ">  
                                    <label>Cumpleaños</label><br>
                                    <input name="data[Nuevo][nuevocumpleanios]" id="nuevocumpleanios" class="date form-control nuevo" placeholder="Cumpleaños del Cliente" autocomplete="off" type="text">
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
                                        <?php echo $this->Form->input('productousuarionuevo', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Selección de Producto', 'onkeyup' => 'fnObtenerDatosProductoUsuarioNuevo(event);')); ?>
                                        <div id="datosProductoclientenuevo" style="position:absolute; z-index:1;"></div>                                
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
                <!--Finaliza el div para facturar productos a los usuarios que se van a registrar como nuevos en la aplicacion-->
                
                <!--Inicia el div para facturar productos a los usuarios de venta rapida, es decir, no se guardan en la aplicacion-->
                <div role="tabpanel" class="tab-pane" id="ventarapida">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">                    
                                <div class="form-group ">  
                                    <label>Nombre *</label><br>                                
                                        <?php echo $this->Form->input('rapidanombre', array('label' => false, 'class' => 'form-control rapida', 'name' => 'data[Rapida][rapidanombre]', 'autocomplete' => 'off', 'placeholder' => 'Nombre del Cliente', 'value' => 'anonimo','onfocus' => 'limpirarFormulariosRegistrados()')); ?>                                                                  
                                </div>              
                            </div> 
                            <div class="col-md-6">                    
                                <div class="form-group ">  
                                    <label>Nit/C.C *</label><br>                                
                                        <?php echo $this->Form->input('rapidanit', array('label' => false, 'class' => 'form-control rapida', 'name' => 'data[Rapida][rapidanit]', 'autocomplete' => 'off', 'placeholder' => 'Nit/C.C del Cliente', 'value' => '111', 'onblur' => 'activarFiltroProductoVentaRapida();')); ?>                                                                  
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
                <!--Finaliza el div para facturar productos a los usuarios de venta rapida, es decir, no se guardan en la aplicacion-->
                </div>
                <!-- Finaliza x_panel
            </div>
            <!--Finaliza el div de los tabs para gestion de usuarios-->
            
                    <legend>&nbsp;</legend>                  
                    <div class="table-responsive">
                        <div class="container-fluid">        
                            <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                                <th><?php echo ('Nombre'); ?></th>
                                                <th><?php echo ('Código'); ?></th>
                                                <th><?php echo ('Cantidad'); ?></th>
                                                <th><?php echo ('Precio Venta U.'); ?></th>
                                                <th><?php echo ('Precio Venta A.I'); ?></th>                                       
                                                <th><?php echo ('% Dtto'); ?></th>
                                                <th><?php echo ('$ Dtto'); ?></th>                                                                                                                            
                                                <th><?php echo ('Valor IVA'); ?></th>                                       
                                                <th><?php echo ('Ttal con IVA'); ?></th>
                                                <th>&nbsp;</th>
                                </tr>  
                                </thead>
                                <tbody id="productosFacturas"></tbody>
                                <tbody id="totalFacturas">
                                    <tr>
                                        <th>&nbsp</th>
                                        <th colspan="2"><b>TOTAL</b></th>
                                        <th class="text-right"><b class="thTUnit"></b></th>
                                        <th class="text-right"><b class="thTTotal"></b></th>
                                        <th>&nbsp;</th>
                                        <th class="text-right"><b class="thDtto"></b></th>
                                        <th class="text-right"><b class="thIVA"></b></th>
                                        <th class="text-right"><b class="thTFCIVA"></b></th>
                                    </tr>
                                </tbody>
                                <tbody id="tBodAbonos"></tbody>
                            </table>
                        </div>
                    </div> 
                    <legend>&nbsp;</legend>            
            
                    <div class="row">
                        <div class="col-md-2">
                            <?php echo $this->Form->input('vendedor', array('label' => 'Vendedor', 'type' => 'select', 'options' => $vendedor, 'class' => 'form-control', 'default' => $usuarioId));?><br>
                            <?php echo $this->Form->input('notafactura', array('label' => 'Nota Factura', 'type' => 'select', 'options' => $notaFactura, 'class' => 'form-control', 'empty' => 'Seleccione una...'));?>                            
                        </div>                  
                        <div class="col-md-2">
                            <label>Factura</label><br>
                            <input type="checkbox" id="esfactura" name="data[Factura][esfactura]" checked>                        
                        </div>
                        <div class="col-md-8">
                            <label for="obs_fact">Observaciones</label>
                            <textarea id="obs_fact" name="data[Factura][observacion]" class="md-textarea form-control" rows="3"></textarea>                            
                            <?php echo $this->Form->input('empresaRelacionada', array('label' => '', 'class' => 'form-control', 'type' => 'hidden'));?>                            
                        </div>
                    </div>             
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

