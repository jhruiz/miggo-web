<?php
App::uses('AppModel', 'Model');

/**
 * Model Reciboscaja
 * 
 * Maneja los recibos de caja y anticipos de clientes.
 * Un recibo de caja registra el ingreso de dinero de un cliente.
 * Puede aplicarse parcial o totalmente a prefacturas/facturas
 * generando registros en la tabla abonofacturas.
 */
class Reciboscaja extends AppModel {

    public $displayField = 'consecutivo';

    public $belongsTo = array(
        'Cliente' => array(
            'className'   => 'Cliente',
            'foreignKey'  => 'cliente_id',
            'conditions'  => '',
            'fields'      => '',
            'order'       => ''
        ),
        'Empresa' => array(
            'className'   => 'Empresa',
            'foreignKey'  => 'empresa_id',
            'conditions'  => '',
            'fields'      => '',
            'order'       => ''
        ),
        'Usuario' => array(
            'className'   => 'Usuario',
            'foreignKey'  => 'usuario_id',
            'conditions'  => '',
            'fields'      => '',
            'order'       => ''
        ),
        'Cuenta' => array(
            'className'   => 'Cuenta',
            'foreignKey'  => 'cuenta_id',
            'conditions'  => '',
            'fields'      => '',
            'order'       => ''
        ),
        'Tipopago' => array(
            'className'   => 'Tipopago',
            'foreignKey'  => 'tipopago_id',
            'conditions'  => '',
            'fields'      => '',
            'order'       => ''
        ),
    );

    // ─────────────────────────────────────────────
    // GUARDAR
    // ─────────────────────────────────────────────

    /**
     * Crea un nuevo recibo de caja.
     * El saldo inicial es igual al valor total.
     *
     * @param  int    $clienteId
     * @param  int    $empresaId
     * @param  int    $usuarioId
     * @param  float  $valor
     * @param  int    $cuentaId
     * @param  int    $tipoPagoId
     * @param  string $consecutivo  Ej: "RC-0001"
     * @param  string $prefijo      Ej: "RC"
     * @param  string $concepto     Observación libre
     * @param  int    $resolucionId FK a resolucionfacturas
     * @return int|false  ID del recibo creado, o false si falla
     */
    public function guardarRecibo($clienteId, $empresaId, $usuarioId, $valor,
                                  $cuentaId, $tipoPagoId, $consecutivo, $prefijo,
                                  $concepto, $resolucionId) {
        $recibo = new Reciboscaja();

        $data = array(
            'cliente_id'            => $clienteId,
            'empresa_id'            => $empresaId,
            'usuario_id'            => $usuarioId,
            'valor'                 => $valor,
            'saldo'                 => $valor,   // saldo inicial = valor total
            'cuenta_id'             => $cuentaId,
            'tipopago_id'           => $tipoPagoId,
            'consecutivo'           => $consecutivo,
            'prefijo'               => $prefijo,
            'concepto'              => $concepto,
            'estado'                => 'activo',
            'resolucionfactura_id'  => $resolucionId,
            'created'               => date('Y-m-d H:i:s'),
        );

        if ($recibo->save($data)) {
            return $recibo->id;
        }
        return false;
    }

    // ─────────────────────────────────────────────
    // LECTURA — LISTADO / BÚSQUEDA
    // ─────────────────────────────────────────────

    /**
     * Listado de recibos de una empresa con filtros opcionales.
     * Trae datos del cliente, usuario, cuenta y tipo de pago.
     *
     * @param  array $conditions  Condiciones CakePHP (incluye empresa_id obligatorio)
     * @return array
     */
    public function obtenerRecibos($conditions) {
        $joins = array();

        $joins[] = array(
            'table'      => 'clientes',
            'alias'      => 'CL',
            'type'       => 'LEFT',
            'conditions' => array('CL.id = Reciboscaja.cliente_id'),
        );

        $joins[] = array(
            'table'      => 'usuarios',
            'alias'      => 'U',
            'type'       => 'INNER',
            'conditions' => array('U.id = Reciboscaja.usuario_id'),
        );

        $joins[] = array(
            'table'      => 'cuentas',
            'alias'      => 'CU',
            'type'       => 'INNER',
            'conditions' => array('CU.id = Reciboscaja.cuenta_id'),
        );

        $joins[] = array(
            'table'      => 'tipopagos',
            'alias'      => 'TP',
            'type'       => 'INNER',
            'conditions' => array('TP.id = Reciboscaja.tipopago_id'),
        );

        return $this->find('all', array(
            'joins'      => $joins,
            'fields'     => array(
                'Reciboscaja.*',
                'CL.nombre', 'CL.nit',
                'U.nombre',
                'CU.descripcion',
                'TP.descripcion',
            ),
            'conditions' => $conditions,
            'order'      => 'Reciboscaja.created DESC',
            'recursive'  => -1,
        ));
    }

