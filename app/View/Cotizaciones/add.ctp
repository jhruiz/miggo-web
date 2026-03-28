<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('facturas/calcularValoresProducto.js')); ?>
<?php echo ($this->Html->script('cotizaciones/imprimircotizacion.js')); ?>
<?php echo ($this->Html->script('cotizaciones/cotizaciones.js')); ?>
<?php echo $this->Form->create('Cotizacione', array('type' => 'post')); ?>
	<fieldset>
            <legend><h2><b><?php echo __('Cotización'); ?></b></h2></legend>
            <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '51', 'id' => 'menuvert'))?>
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#registrado" aria-controls="registrado" data-toggle="tab" role="tab">Cotización cliente registrado</a></li>
                </ul>
            </div>
            
            <?php echo $this->Form->input('cotizacion_id', array('type' => 'hidden', 'value' => "", 'id' => 'cotizacionId'));?>
            <?php echo $this->Form->input('empresa', array('type' => 'hidden', 'value' => $empresaId, 'id' => 'empresaId'));?>
            <?php echo $this->Form->input('idcliente', array('type' => 'hidden'));?>  
            <?php echo $this->Form->input('nombre_empresa', array('type' => 'hidden', 'value' => ($arrEmprea['Empresa']['nombre']), 'id' => 'nombre_empresa'));?>
            <?php echo $this->Form->input('vehiculo', array('type' => 'hidden', 'id' => 'vehiculo_id'));?>           
            
            <div class="tab-content">                
                <!--Inicia el div para facturar productos a los usuarios registrados en la aplicacion-->
                <div role="tabpanel" class="tab-pane active" id="registrado">
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-md-12">
                                
                                <div class="form-group col-md-6">
                                    <label>Tipo de cotización</label>
                                    <?php 
                                        echo $this->Form->input('tipo_cotizacion', array(
                                            'type' => 'select',
                                            'options' => array(
                                                0 => 'Documento de venta', 
                                                1 => 'Factura'
                                            ),
                                            'label' => false, 
                                            'class' => 'form-control', 
                                            'default' => 1
                                        )); 
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <hr style="border-top: 1px solid #eee; margin: 20px 0;">
                            </div>


                            <div class="col-md-6">


                                <div class="form-group">  
                                    <label>Cliente</label><br>  
                                        <?php echo $this->Form->input('datoscliente', array('label' => false, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Cliente')); ?>
                                        <div id="datosCliente" style="position:absolute; z-index:3;"></div>
                                </div>

                                <div class="form-group">
                                    <label>Dirección</label><br>
                                    <?php echo $this->Form->input('dircliente', array('label' => false, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Dirección', 'onblur' => 'actualizarDireccionCliente();')); ?>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nit</label><br>
                                    <?php echo $this->Form->input('nitcliente', array('label' => false, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Nit', 'onblur' => 'actualizarNitCliente();')); ?>
                                </div>

                                <div class="form-group">
                                    <label>Teléfono</label><br>
                                    <?php echo $this->Form->input('telefonocliente', array('label' => false, 'class' => 'form-control registrado', 'autocomplete' => 'off', 'placeholder' => 'Teléfono', 'onblur' => 'actualizarTelefonoCliente();')); ?>
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
                                    </div>
                                    <div>
                                        <div id="datosProducto" style="position:absolute; z-index:1;"></div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>                    
                </div>
                <!--Finaliza el div para facturar productos a los usuarios registrados en la aplicacion-->
                
            </div>
            <!--Finaliza el div de los tabs para gestion de usuarios-->
                  
                    <div class="table-responsive">
                        <div class="container-fluid" id="dv_cotiza">        
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
                                <tbody id="dvTCot">                                    
                                </tbody>
                                <tbody id="imp_bolsa">
                                    <tr>
                                        <th colspan="11">&nbsp</th>
                                        <th><b>INC Bolsa</b></th>
                                        <th class="text-right"><?php echo $this->Form->input('inp_imp_bolsa', array('type' => 'text', 'label' => false, 'class' => 'form-control numericPrice', 'value' => '', 'id' => 'inp_imp_bolsa', 'disabled' => true)); ?></th>
                                    </tr>
                                </tbody>  
                                <tbody id="resultCot">
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
                            </table>
                        </div>
                    </div> 
                    <legend>&nbsp;</legend>            
                    <div class="container-fluid">                        
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $this->Form->input('vendedor', array('label' => 'Vendedor', 'type' => 'select', 'options' => $vendedor, 'class' => 'form-control', 'default' => $usuarioId));?>
                            </div>                                                               
                            <div class="col-md-4">
                            <label>Placa/Número Motor</label><br>
                            <div class="input-group">                                                            
                                <?php echo $this->Form->input('placa', 
                                         array(
                                            'label' => '',
                                            'class' => 'form-control', 
                                            'placeholder' => 'Placa Vehículo',
                                            'autocomplete' => 'off',                                            
                                            'style' => 'z-index:3; position: relative'
                                            )
                                    ); 
                                ?> 
                                <a href="#" class="btn btn-default btn-sm input-group-addon" id="ver_vehiculo"><span class="far fa-eye"></span></a>                                                                
                            </div>
                            <div id="datosVehiculo" style="position:absolute; z-index:1;"></div> 
                            </div>
                            <div class="col-md-4">
                                <div class="form-group col-md-6">
                                    <label>Crar orde de trabajo</label>
                                    <?php 
                                        echo $this->Form->input('crear_ot', array(
                                            'type' => 'select',
                                            'options' => array(
                                                0 => 'No', 
                                                1 => 'Si'
                                            ),
                                            'label' => false, 
                                            'class' => 'form-control'
                                        )); 
                                    ?>
                                </div>
                            </div>                                                               
                        </div><br>             
                    
                        <div class="form-group">
                          <label for="comment">Observaciones: </label>
                          <textarea class="form-control" rows="5" id="observacion"></textarea>
                        </div>                    
                    </div>
                    </form>
        </fieldset>
        <div class="container-fluid">            
            <div class="row">
                <div class="col-md-4" >
                    <a href="#" class="btn btn-primary btn-sm active pull-lefth" role="button" aria-pressed="true" id="imprimirCot">Imprimir Cotización</a>
                </div>
                <div class="col-md-4" >
                    <a href="#" class="btn btn-primary btn-sm active pull-lefth" role="button" aria-pressed="true" id="generarPrefac">Generar Prefactura</a>
                </div>
                <div class="col-md-4">   
                    <div class="row">
                        <a href="#" class="wppSendCot" target="">
                            <img src="<?php echo $urlImgWP; ?>" class="img-responsive" width="35">            
                        </a>
                    </div>
                </div>
            </div>            
        </div>  
