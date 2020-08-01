<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('ordentrabajos/gestionOrdenTrabajo'));?>
<?php echo ($this->Html->script('ordentrabajos/imprimirOrden'));?>
<div class="container body">
<div class="main_container">
<div class="ordentrabajos form">
<?php echo $this->Form->create('Ordentrabajo', array('type' => 'file', 'class' => 'form-inline')); ?>

                     
            <?php echo $this->Form->input('empresa', array('type' => 'hidden', 'value' => $empresaId, 'id' => 'empresaId'));?>
            <?php echo $this->Form->input('vehiculo', array('type' => 'hidden', 'id' => 'vehiculo_id', 'value' => !empty($arrOrdenT['0']['Ordentrabajo']['vehiculo_id']) ? $arrOrdenT['0']['Ordentrabajo']['vehiculo_id'] : ""));?>
            <?php echo $this->Form->input('cliente', array('type' => 'hidden', 'id' => 'cliente_id', 'name' => 'cliente_id', 'value' => !empty($arrOrdenT['0']['Ordentrabajo']['cliente_id']) ? $arrOrdenT['0']['Ordentrabajo']['cliente_id'] : ""));?>
            <?php echo $this->Form->input('ordentrabajo', array('type' => 'hidden', 'id' => 'ordentrabajo_id', 'value' => $id));?>
            <?php echo $this->Form->input('usuarioId', array('type' => 'hidden', 'value' => $usuarioId, 'id' => 'usuarioId'));?>
            <?php echo $this->Form->input('nombre_empresa', array('type' => 'hidden', 'value' => ($arrEmprea['Empresa']['nombre']), 'id' => 'nombre_empresa'));?>
            <?php echo $this->Form->input('nit_empresa', array('type' => 'hidden', 'value' => ($arrEmprea['Empresa']['nit']), 'id' => 'nit_empresa'));?>
         
