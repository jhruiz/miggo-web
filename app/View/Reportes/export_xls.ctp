<style type="text/css">
.tableTd {
    border-width: 0.5pt;
    border: solid;
    font-family: Arial, Verdana;
    font-size: 9px;
    font-weight: bold;
    text-align: center;
    vertical-align: middle;
    width: 100px;
}

.tableTdContent {
    border-width: 0.5pt;
    border: solid;
}

#titles {
    font-weight: bolder;
}

.textRotate {
    border-width: 0.5pt;
    border: solid;
    font-family: Arial, Verdana;
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

.alineaIzquierda {
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
        <td class="tableTd"><?php echo $titulo; ?></td>
        <?php endforeach; ?>
    </tr>
    <?php
if (isset($ciudades)) {
    foreach ($ciudades as $key => $ciudade):

        ?>
    <tr>
        <td class="tableTdContent"><?php echo h($key); ?></td>
        <td class="tableTdContent"><?php echo h($ciudade); ?></td>
        <td class="tableTdContent">Prueba</td>
    </tr>
    <?php
    endforeach;
}else if(isset($cargueinventarios)){
    foreach ($cargueinventarios as $ci):
        ?>
    <tr>
        <td class="tableTdContent"><?php echo h($ci['P']['descripcion']); ?></td>
        <td class="tableTdContent"><?php echo h($ci['P']['codigo']); ?></td>
        <td class="tableTdContent"><?php echo h($ci['D']['descripcion']); ?></td>
        <td class="tableTdContent"><?php echo h($ci['PR']['nombre']); ?></td>
        <td class="tableTdContent"><?php echo h(intval($ci['Cargueinventario']['costoproducto'])); ?></td>
        <td class="tableTdContent"><?php echo h(intval($ci['Cargueinventario']['precioventa'])); ?></td>
        <td class="tableTdContent"><?php echo h(intval($ci['Cargueinventario']['existenciaactual'])); ?></td>
        <td class="tableTdContent"><?php echo h($ci['Cargueinventario']['created']); ?></td>
    </tr>
    <?php
    endforeach;
}else if(isset($cuentaspendientes)){
    foreach ($cuentaspendientes as $cp):
        ?>
    <tr>
        <td class="tableTdContent"><?php echo h($cp['PR']['descripcion']); ?></td>
        <td class="tableTdContent"><?php echo h($cp['PR']['codigo']); ?></td>
        <td class="tableTdContent"><?php echo h($cp['Cuentaspendiente']['costoproducto']); ?></td>
        <td class="tableTdContent"><?php echo h($cp['Cuentaspendiente']['cantidad']); ?></td>
        <td class="tableTdContent"><?php echo h($cp['Cuentaspendiente']['totalobligacion']); ?></td>
        <td class="tableTdContent"><?php echo h($cp['PV']['nombre']); ?></td>
        <td class="tableTdContent"><?php echo h($cp['Cuentaspendiente']['numerofactura']); ?></td>
        <td class="tableTdContent"><?php echo h($cp['Cuentaspendiente']['created']); ?></td>
        <td class="tableTdContent"><?php echo h($cp['PV']['diascredito']); ?></td>
    </tr>
    <?php
    endforeach;
}else if(isset($detFacts)){

    foreach ($detFacts as $dFact):
        ?>
    <tr>
        <td class="tableTdContent"><?php echo h($dFact['Factura']['created']); ?></td>
        <?php if ($dFact['Factura']['consecutivodian'] == ""){?>
        <td class="tableTdContent"><?php echo h($dFact['Factura']['codigo']); ?></td>
        <?php }else{?>
        <td class="tableTdContent"><?php echo h($dFact['Factura']['consecutivodian']); ?></td>
        <?php }?>
        <?php if($dFact['Factura']['cliente_id'] == ""){?>
        <td class="tableTdContent"><?php echo h($dFact['Factura']['nombrecliente']); ?></td>
        <?php }else{?>
        <td class="tableTdContent"><?php echo h($dFact['Cliente']['nombre'] . " - " . $dFact['Cliente']['nit']); ?></td>
        <?php } ?>
        <td class="tableTdContent">
            <?php echo h($dFact['Usuario']['nombre'] . " - " . $dFact['Usuario']['identificacion']); ?></td>
        <td class="tableTdContent"><?php echo h('$' . number_format($dFact['Factura']['pagocontado'],0)); ?></td>
        <td class="tableTdContent"><?php echo h('$' . number_format($dFact['Factura']['pagocredito'],0)); ?></td>
    </tr>
    <?php
    endforeach;
}else if(isset($arrFacts)){
    foreach ($arrFacts as $fact):
        ?>
        <tr>
            <td class="tableTdContent" ><?php echo h($fact['consecutivo']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['fecha']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['nombreCliente']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['identificacion']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['telefono']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['cantidad']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['producto']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['valor']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['valor_ttal']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['descuento']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['subtotal']); ?></td>
            <td class="tableTdContent" ><?php echo h($fact['iva']); ?></td>
        </tr>
    <?php
endforeach;
} else if (isset($utilidades)) {
    foreach ($utilidades as $utilidade):
    ?>
        <tr>
            <td class="tableTdContent" ><?php echo h($utilidade['P']['descripcion']); ?></td>
            <td class="tableTdContent" ><?php echo h($utilidade['P']['referencia']); ?></td>
            <td class="tableTdContent" ><?php echo h($utilidade['DP']['descripcion']); ?></td>
            <td class="tableTdContent" ><?php echo h($utilidade['PV']['nombre']); ?></td>
            <td class="tableTdContent" ><?php echo h($utilidade['US']['nombre']); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($utilidade['Utilidade']['costo_producto'])); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($utilidade['Utilidade']['costo_producto'] * $utilidade['Utilidade']['cantidad'])); ?></td>
            <td class="tableTdContent" ><?php echo h($utilidade['Utilidade']['cantidad']); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($utilidade['Utilidade']['precioventa'])); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($utilidade['Utilidade']['precioventa'] * $utilidade['Utilidade']['cantidad'])); ?></td>
            <td class="tableTdContent" ><?php echo h(intval($utilidade['Utilidade']['utilidadbruta'])); ?></td>
            <td class="tableTdContent" ><?php echo h(number_format($utilidade['Utilidade']['utilidadporcentual'], 4)); ?></td>
            <td class="tableTdContent" ><?php echo h(!empty($utilidade['F']['factura']) ? "Factura" : "Remision"); ?></td>
            <td class="tableTdContent" ><?php echo h($utilidade['Utilidade']['created']); ?></td>
            <td class="tableTdContent" ><?php echo h(!empty($utilidade['F']['factura']) ? $utilidade['F']['consecutivodian'] : $utilidade['F']['codigo']); ?></td>
        </tr>
    <?php
