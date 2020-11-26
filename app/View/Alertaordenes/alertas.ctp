<?php $this->layout = 'inicio';?>
<div class="alertaordenes index">

    <?php echo $this->Form->create('Alertaordenes', array('action' => 'search', 'method' => 'post', 'class' => 'form-inline')); ?>
    <legend>
        <h2><b><?php echo __('Buscar Alertas'); ?></b></h2>
    </legend>

    <ul class="nav nav-tabs" role="tablist">
        <?php foreach ($estadoAlertasTabs as $estadoAlertasTabs): ?>
        <li role="presentation" class="active">
            <?=$this->Html->link(($estadoAlertasTabs["Estadoalerta"]["descripcion"]), ['controller' => 'alertaordenes', 'action' => '/alertas/estadoalerta:' . ($estadoAlertasTabs["Estadoalerta"]["id"])])?>
        </li>
        <?php endforeach;?>
    </ul>

    <br>
    <a class="btn btn-primary" href="/alertaordenes/gestionalertasgeneral" role="button">Nueva Alerta General</a>
   

    <legend>
        <h2><b><?php echo __('Alertas de tipo Orden de trabajo'); ?>
                <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
                    aria-controls="collapseExample">
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                </a>
            </b></h2>
    </legend>






    <div class="collapse" id="collapseExample">
        <div class="card card-body">


            <div class="table-responsive">
                <div class="container">
                    <table cellpadding="0" cellspacing="0"
                        class="table table-striped table-bordered table-hover table-condensed">
                        <tr>

                       
                            <th>&nbsp;</th>
                            <th><?php echo ('Tipo Alerta'); ?></th>
                            <th><?php echo ('Responsable'); ?></th>
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
$days = $diff->invert == 0 ? $diff->days : $diff->days * -1;
$color = $days < 0 ?  'f23c3c' : '00ff44';
?>

                        <tr>
                       
                            <td>
                                <center>
                                    <div style="border-width: 4px; border-radius: 25px; width: 35px;  background: #<?php
echo $color;
?>; ">
                                        <div style="color:white ;"> <?php echo $days; ?> </div>
                                    </div>
                                </center>
                            </td>
                            <td><?php echo h($alertOrd['AL']['descripcion']); ?></td>
                            <td><?php echo h($alertOrd['US']['nombre']); ?></td>
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
                        <?php endforeach;?>
                    </table>
                </div>
            </div>

        </div>
    </div>





</div>


<!-- Fin zona Tabs Estados alerta-->

<legend>
    <h2><b><?php echo __('Alertas de tipo Factura'); ?>






            <a data-toggle="collapse" href="#AlertaFactura" role="button" aria-expanded="false"
                aria-controls="AlertaFactura">
                <i class="fa fa-sort-desc" aria-hidden="true"></i>
            </a>
        </b></h2>
</legend>
<div class="collapse" id="AlertaFactura">
    <div class="card card-body">
        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0"
                    class="table table-striped table-bordered table-hover table-condensed">
                    <tr>
                       
                        <th>&nbsp;</th>

                        <th><?php echo ('Tipo Alerta'); ?></th>
                        <th><?php echo ('Responsable'); ?></th>
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
                    <?php foreach ($alertasFacturas as $alertFac): ?>

                    <?php

$dateAct = new DateTime($fechaAct);
$dateAlert = new DateTime($alertFac['Alertaordene']['fecha_alerta']);
$diff = $dateAct->diff($dateAlert);
$days = $diff->invert == 0 ? $diff->days : $diff->days * -1;
$color = $days < 0 ?  'f23c3c' : '00ff44';
?>

                    <tr>
                    
                        <td>
                            <center>
                                <div style="border-width: 4px; border-radius: 25px; width: 35px;  background: #<?php
echo $color;
?>; ">
                                    <div style="color:white ;"> <?php echo $days; ?> </div>
                                </div>
                            </center>
                        </td>

                        <td><?php echo h($alertFac['AL']['descripcion']); ?></td>
                        <td><?php echo h($alertFac['US']['nombre']); ?></td>
                        <td><?php echo h($alertFac['CL']['nombre']); ?></td>
                        <td><?php echo h($alertFac['CL']['cumpleanios']); ?></td>
                        <td><?php echo h($alertFac['Alertaordene']['fecha_alerta']); ?></td>
                        <td><?php echo h($alertFac['EA']['descripcion']); ?></td>
                        <td><?php echo h($alertFac['Alertaordene']['fecha_ultima_llamada']); ?></td>
                        <td><?php echo h($alertFac['Alertaordene']['cant_llamadas']); ?></td>

                        <td class="actions">
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Gestionar Alerta', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'editfacturas', $alertFac['Alertaordene']['id']))); ?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </div>
    </div>
