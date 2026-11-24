<?php
App::uses('AppModel', 'Model');
App::uses('FacturaRetencion', 'Model');

/**
 * RetencionAplicada Model
 *
 * Lo que el cliente REALMENTE retuvo (definitivo), una fila por tipo de
 * retención y por evento:
 *   - FACTURACION: línea "Retenciones" en la ventana de medios de pago al facturar.
 *   - PAGO_CXC:    al pagar la cuenta por cobrar (paso 6c).
 *
 * Lo CALCULADO está en factura_retenciones (snapshot). Lo pendiente por aplicar
 * de una factura es: calculado - aplicado (no anulado), por tributo.
 */
class RetencionAplicada extends AppModel {

    public $useTable = 'retenciones_aplicadas';

    const ORIGEN_FACTURACION = 'FACTURACION';
    const ORIGEN_PAGO_CXC    = 'PAGO_CXC';

    public $belongsTo = array(
        'Factura' => array(
            'className'  => 'Factura',
            'foreignKey' => 'factura_id',
        ),
    );

    /**
     * Valor aplicado por tributo (sin anuladas).
     *
     * @param  int $facturaId
     * @return array [codigo => float]
     */
    public function aplicadoPorTributo($facturaId) {
        $filas = $this->find('all', array(
            'conditions' => array('RetencionAplicada.factura_id' => (int) $facturaId, 'RetencionAplicada.anulado' => 0),
            'fields'     => array('RetencionAplicada.tributo_codigo', 'SUM(RetencionAplicada.valor) AS valor'),
            'group'      => array('RetencionAplicada.tributo_codigo'),
            'recursive'  => -1,
        ));
        $aplicado = array();
        foreach ($filas as $f) {
            $aplicado[$f['RetencionAplicada']['tributo_codigo']] = round((float) $f[0]['valor'], 2);
        }
        return $aplicado;
    }

    /**
     * Total aplicado de una factura (sin anuladas).
     *
     * @param  int $facturaId
     * @return float
     */
    public function totalAplicado($facturaId) {
        return round(array_sum($this->aplicadoPorTributo($facturaId)), 2);
    }

    /**
     * Lo pendiente por aplicar de una factura, por tributo: calculado - aplicado.
     * Solo tributos que el sistema calculó al facturar (cliente configurado).
     *
     * @param  int $facturaId
     * @return array [codigo => ['etiqueta', 'base', 'calculado', 'aplicado', 'pendiente']]
     */
    public function pendientesPorFactura($facturaId) {
        $FacturaRetencion = ClassRegistry::init('FacturaRetencion');
        $calculado = $FacturaRetencion->totalesPorTributo($facturaId);
        $aplicado = $this->aplicadoPorTributo($facturaId);

        $pendientes = array();
        foreach (FacturaRetencion::$etiquetas as $codigo => $etiqueta) {
            if (empty($calculado[$codigo])) {
                continue;
            }
            $yaAplicado = isset($aplicado[$codigo]) ? $aplicado[$codigo] : 0.0;
            $pendientes[$codigo] = array(
                'etiqueta'  => $etiqueta,
                'base'      => $calculado[$codigo]['base'],
                'calculado' => $calculado[$codigo]['valor'],
                'aplicado'  => $yaAplicado,
                'pendiente' => max(0.0, round($calculado[$codigo]['valor'] - $yaAplicado, 2)),
            );
        }
        return $pendientes;
    }

