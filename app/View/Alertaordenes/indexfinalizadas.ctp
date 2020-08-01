<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('alertaordenes/finalizadas.js'));?>

<div id="graficos" style="magin:20px;" class='col-md-12'></div>

<div class="alertaordenes indexfinalizadas container-fluid">
	<legend><h2><b><?php echo __('Listado de Alertas'); ?></b></h2></legend>

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
                    <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($alertasOrdenes as $alertOrd): ?>
                
                <tr>                
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
                        <?php echo $this->Html->image('png/list-10.png', array('title' => 'Ver Alerta', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'viewf', $alertOrd['Alertaordene']['id']))); ?>
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
                <tr>                    
                    <td><?php echo h($alertDocs['AL']['descripcion']); ?></td>
                    <td><?php echo h($alertDocs['CL']['nombre']); ?></td>
                    <td><?php echo h($alertDocs['VH']['placa'] . ' - ' . $alertDocs['VH']['linea']); ?></td>
                    <td><?php echo h($alertDocs['Alertaordene']['fecha_alerta']); ?></td>
                    <td><?php echo h($alertDocs['Alertaordene']['fecha_mantenimiento']); ?></td>
                    <td><?php echo h($alertDocs['EA']['descripcion']); ?></td>
                    <td><?php echo h($alertDocs['Alertaordene']['fecha_ultima_llamada']); ?></td>
                    <td><?php echo h($alertDocs['Alertaordene']['cant_llamadas']); ?></td>

                    <td class="actions">                            
                        <?php echo $this->Html->image('png/list-10.png', array('title' => 'Gestionar Alerta', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'viewfd', $alertDocs['Alertaordene']['id']))); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
</div>


