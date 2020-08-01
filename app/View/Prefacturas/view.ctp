<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('bandeja/gestionBandejas.js'));  ?>
<?php echo ($this->Html->script('prefacturas/prefacturas.js')); ?>
<?php echo ($this->Html->script('facturas/abonos.js')); ?>
<?php echo ($this->Html->script('facturas/calcularValoresProducto.js'));  ?>
<?php echo ($this->Html->script('prefacturas/imprimirPrefacturas.js'));  ?>
<?php echo ($this->Html->script('prefacturas/view.js'));  ?>
<?php echo $this->Form->create('Prefactura'); ?>
<div class="container body">
<div class="main_container">
<div class="col-md-12">
    <div class="x_panel">
        <div class="x_title">
                    <h2><?php echo __('Orden de Compra'); ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                  
                  </li>
                  <li class="dropdown">
                   
                  </li>
                
                  </li>
                </ul>
                 </div>

<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '30', 'id' => 'menuvert'))?>
<?php echo $this->Form->input('ttalAbonos', array('type' => 'hidden', 'value' => '31', 'class' => 'ttalAbonos', 'value' => $ttalAbonos))?>
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
                                <?php if(isset($prefactura['Cliente']['id']) && $prefactura['Cliente']['id'] != ""){?>
                                    <?php echo $this->Form->input('idcliente', array('type' => 'hidden', 'name' => 'data[Factura][idcliente]', 'id' => 'FacturaIdcliente', 'value' => $prefactura['Cliente']['id'], 'class' => 'id_cliente'));?>
                                    <?php echo $this->Form->input('nombrecliente', array('type' => 'hidden', 'value' => $prefactura['Cliente']['nombre']));?>
                                    <?php echo $this->Form->input('direccliente', array('type' => 'hidden', 'value' => $prefactura['Cliente']['direccion']));?>
                                    <?php echo $this->Form->input('nitcccliente', array('type' => 'hidden', 'value' => $prefactura['Cliente']['nit']));?>
                                    <?php echo $this->Form->input('diascliente', array('type' => 'hidden', 'value' => $prefactura['Cliente']['diascredito']));?>
                                    <?php echo $this->Form->input('telcliente', array('type' => 'hidden', 'value' => $prefactura['Cliente']['telefono']));?>
                                    <?php echo $this->Form->input('limitecliente', array('type' => 'hidden', 'value' => $prefactura['Cliente']['limitecredito']));?>                                
                                <?php }else{?>
                                <?php echo $this->Form->input('idcliente', array('type' => 'hidden', 'name' => 'data[Factura][idcliente]', 'id' => 'FacturaIdcliente', 'class' => 'id_cliente'));?>
                                <?php }?>
                                <?php echo $this->Form->input('empresa', array('type' => 'hidden', 'name' => 'data[Factura][empresa]', 'value' => $empresaId, 'id' => 'empresaId'));?>                                
                                <?php echo $this->Form->input('usuario', array('type' => 'hidden', 'name' => 'data[Factura][usuario]', 'value' => $usuarioId, 'id' => 'usuarioId'));?>
                                <?php echo $this->Form->input('pagocredito', array('type' => 'hidden', 'name' => 'data[Factura][pagocredito]', 'value' => '0', 'id' => 'pagocredito'));?>
                                <?php echo $this->Form->input('pagocontado', array('type' => 'hidden', 'name' => 'data[Factura][pagocontado]', 'value' => '0', 'id' => 'pagocontado'));?>
                                <?php echo $this->Form->input('prefacturado', array('type' => 'hidden', 'name' => 'data[Factura][prefacturado]', 'value' => $prefactura['Prefactura']['id'], 'id' => 'prefacturadoId'));?>
                                <?php echo $this->Form->input('ordentrabajo', array('type' => 'hidden', 'name' => 'data[Factura][ordentrabajo]', 'value' => !empty($prefactura['Prefactura']['ordentrabajo_id']) ? $prefactura['Prefactura']['ordentrabajo_id'] : "", 'id' => 'ordentrabajoId'));?>
                                <?php echo $this->Form->input('prefactId', array('type' => 'hidden', 'value' => $id, 'id' => 'prefactId'));?>
                                
                                <div class="form-group ">  
                                    <label>Cliente</label><br>                                
                                        <?php echo $this->Form->input('datoscliente', array('label' => false, 'name' => 'data[Factura][datoscliente]', 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Cliente')); ?>
                                        <div id="datosCliente" style="position: absolute;"></div>                                
                                </div>

                                <div class="form-group">
                                    <label>Nit</label><br>
                                    <?php echo $this->Form->input('nitcliente', array('label' => false, 'name' => 'data[Factura][nitcliente]', 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Nit', 'onblur' => 'actualizarNitCliente();')); ?>
                                </div>

                                <div class="form-group">
                                    <label>Teléfono</label><br>
                                    <?php echo $this->Form->input('telefonocliente', array('label' => false, 'name' => 'data[Factura][telefonocliente]', 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Teléfono', 'onblur' => 'actualizarTelefonoCliente();')); ?>
                                </div>                           
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dirección</label><br>
                                    <?php echo $this->Form->input('dircliente', array('label' => false, 'name' => 'data[Factura][dircliente]', 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Dirección', 'onblur' => 'actualizarDireccionCliente();')); ?>
                                </div>

                                <div class="form-group">
                                    <label>Días</label><br>
                                    <?php echo $this->Form->input('diascredcliente', array('label' => false, 'name' => 'data[Factura][diascredcliente]', 'class' => 'form-control registrado', 'name' => 'data[Factura][diascredcliente]', 'autocomplete' => 'off', 'placeholder' => 'Días Límite Crédito', 'onblur' => 'actualizarDiasLimite();')); ?>
                                </div>

                                <div class="form-group">
                                    <label>Límite</label><br>
                                    <?php echo $this->Form->input('limitecredcliente', array('label' => false, 'name' => 'data[Factura][limitecredcliente]', 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Límite de Crédito', 'onblur' => 'actualizarCreditoLimite();')); ?>
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
                                        <div id="datosProducto" style="position: absolute; z-index: 999"></div>                                
                                </div>  
                            </div>
                        </div>
                    </div>                    
                </div>
                <!--Finaliza el div para facturar productos a los usuarios registrados en la aplicacion-->                

                <!--Inicia el div para facturar productos a los usuarios que se van a registrar como nuevos en la aplicacion-->
                <div role="tabpanel" class="tab-pane" id="nuevo"><br>
                    <div class="container-fluid">                       
                        <div class="row">
                            <div class="col-md-4">                    
                                <div class="form-group ">  
                                    <label>Nombre *</label><br>                                
                                        <?php echo $this->Form->input('nuevonombre', array('label' => false, 'class' => 'form-control nuevo', 'name' => 'data[Nuevo][nuevonombre]', 'autocomplete' => 'off', 'placeholder' => 'Nombre del Cliente Nuevo', 'onblur' => 'limpirarFormularios()')); ?>
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
                                        <div id="datosProductoclientenuevo" style="position: absolute; z-index: 999"></div>                                
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
                                        <?php echo $this->Form->input('rapidanombre', array('label' => false, 'class' => 'form-control rapida', 'name' => 'data[Rapida][rapidanombre]', 'autocomplete' => 'off', 'placeholder' => 'Nombre del Cliente', 'onblur' => 'limpirarFormulariosRegistrados()')); ?>                                                                  
                                </div>              
                            </div> 
                            <div class="col-md-6">                    
                                <div class="form-group ">  
                                    <label>Nit/C.C *</label><br>                                
                                        <?php echo $this->Form->input('rapidanit', array('label' => false, 'class' => 'form-control rapida', 'name' => 'data[Rapida][rapidanit]', 'autocomplete' => 'off', 'placeholder' => 'Nit/C.C del Cliente', 'onblur' => 'activarFiltroProductoVentaRapida();')); ?>
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
                                        <div id="datosProductoventarapida" style="position: absolute; z-index: 999"></div>                                
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
                <!--Finaliza el div para facturar productos a los usuarios de venta rapida, es decir, no se guardan en la aplicacion-->
                
            </div>
        

            <!--Finaliza el div de los tabs para gestion de usuarios-->
                            
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
                                <tbody id="productosPrefacturas"></tbody>
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

                </div> <!--Termina col -->
            </div><!-- temina x_panel-->
           
            <?php if(!empty($prefactura['Prefactura']['ordentrabajo_id'])){?>
                 <div class="col-md-12">
                 <div class="x_panel">
                 <div class="x_title">
                    <h2><?php echo __('Orden de Trabajo'); ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                  
                  </li>
                  <li class="dropdown">
                   
                  </li>
                
                  </li>
                </ul>
                 </div>
    
                    <div class="table-responsive">
                        <div class="container-fluid">        
                            <table  cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                                <thead>
                                    <tr>
                                                    <th><?php echo ('Orden'); ?></th>
                                                    <th><?php echo ('Mecánico'); ?></th>
                                                    <th><?php echo ('Placa'); ?></th>
                                                    <th><?php echo ('Planta'); ?></th>
                                                    <th><?php echo ('Estado'); ?></th>                                       
                                                    <th>&nbsp;</th>                                       
                                    </tr>  
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo ($arrOrdenT['0']['Ordentrabajo']['codigo']); ?></td>
                                        <td><?php echo ($arrOrdenT['0']['US']['nombre']); ?></td>
                                        <td><?php echo ($arrOrdenT['0']['VH']['placa']); ?></td>
                                        <td><?php echo ($arrOrdenT['0']['PS']['descripcion']); ?></td>
                                        <td><?php echo ($arrOrdenT['0']['OE']['descripcion']); ?></td>
                                        <td><button class="btn btn-primary btn-xs" id="editOrden" type="button">Editar</button></td>
                                    </tr>
                                </tbody>       
                            </table>
                        </div>
                    </div>            
            </div><!-- x_panel -->
    </div><!--col -->
            
            <?php } ?>
        
 <div class="col-md-12">
    <div class="x_panel">
        <div class="x_content">
                    <legend>&nbsp;</legend>            
            
                    <div class="row">
                        <div class="col-md-2">
                            <?php echo $this->Form->input('vendedor', array('label' => 'Vendedor', 'type' => 'select', 'options' => $vendedor, 'class' => 'form-control', 'default' => $usuarioId, 'name' => 'data[Factura][vendedor]'));?><br>
                            <?php echo $this->Form->input('notafactura', array('label' => 'Nota Factura', 'type' => 'select', 'options' => $notaFactura, 'class' => 'form-control', 'empty' => 'Seleccione una...', 'name' => 'data[Factura][notafactura]'));?><br>
                        </div>                      
                        <div class="col-md-1">
                            <label>Factura</label><br>
                            <input type="checkbox" id="esfactura" name="data[Factura][esfactura]" checked>                        
                        </div>
                        <div class="col-md-2">
                            <?php $estadoAct = !empty($prefactura['Prefactura']['estadoprefactura_id']) ? $prefactura['Prefactura']['estadoprefactura_id'] : "";?>
                            <?php echo $this->Form->input('estados', array('label' => 'Estados', 'type' => 'select', 'options' => $estados, 'class' => 'form-control', 'default' => $estadoAct));?>
                        </div>                      
                        
                        <div class="col-md-7">
                            <label for="obs_fact">Observaciones</label>
                            <textarea id="obs_fact" name="data[Factura][observacion]" class="md-textarea form-control" rows="3"><?php echo($prefactura['Prefactura']['observacion'])?></textarea>                              
                            <?php echo $this->Form->input('empresaRelacionada', array('label' => '', 'name' => 'data[Factura][empresaRelacionada]', 'class' => 'form-control', 'type' => 'hidden'));?>
                        </div>
                    </div>             
                    </form>
       
        <div class="nav nav-pills">  
                  <div class="col-md-6">
                      <div class="col-md-2"><button id="btn_abonos" class="btn btn-primary active center-block" type="button">Abonar</button></div>
            <div class="col-md-2"><button type='button' id="btn_facturar" class="btn btn-primary active center-block" onclick="facturarProductos();">Facturar</button></div>
            <div class="col-md-2"><a href="#" class="btn btn-primary active pull-lefth" role="button" aria-pressed="true" id="imprimirCot">Imprimir</a></div>
            <div class="col-md-2">                        
                <a href="#" class="wppSendPF" target="">
                    <img src="<?php echo $urlImgWP; ?>" class="img-responsive" width="35">            
                </a>
            </div>
                  </div>
           
        </div>  
    </div><!-- termina x_content-->
    </div> <!--Termina x_panel-->
       </div> <!-- Termina COL -->
<div id="div_producto"></div>
<div id="div_facturar"></div>
<div id="div_abono"></div>
</div><!-- container body -->
</div><!-- main_container -->
