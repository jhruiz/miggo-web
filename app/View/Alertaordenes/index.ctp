<?php $this->layout='inicio'; ?>
<div class="alertaordenes index">
    
            <?php echo $this->Form->create('Alertaordenes',array('action'=>'search','method'=>'post', 'class' => 'form-inline'));?>
            <legend><h2><b><?php echo __('Buscar Alertas'); ?></b></h2></legend> 
            
            <div class="form-group">
                <label for="Estadoaleras">Estados de alertas</label>
                <?php echo $this->Form->input('estadoalerta', array('label' => '', 'name' => 'estadoalerta', 'empty' => 'Seleccione una', 'type' => 'select', 'options' => $estadoAlertas, 'class' => 'form-control'));?>
            </div><br><br>                

            <div class="form-group">  
            <?php echo $this->Form->submit('Buscar',array('class'=>'btn btn-primary'));?>                
            </div>                    

        </form><br>      
    
	<legend><h2><b><?php echo __('Listado de Alertas'); ?></b></h2></legend>

        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>			
                                <th>&nbsp;</th>
                                <th><?php echo ('Tipo Alerta'); ?></th>
                                <th><?php echo ('Cliente'); ?></th>
                                <th><?php echo ('Vehículo'); ?></th>
                                <th><?php echo ('Técnico'); ?></th>
                                <th><?php echo ('Fecha Alerta'); ?></th>
                                <th><?php echo ('Fecha Mantenimiento'); ?></th>
                                <th><?php echo ('Estado Alerta'); ?></th>
                                <th><?php echo ('Última llamada'); ?></th>
                                <th><?php echo ('Cantidad llamadas'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($alertasOrdenes as $alertOrd): ?>
                
                <?php 
                    
                    $dateAct = new DateTime($fechaAct);
                    $dateAlert = new DateTime($alertOrd['Alertaordene']['fecha_alerta']);
                    $diff = $dateAct->diff($dateAlert);
                    $days = $diff->invert == 0 ? $diff->days : $diff->days*-1;
                    $color = $days < 0 ? 'ff0000' : '00ff44';          
                ?>
                
                <tr>
                <td>
                    <center>
                        <div style="border-width: 4px; border-radius: 25px; width: 35px;  background: #<?php 
                                echo $color;                                       
                        ?>; "><?php echo $days; ?>
                        </div>
                    </center>
                </td>                 
                        <td><?php echo h($alertOrd['AL']['descripcion']); ?></td>
                        <td><?php echo h($alertOrd['CL']['nombre']); ?></td>
                        <td><?php echo h($alertOrd['VH']['placa'] . ' - ' . $alertOrd['VH']['linea']); ?></td>
                        <td><?php echo h($alertOrd['US']['nombre']); ?></td>
                        <td><?php echo h($alertOrd['Alertaordene']['fecha_alerta']); ?></td>
                        <td><?php echo h($alertOrd['Alertaordene']['fecha_mantenimiento']); ?></td>
                        <td><?php echo h($alertOrd['EA']['descripcion']); ?></td>
                        <td><?php echo h($alertOrd['Alertaordene']['fecha_ultima_llamada']); ?></td>
                        <td><?php echo h($alertOrd['Alertaordene']['cant_llamadas']); ?></td>

                        <td class="actions">                            
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Gestionar Alerta', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'edit', $alertOrd['Alertaordene']['id']))); ?>
                        </td>
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>

	<legend><h2><b><?php echo __('Listado de Alertas - Renovación de Documentos'); ?></b></h2></legend>

        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>			
                    <th>&nbsp;</th>
                    <th><?php echo ('Tipo Alerta'); ?></th>
                    <th><?php echo ('Cliente'); ?></th>
                    <th><?php echo ('Vehículo'); ?></th>
                    <th><?php echo ('Fecha Alerta'); ?></th>
                    <th><?php echo ('Fecha Renovación'); ?></th>
                    <th><?php echo ('Estado Alerta'); ?></th>
                    <th><?php echo ('Última llamada'); ?></th>
                    <th><?php echo ('Cantidad llamadas'); ?></th>
                    <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($alertaDocumentos as $alertDocs): ?>

                <?php 
                    
                    $dateAct = new DateTime($fechaAct);
                    $dateAlert = new DateTime($alertDocs['Alertaordene']['fecha_alerta']);
                    $diff = $dateAct->diff($dateAlert);
                    $days = $diff->invert == 0 ? $diff->days : $diff->days*-1;
                    $color = $days < 0 ? 'ff0000' : '00ff44';          
                ?>                
                <tr>
                    <td>
                        <center>
                            <div style="border-width: 4px; border-radius: 25px; width: 35px;  background: #<?php 
                                    echo $color;                                       
                            ?>; "><?php echo $days; ?>
                            </div>
                        </center>
                    </td>                      
                    <td><?php echo h($alertDocs['AL']['descripcion']); ?></td>
                    <td><?php echo h($alertDocs['CL']['nombre']); ?></td>
                    <td><?php echo h($alertDocs['VH']['placa'] . ' - ' . $alertDocs['VH']['linea']); ?></td>
                    <td><?php echo h($alertDocs['Alertaordene']['fecha_alerta']); ?></td>
                    <td><?php echo h($alertDocs['Alertaordene']['fecha_mantenimiento']); ?></td>
                    <td><?php echo h($alertDocs['EA']['descripcion']); ?></td>
                    <td><?php echo h($alertDocs['Alertaordene']['fecha_ultima_llamada']); ?></td>
                    <td><?php echo h($alertDocs['Alertaordene']['cant_llamadas']); ?></td>

                    <td class="actions">                            
                        <?php echo $this->Html->image('png/list-12.png', array('title' => 'Gestionar Alerta', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'editdocs', $alertDocs['Alertaordene']['id']))); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
</div>


