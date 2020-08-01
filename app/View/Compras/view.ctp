<?php $this->layout='inicio'; ?>
<?php $subtotal = 0;?>
<div class="compras">
<legend><h2><b><?php echo __('Compra'); ?></b></h2></legend>

	<dl>
		<dt class="text-info"><?php echo __('Proveedor'); ?></dt>
		<dd>
                    <?php echo h($infoProv['Proveedore']['nombre'] . " - " . $infoProv['Proveedore']['nit']); ?>                    
                    &nbsp;
		</dd><br>
                
		<dt class="text-info"><?php echo __('Fecha factura'); ?></dt>
		<dd>
                    <?php echo h($infoCompra['0']['Compra']['fecha']); ?>                    
                    &nbsp;
		</dd><br>
                
		<dt class="text-info"><?php echo __('Número factura'); ?></dt>
		<dd>
                    <?php echo h($infoCompra['0']['Compra']['numerofactura']); ?>                    
                    &nbsp;
		</dd><br>
                
		<dt class="text-info"><?php echo __('Usuario'); ?></dt>
		<dd>
                    <?php echo h($infoUsr['Usuario']['nombre'] . " - " . $infoUsr['Usuario']['identificacion']); ?>                    
                    &nbsp;
		</dd><br>
                
        <div class="table-responsive">            
            <div class="container">   
                <legend><h2><b><?php echo __('Detalle de factura de compra'); ?></b></h2></legend>
                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover table-condensed">
                    <?php foreach ($catComprasComp as $cat): ?>
                    <tr>
                        <?php $subtotal += $cat['CategoriacomprasCompra']['valor']; ?>
                        <td><?php echo h($listCat[$cat['CategoriacomprasCompra']['categoriacompra_id']]); ?>&nbsp;</td>
                        <td><?php echo h("$" . number_format($cat['CategoriacomprasCompra']['valor'], 2)); ?>&nbsp;</td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td><b>SUBTOTAL</b></td>
                        <td style="border-top:2pt solid black;"><b><?php echo h("$" . number_format($subtotal, 2)); ?></b></td>
                    </tr>
                    <tr>
                        <td><b>IVA <?php echo h((($infoCompra['0']['Compra']['prciva'] - 1)*100) . "%"); ?></b></td>
                        <td>
                            <?php echo h("$" . number_format($infoCompra['0']['Compra']['vlriva'], 2)); ?>                            
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>
                                <?php echo h(!empty($listRicaRfte[$infoCompra['0']['Compra']['retefuente_id']]) ? $listRicaRfte[$infoCompra['0']['Compra']['retefuente_id']] : "RETEFUENTE"); ?>
                                <?php echo h(($infoCompra['0']['Compra']['prcretefuente'] - 1)*100 > 0 ? (($infoCompra['0']['Compra']['prcretefuente'] - 1)*100) . "%" : "0%"); ?>
                            </b>
                        </td>
                        <td><?php echo h("($" . number_format($infoCompra['0']['Compra']['vlrretefuente'], 2) . ")"); ?></td>
                    </tr>
                    <tr>
                        <td>
                            <b>
                                <?php echo h(!empty($listRicaRfte[$infoCompra['0']['Compra']['reteica_id']]) ? $listRicaRfte[$infoCompra['0']['Compra']['reteica_id']] : "RETEICA"); ?>
                                <?php echo h(($infoCompra['0']['Compra']['prcreteica'] - 1)*100 > 0 ? (($infoCompra['0']['Compra']['prcreteica'] - 1)*100) . "%" : "0%"); ?>
                            </b>
                        </td>
                        <td><?php echo h("($" . number_format($infoCompra['0']['Compra']['vlrreteica'], 2) . ")"); ?></td>
                    </tr>
                    <tr>
                        <td><b>TOTAL</b></td>
                        <td style="border-top:2pt solid black;"><b><?php echo h("$" . number_format((
                                $subtotal + $infoCompra['0']['Compra']['vlriva'] - $infoCompra['0']['Compra']['vlrretefuente'] - $infoCompra['0']['Compra']['vlrreteica']
                                ), 2)); ?></b></td>
                    </tr>
                </table>
            </div>
        </div>
                
	</dl>
</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Compras'), array('action' => 'index')); ?> </li>
	</ul>
</div>