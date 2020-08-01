<?php
App::uses('AppController', 'Controller');
App::uses('FacturasController', 'Controller');
App::uses('CuentasclientesController', 'Controller');

class ReportesController extends AppController {

    public function estadisticastortas(){
        $this->loadModel('Deposito');
        
        $this->autoRender = false;

        $posData = $this->request->data;
        
        $fechaIncial = '';
        $fechaFinal = '';
        if(!empty($posData)){
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
        foreach($bodegas as $key => $val){
            $tortas[] = $this->obtenerUtilidadBodegas($key, $val, $fechaIncial, $fechaFinal);
        }

        echo json_encode(array('resp' => $tortas));

    }

    public function obtenerUtilidadBodegas($bodegaId, $bodega, $fechaIncial, $fechaFinal) {
        
        $this->loadModel('Utilidade');

        $arrTitulos = [];
        $seriesData = [];
        
        $utilidad = $this->Utilidade->obtnerVentasBodega($bodegaId, $fechaIncial, $fechaFinal);
        foreach($utilidad as $ut){
            $arrTitulos[] = $ut['P']['descripcion'];

            $seriesData[] = [
                'value' => $ut['0']['contador'],
                'name' => $ut['P']['descripcion']
            ];
        }

        $utilidades = [
            'titulo' => $bodega,
            'legend_data' => $arrTitulos,
            'series_data' => $seriesData
        ];

        return $utilidades;
        
    }

    public function obtenerVentasServicios($fechaInicial, $fechaFinal) {
        $this->loadModel('Utilidade');
        $empresaId = $this->Auth->user('empresa_id');

        $productosVendidos = $this->Utilidade->obtnerVentasProdServ($empresaId, 's', $fechaInicial, $fechaFinal);

        $arrTitulos = [];
        $seriesData = [];

        foreach($productosVendidos as $pv) {
            $arrTitulos[] = $pv['P']['descripcion'];

            $seriesData[] = [
                'value' => $pv['0']['contador'],
                'name' => $pv['P']['descripcion']
            ];
        }

        $facturasCli = [
            'titulo' => 'Servicios mas vendidos',
            'legend_data' => $arrTitulos,
            'series_data' => $seriesData
        ];

        return $facturasCli;        
    }

    public function obtenerVentasProductos($fechaIncial, $fechaFinal) {
        $this->loadModel('Utilidade');
        $empresaId = $this->Auth->user('empresa_id');

        $productosVendidos = $this->Utilidade->obtnerVentasProdServ($empresaId, 'p', $fechaIncial, $fechaFinal);

        $arrTitulos = [];
        $seriesData = [];

        foreach($productosVendidos as $pv) {
            $arrTitulos[] = $pv['P']['descripcion'] . ' - ' . $pv['DP']['descripcion'];

            $seriesData[] = [
                'value' => $pv['0']['contador'],
                'name' => $pv['P']['descripcion'] . ' - ' . $pv['DP']['descripcion']
            ];
        }

        $facturasCli = [
            'titulo' => 'Productos mas vendidos',
            'legend_data' => $arrTitulos,
            'series_data' => $seriesData
        ];

        return $facturasCli;        
        
    }

    public function obtenerClientesVisitas($fechaIncial, $fechaFinal) {
        $this->loadModel('Factura');
        $empresaId = $this->Auth->user('empresa_id');

        $facturasClientes = $this->Factura->obtenerFactuasClientes($empresaId, $fechaIncial, $fechaFinal);

        $arrTitulos = [];
        $seriesData = [];

        foreach($facturasClientes as $fc) {
            $arrTitulos[] = $fc['C']['nombre'];

            $seriesData[] = [
                'value' => $fc['0']['contador'],
                'name' => $fc['C']['nombre']
            ];
        }

        $facturasCli = [
            'titulo' => 'Cliente fiel',
            'legend_data' => $arrTitulos,
            'series_data' => $seriesData
        ];

        return $facturasCli;

    }

    public function obtenerTecnicosOrdenes($fechaIncial, $fechaFinal){
        $this->loadModel('Ordentrabajo');
        $empresaId = $this->Auth->user('empresa_id');

        $ordenTecnicos = $this->Ordentrabajo->obtenerOrdenesTecnicosTortas($empresaId, $fechaIncial, $fechaFinal);
        
        $arrTitulos = [];
        $seriesData = [];

        foreach($ordenTecnicos as $ot){
            $arrTitulos[] = $ot['U']['nombre'];

            $seriesData[] = [
                'value' => $ot['0']['contador'],
                'name' => $ot['U']['nombre']
            ];

        }

        $ordenesTec = [
            'titulo' => 'Ordenes - Técnicos',
            'legend_data' => $arrTitulos,
            'series_data' => $seriesData
        ];

        return $ordenesTec;
    }

    public function obtnerAlertasOrdenes($fechaIncial, $fechaFinal){
        $this->loadModel('Alertaordene');
        $empresaId = $this->Auth->user('empresa_id');

        $alertasOrdenes = $this->Alertaordene->obtieneAlertaOrdenesTortas($empresaId, $fechaIncial, $fechaFinal);

        $arrTitulos = [];
        $seriesData = [];

        foreach($alertasOrdenes as $ao){
            $arrTitulos[] = $ao['A']['descripcion'];

            $seriesData[] = [
                'value' => $ao['0']['contador'],
                'name' => $ao['A']['descripcion']
            ];

        }

        $alertas = [
            'titulo' => 'Alertas - Ordenes',
            'legend_data' => $arrTitulos,
            'series_data' => $seriesData
        ];

        return $alertas;

    }

    /**
     * Funcion que descarga el listado de ciudades -> prueba
     */
    public function descargarListaCiudades(){
        $this->loadModel('Ciudade');
        $ciudades = $this->Ciudade->obtenerListaCiudades();   

        $texto_tit = "LISTA DE CIUDADES";
        $this->set(compact('ciudades'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $ciudades);
        $arr_titulos = array(
            0 => __('ID'),
            1 => __('NOMBRE'),
            2 => __('PRUEBA')
        );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');
    }
    
    /**
     * Funcion que descarga el inventario de productos
     */
    public function descargarInventario(){
        $this->loadModel('Cargueinventario');
        
        $empresaId = $this->Auth->user('empresa_id');

        $data = array();
        if(!empty($_POST['rpproducto'])){
            $data['Cargueinventario.producto_id'] = $_POST['rpproducto'];
        }

        if(!empty($_POST['rpdeposito'])){
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
            7 => __('Fecha de cargue')        
            );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');        
        
    }
    
    /**
     * Descarga el listado de cuentas pendientes por pagar
     */
    public function descargarCuentasPendientes(){
        $this->loadModel('Cuentaspendiente');

        if(!empty($_POST['rpproducto'])){
            $paginate['Cuentaspendiente.producto_id'] = $_POST['rpproducto'];
        }
        
        if(!empty($_POST['rpdeposito'])){
            $paginate['Cuentaspendiente.deposito_id'] = $_POST['rpdeposito'];
        }

        if(!empty($_POST['rpproveedor'])){
            $paginate['Cuentaspendiente.proveedore_id'] = $_POST['rpproveedor'];
        }

        if(!empty($_POST['rpfactura'])){
            $paginate['LOWER(Cuentaspendiente.numerofactura) LIKE'] = '%' . strtolower($_POST['rpfactura']) . '%';
        }            

        $paginate['Cuentaspendiente.empresa_id'] = $this->Auth->user('empresa_id');
        
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
            'Dias credito'      
            );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');                
    }
    
    /**
     * Descarga el cierre diario de cuentas
     */
    public function descargarCierreDiario(){        
            $this->loadModel('Ventarapida');
            $this->loadModel('Cargueinventario');
            $this->loadModel('Gasto');
            $this->loadModel('Cuenta');                        
            $this->loadModel('Abonofactura');                        
            $this->loadModel('Prefactura');                        
            $this->loadModel('Tipopago');                        
            $this->loadModel('Factura');                        
            
            $cuenta = "";
            if(!empty($_POST['rpcuenta'])){
                $cuenta = $_POST['rpcuenta'];
            }
            
            if(!empty($_POST['rpfechacierre'])){
                $fechaCierre = $_POST['rpfechacierre'];
            }else{
                $fechaCierre = date('Y-m-d');
            }
            
            $empresaId = $this->Auth->user('empresa_id');

            /*se obtienen las facturas generadas durante la fecha actual o la seleccionada*/
            $detFacts = $this->Factura->obtenerFacturasTipoPagos($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $cuenta);
            $ventasFactura = [];
            $estadoCuentas = [];
            if(!empty($detFacts)){
                foreach($detFacts as $df){
                    if(!isset($estadoCuentas[$df['FCV']['cuenta_id']])){
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
                        'fcv_valor' => $df['FCV']['valor']
                    ];

                    //se obtiene el ingreso por venta en cada cuenta
                    $estadoCuentas[$df['FCV']['cuenta_id']]['ing_ventas'] += $df['FCV']['valor'];
                }                
            }                
                              
            //se obtiene el registro de gastos de una cuenta, solo el gasto, no el gasto por traslado
            $infoGastos = $this->Gasto->obtenerGastosTrasladosEmpresa($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $cuenta);            
            if(!empty($infoGastos)){
                foreach ($infoGastos as $gts){
                    if(!isset($estadoCuentas[$gts['Gasto']['cuenta_id']]['gastos'])){
                        $estadoCuentas[$gts['Gasto']['cuenta_id']]['gastos'] = 0;
                    }
                    $estadoCuentas[$gts['Gasto']['cuenta_id']]['gastos'] += $gts['Gasto']['valor'];
                }
            }

            //se obtiene el registro de traslados
            $infoTraslados = $this->Gasto->obtenerIngresosTrasladosEmpresa($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $cuenta);
            //se obtiene la sumatoria de ingresos por traslado por cuenta
            foreach($infoTraslados as $it){
                
                //ingresos por traslados
                if(!isset($estadoCuentas[$it['Gasto']['cuentadestino']]['ing_traslados'])){
                    $estadoCuentas[$it['Gasto']['cuentadestino']]['ing_traslados'] = 0;
                }                
                
                //gastos por traslados
                if(!isset($estadoCuentas[$it['Gasto']['cuenta_id']]['gasto_traslados'])){
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
            if(!empty($abonosPrefactura)){
                foreach ($abonosPrefactura as $abnpf){                    
                    
                    if(!isset($estadoCuentas[$abnpf['Abonofactura']['cuenta_id']]['abono_prefact'])){
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
            if(!empty($abonosFactura)){
                foreach ($abonosFactura as $abnf){

                    if(!isset($estadoCuentas[$abnf['Abonofactura']['cuenta_id']]['abono_fact'])){
                        $estadoCuentas[$abnf['Abonofactura']['cuenta_id']]['abono_fact'] = 0;
                    }
                    
                    $arrAbonos[] = [
                        'fecha' => $abnf['Abonofactura']['created'],
                        'valor' => $abnf['Abonofactura']['valor'],
                        'cliente' => $abnf['CL']['nombre'],
                        'cuenta' => $abnf['C']['descripcion']
                    ];
                    
                    $estadoCuentas[$abnf['Abonofactura']['cuenta_id']]['abono_fact'] += $abnf['Abonofactura']['valor'];

                }                
            }      
            
            //se obtiene el estado actual de las cuentas
            $ctasEstAct = $this->Cuenta->obtenerInfoCuentas($empresaId);
            if(!empty($ctasEstAct)){
                foreach ($ctasEstAct as $eaCtas){
                    if(!isset($estadoCuentas[$eaCtas['Cuenta']['id']]['estado_actual'])){
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
                
        $texto_tit = "Facturas";
        $this->set(compact('facturas'));
        $this->set('texto_tit', $texto_tit);
        $this->set('rows', $facturas);
        $arr_titulos = array(
            'Codigo',
            'Consecutivo',
            'Tipo',
            'Cliente',
            'Identificacion',
            'Fecha factura',  
            'Deposito',          
            'Producto',     
            'Codigo producto',     
            'Cantidad',     
            'Costo venta',     
            'Costo total'     
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
            'Deposito',
            'Proveedor',
            'Costo del Producto',
            'Costo Total',
            'Cantidad',
            'Precio Venta',
            'Total Venta',
            'Utilidad Bruta',  
            'Utilidad Porcentual',     
            'Tipo de Utilidad',          
            'Fecha',
            'Factura'
            );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');             
        
    }
    
    public function descargarCuentasClientes(){
        $this->loadModel('Ventarapida');
        $this->loadModel('Cuentascliente');
        $cuentasclientesController = new CuentasclientesController(); 
        $empresaId = $this->Auth->user('empresa_id');
        $cuentasclientes = $this->Cuentascliente->obtenerCuentasClientes($empresaId);

        $fechaActual = date('Y-m-d');
        for($i = 0; $i < count($cuentasclientes); $i++){
            if($cuentasclientes[$i]['Cuentascliente']['cliente_id'] != ""){
                $diasCredito = !empty($cuentasclientes[$i]['CL']['diascredito']) ? $cuentasclientes[$i]['CL']['diascredito'] : 30;
                $cuentasclientes[$i]['Cuentascliente']['fechalimitepago'] = $cuentasclientesController->sumarDiasFecha($cuentasclientes[$i]['Cuentascliente']['created'],$diasCredito);
            }else{
                $infoVentaRapida = $this->Ventarapida->obtenerInfoVentaFactId($cuentasclientes[$i]['Factura']['id']);

                if(count($infoVentaRapida) > 0){
                    $cuentasclientes[$i]['Cliente']['nombre'] = $infoVentaRapida['Ventarapida']['cliente'];
                }else{
                    $cuentasclientes[$i]['Cliente']['nombre'] = "";
                }
                $cuentasclientes[$i]['Cuentascliente']['fechalimitepago'] = "";
            }

            $diff = $cuentasclientesController->diffFechas($fechaActual, $cuentasclientes[$i]['Cuentascliente']['fechalimitepago']);   
            
            if((explode("+", $diff)) > 1){
                $diff = str_replace("+", "", $diff) . "+";
            }           
            
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
            'Total Obligacion',
            'Fecha',
            'Dias Credito',
            'Fecha Limite',  
            'Dias Vencido',     
            'Usuario',
            );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');        
    }
    
    
    public function descargarListaCierreDiario(){
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
            'Total'
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
    public function obtenerSaldosIniciales($listCuenta, $fechaCierre, $empresaId){
        $this->loadModel('Gasto');
        $this->loadModel('Cuenta');
        $estCuenta = []; 

        foreach ($listCuenta as $key => $val){

            $estCuenta[$key] = [
                'nombre' => $val, 
                'saldo' => !empty($estCuenta[$key]['saldo']) ? $estCuenta[$key]['saldo'] : 0, 
                'gasto' => !empty($estCuenta[$key]['gasto']) ? $estCuenta[$key]['gasto'] : 0, 
                'gasto_traslado' => !empty($estCuenta[$key]['gasto_traslado']) ? $estCuenta[$key]['gasto_traslado'] : 0,
                'ingreso_traslado' => !empty($estCuenta[$key]['ingreso_traslado']) ? $estCuenta[$key]['ingreso_traslado'] : 0,
                'ventas' => !empty($estCuenta[$key]['ventas']) ? $estCuenta[$key]['ventas'] : 0,
                'abonos' => !empty($estCuenta[$key]['abonos']) ? $estCuenta[$key]['abonos'] : 0
                ];                

            //se obtiene el saldo de la cuenta
            $cuentas = $this->Cuenta->obtenerDatosCuentaId($key);    
            if(!empty($cuentas)){                    
                $estCuenta[$key]['saldo'] = $cuentas['Cuenta']['saldo']; 
            }

            //se obtienen los gastos de la cuenta   
            $gastos = $this->Gasto->obtenerGastosCuenta($fechaCierre, $empresaId, $key);
            if(!empty($gastos)){               
                foreach($gastos as $gt){
                    $estCuenta[$key]['saldo'] += $gt['Gasto']['valor'];
                    $estCuenta[$key]['gasto'] += $gt['Gasto']['valor'];
                }                    
            }

            //se obtienen los ingresos por traslados en la cuenta
            $traslados = $this->Gasto->obtenerIngresosTrasladosCuenta($fechaCierre, $empresaId, $key);

            if(!empty($traslados)){
                foreach ($traslados as $tr){                                                                       
                    $estCuenta[$key]['saldo'] -= $tr['Gasto']['valor'];
                    $estCuenta[$key]['ingreso_traslado'] += $tr['Gasto']['valor'];
                }
            }

            //se obtienen las ventas registradas a una cuenta
            $facturas = $this->Factura->obtenerFacturasCierreDiario($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $key);
            if(!empty($facturas)){
                foreach ($facturas as $ft){
                    $estCuenta[$key]['saldo'] -= $ft['Factura']['pagocontado'];
                    $estCuenta[$key]['ventas'] += $ft['Factura']['pagocontado'];                        
                }
            } 

            //se obtienen los abonos realizados la cuenta que aun no han sido facturados
            $arrAbonos = $this->Abonofactura->obtenerAbonosACuenta($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $key);
            if(!empty($arrAbonos)){
                foreach ($arrAbonos as $abn){
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
    public function descargarGastos(){
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
            'tipo' => 'P'
        ];

        //se obtinen las empresas relacionadas a la empresa
        $infoEmpresasRel = $this->Relacionempresa->obtenerInformacionEmpresas($empresaId);
        if(!empty($infoEmpresasRel)){
            foreach ($infoEmpresasRel as $ier){
                $arrEmpresa[] = [
                    'id' => $ier['Relacionempresa']['id'],
                    'nombre' => $ier['Relacionempresa']['nombre'],
                    'tipo' => 'S'
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
        if(!empty($infoGastos)){
            foreach ($infoGastos as $gts){
                
                $empRelGasto = "";
                foreach ($arrEmpresa as $empRel){
                    if($empRel['id'] == $gts['Gasto']['empresaasg_id'] && $empRel['tipo'] == $gts['Gasto']['tipoempresa']){
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
                    'empRel' => $empRelGasto
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
            9 => __('Valor')        
            );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');        
    }
    
    public function descargarRotacion() {
        $this->loadModel('Utilidade');
        
        if(!empty($_POST['rpfechIni']) && !empty($_POST['rpfechFin'])){
            $fechaInicio = $_POST['rpfechIni'];
            $fechaFin = $_POST['rpfechFin'];
        }else{
            $fechaInicio = date('Y-m-d');
            $fechaFin = date('Y-m-d');
        }

        $empresaId = $this->Auth->user('empresa_id'); 
        
        $days = $this->diffDates($fechaInicio, $fechaFin);

        /*se recorre el registro de las utilidades*/
        $rotacion = $this->Utilidade->obtenerRotacion($fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59', $empresaId);

        $arrRotation = [];
        foreach($rotacion as $val){
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
            'Promedio costo producto'
            );
        $this->set('titulos', $arr_titulos);
        $this->render('export_xls', 'export_xls');        
    }
    
	public function diffDates($dateInit, $dateEnd){
        $date1 = new DateTime($dateInit);
        $date2 = new DateTime($dateEnd);
        $diff = $date1->diff($date2);
        
        return $diff->days;	
	}    
    
    public function descargarCompras(){
        $this->loadModel('Proveedore');
        $this->loadModel('Usuario');
        $this->loadModel('Compra');
        $this->loadModel('Reteicaretefuente');
        $this->loadModel('Categoriacompra');
        
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
        $listCat = $this->Categoriacompra->obtenerlistaCategoriasCompras($empresaId);        
        
        //se obtiene la lista de reteica retefuente
        $listRicaRfte = $this->Reteicaretefuente->obtenerListaReteicaRetefuente($empresaId);
        
        $arrInfoCompras = [];
        if(!empty($compras)){
            foreach($compras as $cmp){   
                
                $arrInfoCompras[] = [
                    'proveedor' => $listProv[$cmp['Compra']['proveedore_id']],
                    'fecha' => $cmp['Compra']['fecha'],
                    'num_factura' => $cmp['Compra']['numerofactura'],
                    'usuario' => $listUsr[$cmp['Compra']['usuario_id']],
                    'categoria' => $listCat[$cmp['CCC']['categoriacompra_id']],
                    'valor' => $cmp['CCC']['valor'],
                    'iva_prc' => ($cmp['Compra']['prciva'] - 1) * 100,
                    'iva_vlr' => ($cmp['CCC']['valor'] * $cmp['Compra']['prciva']) - $cmp['CCC']['valor'],
                    'retefuente_prc' => !empty($cmp['Compra']['prcretefuente']) ? ($cmp['Compra']['prcretefuente'] - 1) * 100 : 0,
                    'retefuente_vlr' => $cmp['Compra']['prcretefuente'] != 0 ? ($cmp['CCC']['valor'] * $cmp['Compra']['prcretefuente']) - $cmp['CCC']['valor'] : 0,                    
                    'reteica_prc' => !empty($cmp['Compra']['prcreteica']) ? ($cmp['Compra']['prcreteica'] - 1) * 100 : 0,
                    'reteica_vlr' => $cmp['Compra']['prcreteica'] != 0 ? ($cmp['CCC']['valor'] * $cmp['Compra']['prcreteica']) - $cmp['CCC']['valor'] : 0
                ];         
                
            }                    
        }
        
        if($typeTax == '1'){
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
        }else if($typeTax == '2'){
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
        }else if($typeTax == '3'){
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
        }else if($typeTax == '4'){
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
}