endforeach;
} else if (isset($arrRotation)) {
    foreach ($arrRotation as $rotation) {
        ?>
    <tr>
        <td class="tableTdContent"><?php echo h($rotation['descripcion']); ?></td>
        <td class="tableTdContent"><?php echo h(intval($rotation['prom_venta'])); ?></td>
        <td class="tableTdContent"><?php echo h(intval($rotation['prom_precio_venta'])); ?></td>
        <td class="tableTdContent"><?php echo h(intval($rotation['prom_utilidad_bruta'])); ?></td>
        <td class="tableTdContent"><?php echo h(number_format($rotation['prom_utilidad_porc'],4)); ?></td>
        <td class="tableTdContent"><?php echo h(intval($rotation['costo_producto'])); ?></td>
    </tr>
    <?php
    }
}else if(isset($cuentasclientes)){
    foreach ($cuentasclientes as $cuentasCli):
        ?>
    <tr>
        <td class="tableTdContent"><?php echo h($cuentasCli['CL']['nombre']); ?></td>
        <td class="tableTdContent"><?php echo h(!empty($cuentasCli['F']['consecutivodian']) ? $cuentasCli['F']['consecutivodian'] : $cuentasCli['F']['codigo']); ?></td>
        <td class="tableTdContent"><?php echo h($cuentasCli['Cuentascliente']['totalobligacion']); ?></td>
        <td class="tableTdContent"><?php echo h($cuentasCli['Cuentascliente']['created']); ?></td>
        <td class="tableTdContent"><?php echo h($cuentasCli['CL']['diascredito']); ?></td>
        <td class="tableTdContent"><?php echo h($cuentasCli['Cuentascliente']['fechalimitepago']); ?></td>
        <td class="tableTdContent"><?php echo h($cuentasCli['Cuentascliente']['diasvencido']); ?></td>
        <td class="tableTdContent"><?php echo h($cuentasCli['U']['nombre']); ?></td>
    </tr>
    <?php
    endforeach;
}else if(isset($arrInfoCompras)){
    foreach ($arrInfoCompras as $cpr):

        if ($typeTax == '1') {
            $total = $cpr['valor'] + $cpr['iva_vlr'] - $cpr['retefuente_vlr'] - $cpr['reteica_vlr'];
        } else if ($typeTax == '2') {
        $total = $cpr['valor'] + $cpr['iva_vlr'];
    } else if ($typeTax == '3') {
        $total = $cpr['valor'] - $cpr['retefuente_vlr'];
    } else if ($typeTax == '4') {
        $total = $cpr['valor'] - $cpr['reteica_vlr'];
    }

    ?>

    <tr>
        <td class="tableTdContent"><?php echo h($cpr['proveedor']); ?></td>
        <td class="tableTdContent"><?php echo h($cpr['fecha']); ?></td>
        <td class="tableTdContent"><?php echo h($cpr['num_factura']); ?></td>
        <td class="tableTdContent"><?php echo h($cpr['usuario']); ?></td>
        <td class="tableTdContent"><?php echo h($cpr['categoria']); ?></td>
        <td class="tableTdContent"><?php echo h($cpr['valor']); ?></td>

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
} else if(isset($pagosFacturas)){
    foreach ($pagosFacturas as $pagosFact):
        ?>
            <tr>
                <td class="tableTdContent" ><?php echo h($pagosFact['F']['codigo']); ?></td>
                <td class="tableTdContent" ><?php echo h($pagosFact['F']['consecutivodian']); ?></td>
                <td class="tableTdContent" ><?php echo h($pagosFact['F']['created']); ?></td>
                <td class="tableTdContent" ><?php echo h($pagosFact['C']['descripcion']); ?></td>
                <td class="tableTdContent" ><?php echo h($pagosFact['T']['descripcion']); ?></td>
                <td class="tableTdContent" ><?php echo h($pagosFact['FacturaCuentaValore']['valor']); ?></td>
            </tr>
        <?php
    endforeach;
}else if(isset($facturaClientes)){

        foreach ($facturaClientes as $fc): ?>
    
            <tr>
                <td class="tableTdContent"><?php echo h($fc['C']['nombre']); ?></td>
                <td class="tableTdContent"><?php echo h($fc['C']['nit']); ?></td>
                <td class="tableTdContent"><?php echo h($fc['C']['celular']); ?></td>
                <td class="tableTdContent"><?php echo h($fc['V']['placa']); ?></td>
                <td class="tableTdContent"><?php echo h($fc['U']['nombre']); ?></td>
                <td class="tableTdContent"><?php echo h($fc['Factura']['codigo']); ?></td>
                <td class="tableTdContent"><?php echo h($fc['Factura']['created']); ?></td>
                <td class="tableTdContent"><?php echo h($fc['0']['conteo']); ?></td>
                <td class="tableTdContent"><?php echo h($fc['0']['valor']); ?></td>
            </tr>
    
        <?php 
        endforeach;

}else if (isset($gastos)) {
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
}

