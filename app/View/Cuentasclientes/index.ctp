<?php echo ($this->Html->script('cuentasclientes/cuentasclientes.js')); ?>
<?php $this->layout='inicio'; ?>
<div class="cuentasclientes index">   
        <legend><h2><b><?php echo __('Cuentas por Cobrar'); ?></b></h2></legend> 
	<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '35', 'id' => 'menuvert'))?>    
        <div class="table-responsive">
            <div class="container-fluid">         
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                                <th><?php echo $this->Paginator->sort('documento_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('cliente_id'); ?></th>                                                                
                                <th><?php echo $this->Paginator->sort('totalobligacion', 'Total Obligación'); ?></th>
                                <th><?php echo $this->Paginator->sort('factura_id', '# Factura'); ?></th>
                                <th><?php echo $this->Paginator->sort('created', 'Fecha Factura'); ?></th>                                
                                <th><?php echo $this->Paginator->sort('Días Crédito'); ?></th>
                                <th><?php echo $this->Paginator->sort('Fecha Limite'); ?></th>
                                <th><?php echo $this->Paginator->sort('Días Vencido'); ?></th>
                                <th><?php echo $this->Paginator->sort('usuario_id'); ?></th>  
                                <th>&nbsp;</th>
                </tr>
                <?php foreach ($cuentasclientes as $cuentascliente): ?>
                
                <tr class="<?php echo $cuentascliente['Cuentascliente']['color'];?>">
                        <td>
                                <?php echo $this->Html->link($cuentascliente['DC']['codigo'], array('controller' => 'documentos', 'action' => 'view', $cuentascliente['DC']['id'])); ?>
                        </td>
                        <td>
                                <?php echo $this->Html->link($cuentascliente['CL']['nombre'], array('controller' => 'clientes', 'action' => 'view', $cuentascliente['CL']['id'])); ?>
                        </td>                        
                        <td class="<?php echo $cuentascliente['Cuentascliente']['limitecredito']; ?>"><?php echo h("$" . number_format($cuentascliente['Cuentascliente']['totalobligacion'],2)); ?>&nbsp;</td>
                        <td>
                            <?php if ($cuentascliente['F']['consecutivodian'] != ""){?>
                                <?php echo $this->Html->link($cuentascliente['F']['consecutivodian'], array('controller' => 'facturas', 'action' => 'view', $cuentascliente['F']['id'])); ?>
                            <?php } else {?>
                                <?php echo $this->Html->link($cuentascliente['F']['codigo'], array('controller' => 'facturas', 'action' => 'view', $cuentascliente['F']['id'])); ?>
                            <?php } ?>
                        </td>
                        <td><?php echo h($cuentascliente['Cuentascliente']['created']); ?>&nbsp;</td>
                        <td><?php echo h(!empty($cuentascliente['Cliente']['diascredito']) ? $cuentascliente['CL']['diascredito'] : "30"); ?>&nbsp;</td>
                        <td><?php echo h($cuentascliente['Cuentascliente']['fechalimitepago']);?>&nbsp;</td>
                        <td><?php echo h($cuentascliente['Cuentascliente']['diasvencido']);?>&nbsp;</td>
                        <td>
                                <?php echo $this->Html->link($cuentascliente['U']['nombre'], array('controller' => 'usuarios', 'action' => 'view', $cuentascliente['U']['id'])); ?>
                        </td>    
                        <td>
                            <button id="pagarCuenta" class="btn btn-primary" onclick="pagarCuenta('<?php echo $cuentascliente['Cuentascliente']['id']?>');">Pagar</button>
                            <button id="eliminarCuenta" class="btn btn-primary" onclick="eliminarCuenta('<?php echo $cuentascliente['Cuentascliente']['id']?>');">Eliminar</button>
                            <button id="verAbonos" class="btn btn-primary" onclick="verAbonos('<?php echo $cuentascliente['Cuentascliente']['id']?>');">Ver Abonos</button>
                        </td>
                </tr>
                <?php endforeach; ?>
                </table>
        <legend>&nbsp;</legend>
                
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                &nbsp;
            </div>              
            <div class="col-md-2">
                <dl>                    
                    <dt class="text-left text-success"><?php echo h("Saldo Vigente: ");?></dt>
                    <dt class="text-left text-danger"><?php echo h("Saldo Vencido: ");?></dt>
                    <dt class="text-left text-info"><?php echo h("Saldo Total: ");?></dt>
                </dl>
            </div>         
            <div class="col-md-2">                
                <dl>
                    <dt class="text-right text-success"><?php echo h("$" . number_format($costoVigente,2))?></dt>
                    <dt class="text-right text-danger"><?php echo h("$" . number_format($costoVencido,2))?></dt>
                    <dt class="text-right text-info"><?php echo h("$" . number_format($costoTotal,2))?></dt>
                </dl>
            </div>        
        </div>
    </div> 
</div></div><br><br>
<?php echo $this->Form->create('Reporte',array( 'controller' => 'reportes','action'=>'descargarCuentasClientes')); ?>
    <fieldset>    
        <?php echo $this->Form->submit('Descargar',array('class'=>'btn btn-primary')); ?>
    </fieldset>
</form><br><br>
<div id="div_pagarcuenta"></div>