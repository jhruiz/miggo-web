<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('ordentrabajos/gestionOrdenTrabajo'));?>
<div class="ordentrabajos form">
<?php echo $this->Form->create('Ordentrabajo', array('type' => 'file', 'class' => 'form-inline')); ?>
	<fieldset>                    
            <?php echo $this->Form->input('empresa', array('type' => 'hidden', 'value' => $empresaId, 'id' => 'empresaId'));?>
            <?php echo $this->Form->input('vehiculo', array('type' => 'hidden', 'id' => 'vehiculo_id'));?>
            <?php echo $this->Form->input('cliente', array('type' => 'hidden', 'id' => 'cliente_id', 'name' => 'cliente_id'));?>
            <?php echo $this->Form->input('ordentrabajo', array('type' => 'hidden', 'id' => 'ordentrabajo_id'));?>
            <?php echo $this->Form->input('usuarioId', array('type' => 'hidden', 'value' => $usuarioId, 'id' => 'usuarioId'));?>
            <?php echo $this->Form->input('nombre_empresa', array('type' => 'hidden', 'value' => ($arrEmprea['Empresa']['nombre']), 'id' => 'nombre_empresa'));?>
            
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
                                        'class' => 'form-control'
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
                                    'placeholder' => 'Kilometraje Actual'
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
                                        'placeholder' => 'Placa Vehículo'
                                        )
                                    ); 
                            ?> 
                            <a href="#" class="btn btn-default btn-sm input-group-addon" id="ver_vehiculo"><span class="far fa-eye"></span></a>                                                                
                        </div>
                        <div id="datosVehiculo" style="position:absolute; z-index:1;"></div> 
                    </div>
                </div>
            </div>
      
            <div class="container-fluid" style="margin-bottom: 10px;">
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
                                        'class' => 'form-control'
                                    )
                            );
                        ?>
                    </div>
                    <div class="col-md-4">
                        <label>Cliente</label>
                        <?php echo $this->Form->input('cliente', 
                                array(
                                    'label' => '',
                                    'class' => 'form-control', 
                                    'placeholder' => 'Cliente'
                                    )
                                ); 
                        ?>
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
                                         'class' => 'form-control'
                                     )
                             );
                         ?>
                    </div>
                </div>
            </div>
            
            <div class="container-fluid" style="margin-bottom: 10px;">
                <div class="row">
                    <div class="col-md-3">
                        <label>Fecha Ingreso</label><br>
                        <input name="data[Ordentrabajo][fecha_ingreso]" class="date form-control" autocomplete="off" placeholder="Fecha de Ingreso" type="text" id="fecha_ingreso">                        
                    </div>
                    
                    <div class="col-md-3">
                        <label>Fecha Salida</label><br>
                        <input name="data[Ordentrabajo][fecha_salida]" class="date form-control" autocomplete="off" placeholder="Fecha de Salida" type="text" id="fecha_salida">                        
                    </div>
                    
                    <div class="col-md-3">
                        <label>Soat</label><br>
                        <input name="data[Ordentrabajo][soat]" class="date form-control" autocomplete="off" placeholder="Fecha Vence Soat" type="text" id="fecha_soat">                        
                    </div>
                    
                    <div class="col-md-3">
                        <label>Tecnomecánica</label><br>
                        <input name="data[Ordentrabajo][tecnomecanica]" class="date form-control" autocomplete="off" placeholder="Fecha Vence Tecno" type="text" id="fecha_tecno">                        
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
                        <div id="partesVehiculo"></div>
                    </div>
                </div><!--termina content panel-->
            </div>
        </div>    
    </div> <!--TEMIRNA COL -->        
            
            
    <div class="col-md-12"> 
        <div class="x_panel">
            <div class="x_title">
               <h2><?php echo __('Observaciones'); ?></h2>
               <ul class="nav navbar-right panel_toolbox"></ul>
            </div>              
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <label>Observaciones Mecánico</label>
                        <?php echo $this->Form->input('observaciones_usuario', array(
                            'label' => "",
                            'class' => 'form-control', 
                            'placeholder' => 'Observaciones del mecánico',
                            'style' => 'width:100%'
                            )); 
                        ?>
                    </div>
                    <div class="col-md-6">
                        <label>Observaciones Cliente</label>
                        <?php echo $this->Form->input('observaciones_cliente', array(
                            'label' => "",
                            'class' => 'form-control', 
                            'placeholder' => 'Observaciones del cliente',
                            'style' => 'width:100%'
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
                <ul class="nav navbar-right panel_toolbox"></ul>
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
                                <tbody class="tProductos"></tbody>
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
                <ul class="nav navbar-right panel_toolbox"></ul>
            </div>  
            
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

        </div> <!-- termina panel-->
    </div>  <!-- Temrina col-->              
            
	</fieldset>
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
</div>


