<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('facturas/gestionfacturas.js'));?>
<div class="container body">
<div class="main_container">
<br>
  <div class="x_panel"> 
    <input type="hidden" id="facturaId" value="<?php echo $infoFact['Factura']['id'];?>">
    <input type="hidden" id="cliName" value="<?php echo $infoFact['Cliente']['nombre'];?>">
    <input type="hidden" id="cliNit" value="<?php echo $infoFact['Cliente']['nit'];?>">
    <?php if($infoFact['Factura']['factura']){?>
    <input type="hidden" id="emisor" value="<?php echo $infoEmpresa['Empresa']['nombre'];?>">    
    <input type="hidden" id="emisorNit" value="<?php echo $infoEmpresa['Empresa']['nit'];?>">
    <?php }else{?>
    <input type="hidden" id="emisor" value="<?php echo $infoRemision['Relacionempresa']['representantelegal'];?>">    
    <input type="hidden" id="emisorNit" value="<?php echo $infoRemision['Relacionempresa']['nit'];?>">
    <?php }?>

    <div class="row">
        <div class="col-md-10" >
            <button id="butImprimirFact" class="btn btn-primary hidden-print" onclick="imprimirFactura();">Imprimir</button>            
            <button id="butImprimirTk" class="btn btn-primary hidden-print" onclick="imprimirTicket();">Imprimir Ticket</button>
            <button id="butImprimirFactT" class="btn btn-primary hidden-print" onclick="generarAlertaFactura();">Generar Alerta</button>        
            <a class="btn btn-primary" href="https://catalogo-vpfe.dian.gov.co/User/Login" role="button" target="_blank">Facturacion Electronica</a>

            <?php if(!empty($infoFact['Cliente']['celular'])){?>        
                <a href="https://wa.me/57<?php echo $infoFact['Cliente']['celular']; ?>?text=adjuntamos%20información%20de%20su%20interés" target="_blank">
                    <img src="<?php echo $urlImgWP; ?>" class="img-responsive" width="35">            
                </a>
            <?php }else{ ?>     
                <div class="alert alert-danger" role="alert" style="margin-top: 15px;">
                    El usuario no tiene un número celular registrado.
                </div>    
            <?php } ?>
        </div>
    </div>
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '33', 'id' => 'menuvert'))?> 
    <div id="dvFacturas" style="margin:0px; width:100%; font-family:sans-serif; font-size:15px;">    
        <div style="float:center;" align="center">
            <img src="<?php 
                echo $urlImg . $infoEmpresa['Empresa']['id'] . '/' . $infoEmpresa['Empresa']['imagen'];                        
            ?>" 
            class="img-responsive img-thumbnail center-block" width="200">
        </div>  
        <?php if(!empty($infoRemision)){?>
        <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __($infoRemision['Relacionempresa']['nombre']); ?></b></div>
        <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __($infoRemision['Relacionempresa']['representantelegal']); ?></b></div>
        <?php }else{?>
        <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __($infoEmpresa['Empresa']['nombre']); ?></b></div>
        <?php }?>
       
        <?php if($infoFact['Factura']['factura']){ ?>
        <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __($nombreDocumento . ' ' . $prefijo . ' ' . $consecutivoFact) ?></b></div> 
        <input id="tipoVenta" type="hidden" value="1">
        <?php }else{?>
        <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __('DOCUMENTO DE COMPRA No. ' . $prefijo . ' ' . $consecutivoFact) ?></b></div>    
        <input id="tipoVenta" type="hidden" value="2">
        <?php }?>
        
        <!--informacion e imagen de empresa-->
        <div style="margin:0px; width:100%; float:left;">
            <div style="float:left; margin-top: 10px;" align="left">
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Nit: </b><?php 
                        if(!empty($infoRemision)){
                            echo h($infoRemision['Relacionempresa']['nit']);
                        }else
                            echo h($infoEmpresa['Empresa']['nit']);
                        ?>
                    </div>          
                </div>
                
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Teléfono: </b><?php 
                        if(!empty($infoRemision)){
                            echo h($infoRemision['Relacionempresa']['telefono1']);
                        }else{
                            echo h($infoEmpresa['Empresa']['telefono1'] . " / " . $infoEmpresa['Empresa']['telefono2']);
                        }
                                 
                        ?>
                    </div>                 
                </div>
                
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Dirección: </b><?php 
                        if(!empty($infoRemision)){
                            echo h($infoRemision['Relacionempresa']['direccion']);
                        }else{
                            echo h($infoEmpresa['Empresa']['direccion']);
                        }                                                           
                        ?>
                    </div>                           
                </div>
            </div>          
        </div> 
        
        <div style="width:100%; float:left; margin-top: 5px;">
            <?php
            echo __($arrUbicacion['0']['Ciudade']['descripcion'] . ", " . $arrUbicacion['0']['P']['descripcion'] . ", " . $fechaActual);
            ?>
        </div>    
        
        <div style="width:100%; float:left; margin-top: 5px;"><?php echo $infoEmpresa['Empresa']['texto1']?></div>
        
        <!--informacion del cliente y moto-->
        <div style="margin:0px; width:100%; float:left;">
            <div style="float:left; margin-top: 10px; width:50%" align="left">
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Cliente: </b><?php echo h(!empty($infoFact['Cliente']['nombre']) ? $infoFact['Cliente']['nombre'] : "ANONIMO"); ?>
                    </div>          
                </div>
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Teléfono: </b><?php echo h(!empty($infoFact['Cliente']['telefono']) ? $infoFact['Cliente']['telefono'] . " / " . $infoFact['Cliente']['celular'] : $infoFact['Cliente']['celular']); ?>
                    </div>                 
                </div>
                
                <?php if(!empty($infoFact['Factura']['ordentrabajo_id'])){?>
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Motor/Placa: </b><?php echo __(strtoupper($arrVeh['Vehiculo']['placa'])); ?>
                    </div>                           
                </div>
                <?php } ?>

            </div>
            
            <div style="float:right; margin-top:10px; width:50%" align="left">
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Identificación: </b><?php echo h(!empty($infoFact['Cliente']['nit']) ? $infoFact['Cliente']['nit'] : "N/A"); ?>
                    </div>          
                </div>
                
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Dirección: </b><?php echo $infoFact['Cliente']['direccion']; ?>
                    </div>                 
                </div>
                
                <?php if(!empty($infoFact['Factura']['ordentrabajo_id'])){ ?>
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Linea: </b><?php echo __(strtoupper($arrVeh['Vehiculo']['linea'])); ?>
                    </div>                           
                </div>
                <?php } ?>
            </div>            
        </div> 
        <div style="margin: 2px; float: left; width: 100%;">
            <div style="margin: 0px; float: left; width: 100%;">
                <b>Método de Pago: </b> <br>
                <?php if(count($factCV) > 0 ){ ?>
                    <?php foreach($factCV as $fvcv):?>
                        <?php echo h($fvcv['T']['descripcion'] . ": $ " . number_format($fvcv['FacturaCuentaValore']['valor'], 2)); ?>&nbsp;<br>
                    <?php endforeach; ?>                             
                <?php } ?>
                <?php if(count($factAbonos) > 0 ){ ?>
                    <?php foreach($factAbonos as $fab):?>
                        <?php echo h($fab['TP']['descripcion'] . ": $ " . number_format($fab['Abonofactura']['valor'], 2)); ?>&nbsp;<br>
                    <?php endforeach; ?>
                <?php } ?>                                  
            </div>                           
        </div>

        <div style="width:100%; float:left; margin-top: 20px;">
            <table class="table" style="font-family:sans-serif; font-size:15px; border-collapse: collapse; width: 100%;">
                <?php 
                    if(!$infoFact['Factura']['factura']){
                ?>
                <tr>
                                <th class="text-left"><?php echo ('#'); ?></th>
                                <th class="text-left"><?php echo ('Cant'); ?></th>
                                <th class="text-left"><?php echo ('Cod'); ?></th>
                                <th class="text-left"><?php echo ('Descripcion'); ?></th>                                
                                <th class="text-right"><?php echo ('Vlr. Unit'); ?></th>
                                <th class="text-right"><?php echo ('% Dcto.'); ?></th>
                                <th class="text-right"><?php echo ('Subtotal'); ?></th>
                </tr>
                    <?php }else{?>
                <tr>
                                <th class="text-left"><?php echo ('#'); ?></th>
                                <th class="text-left"><?php echo ('Cant'); ?></th>
                                <th class="text-left"><?php echo ('Cod.'); ?></th>                              
                                <th class="text-left"><?php echo ('Descripcion'); ?></th>                                
                                <th class="text-right"><?php echo ('Vlr. Unit'); ?></th>                              
                                <th class="text-right"><?php echo ('%Dcto.'); ?></th>                          
                                <th class="text-right"><?php echo ('Subtotal'); ?></th>
                </tr>                
                    <?php }                
                
                $subTtalVenta = 0;
                $ttalIVA = 0;
                $ttalDtto = 0;
                $valorIVA = 0;
                $contador = 1;
                ?>
                <?php foreach ($infoDetFact as $DetFact): ?>
                
                <?php 
                
                    if(!$infoFact['Factura']['factura']){
                        $costoVenta = $DetFact['Facturasdetalle']['costoventa'];
                        $valorXCantidad = ceil($costoVenta * $DetFact['Facturasdetalle']['cantidad']);
                        $descuento = $valorXCantidad * ($DetFact['Facturasdetalle']['porcentaje']/100);
                        $valorConDescuento = $valorXCantidad - $descuento;

                        if($DetFact['C']['servicio'] == '1'){
                            $ttalServ += $valorXCantidad;
                        } else {
                            $ttalRep += $valorXCantidad;
                        }

                ?>                
                    <tr>
                        <td><?php echo h($contador); ?></td>
                        <td><?php echo h($DetFact['Facturasdetalle']['cantidad']); ?>&nbsp;</td>
                        <td><?php echo h($DetFact['P']['codigo']); ?>&nbsp;</td>
                        <td><?php echo h($DetFact['P']['descripcion']); ?>&nbsp;</td>                    
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

                        if($DetFact['C']['servicio'] == '1'){                            
                            $ttalServ += $valorXCantidad;
                        } else {
                            $ttalRep += $valorXCantidad;
                        }
                ?>                
                    <tr>
                        <td><?php echo h($contador); ?></td>
                        <td><?php echo h($DetFact['Facturasdetalle']['cantidad']); ?>&nbsp;</td>
                        <td><?php echo h($DetFact['P']['codigo']); ?>&nbsp;</td>                        
                        <td><?php echo h($imp . $DetFact['P']['descripcion']); ?>&nbsp;</td>                    
                        <td  align="right"><?php echo h("$ " . number_format($costoBase,2)); ?>&nbsp;</td>
                        <td  align="right"><?php echo h($DetFact['Facturasdetalle']['porcentaje'] . "%"); ?>&nbsp;</td>
                        <td  align="right"><?php echo h("$ " . number_format(($costoBase * $DetFact['Facturasdetalle']['cantidad']),2)); ?>&nbsp;</td>

                    </tr>    
                    
                <?php                          
                    }                                
                    $subTtalVenta += $valorXCantidad; 
                    $ttalIVA += $valorIVA;
                    $ttalDtto += $descuento; 
                    $contador ++;                   
                endforeach; ?> 
                    
                <?php if($infoFact['Factura']['factura']){ ?>

                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td  align="right"><b><?php echo($serviceName);?></b></td>
                    <td  align="right"><b><?php echo "$ " . number_format(($ttalServ),2); ?></td></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td  align="right"><b><?php echo($productName);?></b></td>
                    <td  align="right"><b><?php echo "$ " . number_format(($ttalRep),2); ?></td></td>
                </tr>

                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td  align="right"><b>Subtotal</b></td>
                    <td  align="right"><b><?php echo "$ " . number_format(($subTtalVenta),2); ?></td></td>
                </tr>
                <?php if(!empty($ttalDtto)){ ?>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td  align="right"><b>Descuento</b></td>
                    <td  align="right"><b>(<?php echo ("$ ". number_format(($ttalDtto),2));?>)</b></td>
                </tr>                 
                <?php } ?>                
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td  align="right"><b>Subtotal con Dcto.</b></td>
                    <td  align="right"><b><?php echo ("$ ". number_format((ceil($subTtalVenta - $ttalDtto)),2));?></b></td>
                </tr>                 
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td  align="right"><b>IVA</b></td>
                    <td  align="right"><b><?php echo ("$ ". number_format($ttalIVA,2));?></b></td>
                </tr>                    
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td  align="right"><b>Reteica</b></td>
                    <td  align="right"><b>0%</b></td>
                </tr>                    
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td  align="right"><b>Retefuente</b></td>
                    <td  align="right"><b>0%</b></td>
                </tr> 
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td  align="right"><b>TOTAL</b></td>
                    <td  align="right"><b><?php echo ("$ ". number_format((ceil($subTtalVenta - $ttalDtto) + $ttalIVA),2));?></b></td>
                </tr>                
                <?php }else{ ?>

                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td  align="right"><b><?php echo($serviceName);?></b></td>
                    <td  align="right"><b><?php echo "$ " . number_format(($ttalServ),2); ?></td></td>
                </tr>
                <tr>                
                    <td colspan="5">&nbsp;</td>
                    <td  align="right"><b><?php echo($productName);?></b></td>
                    <td  align="right"><b><?php echo "$ " . number_format(($ttalRep),2); ?></td></td>
                </tr>

                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td  align="right"><b>SUBTOTAL</b></td>
                    <td  align="right"><b><?php echo ("$ ". number_format(($subTtalVenta),2));?></b></td>
                </tr>                
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td  align="right"><b>DESCUENTO</b></td>
                    <td  align="right"><b><?php echo ("$ ". number_format(($ttalDtto),2));?></b></td>
                </tr>                
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td  align="right"><b>TOTAL</b></td>
                    <td  align="right"><b><?php echo ("$ ". number_format(ceil($subTtalVenta - $ttalDtto),2));?></b></td>
                </tr>                
                <?php } ?>

            </table> 
        </div>

        <?php if(count($notaFactura) > '0'){?>        
            <dl>
                <dt><?php echo __('Notas'); ?></dt>
                    <?php foreach ($notaFactura as $nF): ?>
                    <dd>
                        <?php echo h($nF['FacturasNotafactura']['created'] . " " . $nF['Usuario']['nombre'] . ".  " . $nF['Notafactura']['descripcion']); ?>
                        &nbsp;
                    </dd>
                    <?php endforeach;?>
            </dl>
        <?php }?>
    
        
        <div id="contResol">
            <div id="dvResolucion" style="font-family:sans-serif; font-size:15px;"><small><b>
                        <?php if($infoFact['Factura']['factura']){?>
                            Resolución <?php echo ($infoResolucion['resolucion'])?>. Fecha de Resolución <?php echo ($infoResolucion['fechaRes'])?>.
                            Numeración habilitada del <?php echo ($infoResolucion['resInicial']);?> al <?php echo ($infoResolucion['resFin']);?>. <?php echo ($infoResolucion['nota']);?>. 
                        <?php } ?>
            </b></small></div>
        </div>
    
        <div id="conditions">
            <div id="p_condCont" style="font-family:sans-serif; font-size:15px;"><small>
                    <?php if($infoFact['Factura']['factura']){?>
                        <?php echo $infoEmpresa['Empresa']['texto2']?>
                    <?php } ?>
            </small></div>
        </div>    
        
        <div id="conditions_ot">
            <div id="p_condCont_ot"><small>
                <?php echo $infoEmpresa['Empresa']['texto3']?>
            </small></div>
        </div>  
        
        <div id="dvNota">
            <div id='nota_factura'>
                <small>
                <br><br><b>NOTA:</b><?php echo $infoFact['Factura']['observacion'];?>
                </small>
                <div align="center">
                    <small>
                    <br><br><b>Miggo Solutions S.A.S</b>
                    </small>
                </div>
            </div>
        </div>
    </div>  
    <?php if(!empty($arrInfoOrd)){ ?>
    
    <div style="width:100%; float:left; margin-top:50px;">
        <button id="butImprimirFact" class="btn btn-primary hidden-print" onclick="imprimirOrden();">Imprimir Orden</button><br><br>
    </div>
    
    <div id="ordenTrabajo" style="margin:0px; width:100%; font-family:sans-serif; font-size:15px;">

        <div style="float:center;" align="center">
            <img src="<?php 
                echo $urlImg . $infoEmpresa['Empresa']['id'] . '/' . $infoEmpresa['Empresa']['imagen'];                        
            ?>" 
            class="img-responsive img-thumbnail center-block" width="200">
        </div> 
        <div style="width:100%; float:left; margin:0px" align="center"><b><?php 
        if(!empty($infoRemision)){
            echo __($infoRemision['Relacionempresa']['nombre']); 
        }else{
            echo __($infoEmpresa['Empresa']['nombre']); 
        }        
        ?></b></div>
        <div style="width:100%; float:left; margin:0px" align="center"><h4><b>Orden de Trabajo</b></h4></div>
        
        <!--informacion e imagen de empresa-->
        <div style="margin:0px; width:100%; float:left;">
            <div style="float:left; margin-top: 10px;" align="left">
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Nit: </b><?php 
                        if(!empty($infoRemision)){
                            echo h($infoRemision['Relacionempresa']['nit']);
                        }else{
                            echo h($infoEmpresa['Empresa']['nit']);
                        }
                        
                        ?>
                    </div>          
                </div>
                
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Teléfono: </b><?php 
                        if(!empty($infoRemision)){
                            echo h($infoRemision['Relacionempresa']['telefono1']);
                        }else{
                            echo h($infoEmpresa['Empresa']['telefono1'] . " - " . $infoEmpresa['Empresa']['telefono2']);
                        }
                        
                        ?>
                    </div>                 
                </div>
                
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Dirección: </b><?php 
                        if(!empty($infoRemision)){
                            echo h($infoRemision['Relacionempresa']['direccion']);
                        }else{
                            echo h($infoEmpresa['Empresa']['direccion']);
                        }
                        
                        ?>
                    </div>                           
                </div>
            </div>           
        </div>
        
        <div style="width:100%; float:left; margin-top: 5px;">
            <?php
            echo __($arrUbicacion['0']['Ciudade']['descripcion'] . ", " . $arrUbicacion['0']['P']['descripcion'] . ", " . $fechaActual);
            ?>
        </div>                                
        
        <!--informacion del cliente y moto-->
        <div style="margin:0px; width:100%; float:left;">
            <div style="float:left; margin-top: 10px; width:50%;" align="left">
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Cliente: </b><?php echo h($infoFact['Cliente']['nombre']); ?>
                    </div>          
                </div>
                
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                    <b>Teléfono: </b><?php echo h(!empty($infoFact['Cliente']['telefono']) ? $infoFact['Cliente']['telefono'] . " / " . $infoFact['Cliente']['celular'] : $infoFact['Cliente']['celular']); ?>
                    </div>                 
                </div>
                
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Motor/Placa: </b><?php echo __(strtoupper($arrVeh['Vehiculo']['placa'])); ?>
                    </div>                           
                </div>
            </div>
            
            <div style="float:right; margin-top:10px; width:50%;" align="left">
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Identificación: </b><?php echo h($infoFact['Cliente']['nit']); ?>
                    </div>          
                </div>
                
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Dirección: </b><?php echo $infoFact['Cliente']['direccion']; ?>
                    </div>                 
                </div>
                
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Linea: </b><?php echo __(strtoupper($arrVeh['Vehiculo']['linea'])); ?>
                    </div>                           
                </div>
            </div>            
        </div>
        
        <!--kilometraje-->
        <div style="width:100%; float:left; margin-top: 10px;"><b>Kilometraje </b>
            <?php
            echo __(number_format($arrInfoOrd['0']['Ordentrabajo']['kilometraje']));
            ?>
        </div>
        
        <!--orden de trabajo y codigo-->
        <div style="width:100%; float:left; margin-top: 5px;"><b>Orden de Trabajo #</b>
            <?php
            echo __($arrInfoOrd['0']['Ordentrabajo']['codigo']);
            ?>
        </div>
        
        <!--lugar de prestacion del servicio-->
        <div style="width:100%; float:left; margin-top: 5px;"><b>Ubicación: </b>
            <?php
            echo __($arrInfoOrd['0']['PS']['descripcion']);
            ?>
        </div>

        <!--tecnico-->
        <div style="width:100%; float:left; margin-top: 5px;"><b>Técnico: </b>
            <?php
            echo __($arrInfoOrd['0']['US']['nombre']);
            ?>
        </div>
        
        <!--informacion de la fecha de ingreso y salida del vehiculo-->
        <div style="margin-top:0px; width:100%; float:left;">
            <div style="margin-top:5px; margin-right: 3px; float:left" align="left">
                <b>Fecha de Ingreso:</b>
            </div>
            <div style="margin-top:5px; float:left" align="left">
                <?php echo __(str_replace(" 00:00:00", "" ,$arrInfoOrd['0']['Ordentrabajo']['fecha_ingreso'])); ?>
            </div>            
            
            <div style="margin-top:5px; margin-right: 3px; margin-left:20px; float:left" align="left">
                <b>Fecha de Salida:</b>
            </div>
            <div style="margin-top:5px; float:left" align="left">
                <?php echo __(str_replace(" 00:00:00", "", $arrInfoOrd['0']['Ordentrabajo']['fecha_salida'])); ?>
            </div>  
        </div>

        <div style="width:100%; float:left; margin-top: 5px;" align="center"><h4><b><u>PARTES DEL VEHICULO</u></b></h4></div>
        <div style="width:100%; float:left;">
            <div class="table-responsive" style="width:100%; float:left;">    
                <table class="table" style="width:100%; float:left; font-family:sans-serif; font-size:15px;">
                    <thead>
                    <tr>                                            
                        <th align="left"><b>PARTE</b></th>
                        <th align="left"><b>ESTADO</b></th>
                        <th align="left"><b>PARTE</b></th>
                        <th align="left"><b>ESTADO</b></th>
                    </tr> 
                    </thead>
                    <tbody>
                        <?php $contador = 2; $k = 0;?>
                        <?php for($i = 0; $i < ceil(count($partesV)/2); $i++){?>
                        <tr>
                            <?php for($k; $k < $contador; $k++){?>
                                <?php if (isset($partesV[$k])){?>

                                    <td><b><?php echo __(strtoupper($partesV[$k]['PV']['descripcion'])); ?>: </b></td>
                                    
                                    <td><?php echo __($pEstados[$partesV[$k]['OrdentrabajosPartevehiculo']['estadoparte_id']]); ?></td>

                                <?php } ?>
                            <?php } $contador = $contador + 2;?>
                        </tr>
                        <?php } ?>                                                  
                    </tbody>
                </table>
            </div>      
        </div>
        
        <div style="width:90%; float:left; margin: 10px; font-family:sans-serif; font-size:15px;">
            <div style="margin: 5px;"><u><b>OBSERVACIONES CLIENTE</b></u></div>
            <div><small><?php echo __($arrInfoOrd['0']['Ordentrabajo']['observaciones_usuario']);?></small></div>
        </div>        
        <div style="width:90%; float:left; margin: 10px;">
            <div style="margin: 5px;"><u><b>OBSERVACIONES MECÁNICO</b></u></div>
            <div><small><?php echo __($arrInfoOrd['0']['Ordentrabajo']['observaciones_cliente']);?></small></div>
        </div>   
        
        <div style="width:100%; float:left; margin-top: 10px;" align="center"><h4><b><u>REPUESTOS/SUMINISTROS</u></b></h4></div>
        <div style="width:100%; float:left; margin: 10px;">
            <div class="table-responsive" style="width:60%; float:left; ">    
                <table class="table" style="font-family:sans-serif; font-size:15px; border-collapse: collapse; width: 60%;">
                    <thead>
                        <tr>                                            
                        <th align="left"><b>NOMBRE</b></th>
                        <th align="left"><b>CANTIDAD</b></th>
                    </tr> 
                    </thead>
                    <?php foreach ($arrSums as $sums) {?>
                    <tr>
                    <td><?php echo __($sums['P']['descripcion']);?></td>
                    <td align="right"><?php echo __($sums['OrdentrabajosSuministro']['cantidad'])?></td>
                    </tr>                                 
                    <?php } ?>
                </table>
            </div>            
        </div>        
    </div>    
    <?php } ?>
  </div><!-- class="x_panel"--> 
    </div><!-- class="container body"-->
