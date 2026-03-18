<?php
App::uses('AppController', 'Controller');

class IaconsultasController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');



    public function obteneranalisisgerencial(){
        $this->autoRender = false;
        $apiKey = 'AIzaSyBnIiW3KrCJCKa1YR0ywdtl3k-BgUlqDqA'; 
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=" . $apiKey;

        // 1. Capturamos lo que envía el JS
        $input = json_decode(file_get_contents('php://input'), true);


        $promptCierreDiario = "Eres el Director Financiero (CFO) de "; //+ datos.nombreEmpresa;
        $promptCierreDiario .= "Tu función NO es resumir datos (el usuario ya tiene los totales), sino realizar un análisis crítico. ";
        $promptCierreDiario .= "Debes identificar riesgos (como gastos altos, cuentas sin especificar, cómo gestionar abonos, consejos de compras a credito), "; 
        $promptCierreDiario .= "evaluar la liquidez según los medios de pago y dar 2 recomendaciones estratégicas de negocio. ";
        $promptCierreDiario .= "Usa un tono ejecutivo, directo y analítico. ";


    
        // AQUÍ ARMAMOS EL CUERPO PARA GOOGLE (Oculto del navegador)
        $cuerpoParaGoogle = [
            "system_instruction" => [
                "parts" => [["text" => $promptCierreDiario]]
            ],
            "contents" => [
                "parts" => [["text" => $input['cuerpo']]]
            ],
            "generationConfig" => [
                "temperature" => 0.2,
                "maxOutputTokens" => 1000,
                "responseMimeType" => "text/plain"
            ]
        ];

        // 2. Configuramos la petición
        $opciones = array(
            'http' => array(
                'method'  => 'POST',
                    'header'  => "Content-Type: application/json\r\n" .
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n',
                'content' => json_encode($cuerpoParaGoogle),
                'ignore_errors' => true // Para capturar errores de Google si los hay
            )
        );

        $opciones = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\n" .
                            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n", 
                'content' => json_encode($cuerpoParaGoogle),
                'ignore_errors' => true 
            )
        );

        $contexto = stream_context_create($opciones);
        
        // 3. Ejecutamos la llamada
        $resultado = @file_get_contents($url, false, $contexto);

        // 4. Devolvemos la respuesta al JS
        header('Content-Type: application/json');
        echo $resultado;
    }