    /**
     * Guarda las retenciones confirmadas en la ventana de pago al facturar.
     *
     * Reglas:
     *  - Solo si al facturar se calcularon retenciones (cliente configurado) y solo
     *    para esos tributos. Si no, no guarda nada.
     *  - Valores numéricos >= 0; los de 0 no se guardan (quedan como pendientes).
     *  - La suma no puede superar el total de la factura.
     *  - editado_manual = 1 si el valor difiere de lo sugerido (lo pendiente).
     *  - Todo en una transacción. Nunca interrumpe la facturación: si algo falla,
     *    deshace, deja el error en el log y devuelve false.
     *
     * @param  int   $facturaId
     * @param  array $valores      [codigo => valor] enviados desde la ventana
     * @param  int   $usuarioId
     * @param  float $totalFactura total de la factura con impuestos
     * @return bool  true si guardó o no había nada que guardar
     */
    public function guardarDeFacturacion($facturaId, $valores, $usuarioId, $totalFactura) {
        $facturaId = (int) $facturaId;
        $ds = $this->getDataSource();
        $enTransaccion = false;

        try {
            $preparado = $this->prepararFilas($facturaId, $valores, self::ORIGEN_FACTURACION, $usuarioId);
            $filas = $preparado['filas'];
            $total = $preparado['total'];

            if (empty($filas)) {
                return true;
            }
            if ($total - (float) $totalFactura > 0.005) {
                throw new Exception('Las retenciones (' . $total . ') superan el total de la factura (' . $totalFactura . ').');
            }

            $ds->begin();
            $enTransaccion = true;
            if (!$this->saveMany($filas, array('validate' => false, 'atomic' => false))) {
                throw new Exception('No se pudieron guardar las retenciones aplicadas.');
            }
            $ds->commit();
            return true;
        } catch (Exception $e) {
            if ($enTransaccion) {
                $ds->rollback();
            }
            CakeLog::write('error', 'Retenciones aplicadas factura ' . $facturaId . ': no se guardaron. ' . $e->getMessage()
                . ' Valores recibidos: ' . json_encode($valores));
            return false;
        }
    }

    /**
     * Valida los valores de retención enviados por el usuario y arma las filas a
     * guardar. No guarda nada.
     *
     *  - Solo tributos que el sistema calculó al facturar (cliente configurado);
     *    los demás se ignoran.
     *  - Valores numéricos >= 0; los de 0 no generan fila (quedan pendientes).
     *  - valor_calculado = lo pendiente por aplicar; editado_manual si difiere.
     *
     * @param  int    $facturaId
     * @param  array  $valores   [codigo => valor]
     * @param  string $origen    FACTURACION | PAGO_CXC
     * @param  int    $usuarioId
     * @param  array  $extra     campos adicionales de cada fila (abonofactura_id, cuentascliente_id)
     * @return array  ['filas' => [...], 'total' => float]
     * @throws Exception si un valor no es válido o la factura no existe
     */
    public function prepararFilas($facturaId, $valores, $origen, $usuarioId, array $extra = array()) {
        $resultado = array('filas' => array(), 'total' => 0.0);
        if (empty($valores) || !is_array($valores)) {
            return $resultado;
        }

        $pendientes = $this->pendientesPorFactura($facturaId);
        if (empty($pendientes)) {
            // El cliente no está configurado o no hubo retenciones calculadas
            return $resultado;
        }

        $factura = $this->Factura->find('first', array(
            'conditions' => array('Factura.id' => (int) $facturaId),
            'fields'     => array('Factura.id', 'Factura.empresa_id', 'Factura.cliente_id', 'Factura.created'),
            'recursive'  => -1,
        ));
        if (empty($factura)) {
            throw new Exception('La factura no existe.');
        }

        // Al facturar la fecha es la de la factura; en el pago de la CxC, la de hoy
        $fecha = ($origen === self::ORIGEN_FACTURACION) ? substr($factura['Factura']['created'], 0, 10) : date('Y-m-d');
        $ahora = date('Y-m-d H:i:s');

        foreach ($pendientes as $codigo => $p) {
            if (!array_key_exists($codigo, $valores)) {
                continue;
            }
            $valor = $this->_numero($valores[$codigo]);
            if ($valor === null || $valor < 0) {
                throw new Exception('El valor de ' . $p['etiqueta'] . ' no es válido.');
            }
            if ($valor <= 0) {
                continue;
            }
            $resultado['total'] += $valor;
            $resultado['filas'][] = array('RetencionAplicada' => array_merge(array(
                'empresa_id'      => $factura['Factura']['empresa_id'],
                'factura_id'      => (int) $facturaId,
                'cliente_id'      => $factura['Factura']['cliente_id'],
                'origen'          => $origen,
                'tributo_codigo'  => $codigo,
                'nombre'          => $p['etiqueta'],
                'base'            => sprintf('%.2f', $p['base']),
                'valor_calculado' => sprintf('%.2f', $p['pendiente']),
                'valor'           => sprintf('%.2f', $valor),
                'editado_manual'  => abs($valor - $p['pendiente']) >= 0.005 ? 1 : 0,
                'fecha'           => $fecha,
                'usuario_id'      => (int) $usuarioId,
                'created'         => $ahora,
                'modified'        => $ahora,
            ), $extra));
        }
        $resultado['total'] = round($resultado['total'], 2);
        return $resultado;
    }

