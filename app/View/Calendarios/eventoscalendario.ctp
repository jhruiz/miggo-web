<?php 
$this->layout=false;
?>

    <!-- LISTADO DE ALERTAS POR MANTENIMIETO -->
    <?php if(!empty($alertasM)){ ?>
    <legend><h2><b><?php echo $this->Html->link(__('Listado de Alertas Mantenimiento'), array('controller' => 'alertaordenes', 'action' => 'index')); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>			
                                <th><?php echo ('Tipo Alerta'); ?></th>
                                <th><?php echo ('Cliente'); ?></th>
                                <th><?php echo ('Vehículo'); ?></th>
                                <th><?php echo ('Técnico'); ?></th>
                                <th><?php echo ('Fecha Alerta'); ?></th>
                                <th><?php echo ('Fecha Mantenimiento'); ?></th>
                                <th><?php echo ('Estado Alerta'); ?></th>
                                <th><?php echo ('Última llamada'); ?></th>
                                <th><?php echo ('Cantidad llamadas'); ?></th>
                </tr>
                <?php foreach ($alertasM as $alertOrd): ?>            
                <tr>                
                        <td><?php echo h($alertOrd['AL']['descripcion']); ?></td>
                        <td><?php echo h($alertOrd['CLI']['nombre']); ?></td>
                        <td><?php echo h($alertOrd['VEHI']['placa'] . ' - ' . $alertOrd['VEHI']['linea']); ?></td>
                        <td><?php echo h($alertOrd['US']['nombre']); ?></td>
                        <td><?php echo h($alertOrd['Alertaordene']['fecha_alerta']); ?></td>
                        <td><?php echo h($alertOrd['Alertaordene']['fecha_mantenimiento']); ?></td>
                        <td><?php echo h($alertOrd['EA']['descripcion']); ?></td>
                        <td><?php echo h($alertOrd['Alertaordene']['fecha_ultima_llamada']); ?></td>
                        <td><?php echo h($alertOrd['Alertaordene']['cant_llamadas']); ?></td>
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
        <?php } ?>



    <!-- LISTADO DE ALERTAS POR DOCUMENTOS -->
    <?php if(!empty($alertasD)){ ?>    
    <legend><h2><b><?php echo $this->Html->link(__('Listado de Alertas Documentos'), array('controller' => 'alertaordenes', 'action' => 'index')); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>			
                                <th><?php echo ('Tipo Alerta'); ?></th>
                                <th><?php echo ('Cliente'); ?></th>
                                <th><?php echo ('Vehículo'); ?></th>
                                <th><?php echo ('Fecha Alerta'); ?></th>
                                <th><?php echo ('Fecha Mantenimiento'); ?></th>
                                <th><?php echo ('Estado Alerta'); ?></th>
                                <th><?php echo ('Última llamada'); ?></th>
                                <th><?php echo ('Cantidad llamadas'); ?></th>
                </tr>
                <?php foreach ($alertasD as $alertOrd): ?>                
                <tr>              
                        <td><?php echo h($alertOrd['AL']['descripcion']); ?></td>
                        <td><?php echo h($alertOrd['CL']['nombre']); ?></td>
                        <td><?php echo h($alertOrd['VH']['placa'] . ' - ' . $alertOrd['VH']['linea']); ?></td>
                        <td><?php echo h($alertOrd['Alertaordene']['fecha_alerta']); ?></td>
                        <td><?php echo h($alertOrd['Alertaordene']['fecha_mantenimiento']); ?></td>
                        <td><?php echo h($alertOrd['EA']['descripcion']); ?></td>
                        <td><?php echo h($alertOrd['Alertaordene']['fecha_ultima_llamada']); ?></td>
                        <td><?php echo h($alertOrd['Alertaordene']['cant_llamadas']); ?></td>
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
        <?php } ?>   
        
        
        <!-- LISTADO DE ALERTAS DE CUENTAS POR COBRAR -->
        <?php if(!empty($cuentasXCobrar)){ ?>  
            <legend><h2><b><?php echo $this->Html->link(__('Listado de Cuentas por Cobrar'), array('controller' => 'cuentasclientes', 'action' => 'index')); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>			
                                <th><?php echo ('Documento'); ?></th>
                                <th><?php echo ('Cliente'); ?></th>
                                <th><?php echo ('Total Obligación'); ?></th>
                                <th><?php echo ('# Factura'); ?></th>
                                <th><?php echo ('Fecha Factura'); ?></th>
                                <th><?php echo ('Días Crédito'); ?></th>
                                <th><?php echo ('Fecha Límite'); ?></th>
                                <th><?php echo ('Usuario'); ?></th>
                </tr>
                <?php foreach ($cuentasXCobrar as $ctas): ?>                
                <tr>              
                        <td><?php echo h($ctas['DC']['codigo']); ?></td>
                        <td><?php echo h($ctas['CL']['nombre']); ?></td>
                        <td><?php echo h(number_format($ctas['Cuentascliente']['totalobligacion'],'0')); ?></td>
                        <td><?php echo h(!empty($ctas['F']['consecutivodian']) ? $ctas['F']['consecutivodian'] : $ctas['F']['codigo']); ?></td>
                        <td><?php echo h($ctas['Cuentascliente']['created']); ?></td>
                        <td><?php echo h(!empty($ctas['CL']['diascredito']) ? $ctas['CL']['diascredito'] : '30'); ?></td>
                        <td><?php echo h($ctas['Cuentascliente']['fechapago']); ?></td>
                        <td><?php echo h($ctas['U']['nombre']); ?></td>
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>            
        <?php } ?>
        

        <!-- LISTADO DE ALERTAS DE CUENTAS POR PAGAR -->
        <?php if(!empty($cuentasXPagar)){ ?>  
            <legend><h2><b><?php echo $this->Html->link(__('Listado de Cuentas por Pagar'), array('controller' => 'cuentaspendientes', 'action' => 'index')); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>			
                                <th><?php echo ('Documento'); ?></th>
                                <th><?php echo ('Producto'); ?></th>
                                <th><?php echo ('Total Obligación'); ?></th>
                                <th><?php echo ('Proveedor'); ?></th>
                                <th><?php echo ('# Factura'); ?></th>
                                <th><?php echo ('Días Crédito'); ?></th>
                                <th><?php echo ('Usuario'); ?></th>
                </tr>
                <?php foreach ($cuentasXPagar as $ctas): ?>                
                <tr>              
                        <td><?php echo h($ctas['DC']['codigo']); ?></td>
                        <td><?php echo h($ctas['P']['descripcion']); ?></td>
                        <td><?php echo h(number_format($ctas['Cuentaspendiente']['totalobligacion'],'0')); ?></td>
                        <td><?php echo h($ctas['PV']['nombre']); ?></td>
                        <td><?php echo h($ctas['Cuentaspendiente']['numerofactura']); ?></td>
                        <td><?php echo h(!empty($ctas['PV']['diascredito']) ? $ctas['PV']['diascredito'] : '30'); ?></td>
                        <td><?php echo h($ctas['U']['nombre']); ?></td>
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>            
        <?php } ?>
        
        
        <!-- LISTADO DE EVENTOS CALENDARIO -->
        <?php if(!empty($eventos)){ ?>  
            <legend><h2><b><?php echo $this->Html->link(__('Listado de Eventos'), array('controller' => 'eventos ', 'action' => 'index')); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>			
                                <th><?php echo ('Tipo de Evento'); ?></th>
                                <th><?php echo ('Responsable'); ?></th>
                                <th><?php echo ('Fecha Evento'); ?></th>
                                <th><?php echo ('Cliente'); ?></th>
                                <th><?php echo ('Estado'); ?></th>
                                <th><?php echo ('Teléfono'); ?></th>
                                <th><?php echo ('Placa'); ?></th>                                
                                <th><?php echo ('Descripción'); ?></th>
                </tr>
                <?php foreach ($eventos as $event): ?>                
                <tr>              
                        <td><?php echo h($event['TE']['descripcion']); ?></td>
                        <td><?php echo h($event['U']['nombre']); ?></td>
                        <td><?php echo h($event['Evento']['fecha']); ?></td>
                        <td><?php echo h($event['Evento']['cliente']); ?></td>
                        <td><?php echo h($event['EA']['descripcion']); ?></td>
                        <td><?php echo h($event['Evento']['telefono']); ?></td>
                        <td><?php echo h($event['Evento']['placa']); ?></td>
                        <td><?php echo h($event['Evento']['descripcion']); ?></td>
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>            
        <?php } ?>