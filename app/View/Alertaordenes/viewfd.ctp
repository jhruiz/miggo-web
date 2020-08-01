<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('alertaordenes/edit'));?>
<div class="ordentrabajos form">
<?php echo $this->Form->create('Ordentrabajo', array('type' => 'file', 'class' => 'form-inline')); ?>
<fieldset>                    
<?php echo $this->Form->input('alerta_id', array('type' => 'hidden', 'value' => $alertasOrdenes['0']['Alertaordene']['id'], 'id' => 'alerta_id'));?>

<div class="col-md-12">
 
    <div class="x_panel">
        <div class="x_title">
           <h2><?php echo __('Cliente'); ?></h2>
           <ul class="nav navbar-right panel_toolbox"></ul>
       </div>          
        <div class="container-fluid">
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
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-3">
                        <label>Placa</label><br>
                        <?php echo($alertasOrdenes['0']['VH']['placa']); ?>
                    </div>

                    <div class="col-md-3">
                        <label>Modelo</label><br>   
                        <?php echo($alertasOrdenes['0']['VH']['modelo']); ?>                                                     
                    </div>                    

                    <div class="col-md-3">                                      
                        <label>Línea</label><br>  
                        <?php echo($alertasOrdenes['0']['VH']['linea']); ?>                      
                    </div>

                    <div class="col-md-3">                                      
                        <label>Fecha de Vencimiento</label><br>
                        <?php echo($alertasOrdenes['0']['Alertaordene']['fecha_mantenimiento']); ?>                     
                    </div>

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
                                        'class' => 'form-control onlyview',
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
                            name="cant_llamadas" id="cant_llamadas" type="button" class="btn btn-primary onlyview" >
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
                        'class' => 'form-control onlyview', 
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


