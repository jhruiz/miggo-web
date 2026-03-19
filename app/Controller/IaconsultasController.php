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
        $this->loadModel('Configuraciondato');

        $this->autoRender = false;
        $apiKey = $this->Configuraciondato->obtenerValorDatoConfig('IAApiKey'); 
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=" . $apiKey;

        // 1. Capturamos lo que envía el JS
        $input = json_decode(file_get_contents('php://input'), true);

        // 2. Obtiene el prompt por módulo y se envían datos adicionales en el input
        $promptSistema = $this->Iaconsulta->obtenerPrompt($input);
    
        // se crea el cuerpo para la petición
        $cuerpoParaIA = [
            "system_instruction" => [
                "parts" => [
                    ["text" => $promptSistema] 
                ]
            ],
            "contents" => [
                [
                    "parts" => [
                        ["text" => $input['reporte_data']]
                    ]
                ]
            ],
            "generationConfig" => [
                "temperature" => 0.2,
                "maxOutputTokens" => 1000,
                "responseMimeType" => "text/plain"
            ]
        ];

        // 3. Configuramos la petición
        $opciones = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\n",
                'content' => json_encode($cuerpoParaIA),
                'ignore_errors' => true 
            )
        );

        $opciones = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\n" .
                            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n", 
                'content' => json_encode($cuerpoParaIA),
                'ignore_errors' => true 
            )
        );

        $contexto = stream_context_create($opciones);
        
        // 4. Ejecutamos la llamada
        $resultado = @file_get_contents($url, false, $contexto);

        // Validamos si la variable mágica existe
        $statusHttp = 0;
        if (isset($http_response_header) && is_array($http_response_header)) {
            // Buscamos el código 200, 403, 400, etc. en la primera línea
            preg_match('{HTTP\/\S*\s(\d{3})}', $http_response_header[0], $matches);
            $statusHttp = intval($matches[1]);
        } else {
            // Si no existe, es que ni siquiera hubo respuesta del servidor
            $statusHttp = 500; 
        }

        // --- REGISTRO DE CONSUMO ---
        // Llamamos al modelo para guardar la auditoría
        $empresaId = $this->Auth->user('empresa_id');
        $modulo = $input['modulo'];
        $this->Iaconsulta->registrarConsumo($empresaId, $modulo, $resultado, $statusHttp);

        // 5. Devolvemos la respuesta al JS
        header('Content-Type: application/json');
        echo $resultado;
    }

    /**
     * Función que obtiene la información del cierre diario para el análisis de IA
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