    /**
     * ¿El abono tiene retenciones aplicadas (no anuladas)?
     *
     * @param  int $abonoId
     * @return bool
     */
    public function abonoTieneRetenciones($abonoId) {
        return $this->find('count', array(
            'conditions' => array('RetencionAplicada.abonofactura_id' => (int) $abonoId, 'RetencionAplicada.anulado' => 0),
            'recursive'  => -1,
        )) > 0;
    }

    /**
     * Total de retenciones aplicadas al pagar una cuenta por cobrar.
     *
     * @param  int $cuentaClienteId
     * @return float
     */
    public function totalPorCuentaCliente($cuentaClienteId) {
        $fila = $this->find('first', array(
            'conditions' => array('RetencionAplicada.cuentascliente_id' => (int) $cuentaClienteId, 'RetencionAplicada.anulado' => 0),
            'fields'     => array('SUM(RetencionAplicada.valor) AS total'),
            'recursive'  => -1,
        ));
        return !empty($fila[0]['total']) ? round((float) $fila[0]['total'], 2) : 0.0;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // REPORTES (paso 7). Fuente: retenciones aplicadas no anuladas, de facturas
    // no eliminadas, de la empresa indicada.
    // ─────────────────────────────────────────────────────────────────────────

    /** Tributos válidos para el filtro de tipo */
    public static $tributosReporte = array('06' => 'ReteFuente', '05' => 'ReteIVA', '07' => 'ReteICA');

    /**
     * Filtros normalizados del reporte (fechas Y-m-d; por defecto el mes actual).
     *
     * @param  array $entrada desde, hasta, cliente, tributo, certificado
     * @return array
     */
    public function normalizarFiltrosReporte(array $entrada) {
        $fecha = function ($v, $defecto) {
            return (is_string($v) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) && strtotime($v) !== false) ? $v : $defecto;
        };
        $f = array(
            'desde'       => $fecha(isset($entrada['desde']) ? $entrada['desde'] : null, date('Y-m-01')),
            'hasta'       => $fecha(isset($entrada['hasta']) ? $entrada['hasta'] : null, date('Y-m-d')),
            'cliente'     => isset($entrada['cliente']) ? trim(substr((string) $entrada['cliente'], 0, 100)) : '',
            'tributo'     => (isset($entrada['tributo']) && isset(self::$tributosReporte[$entrada['tributo']])) ? $entrada['tributo'] : '',
            'certificado' => (isset($entrada['certificado']) && in_array($entrada['certificado'], array('0', '1'), true)) ? $entrada['certificado'] : '',
        );
        if ($f['hasta'] < $f['desde']) {
            $tmp = $f['desde'];
            $f['desde'] = $f['hasta'];
            $f['hasta'] = $tmp;
        }
        return $f;
    }

