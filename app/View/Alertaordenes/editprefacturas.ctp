<style>
.disabled{
pointer-events: none;
cursor: default;

}
 </style>
<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('alertaordenes/editprefacturas'));?>
<div class="ordentrabajos form">
<?php echo $this->Form->create('Ordentrabajo', array('type' => 'post', 'class' => 'form-inline')); ?>
<fieldset>                    
<?php echo $this->Form->input('km_actual', array('type' => 'hidden', 'value' => $alertasPreFacturas['0']['O']['kilometraje'], 'id' => 'km_actual'));?>
<?php echo $this->Form->input('alerta_id', array('type' => 'hidden', 'value' => $alertasPreFacturas['0']['Alertaordene']['id'], 'id' => 'alerta_id'));?>
<?php echo $this->Form->input('vehiculo_id', array('type' => 'hidden', 'value' => $alertasPreFacturas['0']['VH']['id'], 'id' => 'vehiculoId'));?>
<?php echo $this->Form->input('usuario_id', array('type' => 'hidden', 'value' => $alertasPreFacturas['0']['Alertaordene']['usuario_id'], 'id' => 'usuarioId'));?>

<?php echo $this->Form->input('prefactura_id', array('type' => 'hidden', 'value' => $alertasPreFacturas['0']['Alertaordene']['prefactura_id'], 'id' => 'prefacturaId'));?>
<?php echo $this->Form->input('cliente_id', array('type' => 'hidden', 'value' => $alertasPreFacturas['0']['CL']['id'], 'id' => 'clienteId'));?>
<?php echo $this->Form->input('soat', array('type' => 'hidden', 'value' => $alertasPreFacturas['0']['O']['soat'], 'id' => 'soat'));?>
<?php echo $this->Form->input('tecno', array('type' => 'hidden', 'value' => $alertasPreFacturas['0']['O']['tecnomecanica'], 'id' => 'tecnomecanica'));?>


<?php  
$fechaActual =  date('Y-m-d'); 
$yfechaalertamasuno =  date('Y',strtotime($fechaActual ." + 1 year"));
$yfechaActual =  date('Y');
$mdfechaactual= date('m-d',strtotime($fechaActual));
$fechaCumple = $alertasPreFacturas['0']['CL']['cumpleanios']; 
$mdfechaalerta = date('m-d',strtotime($fechaCumple));

    if ($mdfechaactual > $mdfechaalerta){
        $fechaAlerta = $yfechaalertamasuno . "-" . $mdfechaalerta ;
       
    }
    else if ($mdfechaactual < $mdfechaalerta){
        $fechaAlerta = $yfechaActual . "-" . $mdfechaalerta ;
      
    }

    if ($fechaCumple == NULL){
    $classBtn= "disabled";
    ;
    }
?>

<!-- <h1 >hola</h1> -->
<?php echo $this->Form->input('fecha_cumple', array('type' => ' hidden', 'value' => $fechaAlerta, 'id' => 'fechacumple'));?>
<div class="col-md-12">
<br>
    <div class="x_panel">
   
        <div class="x_title">
       
           <h2><?php echo __('Cliente'); ?></h2>
           <ul class="nav navbar-right panel_toolbox"></ul>
       </div>          
        <div class="container-fluid" style="margin-bottom: 10px;">
            <div class="row">

                <div class="col-md-2">
                    <label>Nombre</label><br>
                    <?php echo($alertasPreFacturas['0']['CL']['nombre']); ?>
                </div>

                <div class="col-md-2">
                    <label>Nit</label><br>   
                    <?php echo($alertasPreFacturas['0']['CL']['nit']); ?>                                                     
                </div>                    

                <div class="col-md-2">                                      
                    <label>Teléfono</label><br>  
                    <?php echo($alertasPreFacturas['0']['CL']['celular']); ?>                      
                </div>

                <div class="col-md-2">                                      
                    <label>Dirección</label><br>
                    <?php echo($alertasPreFacturas['0']['CL']['direccion']); ?>                        
                </div>
                <div class="col-md-2">                                      
                    <label>Cumpleaños</label><br>
                    <a href="#" class="<?php echo $classBtn ?>" title="Crear alerta por cumpleaños" class="btn btn-default btn-sm" id="alerta_cumple"><span class="far fa-eye"></span></a>                     
                    <!-- <a href="#" title="Crear alerta por cumpleaños" class="btn btn-default btn-sm" id="alerta_soat"><span class="far fa-eye"></span></a>                      -->
                    <!-- <a href="#" title="Test" class="btn btn-default btn-sm" id="alerta_soat"><span class="far fa-eye"></span></a>                      -->
                    <?php echo($alertasPreFacturas['0']['CL']['cumpleanios']); ?>  
                                      
                </div>
                <div class="col-md-2">                                      
                                   
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
                        <?php echo($alertasPreFacturas['0']['AL']['descripcion']); ?>
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
                                        'selected' => $alertasPreFacturas['0']['EA']['id']
                                    )
                            );
                        ?>                                                                                                     
                    </div>                    

                    <div class="col-md-3">                                      
                        <label>Unidad de medida</label><br>  
                        <?php echo $alertasPreFacturas['0']['UM']['descripcion']; ?>                          
                    </div>

                    <div class="col-md-3">                                      
                        <label>Fecha creación</label><br>
                        <?php echo $alertasPreFacturas['0']['Alertaordene']['created']; ?>                        
                    </div>

                </div>
      
        </div>   

    </div><!-- Termina COL -->    


    <div class="x_panel">
        <div class="x_title">
           <h2><?php echo __('Gestión por ') . $alertasPreFacturas['0']['UM']['descripcion']?></id></h2>
           <ul class="nav navbar-right panel_toolbox"></ul>
       </div>          
            <div class="container-fluid" style="margin-bottom: 10px;">
                <div class="row">

                        <!-- <div class="col-md-3">
                            <label>Fecha próximo mantenimiento</label><br>   
                            <//?php echo $alertasOrdenes['0']['Alertaordene']['fecha_mantenimiento'];?>
                        </div>                     -->
                        
                        <div class="col-md-3">
                            <label>Fecha inicio de alerta</label><br>
                            <?php echo $alertasPreFacturas['0']['Alertaordene']['fecha_alerta'];?>
                        </div>
                        
                        <div class="col-md-3">
                            <label>Fecha última llamada</label><br>
                            <div id="ult_llamada">
                                <?php echo $alertasPreFacturas['0']['Alertaordene']['fecha_ultima_llamada'];?>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                        <label>Cantidad llamadas</label><br>
                        <button                         
                            name="cant_llamadas" id="cant_llamadas" type="button" class="btn btn-primary" >
                                <div id="cant_llamadas_text">
                                    <?php echo !empty($alertasPreFacturas['0']['Alertaordene']['cant_llamadas']) ?
                                   $alertasPreFacturas['0']['Alertaordene']['cant_llamadas'] : 0  ?>                            
                                </div>
                            </button>
                        </div>
                        <div class="col-md-3">
                            <label>Responsable</label><br>
                            <div id="ult_llamada">
                                <?php echo $alertasPreFacturas['0']['US']['nombre'];?>
                            </div>
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
                        'value' => $alertasPreFacturas['0']['Alertaordene']['observaciones']
                        )); 
                    ?>
                </div>
            </div>
        </div>
    </div><!--TEMRINA PANEL-->              
            
	</fieldset>
    </form>
</div>  