<div class="col-md-12"> 
  <div class="x_panel">
        <div class="x_title">
           <h2><?php echo __('General'); ?></h2>
           <ul class="nav navbar-right panel_toolbox"></ul>
       </div>  
      <div class="container-fluid" style="margin-bottom: 10px;">
                <div class="row">
                    <div class="col-md-4">
                        <label>Estado</label>
                        <?php 
                            echo $this->Form->input("ordenestado_id",
                                    array(
                                        'name'=>"ordenestado",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$arrOrdenEst,
                                        'empty'=>'Seleccione Uno',
                                        'class' => 'form-control',
                                        'default'=> !empty($arrOrdenT['0']['OE']['id']) ? $arrOrdenT['0']['OE']['id'] : ""
                                    )
                            );
                        ?>
                    </div>
                    <div class="col-md-4">
                        <label>Kilometraje</label>
                        <?php echo $this->Form->input('Kilometraje', 
                                array(
                                    'label' => '',
                                    'class' => 'form-control', 
                                    'placeholder' => 'Kilometraje Actual',
                                    'value' => !empty($arrOrdenT['0']['Ordentrabajo']['kilometraje']) ? $arrOrdenT['0']['Ordentrabajo']['kilometraje'] : ""
                                    )
                                ); 
                        ?>                                    
                    </div>
                    <div class="col-md-4">                                      
                        <label>Placa</label><br>
                        <div class="input-group">                                                            
                            <?php echo $this->Form->input('placa', 
                                    array(
                                        'label' => '',
                                        'class' => 'form-control', 
                                        'placeholder' => 'Placa Vehículo',
                                        'value' => !empty($arrOrdenT['0']['VH']['placa']) ? $arrOrdenT['0']['VH']['placa'] : ""
                                        )
                                    ); 
                            ?> 
                            <a href="#" class="btn btn-default btn-sm input-group-addon" id="ver_vehiculo"><span class="far fa-eye"></span></a>                                                                
                        </div>
                        <div id="datosVehiculo" style="position:absolute; z-index:1;"></div> 
                    </div>                    
                </div>
            </div>
       
      <div class="container-fluid" style="margin-bottom: 20px;">
                <div class="row">
                    <div class="col-md-4">
                        <label>Mecánico</label>
                        
                        <?php 
                            echo $this->Form->input("usuario_id",
                                    array(
                                        'name'=>"usuario",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$arrUsr,
                                        'empty'=>'Seleccione Uno',
                                        'class' => 'form-control',
                                        'default' => !empty($arrOrdenT['0']['US']['id']) ? $arrOrdenT['0']['US']['id'] : ""
                                    )
                            );
                        ?>

                    </div>
                    <div class="col-md-4">
                        <label>Cliente</label><br>
                        <div class="input-group">
                        <?php echo $this->Form->input('cliente', 
                                array(
                                    'label' => '',
                                    'class' => 'form-control', 
                                    'placeholder' => 'Cliente',
                                    'value' => !empty($arrOrdenT['0']['CL']['id']) ? $arrOrdenT['0']['CL']['nombre'] . " - " . $arrOrdenT['0']['CL']['nit'] : ""
                                    )
                                ); 
                        ?>
                        <a href="#" class="btn btn-default btn-sm input-group-addon" id="ver_cliente"><span class="far fa-eye"></span></a> 
                        </div>                            
                        <div id="datosCliente" style="position:absolute; z-index:1;"></div>
                    </div>
                    <div class="col-md-4">
                        <label>Planta</label>
                         <?php 
                             echo $this->Form->input("plantaservicio_id",
                                     array(
                                         'name'=>"plantaservicio",
                                         'label' => "",
                                         'type' => 'select',
                                         'options'=>$arrPlantas,
                                         'empty'=>'Seleccione Uno',
                                         'class' => 'form-control',
                                         'default' => !empty($arrOrdenT['0']['PS']['id']) ? $arrOrdenT['0']['PS']['id'] : ""
                                     )
                             );
                         ?>
                    </div>
                </div>
            </div>
            
            <div class="container-fluid" style="margin-bottom: 20px;">
                <div class="row">
                    <div class="col-md-3">
                        <label>Fecha Ingreso</label><br>
                        <input name="data[Ordentrabajo][fecha_ingreso]" class="date form-control" autocomplete="off" placeholder="Fecha de Ingreso" type="text" id="fecha_ingreso" value="<?php echo !empty($arrOrdenT['0']['Ordentrabajo']['fecha_ingreso']) ? $arrOrdenT['0']['Ordentrabajo']['fecha_ingreso'] : ''; ?>">                        
                    </div>
                    
                    <div class="col-md-3">
                        <label>Fecha Salida</label><br>
                        <input name="data[Ordentrabajo][fecha_salida]" class="date form-control" autocomplete="off" placeholder="Fecha de Salida" type="text" id="fecha_salida" value="<?php echo !empty($arrOrdenT['0']['Ordentrabajo']['fecha_salida']) ? $arrOrdenT['0']['Ordentrabajo']['fecha_salida'] : ''; ?>">                        
                    </div>
                    
                    <div class="col-md-3">
                        <label>Soat</label><br>
                        <input name="data[Ordentrabajo][soat]" class="date form-control" autocomplete="off" placeholder="Fecha Vence Soat" type="text" id="fecha_soat" value="<?php echo !empty($arrOrdenT['0']['Ordentrabajo']['soat']) ? $arrOrdenT['0']['Ordentrabajo']['soat'] : "";?>">                        
                    </div>
                    
                    <div class="col-md-3"><br>
                        <label>Tecnomecánica</label>
                        <input name="data[Ordentrabajo][tecnomecanica]" class="date form-control" autocomplete="off" placeholder="Fecha Vence Tecno" type="text" id="fecha_tecno" value="<?php echo !empty($arrOrdenT['0']['Ordentrabajo']['tecnomecanica']) ? $arrOrdenT['0']['Ordentrabajo']['tecnomecanica'] : "";?>">                        
                    </div>                   
                </div>
            </div><br>  
            </div>   
            
             </div><!-- Termina COL -->        
            <div class="col-md-12"> 
               
            <div class="x_content">
                <div class="accordion" id="accordion1" role="tablist" aria-multiselectable="true">
                    <div class="x_panel">
                        <div class="x_title">
                        <a class="panel-heading collapsed" role="tab" id="headingOne1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne1" aria-expanded="false" aria-controls="collapseOne">
                          <h4 class="panel-title"><?php echo __('Partes del Vehículo'); ?> Ver más +</h4>
                        </a>
                    </div>
                        <div id="collapseOne1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="true" style="">
            <div id="partesVehiculo">
                <?php foreach ($arrPartesV as $pv){?>
                <div class="col-md-4" style="margin-bottom: 20px;">
                                <div class="form-group">
                                <label><?php echo ($pv['PV']['descripcion']) ?></label><br>
                            
                            <?php 
                                echo $this->Form->input("plantaservicio_id",
                                        array(
                                            'name' => "plantaservicio",
                                            'id' => $pv['PV']['id'],
                                            'label' => "",
                                            'type' => 'select',
                                            'options'=>$arrEstadoP,
                                            'empty'=>'Seleccione Uno',
                                            'class' => 'form-control prtVehiculo',
                                            'default' => $pv['OrdentrabajosPartevehiculo']['estadoparte_id']
                                        )
                                );
                            ?>   
                                                                           
                            </div>
                       
                    </div>
              
                <?php } ?>
     
            </div>
            </div>
            </div><!--termina content panel-->
        </div>
    </div>
            </div> <!--TEMIRNA COL -->
            <div class="col-md-12"> 
                  <div class="x_panel">
                  aqui partes especiales
                  </div>
              </div>
            <br>
             <div class="col-md-12"> 
                 <div class="x_panel">
                 <div class="x_title">
                    <h2><?php echo __('Observaciones'); ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                  
                  </li>
                  <li class="dropdown">
                   
                  </li>
                
                  </li>
                </ul>
