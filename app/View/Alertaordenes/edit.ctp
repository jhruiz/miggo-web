<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('alertaordenes/edit'));?>
<div class="ordentrabajos form">
<?php echo $this->Form->create('Ordentrabajo', array('type' => 'file', 'class' => 'form-inline')); ?>
<fieldset>                    
<?php echo $this->Form->input('km_actual', array('type' => 'hidden', 'value' => $alertasOrdenes['0']['O']['kilometraje'], 'id' => 'km_actual'));?>
<?php echo $this->Form->input('alerta_id', array('type' => 'hidden', 'value' => $alertasOrdenes['0']['Alertaordene']['id'], 'id' => 'alerta_id'));?>
<?php echo $this->Form->input('vehiculo_id', array('type' => 'hidden', 'value' => $alertasOrdenes['0']['VH']['id'], 'id' => 'vehiculoId'));?>
<?php echo $this->Form->input('cliente_id', array('type' => 'hidden', 'value' => $alertasOrdenes['0']['CL']['id'], 'id' => 'clienteId'));?>
<?php echo $this->Form->input('soat', array('type' => 'hidden', 'value' => $alertasOrdenes['0']['O']['soat'], 'id' => 'soat'));?>
<?php echo $this->Form->input('tecno', array('type' => 'hidden', 'value' => $alertasOrdenes['0']['O']['tecnomecanica'], 'id' => 'tecnomecanica'));?>