/**
 * index method
 *
 * @return void
 */
	public function analisiscierrediario() {
        $this->autoRender = false;
        $this->loadModel('Gasto');
        $this->loadModel('Cuenta');
        $this->loadModel('Abonofactura');
        $this->loadModel('Tipopago');
        $this->loadModel('Factura');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Cuentaspendiente');

        $cuenta = !empty($_POST['cuenta']) ? $_POST['cuenta'] : "";
        $fechaCierre = !empty($_POST['fechaCierre']) ? $_POST['fechaCierre'] : date('Y-m-d');
        $empresaId = $this->Auth->user('empresa_id');
        $infoUsuario = $this->Auth->user();
        $nombreUsuario = $infoUsuario['nombre'];
        $nombreEmpresa = $infoUsuario['Empresa']['nombre'];

        //se obtiene el listado de cuentas de la empresa
        $listCuenta = $this->Cuenta->obtenerCuentasEmpresa($empresaId);

        //se obtiene la lista de tipos de pagos
        $listTipoPago = $this->Tipopago->obtenerListaTiposPagos($empresaId);

        /*se obtienen las facturas generadas durante la fecha actual o la seleccionada*/
        $detFacts = $this->Factura->obtenerFacturasTipoPagos($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $cuenta);
        $ventasFactura = [];
        $ventasFacturaElim = [];
        $estadoCuentas = [];
        if (!empty($detFacts)) {
            foreach ($detFacts as $df) {
                if (!isset($estadoCuentas[$listCuenta[$df['FCV']['cuenta_id']]])) {
                    $estadoCuentas[$listCuenta[$df['FCV']['cuenta_id']]]['ingreso_ventas'] = 0;
                }

                if($df['Factura']['eliminar'] == '0'){
                    //se obtiene el detalle de la venta de cada factura por cada tipo de pago
                    $ventasFactura[] = [
                        'fact_codigo' => $df['Factura']['consecutivodv'],
                        'consecutivodian' => !empty($df['Factura']['consecutivodian']) ? $df['Factura']['consecutivodian'] : "",
                        'cliente_nit' => !empty($df['Cliente']['nit']) ? $df['Cliente']['nit'] : "",
                        'cliente_nombre' => !empty($df['Cliente']['nombre']) ? $df['Cliente']['nombre'] : "",
                        'usuario_nombre' => !empty($df['Usuario']['nombre']) ? $df['Usuario']['nombre'] : "",
                        'usuario_identificacion' => !empty($df['Usuario']['identificacion']) ? $df['Usuario']['identificacion'] : "",
                        'fcv_cuenta' => $listCuenta[$df['FCV']['cuenta_id']],
                        'fcv_tipopago' => $listTipoPago[$df['FCV']['tipopago_id']],
                        'fcv_valor' => $df['FCV']['valor'],
                        'created' => $df['Factura']['created']
                    ];

                    //se obtiene el ingreso por venta en cada cuenta
                    $estadoCuentas[$listCuenta[$df['FCV']['cuenta_id']]]['ingreso_ventas'] += $df['FCV']['valor'];
                } else {
                    $ventasFacturaElim[] = [
                        'fact_codigo' => $df['Factura']['consecutivodv'],
                        'consecutivodian' => !empty($df['Factura']['consecutivodian']) ? $df['Factura']['consecutivodian'] : "",
                        'cliente_nit' => !empty($df['Cliente']['nit']) ? $df['Cliente']['nit'] : "",
                        'cliente_nombre' => !empty($df['Cliente']['nombre']) ? $df['Cliente']['nombre'] : "",
                        'usuario_nombre' => !empty($df['Usuario']['nombre']) ? $df['Usuario']['nombre'] : "",
                        'usuario_identificacion' => !empty($df['Usuario']['identificacion']) ? $df['Usuario']['identificacion'] : "",
                        'fcv_cuenta' => $listCuenta[$df['FCV']['cuenta_id']],
                        'fcv_tipopago' => $listTipoPago[$df['FCV']['tipopago_id']],
                        'fcv_valor' => $df['FCV']['valor'],
                        'created' => $df['Factura']['created']
                    ];
                }
            }
        }

        //se obtiene el registro de gastos de una cuenta, solo el gasto, no el gasto por traslado
        $infoGastos = $this->Gasto->obtenerGastosTrasladosEmpresa($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $cuenta);
        if (!empty($infoGastos)) {
            foreach ($infoGastos as $gts) {
                if (!isset($estadoCuentas[$listCuenta[$gts['Gasto']['cuenta_id']]]['gastos'])) {
                    $estadoCuentas[$listCuenta[$gts['Gasto']['cuenta_id']]]['gastos'] = 0;
                }
                $estadoCuentas[$listCuenta[$gts['Gasto']['cuenta_id']]]['gastos'] += $gts['Gasto']['valor'];
            }
        }

        //se obtiene el registro de traslados
        $infoTraslados = $this->Gasto->obtenerIngresosTrasladosEmpresa($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $cuenta);
        //se obtiene la sumatoria de ingresos por traslado por cuenta
        foreach ($infoTraslados as $it) {

            //ingresos por traslados
            if (!isset($estadoCuentas[$listCuenta[$it['Gasto']['cuentadestino']]]['ing_traslados'])) {
                $estadoCuentas[$listCuenta[$it['Gasto']['cuentadestino']]]['ing_traslados'] = 0;
            }

            //gastos por traslados
            if (!isset($estadoCuentas[$listCuenta[$it['Gasto']['cuenta_id']]]['gasto_traslados'])) {
                $estadoCuentas[$listCuenta[$it['Gasto']['cuenta_id']]]['gasto_traslados'] = 0;
            }

            $estadoCuentas[$listCuenta[$it['Gasto']['cuentadestino']]]['ing_traslados'] += $it['Gasto']['valor'];
            $estadoCuentas[$listCuenta[$it['Gasto']['cuenta_id']]]['gasto_traslados'] += $it['Gasto']['valor'];
        }

        //se obtienen los abonos de la fecha indicada para las prefacturas
        $arrAbonos = [];
        $abonosPrefactura = $this->Abonofactura->obtenerAbonosCierreDiario($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, "1");
        if (!empty($abonosPrefactura)) {
            foreach ($abonosPrefactura as $abnpf) {

                if (!isset($estadoCuentas[$listCuenta[$abnpf['Abonofactura']['cuenta_id']]]['abono_prefact'])) {
                    $estadoCuentas[$listCuenta[$abnpf['Abonofactura']['cuenta_id']]]['abono_prefact'] = 0;
                }

                $arrAbonos[] = [
                    'fecha' => $abnpf['Abonofactura']['created'],
                    'valor' => $abnpf['Abonofactura']['valor'],
                    'cliente' => $abnpf['CL']['nombre'],
                    'cuenta' => $abnpf['C']['descripcion'],
                    'prefactura' => $abnpf['Abonofactura']['prefactura_id'],
                    'factura' => $abnpf['Abonofactura']['factura_id'],
                    'usuario' => $abnpf['U']['nombre']
                ];

                $estadoCuentas[$listCuenta[$abnpf['Abonofactura']['cuenta_id']]]['abono_prefact'] += $abnpf['Abonofactura']['valor'];

            }
        }

        $abonosFactura = $this->Abonofactura->obtenerAbonosCierreDiario($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, "0");
        if (!empty($abonosFactura)) {
            foreach ($abonosFactura as $abnf) {

                if (!isset($estadoCuentas[$listCuenta[$abnf['Abonofactura']['cuenta_id']]]['abono_fact'])) {
                    $estadoCuentas[$listCuenta[$abnf['Abonofactura']['cuenta_id']]]['abono_fact'] = 0;
                }

                $arrAbonos[] = [
                    'fecha' => $abnf['Abonofactura']['created'],
                    'valor' => $abnf['Abonofactura']['valor'],
                    'cliente' => $abnf['CL']['nombre'],
                    'cuenta' => $abnf['C']['descripcion'],
                    'prefactura' => $abnf['Abonofactura']['prefactura_id'],
                    'factura' => $abnf['Abonofactura']['factura_id'],
                    'usuario' => $abnf['U']['nombre']
                ];

                $estadoCuentas[$listCuenta[$abnf['Abonofactura']['cuenta_id']]]['abono_fact'] += $abnf['Abonofactura']['valor'];

            }
        }

        //se obtiene el estado actual de las cuentas
        $ctasEstAct = $this->Cuenta->obtenerInfoCuentas($empresaId);
        if (!empty($ctasEstAct)) {
            foreach ($ctasEstAct as $eaCtas) {
                if (!isset($estadoCuentas[$listCuenta[$eaCtas['Cuenta']['id']]]['estado_actual'])) {
                    $estadoCuentas[$listCuenta[$eaCtas['Cuenta']['id']]]['estado_actual'] = 0;
                }

                $estadoCuentas[$listCuenta[$eaCtas['Cuenta']['id']]]['estado_actual'] += $eaCtas['Cuenta']['saldo'];
            }
        }

        //se obtienen las cuentas pendientes de los clientes registradas en el dia consultado
        $ctasClientes = $this->Cuentascliente->obtenerVentasCredito($empresaId, $fechaCierre);
        $ctasPendientes = $this->Cuentaspendiente->obtenerComprasCredito($empresaId, $fechaCierre);

        echo json_encode(array(
            'facturas' => $ventasFactura, 
            'facturasEliminadas' => $ventasFacturaElim, 
            'nombreVendedor' => $nombreUsuario, 
            'nombreEmpresa' => $nombreEmpresa,
            'estadoCuentas' => $estadoCuentas,
            'arrAbonos' => $arrAbonos
        ));
	}

}
