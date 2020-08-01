<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('bandeja/gestionBandejas.js'));?>
<div class="utilidades index">
    <?php echo $this->Form->create('Utilidades',array('action'=>'search','method'=>'post', 'class' => 'form-inline'));?>
    <legend><h2><b><?php echo __('Buscar Fecha de Utilidad por Ventas'); ?></b></h2></legend> 
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '34', 'id' => 'menuvert'))?>    
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">  
                <label>Fecha Inicial</label><br>                          
                <input name="data[Utilidade][fechaInicio]" id="fechaInicio" autocomplete="off" class="date form-control" placeholder="Fecha Inicio" type="text">                             
            </div>             
        </div>       
        <div class="col-md-3">
            <div class="form-group">
                <label>Fecha Final</label><br>                          
                <input name="data[Utilidade][fechaFin]" id="fechaFin" autocomplete="off" class="date form-control" placeholder="Fecha Fin" type="text">               
            </div>        
        </div>
        <div class="col-md-3">
            &nbsp; 
        </div>                      
        <div class="col-md-3">
            &nbsp;
        </div>                      
    </div><br>              
    <?php echo $this->Form->submit('Buscar',array('class'=>'btn btn-primary'));?>
    </form><br><br>  
    
	<legend><h2><b><?php echo __('Utilidades por Ventas. ' . $fechaInicio . ' - ' . $fechaFin); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">                
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                <tr>
                    <th><?php echo h('Producto'); ?></th>
                    <th><?php echo h('Deposito'); ?></th>
                    <th><?php echo h('Proveedor'); ?></th>
                    <th><?php echo h('Costo del Producto'); ?></th>
                    <th><?php echo h('Costo Total'); ?></th>
                    <th><?php echo h('cantidad'); ?></th>
                    <th><?php echo h('Precio de Venta'); ?></th>                    
                    <th><?php echo h('Total Venta'); ?></th>                    
                    <th><?php echo h('Utilidad Bruta'); ?></th>
                    <th><?php echo h('Utilidad Porcentual'); ?></th>
                    <th><?php echo h('Tipo Utilidad'); ?></th>
                    <th><?php echo h('Fecha'); ?></th>
                    <th><?php echo h('Factura'); ?></th>
                </tr>
                <?php foreach ($utilidades as $utilidade): ?>
                <tr>
                    <td><?php echo h($utilidade['P']['descripcion']); ?>&nbsp;</td>
                    <td><?php echo h($utilidade['DP']['descripcion']); ?>&nbsp;</td>
                    <td><?php echo h(!empty($utilidade['PV']['nombre']) ? $utilidade['PV']['nombre'] : "Sin Proveedor"); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format(intval($utilidade['Utilidade']['costo_producto']),2)); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format(intval($utilidade['Utilidade']['costo_producto'] * $utilidade['Utilidade']['cantidad']),2)); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h($utilidade['Utilidade']['cantidad']); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format(intval($utilidade['Utilidade']['precioventa']),2)); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h("$" . number_format(intval($utilidade['Utilidade']['precioventa'] * $utilidade['Utilidade']['cantidad']),2)); ?>&nbsp;</td>                    
                    <td class="text-right"><?php echo h("$" . number_format(intval($utilidade['Utilidade']['utilidadbruta']),2)); ?>&nbsp;</td>
                    <td class="text-right"><?php echo h(number_format($utilidade['Utilidade']['utilidadporcentual'],4) . "%"); ?>&nbsp;</td>
                    <td><?php echo h(!empty($utilidade['F']['factura']) ? "Factura" : "Remision"); ?>&nbsp;</td>
                    <td><?php echo h($utilidade['Utilidade']['created']); ?>&nbsp;</td>
                    <td><?php echo $this->Html->link($utilidade['F']['factura'] == '1' ? $utilidade['F']['consecutivodian'] : $utilidade['F']['codigo'], 
                            '/facturas/view/' . $utilidade['F']['id'], array('target' => '_blanck')); ?>&nbsp;</td>
                </tr>
                <?php endforeach; ?>
                </table>
                <legend>&nbsp;</legend>
                <div class="row">
                    <div class="col-md-8">
                        &nbsp;
                    </div>              
                    <div class="col-md-2">
                        <dl>
                            <dt class="text-left text-success"><?php echo h("Venta Total: ");?></dt>                    
                            <dt class="text-left text-success"><?php echo h("Costo Total: ");?></dt>    
                            <legend></legend>
                            <dt class="text-left text-success"><?php echo h("Total Utilidad: ");?></dt>                            
                        </dl>
                    </div>         
                    <div class="col-md-2">                
                        <dl>                            
                            <dt class="text-right text-success"><?php echo h("$" . number_format($utilidadBruta,2))?></dt>
                            <dt class="text-right text-success"><?php echo h("$" . number_format(($totalVenta),2))?></dt>
                            <legend></legend>
                            <dt class="text-right text-success"><?php echo h("$" . number_format(($utilidadBruta - $totalVenta),2))?></dt>
                        </dl>
                    </div>                     
                </div>
                
            </div>
        </div>
</div><br><br>
<?php echo $this->Form->create('Reporte',array( 'controller' => 'reportes','action'=>'descargarUtilidades')); ?>
    <fieldset>    
        <?php echo $this->Form->input('rpfechIni', array('type' => 'hidden', 'name' => 'rpfechIni', 'value' => $fechaInicio))?>

        <?php echo $this->Form->input('rpfechFin', array('type' => 'hidden', 'name' => 'rpfechFin', 'value' => $fechaFin))?>
        <?php echo $this->Form->submit('Descargar',array('class'=>'btn btn-primary')); ?>
    </fieldset>
</form><br><br>
