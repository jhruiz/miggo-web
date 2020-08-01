<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('documentos/documentos.js')); ?>
<legend><h2><b><?php echo __('Documento ' . $documento['Documento']['codigo']); ?></b></h2></legend>
<?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '28', 'id' => 'menuvert'))?>
<div class="container">                                        
    <div class="btn-group hidden-print">
        <input type="hidden" id="documentoId" value="<?php echo $documento['Documento']['id'];?>">
        <button id="butImprimirDoc" class="btn btn-primary" onclick="window.print();">Imprimir</button>
        <button id="butAgregarNota" name="<?php echo $documento['Usuario']['id'];?>" class="btn btn-primary" onclick="agregarNotaDocumento(this);">Agragar Nota</button>
    </div>
</div><br><br> 
<div class="documentos view">    
    <div class="container-fluid">
        <div class="form-inline">
            
                        <section class="main row">
                            <div class="col-md-4" style="width: 50%;">
                                <dl>
                                    <h3><dt><?php echo h($documento['Empresa']['nombre']);?></dt></h3>
                                    <dt><?php echo h("Nit: " . $documento['Empresa']['nit']);?></dt>
                                    <dt><?php echo h("Nit: " . $documento['Empresa']['telefono1']);?></dt><br>
                                    <dt><?php echo h("Código Documento " . $documento['Documento']['codigo']);?></dt>
                                    <dt><?php echo h($documento['Tiposdocumento']['descripcion']);?></dt>
                                    <dt><?php echo h($documento['Documento']['created']);?></dt>
                                    <dt><?php echo h("Gestión Por: " . $documento['Usuario']['nombre']);?></dt>                    
                                </dl>
                            </div>         
                            <div class="col-md-4" style="width: 1%;">
                                &nbsp;
                            </div>            
                            <div class="col-md-4" style="width: 49%;">
                                <img src="<?php echo $urlImg . $documento['Empresa']['id'] . '/' . $documento['Empresa']['imagen'];?>" class="img-responsive img-thumbnail center-block" width="180" height="180" >
                            </div>
                            <div class="clearfix visible-sm-block"></div>    
                        </section>
        </div>
        <div class="clearfix"></div> 
    </div>	
    <legend>&nbsp;</legend>
</div>
<div class="container-fluid">
        <div class="table-responsive">
            <div class="container">
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                    <tr class="info">
                                    <th><?php echo ('Producto'); ?></th>
                                    <th><?php echo ('Depósito Origen'); ?></th>
                                    <th><?php echo ('Depósito Destino'); ?></th>
                                    <th><?php echo ('Costo Unitario'); ?></th>
                                    <th><?php echo ('Cantidad'); ?></th>
                                    <th><?php echo ('Precio Máximo'); ?></th>
                                    <th><?php echo ('Precio Mínimo'); ?></th>
                                    <th><?php echo ('Precio de Venta'); ?></th>
                                    <th><?php echo ('Proveedor'); ?></th>
                                    <th><?php echo ('Tipo Pago'); ?></th>
                                    <th><?php echo ('# Factura'); ?></th>
                    </tr>
                    <?php foreach ($detalleDoc as $detalledocumento): ?>
                    <tr>
                        <td><?php echo h($detalledocumento['Producto']['descripcion']); ?>&nbsp;</td>
                        <td><?php echo h($detalledocumento['Detalledocumento']['depositoorigen_id']); ?>&nbsp;</td>
                        <td><?php echo h($detalledocumento['Detalledocumento']['depositodestino_id']); ?>&nbsp;</td>
                        <td><?php echo h("$ ". number_format($detalledocumento['Detalledocumento']['costoproducto'],2)); ?>&nbsp;</td>
                        <td><?php echo h($detalledocumento['Detalledocumento']['cantidad']); ?>&nbsp;</td>
                        <td><?php echo h("$ ". number_format($detalledocumento['Detalledocumento']['preciomaximo'],2)); ?>&nbsp;</td>
                        <td><?php echo h("$ ". number_format($detalledocumento['Detalledocumento']['preciominimo'],2)); ?>&nbsp;</td>
                        <td><?php echo h("$ ". number_format($detalledocumento['Detalledocumento']['precioventa'],2)); ?>&nbsp;</td>
                        <td><?php echo h($detalledocumento['Proveedore']['nombre']); ?>&nbsp;</td>
                        <td><?php echo h($detalledocumento['Tipopago']['descripcion']); ?>&nbsp;</td>
                        <td><?php echo h($detalledocumento['Detalledocumento']['numerofactura']); ?>&nbsp;</td>
                    </tr>
                    <?php endforeach; ?>                  
                </table>                                
            </div>
        </div>
</div>
<legend>&nbsp;</legend>
<section class="main row">
    <div class="col-md-8">
        &nbsp;
    </div>              
    <div class="col-md-2">
            <dt><?php echo h("Total de Productos: ");?></dt>
            <dt><?php echo h("Total de Unidades: ");?></dt>
            <dt><?php echo h("Total: ");?></dt>                
    </div>  
    <div class="col-md-1">
        <dt class="text-right"><?php echo h($totalProductos);?></dt>
        <dt class="text-right"><?php echo h($cantProd);?></dt>
        <dt class="text-right"><?php echo h("$" . number_format($total,2));?></dt>   
    </div>
    <div class="col-md-1">
        &nbsp;
    </div>
</section>
<legend>&nbsp;</legend>

<div class="container">
    <dl>
            <dt><?php echo __('Notas'); ?></dt>
            <?php foreach ($arrAnotaciones as $notas):?>
            <dd>
                    <?php echo h($notas['Anotacione']['created'] . " " . $notas['Usuario']['nombre'] . ".  " . $notas['Anotacione']['descripcion']); ?>
                    &nbsp;
            </dd>
            <?php endforeach;?>
    </dl>
</div><br><br>

<div class="container hidden-print">                                        
    <div class="btn-group">
        <button id="butImprimirDoc" class="btn btn-primary" onclick="window.print();">Imprimir</button>
        <button id="butAgregarNota" name="<?php echo $documento['Usuario']['id'];?>" class="btn btn-primary" onclick="agregarNotaDocumento(this);">Agragar Nota</button>
    </div>
</div> 
<div id="div_anotacion"></div>

	

