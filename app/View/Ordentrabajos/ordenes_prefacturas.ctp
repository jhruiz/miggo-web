<?php $this->layout='inicio'; ?>
<div class="container body">
<div class="main_container">

    <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#prefacturas" aria-controls="prefacturas" data-toggle="tab" role="tab">Prefacturas</a></li>
            <li role="presentation"><a href="#nuevo" aria-controls="nuevo" data-toggle="tab" role="tab">Ordenes de Trabajo</a></li>
        </ul>
    </div>

    <div class="tab-content">
        <!--PREFACTURAS-->
        <div role="tabpanel" class="tab-pane active" id="prefacturas">
            <div class="">
                <div class="container">
                    <table class="table table-hover" cellpadding="0" cellspacing="0" >
                        <thead>
                            <tr>
                                <th><?php echo ('Cliente'); ?></th>
                                <th><?php echo ('Vehículo'); ?></th>
                                <th><?php echo ('Fecha'); ?></th>
                                <th><?php echo ('Estado'); ?></th>
                                <th><?php echo ('Observación'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prefacturas as $prefactura): ?>
                            <tr>
                                <td><?php echo h($prefactura['CL']['nombre']); ?>&nbsp;</td>
                                <td><?php echo h($prefactura['VH']['placa']); ?>&nbsp;</td>
                                <td><?php echo h($prefactura['Prefactura']['created']); ?>&nbsp;</td>
                                <td><?php echo h(!empty($prefactura['Prefactura']['estadoprefactura_id'])  
                                        ? $estados[$prefactura['Prefactura']['estadoprefactura_id']] : ""); ?>&nbsp;</td>
                                <td><?php echo h($prefactura['Prefactura']['observacion']);?>&nbsp;</td>
                                <td class="actions">
                                <?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-eye fa-lg', 'title' => 'Ver Prefactura')), array('controller' => 'prefacturas', 'action' => 'view', $prefactura['Prefactura']['id']), array('escape' => false)) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--Finaliza PREFACTUAS-->                

        <!--Inicia ORDENES DE TRABAJO-->
        <div role="tabpanel" class="tab-pane" id="nuevo"><br>
            <div class="">
                <div class="container">
                    <table class="table table-hover" cellpadding="0" cellspacing="0" >
                        <thead>
                            <tr>
                                <th><?php echo ('Código'); ?></th>
                                <th><?php echo ('Mecánico'); ?></th>
                                <th><?php echo ('Cliente'); ?></th>
                                <th><?php echo ('Vehículo'); ?></th>
                                <th><?php echo ('Estado'); ?></th>
                                <th><?php echo ('Observaciones Mecánico'); ?></th>
                                <th><?php echo ('Observaciones Cliente'); ?></th>
                                <th class="actions"><?php echo __('Acciones'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ordenes as $ot): ?>
                            <tr>
                            <td><?php echo h($ot['Ordentrabajo']['codigo']); ?>&nbsp;</td>
                                <td><?php echo h($ot['US']['nombre']); ?>&nbsp;</td>
                                <td><?php echo h(!empty($ot['CL']['nombre']) ? $ot['CL']['nombre'] : ""); ?>&nbsp;</td>
                                <td><?php echo h(!empty($ot['VH']['placa']) ? $ot['VH']['placa'] : ""); ?>&nbsp;</td>
                                <td><?php echo h(!empty($ot['OE']['descripcion']) ? $ot['OE']['descripcion'] : ""); ?>&nbsp;</td>
                                <td><?php echo h(!empty($ot['Ordentrabajo']['observaciones_usuario']) ? $ot['Ordentrabajo']['observaciones_usuario'] : ""); ?>&nbsp;</td>
                                <td><?php echo h(!empty($ot['Ordentrabajo']['observaciones_cliente']) ? $ot['Ordentrabajo']['observaciones_cliente'] : ""); ?>&nbsp;</td>
                                <td class="actions">
                                <?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-eye fa-lg', 'title' => 'Ver Orden')), array('action' => 'edit', $ot['Ordentrabajo']['id']), array('escape' => false)) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p>                
            </div>
        </div>
        <!--Finaliza ORDENES DE TRABAJO-->            
        
    </div>  
 
</div>
</div>