<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');

/**
 * ReciboscajasController
 *
 * Gestiona los recibos de caja y anticipos de clientes.
 *
 * Flujo principal:
 *   1. Se crea el recibo (add) → genera consecutivo desde resolucionfacturas
 *   2. El recibo queda con saldo disponible (estado: activo)
 *   3. Desde la prefactura/factura se aplica el recibo como abono (ajaxAplicarRecibo)
 *      → descuenta el saldo del recibo y crea un registro en abonofacturas
 *   4. Cuando el saldo llega a 0 el recibo pasa a estado 'aplicado' automáticamente
 *
 * Tipo de documento para resolución de recibos de caja: 3
 * (debe existir en tipodocumentoventas con id=3 descripción='Recibo de Caja')
 */
class ReciboscajasController extends AppController {

    public $components = array('Paginator');

    /**
     * Tipo de documento en resolucionfacturas para recibos de caja.
     * Ajusta este valor según la configuración de tu tabla tipodocumentoventas.
     */
    const TIPO_DOC_RECIBO = 3;

    // ─────────────────────────────────────────────────────────────────────────
    // INDEX — Listado con filtros
    // ─────────────────────────────────────────────────────────────────────────

    public function index() {
        $usuariosController = new UsuariosController();
        $usuariosController->registraractividad($this->Auth->user('id'));

        $this->loadModel('Tipopago');
        $this->loadModel('Cuenta');

        $empresaId = $this->Auth->user('empresa_id');

        $conditions = array('Reciboscaja.empresa_id' => $empresaId);

        // ── Filtros opcionales ──
        $filtConsecutivo = '';
        $filtCliente     = '';
        $filtEstado      = '';
        $filtCuenta      = '';
        $filtTipoPago    = '';
        $filtFechaIni    = '';
        $filtFechaFin    = '';

        if (!empty($this->passedArgs['Reciboscaja']['consecutivo'])) {
            $conditions['Reciboscaja.consecutivo LIKE'] = '%' . $this->passedArgs['Reciboscaja']['consecutivo'] . '%';
            $filtConsecutivo = $this->passedArgs['Reciboscaja']['consecutivo'];
        }

        if (!empty($this->passedArgs['Reciboscaja']['cliente'])) {
            $conditions['CL.nombre LIKE'] = '%' . $this->passedArgs['Reciboscaja']['cliente'] . '%';
            $filtCliente = $this->passedArgs['Reciboscaja']['cliente'];
        }

        if (!empty($this->passedArgs['Reciboscaja']['estado'])) {
            $conditions['Reciboscaja.estado'] = $this->passedArgs['Reciboscaja']['estado'];
            $filtEstado = $this->passedArgs['Reciboscaja']['estado'];
        }

        if (!empty($this->passedArgs['Reciboscaja']['cuenta'])) {
            $conditions['Reciboscaja.cuenta_id'] = $this->passedArgs['Reciboscaja']['cuenta'];
            $filtCuenta = $this->passedArgs['Reciboscaja']['cuenta'];
        }

        if (!empty($this->passedArgs['Reciboscaja']['tipopago'])) {
            $conditions['Reciboscaja.tipopago_id'] = $this->passedArgs['Reciboscaja']['tipopago'];
            $filtTipoPago = $this->passedArgs['Reciboscaja']['tipopago'];
        }

        if (!empty($this->passedArgs['Reciboscaja']['fechaIni']) && !empty($this->passedArgs['Reciboscaja']['fechaFin'])) {
            $conditions['Reciboscaja.created BETWEEN ? AND ?'] = array(
                $this->passedArgs['Reciboscaja']['fechaIni'] . ' 00:00:00',
                $this->passedArgs['Reciboscaja']['fechaFin'] . ' 23:59:59',
            );
            $filtFechaIni = $this->passedArgs['Reciboscaja']['fechaIni'];
            $filtFechaFin = $this->passedArgs['Reciboscaja']['fechaFin'];
        }

        // Sin filtro de fecha → mostrar solo el día de hoy
        if (empty($this->passedArgs['Reciboscaja']['fechaIni']) && empty($this->passedArgs['Reciboscaja']['fechaFin'])
            && empty($this->passedArgs['Reciboscaja']['consecutivo']) && empty($this->passedArgs['Reciboscaja']['cliente'])) {
            $conditions['Reciboscaja.created BETWEEN ? AND ?'] = array(
                date('Y-m-d') . ' 00:00:00',
                date('Y-m-d') . ' 23:59:59',
            );
        }

        $recibos = $this->Reciboscaja->obtenerRecibos($conditions);
        $cuentas   = $this->Cuenta->obtenerCuentasEmpresa($empresaId);
        $tipoPagos = $this->Tipopago->obtenerListaTiposPagos($empresaId);

        $this->set(compact(
            'recibos', 'cuentas', 'tipoPagos',
            'filtConsecutivo', 'filtCliente', 'filtEstado',
            'filtCuenta', 'filtTipoPago', 'filtFechaIni', 'filtFechaFin'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ADD — Crear nuevo recibo de caja
    // ─────────────────────────────────────────────────────────────────────────

    public function add() {
        $usuariosController = new UsuariosController();
        $usuariosController->registraractividad($this->Auth->user('id'));

        $this->loadModel('Tipopago');
        $this->loadModel('Cuenta');
        $this->loadModel('Resolucionfactura');

        $empresaId = $this->Auth->user('empresa_id');
        $usuarioId = $this->Auth->user('id');

        if ($this->request->is('post')) {
            $posData = $this->request->data['Reciboscaja'];

            //se obtiene la cuenta co nel tipo de pago
            $infoTipoPago = $this->Tipopago->obtenerTipoPagoPorId( $posData['tipopago_id'] );
            
            $clienteId  = $posData['cliente_id'];
            $valor      = floatval(str_replace(',', '', $posData['valor']));
            $cuentaId   = $infoTipoPago['Tipopago']['cuenta_id'];
            $tipoPagoId = $posData['tipopago_id'];
            $concepto   = !empty($posData['concepto']) ? trim($posData['concepto']) : null;

            // ── Obtener consecutivo desde resolución ──
            $consecutivoData = $this->_obtenerConsecutivoRecibo($empresaId);

            if ($consecutivoData === false) {
                $this->Session->setFlash('No hay una resolución activa configurada para Recibos de Caja. Configure una en el módulo de Resoluciones.');
                $this->redirect(array('action' => 'add'));
                return;
            }

            $consecutivo = $consecutivoData['prefijo'] . '-' . str_pad($consecutivoData['consecutivo'], 4, '0', STR_PAD_LEFT);

            // ── Guardar recibo ──
            $reciboId = $this->Reciboscaja->guardarRecibo(
                $clienteId, $empresaId, $usuarioId, $valor,
                $cuentaId, $tipoPagoId, $consecutivo,
                $consecutivoData['prefijo'], $concepto,
                $consecutivoData['resolucion_id']
            );

            if ($reciboId) {
                // Incrementar el consecutivo en la resolución
                $this->Resolucionfactura->actualizarResolucion($empresaId, self::TIPO_DOC_RECIBO);

                // Sumar el valor a la cuenta
                $this->_sumarSaldoCuenta($cuentaId, $valor);

                $this->Session->setFlash('Recibo de caja ' . $consecutivo . ' creado correctamente.');
                $this->redirect(array('action' => 'view', $reciboId));
            } else {
                $this->Session->setFlash('No se pudo guardar el recibo. Por favor, intente de nuevo.');
            }
        }
        $tipoPagos = $this->Tipopago->obtenerListaTiposPagos($empresaId);

        $this->set(compact('tipoPagos', 'empresaId', 'usuarioId'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // VIEW — Detalle de un recibo y sus abonos aplicados
    // ─────────────────────────────────────────────────────────────────────────

    public function view($id = null) {
        $usuariosController = new UsuariosController();
        $usuariosController->registraractividad($this->Auth->user('id'));

        if (empty($id)) {
            $this->Session->setFlash('Recibo no encontrado.');
            return $this->redirect(array('action' => 'index'));
        }

        $this->loadModel('Abonofactura');

        $recibo = $this->Reciboscaja->obtenerReciboPorId($id);

        if (empty($recibo)) {
            $this->Session->setFlash('El recibo no existe.');
            return $this->redirect(array('action' => 'index'));
        }

        // Verificar que el recibo pertenece a la empresa del usuario
        $empresaId = $this->Auth->user('empresa_id');
        if ($recibo['Reciboscaja']['empresa_id'] != $empresaId) {
            $this->Session->setFlash('No tiene permiso para ver este recibo.');
            return $this->redirect(array('action' => 'index'));
        }

        // Abonos aplicados desde este recibo
        $abonosAplicados = $this->Abonofactura->obtenerAbonosPorRecibo($id);

        $this->set(compact('recibo', 'abonosAplicados'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AJAX — Obtener recibos activos de un cliente (para usarlos en prefacturas/facturas)
    // ─────────────────────────────────────────────────────────────────────────

    public function ajaxObtenerRecibosCliente() {
        $this->autoRender = false;

        $posData   = $this->request->data;
        $clienteId = $posData['clienteId'];
        $empresaId = $this->Auth->user('empresa_id');

        $recibos = $this->Reciboscaja->obtenerRecibosActivosCliente($clienteId, $empresaId);

        $resp = array();
        foreach ($recibos as $r) {
            $resp[] = array(
                'id'          => $r['Reciboscaja']['id'],
                'consecutivo' => $r['Reciboscaja']['consecutivo'],
                'valor'       => $r['Reciboscaja']['valor'],
                'saldo'       => $r['Reciboscaja']['saldo'],
                'concepto'    => $r['Reciboscaja']['concepto'],
                'created'     => $r['Reciboscaja']['created'],
            );
        }

        echo json_encode(array('resp' => $resp));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AJAX — Aplicar un recibo como abono a una prefactura o factura
    // ─────────────────────────────────────────────────────────────────────────

    public function ajaxAplicarRecibo() {
        $this->autoRender = false;

        $this->loadModel('Abonofactura');
        $this->loadModel('Cuenta');

        $posData      = $this->request->data;
        $reciboId     = $posData['reciboId'];
        $valor        = floatval(str_replace(',', '', $posData['valor']));
        $prefacturaId = !empty($posData['prefacturaId']) ? $posData['prefacturaId'] : null;
        $facturaId    = !empty($posData['facturaId'])    ? $posData['facturaId']    : null;
        $empresaId    = $this->Auth->user('empresa_id');
        $usuarioId    = $this->Auth->user('id');

        // Verificar que el recibo pertenece a la empresa y tiene saldo suficiente
        $recibo = $this->Reciboscaja->find('first', array(
            'conditions' => array(
                'Reciboscaja.id'         => $reciboId,
                'Reciboscaja.empresa_id' => $empresaId,
                'Reciboscaja.estado !='  => 'anulado',
            ),
            'recursive' => -1,
        ));

        if (empty($recibo)) {
            echo json_encode(array('resp' => false, 'msg' => 'Recibo no encontrado o anulado.'));
            return;
        }

        if (floatval($recibo['Reciboscaja']['saldo']) < $valor) {
            echo json_encode(array('resp' => false, 'msg' => 'El valor a aplicar supera el saldo disponible del recibo (' . number_format($recibo['Reciboscaja']['saldo'], 0, ',', '.') . ').'));
            return;
        }

        // Registrar el abono en abonofacturas
        $datosAbono = array(
            'prefactura_id'  => $prefacturaId,
            'factura_id'     => $facturaId,
            'usuario_id'     => $usuarioId,
            'valor'          => $valor,
            'empresa_id'     => $empresaId,
            'cuenta_id'      => $recibo['Reciboscaja']['cuenta_id'],
            'tipopago_id'    => $recibo['Reciboscaja']['tipopago_id'],
            'recibocaja_id'  => $reciboId,
            'created'        => date('Y-m-d H:i:s'),
        );

        $this->Abonofactura->create();
        if ($this->Abonofactura->save($datosAbono)) {
            // Descontar el saldo del recibo
            $this->Reciboscaja->descontarSaldo($reciboId, $valor);

            echo json_encode(array(
                'resp'          => true,
                'msg'           => 'Recibo aplicado correctamente.',
                'saldoRestante' => floatval($recibo['Reciboscaja']['saldo']) - $valor,
            ));
        } else {
            echo json_encode(array('resp' => false, 'msg' => 'No se pudo registrar el abono. Intente de nuevo.'));
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AJAX — Anular un recibo
    // ─────────────────────────────────────────────────────────────────────────

    public function ajaxAnularRecibo() {
        $this->autoRender = false;

        $this->loadModel('Cuenta');

        $posData   = $this->request->data;
        $reciboId  = $posData['reciboId'];
        $empresaId = $this->Auth->user('empresa_id');

        // Validar que pertenece a la empresa
        $recibo = $this->Reciboscaja->find('first', array(
            'conditions' => array(
                'Reciboscaja.id'         => $reciboId,
                'Reciboscaja.empresa_id' => $empresaId,
            ),
            'recursive' => -1,
        ));

        if (empty($recibo)) {
            echo json_encode(array('resp' => false, 'msg' => 'Recibo no encontrado.'));
            return;
        }

        $resultado = $this->Reciboscaja->anularRecibo($reciboId);

        if ($resultado['ok']) {
            // Revertir el saldo de la cuenta (descontar lo que se había sumado)
            $this->_restarSaldoCuenta(
                $recibo['Reciboscaja']['cuenta_id'],
                $recibo['Reciboscaja']['valor']
            );
        }

        echo json_encode(array('resp' => $resultado['ok'], 'msg' => $resultado['msg']));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AJAX — Obtener datos completos de un recibo para impresión
    // ─────────────────────────────────────────────────────────────────────────

    public function ajaxObtenerDatosImpresion() {
        $this->autoRender = false;

        $this->loadModel('Abonofactura');
        $this->loadModel('Empresa');

        $posData   = $this->request->data;
        $reciboId  = $posData['reciboId'];
        $empresaId = $this->Auth->user('empresa_id');

        $recibo = $this->Reciboscaja->obtenerReciboPorId($reciboId);

        if (empty($recibo) || $recibo['Reciboscaja']['empresa_id'] != $empresaId) {
            echo json_encode(array('resp' => false, 'msg' => 'Recibo no encontrado.'));
            return;
        }

        $empresa        = $this->Empresa->obtenerEmpresaPorId($empresaId);
        $abonosAplicados = $this->Abonofactura->obtenerAbonosPorRecibo($reciboId);

        echo json_encode(array(
            'resp'    => true,
            'recibo'  => $recibo,
            'empresa' => $empresa,
            'abonos'  => $abonosAplicados,
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SEARCH — Redirige el formulario de búsqueda al index con passedArgs
    // ─────────────────────────────────────────────────────────────────────────

    public function search() {
        $url = array('action' => 'index');
        foreach ($this->data as $k => $v) {
            $url[$k] = $v;
        }
        $this->redirect($url, null, true);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // MÉTODOS PRIVADOS DE APOYO
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Obtiene el consecutivo y prefijo activo para recibos de caja
     * desde la tabla resolucionfacturas.
     *
     * @param  int $empresaId
     * @return array|false  ['consecutivo', 'prefijo', 'resolucion_id'] o false si no existe
     */
    private function _obtenerConsecutivoRecibo($empresaId) {
        $this->loadModel('Resolucionfactura');

        $resolucion = $this->Resolucionfactura->obtenerResolucion($empresaId, self::TIPO_DOC_RECIBO);

        if (empty($resolucion)) {
            return false;
        }

        return array(
            'consecutivo'   => !empty($resolucion['Resolucionfactura']['consecutivoactual'])
                                    ? $resolucion['Resolucionfactura']['consecutivoactual'] : 1,
            'prefijo'       => !empty($resolucion['Resolucionfactura']['prefijo'])
                                    ? $resolucion['Resolucionfactura']['prefijo'] : 'RC',
            'resolucion_id' => $resolucion['Resolucionfactura']['id'],
        );
    }

    /**
     * Suma un valor al saldo de una cuenta.
     *
     * @param int   $cuentaId
     * @param float $valor
     */
    private function _sumarSaldoCuenta($cuentaId, $valor) {
        $this->loadModel('Cuenta');
        $infoCuenta  = $this->Cuenta->obtenerDatosCuentaId($cuentaId);
        $saldoFinal  = floatval($infoCuenta['Cuenta']['saldo']) + floatval($valor);
        $this->Cuenta->actualizarSaldoCuenta($cuentaId, $saldoFinal);
    }

    /**
     * Resta un valor al saldo de una cuenta (para anulaciones).
     *
     * @param int   $cuentaId
     * @param float $valor
     */
    private function _restarSaldoCuenta($cuentaId, $valor) {
        $this->loadModel('Cuenta');
        $infoCuenta  = $this->Cuenta->obtenerDatosCuentaId($cuentaId);
        $saldoFinal  = floatval($infoCuenta['Cuenta']['saldo']) - floatval($valor);
        $this->Cuenta->actualizarSaldoCuenta($cuentaId, $saldoFinal);
    }
}
