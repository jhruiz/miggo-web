<style>
.disabled{
pointer-events: none;
cursor: default;

}
 </style>
<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('alertaordenes/alertaordenes'));?>
<?php echo ($this->Html->script('alertaordenes/gestionalertasfac'));?>
<div class="ordentrabajos form">
<?php echo $this->Form->create('Ordentrabajo', array('type' => 'post', 'class' => 'form-inline')); ?>
<fieldset>                    
<?php echo $this->Form->input('empresa', array('type' => 'hidden', 'value' => $empresa_id, 'id' => 'empresaId'));?>
<?php echo $this->Form->input('ordentrabajo', array('type' => 'hidden', 'value' => $ordenTrabajoId, 'id' => 'ordenTId'));?>
<?php echo $this->Form->input('vehiculo_id', array('type' => 'hidden', 'value' => $infoFacturaCli['0']['VH']['id'], 'id' => 'vehiculoId'));?>
<?php echo $this->Form->input('cliente_id', array('type' => 'hidden', 'class' => 'form-control', 'value' => $infoFacturaCli['0']['CL']['id'], 'id' => 'clienteId'));?>
<?php echo $this->Form->input('clientecumpleanios', array('type' => 'hidden', 'value' => $infoFacturaCli['0']['CL']['cumpleanios'], 'id' => 'clientecumpleanios'));?>
<?php echo $this->Form->input('factura_id', array('type' => 'hidden', 'class' => 'form-control','value' => $id_factura, 'id' => 'facturaId'));?>
<?php echo $this->Form->input('factura_idadd', array('type' => 'hidden', 'class' => 'form-control','value' => $id_factura, 'id' => 'factura_idadd'));?>
<?php echo $this->Form->input('cliente_idadd', array('type' => 'hidden', 'class' => 'form-control', 'value' => $infoFacturaCli['0']['CL']['id'], 'id' => 'cliente_idadd'));?>

<?php  
$fechaActual =  date('Y-m-d'); 
$yfechaalertamasuno =  date('Y',strtotime($fechaActual ." + 1 year"));
$yfechaActual =  date('Y');
$mdfechaactual= date('m-d',strtotime($fechaActual));
$fechaCumple = $infoFacturaCli['0']['CL']['cumpleanios']; 
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
<?php echo $this->Form->input('fecha_cumple', array('type' => ' hidden', 'value' => $fechaAlerta, 'id' => 'fechacumple'));?>

<div  class="col-md-12">
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
                    <?php echo($infoFacturaCli['0']['CL']['nombre']); ?>
                </div>

                <div class="col-md-2">
                    <label>Nit</label><br>   
                    <?php echo($infoFacturaCli['0']['CL']['nit']); ?>                                                     
                </div>                    

                <div class="col-md-2">                                      
                    <label>Teléfono</label><br>  
                    <?php echo($infoFacturaCli['0']['CL']['celular']); ?>                      
                </div>

                <div class="col-md-2">                                      
                    <label>Dirección</label><br>
                    <?php echo($infoFacturaCli['0']['CL']['direccion']); ?>                        
                </div>
                <div class="col-md-2">                                      
                    <label>Cumpleaños</label><br>
                    <a class="<?php echo $classBtn ?>" href="#"  title="Crear alerta por cumpleaños" class="btn btn-default btn-sm" id="alerta_cumple"><span class="far fa-eye"></span></a>                     
                    <?php echo($infoFacturaCli['0']['CL']['cumpleanios']); ?>  
                                      
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
                            <label>Fecha de alerta</label><br>
                            <input class="date form-control" placeholder="Fecha de Alerta" type="text" id="fecha_alerta">
                        </div>

 <div class="col-md-3">
                        <label>Responsable</label><br>
                        <?php 
                            echo $this->Form->input("usuario_id",
                                    array(
                                        'name'=>"usuario_id",
                                        'id'=>"usuarioId",
                                        'label' => "",
                                        'type' => 'select',
                                        'options'=>$usuarios,
                                        'empty'=>'Seleccione Una',
                                        'class' => 'form-control'
                                    )
                            );
                        ?>
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


