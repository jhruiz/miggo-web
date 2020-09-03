<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('cotizaciones/cotizaciones.js')); ?>
<?php echo $this->Form->create('Cotizacione'); ?>
	<fieldset>
            <legend><h2><b><?php echo __('Cotización'); ?></b></h2></legend>
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '51', 'id' => 'menuvert'))?>
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#registrado" aria-controls="registrado" data-toggle="tab" role="tab">Cotización cliente registrado</a></li>
<!--                    <li role="presentation"><a href="#nuevo" aria-controls="nuevo" data-toggle="tab" role="tab">Cotización cliente Nuevo</a></li>
                    <li role="presentation"><a href="#cotizacionrapida" aria-controls="cotizacionrapida" data-toggle="tab" role="tab">Cotización rápida</a></li>                        -->
                </ul>
            </div>

            <?php $idCliente = !empty($arrCotiza['0']['Cotizacione']['cliente_id']) ? $arrCotiza['0']['Cotizacione']['cliente_id'] : ""; ?>
            <?php echo $this->Form->input('cotizacion_id', array('type' => 'hidden', 'value' => $arrCotiza['0']['Cotizacione']['id'], 'id' => 'cotizacionId'));?>
            <?php echo $this->Form->input('empresa', array('type' => 'hidden', 'value' => $empresaId, 'id' => 'empresaId'));?>
            <?php echo $this->Form->input('idcliente', array('type' => 'hidden', 'value' => $idCliente));?>  
            <?php echo $this->Form->input('nombre_empresa', array('type' => 'hidden', 'value' => ($arrEmprea['Empresa']['nombre']), 'id' => 'nombre_empresa'));?>
            <?php echo $this->Form->input('vehiculo', array('type' => 'hidden', 'id' => 'vehiculo_id', 'value' => !empty($arrVehiculo['Vehiculo']['id']) ? $arrVehiculo['Vehiculo']['id'] : ""));?>  
            
            <div class="tab-content">                
                <!--Inicia el div para facturar productos a los usuarios registrados en la aplicacion-->
                <div role="tabpanel" class="tab-pane active" id="registrado">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">  
                                    <label>Cliente</label><br>  
                                        <?php echo $this->Form->input('datoscliente', array('label' => false, 'value' => $cliName, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Cliente')); ?>
                                        <div id="datosCliente" style="position:absolute; z-index:1;"></div>
                                </div>

                                <div class="form-group">
                                    <label>Nit</label><br>
                                    <?php echo $this->Form->input('nitcliente', array('label' => false, 'value' => $cliNit, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Nit', 'onblur' => 'actualizarNitCliente();')); ?>
                                </div>

                                <div class="form-group">
                                    <label>Teléfono</label><br>
                                    <?php echo $this->Form->input('telefonocliente', array('label' => false, 'value' => $cliTel, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Teléfono', 'onblur' => 'actualizarTelefonoCliente();')); ?>
                                </div>                           
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dirección</label><br>
                                    <?php echo $this->Form->input('dircliente', array('label' => false, 'value' => $cliDir, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Dirección', 'onblur' => 'actualizarDireccionCliente();')); ?>
                                </div>

                                <div class="form-group">
                                    <label>Días</label><br>
                                    <?php echo $this->Form->input('diascredcliente', array('label' => false, 'value' => $cliDias, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Días Límite Crédito', 'onblur' => 'actualizarDiasLimite();')); ?>
                                </div>

                                <div class="form-group">
                                    <label>Límite</label><br>
                                    <?php echo $this->Form->input('limitecredcliente', array('label' => false, 'value' => $cliLimC, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Límite de Crédito', 'onblur' => 'actualizarCreditoLimite();')); ?>
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
                                    <div class="input-group"> 
                                        <?php echo $this->Form->input('producto', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Selección de Producto')); ?>
                                        <div id="datosProducto" style="position:absolute; z-index:1;"></div>
                                        <a href="#" class="btn btn-default btn-sm input-group-addon" id="add_product"><span class="far fa-plus"></span></a>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>                    
                </div>
                <!--Finaliza el div para facturar productos a los usuarios registrados en la aplicacion-->
                
                <!--Inicia el div para facturar productos a los usuarios que se van a registrar como nuevos en la aplicacion-->
                <div role="tabpanel" class="tab-pane" id="nuevo"><br>
                    <div class="container-fluid">
                        <?php echo $this->Form->input('cotizaciones', array('type' => 'hidden', 'class' => 'nuevo', 'value' => '', 'id' => 'cotizacioneId'));?>
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
                                        <?php echo $this->Form->input('nuevonit', array('label' => false, 'class' => 'form-control nuevo', 'name' => 'data[Nuevo][nuevonit]', 'autocomplete' => 'off', 'placeholder' => 'Nit/C.C del Cliente Nuevo')); ?>                               
                                </div>              
                            </div>

                            <div class="col-md-4">                    
                                <div class="form-group ">  
                                    <label>Dirección *</label><br>                                
                                        <?php echo $this->Form->input('nuevodireccion', array('label' => false, 'class' => 'form-control nuevo', 'name' => 'data[Nuevo][nuevodireccion]', 'autocomplete' => 'off', 'placeholder' => 'Dirección Cliente Nuevo')); ?>                              
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
                                        <?php echo $this->Form->input('nuevodiscredito', array('label' => false, 'class' => 'form-control nuevo', 'name' => 'data[Nuevo][nuevodiascredito]',  'autocomplete' => 'off', 'placeholder' => 'Días de Crédito')); ?>                               
                                </div>              
                            </div>

                            <div class="col-md-4">                    
                                <div class="form-group ">  
                                    <label>Límite de Crédito *</label><br>                                
                                        <?php echo $this->Form->input('nuevolimitecredito', array('label' => false, 'class' => 'form-control nuevo', 'name' => 'data[Nuevo][nuevolimitecredito]',  'autocomplete' => 'off', 'placeholder' => 'Límite de Crédito')); ?>                              
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
                        
                        <div>
                            <a href="#" class="btn btn-primary btn-sm active" role="button" aria-pressed="true" id="btnGuardarCliente">Guardar Cliente</a>                          
                        </div>
                        
                    </div>
                    
                    <legend>&nbsp;</legend>  
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">  
                                    <label>Producto</label><br>  
                                    <div class="input-group"> 
                                        <?php echo $this->Form->input('productousuarionuevo', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Selección de Producto')); ?>
                                        <a href="#" class="btn btn-default btn-sm input-group-addon" id="add_product"><span class="far fa-plus"></span></a>
                                    </div>                           
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
                <!--Finaliza el div para facturar productos a los usuarios que se van a registrar como nuevos en la aplicacion-->
                
                <!--Inicia el div para facturar productos a los usuarios de venta rapida, es decir, no se guardan en la aplicacion-->
                <div role="tabpanel" class="tab-pane" id="cotizacionrapida">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">                    
                                <div class="form-group ">  
                                    <label>Nombre *</label><br>                                
                                        <?php echo $this->Form->input('rapidanombre', array('label' => false, 'value' => $anomName, 'class' => 'form-control rapida', 'name' => 'data[Rapida][rapidanombre]', 'autocomplete' => 'off', 'placeholder' => 'Nombre del Cliente','onfocus' => 'limpirarFormulariosRegistrados()')); ?>                                                                  
                                </div>
                            </div> 
                            <div class="col-md-6">                    
                                <div class="form-group ">  
                                    <label>Nit/C.C *</label><br>                                
                                        <?php echo $this->Form->input('rapidanit', array('label' => false, 'value' => $anomCC, 'class' => 'form-control rapida', 'name' => 'data[Rapida][rapidanit]', 'autocomplete' => 'off', 'placeholder' => 'Nit/C.C del Cliente', 'onblur' => 'activarFiltroProductoVentaRapida();')); ?>                                                                  
                                </div>              
                            </div>                            
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">                    
                                <div class="form-group ">  
                                    <label>Teléfono</label><br>                                
                                        <?php echo $this->Form->input('rapidatelefono', array('label' => false, 'value' => $anomTel, 'class' => 'form-control rapida', 'name' => 'data[Rapida][rapidatelefono]', 'autocomplete' => 'off', 'placeholder' => 'Teléfono del Cliente')); ?>                                                                  
                                </div>              
                            </div> 
                            <div class="col-md-6">                    
                                <div class="form-group ">  
                                    <label>Dirección</label><br>                                
                                        <?php echo $this->Form->input('rapidadireccion', array('label' => false, 'value' => $anomDir, 'class' => 'form-control rapida', 'name' => 'data[Rapida][rapidadireccion]', 'autocomplete' => 'off', 'placeholder' => 'Dirección del Cliente')); ?>                                                                  
                                </div>              
                            </div>                            
                        </div>                        
                        <div>
                             <a href="#" class="btn btn-primary btn-sm active" role="button" aria-pressed="true" id="btnCotizacionRapida">Guardar</a>                          
                         </div>                           
                    </div>
                 
                    <legend>&nbsp;</legend>  
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">  
                                    <label>Producto</label><br>                                
                                        <?php echo $this->Form->input('productoventarapida', array('label' => false, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Selección de Producto')); ?>
                                        <div id="datosProductoventarapida" style="position:absolute; z-index:1;"></div>                                
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
                <!--Finaliza el div para facturar productos a los usuarios de venta rapida, es decir, no se guardan en la aplicacion-->
                
            </div>
            <!--Finaliza el div de los tabs para gestion de usuarios-->
                  
                    <div class="table-responsive">
                        <div class="container-fluid" id="dv_cotiza">        
                            <table  id="productosCotizacion" cellpadding="0" cellspacing="0" class="table">
                                <thead>
                                    <tr>
                                        <th><?php echo ('Nombre'); ?></th>
                                        <th><?php echo ('Cantidad'); ?></th>
                                        <th><?php echo ('Valor Unitario'); ?></th>
                                        <th><?php echo ('Valor Total'); ?></th>                                       
                                        <th>&nbsp;</th>
                                    </tr> 
                                </thead>
                                <tbody id="dvTCot"> 
                                    <?php foreach($arrCotiza as $pr){?>
                                        <?php if(!empty($pr['CD']['id'])){?>
                                        <tr id="tr_<?php echo ($pr['CD']['id']); ?>">
                                        <td> <?php echo ($pr['CD']['nombreproducto']);?> </td>
                                        <td><input type="text" id="cant_<?php echo ($pr['CD']['id']);?>" class="form-control ttales" value="<?php echo($pr['CD']['cantidad']);?>" onblur="actCantPrdCot(this)">&nbsp;</td>
                                        <td><input type="text" id="vUnit_<?php echo ($pr['CD']['id']);?>" class="form-control ttales" value="<?php echo ($pr['CD']['costoventa']);?>" onblur="actValUnitPrdCot(this)">&nbsp;</td>
                                        <td><input type="text" id="vTtal_<?php echo ($pr['CD']['id']);?>" class="form-control ttales tfinal" value="<?php echo ($pr['CD']['costototal']);?>" readonly>&nbsp;</td>
                                        <td><input type="button" class="btn btn-primary btn-sm" value="Eliminar" id="<?php echo ($pr['CD']['id']);?>" onclick="eliminarPrdCot(this)"></td>
                                        </tr>
                                        <?php }?>
                                    <?php }?>
                                </tbody>
                                <tr>
                                    <td colspan="2"></td>
                                    <td><b>TOTAL</b></td>
                                    <td><div id="resultCot"></div></td>
                                </tr>                                
                                
                            </table>
                        </div>
                    </div> 
                    <legend>&nbsp;</legend>            
                    <div class="container-fluid">                        
                        <div class="row">
                            <div class="col-md-2">
                                <?php echo $this->Form->input('vendedor', array('label' => 'Vendedor', 'type' => 'select', 'options' => $vendedor, 'class' => 'form-control', 'default' => $arrCotiza['0']['Cotizacione']['usuario_id']));?>
                            </div>     
                            <div class="col-md-2">
                            <label>Placa</label><br>
                            <div class="input-group">                                                            
                                <?php echo $this->Form->input('placa', 
                                    array(
                                        'label' => '',
                                        'class' => 'form-control', 
                                        'placeholder' => 'Placa Vehículo',
                                        'value' => !empty($arrVehiculo['Vehiculo']['placa']) ? $arrVehiculo['Vehiculo']['placa'] : ""
                                        )
                                    ); 
                                ?> 
                                <a href="#" class="btn btn-default btn-sm input-group-addon" id="ver_vehiculo"><span class="far fa-eye"></span></a>                                                                
                            </div>
                            <div id="datosVehiculo" style="position:absolute; z-index:1;"></div> 
                            </div>                            
                        </div><br>             
                    
                        <div class="form-group">
                          <label for="comment">Observaciones: </label>
                          <textarea class="form-control" rows="5" id="observacion"><?php echo h($obs);?></textarea>
                        </div>
                    </div>
                    </form>
        </fieldset>
        <div class="container-fluid">            
            <div class="row">
                <div class="col-md-6" >
                    <a href="#" class="btn btn-primary btn-sm active pull-lefth" role="button" aria-pressed="true" id="imprimirCot">Imprimir Cotización</a>
                </div>
                <div class="col-md-6">
                    <?php if(!empty($cliTel)){?>        
                    <div class="row">
                        <a href="https://wa.me/57<?php echo $cliTel; ?>?text=adjuntamos%20información%20de%20su%20interés" target="_blank">
                            <img src="<?php echo $urlImgWP; ?>" class="img-responsive" width="35">            
                        </a>
                    </div>
                    <?php }else{ ?>     
                    <div class="alert alert-danger" role="alert" style="margin-top: 15px;">
                          El usuario no tiene un número celular registrado.
                        </div>    
                    <?php } ?>
                </div>
            </div>            
        </div>  

<div id="dv_emp">
    <div id="dv_info_emp">
        <div style="margin:0px; width:100%; float:left;">            
            <div style="float:left; margin-top: 10px;" align="left">
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Nit: </b><?php echo h($arrEmprea['Empresa']['nit']);?>
                    </div>          
                </div>

                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Teléfono: </b><?php echo h($arrEmprea['Empresa']['telefono1'] . " - " . $arrEmprea['Empresa']['telefono2']);?>
                    </div>                 
                </div>

                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Dirección: </b><?php echo h($arrEmprea['Empresa']['direccion']);?>
                    </div>                           
                </div>
            </div>
            <div style="float:right; margin-right:30px;">
                <img src="<?php echo $urlImg . $arrEmprea['Empresa']['id'] . '/' . $arrEmprea['Empresa']['imagen'];?>" 
                     class="img-responsive img-thumbnail center-block" width="200">  
            </div>            
        </div> 

        <div style="width:100%; float:left; margin-top: 20px;">
            <?php
            echo __($arrUbicacion['0']['Ciudade']['descripcion'] . ", " . $arrUbicacion['0']['P']['descripcion'] . ", " . $fecha);
            ?>
        </div>         
    </div>
    
    <div id="dv_emisor" style="width:100%; float:left;">
        <div style="margin-top:50px; width:100%; float:left;">            
            <div style="float:left; margin-top: 10px;" align="left">
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>EMISOR: </b><?php echo h($arrEmprea['Empresa']['nombre']);?>
                    </div>          
                </div>

                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Nit: </b><?php echo h($arrEmprea['Empresa']['nit']);?>
                    </div>                 
                </div>
            </div>           
        </div>
        
        <div style="margin-top:50px; width:100%; float:left;">            
            <div style="float:left; margin-top: 10px;" align="left">
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <small>*Precios sujetos a cambios sin previo aviso</small>
                    </div>          
                </div>

                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <small>*Se debe anticipar el 50%</small>
                    </div>                 
                </div>
            </div>           
        </div>        
    </div>
</div>