    /**
     * Obtiene un recibo por ID con todos sus datos relacionados.
     *
     * @param  int $id
     * @return array
     */
    public function obtenerReciboPorId($id) {
        $joins = array();

        $joins[] = array(
            'table'      => 'clientes',
            'alias'      => 'CL',
            'type'       => 'LEFT',
            'conditions' => array('CL.id = Reciboscaja.cliente_id'),
        );

        $joins[] = array(
            'table'      => 'usuarios',
            'alias'      => 'U',
            'type'       => 'INNER',
            'conditions' => array('U.id = Reciboscaja.usuario_id'),
        );

        $joins[] = array(
            'table'      => 'cuentas',
            'alias'      => 'CU',
            'type'       => 'INNER',
            'conditions' => array('CU.id = Reciboscaja.cuenta_id'),
        );

        $joins[] = array(
            'table'      => 'tipopagos',
            'alias'      => 'TP',
            'type'       => 'INNER',
            'conditions' => array('TP.id = Reciboscaja.tipopago_id'),
        );

        return $this->find('first', array(
            'joins'      => $joins,
            'fields'     => array(
                'Reciboscaja.*',
                'CL.nombre', 'CL.nit', 'CL.telefono', 'CL.email',
                'U.nombre',
                'CU.descripcion',
                'TP.descripcion',
            ),
            'conditions' => array('Reciboscaja.id' => $id),
            'recursive'  => -1,
        ));
    }

    /**
     * Recibos activos (con saldo disponible) de un cliente.
     * Usado al momento de aplicar un recibo a una factura/prefactura.
     *
     * @param  int $clienteId
     * @param  int $empresaId
     * @return array
     */
    public function obtenerRecibosActivosCliente($clienteId, $empresaId) {
        return $this->find('all', array(
            'conditions' => array(
                'Reciboscaja.cliente_id' => $clienteId,
                'Reciboscaja.empresa_id' => $empresaId,
                'Reciboscaja.estado'     => 'activo',
                'Reciboscaja.saldo >'    => 0,
            ),
            'fields'    => array(
                'Reciboscaja.id',
                'Reciboscaja.consecutivo',
                'Reciboscaja.valor',
                'Reciboscaja.saldo',
                'Reciboscaja.concepto',
                'Reciboscaja.created',
            ),
            'order'     => 'Reciboscaja.created ASC',
            'recursive' => -1,
        ));
    }

    // ─────────────────────────────────────────────
    // ACTUALIZAR SALDO
    // ─────────────────────────────────────────────

    /**
     * Descuenta un valor del saldo disponible del recibo.
     * Si el saldo llega a 0, cambia el estado a 'aplicado'.
     *
     * @param  int   $reciboId
     * @param  float $valorAplicado
     * @return bool
     */
    public function descontarSaldo($reciboId, $valorAplicado) {
        $recibo = $this->find('first', array(
            'conditions' => array('Reciboscaja.id' => $reciboId),
            'recursive'  => -1,
        ));

        if (empty($recibo)) {
            return false;
        }

        $saldoNuevo = floatval($recibo['Reciboscaja']['saldo']) - floatval($valorAplicado);

        // Nunca dejar saldo negativo
        if ($saldoNuevo < 0) {
            return false;
        }

        $data = array(
            'id'    => $reciboId,
            'saldo' => $saldoNuevo,
            'estado' => $saldoNuevo == 0 ? 'aplicado' : 'activo',
        );

        $r = new Reciboscaja();
        return (bool) $r->save($data);
    }

    /**
     * Devuelve el saldo al recibo cuando se elimina un abono asociado.
     * Si el recibo estaba 'aplicado', lo vuelve a 'activo'.
     *
     * @param  int   $reciboId
     * @param  float $valorDevuelto
     * @return bool
     */
    public function devolverSaldo($reciboId, $valorDevuelto) {
        $recibo = $this->find('first', array(
            'conditions' => array('Reciboscaja.id' => $reciboId),
            'recursive'  => -1,
        ));

        if (empty($recibo)) {
            return false;
        }

        $saldoNuevo = floatval($recibo['Reciboscaja']['saldo']) + floatval($valorDevuelto);

        // El saldo no puede superar el valor original del recibo
        if ($saldoNuevo > floatval($recibo['Reciboscaja']['valor'])) {
            $saldoNuevo = floatval($recibo['Reciboscaja']['valor']);
        }

        $data = array(
            'id'     => $reciboId,
            'saldo'  => $saldoNuevo,
            'estado' => 'activo',
        );

        $r = new Reciboscaja();
        return (bool) $r->save($data);
    }

    // ─────────────────────────────────────────────
    // ANULACIÓN
    // ─────────────────────────────────────────────