else if(isset($arrOrdenesT)) {

    foreach ($arrOrdenesT as $ordenes):
        ?>
        <tr>
            <th class="tableTdContent"><?php echo h($ordenes['Ordentrabajo']['codigo']) ?></th>
            <th class="tableTdContent"><?php echo h($ordenes['Ordentrabajo']['fecha_ingreso']) ?></th>
            <th class="tableTdContent"><?php echo h($ordenes['US']['nombre']) ?></th>
            <th class="tableTdContent"><?php echo h($ordenes['CL']['nombre']) ?></th>
            <th class="tableTdContent"><?php echo h($ordenes['VH']['placa']) ?></th>
            <th class="tableTdContent"><?php echo h($ordenes['OE']['descripcion']) ?></th>
        </tr>
        <?php
    endforeach;

}

else if(isset($arrFactOrdenes)) {

    foreach ($arrFactOrdenes as $ordenes):

        $consec = !empty($ordenes['Factura']['consecutivodian']) ? $ordenes['Factura']['consecutivodian'] : $ordenes['Factura']['codigo'];

        ?>
        <tr>
            <th class="tableTdContent"><?php echo h($ordenes['OT']['codigo']) ?></th>
            <th class="tableTdContent"><?php echo h($consec) ?></th>
            <th class="tableTdContent"><?php echo h($ordenes['Factura']['created']) ?></th>
            <th class="tableTdContent"><?php echo h($ordenes['US']['nombre']) ?></th>
            <th class="tableTdContent"><?php echo h($ordenes['PR']['descripcion'] . '(' . $ordenes['PR']['codigo'] . ')') ?></th>
            <th class="tableTdContent"><?php echo h($ordenes['VH']['placa']) ?></th> 
            <th class="tableTdContent"><?php echo h($ordenes['VH']['marca']) ?></th> 
            <th class="tableTdContent"><?php echo h($ordenes['VH']['linea']) ?></th> 
            <th class="tableTdContent"><?php echo h($ordenes['FD']['cantidad']) ?></th>
            <th class="tableTdContent"><?php echo h($ordenes['FD']['costoventa']) ?></th>
            <th class="tableTdContent"><?php echo h($ordenes['FD']['costototal']) ?></th>
            <th class="tableTdContent"><?php echo h($ordenes['Factura']['fechapagoservicio']) ?></th>
        </tr>
        <?php
    endforeach;

}