    /**
     * WHERE común de los reportes sobre retenciones_aplicadas (alias RA, C, F).
     *
     * @param  int   $empresaId
     * @param  array $f       filtros normalizados
     * @param  array $params  parámetros de la consulta (se llenan aquí)
     * @param  bool  $conCertificado aplicar el filtro de certificado
     * @return string
     */
    protected function _whereReporte($empresaId, array $f, array &$params, $conCertificado = true) {
        $where = array('RA.empresa_id = ?', 'RA.anulado = 0', 'COALESCE(F.eliminar, 0) = 0', 'RA.fecha BETWEEN ? AND ?');
        $params[] = (int) $empresaId;
        $params[] = $f['desde'];
        $params[] = $f['hasta'];
        if ($f['cliente'] !== '') {
            $where[] = '(C.nit LIKE ? OR C.nombre LIKE ?)';
            $params[] = '%' . $f['cliente'] . '%';
            $params[] = '%' . $f['cliente'] . '%';
        }
        if ($f['tributo'] !== '') {
            $where[] = 'RA.tributo_codigo = ?';
            $params[] = $f['tributo'];
        }
        if ($conCertificado && $f['certificado'] !== '') {
            $where[] = 'RA.certificado_recibido = ?';
            $params[] = (int) $f['certificado'];
        }
        return implode(' AND ', $where);
    }

    /** Número de factura para mostrar: prefijo + consecutivo DIAN (o el interno) */
    protected function _numeroFactura($f) {
        $consecutivo = !empty($f['consecutivodian']) ? $f['consecutivodian'] : $f['consecutivodv'];
        return trim((string) (isset($f['prefijo']) ? $f['prefijo'] : '') . $consecutivo);
    }

    /**
     * Reporte "Retenciones que me practicaron": una fila por retención aplicada.
     *
     * Tarifa: la del snapshot si la factura usó una sola para ese tributo; si
     * mezcló tarifas (por ejemplo productos 2,5% y servicios 4%), "Varias".
     *
     * @return array ['filas' => [...], 'total_base' => float, 'total_valor' => float]
     */
    public function reporteDetalle($empresaId, array $f) {
        $params = array();
        $where = $this->_whereReporte($empresaId, $f, $params);
        $filas = $this->query(
            "SELECT RA.id, RA.fecha, RA.origen, RA.tributo_codigo, RA.nombre, RA.base, RA.valor,
                    RA.certificado_recibido, RA.certificado_archivo, RA.certificado_fecha, RA.factura_id,
                    C.nit, C.nombre, F.prefijo, F.consecutivodian, F.consecutivodv
             FROM retenciones_aplicadas RA
             INNER JOIN facturas F ON F.id = RA.factura_id
             LEFT JOIN clientes C ON C.id = RA.cliente_id
             WHERE $where
             ORDER BY RA.fecha, C.nombre, RA.id",
            $params
        );

        // Tarifas del snapshot por factura y tributo
        $tarifas = array();
        $facturaIds = array();
        foreach ($filas as $r) {
            $facturaIds[(int) $r['RA']['factura_id']] = true;
        }
        if (!empty($facturaIds)) {
            $ids = implode(',', array_map('intval', array_keys($facturaIds)));
            $snap = $this->query(
                "SELECT factura_id, tributo_codigo, COUNT(DISTINCT CONCAT(tarifa, '|', tipo_valor)) AS n,
                        MIN(tarifa) AS tarifa, MIN(tipo_valor) AS tipo_valor
                 FROM factura_retenciones WHERE factura_id IN ($ids)
                 GROUP BY factura_id, tributo_codigo"
            );
            foreach ($snap as $s) {
                $x = $s['factura_retenciones'] + $s[0];
                $tarifas[$x['factura_id'] . '_' . $x['tributo_codigo']] = ((int) $x['n'] === 1)
                    ? rtrim(rtrim(number_format((float) $x['tarifa'], 4, ',', '.'), '0'), ',') . ($x['tipo_valor'] === 'POR_MIL' ? ' por mil' : ' %')
                    : 'Varias';
            }
        }

        $resultado = array('filas' => array(), 'total_base' => 0.0, 'total_valor' => 0.0);
        foreach ($filas as $r) {
            $clave = $r['RA']['factura_id'] . '_' . $r['RA']['tributo_codigo'];
            $resultado['filas'][] = array(
                'id'               => (int) $r['RA']['id'],
                'fecha'            => $r['RA']['fecha'],
                'origen'           => $r['RA']['origen'] === self::ORIGEN_FACTURACION ? 'Al facturar' : 'Pago CxC',
                'nit'              => $r['C']['nit'],
                'cliente'          => $r['C']['nombre'],
                'factura_id'       => (int) $r['RA']['factura_id'],
                'factura'          => $this->_numeroFactura($r['F']),
                'tributo'          => $r['RA']['tributo_codigo'],
                'retencion'        => $r['RA']['nombre'],
                'base'             => (float) $r['RA']['base'],
                'tarifa'           => isset($tarifas[$clave]) ? $tarifas[$clave] : '',
                'valor'            => (float) $r['RA']['valor'],
                'certificado'      => (int) $r['RA']['certificado_recibido'] === 1,
                'tiene_archivo'    => !empty($r['RA']['certificado_archivo']),
                'certificado_fecha'=> $r['RA']['certificado_fecha'],
            );
            $resultado['total_base'] += (float) $r['RA']['base'];
            $resultado['total_valor'] += (float) $r['RA']['valor'];
        }
        $resultado['total_base'] = round($resultado['total_base'], 2);
        $resultado['total_valor'] = round($resultado['total_valor'], 2);
        return $resultado;
    }

