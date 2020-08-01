<?php $this->layout='inicio'; ?>
<?php echo ($this->Html->script('facturas/gestionfacturas.js'));?>
<div class="container body">
<div class="main_container">
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
        <div class="col-md-1" >
            <button id="butImprimirFact" class="btn btn-primary hidden-print" onclick="imprimirFactura();">Imprimir</button>
        </div>
        <div class="col-md-5">
            <?php if(!empty($infoFact['Cliente']['celular'])){?>        
            <div class="row">
                <a href="https://wa.me/57<?php echo $infoFact['Cliente']['celular']; ?>?text=Somos%20el%20%23equipotorque%2c%20adjuntamos%20información%20de%20su%20interés" target="_blank">
                    <img src="<?php echo $urlImgWP; ?>" class="img-responsive" width="35">            
                </a>
            </div>
            <?php }else{ ?>     
            <div class="alert alert-danger" role="alert" style="margin-top: 15px;">
                  El usuario no tiene un número celular registrado.
                </div>    
            <?php } ?>
        </div>
    </div>
    <?php echo $this->Form->input('menuvert', array('type' => 'hidden', 'value' => '33', 'id' => 'menuvert'))?> 
    
    <div id="dvFacturas" style="margin:0px; width:100%; font-family:sans-serif; font-size:15px;">    
        <?php if(!empty($infoRemision)){?>
        <div style="width:100%; float:left; margin:0px" align="center"><h4><b><?php echo __($infoRemision['Relacionempresa']['nombre']); ?></b></h4></div>
        <div style="width:100%; float:left; margin:0px" align="center"><h4><b><?php echo __($infoRemision['Relacionempresa']['representantelegal']); ?></b></h4></div>
        <?php }else{?>
        <div style="width:100%; float:left; margin:0px" align="center"><h4><b><?php echo __($infoEmpresa['Empresa']['nombre']); ?></b></h4></div>
        <?php }?>
       
        <?php if($infoFact['Factura']['factura']){ ?>
        <div style="width:100%; float:left; margin:0px" align="center"><h4><b><?php echo __('FACTURA DE VENTA No. ' . $consecutivoFact) ?></b></h4></div> 
        <input id="tipoVenta" type="hidden" value="1">
        <?php }else{?>
        <div style="width:100%; float:left; margin:0px" align="center"><h4><b><?php echo __('DOCUMENTO EQUIVALENTE No. ' . $consecutivoFact) ?></b></h4></div>    
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
            <div style="float:right; margin-right:30px;">
                <img src="<?php 
                        echo $urlImg . $infoEmpresa['Empresa']['id'] . '/' . $infoEmpresa['Empresa']['imagen'];                        
                    ?>" 
                     class="img-responsive img-thumbnail center-block" width="200">
            </div>            
        </div> 
        
        <div style="width:100%; float:left; margin-top: 5px;">
            <?php
            echo __($arrUbicacion['0']['Ciudade']['descripcion'] . ", " . $arrUbicacion['0']['P']['descripcion'] . ", " . $fechaActual);
            ?>
        </div>    
        
        <?php if($infoFact['Factura']['factura']){ ?>
        <div style="width:100%; float:left; margin-top: 5px;">IVA REGIMEN COMUN</div>         
        <div style="width:100%; float:left; margin: 0px;">Código de Actividad Económica 4541 Tarifa I.C.A.: 7.7 x MIL</div>         
        <div style="width:100%; float:left; margin: 0px;">No somos Grandes Contribuyentes</div>
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
                        <b>Teléfono: </b><?php echo $infoFact['Cliente']['telefono'] . " / " . $infoFact['Cliente']['celular']; ?>
                    </div>                 
                </div>
                
                <?php if(!empty($infoFact['Factura']['ordentrabajo_id'])){?>
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Moto/Placa: </b><?php echo __(strtoupper($arrVeh['Vehiculo']['placa'])); ?>
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
                        <b>Dirección: </b><?php $infoFact['Cliente']['direccion']; ?>
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
                <b>Método de Pago: </b><?php if(isset($infoTipoPago['Tipopago'])){echo __($infoTipoPago['Tipopago']['descripcion']);}?>
            </div>                           
        </div>

        <div style="width:100%; float:left; margin-top: 20px;">
            <table class="table" style="font-family:sans-serif; font-size:15px; border-collapse: collapse; width: 100%;">
                <?php 
                    if(!$infoFact['Factura']['factura']){
                ?>
                <tr>
                                <th class="text-left"><?php echo ('Cant'); ?></th>
                                <th class="text-left"><?php echo ('Descripcion'); ?></th>                                
                                <th class="text-right"><?php echo ('Vlr. Unit'); ?></th>
                                <th class="text-right"><?php echo ('% Dcto.'); ?></th>
                                <th class="text-right"><?php echo ('Subtotal'); ?></th>
                </tr>
                    <?php }else{?>
                <tr>
                                <th class="text-left"><?php echo ('Cant'); ?></th>
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
                    <td colspan="3">&nbsp;</td>
                    <td  align="right"><b>Subtotal</b></td>
                    <td  align="right"><b><?php echo "$ " . number_format(($subTtalVenta),2); ?></td></td>
                </tr>
                <?php if(!empty($ttalDtto)){ ?>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td  align="right"><b>Descuento</b></td>
                    <td  align="right"><b>(<?php echo ("$ ". number_format(($ttalDtto),2));?>)</b></td>
                </tr>                 
                <?php } ?>                
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td  align="right"><b>Subtotal con Dcto.</b></td>
                    <td  align="right"><b><?php echo ("$ ". number_format((ceil($subTtalVenta - $ttalDtto)),2));?></b></td>
                </tr>                 
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td  align="right"><b>IVA</b></td>
                    <td  align="right"><b><?php echo ("$ ". number_format($ttalIVA,2));?></b></td>
                </tr>                    
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td  align="right"><b>Reteica</b></td>
                    <td  align="right"><b>0%</b></td>
                </tr>                    
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td  align="right"><b>Retefuente</b></td>
                    <td  align="right"><b>0%</b></td>
                </tr> 
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td  align="right"><b>TOTAL</b></td>
                    <td  align="right"><b><?php echo ("$ ". number_format((ceil($subTtalVenta - $ttalDtto) + $ttalIVA),2));?></b></td>
                </tr>                
                <?php }else{ ?>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td  align="right"><b>SUBTOTAL</b></td>
                    <td  align="right"><b><?php echo ("$ ". number_format(($subTtalVenta),2));?></b></td>
                </tr>                
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td  align="right"><b>DESCUENTO</b></td>
                    <td  align="right"><b><?php echo ("$ ". number_format(($ttalDtto),2));?></b></td>
                </tr>                
                <tr>
                    <td colspan="3">&nbsp;</td>
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
                            Numeración habilitada del <?php echo ($infoResolucion['resInicial']);?> al <?php echo ($infoResolucion['resFin']);?>. Regimen Común. <?php echo ($infoResolucion['nota']);?>. 
                        <?php } ?>
            </b></small></div>
        </div>
    
        <div id="conditions">
            <div id="p_condCont" style="font-family:sans-serif; font-size:15px;"><small>
                    <?php if($infoFact['Factura']['factura']){?>
                ESTA FACTURA SE AJUSTA A LO DISPUESTO EN LA LEY 1231 Y DE CONFORMIDAD CON LOS ART. 621 Y 774 DEL CODIGO
                DEL COMERCIO, ART 617 DEL E.T. EL COMPRADOR CON SU FIRMA EXPRESA LA ACEPTACION DEL CONTENIDO DE LA
                FACTURA EN TODAS SUS PARTES, ADEMAS QUE EQUIVALE A LA CONSTANCIA DEL RECIBO REAL Y MATERIAL DE LAS
                MERCANCIAS Y / O LOS SERVICIOS DESCRITOS EN ESTE TITULO VALOR, Y SE OBLIGA A PAGAR DENTRO DE LOS
                TERMINOS Y CONDICIONES AQUI DESCRITOS AL TENEDOR LEGITIMO DE LA FACTURA EL COMPRADOR NO PODRA
                ALEGAR FALTA DE REPRESENTACION O INDEBIDA REPRESENTACION POR RAZON DE LA PERSONA QUE RECIBA LA
                MERCANCIA O EL SERVICIO EN SUS DEPENDENCIAS.
                    <?php } ?>
            </small></div>
        </div>    
        
        <div id="conditions_ot">
            <div id="p_condCont_ot"><small>
                <b>Condiciones del Contrato: 1</b>. El cliente autoriza a quien firma en el presente contrato a ordenar y contratar con el centro de servicio, la ejecución de los respectivos trabajos y por tanto
                da fe que conoce y acepta en su totalidad las condiciones que son parte integrante del contrato que se celebra y consta en el presente documento. <b>2</b>. El centro de servicio queda
                facultado para realizar las pruebas que requiera el vehiculó por fuera del taller. <b>3</b>. El centro de servicio no se hacer responsable por objetos dejados dentro del vehiculo. <b>4</b>. El cliente o la
                persona autorizada. Faculta expresamente al taller. TOQUE RACING S.A.S., a ejercer el derecho de retención del vehiculo. <b>5</b>. El centro de servicio no se hace responsable por daños o
                deterioro del vehiculo. Si estos se presentan por causas de fuerza mayor o extensión de tiempo causado por el cliente. <b>6</b>. El propietario o autorizado firmante del presente contrato, se
                comprometen a reconocer un valor de cinco mil pesos m/cte. ($ 5.000) por concepto de parqueo, por cada día que transcurra desde que finalice los trabajos hasta el momento de
                retiro del vehiculo. <b>7</b>. Aclaraciones: En el momento de la entrada del vehículo se debe cancelar el total del valor de los repuestos. Si la orden de trabajo se encuentra terminada y su
                vehículo no ha sido recogido en los próximos 4 días posterior a esta, al día 5 se le procedera a efectuar un cobro de parqueadero de valor de $5.500 pesos diario.                 
            </small></div>
        </div>  
        
        <div id="dvNota">
            <div id='nota_factura'>
                <small>
                <br><br><b>NOTA:</b><?php echo $infoFact['Factura']['observacion'];?>
                </small>
            </div>
        </div>
    </div>  
    <?php if(!empty($arrInfoOrd)){ ?>
    
    <div style="width:100%; float:left; margin-top:50px;">
        <button id="butImprimirFact" class="btn btn-primary hidden-print" onclick="imprimirOrden();">Imprimir Orden</button><br><br>
    </div>
    
    <div id="ordenTrabajo" style="margin:0px; width:100%; font-family:sans-serif; font-size:15px;">
        
        <div style="width:100%; float:left; margin:0px" align="center"><h4><b><?php 
        if(!empty($infoRemision)){
            echo __($infoRemision['Relacionempresa']['nombre']); 
        }else{
            echo __($infoEmpresa['Empresa']['nombre']); 
        }        
        ?></b></h4></div>
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
            <div style="float:right; margin-right:30px;">
                <img src="<?php echo $urlImg . $infoEmpresa['Empresa']['id'] . '/' . $infoEmpresa['Empresa']['imagen'];?>" 
                     class="img-responsive img-thumbnail center-block" width="200">
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
                        <b>Teléfono: </b><?php $infoFact['Cliente']['telefono']; ?>
                    </div>                 
                </div>
                
                <div style="margin: 2px; float: left; width: 100%;">
                    <div style="margin: 0px; float: left; width: 100%;">
                        <b>Moto/Placa: </b><?php echo __(strtoupper($arrVeh['Vehiculo']['placa'])); ?>
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
                        <b>Dirección: </b><?php $infoFact['Cliente']['direccion']; ?>
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