<div class="col-md-12">
 
    <div class="x_panel">
        <div class="x_title">
           <h2><?php echo __('Cliente'); ?></h2>
           <ul class="nav navbar-right panel_toolbox"></ul>
       </div>          
        <div class="container-fluid" style="margin-bottom: 10px;">
            <div class="row">

                <div class="col-md-3">
                    <label>Nombre</label><br>
                    <?php echo($alertasOrdenes['0']['CL']['nombre']); ?>
                </div>

                <div class="col-md-3">
                    <label>Nit</label><br>   
                    <?php echo($alertasOrdenes['0']['CL']['nit']); ?>                                                     
                </div>                    

                <div class="col-md-3">                                      
                    <label>Teléfono</label><br>  
                    <?php echo($alertasOrdenes['0']['CL']['celular']); ?>                      
                </div>

                <div class="col-md-3">                                      
                    <label>Dirección</label><br>
                    <?php echo($alertasOrdenes['0']['CL']['direccion']); ?>                        
                </div>

            </div>
        </div>  

    </div><!-- Termina COL -->                 

    <div class="x_panel">
        <div class="x_title">
           <h2><?php echo __('Vehículo'); ?></h2>
           <ul class="nav navbar-right panel_toolbox"></ul>
       </div>          
            <div class="container-fluid" style="margin-bottom: 10px;">
                <div class="row">

                    <div class="col-md-2">
                        <label style="margin-bottom:10px;">Placa</label><br>
                        <?php echo($alertasOrdenes['0']['VH']['placa']); ?>
                    </div>

                    <div class="col-md-2">
                        <label style="margin-bottom:10px;">Modelo</label><br>   
                        <?php echo($alertasOrdenes['0']['VH']['modelo']); ?>                                                     
                    </div>                    

                    <div class="col-md-2">                                      
                        <label style="margin-bottom:10px;">Línea</label><br>  
                        <?php echo($alertasOrdenes['0']['VH']['linea']); ?>                      
                    </div>

                    <div class="col-md-2">                                      
                        <label style="margin-bottom:10px;">Kilometraje</label><br>
                        <?php echo($alertasOrdenes['0']['O']['kilometraje']); ?>                        
                    </div>

                    <div class="col-md-2">                                      
                        <label>SOAT</label><br>
                        <?php echo($alertasOrdenes['0']['O']['soat']); ?>   
                        <a href="#" class="btn btn-default btn-sm" id="alerta_soat"><span class="far fa-eye"></span></a>                     
                    </div>

                    <div class="col-md-2">                                      
                        <label>Tecnomecánica</label><br>
                        <?php echo($alertasOrdenes['0']['O']['tecnomecanica']); ?>                
                        <a href="#" class="btn btn-default btn-sm" id="alerta_tecno"><span class="far fa-eye"></span></a>        
                    </div>

                </div>
      
        </div>   

    </div><!-- Termina COL -->                    

    <div class="x_panel">
        <div class="x_title">
           <h2><?php echo __('Orden de Trabajo'); ?></h2>
           <ul class="nav navbar-right panel_toolbox"></ul>
       </div>          
            <div class="container-fluid" style="margin-bottom: 10px;">
                <div class="row">

                    <div class="col-md-1">&nbsp;</div>

                    <div class="col-md-2">
                        <label>Planta de servicio</label><br>
                        <?php echo($alertasOrdenes['0']['PS']['descripcion']); ?>
                    </div>

                    <div class="col-md-2">
                        <label>Técnico</label><br>   
                        <?php echo($alertasOrdenes['0']['US']['nombre']); ?>                                                     
                    </div>                    

                    <div class="col-md-2">                                      
                        <label>Estado</label><br>  
                        <?php echo($alertasOrdenes['0']['OE']['descripcion']); ?>                      
                    </div>

                    <div class="col-md-2">                                      
                        <label>Fecha de ingreso</label><br>
                        <?php echo($alertasOrdenes['0']['O']['fecha_ingreso']); ?>                        
                    </div>

                    <div class="col-md-2">                                      
                        <label>Fecha de salida</label><br>
                        <?php echo($alertasOrdenes['0']['O']['fecha_salida']); ?>                        
                    </div>

                    <div class="col-md-1">&nbsp;</div>

                </div>
        </div>   

    </div><!-- Termina COL -->                    

    <div class="x_panel">
        <div class="x_title">
           <h2><?php echo __('Gestión Alerta'); ?></h2>
           <ul class="nav navbar-right panel_toolbox"></ul>
       </div>          
            <div class="container-fluid" style="margin-bottom: 10px;">
                <div class="row">

                    <div class="col-md-3">
                        <label>Tipo de alerta</label><br>
                        <?php echo($alertasOrdenes['0']['AL']['descripcion']); ?>
                    </div>

                    <div class="col-md-3">
                        <label>Estado alerta</label><br>  
                        <?php 
                            echo $this->Form->input("estado_alerta_id",
                                    array(
                                        'name'=>"estado_alerta",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$estadoAlertas,
                                        'empty'=>'Seleccione Una',
                                        'class' => 'form-control',
                                        'selected' => $alertasOrdenes['0']['EA']['id']
                                    )
                            );
                        ?>                                                                                                     
                    </div>                    

                    <div class="col-md-3">                                      
                        <label>Unidad de medida</label><br>  
                        <?php echo $alertasOrdenes['0']['UM']['descripcion']; ?>                          
                    </div>

                    <div class="col-md-3">                                      
                        <label>Fecha creación</label><br>
                        <?php echo $alertasOrdenes['0']['Alertaordene']['created']; ?>                        
                    </div>

                </div>
      
        </div>   

    </div><!-- Termina COL -->    


    <div class="x_panel">
        <div class="x_title">
           <h2><?php echo __('Gestión por ') . $alertasOrdenes['0']['UM']['descripcion']?></id></h2>
           <ul class="nav navbar-right panel_toolbox"></ul>
       </div>          
            <div class="container-fluid" style="margin-bottom: 10px;">
                <div class="row">

                        <div class="col-md-3">
                            <label>Fecha próximo mantenimiento</label><br>   
                            <?php echo $alertasOrdenes['0']['Alertaordene']['fecha_mantenimiento'];?>
                        </div>                    
                        
                        <div class="col-md-3">
                            <label>Fecha inicio de alerta</label><br>
                            <?php echo $alertasOrdenes['0']['Alertaordene']['fecha_alerta'];?>
                        </div>
                        
                        <div class="col-md-3">
                            <label>Fecha última llamada</label><br>
                            <div id="ult_llamada">
                                <?php echo $alertasOrdenes['0']['Alertaordene']['fecha_ultima_llamada'];?>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                        <label>Cantidad llamadas</label><br>
                        <button                         
                            name="cant_llamadas" id="cant_llamadas" type="button" class="btn btn-primary" >
                                <div id="cant_llamadas_text">
                                    <?php echo !empty($alertasOrdenes['0']['Alertaordene']['cant_llamadas']) ?
                                    $alertasOrdenes['0']['Alertaordene']['cant_llamadas'] : 0  ?>                            
                                </div>
                            </button>
                        </div>
                </div>

        </div>   

    </div><!-- Termina COL --> 
         
            
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo __('Observaciones'); ?></h2>
            <ul class="nav navbar-right panel_toolbox"></ul>
        </div>              
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php echo $this->Form->input('observaciones_cliente', array(
                        'label' => "",
                        'class' => 'form-control', 
                        'placeholder' => 'Observaciones del cliente',
                        'style' => 'width:100%',
                        'value' => $alertasOrdenes['0']['Alertaordene']['observaciones']
                        )); 
                    ?>
                </div>
            </div>
        </div>
    </div><!--TEMRINA PANEL-->              
            
	</fieldset>
    </form>
</div>  


