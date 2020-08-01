<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('alertaordenes/alertaordenes'));?>
<div class="ordentrabajos form">
<?php echo $this->Form->create('Ordentrabajo', array('type' => 'file', 'class' => 'form-inline')); ?>
<fieldset>                    
<?php echo $this->Form->input('empresa', array('type' => 'hidden', 'value' => $empresa_id, 'id' => 'empresaId'));?>
<?php echo $this->Form->input('ordentrabajo', array('type' => 'hidden', 'value' => $ordenTrabajoId, 'id' => 'ordenTId'));?>
<?php echo $this->Form->input('vehiculo_id', array('type' => 'hidden', 'value' => $infoOrdenCliV['0']['VH']['id'], 'id' => 'vehiculoId'));?>
<?php echo $this->Form->input('cliente_id', array('type' => 'hidden', 'value' => $infoOrdenCliV['0']['CL']['id'], 'id' => 'clienteId'));?>
<?php echo $this->Form->input('km_actual', array('type' => 'hidden', 'value' => $infoOrdenCliV['0']['Ordentrabajo']['kilometraje'], 'id' => 'km_actual'));?>
<?php echo $this->Form->input('soat', array('type' => 'hidden', 'value' => $infoOrdenCliV['0']['Ordentrabajo']['soat'], 'id' => 'soat'));?>
<?php echo $this->Form->input('tecno', array('type' => 'hidden', 'value' => $infoOrdenCliV['0']['Ordentrabajo']['tecnomecanica'], 'id' => 'tecnomecanica'));?>

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
                    <?php echo($infoOrdenCliV['0']['CL']['nombre']); ?>
                </div>

                <div class="col-md-3">
                    <label>Nit</label><br>   
                    <?php echo($infoOrdenCliV['0']['CL']['nit']); ?>                                                     
                </div>                    

                <div class="col-md-3">                                      
                    <label>Teléfono</label><br>  
                    <?php echo($infoOrdenCliV['0']['CL']['telefono']); ?>                      
                </div>

                <div class="col-md-3">                                      
                    <label>Dirección</label><br>
                    <?php echo($infoOrdenCliV['0']['CL']['direccion']); ?>                        
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
                        <?php echo($infoOrdenCliV['0']['VH']['placa']); ?>
                    </div>

                    <div class="col-md-2">
                        <label style="margin-bottom:10px;">Modelo</label><br>   
                        <?php echo($infoOrdenCliV['0']['VH']['modelo']); ?>                                                     
                    </div>                    

                    <div class="col-md-2">                                      
                        <label style="margin-bottom:10px;">Línea</label><br>  
                        <?php echo($infoOrdenCliV['0']['VH']['linea']); ?>                      
                    </div>

                    <div class="col-md-2">                                      
                        <label style="margin-bottom:10px;">Kilometraje</label><br>
                        <?php echo($infoOrdenCliV['0']['Ordentrabajo']['kilometraje']); ?>                        
                    </div>

                    <div class="col-md-2">                                      
                        <label>SOAT</label><br>
                        <?php echo($infoOrdenCliV['0']['Ordentrabajo']['soat']); ?>   
                        <a href="#" class="btn btn-default btn-sm" id="alerta_soat"><span class="far fa-eye"></span></a>                     
                    </div>

                    <div class="col-md-2">                                      
                        <label>Tecnomecánica</label><br>
                        <?php echo($infoOrdenCliV['0']['Ordentrabajo']['tecnomecanica']); ?>                
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
                        <?php echo($infoOrdenCliV['0']['PS']['descripcion']); ?>
                    </div>

                    <div class="col-md-2">
                        <label>Técnico</label><br>   
                        <?php echo($infoOrdenCliV['0']['US']['nombre']); ?>                                                     
                    </div>                    

                    <div class="col-md-2">                                      
                        <label>Estado</label><br>  
                        <?php echo($infoOrdenCliV['0']['OE']['descripcion']); ?>                      
                    </div>

                    <div class="col-md-2">                                      
                        <label>Fecha de ingreso</label><br>
                        <?php echo($infoOrdenCliV['0']['Ordentrabajo']['fecha_ingreso']); ?>                        
                    </div>

                    <div class="col-md-2">                                      
                        <label>Fecha de salida</label><br>
                        <?php echo($infoOrdenCliV['0']['Ordentrabajo']['fecha_salida']); ?>                        
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
                        <?php 
                            echo $this->Form->input("tipo_alerta_id",
                                    array(
                                        'name'=>"tipo_alerta",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$alertas,
                                        'empty'=>'Seleccione Una',
                                        'class' => 'form-control'
                                    )
                            );
                        ?>
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
                                        'class' => 'form-control'
                                    )
                            );
                        ?>                                                                                                     
                    </div>                    

                    <div class="col-md-3">                                      
                        <label>Unidad de medida</label><br>  
                        <?php 
                            echo $this->Form->input("unidades_medida_id",
                                    array(
                                        'name'=>"unidades_medida",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$unidadesMed,
                                        'class' => 'form-control'
                                    )
                            );
                        ?>                          
                    </div>

                    <div class="col-md-3">                                      
                        <label>Fecha actual</label><br>
                        <?php echo($fechaActual); ?>                        
                    </div>

                </div>
      
        </div>   

    </div><!-- Termina COL -->    


    <div class="x_panel">
        <div class="x_title">
           <h2><?php echo __('Gestión por '); ?><div id="tipo_unidad_medida"></id></h2>
           <ul class="nav navbar-right panel_toolbox"></ul>
       </div>          
            <div class="container-fluid" style="margin-bottom: 10px;">
                <div class="row">

                    <div id="day_unit">
                        <div class="col-md-3">                                      
                            <label>Kilómetros prox. mantenimiento</label><br>
                            <?php echo $this->Form->input('kmproxmant', 
                                        array(
                                            'label' => '',
                                            'class' => 'form-control calckm numericPrice', 
                                            'placeholder' => 'Kilómetros próximo mantenimiento'
                                            )
                                        ); 
                            ?>                      
                        </div>                 

                        <div class="col-md-3">                                      
                            <label>Km promedio x día</label><br>  
                            <?php echo $this->Form->input('kmxdia', 
                                        array(
                                            'label' => '',
                                            'class' => 'form-control calckm numericPrice', 
                                            'placeholder' => 'Kilómetros por días prom.'
                                            )
                                        ); 
                            ?>                      
                        </div>
               
                    </div>

                        <div class="col-md-3">
                            <label>Fecha próximo mantenimiento</label><br>   
                            <input class="date form-control" placeholder="Fecha de Mantenimiento" type="text" id="fecha_mant">           
                        </div>                    
                        
                        <div class="col-md-3">
                            <label>Fecha de alerta</label><br>
                            <input class="date form-control" placeholder="Fecha de Alerta" type="text" id="fecha_alerta">
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
                        'style' => 'width:100%'
                        )); 
                    ?>
                </div>
            </div>
        </div>
    </div><!--TEMRINA PANEL-->              
            
	</fieldset>
    <a href="#" class="btn btn-primary active" role="button" aria-pressed="true" id="guardarAlerta">Guardar Alerta</a>
    </form>
</div>  


