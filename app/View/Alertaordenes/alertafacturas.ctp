<?php $this->layout = 'inicio';?>
<div class="alertafacturas index">



	<legend><h2><b><?php echo __('Listado de Alertas'); ?></b></h2></legend>
   
       
    <?php echo $this->Form->create('Alertaordenes', array('action' => 'search', 'method' => 'post', 'class' => 'form-inline')); ?>
            <legend><h2><b><?php echo __('Buscar Alertas'); ?></b></h2></legend>

<!-- Inicio zona Tabs Estados alerta-->
<div class="x_panel">
    <div class="x_title">
        <h2><?php echo __('Estado de alertas Facturas , Prefacturas'); ?></h2>
    </div>

    <!-- Enlace tabs-->
    <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"> <?=$this->Html->link(("TODOS"), ['controller' => 'alertaordenes', 'action' => '/alertafacturas' ])?></li>

        <?php foreach ($estadoAlertasTabs as $estadoAlertasTabs): ?>
            <li role="presentation" class="active"> <?=$this->Html->link(($estadoAlertasTabs["Estadoalerta"]["descripcion"]), ['controller' => 'alertaordenes', 'action' => '/alertafacturas/estadoalerta:' . ($estadoAlertasTabs["Estadoalerta"]["id"])])?></li>

            <?php endforeach;?>


       </ul>
    </div>


	<legend><h2><b><?php echo __('Listado de Alertas Facturas'); ?></b></h2></legend>

        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                <th><?php echo ('id'); ?></th>
                                <th>&nbsp;</th>
                               
                                
                                <th><?php echo ('Tipo Alerta'); ?></th>
                                <th><?php echo ('Cliente'); ?></th>
                                <th><?php echo ('Fecha de cumpleaños'); ?></th>
                                <!-- <th><//s?php echo ('Técnico'); ?></th> -->
                                <th><?php echo ('Fecha Alerta'); ?></th>
                                <!-- <th><//?php echo ('Fecha Mantenimiento'); ?></th> -->
                                <th><?php echo ('Estado Alerta'); ?></th>
                                <th><?php echo ('Última llamada'); ?></th>
                                <th><?php echo ('Cantidad llamadas'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($alertasFacturas as $alertOrd): ?>

                <?php

$dateAct = new DateTime($fechaAct);
$dateAlert = new DateTime($alertOrd['Alertaordene']['fecha_alerta']);
$diff = $dateAct->diff($dateAlert);
$days = $diff->invert == 0 ? $diff->days : $diff->days * -1;
$color = $days < 0 ? 'f23c3c' : '00ff44';
?>

                <tr>
                <td><?php echo h($alertOrd['Alertaordene']['id']); ?></td>
                        
                <td>
                    <center>
                        <div style="border-width: 4px; border-radius: 25px; width: 35px;  background: #<?php
echo $color;
?>; "> <div style="color:white ;">  <?php echo $days; ?> </div>
                        </div>
                    </center>
                </td>
                        
                        <td><?php echo h($alertOrd['AL']['descripcion']); ?></td>
                        <td><?php echo h($alertOrd['CL']['nombre']); ?></td>
                        <td><?php echo h($alertOrd['CL']['cumpleanios']); ?></td>
                        <!-- <td><//?php echo h($alertOrd['VH']['placa'] . ' - ' . $alertOrd['VH']['linea']); ?></td> -->
                        <!-- <td><//?php echo h($alertOrd['US']['nombre']); ?></td> -->
                        <td><?php echo h($alertOrd['Alertaordene']['fecha_alerta']); ?></td>
                        <!-- <td><//?php echo h($alertOrd['Alertaordene']['fecha_mantenimiento']); ?></td> -->
                        <td><?php echo h($alertOrd['EA']['descripcion']); ?></td>
                        <td><?php echo h($alertOrd['Alertaordene']['fecha_ultima_llamada']); ?></td>
                        <td><?php echo h($alertOrd['Alertaordene']['cant_llamadas']); ?></td>

                        <td class="actions">
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Gestionar Alerta', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'editfacturas', $alertOrd['Alertaordene']['factura_id']))); ?>
                        </td>
                </tr>
                <?php endforeach;?>
                </table>
            </div>
        </div>
	<legend><h2><b><?php echo __('Listado de Alertas Prefactura'); ?></b></h2></legend>

        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                <th><?php echo ('Id'); ?></th>
                                <th>&nbsp;</th>
                                <th><?php echo ('Tipo Alerta'); ?></th>
                                <th><?php echo ('Cliente'); ?></th>
                                <th><?php echo ('Fecha de cumpleaños'); ?></th>
                                <!-- <th><//s?php echo ('Técnico'); ?></th> -->
                                <th><?php echo ('Fecha Alerta'); ?></th>
                                <!-- <th><//?php echo ('Fecha Mantenimiento'); ?></th> -->
                                <th><?php echo ('Estado Alerta'); ?></th>
                                <th><?php echo ('Última llamada'); ?></th>
                                <th><?php echo ('Cantidad llamadas'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($alertasPreFacturas as $alertPreFac): ?>

                <?php

$dateAct = new DateTime($fechaAct);
$dateAlert = new DateTime($alertPreFac['Alertaordene']['fecha_alerta']);
$diff = $dateAct->diff($dateAlert);
$days = $diff->invert == 0 ? $diff->days : $diff->days * -1;
$color = $days < 0 ? 'f23c3c' : '00ff44';
?>

                <tr>
                <td><?php echo h($alertPreFac['Alertaordene']['id']); ?></td>
                <td>
                    <center>
                        <div style="border-width: 4px; border-radius: 25px; width: 35px;  background: #<?php
echo $color;
?>; "> <div style="color:white ;">  <?php echo $days; ?> </div>
                        </div>
                    </center>
                </td>
                       
                        <td><?php echo h($alertPreFac['AL']['descripcion']); ?></td>
                        <td><?php echo h($alertPreFac['CL']['nombre']); ?></td>
                        <td><?php echo h($alertPreFac['CL']['cumpleanios']); ?></td>
                        <!-- <td><//?php echo h($alertOrd['VH']['placa'] . ' - ' . $alertOrd['VH']['linea']); ?></td> -->
                        <!-- <td><//?php echo h($alertOrd['US']['nombre']); ?></td> -->
                        <td><?php echo h($alertPreFac['Alertaordene']['fecha_alerta']); ?></td>
                        <!-- <td><//?php echo h($alertOrd['Alertaordene']['fecha_mantenimiento']); ?></td> -->
                        <td><?php echo h($alertPreFac['EA']['descripcion']); ?></td>
                        <td><?php echo h($alertPreFac['Alertaordene']['fecha_ultima_llamada']); ?></td>
                        <td><?php echo h($alertPreFac['Alertaordene']['cant_llamadas']); ?></td>

                        <td class="actions">
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Gestionar Alerta', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'editprefacturas',$alertPreFac['Alertaordene']['prefactura_id']))); ?>
                        </td>
                </tr>
                <?php endforeach;?>
                </table>
            </div>
        </div>

	

</div>
<!-- Fin zona Tabs Estados alerta-->


</div>