</div>  
     
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <label>Observaciones Mecánico</label>
                        <?php echo $this->Form->input('observaciones_usuario', array(
                            'label' => "",
                            'class' => 'form-control', 
                            'placeholder' => 'Observaciones del mecánico',
                            'style' => 'width:100%',
                            'value' => !empty($arrOrdenT['0']['Ordentrabajo']['observaciones_usuario']) ? $arrOrdenT['0']['Ordentrabajo']['observaciones_usuario'] : ''
                            )); 
                        ?>
                    </div>
                    <div class="col-md-6">
                        <label>Observaciones Cliente</label>
                        <?php echo $this->Form->input('observaciones_cliente', array(
                            'label' => "",
                            'class' => 'form-control', 
                            'placeholder' => 'Observaciones del cliente',
                            'style' => 'width:100%',
                            'value' => !empty($arrOrdenT['0']['Ordentrabajo']['observaciones_cliente']) ? $arrOrdenT['0']['Ordentrabajo']['observaciones_cliente'] : ''
                            )); 
                        ?>
                    </div>
                </div>
            </div><br>
        </div><!--TEMRINA PANEL-->
            </div><!--TERMINA COL-->
            
            
            <div class="col-md-12"> 
                <div class="x_panel">
                 <div class="x_title">
                    <h2><?php echo __('Suministros'); ?></h2>
                </div>  
            
            
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <label>Producto</label>                                
                        <?php echo $this->Form->input('producto', array(
                            'label' => '', 
                            'class' => 'form-control', 
                            'autocomplete' => 'off', 
                            'placeholder' => 'Selección de Producto',
                            )); ?>
                        <div id="datosProducto" style="position:absolute; z-index:1;"></div>                                                
                    </div>
                    <div class="col-md-6">&nbsp;</div>
                </div><br><br>
                <div class="row">
                    <div class="col-md-2">&nbsp;</div>
                    <div class="col-md-8">
                        <div class="table-responsive">    
                            <table  id="productosOrden" cellpadding="0" cellspacing="0" class="table">
                                <thead>
                                <tr>                                            
                                    <th><?php echo ('Código'); ?></th>
                                    <th><?php echo ('Nombre'); ?></th>
                                    <th><?php echo ('Cantidad'); ?></th>
                                    <th>&nbsp;</th>
                                </tr> 
                                </thead>
                                <tbody class="tProductos">
                                    <?php foreach ($arrSums as $sums) {?>
                                    
                                    <tr id="tr_<?php echo __($sums['CI']['id']);?>">
                                    <td><?php echo __($sums['P']['codigo'])?></td>
                                    <td><?php echo __($sums['P']['descripcion'])?></td>
                                    <td><input type="text" value="<?php echo __($sums['OrdentrabajosSuministro']['cantidad']);?>" class="form-control" id="inp_<?php echo __($sums['CI']['id']);?>"
                                     onblur="actualizarCantidadSum(this);"></td>
                                    <td><input type="button" class="btn btn-danger" value="Eliminar" id="elim_<?php echo __($sums['CI']['id']);?>"
                                     onclick="eliminarSumOrden(this)"></td>
                                    </tr>                                 
                                    <?php } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-2">&nbsp;</div>
                </div>                 
            </div>   
            </div> <!-- termina panel-->
            </div>  <!-- Temrina col-->   

            <div class="col-md-12"> 
                <div class="x_panel">
                    <div class="x_title">
                        <h2><?php echo __('Acciones'); ?></h2>
                    </div>  
                        
                    <div class="container-fluid">
                        <div class="container-fluid">
                            <div class="col-md-3">                
                                <a href="#" class="wppSendPF" target="">
                                    <img src="<?php echo $urlImgWP; ?>" class="img-responsive" width="35">            
                                </a>
                            </div>

                            <div class="col-md-3">                
                                <a href="#" class="btn btn-primary btn-sm active" role="button" aria-pressed="true" id="btn_orden_trabajo">Orden de Trabajo</a>
                            </div>

                            <div class="col-md-3">
                                <a href="#" class="btn btn-primary btn-sm active" role="button" aria-pressed="true" id="btn_orden_entrada">Remisión de Entrada</a>
                            </div>

                            <div class="col-md-3">
                                <a href="#" class="btn btn-primary btn-sm active" role="button" aria-pressed="true" id="btn_alerta">Generar Alerta</a>
                            </div>
                        </div> 
                    
                    </div>   
                </div> <!-- termina panel-->
            </div>  <!-- Temrina col-->         
            
	
    </form>
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
    
    <div id="aclaraciones" style="width: 100%; float: left;">
        <div style="margin-top: 50px; width: 100%; float: left;">
            <div style="float:left; margin-top: 10px;" align="left">
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>ACLARACIONES: </b>
                    </div>          
                </div>

                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        En el momento de la entrada del vechículo se debe cancelar el total del valor de los repuestos. Si la orden
                        de trabajo se encuentra terminada y su vehículo no ha sido recogido en los proximos 4 días posterior a esta,
                        al día 5 se le procedera a efetuar un cobro de parqueadero de valor de $5.500 pesos diarios
                    </div>                 
                </div>
            </div>             
        </div>
    </div>
    <div id="conditions_ot">    
        <div id="p_condCont_ot"><small>
            <b>Condiciones del Contrato: 1</b>. El cliente autoriza a quien firma en el presente contrato a ordenar y contratar con el centro de servicio, la ejecución de los respectivos trabajos y por tanto
            da fe que conoce y acepta en su totalidad las condiciones que son parte integrante del contrato que se celebra y consta en el presente documento. <b>2</b>. El centro de servicio queda
            facultado para realizar las pruebas que requiera el vehiculó por fuera del taller. <b>3</b>. El centro de servicio no se hacer responsable por objetos dejados dentro del vehiculo. <b>4</b>. El cliente o la
            persona autorizada. Faculta expresamente al taller. TOQUE RACING S.A.S., a ejercer el derecho de retención del vehiculo. <b>5</b>. El centro de servicio no se hace responsable por daños o
            deterioro del vehiculo. Si estos se presentan por causas de fuerza mayor o extensión de tiempo causado por el cliente. <b>6</b>. El propietario o autorizado firmante del presente contrato, se
            comprometen a reconocer un valor de cinco mil pesos m/cte. ($ 5.000) por concepto de parqueo, por cada día que transcurra desde que finalice los trabajos hasta el momento de
            retiro del vehiculo. <b>7</b>. Aclaraciones: En el momento de la entrada del vehículo se debe cancelar el total del valor de los repuestos. Si la orden de trabajo se encuentra terminada y su
            vehículo no ha sido recogido en los próximos 4 días posterior a esta, al día 5 se le procedera a efectuar un cobro de parqueadero de valor de $5.500 pesos diario.                 
        </small></div>
    </div>
</div>
</div><!-- class="container -->
</div> <!--class="main_container-->