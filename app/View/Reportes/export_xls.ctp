<style type="text/css">
    .tableTd {
        border-width: 0.5pt; 
        border: solid; 
        font-family: Arial,Verdana;
        font-size: 9px;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        width: 100px;
    }
    .tableTdContent{
        border-width: 0.5pt; 
        border: solid;
    }
    #titles{
        font-weight: bolder;
    }
    .textRotate{
        border-width: 0.5pt; 
        border: solid; 
        font-family: Arial,Verdana;
        font-size: 9px;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        border-width: 0.5pt; 
        border: solid; 
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        -ms-transform: rotate(-90deg);
        -o-transform: rotate(-90deg);
        transform: rotate(-90deg);
        mso-rotate: 90;
        -webkit-transform-origin: 50% 50%;
        -moz-transform-origin: 50% 50%;
        -ms-transform-origin: 50% 50%;
        -o-transform-origin: 50% 50%;
        transform-origin: 50% 50%;
        position: absolute;
        filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }
    .alineaIzquierda
    {
        text-align: left;
    }
</style>
<?php if(!isset($ventasFactura)){ ?>
<table>
    <tr>
        <td><b><?= $texto_tit; ?></b></td>
    </tr>
    <tr>
        <td><b>Fecha:</b></td>
        <td><?php echo date("d/m/Y, g:i a"); ?></td>
    </tr>
    <tr>
        <td><b>Numero de Filas:</b></td>
        <td class="alineaIzquierda"><?php echo count($rows); ?></td>
    </tr>
    <tr>
        <td></td>
    </tr>

    <tr id="titles">
        <?php foreach ($titulos as $i => $titulo): ?>
            <td class="tableTd" ><?php echo $titulo; ?></td>
        <?php endforeach; ?>
    </tr>
<?php
if (isset($ciudades)) {
    foreach ($ciudades as $key => $ciudade):

        ?>
        <tr>
            <td class="tableTdContent" ><?php echo h($key); ?></td>
            <td class="tableTdContent" ><?php echo h($ciudade); ?></td>
            <td class="tableTdContent" >Prueba</td>
        </tr>
        <?php
    endforeach;
}else if(isset($cargueinventarios)){
    foreach ($cargueinventarios as $ci):
        ?>
        <tr>
            <td class="tableTdContent" ><?php echo h($ci['P']['descripcion']); ?></td>
            <td class="tableTdContent" ><?php echo h($ci['P']['codigo']); ?></td>
            <td class="tableTdContent" ><?php echo h($ci['D']['descripcion']); ?></td>
            <td class="tableTdContent" ><?php echo h($ci['PR']['nombre']); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($ci['Cargueinventario']['costoproducto'])); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($ci['Cargueinventario']['precioventa'])); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($ci['Cargueinventario']['existenciaactual'])); ?></td>
            <td class="tableTdContent" ><?php echo h($ci['Cargueinventario']['created']); ?></td>
        </tr>
        <?php
    endforeach;
}else if(isset($cuentaspendientes)){
    foreach ($cuentaspendientes as $cp):
        ?>
        <tr>
            <td class="tableTdContent" ><?php echo h($cp['PR']['descripcion']); ?></td>
            <td class="tableTdContent" ><?php echo h($cp['PR']['codigo']); ?></td>
            <td class="tableTdContent" ><?php echo h($cp['Cuentaspendiente']['costoproducto']); ?></td>
            <td class="tableTdContent" ><?php echo h($cp['Cuentaspendiente']['cantidad']); ?></td>
            <td class="tableTdContent" ><?php echo h($cp['Cuentaspendiente']['totalobligacion']); ?></td>
            <td class="tableTdContent" ><?php echo h($cp['PV']['nombre']); ?></td>
            <td class="tableTdContent" ><?php echo h($cp['Cuentaspendiente']['numerofactura']); ?></td>
            <td class="tableTdContent" ><?php echo h($cp['Cuentaspendiente']['created']); ?></td>
            <td class="tableTdContent" ><?php echo h($cp['PV']['diascredito']); ?></td>
        </tr>
        <?php
    endforeach;
}else if(isset($detFacts)){

    foreach ($detFacts as $dFact):
        ?>
        <tr>
            <td class="tableTdContent" ><?php echo h($dFact['Factura']['created']); ?></td>
            <?php if ($dFact['Factura']['consecutivodian'] == ""){?>
            <td class="tableTdContent" ><?php echo h($dFact['Factura']['codigo']); ?></td>
            <?php }else{?>
            <td class="tableTdContent" ><?php echo h($dFact['Factura']['consecutivodian']); ?></td>
            <?php }?>
            <?php if($dFact['Factura']['cliente_id'] == ""){?>
            <td class="tableTdContent" ><?php echo h($dFact['Factura']['nombrecliente']); ?></td>
            <?php }else{?>
            <td class="tableTdContent" ><?php echo h($dFact['Cliente']['nombre'] . " - " . $dFact['Cliente']['nit']); ?></td>
            <?php } ?> 
            <td class="tableTdContent" ><?php echo h($dFact['Usuario']['nombre'] . " - " . $dFact['Usuario']['identificacion']); ?></td>
            <td class="tableTdContent" ><?php echo h('$' . number_format($dFact['Factura']['pagocontado'],0)); ?></td>
            <td class="tableTdContent" ><?php echo h('$' . number_format($dFact['Factura']['pagocredito'],0)); ?></td>
        </tr>
    <?php
    endforeach;
}else if(isset($facturas)){
    foreach ($facturas as $fact):
        ?>
        <tr>
            <td class="tableTdContent" ><?php echo h($fact['Factura']['codigo']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['Factura']['consecutivodian']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['Factura']['factura'] ? "F" : "R"); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['CL']['nombre']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['CL']['nit']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['Factura']['created']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['DP']['descripcion']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['PR']['descripcion']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['PR']['codigo']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['FD']['cantidad']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['FD']['costoventa']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['FD']['costototal']); ?></td>
        </tr>
    <?php
    endforeach;
}else if(isset($utilidades)){
    foreach ($utilidades as $utilidade):
        ?>
        <tr>
            <td class="tableTdContent" ><?php echo h($utilidade['P']['descripcion']); ?></td>
            <td class="tableTdContent" ><?php echo h($utilidade['DP']['descripcion']); ?></td>
            <td class="tableTdContent" ><?php echo h($utilidade['PV']['nombre']); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($utilidade['Utilidade']['costo_producto'])); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($utilidade['Utilidade']['costo_producto'] * $utilidade['Utilidade']['cantidad'])); ?></td>
            <td class="tableTdContent" ><?php echo h($utilidade['Utilidade']['cantidad']); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($utilidade['Utilidade']['precioventa'])); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($utilidade['Utilidade']['precioventa'] * $utilidade['Utilidade']['cantidad'])); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($utilidade['Utilidade']['utilidadbruta'])); ?></td>
            <td class="tableTdContent" ><?php echo h(number_format($utilidade['Utilidade']['utilidadporcentual'],4)); ?></td>
            <td class="tableTdContent" ><?php echo h(!empty($utilidade['F']['factura']) ? "Factura" : "Remision"); ?></td>
            <td class="tableTdContent" ><?php echo h($utilidade['Utilidade']['created']); ?></td>
            <td class="tableTdContent" ><?php echo h(!empty($utilidade['F']['factura']) ? $utilidade['F']['consecutivodian'] : $utilidade['F']['codigo']); ?></td>
        </tr>
    <?php
    endforeach;
}else if($arrRotation){
    foreach($arrRotation as $rotation){
        ?>
        <tr>
            <td class="tableTdContent" ><?php echo h($rotation['descripcion']); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($rotation['prom_venta'])); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($rotation['prom_precio_venta'])); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($rotation['prom_utilidad_bruta'])); ?></td>
            <td class="tableTdContent" ><?php echo h(number_format($rotation['prom_utilidad_porc'],4)); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($rotation['costo_producto'])); ?></td>
        </tr>        
        <?php
    }
}else if(isset($cuentasclientes)){
    foreach ($cuentasclientes as $cuentasCli):
        ?>
        <tr>
            <td class="tableTdContent" ><?php echo h($cuentasCli['CL']['nombre']); ?></td>
            <td class="tableTdContent" ><?php echo h($cuentasCli['Cuentascliente']['totalobligacion']); ?></td>
            <td class="tableTdContent" ><?php echo h($cuentasCli['Cuentascliente']['created']); ?></td>
            <td class="tableTdContent" ><?php echo h($cuentasCli['CL']['diascredito']); ?></td>
            <td class="tableTdContent" ><?php echo h($cuentasCli['Cuentascliente']['fechalimitepago']); ?></td>
            <td class="tableTdContent" ><?php echo h($cuentasCli['Cuentascliente']['diasvencido']); ?></td>
            <td class="tableTdContent" ><?php echo h($cuentasCli['U']['nombre']); ?></td>
        </tr>
    <?php
    endforeach;
}else if(isset($arrInfoCompras)){
    foreach ($arrInfoCompras as $cpr):

        if($typeTax == '1'){
            $total = $cpr['valor'] + $cpr['iva_vlr'] - $cpr['retefuente_vlr'] - $cpr['reteica_vlr'];
        }else if($typeTax == '2'){
            $total = $cpr['valor'] + $cpr['iva_vlr'];
        }else if($typeTax == '3'){
            $total = $cpr['valor'] - $cpr['retefuente_vlr'];
        }else if($typeTax == '4'){
            $total = $cpr['valor'] - $cpr['reteica_vlr'];
        }
        
        ?>

        <tr>
            <td class="tableTdContent" ><?php echo h($cpr['proveedor']); ?></td>
            <td class="tableTdContent" ><?php echo h($cpr['fecha']); ?></td>
            <td class="tableTdContent" ><?php echo h($cpr['num_factura']); ?></td>
            <td class="tableTdContent" ><?php echo h($cpr['usuario']); ?></td>
            <td class="tableTdContent" ><?php echo h($cpr['categoria']); ?></td>
            <td class="tableTdContent" ><?php echo h($cpr['valor']); ?></td>

            <?php if($typeTax == '1'){?>
                <td class="tableTdContent" ><?php echo h($cpr['iva_prc']); ?></td>
                <td class="tableTdContent" ><?php echo h($cpr['iva_vlr']); ?></td>
                <td class="tableTdContent" ><?php echo h($cpr['retefuente_prc']); ?></td>
                <td class="tableTdContent" ><?php echo h($cpr['retefuente_vlr']); ?></td>
                <td class="tableTdContent" ><?php echo h($cpr['reteica_prc']); ?></td>
                <td class="tableTdContent" ><?php echo h($cpr['reteica_vlr']); ?></td>
                <td class="tableTdContent" ><?php echo h($total); ?></td>
            <?php }else if($typeTax == '2'){?>
                <td class="tableTdContent" ><?php echo h($cpr['iva_prc']); ?></td>
                <td class="tableTdContent" ><?php echo h($cpr['iva_vlr']); ?></td>
                <td class="tableTdContent" ><?php echo h($total); ?></td>
            <?php }else if($typeTax == '3'){?>
                <td class="tableTdContent" ><?php echo h($cpr['retefuente_prc']); ?></td>
                <td class="tableTdContent" ><?php echo h($cpr['retefuente_vlr']); ?></td>
                <td class="tableTdContent" ><?php echo h($total); ?></td>
            <?php }else if($typeTax == '4'){?>
                <td class="tableTdContent" ><?php echo h($cpr['reteica_prc']); ?></td>
                <td class="tableTdContent" ><?php echo h($cpr['reteica_vlr']); ?></td>
                <td class="tableTdContent" ><?php echo h($total); ?></td>
            <?php }?>
        </tr>
    <?php
    endforeach;
}else if(isset($gastos)){
    foreach ($gastos as $gasto):
        ?>
        <tr>
            <td class="tableTdContent" ><?php echo h($gasto['descripcion']); ?></td>
            <td class="tableTdContent" ><?php echo h($gasto['usuario']); ?></td>
            <td class="tableTdContent" ><?php echo h($gasto['empRel']); ?></td>
            <td class="tableTdContent" ><?php echo h($gasto['fechagasto']); ?></td>
            <td class="tableTdContent" ><?php echo h($gasto['created']); ?></td>
            <td class="tableTdContent" ><?php echo h($gasto['itemsgasto']); ?></td>
            <td class="tableTdContent" ><?php echo h($gasto['cuenta']); ?></td>
            <td class="tableTdContent" ><?php echo h($gasto['traslado'] == '1' ? 'Traslado' : 'Gasto'); ?></td>
            <td class="tableTdContent" ><?php echo h($gasto['cuentadestino']); ?></td>
            <td class="tableTdContent" ><?php echo h($gasto['valor']); ?></td>
        </tr>
    <?php
    endforeach;
}else if(isset($cierrediario)){
    
    foreach ($cierrediario as $cd):
    $total = 0;
    $total += $cd['Cierrecaja']['saldo_inicial'];
    $total += $cd['Cierrecaja']['ventas'];
    $total -= $cd['Cierrecaja']['gastos'];
    $total += $cd['Cierrecaja']['traslados_ing'];
    $total -= $cd['Cierrecaja']['traslados_gas'];
    $total += $cd['Cierrecaja']['abonos'];
    ?>
    <tr>
        <td class="tableTdContent" ><?php echo h($listCuentas[$cd['Cierrecaja']['caja_id']]); ?>&nbsp;</td>
        <td class="tableTdContent" ><?php echo h($cd['Cierrecaja']['saldo_inicial']); ?></td>
        <td class="tableTdContent" ><?php echo h($cd['Cierrecaja']['ventas']); ?></td>
        <td class="tableTdContent" ><?php echo h($cd['Cierrecaja']['gastos']); ?></td>
        <td class="tableTdContent" ><?php echo h($cd['Cierrecaja']['traslados_ing']); ?></td>
        <td class="tableTdContent" ><?php echo h($cd['Cierrecaja']['traslados_gas']); ?></td>
        <td class="tableTdContent" ><?php echo h($cd['Cierrecaja']['abonos']); ?></td>
        <td class="tableTdContent" ><?php echo h($total); ?></td>
    </tr>
    <?php
    endforeach;
} ?>
</table>
<?php }else{ ?>
    <table>
    <tr>
        <td><b><?php echo __('Detalles cierre del dia'); ?></b></td>
    </tr>
    <tr>
        <td><b>Fecha:</b></td>
        <td><?php echo ($fechaCierre); ?></td>
    </tr>
    <tr>
        <td></td>
    </tr>

    <tr><td><b><?php echo __('Ventas'); ?></b></td></tr>
    <tr id="titles">
        <td class="tableTd"><?php echo ('Consecutivo Factura'); ?></td>
        <td class="tableTd"><?php echo ('Cliente'); ?></td>
        <td class="tableTd"><?php echo ('Vendedor'); ?></td>
        <td class="tableTd"><?php echo ('Cuenta'); ?></td>                                
        <td class="tableTd"><?php echo ('Tipo Pago'); ?></td>
        <td class="tableTd"><?php echo ('Valor'); ?></td>        
    </tr>
    <?php foreach ($ventasFactura as $dFact): ?>
    <tr>        
        <td class="tableTdContent"><?php echo(!empty($dFact['consecutivodian']) ? $dFact['consecutivodian'] : $dFact['fact_codigo']);?></td>
        <td class="tableTdContent"><?php echo(!empty($dFact['cliente_nombre']) ? $dFact['cliente_nombre'] . " - " . $dFact['cliente_nit'] : 'Venta Anónima');?></td>
        <td class="tableTdContent"><?php echo h($dFact['usuario_nombre'] . " - " . $dFact['usuario_identificacion']); ?></td>
        <td class="tableTdContent"><?php echo h($listCuenta[$dFact['fcv_cuenta']]); ?></td>
        <td class="tableTdContent"><?php echo h($listTipoPago[$dFact['fcv_tipopago']]); ?></td>     
        <td class="tableTdContent"><?php echo h($dFact['fcv_valor']);?></td>              
    </tr>
    <?php endforeach; ?>
    </table>
    <br><br>
    <table>
    <tr><td><b><?php echo __('Detalle Gastos'); ?></b></td></tr>  
    <tr  id="titles">
        <td  class="tableTd"><?php echo ('Descripcion'); ?></td>
        <td  class="tableTd"><?php echo ('usuario'); ?></td>
        <td  class="tableTd"><?php echo ('Fecha del Gasto'); ?></td>                    
        <td  class="tableTd"><?php echo ('Cuenta'); ?></td>
        <td  class="tableTd"><?php echo ('Tipo'); ?></td>
        <td  class="tableTd"><?php echo ('Valor'); ?></td>
    </tr>
    <?php foreach ($infoGastos as $gt): ?>
    <tr>
        <td class="tableTdContent"><?php echo h($gt['Gasto']['descripcion']); ?>&nbsp;</td>
        <td class="tableTdContent"><?php echo h($gt['Usuario']['nombre']); ?>&nbsp;</td>
        <td class="tableTdContent"><?php echo h($gt['Gasto']['fechagasto']); ?>&nbsp;</td>                    
        <td class="tableTdContent"><?php echo h($gt['Cuenta']['descripcion']); ?>&nbsp;</td>
        <td class="tableTdContent"><?php echo h(!empty($gt['Cuenta']['traslado']) ? "Traslado" : "Gasto"); ?>&nbsp;</td>
        <td class="tableTdContent"><?php echo h($gt['Gasto']['valor']); ?>&nbsp;</td>                    
    </tr>
    <?php endforeach; ?>
    </table>
    <br><br>
    <table>
    <tr><td><b><?php echo __('Detalle Traslados'); ?></b></td></tr>
    <tr>
        <td class="tableTd"><?php echo ('Descripcion'); ?></td>
        <td class="tableTd"><?php echo ('usuario'); ?></td>
        <td class="tableTd"><?php echo ('Fecha del Traslado'); ?></td>                    
        <td class="tableTd"><?php echo ('Cuenta Origen'); ?></td>
        <td class="tableTd"><?php echo ('Cuenta Destino'); ?></td>
        <td class="tableTd"><?php echo ('Valor'); ?></td>
    </tr>
    <?php foreach ($infoTraslados as $trs): ?> 
    <tr>
        <td  class="tableTdContent"><?php echo h($trs['Gasto']['descripcion']); ?>&nbsp;</td>
        <td  class="tableTdContent"><?php echo h($trs['Usuario']['nombre']); ?>&nbsp;</td>
        <td  class="tableTdContent"><?php echo h($trs['Gasto']['fechagasto']); ?>&nbsp;</td>                    
        <td  class="tableTdContent"><?php echo h($trs['Cuenta']['descripcion']); ?>&nbsp;</td>
        <td  class="tableTdContent"><?php echo h($listCuenta[$trs['Gasto']['cuentadestino']]); ?>&nbsp;</td>
        <td  class="tableTdContent"><?php echo h($trs['Gasto']['valor']); ?>&nbsp;</td>                    
    </tr>
    <?php endforeach; ?>
    </table>
    <br><br>
      
    <table>
    <tr><td><b><?php echo __('Detalle Abonos'); ?></b></td></tr>
    <tr>
        <td class="tableTd"><?php echo ('usuario'); ?></td>
        <td class="tableTd"><?php echo ('Fecha del Abono'); ?></td>                    
        <td class="tableTd"><?php echo ('Cuenta'); ?></td>                    
        <td class="tableTd"><?php echo ('Valor Abono'); ?></td>
    </tr>
    <?php foreach ($arrAbonos as $abn): ?> 
    <tr>
        <td class="tableTdContent"><?php echo h($abn['cliente']); ?>&nbsp;</td>
        <td class="tableTdContent"><?php echo h($abn['fecha']); ?>&nbsp;</td>                    
        <td class="tableTdContent"><?php echo h($abn['cuenta']); ?>&nbsp;</td>                    
        <td class="tableTdContent"><?php echo h($abn['valor']); ?>&nbsp;</td>                    
    </tr>
    <?php endforeach; ?>
    </table><br><br>

    <table>
    <tr><td><b><?php echo __('Detalle de Estado por Caja'); ?></b></td></tr>
    <tr>
        <td class="tableTd"><?php echo ('Caja'); ?></td>
        <td class="tableTd"><?php echo ('Saldo Inicial'); ?></td>
        <td class="tableTd"><?php echo ('Ventas'); ?></td>                    
        <td class="tableTd"><?php echo ('Gastos'); ?></td>
        <td class="tableTd"><?php echo ('Ing. Traslados'); ?></td>
        <td class="tableTd"><?php echo ('Gas. Traslados'); ?></td>
        <td class="tableTd"><?php echo ('Abonos Prefacturas'); ?></td>
        <td class="tableTd"><?php echo ('Abonos Facturas'); ?></td>
        <td class="tableTd"><?php echo ('Total'); ?></td>
    </tr>
    <?php foreach ($estadoCuentas as $key => $val): ?> 

    <?php 
        $saldoInicial = 0;                    
        $saldoInicial = $val['estado_actual'];
        $saldoInicial -= isset($val['ing_ventas']) ? $val['ing_ventas'] : 0;
        $saldoInicial += isset($val['gastos']) ? $val['gastos'] : 0;
        $saldoInicial -= isset($val['ing_traslados']) ? $val['ing_traslados'] : 0;
        $saldoInicial += isset($val['gasto_traslados']) ? $val['gasto_traslados'] : 0;
        $saldoInicial -= isset($val['abono_prefact']) ? $val['abono_prefact'] : 0;
        $saldoInicial -= isset($val['abono_fact']) ? $val['abono_fact'] : 0;
    ?>
    <tr>
        <td class="tableTdContent"><?php echo ($listCuenta[$key]);?></td>                                        
        <td class="tableTdContent"><?php echo ($saldoInicial); ?></td>
        <td class="tableTdContent"><?php echo (isset($val['ing_ventas']) ? $val['ing_ventas'] : 0); ?></td>
        <td class="tableTdContent"><?php echo (isset($val['gastos']) ? $val['gastos'] : 0); ?></td>
        <td class="tableTdContent"><?php echo (isset($val['ing_traslados']) ? $val['ing_traslados'] : 0); ?></td>
        <td class="tableTdContent"><?php echo (isset($val['gasto_traslados']) ? $val['gasto_traslados'] : 0); ?></td>
        <td class="tableTdContent"><?php echo (isset($val['abono_prefact']) ? $val['abono_prefact'] : 0); ?></td>
        <td class="tableTdContent"><?php echo (isset($val['abono_fact']) ? $val['abono_fact'] : 0); ?></td>
        <td class="tableTdContent"><?php echo (isset($val['estado_actual']) ? $val['estado_actual'] : 0); ?></td>
    </tr>
    <?php endforeach; ?>
    </table>


    <br><br>
<?php } ?>




