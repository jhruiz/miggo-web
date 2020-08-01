<?php $this->layout='inicio'; ?>
<div class="detalledocumentos viewmovements">
        
	<legend><h2><b><?php echo __('Movimientos de Productos'); ?></b></h2></legend>
        <div class="table-responsive">
            <div class="container">        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                    <tr>
                                    <th><?php echo ('Nombre'); ?></th>
                                    <th><?php echo ('Codigo'); ?></th>
                                    <th><?php echo ('Costo Producto') ?></th>
                                    <th><?php echo ('Cantidad Producto') ?></th>
                                    <th><?php echo ('Numero Factura') ?></th>
                                    <th><?php echo ('Tipo de Movimiento') ?></th>
                                    <th><?php echo ('Fecha de Movimiento') ?></th>                                    
                    </tr>
                    <?php foreach ($arrMovements as $arrMovement): ?>
                    <tr>
                            <td><?php echo h($arrMovement['P']['descripcion']); ?>&nbsp;</td>
                            <td><?php echo h($arrMovement['P']['codigo']); ?>&nbsp;</td>
                            <td><?php echo h($arrMovement['Detalledocumento']['costoproducto']); ?>&nbsp;</td>
                            <td><?php echo h($arrMovement['Detalledocumento']['cantidad']); ?>&nbsp;</td>  
                            <td><?php echo h($arrMovement['Detalledocumento']['numerofactura']); ?>&nbsp;</td> 
                            <td><?php echo h($arrMovement['TD']['descripcion']); ?>&nbsp;</td>
                            <td><?php echo h($arrMovement['D']['created']); ?>&nbsp;</td>                            
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
</div>