// Tabla excel vista /productos/index
else if (isset($productos)) {
    foreach ($productos as $producto):
    ?>
    <tr>
        <td class="tableTdContent"><?php echo h($producto['Producto']['codigo']); ?></td>
        <td class="tableTdContent"><?php echo h($producto['Producto']['referencia']); ?></td>
        <td class="tableTdContent"><?php echo h($producto['Producto']['descripcion']); ?></td>
        <td class="tableTdContent"><?php echo h($producto['C']['descripcion']); ?></td>
        <td class="tableTdContent"><?php echo h($producto['Producto']['marca']); ?></td>
        <td class="tableTdContent"><?php echo h($producto['Producto']['existenciaminima']); ?></td>
        <td class="tableTdContent"><?php echo h($producto['Producto']['existenciamaxima']); ?></td>
    </tr>
    <?php
endforeach;
}
// Tabla excel vista /categorias/index/
else if (isset($categoriasReporte)) {
    foreach ($categoriasReporte as $categoria):
    ?>
    <tr>
        <th class="tableTdContent"><?php echo h($categoria['Categoria']['descripcion']) ?></th>
        <?php 
            if ($categoria['Categoria']['servicio'] == 0){
            $categoria['Categoria']['servicio'] = "No";
            }
            if ($categoria['Categoria']['servicio'] == 1){
            $categoria['Categoria']['servicio'] = "Si";
            }
        ?>
        <?php 
            if ($categoria['Categoria']['mostrarencatalogo'] == 0){
            $categoria['Categoria']['mostrarencatalogo'] = "No";
            }
            if ($categoria['Categoria']['mostrarencatalogo'] == 1){
            $categoria['Categoria']['mostrarencatalogo'] = "Si";
            }
        ?>
        <th class="tableTdContent"><?php echo h($categoria['Categoria']['mostrarencatalogo']) ?></th>
        <th class="tableTdContent"><?php echo h($categoria['Categoria']['servicio']) ?></th>
        <th class="tableTdContent"><?php echo h($categoria['Categoria']['created']) ?></th>
    </tr>
    <?php
endforeach;
}
// Tabla excel vista /clientes/index/
else if (isset($clientesReporte)) {
    foreach ($clientesReporte as $cliente):
    ?>
    <tr>
        <th class="tableTdContent"><?php echo h($cliente['Cliente']['nit']) ?></th>
        <th class="tableTdContent"><?php echo h($cliente['Cliente']['nombre']) ?></th>
        <th class="tableTdContent"><?php echo h($cliente['Cliente']['direccion']) ?></th>
        <th class="tableTdContent"><?php echo h($cliente['Cliente']['telefono']) ?></th>
        <th class="tableTdContent"><?php echo h($cliente['CU']['descripcion']) ?></th>
        <th class="tableTdContent"><?php echo h($cliente['Cliente']['celular']) ?></th>
        <th class="tableTdContent"><?php echo h($cliente['Cliente']['diascredito']) ?></th>
        <th class="tableTdContent"><?php echo h($cliente['Cliente']['limitecredito']) ?></th>
        <th class="tableTdContent"><?php echo h($cliente['E']['descripcion']) ?></th>
        <th class="tableTdContent"><?php echo h($cliente['C']['descripcion']) ?></th>
    </tr>
    <?php
endforeach;
}
// Tabla excel vista /deposito/index/
else if (isset($depositosReporte)) {
    foreach ($depositosReporte as $deposito):
    ?>
    <tr>
        <th class="tableTdContent"><?php echo h($deposito['Deposito']['descripcion']) ?></th>
        <th class="tableTdContent"><?php echo h($deposito['C']['descripcion']) ?></th>
        <th class="tableTdContent"><?php echo h($deposito['Deposito']['telefono']) ?></th>
        <th class="tableTdContent"><?php echo h($deposito['Deposito']['direccion']) ?></th>
        <th class="tableTdContent"><?php echo h($deposito['U']['nombre']) ?></th>
        <th class="tableTdContent"><?php echo h($deposito['Deposito']['empresa_id'] . $deposito['Deposito']['ciudade_id'] . '-' . $deposito['Deposito']['id']) ?></th>
    </tr>
    <?php
endforeach;
}
// Tabla excel vista /proveedores/index
else if (isset($proovedoresReporte)) {
    foreach ($proovedoresReporte as $proveedor):
    ?>
    <tr>
        <th class="tableTdContent"><?php echo h($proveedor['Proveedore']['nit']) ?></th>
        <th class="tableTdContent"><?php echo h($proveedor['Proveedore']['nombre']) ?></th>
        <th class="tableTdContent"><?php echo h($proveedor['Proveedore']['empresa_id'] . $proveedor['Proveedore']['usuario_id']) ?></th>
        <th class="tableTdContent"><?php echo h($proveedor['Proveedore']['direccion']) ?></th>
        <th class="tableTdContent"><?php echo h($proveedor['Proveedore']['telefono']) ?></th>
        <th class="tableTdContent"><?php echo h($proveedor['Ciudade']['descripcion']) ?></th>
        <th class="tableTdContent"><?php echo h($proveedor['Proveedore']['celular']) ?></th>
        <?php 
            if ($proveedor['Proveedore']['estado_id'] == 2){
                $proveedor['Proveedore']['estado_id'] = "INACTIVO";
            }
            if ($proveedor['Proveedore']['estado_id'] == 1){
                $proveedor['Proveedore']['estado_id'] = "ACTIVO";
            }
        ?>
        <th class="tableTdContent"><?php echo h($proveedor['Proveedore']['estado_id'])?></th>
    </tr>
    <?php
endforeach;
}
// Tabla excel vista /prefacturas/index
else if (isset($prefacturasReporte)) {
    foreach ($prefacturasReporte as $prefactura):
    ?>
    <tr>
        <th class="tableTdContent"><?php echo h($prefactura['Prefactura']['id']) ?></th>
        <th class="tableTdContent"><?php echo h($prefactura['CL']['nombre']) ?></th>
        <th class="tableTdContent"><?php echo h($prefactura['VH']['placa']) ?></th>
        <th class="tableTdContent"><?php echo h($prefactura['Prefactura']['created']) ?></th>
        <th class="tableTdContent"><?php echo h($prefactura['ES']['descripcion']) ?></th>
        <th class="tableTdContent"><?php echo h($prefactura['Prefactura']['observacion']) ?></th>
        <?php $costo = number_format($prefactura['PD']['costoventa'],2); ?>
        <th class="tableTdContent"><?php echo h($costo) ?></th>
        <th class="tableTdContent"><?php echo h($prefactura['PR']['codigo'] ) ?></th>
        <th class="tableTdContent"><?php echo h($prefactura['PR']['descripcion'] ) ?></th>
    </tr>
    <?php
endforeach;
}
// Tabla excel vista /usuarios/index
else if (isset($usuariosReporte)) {
    foreach ($usuariosReporte as $usuario):
    ?>
    <tr>
        <th class="tableTdContent"><?php echo h($usuario['Usuario']['nombre']) ?></th>
        <th class="tableTdContent"><?php echo h($usuario['Usuario']['identificacion']) ?></th>
        <th class="tableTdContent"><?php echo h($usuario['Usuario']['username']) ?></th>
        <th class="tableTdContent"><?php echo h($usuario['P']['descripcion']) ?></th>
        <th class="tableTdContent"><?php echo h($usuario['E']['descripcion']) ?></th>
    </tr>
    <?php
endforeach;
}