</div><!-- class="main_container"-->


<!-- div para impresora de ticket -->
<div class="container-fluid" id="dvTicket" style="margin:0px; width:100%; font-family:sans-serif; font-size:15px;">
    <div>
        <img src="<?php 
                echo $urlImg . $infoEmpresa['Empresa']['id'] . '/' . $infoEmpresa['Empresa']['imagen'];                        
            ?>" 
                class="img-responsive img-thumbnail center-block" width="200">
    </div>     
    <?php if(!empty($infoRemision)){?>
    <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __($infoRemision['Relacionempresa']['nombre']); ?></b></div>
    <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __($infoRemision['Relacionempresa']['representantelegal']); ?></b></div>
    <?php }else{?>
    <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __($infoEmpresa['Empresa']['nombre']); ?></b></div>
    <?php }?>
    
    <?php if($infoFact['Factura']['factura']){ ?>
    <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __($nombreDocumento . ' ' . $prefijo . ' ' . $consecutivoFact) ?></b></div> 
    <input id="tipoVenta" type="hidden" value="1">
    <?php }else{?>
    <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __('DOCUMENTO DE COMPRA No. ' . $prefijo . ' ' . $consecutivoFact) ?></b></div>    
    <input id="tipoVenta" type="hidden" value="2">
    <?php }?>
    
    <!--informacion e imagen de empresa-->
    <div style="margin:0px; width:100%; float:left;">
        <div style="float:left; margin-top: 10px;" align="left">
            <div style="margin: 2px; float: left; width: 100%;">
                <div style="margin: 0px; float: left; width: 100%;">
                    <b>Nit: </b><?php 
                    if(!empty($infoRemision)){
                        echo h($infoRemision['Relacionempresa']['nit']);
                    }else
                        echo h($infoEmpresa['Empresa']['nit']);
                    ?>
                </div>          
            </div>
            
            <div style="margin: 2px; float: left; width: 100%;">
                <div style="margin: 0px; float: left; width: 100%;">
                    <b>Telefono: </b><?php 
                    if(!empty($infoRemision)){
                        echo h($infoRemision['Relacionempresa']['telefono1']);
                    }else{
                        echo h($infoEmpresa['Empresa']['telefono1'] . " / " . $infoEmpresa['Empresa']['telefono2']);
                    }
                                
                    ?>
                </div>                 
            </div>
            
            <div style="margin: 2px; float: left; width: 100%;">
                <div style="margin: 0px; float: left; width: 100%;">
                    <b>Direccion: </b><?php 
                    if(!empty($infoRemision)){
                        echo h($infoRemision['Relacionempresa']['direccion']);
                    }else{
                        echo h($infoEmpresa['Empresa']['direccion']);
                    }                                                           
                    ?>
                </div>                           
            </div>
        </div>           
    </div> 
    
    <div style="width:100%; float:left; margin-top: 5px;">
        <?php
        echo __($arrUbicacion['0']['Ciudade']['descripcion'] . ", " . $arrUbicacion['0']['P']['descripcion'] . ", " . $fechaActual);
        ?>
    </div>    
    
    <!--informacion del cliente y moto-->
    <div style="margin:0px; width:100%; float:left;">
        <div style="float:left; margin-top: 10px; width:50%" align="left">
            <div style="margin: 2px; float: left; width: 100%;">
                <div style="margin: 0px; float: left; width: 100%;">
                    <b>Cliente: </b><?php echo h(!empty($infoFact['Cliente']['nombre']) ? $infoFact['Cliente']['nombre'] : "ANONIMO"); ?>
                </div>          
            </div>
            <div style="margin: 2px; float: left; width: 100%;">
                <div style="margin: 0px; float: left; width: 100%;">
                <b>Teléfono: </b><?php echo h(!empty($infoFact['Cliente']['telefono']) ? $infoFact['Cliente']['telefono'] . " / " . $infoFact['Cliente']['celular'] : $infoFact['Cliente']['celular']); ?>
                </div>                 
            </div>
            
            <?php if(!empty($infoFact['Factura']['ordentrabajo_id'])){?>
            <div style="margin: 2px; float: left; width: 100%;">
                <div style="margin: 0px; float: left; width: 100%;">
                    <b>Motor/Placa: </b><?php echo __(strtoupper($arrVeh['Vehiculo']['placa'])); ?>
                </div>                           
            </div>
            <?php } ?>

        </div>
        
        <div style="float:right; margin-top:10px; width:50%" align="left">
            <div style="margin: 2px; float: left; width: 100%;">
                <div style="margin: 0px; float: left; width: 100%;">
                    <b>Identificacion: </b><?php echo h(!empty($infoFact['Cliente']['nit']) ? $infoFact['Cliente']['nit'] : "N/A"); ?>
                </div>          
            </div>
            
            <div style="margin: 2px; float: left; width: 100%;">
                <div style="margin: 0px; float: left; width: 100%;">
                    <b>Direccion: </b><?php echo $infoFact['Cliente']['direccion']; ?>
                </div>                 
            </div>
            
            <?php if(!empty($infoFact['Factura']['ordentrabajo_id'])){ ?>
            <div style="margin: 2px; float: left; width: 100%;">
                <div style="margin: 0px; float: left; width: 100%;">
                    <b>Linea: </b><?php echo __(strtoupper($arrVeh['Vehiculo']['linea'])); ?>
                </div>                           
            </div>
            <?php } ?>
        </div>            
    </div> 
    <div style="margin: 2px; float: left; width: 100%;">
        <div style="margin: 0px; float: left; width: 100%;">
            <b>Vendedor: </b><?php echo $infoFact['Usuario']['nombre']?>
        </div>                           
    </div>
    <div style="margin: 0px; float: left; width: 100%;">
        <b>Método de Pago: </b> <br>
        <?php if(count($factCV) > 0 ){ ?>
            <?php foreach($factCV as $fvcv):?>
                <?php echo h($fvcv['T']['descripcion'] . ": $ " . number_format($fvcv['FacturaCuentaValore']['valor'], 0)); ?>&nbsp;<br>
            <?php endforeach; ?>                             
        <?php } ?>

        <?php if(count($factAbonos) > 0 ){ ?>
            <?php foreach($factAbonos as $fab):?>
                <?php echo h($fab['TP']['descripcion'] . ": $ " . number_format($fab['Abonofactura']['valor'], 0)); ?>&nbsp;<br>
            <?php endforeach; ?>
        <?php } ?>                                  
    </div>      

    <div style="width:100%;">
            <table style="font-size:12px; width: 70%;">
                <?php 
                    if(!$infoFact['Factura']['factura']){
                ?>
                <tr>
                                <th class="text-left"><?php echo ('#'); ?></th>
                                <th class="text-left"><?php echo ('Desc'); ?></th>                                
                                <th class="text-right"><?php echo ('Vlr.Unit'); ?></th>
                                <th class="text-right"><?php echo ('Subtotal'); ?></th>
                </tr>
                    <?php }else{?>
                <tr>
                                <th class="text-left"><?php echo ('#'); ?></th>
                                <th class="text-left"><?php echo ('Desc'); ?></th>                                
                                <th class="text-right"><?php echo ('Vlr.Unit'); ?></th>                                                     
                                <th class="text-right"><?php echo ('Subtotal'); ?></th>
                </tr>                
                    <?php }                
                
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
                        <td><?php echo h($DetFact['P']['descripcion']); ?>&nbsp;</td>                    
                        <td  align="right"><?php echo h("$" . number_format($costoVenta,0)); ?>&nbsp;</td>
                        <td  align="right"><?php echo h("$" . number_format(($valorXCantidad),0)); ?>&nbsp;</td>
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
                        <td><?php echo h($imp . $DetFact['P']['descripcion']); ?>&nbsp;</td>                    
                        <td  align="right"><?php echo h("$" . number_format($costoBase,0)); ?>&nbsp;</td>
                        <td  align="right"><?php echo h("$" . number_format(($costoBase * $DetFact['Facturasdetalle']['cantidad']),0)); ?>&nbsp;</td>

                    </tr>    
                    
                <?php                          
                    }                                
                    $subTtalVenta += $valorXCantidad; 
                    $ttalIVA += $valorIVA;
                    $ttalDtto += $descuento;                    
                endforeach; ?> 
                    
                <?php if($infoFact['Factura']['factura']){ ?>
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td  align="right"><b><?php echo($serviceName);?></b></td>
                    <td  align="right"><b><?php echo "$ " . number_format(($ttalServ),0); ?></td></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td  align="right"><b><?php echo($productName);?></b></td>
                    <td  align="right"><b><?php echo "$ " . number_format(($ttalRep),0); ?></td></td>
                </tr>

                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td  align="right"><b>Subtotal</b></td>
                    <td  align="right"><b><?php echo "$" . number_format(($subTtalVenta),0); ?></td></td>
                </tr>
                <?php if(!empty($ttalDtto)){ ?>
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td  align="right"><b>Dcto</b></td>
                    <td  align="right"><b>(<?php echo ("$". number_format(($ttalDtto),0));?>)</b></td>
                </tr>                 
                <?php } ?>                
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td  align="right"><b>Subttal con Dcto.</b></td>
                    <td  align="right"><b><?php echo ("$". number_format((ceil($subTtalVenta - $ttalDtto)),0));?></b></td>
                </tr>                 
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td  align="right"><b>IVA</b></td>
                    <td  align="right"><b><?php echo ("$". number_format($ttalIVA,0));?></b></td>
                </tr>                    
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td  align="right"><b>Reteica</b></td>
                    <td  align="right"><b>0%</b></td>
                </tr>                    
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td  align="right"><b>Retefuente</b></td>
                    <td  align="right"><b>0%</b></td>
                </tr> 
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td  align="right"><b>TOTAL</b></td>
                    <td  align="right"><b><?php echo ("$". number_format((ceil($subTtalVenta - $ttalDtto) + $ttalIVA),0));?></b></td>
                </tr>                
                <?php }else{ ?>
                    <tr>
                    <td colspan="2">&nbsp;</td>
                    <td  align="right"><b><?php echo($serviceName);?></b></td>
                    <td  align="right"><b><?php echo "$ " . number_format(($ttalServ),2); ?></td></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td  align="right"><b><?php echo($productName);?></b></td>
                    <td  align="right"><b><?php echo "$ " . number_format(($ttalRep),2); ?></td></td>
                </tr>

                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td  align="right"><b>Subttal</b></td>
                    <td  align="right"><b><?php echo ("$". number_format(($subTtalVenta),0));?></b></td>
                </tr>                
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td  align="right"><b>Dcto</b></td>
                    <td  align="right"><b><?php echo ("$". number_format(($ttalDtto),0));?></b></td>
                </tr>                
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td  align="right"><b>TOTAL</b></td>
                    <td  align="right"><b><?php echo ("$". number_format(ceil($subTtalVenta - $ttalDtto),0));?></b></td>
                </tr>                
                <?php } ?>

            </table> 
        </div>

    <?php if(count($notaFactura) > '0'){?>        
        <div align="center">
            <?php foreach ($notaFactura as $nF): ?>
            <div>
                <?php echo h($nF['Notafactura']['descripcion']); ?>
                &nbsp;
            </div>
            <?php endforeach;?>
        </div>
    <?php }?>    


    <?php if($infoFact['Factura']['observacion'] != ""){?>
        <div id="dvNota">
            <div id='nota_factura' align="center">
                <small>
                <br><br><b>NOTA:</b><?php echo $infoFact['Factura']['observacion'];?>
                </small>
                <small>
                <br><br><b>Miggo Solutions S.A.S</b>
                </small>
            </div>
        </div> 
    <?php } else { ?>
        <div id="dvNota">
            <div id='nota_factura' align="center">
                <small>
                <br><br><b>Miggo Solutions S.A.S</b>
                </small>
            </div>
        </div>  
    <?php } ?>  
</div>


