<?php
App::uses('AppController', 'Controller');
App::uses('FacturasController', 'Controller');
App::uses('CuentasclientesController', 'Controller');

class ReportesController extends AppController
{
    // obtiene estadisticas r
    public function estadisticastortas()
    {
        $this->loadModel('Deposito');

        $this->autoRender = false;

        $posData = $this->request->data;

        $fechaIncial = '';
        $fechaFinal = '';
        if (!empty($posData)) {
            $fechaIncial = $posData['fechaInicial'] . ' 00:00:00';
            $fechaFinal = $posData['fechaFinal'] . ' 23:59:59';
        }

        $tortas = [];

        $tortas[] = $this->obtnerAlertasOrdenes($fechaIncial, $fechaFinal);

        $tortas[] = $this->obtenerTecnicosOrdenes($fechaIncial, $fechaFinal);

        $tortas[] = $this->obtenerClientesVisitas($fechaIncial, $fechaFinal);

        $tortas[] = $this->obtenerVentasProductos($fechaIncial, $fechaFinal);

        $tortas[] = $this->obtenerVentasServicios($fechaIncial, $fechaFinal);

        $empresaId = $this->Auth->user('empresa_id');
        $bodegas = $this->Deposito->obtenerBodegasEstadisticas($empresaId);
        foreach ($bodegas as $key => $val) {
            $tortas[] = $this->obtenerUtilidadBodegas($key, $val, $fechaIncial, $fechaFinal);
        }

        echo json_encode(array('resp' => $tortas));

    }

    public function obtenerUtilidadBodegas($bodegaId, $bodega, $fechaIncial, $fechaFinal)
    {

        $this->loadModel('Utilidade');

        $arrTitulos = [];
        $seriesData = [];

        $utilidad = $this->Utilidade->obtnerVentasBodega($bodegaId, $fechaIncial, $fechaFinal);
        foreach ($utilidad as $ut) {
            $arrTitulos[] = $ut['P']['descripcion'];

            $seriesData[] = [
                'value' => $ut['0']['contador'],
                'name' => $ut['P']['descripcion'],
            ];
        }

        $utilidades = [
            'titulo' => $bodega,
            'legend_data' => $arrTitulos,
            'series_data' => $seriesData,
        ];

        return $utilidades;

    }

    public function obtenerVentasServicios($fechaInicial, $fechaFinal)
    {
        $this->loadModel('Utilidade');
        $empresaId = $this->Auth->user('empresa_id');

        $productosVendidos = $this->Utilidade->obtnerVentasProdServ($empresaId, 's', $fechaInicial, $fechaFinal);

        $arrTitulos = [];
        $seriesData = [];

        foreach ($productosVendidos as $pv) {
            $arrTitulos[] = $pv['P']['descripcion'];

            $seriesData[] = [
                'value' => $pv['0']['contador'],
                'name' => $pv['P']['descripcion'],
            ];
        }

        $facturasCli = [
            'titulo' => 'Servicios mas vendidos',
            'legend_data' => $arrTitulos,
            'series_data' => $seriesData,
        ];

        return $facturasCli;
    }

    public function obtenerVentasProductos($fechaIncial, $fechaFinal)
    {
        $this->loadModel('Utilidade');
        $empresaId = $this->Auth->user('empresa_id');

        $productosVendidos = $this->Utilidade->obtnerVentasProdServ($empresaId, 'p', $fechaIncial, $fechaFinal);

        $arrTitulos = [];
        $seriesData = [];

        foreach ($productosVendidos as $pv) {
            $arrTitulos[] = $pv['P']['descripcion'] . ' - ' . $pv['DP']['descripcion'];

            $seriesData[] = [
                'value' => $pv['0']['contador'],
                'name' => $pv['P']['descripcion'] . ' - ' . $pv['DP']['descripcion'],
            ];
        }

        $facturasCli = [
            'titulo' => 'Productos mas vendidos',
            'legend_data' => $arrTitulos,
            'series_data' => $seriesData,
        ];

        return $facturasCli;

    }

    public function obtenerClientesVisitas($fechaIncial, $fechaFinal)
    {
        $this->loadModel('Factura');
        $empresaId = $this->Auth->user('empresa_id');

        $facturasClientes = $this->Factura->obtenerFactuasClientes($empresaId, $fechaIncial, $fechaFinal);

        $arrTitulos = [];
        $seriesData = [];

        foreach ($facturasClientes as $fc) {
            $arrTitulos[] = $fc['C']['nombre'];

            $seriesData[] = [
                'value' => $fc['0']['contador'],
                'name' => $fc['C']['nombre'],
            ];
        }

        $facturasCli = [
            'titulo' => 'Cliente fiel',
            'legend_data' => $arrTitulos,
            'series_data' => $seriesData,
        ];

        return $facturasCli;

    }

    public function obtenerTecnicosOrdenes($fechaIncial, $fechaFinal)
    {
        $this->loadModel('Ordentrabajo');
        $empresaId = $this->Auth->user('empresa_id');

        $ordenTecnicos = $this->Ordentrabajo->obtenerOrdenesTecnicosTortas($empresaId, $fechaIncial, $fechaFinal);

        $arrTitulos = [];
        $seriesData = [];

        foreach ($ordenTecnicos as $ot) {
            $arrTitulos[] = $ot['U']['nombre'];

            $seriesData[] = [
                'value' => $ot['0']['contador'],
                'name' => $ot['U']['nombre'],
            ];

        }

        $ordenesTec = [
            'titulo' => 'Ordenes - Técnicos',
            'legend_data' => $arrTitulos,
            'series_data' => $seriesData,
        ];

        return $ordenesTec;
    }

    public function obtnerAlertasOrdenes($fechaIncial, $fechaFinal)
    {
        $this->loadModel('Alertaordene');
        $empresaId = $this->Auth->user('empresa_id');

        $alertasOrdenes = $this->Alertaordene->obtieneAlertaOrdenesTortas($empresaId, $fechaIncial, $fechaFinal);

        $arrTitulos = [];
        $seriesData = [];

        foreach ($alertasOrdenes as $ao) {
            $arrTitulos[] = $ao['A']['descripcion'];

            $seriesData[] = [
                'value' => $ao['0']['contador'],
                'name' => $ao['A']['descripcion'],
            ];

        }

        $alertas = [
            'titulo' => 'Alertas - Ordenes',
            'legend_data' => $arrTitulos,
            'series_data' => $seriesData,
        ];

        return $alertas;

    }