    /**
     * Reporte "Certificados pendientes": por cliente, retenciones sin certificado.
     *
     * @return array ['filas' => [...], 'total_valor' => float]
     */
    public function reporteCertificadosPendientes($empresaId, array $f) {
        $params = array();
        $where = $this->_whereReporte($empresaId, $f, $params, false);
        $filas = $this->query(
            "SELECT RA.cliente_id, MAX(C.nit) AS nit, MAX(C.nombre) AS nombre, COUNT(*) AS cantidad,
                    SUM(RA.valor) AS total, MIN(RA.fecha) AS mas_antigua
             FROM retenciones_aplicadas RA
             INNER JOIN facturas F ON F.id = RA.factura_id
             LEFT JOIN clientes C ON C.id = RA.cliente_id
             WHERE $where AND RA.certificado_recibido = 0
             GROUP BY RA.cliente_id
             ORDER BY mas_antigua, nombre",
            $params
        );
        $resultado = array('filas' => array(), 'total_valor' => 0.0);
        foreach ($filas as $r) {
            $x = $r[0] + $r['RA'];
            $resultado['filas'][] = array(
                'nit'         => $x['nit'],
                'cliente'     => $x['nombre'],
                'cantidad'    => (int) $x['cantidad'],
                'valor'       => round((float) $x['total'], 2),
                'mas_antigua' => $x['mas_antigua'],
            );
            $resultado['total_valor'] += (float) $x['total'];
        }
        $resultado['total_valor'] = round($resultado['total_valor'], 2);
        return $resultado;
    }

