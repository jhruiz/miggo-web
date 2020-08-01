<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('precargueinventario/precargueinventario.js')); ?>
<div class="precargueinventarios index">
	<legend><h2><b><?php echo __('Cargue de Inventario Parcial'); ?></b></h2></legend>
        <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '11', 'id' => 'menuvert'))?> 
        <div class="container">
            <br>
            <div class="btn-group">
                <button id="butVerCatalogo" class="btn btn-primary" onclick="verCargueCatalogo();">Catálogo Productos</button>                                           
                <?php if(isset($arrInfoPreCargue['0'])){?>
                    <button name="<?php echo $arrInfoPreCargue['0']['Precargueinventario']['usuario_id'];?>" id="butCargarInventario_<?php echo $arrInfoPreCargue['0']['Precargueinventario']['usuario_id'];?>" class="btn btn-primary" onclick="aprobarCargueProductos(this);">Aprobar Cargue</button>
                <?php } ?>
            </div>
        </div><br><br>
        
        <div class="table-responsive">
            <div>        
                <table cellpadding="0" cellspacing="0" class="table table-striped table-hover table-condensed">
                    <tr>
                                    <th><?php echo ('Producto'); ?></th>
                                    <th><?php echo ('Depósito'); ?></th>
                                    <th><?php echo ('Valor Unitario'); ?></th>
                                    <th><?php echo ('Cantidad'); ?></th>
                                    <th><?php echo ('Valor Total');?></th>
                                    <th><?php echo ('Valor Máximo'); ?></th>
                                    <th><?php echo ('Valor Mínimo'); ?></th>
                                    <th><?php echo ('Precio Venta'); ?></th>
                                    <th><?php echo ('Proveedor'); ?></th>
                                    <th><?php echo ('Tipo Pago'); ?></th>
                                    <th><?php echo ('Factura'); ?></th>
                                    <th>&nbsp;</th>
                    </tr>
                    <?php foreach ($arrInfoPreCargue as $datCarg): ?>
                    <tr>
                        <td><br><?php echo h($datCarg['Producto']['descripcion']); ?> <input type="hidden" name="prod_<?php echo $datCarg['Precargueinventario']['id']; ?>"></td>
                            <td> <?php                             
                                            echo $this->Form->input("depositos_" . $datCarg['Precargueinventario']['id'],
                                                    array(
                                                        'name'=>"depositos_" . $datCarg['Precargueinventario']['id'],
                                                        'label' => "",
                                                        'type' => 'select',
                                                        'options'=>$listDepositos,
                                                        'class' => 'form-control',
                                                        'default' => $datCarg['Deposito']['id'],
                                                        'onchange' => 'actualizarDeposito(this);'
                                                    )
                                            );
                                         ?> </td>
                            <td><br><input type="text" size="5" name="costprod_<?php echo $datCarg['Precargueinventario']['id']; ?>" id="costprod_<?php echo $datCarg['Precargueinventario']['id']?>" class="form-control input-sm numericPrice" size="20" value="<?php echo $datCarg['Precargueinventario']['costoproducto'];?>" onblur="actualizarValor(this);"></td>
                            <td><br><input type="text" size="2" name="cant_<?php echo $datCarg['Precargueinventario']['id']; ?>" id="cant_<?php echo $datCarg['Precargueinventario']['id']; ?>" class="form-control input-sm" value="<?php echo $datCarg['Precargueinventario']['cantidad']; ?>" onblur="actualizarCantidad(this);"></td>
                            <td><br><input type="text" size="8" name="costTot_<?php echo $datCarg['Precargueinventario']['id']; ?>" id="cosTot_<?php echo $datCarg['Precargueinventario']['id']; ?>" class="form-control input-sm numericPrice" value="$<?php echo $datCarg['Precargueinventario']['costototal'];?>" readonly="readonly"></td>
                            <td><br><input type="text" size="5" name="precioMax_<?php echo $datCarg['Precargueinventario']['id']; ?>" id="precioMax_<?php echo $datCarg['Precargueinventario']['id']; ?>" class="form_control input-sm numericPrice" value="<?php echo $datCarg['Precargueinventario']['preciomaximo']; ?>" onblur="actualizarValorMaximo(this);"></td>
                            <td><br><input type="text" size="5" name="precioMin_<?php echo $datCarg['Precargueinventario']['id']; ?>" id="precioMin_<?php echo $datCarg['Precargueinventario']['id']; ?>" class="form_control input-sm numericPrice" value="<?php echo $datCarg['Precargueinventario']['preciominimo']; ?>" onblur="actualizarValorMinimo(this);"></td>
                            <td><br><input type="text" size="5" name="precioVen_<?php echo $datCarg['Precargueinventario']['id']; ?>" id="precioVen_<?php echo $datCarg['Precargueinventario']['id']; ?>" class="form_control input-sm numericPrice" value="<?php echo $datCarg['Precargueinventario']['precioventa']; ?>" onblur="actualizarPrecioVenta(this);"></td>
                            <td> <?php                             
                                            echo $this->Form->input("proveedore_" . $datCarg['Precargueinventario']['id'],
                                                    array(
                                                        'name'=>"proveedore_" . $datCarg['Precargueinventario']['id'],
                                                        'label' => "",
                                                        'type' => 'select',
                                                        'options'=>$listProveedores,
                                                        'class' => 'form-control',
                                                        'default' => $datCarg['Proveedore']['id'],
                                                        'onchange' => 'actualizarProveedor(this);'
                                                    )
                                            );
                                         ?> </td>
                            <td><?php                             
                                            echo $this->Form->input("tipopago_" . $datCarg['Precargueinventario']['id'],
                                                    array(
                                                        'name'=>"tipopago_" . $datCarg['Precargueinventario']['id'],
                                                        'label' => "",
                                                        'type' => 'select',
                                                        'options'=>$listTipoPago,
                                                        'class' => 'form-control',
                                                        'default' => $datCarg['Tipopago']['id'],
                                                        'onchange' => 'actualizarTipoPago(this);'
                                                    )
                                            );
                                         ?> </td>
                            <td><br><input type="text" size="9" name="fact_<?php echo $datCarg['Precargueinventario']['id']; ?>" id="fact_<?php echo $datCarg['Precargueinventario']['id']; ?>" class="form-control input-sm" value="<?php echo $datCarg['Precargueinventario']['numerofactura']; ?>" onblur="actualizarNumeroFactura(this);"></td>
                            <td class="actions"><br>                                      
                                <?php
                                echo $this->Form->postLink(                        
                                  $this->Html->image('png/list-2.png', array('title' => 'Eliminar Producto', 'alt' => __('Brownies'), 'width' => '20px')), //imagen
                                  array('action' => 'delete', $datCarg['Precargueinventario']['id']), //url
                                  array('escape' => false), //el escape
                                  __('Está seguro que desea eliminar el producto del precargue %s?', $datCarg['Producto']['descripcion']) //la confirmacion
                                ); 
                                ?>                      
                            </td>
                    </tr>
            <?php endforeach; ?>
                </table>                
            </div>            
        </div>
        <div class="container">
            <div class="text-center"><legend><h4><b><?php echo __('Detalle de la Operación'); ?></b></h4></legend></div>
                <div class="row">
                    <div class="row">
                        <div class="col-sm-8">&nbsp;</div>
                      <div class="col-sm-2"><b>Cantidad de Productos</b></div>
                      <div class="col-sm-2 text-right"><?php echo $totalProductos;?></div>      
                    </div><br>
                    <div class="row">
                        <div class="col-sm-8">&nbsp;</div>
                      <div class="col-sm-2"><b>Cantidad de Ítems</b></div>
                      <div class="col-sm-2 text-right"><?php echo $cantItems;?></div>      
                    </div><br>   
                    <div class="row">
                        <div class="col-sm-8">&nbsp;</div>
                      <div class="col-sm-2"><b>Costo Total</b></div>
                      <div class="col-sm-2 text-right">$ <?php echo number_format($costoTotal,2);?></div>      
                    </div> 
                </div> 
        </div>
</div>
<div id="div_anotacion"></div>