else if(isset($cierrediario)){
    
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

    <tr>
        <td><b><?php echo __('Ventas'); ?></b></td>
    </tr>
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
    <tr>
        <td><b><?php echo __('Detalle de Estado por Caja'); ?></b></td>
    </tr>
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
    <?php }?>



<!-- Zona descarga /ordentrabajos/ordenesPrefacturas (Prefacturas)-->
<?php if (isset($prefacturas)) {?>

        <?php foreach ($prefacturas as $prefactura): ?>
            <tr>
            <td class="tableTdContent" ><?php echo h($prefactura['CL']['nombre']); ?><br></td>

            <td class="tableTdContent" ><?php echo h($prefactura['VH']['placa']); ?></td>
            <td class="tableTdContent" ><?php echo h($prefactura['Prefactura']['created']); ?></td>
            <td class="tableTdContent" ><?php echo h(!empty($prefactura['Prefactura']['estadoprefactura_id']) ? $estados[$prefactura['Prefactura']['estadoprefactura_id']] : ""); ?></td>
            <td class="tableTdContent" ><?php echo h($prefactura['Prefactura']['observacion']); ?></td>
            </tr>
        <?php endforeach;?>

    <?php }?>

<!-- Zona descarga /ordentrabajos/ordenesPrefacturas (Ordenes trabajo)-->
    <?php if (isset($ordenes)) {?>
    <?php foreach ($ordenes as $ot): ?>

        <tr>
            <td class="tableTdContent" ><?php echo h($ot['Ordentrabajo']['codigo']); ?>&nbsp;</td>
            <td class="tableTdContent" ><?php echo h($ot['US']['nombre']); ?>&nbsp;</td>
            <td class="tableTdContent" ><?php echo h(!empty($ot['CL']['nombre']) ? $ot['CL']['nombre'] : ""); ?>&nbsp;</td>
            <td class="tableTdContent" ><?php echo h(!empty($ot['VH']['placa']) ? $ot['VH']['placa'] : ""); ?>&nbsp;</td>
            <td class="tableTdContent" ><?php echo h(!empty($ot['OE']['descripcion']) ? $ot['OE']['descripcion'] : ""); ?>&nbsp;</td>
            <td class="tableTdContent" ><?php echo h(!empty($ot['Ordentrabajo']['observaciones_usuario']) ? $ot['Ordentrabajo']['observaciones_usuario'] : ""); ?>&nbsp;</td>
            <td class="tableTdContent" ><?php echo h(!empty($ot['Ordentrabajo']['observaciones_cliente']) ? $ot['Ordentrabajo']['observaciones_cliente'] : ""); ?>&nbsp;</td>
        </tr>

    <?php endforeach;?>
    <?php }?>
