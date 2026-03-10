<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('facturas/gestionfacturas.js'));?>
<div class="container body">
<div class="main_container">

<br>
  <div class="x_panel"> 
    <input type="hidden" id="facturaId" value="<?php echo $infoFact['Factura']['id'];?>">
    <input type="hidden" id="cliName" value="<?php echo $infoFact['Cliente']['nombre'];?>">
    <input type="hidden" id="cliNit" value="<?php echo $infoFact['Cliente']['nit'];?>">
    <input type="hidden" id="dianQRStr" value="<?php echo $infoFact['Factura']['dianQRStr'];?>">
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
            <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __("NIT: " . $infoRemision['Relacionempresa']['nit']); ?></b></div>
            <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __($infoRemision['Relacionempresa']['direccion']); ?></b></div>
            <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __($arrUbicacion['0']['Ciudade']['descripcion'] . ", " . $arrUbicacion['0']['P']['descripcion']); ?></b></div>
            <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __($infoRemision['Relacionempresa']['telefono1']); ?></b></div>
        <?php }else{?>
        <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __($infoEmpresa['Empresa']['nombre']); ?></b></div>

        <div id="infoLegal" style="margin:0px; width:100%; font-family:sans-serif; font-size:8px; float:center;" align="center">
            <div>NIT: <?php echo h($infoEmpresa['Empresa']['nit'] . " - " . $infoEmpresa['Empresa']['texto1']); ?></div>
            <div><?php echo h($infoResolucion['Resolucionfactura']['nombredocumento']); ?>  <?php echo h($infoResolucion['Resolucionfactura']['resolucion']); ?></div>
            <div>
                de <?php echo h($infoResolucion['Resolucionfactura']['fechainicio'] . ", Prefijo: " . $infoFact['Factura']['prefijo'] . ", Rango " . $infoResolucion['Resolucionfactura']['resolucioninicia'] . " al " . $infoResolucion['Resolucionfactura']['resolucionfin']); ?> -
                Vigencia Desde: <?php echo h($infoResolucion['Resolucionfactura']['fechainicio'] . " Hasta: " . $infoResolucion['Resolucionfactura']['fechafin'])?>
            </div>
            <div>REPRESENTACIÓN GRÁFICA DE FACTURA ELECTRÓNICA</div>
            <div><?php echo h($infoEmpresa['Empresa']['direccion'] . " - " . $arrUbicacion['0']['Ciudade']['descripcion'] . " - " . $arrUbicacion['0']['P']['descripcion'] . " - " . $infoEmpresa['Empresa']['telefono1']);?></div>
            <div>E-mail: <?php echo h($infoEmpresa['Empresa']['email']);?></div>
        </div>

        <?php }?>
       
        <?php if($infoFact['Factura']['factura']){ ?>
        <div style="width:100%; float:left; margin:0px; margin-top:10px;" align="center">
            <div><b><?php echo __($infoResolucion['Resolucionfactura']['nombredocumento'] . ' ' . $infoFact['Factura']['prefijo'] . ' ' . $consecutivoFact) ?></b></div> 
            <div> <?php echo __($arrUbicacion['0']['Ciudade']['descripcion'] . ", " . $arrUbicacion['0']['P']['descripcion'] . ", " . $fechaActual); ?> Emitido a la DIAN </div>
        </div>
        <input id="tipoVenta" type="hidden" value="1">
        <?php }else{?>
        <div style="width:100%; float:left; margin:0px" align="center"><b><?php echo __($infoResolucion['Resolucionfactura']['nombredocumento'] . ' ' . $infoFact['Factura']['prefijo'] . ' ' . $consecutivoFact) ?></b></div>  
        <div style="width:100%; float:left; margin:0px" align="center"><?php echo __($infoEmpresa['Empresa']['texto1']); ?></div>
        <div style="width:100%; float:left; margin:0px" align="center"> <?php echo __($arrUbicacion['0']['Ciudade']['descripcion'] . ", " . $arrUbicacion['0']['P']['descripcion'] . ", " . $fechaActual); ?></div>  
        <input id="tipoVenta" type="hidden" value="2">
        <?php }?>
        
        <!--informacion e imagen de empresa-->
        <?php if(!$infoFact['Factura']['factura']){ ?>
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
        <?php } ?>
        
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
            
            <div style="float:left; margin-top: 0px; width:50%" align="left">
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Email: </b><?php echo h(!empty($infoFact['Cliente']['email']) ? $infoFact['Cliente']['email'] : "N/A"); ?>
                    </div>          
                </div>
            </div>            
        </div> 

        <div style="margin: 2px; float: left; width: 100%;">
            <div style="margin: 0px; float: left; width: 50%;">
                <b>Método(s) de Pago: </b> <br>
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
                <?php if(count($factCredit) > 0 ){ ?>
                    <?php echo h($factCredit['0']['TP']['descripcion'] . ": $ " . number_format($factCredit['0']['Cuentascliente']['totalobligacion'], 2)); ?>&nbsp;<br>
                <?php } ?>                                  
            </div>                           

            <div style="margin: 0px; float: left; width: 50%;">
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Fecha Orden: </b><?php echo h(!empty($infoFact['Factura']['fechaorden']) ? $infoFact['Factura']['fechaorden'] : "N/A"); ?>
                    </div>          
                </div>
                
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Prefijo - No. Orden: </b><?php echo h(!empty($infoFact['Factura']['numeroorden']) ? $infoFact['Factura']['numeroorden'] : "N/A"); ?>
                    </div>                 
                </div>                                 
            </div>                           

                       
        </div>
        

        <!-- Inicio desarrollo para facturas -->
        <?php if($infoFact['Factura']['factura']) { ?>
        <div style="width:100%; float:left; margin-top: 20px;">
            <table class="table" style="font-family:sans-serif; font-size:15px; border-collapse: collapse; width: 100%;">
                <tr>
                    <th class="text-left"><?php echo ('#'); ?></th>
                    <th class="text-left"><?php echo ('Nombre'); ?></th>                                
                    <th class="text-left"><?php echo ('Código'); ?></th>                              
                    <th class="text-left"><?php echo ('Cant.'); ?></th>
                    <th class="text-right"><?php echo ('Precio unitario'); ?></th>                              
                    <th class="text-right"><?php echo ('Descuento'); ?></th> 
                    <th class="text-right"><?php echo ('IVA'); ?></th>
                    <th class="text-right"><?php echo ('INC'); ?></th>
                    <th class="text-right"><?php echo ('INC bolsa'); ?></th>                         
                    <th class="text-right"><?php echo ('Total línea'); ?></th>
                </tr>   

                <!-- variables -->
                <?php 
                 $contador = 1;
                 $subtotalServ = 0;
                 $subtotalProd = 0;
                 $descuento = 0;
                 $IVA = 0;
                 $INC = 0;
                 $INCBolsa = 0;
                 $totalFactura = 0;
                ?>

                <?php foreach ($infoDetFact as $DetFact): ?>

                    <tr>
                        <td><?php echo h( $contador ); ?></td>
                        <td><?php echo h( $DetFact['P']['descripcion'] ); ?></td>   
                        <td><?php echo h( $DetFact['P']['codigo'] ); ?></td>                        
                        <td><?php echo h( ( $DetFact['Facturasdetalle']['cantidad'] ) ); ?></td>
                        <td  align="right"><?php echo (number_format( ($DetFact['valoresBase']['valorBaseUnitario'] + $DetFact['valoresBase']['descuento']) / $DetFact['Facturasdetalle']['cantidad'], 2 )); ?></td>
                        <td  align="right"><?php echo (number_format( $DetFact['valoresBase']['descuento'], 2 ) . ' (' . number_format( $DetFact['Facturasdetalle']['porcentaje'], 2 ) . '%)' ); ?></td>
                        <td  align="right"><?php echo (number_format( $DetFact['valoresBase']['valorIVA'], 2 ) . ' (' . number_format( $DetFact['Facturasdetalle']['impuesto'], 2 ) . '%)' ); ?></td>
                        <td  align="right"><?php echo (number_format( $DetFact['valoresBase']['valorINC'], 2 ) . ' (' . number_format( $DetFact['Facturasdetalle']['impoconsumo'], 2 ) . '%)' ); ?></td>
                        <td  align="right"><?php echo (number_format( $DetFact['valoresBase']['varorINCBolsa'] / $DetFact['Facturasdetalle']['cantidad'], 2 )); ?></td>
                        <td  align="right"><?php echo (number_format( $DetFact['valoresBase']['precioUnitarioFinal'], 2 )); ?></td>                    
                    </tr>

                    <?php  
                        $contador++;
                        
                        if($DetFact['C']['servicio'] == '1'){
                            $subtotalServ += ($DetFact['valoresBase']['valorBaseUnitario'] + $DetFact['valoresBase']['descuento']);
                        } else {
                            $subtotalProd += ($DetFact['valoresBase']['valorBaseUnitario'] + $DetFact['valoresBase']['descuento']);
                        }

                        $descuento += $DetFact['valoresBase']['descuento'];
                        $IVA += $DetFact['valoresBase']['valorIVA'];
                        $INC += $DetFact['valoresBase']['valorINC'];
                        $INCBolsa += $DetFact['valoresBase']['varorINCBolsa'];
                        $totalFactura += $DetFact['valoresBase']['precioUnitarioFinal'] + $DetFact['valoresBase']['varorINCBolsa'];
                            
                    ?>
                <?php endforeach; ?>

                <tr>
                    <td  align="right" colspan="9">Subtotal servicio</td>
                    <td  align="right"><?php echo number_format($subtotalServ,2); ?></td></td>
                </tr>
                <tr>
                    <td  align="right" colspan="9">Subtotal producto</td>
                    <td  align="right"><?php echo number_format($subtotalProd,2); ?></td></td>
                </tr>
                <tr>
                    <td  align="right" colspan="9"><b>Subtotal</b></td>
                    <td  align="right"><b><?php echo number_format(($subtotalServ+$subtotalProd),2); ?></b></td></td>
                </tr>
                <tr>
                    <td  align="right" colspan="9">Descuento</td>
                    <td  align="right"><?php echo number_format($descuento,2); ?></td></td>
                </tr>
                <tr>
                    <td  align="right" colspan="9"><b>Total Bruto Factura</b></td>
                    <td  align="right"><b><?php echo number_format($subtotalServ+$subtotalProd-$descuento,2); ?></b></td></td>
                </tr>
                <tr>
                    <td  align="right" colspan="9">IVA</td>
                    <td  align="right"><?php echo number_format($IVA,2); ?></td></td>
                </tr>
                <tr>
                    <td  align="right" colspan="9">INC</td>
                    <td  align="right"><?php echo number_format($INC,2); ?></td></td>
                </tr>
                <tr>
                    <td  align="right" colspan="9">Bolsas</td>
                    <td  align="right"><?php echo number_format($INCBolsa,2); ?></td></td>
                </tr>
                <tr>
                    <td  align="right" colspan="9"><b>Total impuestos</b></td>
                    <td  align="right"><b><?php echo number_format($IVA+$INC+$INCBolsa,2); ?></b></td></td>
                </tr>
                <tr>
                    <td  align="right" colspan="9"><b>Total factura</b></td>
                    <td  align="right"><b><?php echo number_format($totalFactura,2); ?></b></td></td>
                </tr>

            </table>
        </div>
        <?php } else {  ?>
        <!-- Fin desarrollo para Facturas -->


        <!-- Inicio desarrollo para documentos de venta -->
            <div style="width:100%; float:left; margin-top: 20px;">
                <table class="table" style="font-family:sans-serif; font-size:15px; border-collapse: collapse; width: 100%;">

                <tr>
                                <th class="text-left"><?php echo ('#'); ?></th>
                                <th class="text-left"><?php echo ('Nombre'); ?></th>                                
                                <th class="text-left"><?php echo ('Código'); ?></th>
                                <th class="text-left"><?php echo ('Cant.'); ?></th>
                                <th class="text-right"><?php echo ('Precio unitario'); ?></th>
                                <th class="text-right"><?php echo ('Descuento'); ?></th>
                                <th class="text-right"><?php echo ('Total línea'); ?></th>
                </tr>

                <!-- variables -->
                <?php 
                 $contador = 1;
                 $subtotalServ = 0;
                 $subtotalProd = 0;
                 $descuento = 0;
                 $totalFactura = 0;
                ?>

                <?php foreach ($infoDetFact as $DetFact): ?>

                    <tr>
                        <td><?php echo h( $contador ); ?></td>
                        <td><?php echo h( $DetFact['P']['descripcion'] ); ?></td>   
                        <td><?php echo h( $DetFact['P']['codigo'] ); ?></td>                        
                        <td><?php echo h( ( $DetFact['Facturasdetalle']['cantidad'] ) ); ?></td>
                        <td  align="right"><?php echo (number_format( ($DetFact['valoresBase']['valorBaseUnitario'] + $DetFact['valoresBase']['descuento']) / $DetFact['Facturasdetalle']['cantidad'], 2 )); ?></td>
                        <td  align="right"><?php echo (number_format( $DetFact['valoresBase']['descuento'], 2 ) . ' (' . number_format( $DetFact['Facturasdetalle']['porcentaje'], 2 ) . '%)' ); ?></td>
                        <td  align="right"><?php echo (number_format( $DetFact['valoresBase']['precioUnitarioFinal'], 2 )); ?></td>                    
                    </tr>

                    <?php  
                        $contador++;
                        
                        if($DetFact['C']['servicio'] == '1'){
                            $subtotalServ += ($DetFact['valoresBase']['valorBaseUnitario'] + $DetFact['valoresBase']['descuento']);
                        } else {
                            $subtotalProd += ($DetFact['valoresBase']['valorBaseUnitario'] + $DetFact['valoresBase']['descuento']);
                        }

                        $descuento += $DetFact['valoresBase']['descuento'];
                        $totalFactura += $DetFact['valoresBase']['precioUnitarioFinal'] + $DetFact['valoresBase']['varorINCBolsa'];
                            
                    ?>
                <?php endforeach; ?>

                <tr>
                    <td  align="right" colspan="6"><?php echo($serviceName);?></td>
                    <td  align="right"><?php echo "$ " . number_format(($subtotalServ),2); ?></td></td>
                </tr>
                <tr>                
                    <td  align="right" colspan="6"><?php echo($productName);?></td>
                    <td  align="right"><?php echo "$ " . number_format(($subtotalProd),2); ?></td></td>
                </tr>

                <tr>
                    <td  align="right" colspan="6"><b>Subtotal</b></td>
                    <td  align="right"><b><?php echo ("$ ". number_format(($subtotalServ+$subtotalProd),2));?></b></td>
                </tr>                
                <tr>
                    <td  align="right" colspan="6">Descuento</td>
                    <td  align="right"><?php echo ("$ ". number_format(($descuento),2));?></td>
                </tr>                
                <tr>
                    <td  align="right" colspan="6"><b>TOTAL</b></td>
                    <td  align="right"><b><?php echo ("$ ". number_format($totalFactura,2));?></b></td>
                </tr> 

                </table>
            </div>
        <?php } ?>
        <!-- Fin desarrollo para documentos de venta -->

        <div id="dvNota" align="center" style="margin-top:10px;">

            <?php if($infoFact['Factura']['diancufe']) { ?>
                <div align="center">
                    <small>
                    <div><?php echo('CUFE ' . $infoFact['Factura']['diancufe']); ?></div>
                    </small>
                </div>

                <div style="margin-top:10px;" align="center" class="qr_imp"></div>
            <?php } ?>

            <?php if($infoFact['Factura']['observacion'] != "") {?>
                <div style="margin-top:10px;" align="center">
                    <small>
                    <b>Nota: </b><?php echo $infoFact['Factura']['observacion'];?>
                    
                </div>
            <?php } ?>

            <?php if(count($notaFactura) > '0'){?>        
                <div style="margin-top:10px;" align="center">
                    <small>
                        <?php foreach ($notaFactura as $nF): ?>
                            <small><?php echo h($nF['Notafactura']['descripcion']); ?></small>
                        <?php endforeach;?>
                    </small>
                </div>
            <?php }?>

            <div class="linea-ticket" style="margin-top: 15px; text-align: center; font-size: 9px; padding-top: 5px;">
                Software de facturación suministrado por Miggo Solutions S.A.S NIT 901629169<br>
                Modalidad software adquirido desarrollador habilitado por la DIAN según concepto 013246 de 2025<br>
                www.miggo.com.co linea de servicio al cliente 3116871320.
            </div>

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
            echo __(number_format($arrInfoOrd['0']['Ordentrabajo']['kilometraje'], 4, '.', ','));
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
                        <?php for($i = 0; $i < (count($partesV)/2); $i++){?>
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
<div class="container-fluid" id="dvTicket" style="margin:0px; width:100%; font-family:sans-serif; font-size:12px; line-height: 1.2; color:#000;">
    
    <div style="width:100%; text-align:center; margin-bottom: 5px;">
        <img src="<?php echo $urlImg . $infoEmpresa['Empresa']['id'] . '/' . $infoEmpresa['Empresa']['imagen']; ?>" 
             style="max-width: 180px; height: auto;">
    </div>     

    <div style="text-align:center; margin-bottom: 10px;">
        <?php if(!empty($infoRemision)){ ?>
            <b><?php echo __($infoRemision['Relacionempresa']['nombre']); ?></b><br>
            <b><?php echo __($infoRemision['Relacionempresa']['representantelegal']); ?></b><br>
            <b>NIT: <?php echo h($infoRemision['Relacionempresa']['nit']); ?></b><br>
            <?php echo h($infoRemision['Relacionempresa']['direccion']); ?><br>
            <?php echo h($arrUbicacion['0']['Ciudade']['descripcion'] . ", " . $arrUbicacion['0']['P']['descripcion']); ?><br>
            <?php echo h($infoRemision['Relacionempresa']['telefono1']); ?>
        <?php } else { ?>
            <b><?php echo h($infoEmpresa['Empresa']['nombre']); ?></b><br>
            <div style="font-size: 11px;">
                NIT: <?php echo h($infoEmpresa['Empresa']['nit'] . " - " . $infoEmpresa['Empresa']['texto1']); ?><br>
                Resolución No. <?php echo h($infoResolucion['Resolucionfactura']['resolucion']); ?><br>
                de <?php echo h($infoResolucion['Resolucionfactura']['fechainicio']); ?><br>
                Prefijo: <?php echo h($infoFact['Factura']['prefijo']); ?>, Rango <?php echo h($infoResolucion['Resolucionfactura']['resolucioninicia'] . " al " . $infoResolucion['Resolucionfactura']['resolucionfin']); ?><br>
                Vigencia: <?php echo h($infoResolucion['Resolucionfactura']['fechainicio'] . " Hasta: " . $infoResolucion['Resolucionfactura']['fechafin']); ?><br>
                <b>REPRESENTACIÓN GRÁFICA DE FACTURA ELECTRÓNICA</b><br>
                <?php echo h($infoEmpresa['Empresa']['direccion'] . " - " . $arrUbicacion['0']['Ciudade']['descripcion']); ?><br>
                Tel: <?php echo h($infoEmpresa['Empresa']['telefono1']); ?> | E-mail: <?php echo h($infoEmpresa['Empresa']['email']); ?>
            </div>
        <?php } ?>
    </div>

    <div style="border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 5px 0; text-align:center;">
        <b><?php echo __($infoResolucion['Resolucionfactura']['nombredocumento'] . ' ' . $infoFact['Factura']['prefijo'] . ' ' . $consecutivoFact) ?></b><br>
        <span style="font-size: 10px;"><?php echo __($arrUbicacion['0']['Ciudade']['descripcion'] . ", " . $fechaActual); ?></span>
        <?php if($infoFact['Factura']['factura']){ echo "<br>Emitido a la DIAN"; } ?>
    </div>

    <div style="margin-top: 10px; width:100%;">
        <b>Cliente:</b> <?php echo h(!empty($infoFact['Cliente']['nombre']) ? $infoFact['Cliente']['nombre'] : "ANONIMO"); ?><br>
        <b>CC/NIT:</b> <?php echo h(!empty($infoFact['Cliente']['nit']) ? $infoFact['Cliente']['nit'] : "N/A"); ?><br>
        <b>Tel:</b> <?php echo h(!empty($infoFact['Cliente']['telefono']) ? $infoFact['Cliente']['telefono'] : $infoFact['Cliente']['celular']); ?><br>
        <b>Dir:</b> <?php echo h($infoFact['Cliente']['direccion']); ?><br>

        <?php if(!empty($infoFact['Factura']['ordentrabajo_id'])){ ?>
            <div style="border-top: 1px dotted #ccc; margin-top: 5px; padding-top: 5px;">
                <b>Placa:</b> <?php echo h(strtoupper($arrVeh['Vehiculo']['placa'])); ?> | 
                <b>Línea:</b> <?php echo h(strtoupper($arrVeh['Vehiculo']['linea'])); ?>
            </div>
        <?php } ?>
        
        <b>Orden:</b> <?php echo h($infoFact['Factura']['numeroorden']); ?> | 
        <b>Fecha:</b> <?php echo h($infoFact['Factura']['fechaorden']); ?><br>
        <b>Vendedor:</b> <?php echo h($infoFact['Usuario']['nombre']); ?>
    </div>

    <div style="margin-top: 5px; font-size: 11px;">
        <b>Método(s) de Pago:</b><br>
        <?php if(count($factCV) > 0 ){ foreach($factCV as $fvcv): ?>
            - <?php echo h($fvcv['T']['descripcion'] . ": $" . number_format($fvcv['FacturaCuentaValore']['valor'], 2)); ?><br>
        <?php endforeach; } ?>

        <?php if(count($factAbonos) > 0 ){ foreach($factAbonos as $fab): ?>
            - <?php echo h($fab['TP']['descripcion'] . ": $" . number_format($fab['Abonofactura']['valor'], 2)); ?><br>
        <?php endforeach; } ?>

        <?php if(count($factCredit) > 0 ){ ?>
            - <?php echo h($factCredit['0']['TP']['descripcion'] . ": $" . number_format($factCredit['0']['Cuentascliente']['totalobligacion'], 2)); ?><br>
        <?php } ?>
    </div>

    <table style="width:100%; margin-top: 10px; border-collapse: collapse; font-size: 11px;">
        <thead>
            <tr style="border-bottom: 1px solid #000;">
                <th align="left">DESCRIPCIÓN</th>
                <th align="center">CANT</th>
                <th align="right">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $subtotalServ = 0; $subtotalProd = 0; $descuento = 0;
                $IVA = 0; $INC = 0; $INCBolsa = 0; $totalFactura = 0;
            ?>
            <?php foreach ($infoDetFact as $DetFact): ?>
                <tr>
                    <td colspan="3" style="padding-top: 5px;"><?php echo h($DetFact['P']['descripcion']); ?></td>
                </tr>
                <tr style="border-bottom: 1px dotted #eee;">
                    <td><?php echo h($DetFact['P']['codigo']); ?></td>
                    <td align="center"><?php echo h($DetFact['Facturasdetalle']['cantidad']); ?></td>
                    <td align="right"><?php echo number_format($DetFact['valoresBase']['precioUnitarioFinal'], 2); ?></td>
                </tr>
                <?php
                    if($DetFact['C']['servicio'] == '1'){
                        $subtotalServ += ($DetFact['valoresBase']['valorBaseUnitario'] + $DetFact['valoresBase']['descuento']);
                    } else {
                        $subtotalProd += ($DetFact['valoresBase']['valorBaseUnitario'] + $DetFact['valoresBase']['descuento']);
                    }
                    $descuento += $DetFact['valoresBase']['descuento'];
                    $IVA += $DetFact['valoresBase']['valorIVA'];
                    $INC += $DetFact['valoresBase']['valorINC'];
                    $INCBolsa += $DetFact['valoresBase']['varorINCBolsa'];
                    $totalFactura += $DetFact['valoresBase']['precioUnitarioFinal'] + $DetFact['valoresBase']['varorINCBolsa'];
                ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 10px; border-top: 1px dashed #000; padding-top: 5px;">
        <table style="width: 100%; font-size: 12px;">
            <tr><td align="right">Subtotal Serv:</td><td align="right"><?php echo number_format($subtotalServ, 2); ?></td></tr>
            <tr><td align="right">Subtotal Prod:</td><td align="right"><?php echo number_format($subtotalProd, 2); ?></td></tr>
            <tr><td align="right"><b>Subtotal:</b></td><td align="right"><b><?php echo number_format($subtotalServ+$subtotalProd, 2); ?></b></td></tr>
            <tr><td align="right">Descuento:</td><td align="right"><?php echo number_format($descuento, 2); ?></td></tr>
            <tr><td align="right"><b>Total Bruto:</b></td><td align="right"><b><?php echo number_format($subtotalServ+$subtotalProd-$descuento, 2); ?></b></td></tr>
            <tr><td align="right">IVA:</td><td align="right"><?php echo number_format($IVA, 2); ?></td></tr>
            <tr><td align="right">INC:</td><td align="right"><?php echo number_format($INC, 2); ?></td></tr>
            <tr><td align="right">Bolsas:</td><td align="right"><?php echo number_format($INCBolsa, 2); ?></td></tr>
            <tr><td align="right"><b>Total Impuestos:</b></td><td align="right"><b><?php echo number_format($IVA+$INC+$INCBolsa, 2); ?></b></td></tr>
            <tr style="font-size: 14px;">
                <td align="right"><b>TOTAL FACTURA:</b></td>
                <td align="right"><b>$<?php echo number_format($totalFactura, 2); ?></b></td>
            </tr>
        </table>
    </div>

</div>