    /**
     * Funcion que descarga el listado de ciudades -> prueba
     */
    public function descargarListaCiudades()
    {
        $this->loadModel('Ciudade');
        $ciudades = $this->Ciudade->obtenerListaCiudades();

        $texto_tit = "LISTA DE CIUDADES";
        $this->set(compact('ciudades'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $ciudades);
        $arr_titulos = array(
            0 => __('ID'),
            1 => __('NOMBRE'),
            2 => __('PRUEBA'),
        );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }

    /**
     * Funcion que descarga el inventario de productos
     */
    public function descargarInventario()
    {
        $this->loadModel('Cargueinventario');

        $empresaId = $this->Auth->user('empresa_id');

        $data = array();
        if (!empty($_POST['rpproducto'])) {
            $data['Cargueinventario.producto_id'] = $_POST['rpproducto'];
        }

        if (!empty($_POST['rpdeposito'])) {
            $data['Cargueinventario.deposito_id'] = $_POST['rpdeposito'];
        }

        $data['Cargueinventario.empresa_id'] = $empresaId;

        /*se obtiene el stock que tiene la empresa en el inventario*/
        $cargueinventarios = $this->Cargueinventario->obtenerCargueInventario($data);

        $texto_tit = "Inventario de Productos";
        $this->set(compact('cargueinventarios'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $cargueinventarios);
        $arr_titulos = array(
            0 => __('Producto'),
            1 => __('Codigo'),
            2 => __('Deposito'),
            3 => __('Proveedor'),
            4 => __('Costo producto'),
            5 => __('Valor venta'),
            6 => __('Existencia actual'),
            7 => __('Fecha de cargue'),
        );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');

    }

    /**
     * Suma una cantidad de dias especifica a una fecha dada
     */
    public function sumarDiasFecha($fecha,$dias){
        if(empty($dias)){
            $dias = 30;
        }
        $fechaNew = new DateTime($fecha);
        $fechaNew->add(new DateInterval('P' . $dias . 'D'));
        $fechaFin = $fechaNew->format('Y-m-d');
        return $fechaFin;          
    }

    /**
     * Retorna la diferencia entre dos fechas
     */
    public function diffFechas($fechaLimite, $fechaActual){
        $datetime1 = date_create($fechaLimite);
        $datetime2 = date_create($fechaActual);
        $interval = date_diff($datetime1, $datetime2);
        $dias = $interval->format('%R%a');
        return $dias;            
    }       

    /**
     * Genera el reporte de cuentas por cobrar
     */
    public function descargarCuentasClientes() {

        $empresaId = $this->Auth->user('empresa_id');
        $cuentasclientes = $this->Cuentascliente->obtenerCuentasClientes($empresaId);

        for($i = 0; $i < count($cuentasclientes); $i++){
            if($cuentasclientes[$i]['Cuentascliente']['cliente_id'] != ""){
                $cuentasclientes[$i]['Cuentascliente']['fechalimitepago'] = $this->sumarDiasFecha($cuentasclientes[$i]['Cuentascliente']['created'],$cuentasclientes[$i]['CL']['diascredito']);
            }else{
                $infoVentaRapida = $this->Ventarapida->obtenerInfoVentaFactId($cuentasclientes[$i]['Factura']['id']);

                if(count($infoVentaRapida) > 0){
                    $cuentasclientes[$i]['CL']['nombre'] = $infoVentaRapida['Ventarapida']['cliente'];
                }else{
                    $cuentasclientes[$i]['CL']['nombre'] = "";
                }
                $cuentasclientes[$i]['Cuentascliente']['fechalimitepago'] = "";
            }
       
            $diff = $this->diffFechas($fechaActual, $cuentasclientes[$i]['Cuentascliente']['fechalimitepago']);                               
                
            if($diff <= '0'){
                $cuentasclientes[$i]['Cuentascliente']['diasvencido'] = $diff;
            }else{
                $cuentasclientes[$i]['Cuentascliente']['diasvencido'] = $diff;
            }
        }
        
        $texto_tit = "Cuentas por Cobrar";
        $this->set(compact('cuentasclientes'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $cuentasclientes);
        $arr_titulos = array(
            'Cliente',
            '# Factura',
            'Total obligacion',
            'Fecha factura',
            'Dias credito',
            'Fecha limite',
            'Dias vencido',
            'Usuario'            
        );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');        

    }

    /**
     * Descarga el listado de cuentas pendientes por pagar
     */
    public function descargarCuentasPendientes()
    {
        $this->loadModel('Cuentaspendiente');

        if (!empty($_POST['rpproducto'])) {
            $paginate['Cuentaspendiente.producto_id'] = $_POST['rpproducto'];
        }

        if (!empty($_POST['rpdeposito'])) {
            $paginate['Cuentaspendiente.deposito_id'] = $_POST['rpdeposito'];
        }

        if (!empty($_POST['rpproveedor'])) {
            $paginate['Cuentaspendiente.proveedore_id'] = $_POST['rpproveedor'];
        }

        if (!empty($_POST['rpfactura'])) {
            $paginate['LOWER(Cuentaspendiente.numerofactura) LIKE'] = '%' . strtolower($_POST['rpfactura']) . '%';
        }

        $paginate['Cuentaspendiente.empresa_id'] = $this->Auth->user('empresa_id');

        $paginate['Cuentaspendiente.eliminar'] = 0;

        $cuentaspendientes = $this->Cuentaspendiente->obtenerCuentasPendientes($paginate);

        $texto_tit = "Cuentas por Pagar";
        $this->set(compact('cuentaspendientes'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $cuentaspendientes);
        $arr_titulos = array(
            'Producto',
            'Codigo',
            'Costo producto',
            'Cantidad',
            'Costo total',
            'Proveedor',
            'No. Factua',
            'Fecha factura',
            'Dias credito',
        );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }

    /**
     * Descarga el cierre diario de cuentas
     */
    public function descargarCierreDiario()
    {
        $this->loadModel('Ventarapida');
        $this->loadModel('Cargueinventario');
        $this->loadModel('Gasto');
        $this->loadModel('Cuenta');
        $this->loadModel('Abonofactura');
        $this->loadModel('Prefactura');
        $this->loadModel('Tipopago');
        $this->loadModel('Factura');

        $cuenta = "";
        if (!empty($_POST['rpcuenta'])) {
            $cuenta = $_POST['rpcuenta'];
        }

        if (!empty($_POST['rpfechacierre'])) {
            $fechaCierre = $_POST['rpfechacierre'];
        } else {
            $fechaCierre = date('Y-m-d');
        }

        $empresaId = $this->Auth->user('empresa_id');

        /*se obtienen las facturas generadas durante la fecha actual o la seleccionada*/
        $detFacts = $this->Factura->obtenerFacturasTipoPagos($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $cuenta);
        $ventasFactura = [];
        $estadoCuentas = [];
        if (!empty($detFacts)) {
            foreach ($detFacts as $df) {
                if (!isset($estadoCuentas[$df['FCV']['cuenta_id']])) {
                    $estadoCuentas[$df['FCV']['cuenta_id']]['ing_ventas'] = 0;
                }

                //se obtiene el detalle de la venta de cada factura por cada tipo de pago
                $ventasFactura[] = [
                    'fact_codigo' => $df['Factura']['codigo'],
                    'consecutivodian' => !empty($df['Factura']['consecutivodian']) ? $df['Factura']['consecutivodian'] : "",
                    'cliente_nit' => !empty($df['Cliente']['nit']) ? $df['Cliente']['nit'] : "",
                    'cliente_nombre' => !empty($df['Cliente']['nombre']) ? $df['Cliente']['nombre'] : "",
                    'usuario_nombre' => !empty($df['Usuario']['nombre']) ? $df['Usuario']['nombre'] : "",
                    'usuario_identificacion' => !empty($df['Usuario']['identificacion']) ? $df['Usuario']['identificacion'] : "",
                    'fcv_cuenta' => $df['FCV']['cuenta_id'],
                    'fcv_tipopago' => $df['FCV']['tipopago_id'],
                    'fcv_valor' => $df['FCV']['valor'],
                ];

                //se obtiene el ingreso por venta en cada cuenta
                $estadoCuentas[$df['FCV']['cuenta_id']]['ing_ventas'] += $df['FCV']['valor'];
            }
        }

        //se obtiene el registro de gastos de una cuenta, solo el gasto, no el gasto por traslado
        $infoGastos = $this->Gasto->obtenerGastosTrasladosEmpresa($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $cuenta);
        if (!empty($infoGastos)) {
            foreach ($infoGastos as $gts) {
                if (!isset($estadoCuentas[$gts['Gasto']['cuenta_id']]['gastos'])) {
                    $estadoCuentas[$gts['Gasto']['cuenta_id']]['gastos'] = 0;
                }
                $estadoCuentas[$gts['Gasto']['cuenta_id']]['gastos'] += $gts['Gasto']['valor'];
            }
        }

        //se obtiene el registro de traslados
        $infoTraslados = $this->Gasto->obtenerIngresosTrasladosEmpresa($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $cuenta);
        //se obtiene la sumatoria de ingresos por traslado por cuenta
        foreach ($infoTraslados as $it) {

            //ingresos por traslados
            if (!isset($estadoCuentas[$it['Gasto']['cuentadestino']]['ing_traslados'])) {
                $estadoCuentas[$it['Gasto']['cuentadestino']]['ing_traslados'] = 0;
            }

            //gastos por traslados
            if (!isset($estadoCuentas[$it['Gasto']['cuenta_id']]['gasto_traslados'])) {
                $estadoCuentas[$it['Gasto']['cuenta_id']]['gasto_traslados'] = 0;
            }

            $estadoCuentas[$it['Gasto']['cuentadestino']]['ing_traslados'] += $it['Gasto']['valor'];
            $estadoCuentas[$it['Gasto']['cuenta_id']]['gasto_traslados'] += $it['Gasto']['valor'];
        }

        //se obtiene el listado de cuentas de la empresa
        $listCuenta = $this->Cuenta->obtenerCuentasEmpresa($empresaId);

        //se obtiene la lista de tipos de pagos
        $listTipoPago = $this->Tipopago->obtenerListaTiposPagos($empresaId);

        //se obtienen los abonos de la fecha indicada para las prefacturas
        $arrAbonos = [];
        $abonosPrefactura = $this->Abonofactura->obtenerAbonosCierreDiario($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, "1");
        if (!empty($abonosPrefactura)) {
            foreach ($abonosPrefactura as $abnpf) {

                if (!isset($estadoCuentas[$abnpf['Abonofactura']['cuenta_id']]['abono_prefact'])) {
                    $estadoCuentas[$abnpf['Abonofactura']['cuenta_id']]['abono_prefact'] = 0;
                }

                $arrAbonos[] = [
                    'fecha' => $abnpf['Abonofactura']['created'],
                    'valor' => $abnpf['Abonofactura']['valor'],
                    'cliente' => $abnpf['CL']['nombre'],
                    'cuenta' => $abnpf['C']['descripcion'],
                ];

                $estadoCuentas[$abnpf['Abonofactura']['cuenta_id']]['abono_prefact'] += $abnpf['Abonofactura']['valor'];

            }
        }

        $abonosFactura = $this->Abonofactura->obtenerAbonosCierreDiario($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, "0");
        if (!empty($abonosFactura)) {
            foreach ($abonosFactura as $abnf) {

                if (!isset($estadoCuentas[$abnf['Abonofactura']['cuenta_id']]['abono_fact'])) {
                    $estadoCuentas[$abnf['Abonofactura']['cuenta_id']]['abono_fact'] = 0;
                }

                $arrAbonos[] = [
                    'fecha' => $abnf['Abonofactura']['created'],
                    'valor' => $abnf['Abonofactura']['valor'],
                    'cliente' => $abnf['CL']['nombre'],
                    'cuenta' => $abnf['C']['descripcion'],
                ];

                $estadoCuentas[$abnf['Abonofactura']['cuenta_id']]['abono_fact'] += $abnf['Abonofactura']['valor'];

            }
        }

        //se obtiene el estado actual de las cuentas
        $ctasEstAct = $this->Cuenta->obtenerInfoCuentas($empresaId);
        if (!empty($ctasEstAct)) {
            foreach ($ctasEstAct as $eaCtas) {
                if (!isset($estadoCuentas[$eaCtas['Cuenta']['id']]['estado_actual'])) {
                    $estadoCuentas[$eaCtas['Cuenta']['id']]['estado_actual'] = 0;
                }

                $estadoCuentas[$eaCtas['Cuenta']['id']]['estado_actual'] += $eaCtas['Cuenta']['saldo'];
            }
        }

        $this->set(compact('ventasFactura'));
        $this->set('rows', $detFacts);
        $this->set(compact('ventasFactura', 'listCuenta', 'fechaCierre', 'rpfechacierre', 'infoTraslados'));
        $this->set(compact('infoGastos', 'arrAbonos', 'flgCierre', 'rpcuenta', 'estadoCuentas', 'listTipoPago'));
        $this->render('export_xls', 'export_xls');
    }

    /**
     * Se descargan las facturas
     */
    public function descargarFacturas(){

        $this->loadModel('Factura');
        

        if(!empty($_POST['rpcodigo'])){
            $paginate['Factura.codigo LIKE'] = '%' . $_POST['rpcodigo'] . '%';
        } 

        if(!empty($_POST['rpconsecutivo'])){
            $paginate['Factura.consecutivodian LIKE'] = '%' . $_POST['rpconsecutivo'] . '%';
        }             

        if(!empty($_POST['rpvendedor'])){
            $paginate['Factura.usuario_id'] = $_POST['rpvendedor'];
        }            

        if(!empty($_POST['rpfecha']) && !empty($_POST['rpfechaFin'])){
            $paginate['Factura.created BETWEEN ? AND ?'] = array($_POST['rpfecha'], $_POST['rpfechaFin']);
        }            

        if(!empty($_POST['rpvencimiento'])){
            $paginate['Factura.fechavence BETWEEN ? AND ?'] = array($_POST['rpvencimiento'] . ' 00:00:01', $_POST['rpvencimiento'] . ' 23:59:59');
        }            

        if(!empty($_POST['rptipopago'])){
            $paginate['FCV.tipopago_id'] = $_POST['rptipopago'];
        }        
        
        if($_POST['esfactura'] == '1'){
            $paginate['Factura.factura'] = true;
        }else if($_POST['esfactura'] == '0'){
            $paginate['Factura.factura'] = false;
        }

        $empresaId = $this->Auth->user('empresa_id');
        $paginate['Factura.empresa_id'] = $empresaId;

        $facturas = $this->Factura->obtenerFacturas($paginate); 

        $arrFacts = array();
        foreach ($facturas as $f){ 

            if($f['Factura']['factura']){
                $valorBase = 0;
                $descuento = 0;

                if (!empty($f['FD']['impuesto'])){
                    $valorBase = ceil($f['FD']['costoventa'] / (($f['FD']['impuesto'] / 100) +1));
                } else {
                    $valorBase = ceil($f['FD']['costoventa']);
                }

                if (!empty($f['FD']['porcentaje'])){
                    $descuento = ceil(($valorBase * ($f['FD']['porcentaje'])/100) * $f['FD']['cantidad']);
                }

                $valorXCantidad = $valorBase * $f['FD']['cantidad'];
                $iva = ceil(($valorXCantidad - $descuento) * ($f['FD']['impuesto']/100));
            }else{
                $valorBase = $f['FD']['costoventa'];
                $valorXCantidad = ceil($valorBase * $f['FD']['cantidad']);
                $descuento = $valorXCantidad * ($f['FD']['porcentaje']/100);
            }


            $arrFacts[] = [
                'consecutivo' => !empty($f['Factura']['consecutivodian']) ? $f['Factura']['consecutivodian'] : $f['Factura']['codigo'],
                'fecha' => $f['Factura']['created'],
                'nombreCliente' => $f['CL']['nombre'],
                'identificacion' => $f['CL']['nit'],
                'telefono' => $f['CL']['celular'],
                'cantidad' => $f['FD']['cantidad'],
                'producto' => $f['PR']['descripcion'],
                'valor' => $valorBase,
                'valor_ttal' => $valorBase * $f['FD']['cantidad'],
                'descuento' => $descuento,
                'subtotal' => ($valorBase * $f['FD']['cantidad']) - $descuento,
                'iva' => $iva
            ];
        }
                
        $texto_tit = "Facturas";
        $this->set(compact('arrFacts'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $arrFacts);
        $arr_titulos = array(
            'Consecutivo',
            'Fecha',
            'Nombre Cliente',
            'Identificacion',
            'Telefono',
            'Cantidad',
            'Descripcion',
            'Valor Unitario',
            'Valor Total',
            'Descuento',
            'Subtotal',
            'IVA'   
            );

        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');       
        
    }
    
    /**
     * Se genera el reporte de utilidades
     */
    public function descargarUtilidades(){
        $this->loadModel('Utilidade');
        
        if(!empty($_POST['rpfechIni']) && !empty($_POST['rpfechFin'])){
            $fechaInicio = $_POST['rpfechIni'];
            $fechaFin = $_POST['rpfechFin'];
        }else{
            $fechaInicio = date('Y-m-d');
            $fechaFin = date('Y-m-d');
        }

        $empresaId = $this->Auth->user('empresa_id'); 

        /*se recorre el registro de las utilidades*/
        $utilidades = $this->Utilidade->obtenerUtilidadesPorEmpresa($fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59', $empresaId);

        $texto_tit = "Utilidad por Ventas";
        $this->set(compact('utilidades'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $utilidades);
        $arr_titulos = array(
            'Producto',
            'Referencia',
            'Deposito',
            'Proveedor',
            'Vendedor',
            'Costo del Producto',
            'Costo Total',
            'Cantidad',
            'Precio Venta',
            'Total Venta',
            'Utilidad Bruta',
            'Utilidad Porcentual',
            'Tipo de Utilidad',
            'Fecha',
            'Factura',
        );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }
    /**
     * Se genera el reporte de PreFacturas
     */
    public function descargarPreFacturas()
    {
        $this->loadModel('Prefactura');
        $this->loadModel('Estadosprefactura');
        $empresaId = $this->Auth->user('empresa_id');
        $prefacturas = $this->Prefactura->obtenerPrefacturas(null, null, null, $empresaId);
        $estados = $this->Estadosprefactura->obtenerListaEstados();
        $texto_tit = "Prefacturas";
        $this->set(compact('prefacturas'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $prefacturas);

        $arr_titulos = array(
            'Cliente',
            'Veh&iacute;culo',
            'Fecha',
            'Estado',
            'Observaci&oacute;n',
        );

        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');

    }

    /**
     * Se genera el reporte de Ordenes de trabajo
     */
    public function descargarOrdenesTrabajo()
    {

        $this->loadModel('Ordentrabajo');
        $filterOT = array('OE.ordenfinal <>' => '1');
        $ordenes = $this->Ordentrabajo->obtenerOrdenesTrabajo($filterOT);
        $empresaId = $this->Auth->user('empresa_id');
        $texto_tit = "Orden trabajo";
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $ordenes);
        $arr_titulos = array(
            'C&oacute;digo',
            'Mec&aacute;nico',
            'Cliente',
            'Veh&iacute;culo',
            'Estado',
            'Observaci&oacute;n Mec&aacute;nico',
            'Observaci&oacute;n Cliente',
        );
        //aqui se debio solucionar problema de comas.
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }
    /**
     * Se genera el reporte de Factura Cuenta Valores
     */
    public function descargarFacturaCuentaValores()
    {
        $this->loadModel('Cuenta');
        $this->loadModel('Tipopago');
        $this->loadModel('FacturaCuentaValore');
        $empresaId = $this->Auth->user('empresa_id');

        if (!empty($_POST['codigoDian'])) {
            $filter = null;
            $codigoDian = $_POST['codigoDian'];
            $filter['F.consecutivodian'] = $_POST['codigoDian'];
        }

        if (!empty($_POST['numeroFactura'])) {
            $filter = null;
            $numeroFactura = $_POST['numeroFactura'];
            $filter['F.codigo'] = $_POST['numeroFactura'];
        }

        if (!empty($_POST['fechaInicio']) && !empty($_POST['fechaFin'])) {
            $fechaInicio = $_POST['fechaInicio'];
            $fechaFin = $_POST['fechaFin'];
            $filter['F.created BETWEEN ? AND ?'] = array($_POST['fechaInicio'] . ' 00:00:01', $_POST['fechaFin'] . ' 23:23:59');
        }

        if (!empty($_POST['tipocuentas'])) {
            $filter = null;
            $tipocuentas = $_POST['tipocuentas'];
            $filter['FacturaCuentaValore.cuenta_id'] = $_POST['tipocuentas'];
        }
        if (!empty($_POST['tipopagos'])) {
            $filter = null;
            $tipopagos = $_POST['tipopagos'];
            $filter['FacturaCuentaValore.tipopago_id'] = $_POST['tipopagos'];
        }

        $filter['F.empresa_id'] = $empresaId;

        $pagosFacturas = $this->FacturaCuentaValore->obtenerMetodosPagosFacturas($filter);

        $texto_tit = "Factura cuenta valor";
        $this->set(compact('pagosFacturas'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $pagosFacturas);
        $arr_titulos = array(
            'Consecutivo factura',
            'Consecutivo Dian',
            'Fecha',
            'Cuenta',
            'Tipo pago',
            'Valor pago',
        );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');       
    }
    /**
     * Se genera el reporte de Factura reporte facturas clientes
     */
    public function descargarReporteFacturasClientes()
    {
        $this->loadModel('Usuario');
        $this->loadModel('Factura');
        $filtros = [];

        if (!empty($_POST['mecanico'])) {
            $filtros['O.usuario_id'] = $_POST['mecanico'];
        }

        if (!empty($_POST['fecha_inicio']) && empty($_POST['fecha_fin'])) {
            $filtros['Factura.created BETWEEN ? AND ?'] = array(
                $_POST['fecha_inicio'] . ' 00:00:00',
                date('Y-m-d') . ' 23:59:59');
        }

        if (empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin'])) {
            $filtros['Factura.created BETWEEN ? AND ?'] = array(
                date('Y-01-01' . ' 00:00:01'),
                $_POST['fecha_fin'] . ' 23:59:59');
        }

        if (!empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin'])) {
            $filtros['Factura.created BETWEEN ? AND ?'] = array(
                $_POST['fecha_inicio'] . ' 00:00:01',
                $_POST['fecha_fin'] . ' 23:59:59');
        }
        if (!empty($_POST['nitCliente'])) {
            $filtros['C.nit'] = $_POST['nitCliente'];
        }
        if (!empty($_POST['placa'])) {
            $filtros['V.placa'] = $_POST['placa'];
        }

        $empresaId = $this->Auth->user('empresa_id');

        $facturaClientes = $this->Factura->obtenerFacturasClientes($empresaId, $filtros);

        $texto_tit = "Servicios por cliente";
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $facturaClientes);
        $arr_titulos = array(
            'Cliente',
            'Identificacion Cliente',
            'Celular Cliente',
            'Placa Vehiculo',
            'Tecnico',
            'Codigo Factura',
            'Fecha Factura',
            'Cantidad Facturas',
            'Valor total Facturas',
        );
        $this->set(compact('facturaClientes'));
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }

    public function descargarListaCierreDiario()
    {
        $this->loadModel('Cuenta');
        $this->loadModel('Cierrecaja');

        $rpfech = $_POST['rpfech'];
        $cajaId = $_POST['cajaId'];

        $empresaId = $this->Auth->user('empresa_id');

        //se obtiene el cierre de cajas
        $cierrediario = $this->Cierrecaja->obtenerCierreCajas($empresaId, $rpfech, $cajaId);

        //se obtiene el listado de cajas
        $listCuentas = $this->Cuenta->obtenerCuentasEmpresa($empresaId);

        $texto_tit = "Lista Cierre Diario";
        $this->set(compact('cierrediario'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $cierrediario);
        $arr_titulos = array(
            'Caja',
            'Saldo inicial',
            'Ventas',
            'Gastos',
            'Ing. Traslados',
            'Gas. Traslados',
            'Abonos',
            'Total',
        );
        $this->set('titulos', $arr_titulos);
        $this->set('listCuentas', $listCuentas);
        $this->render('export_xls', 'export_xls');

    }

    /**
     * Se obtienen los estados iniciales de una caja en el dia seleccionado
     * @param type $listCuenta
     * @param type $fechaCierre
     * @param type $empresaId
     * @return type
     */
    public function obtenerSaldosIniciales($listCuenta, $fechaCierre, $empresaId)
    {
        $this->loadModel('Gasto');
        $this->loadModel('Cuenta');
        $estCuenta = [];

        foreach ($listCuenta as $key => $val) {

            $estCuenta[$key] = [
                'nombre' => $val,
                'saldo' => !empty($estCuenta[$key]['saldo']) ? $estCuenta[$key]['saldo'] : 0,
                'gasto' => !empty($estCuenta[$key]['gasto']) ? $estCuenta[$key]['gasto'] : 0,
                'gasto_traslado' => !empty($estCuenta[$key]['gasto_traslado']) ? $estCuenta[$key]['gasto_traslado'] : 0,
                'ingreso_traslado' => !empty($estCuenta[$key]['ingreso_traslado']) ? $estCuenta[$key]['ingreso_traslado'] : 0,
                'ventas' => !empty($estCuenta[$key]['ventas']) ? $estCuenta[$key]['ventas'] : 0,
                'abonos' => !empty($estCuenta[$key]['abonos']) ? $estCuenta[$key]['abonos'] : 0,
            ];

            //se obtiene el saldo de la cuenta
            $cuentas = $this->Cuenta->obtenerDatosCuentaId($key);
            if (!empty($cuentas)) {
                $estCuenta[$key]['saldo'] = $cuentas['Cuenta']['saldo'];
            }

            //se obtienen los gastos de la cuenta
            $gastos = $this->Gasto->obtenerGastosCuenta($fechaCierre, $empresaId, $key);
            if (!empty($gastos)) {
                foreach ($gastos as $gt) {
                    $estCuenta[$key]['saldo'] += $gt['Gasto']['valor'];
                    $estCuenta[$key]['gasto'] += $gt['Gasto']['valor'];
                }
            }

            //se obtienen los ingresos por traslados en la cuenta
            $traslados = $this->Gasto->obtenerIngresosTrasladosCuenta($fechaCierre, $empresaId, $key);

            if (!empty($traslados)) {
                foreach ($traslados as $tr) {
                    $estCuenta[$key]['saldo'] -= $tr['Gasto']['valor'];
                    $estCuenta[$key]['ingreso_traslado'] += $tr['Gasto']['valor'];
                }
            }

            //se obtienen las ventas registradas a una cuenta
            $facturas = $this->Factura->obtenerFacturasCierreDiario($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $key);
            if (!empty($facturas)) {
                foreach ($facturas as $ft) {
                    $estCuenta[$key]['saldo'] -= $ft['Factura']['pagocontado'];
                    $estCuenta[$key]['ventas'] += $ft['Factura']['pagocontado'];
                }
            }

            //se obtienen los abonos realizados la cuenta que aun no han sido facturados
            $arrAbonos = $this->Abonofactura->obtenerAbonosACuenta($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $key);
            if (!empty($arrAbonos)) {
                foreach ($arrAbonos as $abn) {
                    $estCuenta[$key]['saldo'] -= $abn['Abonofactura']['valor'];
                    $estCuenta[$key]['abonos'] += $abn['Abonofactura']['valor'];
                }
            }
        }

        return $estCuenta;
    }

    /**
     * Se obtiene el registro de gastos en excel
     */
    public function descargarGastos()
    {
        $this->loadModel('Cuenta');
        $this->loadModel('Gasto');
        $this->loadModel('Itemsgasto');
        $this->loadModel('Empresa');
        $this->loadModel('Relacionempresa');

        $empresaId = $this->Auth->user('empresa_id');
        $fechainicio = $_POST['rpfechainicio'];
        $fechafin = $_POST['rpfechafin'];
        $item = isset($_POST['rpitem']) && !empty($_POST['rpitem']) ? $_POST['rpitem'] : "";

        $arrEmpresa = [];

        //se obtiene la infomacion de la empresa
        $infoEmpresa = $this->Empresa->obtenerEmpresaPorId($empresaId);

        $arrEmpresa[] = [
            'id' => $infoEmpresa['Empresa']['id'],
            'nombre' => $infoEmpresa['Empresa']['nombre'],
            'tipo' => 'P',
        ];

        //se obtinen las empresas relacionadas a la empresa
        $infoEmpresasRel = $this->Relacionempresa->obtenerInformacionEmpresas($empresaId);
        if (!empty($infoEmpresasRel)) {
            foreach ($infoEmpresasRel as $ier) {
                $arrEmpresa[] = [
                    'id' => $ier['Relacionempresa']['id'],
                    'nombre' => $ier['Relacionempresa']['nombre'],
                    'tipo' => 'S',
                ];
            }
        }

        //se obtiene el listado de las cuentas
        $listCuentas = $this->Cuenta->obtenerCuentasEmpresa($empresaId);

        $infoGastos = $this->Gasto->obtenerGastosEmpresa($fechainicio . " 00:00:00", $fechafin . " 23:59:59", $empresaId, "", $item);

        //se obtiene el listado de items para gastos
        $itemsGasto = $this->Itemsgasto->obtenerListaItemsGastos($empresaId);

        $gastos = [];
        $ttalGastos = 0;
        if (!empty($infoGastos)) {
            foreach ($infoGastos as $gts) {

                if($gts['Gasto']['traslado']) { continue; }

                $empRelGasto = "";
                foreach ($arrEmpresa as $empRel) {
                    if ($empRel['id'] == $gts['Gasto']['empresaasg_id'] && $empRel['tipo'] == $gts['Gasto']['tipoempresa']) {
                        $empRelGasto = $empRel['nombre'];
                    }
                }

                $gastos[] = [
                    'id' => $gts['Gasto']['id'],
                    'descripcion' => $gts['Gasto']['descripcion'],
                    'usuario' => $gts['Usuario']['nombre'],
                    'fechagasto' => $gts['Gasto']['fechagasto'],
                    'created' => $gts['Gasto']['created'],
                    'valor' => $gts['Gasto']['valor'],
                    'cuenta' => $gts['Cuenta']['descripcion'],
                    'traslado' => $gts['Gasto']['traslado'],
                    'cuentadestino' => !empty($gts['Gasto']['cuentadestino']) ? $listCuentas[$gts['Gasto']['cuentadestino']] : "",
                    'itemsgasto' => !empty($gts['Gasto']['itemsgasto_id']) ? $itemsGasto[$gts['Gasto']['itemsgasto_id']] : "",
                    'empRel' => $empRelGasto,
                ];
            }
        }

        $texto_tit = "Gastos Empresa";
        $this->set(compact('gastos'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $gastos);
        $arr_titulos = array(
            0 => __('Descripcion'),
            1 => __('Usuario'),
            2 => __('Empresa'),
            3 => __('Fecha Gasto'),
            4 => __('Fecha Registro'),
            5 => __('Item del Gasto'),
            6 => __('Cuenta Origen'),
            7 => __('Tipo'),
            8 => __('Cuenta Destino'),
            9 => __('Valor'),
        );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }

    public function descargarRotacion()
    {
        $this->loadModel('Utilidade');

        if (!empty($_POST['rpfechIni']) && !empty($_POST['rpfechFin'])) {
            $fechaInicio = $_POST['rpfechIni'];
            $fechaFin = $_POST['rpfechFin'];
        } else {
            $fechaInicio = date('Y-m-d');
            $fechaFin = date('Y-m-d');
        }

        $empresaId = $this->Auth->user('empresa_id');

        $days = $this->diffDates($fechaInicio, $fechaFin);

        /*se recorre el registro de las utilidades*/
        $rotacion = $this->Utilidade->obtenerRotacion($fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59', $empresaId);

        $arrRotation = [];
        foreach ($rotacion as $val) {
            $arrProd['prom_venta'] = $val['0']['quant'] / $days;
            $arrProd['prom_precio_venta'] = $val['0']['precioventa'] / $val['0']['quant'];
            $arrProd['prom_utilidad_bruta'] = $val['0']['utilidadbruta'] / $val['0']['quant'];
            $arrProd['prom_utilidad_porc'] = $val['0']['utilidadporcentual'] / $val['0']['quant'];
            $arrProd['costo_producto'] = $val['0']['costoproducto'] / $val['0']['quant'];
            $arrProd['descripcion'] = $val['P']['descripcion'];

            $arrRotation[] = $arrProd;
        }

        $texto_tit = "Rotacion de productos";
        $this->set(compact('arrRotation'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $arrRotation);
        $arr_titulos = array(
            'Producto',
            'Promedio cantidad',
            'Promedio precio venta',
            'Promedio utilidad bruta',
            'Promedio utilidad porcentual',
            'Promedio costo producto',
        );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }

    public function diffDates($dateInit, $dateEnd)
    {
        $date1 = new DateTime($dateInit);
        $date2 = new DateTime($dateEnd);
        $diff = $date1->diff($date2);

        return $diff->days;
    }

    public function descargarCompras()
    {
        $this->loadModel('Proveedore');
        $this->loadModel('Usuario');
        $this->loadModel('Compra');
        $this->loadModel('Reteicaretefuente');
        $this->loadModel('Producto');
        $this->loadModel('Configuraciondato');

        $proveedorId = $_POST['proveedorId'];
        $usuarioId = $_POST['usuarioId'];
        $numFactura = $_POST['numFactura'];
        $FDesde = $_POST['FDesde'];
        $FHasta = $_POST['FHasta'];
        $typeTax = $_POST['type_tax'];

        $empresaId = $this->Auth->user('empresa_id');

        //se obtiene el listado de proveedores
        $listProv = $this->Proveedore->obtenerProveedoresEmpresa($empresaId);

        //se obtiene el listado de usuarios
        $listUsr = $this->Usuario->obtenerUsuarioEmpresa($empresaId);

        //se obtiene la informacion de las compras
        $compras = $this->Compra->obtenerCompras($proveedorId, $usuarioId, $numFactura, $FDesde, $FHasta, $empresaId);

        //se obtiene las categorias de compras
        $productos = $this->Producto->obtenerListaProductosEmpresa($empresaId);

        $strDato = "ivaCompra";
        $ivaCompra = $this->Configuraciondato->obtenerValorDatoConfig($strDato);         

        //se obtiene la lista de reteica retefuente
        $listRicaRfte = $this->Reteicaretefuente->obtenerListaReteicaRetefuente($empresaId);

        $arrInfoCompras = [];
        if (!empty($compras)) {
            foreach ($compras as $cmp) {

                $arrInfoCompras[] = [
                    'proveedor' => $listProv[$cmp['Compra']['proveedore_id']],
                    'fecha' => $cmp['Compra']['fecha'],
                    'num_factura' => $cmp['Compra']['numerofactura'],
                    'usuario' => $listUsr[$cmp['Compra']['usuario_id']],
                    'categoria' => $productos[$cmp['CCC']['producto_id']],
                    'valor' => $cmp['CCC']['costottal'],
                    'iva_prc' => $ivaCompra,
                    'iva_vlr' => $cmp['CCC']['vlriva'],
                    'retefuente_prc' => !empty($cmp['Compra']['prcretefuente']) ? ($cmp['Compra']['prcretefuente'] - 1) * 100 : 0,
                    'retefuente_vlr' => $cmp['Compra']['prcretefuente'] != 0 ? ($cmp['CCC']['valor'] * $cmp['Compra']['prcretefuente']) - $cmp['CCC']['valor'] : 0,
                    'reteica_prc' => !empty($cmp['Compra']['prcreteica']) ? ($cmp['Compra']['prcreteica'] - 1) * 100 : 0,
                    'reteica_vlr' => $cmp['Compra']['prcreteica'] != 0 ? ($cmp['CCC']['valor'] * $cmp['Compra']['prcreteica']) - $cmp['CCC']['valor'] : 0,
                ];

            }
        }

        if ($typeTax == '1') {
            $arr_titulos = array(
                'Proveedor',
                'Fecha factura',
                'Numero factura',
                'Usuario',
                'Categoria',
                'Valor',
                '% IVA',
                'Valor IVA',
                '% Retefuente',
                'Valor Retefuente',
                '% Reteica',
                'Valor Reteica',
                'Total');
        } else if ($typeTax == '2') {
            $arr_titulos = array(
                'Proveedor',
                'Fecha factura',
                'Numero factura',
                'Usuario',
                'Categoria',
                'Valor',
                '% IVA',
                'Valor IVA',
                'Total');
        } else if ($typeTax == '3') {
            $arr_titulos = array(
                'Proveedor',
                'Fecha factura',
                'Numero factura',
                'Usuario',
                'Categoria',
                'Valor',
                '% Retefuente',
                'Valor Retefuente',
                'Total');
        } else if ($typeTax == '4') {
            $arr_titulos = array(
                'Proveedor',
                'Fecha factura',
                'Numero factura',
                'Usuario',
                'Categoria',
                'Valor',
                '% Reteica',
                'Valor Reteica',
                'Total');
        } 
        
        $texto_tit = "Compras";
        $this->set(compact('arrInfoCompras', 'typeTax'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $arrInfoCompras);
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');         
        
    }

    /**
     * Se genera el reporte de productos de la vista /productos/index
     */
    public function descargarReporteProductos()
    {
        
        $usuarioAct = $this->Auth->user('id');
        $empresaId = $this->Auth->user('empresa_id');
        $codigo = $_POST['codigo'];
        $referencia = $_POST['referencia'];
        $nombre = $_POST['codigo'];
        $categoria = $_POST['categorias'];
        
        $this->loadModel('Categoria');
        $this->loadModel('Producto');

        if (isset($_POST['codigo']) && $_POST['codigo'] != "") {
            $filtros['LOWER(Producto.codigo) LIKE'] = '%' . strtolower($_POST['codigo']) . '%';
        }

        if (isset($_POST['nombre']) && $_POST['nombre'] != "") {
            $filtros['LOWER(Producto.descripcion) LIKE'] = '%' . strtolower($_POST['nombre'] . '%');
        }

        if (isset($_POST['categorias']) && $_POST['categorias'] != "") {
            $filtros['Producto.categoria_id'] = $_POST['categorias'];
        }

        if (isset($_POST['referencia']) && $_POST['referencia'] != "") {
            $filtros['LOWER(Producto.referencia) LIKE'] = '%' . strtolower($_POST['referencia']) . '%';
        }
       
        $productos = $this->Producto->obtenerProductosReporte($empresaId, $filtros); 
        $texto_tit = "Productos";
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $productos);
        $arr_titulos = array(
            'C&oacute;digo',
            'Referencia',
            'Nombre',
            'Categor&iacute;a',
            'Marca',
            'Existencia M&iacute;nima',
            'Existencia M&aacute;xima',
        );
        $this->set(compact('productos'));
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }
    /**
     * Se genera el reporte de categorias de la vista /categorias/index/
     */
    public function descargarReporteCategorias()
    {
        $this->loadModel('Categoria');
     
        $usuarioAct = $this->Auth->user('id');
        $empresaId = $this->Auth->user('empresa_id');
        $nombre = $_POST['nombre'];

        $this->loadModel('Categoria');
        if (isset($_POST['nombre']) && $_POST['nombre'] != "") {
            $filtros['LOWER(Categoria.descripcion) LIKE'] = '%' . strtolower($_POST['nombre']) . '%';
        }
       
        $categoriasReporte = $this->Categoria->obtenerCategoriasReporte($empresaId, $filtros);
        $texto_tit = "Categorias";
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $categoriasReporte);
        $arr_titulos = array(
            'Nombre',
            'Ver en cat&aacute;logo',
            'Es servicio',
            'Fecha',
        );
        $this->set(compact('categoriasReporte'));
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }
    /**
     * Se genera el reporte de proveedores de la vista /proveedores/index
     */
    public function descargarReporteProveedores()
    {
        $this->loadModel('Proveedore');
     
        $usuarioAct = $this->Auth->user('id');
        $empresaId = $this->Auth->user('empresa_id');
        $nombre = $_POST['nombre'];
        $nit = $_POST['nit'];
        $ciudad = $_POST['ciudad'];

        if(isset($_POST['nombre']) && $_POST['nombre'] != ""){
            $filtros['LOWER(Proveedore.nombre) LIKE'] = '%' . strtolower($_POST['nombre']) . '%';
        }
        
        if(isset($_POST['nit']) && $_POST['nit'] != ""){
            $filtros['LOWER(Proveedore.nit) LIKE'] = '%' . strtolower($_POST['nit']) . '%';
        }
        
        if(isset($_POST['ciudad']) && $_POST['ciudad'] != ""){
            $filtros['Proveedore.ciudade_id'] = $_POST['ciudad'];
        }  

        $proovedoresReporte = $this->Proveedore->obtenerProovedoresReporte($empresaId, $filtros);
        $texto_tit = "Proovedores";
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $proovedoresReporte );
        $arr_titulos = array(
            'Nit',
            'Nombre',
            'C&oacute;digo',
            'Direcci&oacute;n',
            'Tel&eacute;fono',
            'Ciudad',
            'Celular',
            'Estado',
        );
        $this->set(compact('proovedoresReporte'));
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }
    /**
     * Se genera el reporte depositos de la vista /depositos/index
     */
    public function descargarReporteDepositos()
    {
        $this->loadModel('Deposito');
        $usuarioAct = $this->Auth->user('id');
        $empresaId = $this->Auth->user('empresa_id');
        if(isset($_POST['nombre']) && $_POST['nombre'] != ""){
            $filtros['LOWER(Deposito.descripcion) LIKE'] = '%' . strtolower($_POST['nombre']) . '%';
        }
        
        if(isset($_POST['encargado']) && $_POST['encargado'] != ""){
            $filtros['Deposito.usuario_id'] = $_POST['encargado'];
        }

        if(isset($_POST['ciudad']) && $_POST['ciudad'] != ""){
            $filtros['Deposito.ciudade_id'] = $_POST['ciudad'];
        }            

        
        $depositosReporte = $this->Deposito->obtenerDepositosReporte($empresaId, $filtros);
        $texto_tit = "Depositos";
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $depositosReporte );
        $arr_titulos = array(
            'Nombre',
            'Ciudad',
            'Tel&eacute;fono',
            'Direcci&oacute;n',
            'Encargado',
            'C&oacute;digo',
        );
        $this->set(compact('depositosReporte'));
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }
    /**
     * Se genera el reporte de clientes de la vista /clientes/index
     */
    public function descargarReporteClientes()
    {
        $this->loadModel('Cliente');
        $usuarioAct = $this->Auth->user('id');
        $empresaId = $this->Auth->user('empresa_id');
       
        if (isset($_POST['nit']) && $_POST['nit'] != "") {
            $filtros['LOWER(Cliente.nit) LIKE'] = '%' . strtolower($_POST['nit']) . '%';
        }

        if (isset($_POST['nombre']) && $_POST['nombre'] != "") {
            $filtros['LOWER(Cliente.nombre) LIKE'] = '%' . strtolower($_POST['nombre']) . '%';
        }
       
        $clientesReporte = $this->Cliente->obtenerClientesReporte($empresaId, $filtros);
        $texto_tit = "Clientes";
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $clientesReporte );
        $arr_titulos = array(
            'Nit',
            'Nombre',
            'Direcci&oacute;n',
            'Tel&eacute;fono',
            'Ciudad',
            'Celular',
            'D&iacute;as de cr&eacute;dito',
            'L&iacute;mite de cr&eacute;dito',
            'Estado',
            'Clasificaci&oacute;n',
                );
        $this->set(compact('clientesReporte'));
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }
    /**
     * Se genera el reporte de prefacturas de la vista /prefacturas//index
     */
    public function descargarReportePrefacuras()
    {   
        $this->loadModel('Estadosprefactura');
        $this->loadModel('Prefacturasdetalle');
        $this->loadModel('Prefactura');
        $this->loadModel('Vehiculo');
        
        $cliente = $_POST['cliente'];
        $placa = $_POST['vehiculo'];
        
        $usuarioAct = $this->Auth->user('id');
        $empresaId = $this->Auth->user('empresa_id');
        
        $prefacturasReporte = $this->Prefactura->obtenerPrefacturasReporte($placa, $cliente, $empresaId);
   
        $texto_tit = "Prefacturas";
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $prefacturasReporte );
        $arr_titulos = array(
            'Codigo',
            'Cliente',
            'Veh&iacute;culo',
            'Fecha',
            'Estado',
            'Observaci&oacute;n',
            'Precio venta',
            'Producto c&oacute;digo',
            'Producto descripci&oacute;n',
                );
        $this->set(compact('prefacturasReporte'));
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }
    /**
     * Se genera el reporte de usuarios de la vista /usuarios/index
     */
    public function descargarReporteUsuarios()
    {   
        $empresaId = $this->Auth->user('empresa_id');
        $this->loadModel('Usuario');
        if (isset($_POST['nombre']) && $_POST['nombre'] != "") {
            $filtros['LOWER(Usuario.nombre) LIKE'] = '%' . strtolower($_POST['nombre']) . "%";
        }

        if (isset($_POST['identificacion']) && ($_POST['identificacion'] != "")) {
            $filtros['Usuario.identificacion LIKE'] = '%' . $_POST['identificacion'] . "%";
        }        
       
        $usuariosReporte = $this->Usuario->obtenerUsuariosReporte($empresaId, $filtros); 
        $texto_tit = "Usuarios";
        $this->set('texto_tit', $texto_tit);
        $this->set('rows',$usuariosReporte );
        $arr_titulos = array(
            'Nombre',
            'Identificaci&oacute;n',
            'Username',
            'Perfil',
            'Estado',
                );
        $this->set(compact('usuariosReporte'));
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }

    /**
     * Se genera el reporte de ordenes de trabajo para su descarga
     */
    public function descargarOrdenes() {

        $this->loadModel('Ordentrabajo');

        $usuario = $_POST['rpusuario'];
        $cliente = $_POST['rpcliente'];
        $planta = $_POST['rpplanta'];
        $estado = $_POST['rpestado'];
        $vehiculo = $_POST['rpvehiculo'];
        $empresaId = $this->Auth->user('empresa_id');
        
        if(!empty($usuario)){ $filter['Ordentrabajo.usuario_id'] = $usuario; }
        
        if(!empty($cliente)){ $filter['Ordentrabajo.cliente_id'] = $cliente; }
        
        if(!empty($planta)){ $filter['Ordentrabajo.plantaservicio_id'] = $planta; }

        if(!empty($estado)){ $filter['Ordentrabajo.ordenestado_id'] = $estado; }
        
        if(!empty($vehiculo)){ $filter['Ordentrabajo.vehiculo_id'] = $vehiculo; }  

        $arrOrdenesT = $this->Ordentrabajo->obtenerOrdenesTrabajo($filter, $empresaId);

        $texto_tit = "Ordenes";
        $this->set(compact('arrOrdenesT'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows',$arrOrdenesT);
        $arr_titulos = array(
            'Codigo',
            'Fecha Ingreso',
            'Tecnico',
            'Cliente',
            'Vehiculo',
            'Estado',
                );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');

    }

    /**
     * Generar reporte de ordenes por mecánico
     */
    public function descargarOrdenesMecanicos() {

        $this->loadModel('Factura');
        $this->loadModel('Marcavehiculo');

        $fechaIni = $_POST['rpcreatedIni'];
        $fechaFin = $_POST['rpcreatedFin'];
        $usuario = $_POST['rpusuario'];
        $empresa = $_POST['empresaId'];

        $filter['Factura.empresa_id'] = $this->Auth->user('empresa_id');;

        if (!empty($usuario)) { $filter['OT.usuario_id'] = $usuario; }

        if (!empty($fechaIni) && empty($fechaFin)) { $filter['Factura.created >'] = $fechaIni . " 00:00:01"; }
        else if (empty($fechaIni) && !empty($fechaFin)) { $filter['Factura.created <'] = $fechaFin . " 23:59:59"; }
        else { $filter['Factura.created BETWEEN ? AND ?'] = array($fechaIni . " 00:00:01", $fechaFin . " 23:59:59"); }

        $arrFactOrdenes = $this->Factura->obtenerFacturasOrdenesServicios($filter);
        $marcas = $this->Marcavehiculo->obtenerListaMarcavehiculos();

        foreach($arrFactOrdenes as $key => $val) {
            $arrFactOrdenes[$key]['VH']['marca'] = $marcas[$val['VH']['marcavehiculo_id']];
        }

        $texto_tit = "Ordenes por Tecnico";
        $this->set(compact('arrFactOrdenes'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows',$arrFactOrdenes);
        $arr_titulos = array(
            'Orden',
            'Factura',
            'Fecha Factura',
            'Tecnico',
            'Servicio',
            'Placa', 
            'Marca', 
            'Linea', 
            'Cantidad',
            'Costo',
            'Costo Total',
            'Fecha de Pago'
                );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');

    }

}