</div>


<legend>
    <h2><b><?php echo __('Alertas de tipo Pre Factura'); ?>
            <a data-toggle="collapse" href="#AlertaPreFactura" role="button" aria-expanded="false"
                aria-controls="AlertaPreFactura">
                <i class="fa fa-sort-desc" aria-hidden="true"></i>
            </a>
        </b></h2>
</legend>
<div class="collapse" id="AlertaPreFactura">
    <div class="card card-body">
      
    <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                
                                <th>&nbsp;</th>
                                <th><?php echo ('Tipo Alerta'); ?></th>
                                <th><?php echo ('Responsable'); ?></th>
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
                
                <td>
                    <center>
                        <div style="border-width: 4px; border-radius: 25px; width: 35px;  background: #<?php
echo $color;
?>; "> <div style="color:white ;">  <?php echo $days; ?> </div>
                        </div>
                    </center>
                </td>
                       
                        <td><?php echo h($alertPreFac['AL']['descripcion']); ?></td>
                        <td><?php echo h($alertPreFac['US']['nombre']); ?></td>
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
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Gestionar Alerta', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'editprefacturas',$alertPreFac['Alertaordene']['id']))); ?>
                        </td>
                </tr>
                <?php endforeach;?>
                </table>
            </div>
        </div>
    </div>
</div>


<legend>
    <h2><b><?php echo __('Alertas de tipo General'); ?>
            <a data-toggle="collapse" href="#AlertaGeneral" role="button" aria-expanded="false"
                aria-controls="AlertaPreFactura">
                <i class="fa fa-sort-desc" aria-hidden="true"></i>
            </a>
        </b></h2>
</legend>
<div class="collapse" id="AlertaGeneral">
    <div class="card card-body">
          
    <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                
                                <th>&nbsp;</th>
                                <th><?php echo ('Tipo Alerta'); ?></th>
                                <th><?php echo ('Usuario responsable'); ?></th>
                                <th><?php echo ('Cliente '); ?></th>
                                <th><?php echo ('Fecha de cumpleaños'); ?></th>
                                <!-- <th><//s?php echo ('Técnico'); ?></th> -->
                                <th><?php echo ('Fecha Alerta'); ?></th>
                                <!-- <th><//?php echo ('Fecha Mantenimiento'); ?></th> -->
                                <th><?php echo ('Estado Alerta'); ?></th>
                                <th><?php echo ('Última llamada'); ?></th>
                                <th><?php echo ('Cantidad llamadas'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                </tr>
                <?php foreach ($alertasGeneral as $alertGen): ?>

                <?php

$dateAct = new DateTime($fechaAct);
$dateAlert = new DateTime($alertGen['Alertaordene']['fecha_alerta']);
$diff = $dateAct->diff($dateAlert);
$days = $diff->invert == 0 ? $diff->days : $diff->days * -1;
$color = $days < 0 ? 'f23c3c' : '00ff44';
?>

                <tr>
               
                <td>
                    <center>
                        <div style="border-width: 4px; border-radius: 25px; width: 35px;  background: #<?php
echo $color;
?>; "> <div style="color:white ;">  <?php echo $days; ?> </div>
                        </div>
                    </center>
                </td>
                       
                        <td><?php echo h($alertGen['AL']['descripcion']); ?></td>
                        <!-- <td><//?php echo h($alertOrd['VH']['placa'] . ' - ' . $alertOrd['VH']['linea']); ?></td> -->
                        <!-- <td><//?php echo h($alertOrd['US']['nombre']); ?></td> -->
                        <td><?php echo h($alertGen['US']['nombre']); ?></td>
                        <td><?php echo h($alertGen['CL']['nombre']); ?></td>
                        <td><?php echo h($alertGen['CL']['cumpleanios']); ?></td>
                        <td><?php echo h($alertGen['Alertaordene']['fecha_alerta']); ?></td>
                        <!-- <td><//?php echo h($alertOrd['Alertaordene']['fecha_mantenimiento']); ?></td> -->
                        <td><?php echo h($alertGen['EA']['descripcion']); ?></td>
                        <td><?php echo h($alertGen['Alertaordene']['fecha_ultima_llamada']); ?></td>
                        <td><?php echo h($alertGen['Alertaordene']['cant_llamadas']); ?></td>

                        <td class="actions">
                            <?php echo $this->Html->image('png/list-12.png', array('title' => 'Gestionar Alerta', 'alt' => __('Brownies'), 'width' => '20px', 'url' => array('action' => 'editgeneral',$alertGen['Alertaordene']['id']))); ?>
                        </td>
                </tr>
                <?php endforeach;?>
                </table>
            </div>
        </div>
    </div>
</div>




</div>