    /**
     * Reporte "Resumen del período": totales por tipo y ReteICA por ciudad.
     * La ciudad de ReteICA sale del snapshot de la factura (ciudad del cliente al facturar).
     *
     * @return array ['tributos' => [...], 'ciudades' => [...], 'total_valor' => float]
     */
    public function reporteResumen($empresaId, array $f) {
        $params = array();
        $where = $this->_whereReporte($empresaId, $f, $params);
        $porTributo = $this->query(
            "SELECT RA.tributo_codigo, COUNT(*) AS cantidad, SUM(RA.base) AS base, SUM(RA.valor) AS valor
             FROM retenciones_aplicadas RA
             INNER JOIN facturas F ON F.id = RA.factura_id
             LEFT JOIN clientes C ON C.id = RA.cliente_id
             WHERE $where
             GROUP BY RA.tributo_codigo",
            $params
        );
        $sumas = array();
        foreach ($porTributo as $r) {
            $sumas[$r['RA']['tributo_codigo']] = $r[0];
        }
        $resultado = array('tributos' => array(), 'ciudades' => array(), 'total_valor' => 0.0);
        foreach (self::$tributosReporte as $codigo => $nombre) {
            if (empty($sumas[$codigo])) {
                continue;
            }
            $resultado['tributos'][] = array(
                'tributo'  => $codigo,
                'nombre'   => $nombre,
                'cantidad' => (int) $sumas[$codigo]['cantidad'],
                'base'     => round((float) $sumas[$codigo]['base'], 2),
                'valor'    => round((float) $sumas[$codigo]['valor'], 2),
            );
            $resultado['total_valor'] += (float) $sumas[$codigo]['valor'];
        }
        $resultado['total_valor'] = round($resultado['total_valor'], 2);

        if ($f['tributo'] === '' || $f['tributo'] === '07') {
            $paramsIca = array();
            $whereIca = $this->_whereReporte($empresaId, $f, $paramsIca);
            $ciudades = $this->query(
                "SELECT COALESCE(CM.nombre, 'Sin ciudad') AS ciudad, COUNT(*) AS cantidad, SUM(RA.base) AS base, SUM(RA.valor) AS valor
                 FROM retenciones_aplicadas RA
                 INNER JOIN facturas F ON F.id = RA.factura_id
                 LEFT JOIN clientes C ON C.id = RA.cliente_id
                 LEFT JOIN (SELECT factura_id, MAX(ciudadesmiggo_id) AS ciudad_id
                            FROM factura_retenciones WHERE tributo_codigo = '07' GROUP BY factura_id) FRC
                        ON FRC.factura_id = RA.factura_id
                 LEFT JOIN ciudadesmiggos CM ON CM.id = FRC.ciudad_id
                 WHERE $whereIca AND RA.tributo_codigo = '07'
                 GROUP BY ciudad
                 ORDER BY ciudad",
                $paramsIca
            );
            foreach ($ciudades as $c) {
                $resultado['ciudades'][] = array(
                    'ciudad'   => $c[0]['ciudad'],
                    'cantidad' => (int) $c[0]['cantidad'],
                    'base'     => round((float) $c[0]['base'], 2),
                    'valor'    => round((float) $c[0]['valor'], 2),
                );
            }
        }
        return $resultado;
    }

