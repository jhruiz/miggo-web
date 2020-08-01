<?php $this->layout='inicio'; ?>
<div class="detallefactura">
    <legend><h2><b><?php echo __('Detalle Factura No. ' . $consecutivoFact . '. ' 
            . $arrUbicacion['0']['Ciudade']['descripcion'] . ', ' . $arrUbicacion['0']['P']['descripcion'] . ', '
            . $fechaActual); ?></b></h2></legend>

    <div class="container-fluid">
        <div class="row">        
            <div class="col-md-3">
                <legend><h2><b><?php echo __('Empresa'); ?></b></h2></legend>

                <?php if(!empty($infoRemision)){?>
                <dd><?php echo h($infoRemision['Relacionempresa']['nombre']); ?>&nbsp;</dd>    
                <dd><?php echo h($infoRemision['Relacionempresa']['nit']); ?>&nbsp;</dd>    
                <dd><?php echo h($infoRemision['Relacionempresa']['direccion']); ?>&nbsp;</dd>    
                <dd><?php echo h($infoRemision['Relacionempresa']['telefono1']); ?>&nbsp;</dd>    
                <dd><?php echo h($infoRemision['Relacionempresa']['representantelegal']); ?>&nbsp;</dd>    
                <?php }else{ ?>
                <dd><?php echo h($infoEmpresa['Empresa']['nombre']); ?>&nbsp;</dd>    
                <dd><?php echo h($infoEmpresa['Empresa']['nit']); ?>&nbsp;</dd>    
                <dd><?php echo h($infoEmpresa['Empresa']['direccion']); ?>&nbsp;</dd>    
                <dd><?php echo h($infoEmpresa['Empresa']['telefono1']); ?>&nbsp;</dd>    
                <dd><?php echo h($infoEmpresa['Empresa']['representantelegal']); ?>&nbsp;</dd>            
                <?php } ?>

            </div>        
            <div class="col-md-3">
                <legend><h2><b><?php echo __('Vendedor'); ?></b></h2></legend>
                <dd><?php echo h($infoVendedor['Usuario']['nombre']); ?>&nbsp;</dd>                    
                <dd><?php echo h($infoVendedor['Usuario']['identificacion']); ?>&nbsp;</dd>                    
                <dd><?php echo h($infoVendedor['Perfile']['descripcion']); ?>&nbsp;</dd>                   
                    &nbsp;
            </div>        
            <div class="col-md-3">
                <legend><h2><b><?php echo __('Cliente'); ?></b></h2></legend>
                <dd><?php echo h($infoFact['Cliente']['nombre']); ?>&nbsp;</dd>
                <dd><?php echo h($infoFact['Cliente']['direccion']); ?>&nbsp;</dd>
                <dd><?php echo h($infoFact['Cliente']['celular']); ?>&nbsp;</dd>
                <dd><?php echo h("Cartera " . "$" . number_format($totalCartera, 2)); ?>&nbsp;</dd>
            </div>        
            <div class="col-md-3">
                <legend><h2><b><?php echo __('Vehículo'); ?></b></h2></legend>
                <dd><?php echo h(!empty($arrVeh['Vehiculo']['linea']) ? $arrVeh['Vehiculo']['linea'] : ""); ?>&nbsp;</dd>
                <dd><?php echo h(!empty($arrVeh['Vehiculo']['placa']) ? $arrVeh['Vehiculo']['placa'] : ""); ?>&nbsp;</dd>
                <dd><?php echo h(!empty($arrVeh['Vehiculo']['modelo']) ? $arrVeh['Vehiculo']['modelo'] : ""); ?>&nbsp;</dd>
                <dd><?php echo h(!empty($arrVeh['Vehiculo']['color']) ? $arrVeh['Vehiculo']['color'] : ""); ?>&nbsp;</dd>
            </div>        
        </div>        
    </div><br><br>

    <div class="container">        
        <legend><h2><b><?php echo __('Detalle de pagos'); ?></b></h2></legend>
        <div class="container-fluid">
            <div class="container">

                <div class="col-md-3">
                    <legend><h2><b><?php echo __('Caja'); ?></b></h2></legend>
                    <?php foreach($factCV as $fvcv):?>
                        <dd><?php echo h($fvcv['C']['descripcion']); ?>&nbsp;</dd>
                    <?php endforeach; ?>                                
                </div>  

                <div class="col-md-2">
                    <legend><h2><b><?php echo __('Tipo de pago'); ?></b></h2></legend>
                    <?php foreach($factCV as $fvcv):?>
                        <dd><?php echo h($fvcv['T']['descripcion']); ?>&nbsp;</dd>
                    <?php endforeach; ?>                                
                </div>  

                <div class="col-md-2">
                    <legend><h2><b><?php echo __('Fecha'); ?></b></h2></legend>
                    <?php foreach($factCV as $fvcv):?>
                        <dd><?php echo h($fvcv['FacturaCuentaValore']['created']); ?>&nbsp;</dd>
                    <?php endforeach; ?>                                
                </div>  

                <div class="col-md-3">
                    <legend><h2><b><?php echo __('Usuario'); ?></b></h2></legend>
                    <?php foreach($factCV as $fvcv):?>
                        <dd><?php echo h(!empty($usrEmpresa[$fvcv['FacturaCuentaValore']['usuario_id']]) ? $usrEmpresa[$fvcv['FacturaCuentaValore']['usuario_id']] : ""); ?>&nbsp;</dd>
                    <?php endforeach; ?>                                
                </div>  

                <div class="col-md-2">
                    <legend><h2><b><?php echo __('Valor'); ?></b></h2></legend>
                    <?php foreach($factCV as $fvcv):?>
                        <dd><?php echo h("$ " . number_format($fvcv['FacturaCuentaValore']['valor'], 2)); ?>&nbsp;</dd>
                    <?php endforeach; ?>                                
                </div>  
            </div>
        </div>
    </div><br><br>

    
    <div style="width:100%; float:left; margin-top: 20px;">
        <h2><b><?php echo __('Productos'); ?></b></h2>
        <table class="table" style="font-family:sans-serif; font-size:15px; border-collapse: collapse; width: 100%;">
            <tr>
                <th class="text-left"><?php echo ('Cant'); ?></th>
                <th class="text-left"><?php echo ('Descripcion'); ?></th>                                
                <th class="text-left"><?php echo ('Deposito'); ?></th>                                
                <th class="text-right"><?php echo ('Vlr. Unit'); ?></th>                              
                <th class="text-right"><?php echo ('%Dcto.'); ?></th>                          
                <th class="text-right"><?php echo ('Subtotal'); ?></th>
            </tr>                
                <?php                 

            $subTtalVenta = 0;
            $ttalIVA = 0;
            $ttalDtto = 0;
            $valorIVA = 0;
            ?>
            <?php foreach ($infoDetFact as $DetFact): ?>

            <?php 

                if(!$infoFact['Factura']['factura']){
                    $costoVenta = $DetFact['Facturasdetalle']['costoventa'];
                    $valorXCantidad = ceil($costoVenta * $DetFact['Facturasdetalle']['cantidad']);
                    $descuento = $valorXCantidad * ($DetFact['Facturasdetalle']['porcentaje']/100);
                    $valorConDescuento = $valorXCantidad - $descuento;
            ?>                
                <tr>
                    <td><?php echo h($DetFact['Facturasdetalle']['cantidad']); ?>&nbsp;</td>
                    <td><?php echo h($DetFact['Producto']['descripcion']); ?>&nbsp;</td>                    
                    <td><?php echo h($DetFact['Deposito']['descripcion']); ?>&nbsp;</td>                    
                    <td  align="right"><?php echo h("$ " . number_format($costoVenta,2)); ?>&nbsp;</td>
                    <td  align="right"><?php echo h($DetFact['Facturasdetalle']['porcentaje'] . "%"); ?>&nbsp;</td>
                    <td  align="right"><?php echo h("$ " . number_format(($valorXCantidad),2)); ?>&nbsp;</td>
                </tr>    

            <?php        
                }else{

                    $costoBase = 0;
                    $descuento = 0;
                    $costoVenta = $DetFact['Facturasdetalle']['costoventa'];
                    $imp = "";
                    if(!empty($DetFact['Facturasdetalle']['impuesto'])){
                        $imp = "*";
                        $costoBase = ceil($DetFact['Facturasdetalle']['costoventa'] / (($DetFact['Facturasdetalle']['impuesto']/100)+1));
                    }else{
                        $costoBase = ceil($DetFact['Facturasdetalle']['costoventa']);
                    }

                    if(!empty($DetFact['Facturasdetalle']['porcentaje'])){
                        $descuento = ceil(($costoBase * ($DetFact['Facturasdetalle']['porcentaje'])/100) * $DetFact['Facturasdetalle']['cantidad']);
                    }                        

                    $valorXCantidad = $costoBase * $DetFact['Facturasdetalle']['cantidad'];                                                
                    $valorIVA = ceil(($valorXCantidad - $descuento) * ($DetFact['Facturasdetalle']['impuesto']/100));

            ?>                
                <tr>
                    <td><?php echo h($DetFact['Facturasdetalle']['cantidad']); ?>&nbsp;</td>
                    <td><?php echo h($imp . $DetFact['Producto']['descripcion']); ?>&nbsp;</td>  
                    <td><?php echo h($DetFact['Deposito']['descripcion']); ?>&nbsp;</td> 
                    <td  align="right"><?php echo h("$ " . number_format($costoBase,2)); ?>&nbsp;</td>
                    <td  align="right"><?php echo h($DetFact['Facturasdetalle']['porcentaje'] . "%"); ?>&nbsp;</td>
                    <td  align="right"><?php echo h("$ " . number_format(($costoBase * $DetFact['Facturasdetalle']['cantidad']),2)); ?>&nbsp;</td>

                </tr>    

            <?php                          
                }                                
                $subTtalVenta += $valorXCantidad; 
                $ttalIVA += $valorIVA;
                $ttalDtto += $descuento;                    
            endforeach; ?> 

            <?php if($infoFact['Factura']['factura']){ ?>
            <tr>
                <td colspan="4">&nbsp;</td>
                <td  align="right"><b>Subtotal</b></td>
                <td  align="right"><b><?php echo "$ " . number_format(($subTtalVenta),2); ?></td></td>
            </tr>
            <?php if(!empty($ttalDtto)){ ?>
            <tr>
                <td colspan="4">&nbsp;</td>
                <td  align="right"><b>Descuento</b></td>
                <td  align="right"><b>(<?php echo ("$ ". number_format(($ttalDtto),2));?>)</b></td>
            </tr>                 
            <?php } ?>                
            <tr>
                <td colspan="4">&nbsp;</td>
                <td  align="right"><b>Subtotal con Dcto.</b></td>
                <td  align="right"><b><?php echo ("$ ". number_format((ceil($subTtalVenta - $ttalDtto)),2));?></b></td>
            </tr>                 
            <tr>
                <td colspan="4">&nbsp;</td>
                <td  align="right"><b>IVA</b></td>
                <td  align="right"><b><?php echo ("$ ". number_format($ttalIVA,2));?></b></td>
            </tr>                    
            <tr>
                <td colspan="4">&nbsp;</td>
                <td  align="right"><b>Reteica</b></td>
                <td  align="right"><b>0%</b></td>
            </tr>                    
            <tr>
                <td colspan="4">&nbsp;</td>
                <td  align="right"><b>Retefuente</b></td>
                <td  align="right"><b>0%</b></td>
            </tr> 
            <tr>
                <td colspan="4">&nbsp;</td>
                <td  align="right"><b>TOTAL</b></td>
                <td  align="right"><b><?php echo ("$ ". number_format((ceil($subTtalVenta - $ttalDtto) + $ttalIVA),2));?></b></td>
            </tr>                
            <?php }else{ ?>
            <tr>
                <td colspan="4">&nbsp;</td>
                <td  align="right"><b>SUBTOTAL</b></td>
                <td  align="right"><b><?php echo ("$ ". number_format(($subTtalVenta),2));?></b></td>
            </tr>                
            <tr>
                <td colspan="4">&nbsp;</td>
                <td  align="right"><b>DESCUENTO</b></td>
                <td  align="right"><b><?php echo ("$ ". number_format(($ttalDtto),2));?></b></td>
            </tr>                
            <tr>
                <td colspan="4">&nbsp;</td>
                <td  align="right"><b>TOTAL</b></td>
                <td  align="right"><b><?php echo ("$ ". number_format(ceil($subTtalVenta - $ttalDtto),2));?></b></td>
            </tr>                
            <?php } ?>

        </table> 
    </div>

</div>
<div class="actions">
	<legend><h2><b><?php echo __('Acciones'); ?></b></h2></legend>
	<ul>
		<li><?php echo $this->Html->link(__('Lista Facturas'), array('action' => 'index')); ?> </li>
	</ul>
</div>