    /**
     * Anula un recibo de caja.
     * Solo se puede anular si no tiene abonos aplicados (saldo == valor).
     *
     * @param  int $reciboId
     * @return array ['ok' => bool, 'msg' => string]
     */
    public function anularRecibo($reciboId) {
        $recibo = $this->find('first', array(
            'conditions' => array('Reciboscaja.id' => $reciboId),
            'recursive'  => -1,
        ));

        if (empty($recibo)) {
            return array('ok' => false, 'msg' => 'El recibo no existe.');
        }

        if ($recibo['Reciboscaja']['estado'] === 'anulado') {
            return array('ok' => false, 'msg' => 'El recibo ya está anulado.');
        }

        $valorTotal = floatval($recibo['Reciboscaja']['valor']);
        $saldo      = floatval($recibo['Reciboscaja']['saldo']);

        if ($saldo < $valorTotal) {
            return array('ok' => false, 'msg' => 'No es posible anular el recibo porque tiene abonos aplicados. Primero elimine los abonos asociados.');
        }

        $data = array('id' => $reciboId, 'estado' => 'anulado');
        $r = new Reciboscaja();

        if ($r->save($data)) {
            return array('ok' => true, 'msg' => 'Recibo anulado correctamente.');
        }
        return array('ok' => false, 'msg' => 'No se pudo anular el recibo. Intente de nuevo.');
    }

    // ─────────────────────────────────────────────
    // REPORTES
    // ─────────────────────────────────────────────

    /**
     * Obtiene el total de recibos de caja en un rango de fechas
     * agrupado por cuenta, útil para el cierre diario.
     *
     * @param  string $fechaIni  'Y-m-d 00:00:00'
     * @param  string $fechaFin  'Y-m-d 23:59:59'
     * @param  int    $empresaId
     * @return array
     */
    public function totalRecibosPorCuenta($fechaIni, $fechaFin, $empresaId) {
        $joins = array();

        $joins[] = array(
            'table'      => 'cuentas',
            'alias'      => 'CU',
            'type'       => 'INNER',
            'conditions' => array('CU.id = Reciboscaja.cuenta_id'),
        );

        return $this->find('all', array(
            'joins'      => $joins,
            'fields'     => array(
                'Reciboscaja.cuenta_id',
                'CU.descripcion',
                'SUM(Reciboscaja.valor) AS total',
                'COUNT(Reciboscaja.id) AS cantidad',
            ),
            'conditions' => array(
                'Reciboscaja.created BETWEEN ? AND ?' => array($fechaIni, $fechaFin),
                'Reciboscaja.empresa_id'              => $empresaId,
                'Reciboscaja.estado !='               => 'anulado',
            ),
            'group'     => 'Reciboscaja.cuenta_id',
            'recursive' => -1,
        ));
    }

    /**
     * Obtiene los recibos de caja y los abonos relacionados a este
     * @param  string $fechaIni  'Y-m-d 00:00:00'
     * @param  string $fechaFin  'Y-m-d 23:59:59'
     * @param  int    $empresaId
     * @return array
     */
    public function recibosCajaAbonos($conditions) {
        $joins = array();

        $joins[] = array(
            'table'      => 'clientes',
            'alias'      => 'CL',
            'type'       => 'LEFT',
            'conditions' => array('CL.id = Reciboscaja.cliente_id'),
        );

        $joins[] = array(
            'table'      => 'usuarios',
            'alias'      => 'U',
            'type'       => 'INNER',
            'conditions' => array('U.id = Reciboscaja.usuario_id'),
        );

        $joins[] = array(
            'table'      => 'cuentas',
            'alias'      => 'CU',
            'type'       => 'INNER',
            'conditions' => array('CU.id = Reciboscaja.cuenta_id'),
        );

        $joins[] = array(
            'table'      => 'tipopagos',
            'alias'      => 'TP',
            'type'       => 'INNER',
            'conditions' => array('TP.id = Reciboscaja.tipopago_id'),
        );

        $joins[] = array(
            'table'      => 'abonofacturas',
            'alias'      => 'AF',
            'type'       => 'LEFT',
            'conditions' => array('AF.recibocaja_id = Reciboscaja.id'),
        );

        $joins[] = array(
            'table'      => 'prefacturas',
            'alias'      => 'PF',
            'type'       => 'LEFT',
            'conditions' => array('AF.prefactura_id = PF.id'),
        );

        $joins[] = array(
            'table'      => 'facturas',
            'alias'      => 'FC',
            'type'       => 'LEFT',
            'conditions' => array('AF.factura_id = FC.id'),
        );

        return $this->find('all', array(
            'joins'      => $joins,
            'fields'     => array(
                'Reciboscaja.*',
                'CL.nombre', 'CL.nit', 'CL.telefono', 'CL.email',
                'U.nombre',
                'CU.descripcion',
                'TP.descripcion',
                'AF.*',
                'PF.*',
                'FC.*'
            ),
            'conditions' => $conditions,
            'recursive'  => -1,
        ));
    }
}