    /**
     * Reporte "Diferencias estimado vs. real", por factura y tributo. El período
     * es la fecha de la factura. Estados:
     *   Igual      lo aplicado coincide con lo calculado.
     *   Pendiente  falta aplicar y la cuenta por cobrar aún tiene saldo.
     *   No retuvo  la factura ya no tiene saldo y no se aplicó nada.
     *   Diferente  la factura ya no tiene saldo y se aplicó un valor distinto.
     *
     * @return array ['filas' => [...], 'total_calculado', 'total_aplicado', 'total_diferencia']
     */
    public function reporteDiferencias($empresaId, array $f) {
        $where = array('F.empresa_id = ?', 'COALESCE(F.eliminar, 0) = 0', 'F.created BETWEEN ? AND ?');
        $params = array((int) $empresaId, $f['desde'] . ' 00:00:00', $f['hasta'] . ' 23:59:59');
        if ($f['cliente'] !== '') {
            $where[] = '(C.nit LIKE ? OR C.nombre LIKE ?)';
            $params[] = '%' . $f['cliente'] . '%';
            $params[] = '%' . $f['cliente'] . '%';
        }
        if ($f['tributo'] !== '') {
            $where[] = 'FR.tributo_codigo = ?';
            $params[] = $f['tributo'];
        }
        $where = implode(' AND ', $where);

        $filas = $this->query(
            "SELECT FR.factura_id, FR.tributo_codigo, SUM(FR.valor) AS calculado,
                    MAX(F.created) AS fecha, MAX(F.prefijo) AS prefijo, MAX(F.consecutivodian) AS consecutivodian,
                    MAX(F.consecutivodv) AS consecutivodv, MAX(C.nit) AS nit, MAX(C.nombre) AS nombre,
                    (SELECT COALESCE(SUM(A.valor), 0) FROM retenciones_aplicadas A
                      WHERE A.factura_id = FR.factura_id AND A.tributo_codigo = FR.tributo_codigo AND A.anulado = 0) AS aplicado,
                    (SELECT COALESCE(SUM(CAST(CC.totalobligacion AS DECIMAL(18,2))), 0) FROM cuentasclientes CC
                      WHERE CC.factura_id = FR.factura_id AND COALESCE(CC.eliminar, 0) = 0) AS saldo_cxc
             FROM factura_retenciones FR
             INNER JOIN facturas F ON F.id = FR.factura_id
             LEFT JOIN clientes C ON C.id = F.cliente_id
             WHERE $where
             GROUP BY FR.factura_id, FR.tributo_codigo
             ORDER BY fecha, FR.factura_id, FR.tributo_codigo",
            $params
        );

        $resultado = array('filas' => array(), 'total_calculado' => 0.0, 'total_aplicado' => 0.0, 'total_diferencia' => 0.0);
        foreach ($filas as $r) {
            $x = $r[0] + $r['FR'];
            $calculado = round((float) $x['calculado'], 2);
            $aplicado = round((float) $x['aplicado'], 2);
            $diferencia = round($calculado - $aplicado, 2);
            if (abs($diferencia) < 0.005) {
                $estado = 'Igual';
            } elseif ((float) $x['saldo_cxc'] > 0.005) {
                $estado = 'Pendiente';
            } elseif ($aplicado <= 0) {
                $estado = 'No retuvo';
            } else {
                $estado = 'Diferente';
            }
            $resultado['filas'][] = array(
                'fecha'      => substr($x['fecha'], 0, 10),
                'factura_id' => (int) $x['factura_id'],
                'factura'    => $this->_numeroFactura($x),
                'nit'        => $x['nit'],
                'cliente'    => $x['nombre'],
                'retencion'  => isset(self::$tributosReporte[$x['tributo_codigo']]) ? self::$tributosReporte[$x['tributo_codigo']] : $x['tributo_codigo'],
                'calculado'  => $calculado,
                'aplicado'   => $aplicado,
                'diferencia' => $diferencia,
                'estado'     => $estado,
            );
            $resultado['total_calculado'] += $calculado;
            $resultado['total_aplicado'] += $aplicado;
            $resultado['total_diferencia'] += $diferencia;
        }
        foreach (array('total_calculado', 'total_aplicado', 'total_diferencia') as $k) {
            $resultado[$k] = round($resultado[$k], 2);
        }
        return $resultado;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CERTIFICADOS DE RETENCIÓN (paso 7)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Carpeta (no pública) donde se guardan los certificados. Se puede cambiar
     * con configuraciondatos.dirCertRetenciones (ruta absoluta).
     *
     * @return string ruta absoluta terminada en separador
     */
    public function directorioCertificados() {
        $Configuraciondato = ClassRegistry::init('Configuraciondato');
        $dir = $Configuraciondato->obtenerValorDatoConfig('dirCertRetenciones');
        if (empty($dir)) {
            $dir = APP . 'Certificadosretenciones';
        }
        return rtrim($dir, '/\\') . DS;
    }

    /** Ids válidos (enteros > 0, sin repetir) */
    protected function _idsLimpios($ids) {
        $limpios = array();
        foreach ((array) $ids as $id) {
            if (is_numeric($id) && (int) $id > 0) {
                $limpios[(int) $id] = (int) $id;
            }
        }
        return array_values($limpios);
    }

    /**
     * Marca retenciones de la empresa como "certificado recibido" con un archivo.
     *
     * @param  int    $empresaId
     * @param  array  $ids
     * @param  string $archivo  ruta relativa (empresa/archivo)
     * @param  string $fecha    Y-m-d
     * @return int    retenciones marcadas
     */
    public function registrarCertificado($empresaId, $ids, $archivo, $fecha) {
        $ids = $this->_idsLimpios($ids);
        if (empty($ids)) {
            return 0;
        }
        $db = $this->getDataSource();
        $this->updateAll(
            array(
                'RetencionAplicada.certificado_recibido' => 1,
                'RetencionAplicada.certificado_archivo'  => $db->value($archivo, 'string'),
                'RetencionAplicada.certificado_fecha'    => $db->value($fecha, 'string'),
                'RetencionAplicada.modified'             => $db->value(date('Y-m-d H:i:s'), 'string'),
            ),
            array('RetencionAplicada.id' => $ids, 'RetencionAplicada.empresa_id' => (int) $empresaId, 'RetencionAplicada.anulado' => 0)
        );
        return (int) $this->getAffectedRows();
    }

    /**
     * Quita el certificado de retenciones de la empresa.
     *
     * @return array ['quitadas' => int, 'archivos_sin_uso' => [rutas relativas que ya nadie usa]]
     */
    public function quitarCertificado($empresaId, $ids) {
        $ids = $this->_idsLimpios($ids);
        $resultado = array('quitadas' => 0, 'archivos_sin_uso' => array());
        if (empty($ids)) {
            return $resultado;
        }
        $condiciones = array('RetencionAplicada.id' => $ids, 'RetencionAplicada.empresa_id' => (int) $empresaId);
        $archivos = $this->find('list', array(
            'conditions' => $condiciones + array('RetencionAplicada.certificado_archivo IS NOT NULL'),
            'fields'     => array('RetencionAplicada.id', 'RetencionAplicada.certificado_archivo'),
            'recursive'  => -1,
        ));
        $db = $this->getDataSource();
        $this->updateAll(
            array(
                'RetencionAplicada.certificado_recibido' => 0,
                'RetencionAplicada.certificado_archivo'  => null,
                'RetencionAplicada.certificado_fecha'    => null,
                'RetencionAplicada.modified'             => $db->value(date('Y-m-d H:i:s'), 'string'),
            ),
            $condiciones
        );
        $resultado['quitadas'] = (int) $this->getAffectedRows();

        // Un mismo archivo puede cubrir varias retenciones: solo queda sin uso si ninguna lo tiene
        foreach (array_unique(array_values($archivos)) as $archivo) {
            $enUso = $this->find('count', array(
                'conditions' => array('RetencionAplicada.certificado_archivo' => $archivo),
                'recursive'  => -1,
            ));
            if ($enUso === 0) {
                $resultado['archivos_sin_uso'][] = $archivo;
            }
        }
        return $resultado;
    }

    /**
     * Archivo del certificado de una retención de la empresa, o null.
     *
     * @return string|null ruta relativa
     */
    public function archivoCertificado($empresaId, $id) {
        $archivo = $this->field('certificado_archivo', array(
            'RetencionAplicada.id'         => (int) $id,
            'RetencionAplicada.empresa_id' => (int) $empresaId,
        ));
        return !empty($archivo) ? $archivo : null;
    }

    /** Número a partir del texto de la ventana ("31,000.50" o "31000.5"); null si no es válido */
    protected function _numero($valor) {
        $texto = str_replace(array(',', ' ', '$'), '', trim((string) $valor));
        if ($texto === '') {
            return 0.0;
        }
        return is_numeric($texto) ? round((float) $texto, 2) : null;
    }
}
