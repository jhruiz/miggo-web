<?php
App::uses('AppShell', 'Console/Command');
App::uses('MotorRetenciones', 'Lib');

/**
 * Pruebas del motor de retenciones en ventas.
 *
 *   cake retenciones_prueba            casos CP-01 a CP-14 y extras (sin base de datos)
 *   cake retenciones_prueba real ID    calcula una factura real (solo lectura)
 *
 * Volver a correrlo cada vez que se cambie el motor o las reglas.
 */
class RetencionesPruebaShell extends AppShell {

    public $uses = array();

    const UVT_2026 = 52374;
    const CALI = 1006;

    private $_ok = 0;
    private $_fallas = 0;

    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->description('Pruebas del motor de retenciones en ventas.');
        $parser->addSubcommand('real', array('help' => 'Calcula las retenciones de una factura real (solo lectura).'));
        return $parser;
    }

    public function main() {
        $motor = new MotorRetenciones();

        $this->_cp01($motor);
        $this->_cp02($motor);
        $this->_cp03($motor);
        $this->_cp04($motor);
        $this->_cp05($motor);
        $this->_cp06($motor);
        $this->_cp07($motor);
        $this->_cp08($motor);
        $this->_cp09($motor);
        $this->_cp10($motor);
        $this->out('CP-11 La factura emitida conserva su snapshot: se prueba en el paso 5 (guardado del snapshot).');
        $this->_cp12($motor);
        $this->_cp13($motor);
        $this->_cp14($motor);
        $this->_extras($motor);

        $this->hr();
        $this->out(sprintf('Resultado: %d correctas, %d fallas', $this->_ok, $this->_fallas));
        if ($this->_fallas > 0) {
            $this->_stop(1);
        }
    }

    /** Calcula una factura real con los datos de la base de datos (no guarda nada) */
    public function real() {
        $facturaId = isset($this->args[0]) ? (int) $this->args[0] : 0;
        if ($facturaId <= 0) {
            $this->err('Indique el id de la factura: cake retenciones_prueba real 12345');
            return;
        }
        $Factura = ClassRegistry::init('Factura');
        $res = $Factura->calcularRetenciones($facturaId);
        $this->out(json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DATOS BASE DE LOS CASOS (sección 4.6 del documento)
    // ─────────────────────────────────────────────────────────────────────────

    private function _entrada(array $cambios = array()) {
        $base = array(
            'es_electronica' => true,
            'fecha'          => '2026-08-15',
            'total_factura'  => 1190000,
            'cliente'        => array(
                'practica_retefuente' => 1, 'practica_reteiva' => 1, 'practica_reteica' => 1,
                'tipo_persona' => 'JURIDICA', 'ciudad_id' => self::CALI, 'ciudad_nombre' => 'Cali',
            ),
            'empresa'        => array('declarante_renta' => true, 'responsabilidades' => array()),
            'lineas'         => array(
                array('tipo' => 'PRODUCTO', 'subtotal' => 600000, 'iva' => 114000),
                array('tipo' => 'SERVICIO', 'subtotal' => 400000, 'iva' => 76000),
            ),
            'uvt'            => self::UVT_2026,
            'filas'          => $this->_filas(),
        );
        return array_replace_recursive($base, $cambios);
    }

    /** Las 8 reglas sembradas, con ReteICA Cali 5 / 10 por mil (como el ejemplo 4.6) */
    private function _filas() {
        $f = function ($id, $nombre, $tributo, $aplica, $base, $condicion, $tipo, $valor, $uvt, $desde, $ciudad = null, $hasta = null) {
            return array('id' => $id, 'nombre' => $nombre, 'tributo' => $tributo, 'aplica_a' => $aplica,
                'base_calculo' => $base, 'ciudad_id' => $ciudad, 'condicion_declarante' => $condicion,
                'tipo_valor' => $tipo, 'valor' => $valor, 'base_minima_uvt' => $uvt, 'vigente_desde' => $desde,
                'vigente_hasta' => $hasta, 'activo' => 1, 'cuenta_contable' => '1355');
        };
        return array(
            $f(1, 'ReteFuente productos - declarante',    '06', 'PRODUCTO', 'SUBTOTAL', 'DECLARANTE',    'PORCENTAJE', '2.5000', 10, '2026-07-01'),
            $f(2, 'ReteFuente productos - no declarante', '06', 'PRODUCTO', 'SUBTOTAL', 'NO_DECLARANTE', 'PORCENTAJE', '3.5000', 10, '2026-07-01'),
            $f(3, 'ReteFuente servicios - declarante',    '06', 'SERVICIO', 'SUBTOTAL', 'DECLARANTE',    'PORCENTAJE', '4.0000',  2, '2026-07-01'),
            $f(4, 'ReteFuente servicios - no declarante', '06', 'SERVICIO', 'SUBTOTAL', 'NO_DECLARANTE', 'PORCENTAJE', '6.0000',  2, '2026-07-01'),
            $f(5, 'ReteIVA productos',                    '05', 'PRODUCTO', 'IVA',      'TODOS',         'PORCENTAJE', '15.0000', 10, '2026-07-01'),
            $f(6, 'ReteIVA servicios',                    '05', 'SERVICIO', 'IVA',      'TODOS',         'PORCENTAJE', '15.0000',  2, '2026-07-01'),
            $f(7, 'ReteICA Cali productos',               '07', 'PRODUCTO', 'SUBTOTAL', 'TODOS',         'POR_MIL',    '5.0000',   0, '2026-01-01', self::CALI),
            $f(8, 'ReteICA Cali servicios',               '07', 'SERVICIO', 'SUBTOTAL', 'TODOS',         'POR_MIL',    '10.0000',  0, '2026-01-01', self::CALI),
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CASOS DEL DOCUMENTO (sección 9.1)
    // ─────────────────────────────────────────────────────────────────────────

    private function _cp01($m) {
        $r = $m->calcular($this->_entrada());
        $this->_igual('CP-01 Total retenciones', 66500, $r['total_retenciones']);
        $this->_igual('CP-01 Neto a pagar', 1123500, $r['neto_a_pagar']);
        $this->_igual('CP-01 ReteFuente productos', 15000, $this->_valor($r, 1));
        $this->_igual('CP-01 ReteFuente servicios', 16000, $this->_valor($r, 3));
        $this->_igual('CP-01 ReteIVA productos', 17100, $this->_valor($r, 5));
        $this->_igual('CP-01 ReteIVA servicios', 11400, $this->_valor($r, 6));
        $this->_igual('CP-01 ReteICA productos', 3000, $this->_valor($r, 7));
        $this->_igual('CP-01 ReteICA servicios', 4000, $this->_valor($r, 8));
        $this->_igual('CP-01 Sin avisos', 0, count($r['avisos']));
    }

    private function _cp02($m) {
        $e = $this->_entrada();
        $e['lineas'][0] = array('tipo' => 'PRODUCTO', 'subtotal' => 500000, 'iva' => 95000);
        $r = $m->calcular($e);
        $this->_igual('CP-02 Sin ReteFuente productos', null, $this->_valor($r, 1));
        $this->_igual('CP-02 Sin ReteIVA productos', null, $this->_valor($r, 5));
        $this->_igual('CP-02 ReteFuente servicios igual', 16000, $this->_valor($r, 3));
        $this->_igual('CP-02 ReteIVA servicios igual', 11400, $this->_valor($r, 6));
    }

    private function _cp03($m) {
        $e = $this->_entrada();
        $e['lineas'][0] = array('tipo' => 'PRODUCTO', 'subtotal' => 523740, 'iva' => 99510.60);
        $r = $m->calcular($e);
        $this->_igual('CP-03 Producto exacto a 10 UVT: aplica ReteFuente (>=)', 13093.50, $this->_valor($r, 1));
        $this->_igual('CP-03 ReteIVA productos con 2 decimales', 14926.59, $this->_valor($r, 5));
    }

    private function _cp04($m) {
        $e = $this->_entrada();
        $e['lineas'][1] = array('tipo' => 'SERVICIO', 'subtotal' => 100000, 'iva' => 19000);
        $r = $m->calcular($e);
        $this->_igual('CP-04 Sin ReteFuente servicios', null, $this->_valor($r, 3));
        $this->_igual('CP-04 Sin ReteIVA servicios', null, $this->_valor($r, 6));
    }

    private function _cp05($m) {
        $e = $this->_entrada(array('cliente' => array('practica_retefuente' => 0, 'practica_reteiva' => 0, 'practica_reteica' => 0)));
        $r = $m->calcular($e);
        $this->_igual('CP-05 Sin retenciones', 0, count($r['retenciones']));
        $this->_igual('CP-05 Neto = total', 1190000, $r['neto_a_pagar']);
    }

    private function _cp06($m) {
        $r = $m->calcular($this->_entrada(array('empresa' => array('declarante_renta' => false))));
        $this->_igual('CP-06 ReteFuente productos no declarante (3,5%)', 21000, $this->_valor($r, 2));
        $this->_igual('CP-06 ReteFuente servicios no declarante (6%)', 24000, $this->_valor($r, 4));
        $this->_igual('CP-06 No usa la de declarante', null, $this->_valor($r, 1));
    }

    private function _cp07($m) {
        $r = $m->calcular($this->_entrada(array('empresa' => array('responsabilidades' => array('O-15')))));
        $this->_igual('CP-07 O-15: sin ReteFuente', 0, $this->_contarTributo($r, '06'));
        $this->_igual('CP-07 O-15: ReteIVA normal', 2, $this->_contarTributo($r, '05'));
        $this->_igual('CP-07 O-15: ReteICA normal', 2, $this->_contarTributo($r, '07'));
    }

    private function _cp08($m) {
        $r = $m->calcular($this->_entrada(array('empresa' => array('responsabilidades' => array('R-99-PN')))));
        $this->_igual('CP-08 R-99-PN: sin ReteIVA', 0, $this->_contarTributo($r, '05'));
        $this->_igual('CP-08 R-99-PN: ReteFuente normal', 2, $this->_contarTributo($r, '06'));
    }

    private function _cp09($m) {
        $r = $m->calcular($this->_entrada(array('cliente' => array('ciudad_id' => 999999, 'ciudad_nombre' => 'Palmira'))));
        $this->_igual('CP-09 Sin ReteICA', 0, $this->_contarTributo($r, '07'));
        $this->_igual('CP-09 Aviso de ciudad', true, in_array('No hay tarifa de ReteICA configurada para Palmira.', $r['avisos'], true));
        $this->_igual('CP-09 Las demás se calculan (no bloquea)', 4, count($r['retenciones']));
    }

    private function _cp10($m) {
        $r = $m->calcular($this->_entrada(array('uvt' => null, 'fecha' => '2027-02-10')));
        $this->_igual('CP-10 Sin retenciones', 0, count($r['retenciones']));
        $this->_igual('CP-10 Aviso de UVT', true, in_array('Falta configurar el valor de la UVT para el año 2027.', $r['avisos'], true));
    }

    private function _cp12($m) {
        $e = $this->_entrada(array('fecha' => '2026-06-15'));
        // Filas históricas de ReteFuente, vigentes hasta el 30/06/2026 (27 UVT / 4 UVT)
        $e['filas'][] = array('id' => 20, 'nombre' => 'ReteFuente productos - declarante (hasta jun)', 'tributo' => '06', 'aplica_a' => 'PRODUCTO',
            'base_calculo' => 'SUBTOTAL', 'condicion_declarante' => 'DECLARANTE', 'tipo_valor' => 'PORCENTAJE', 'valor' => '2.5000',
            'base_minima_uvt' => 27, 'vigente_desde' => '2026-01-01', 'vigente_hasta' => '2026-06-30', 'activo' => 1);
        $e['filas'][] = array('id' => 21, 'nombre' => 'ReteFuente servicios - declarante (hasta jun)', 'tributo' => '06', 'aplica_a' => 'SERVICIO',
            'base_calculo' => 'SUBTOTAL', 'condicion_declarante' => 'DECLARANTE', 'tipo_valor' => 'PORCENTAJE', 'valor' => '4.0000',
            'base_minima_uvt' => 4, 'vigente_desde' => '2026-01-01', 'vigente_hasta' => '2026-06-30', 'activo' => 1);
        $r = $m->calcular($e);
        $this->_igual('CP-12 Productos: 600.000 < 27 UVT, no aplica', null, $this->_valor($r, 20));
        $this->_igual('CP-12 Servicios: 400.000 >= 4 UVT, aplica la histórica', 16000, $this->_valor($r, 21));
        $this->_igual('CP-12 No usa las filas desde 01/07', null, $this->_valor($r, 3));
        $this->_igual('CP-12 ReteIVA (desde 01/07) no vigente el 15/06', 0, $this->_contarTributo($r, '05'));
    }

    private function _cp13($m) {
        // Ajustado a la decisión aprobada: la base mínima se evalúa por grupo
        $e = $this->_entrada();
        $e['lineas'][1] = array('tipo' => 'SERVICIO', 'subtotal' => 100000, 'iva' => 19000);
        $r = $m->calcular($e);
        $this->_igual('CP-13 (por grupo) servicio de 100.000: no aplica ReteFuente servicios', null, $this->_valor($r, 3));
        $this->_igual('CP-13 (por grupo) producto de 600.000: sí aplica', 15000, $this->_valor($r, 1));
    }

    private function _cp14($m) {
        $e = $this->_entrada(array('total_factura' => 476000));
        $e['lineas'] = array(array('tipo' => 'SERVICIO', 'subtotal' => 400000, 'iva' => 76000));
        $r = $m->calcular($e);
        $soloServicio = true;
        foreach ($r['retenciones'] as $ret) {
            if ($ret['grupo'] !== 'SERVICIO') {
                $soloServicio = false;
            }
        }
        $this->_igual('CP-14 Solo filas de SERVICIO', true, $soloServicio);
        $this->_igual('CP-14 Tres retenciones de servicios', 3, count($r['retenciones']));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CASOS ADICIONALES (decisiones aprobadas)
    // ─────────────────────────────────────────────────────────────────────────

    private function _extras($m) {
        $r = $m->calcular($this->_entrada(array('es_electronica' => false)));
        $this->_igual('EX-01 Factura no electrónica: nada', 0, count($r['retenciones']));

        $e = $this->_entrada();
        $e['cliente'] = null;
        $r = $m->calcular($e);
        $this->_igual('EX-02 Sin cliente (venta rápida): nada', 0, count($r['retenciones']));

        $r = $m->calcular($this->_entrada(array('empresa' => array('responsabilidades' => array('O-47')))));
        $this->_igual('EX-03 O-47: sin ReteFuente', 0, $this->_contarTributo($r, '06'));

        $r = $m->calcular($this->_entrada(array('empresa' => array('responsabilidades' => array('O-13')))));
        $this->_igual('EX-04 O-13: sin ReteIVA', 0, $this->_contarTributo($r, '05'));

        $r = $m->calcular($this->_entrada(array('empresa' => array('responsabilidades' => array('O-13')), 'cliente' => array('tipo_persona' => 'ENTIDAD_PUBLICA'))));
        $this->_igual('EX-05 O-13 con cliente entidad pública: sí ReteIVA', 2, $this->_contarTributo($r, '05'));

        $r = $m->calcular($this->_entrada(array('cliente' => array('ciudad_id' => null))));
        $this->_igual('EX-06 ReteICA sin ciudad del cliente: aviso', true,
            in_array('El cliente practica ReteICA pero no tiene ciudad registrada; no se calculó ReteICA.', $r['avisos'], true));

        // Regla específica de PRODUCTO tiene prioridad sobre una de TODOS
        $e = $this->_entrada(array('cliente' => array('practica_retefuente' => 0, 'practica_reteica' => 0)));
        $e['filas'] = array(
            array('id' => 30, 'nombre' => 'ReteIVA todos', 'tributo' => '05', 'aplica_a' => 'TODOS', 'base_calculo' => 'IVA',
                'tipo_valor' => 'PORCENTAJE', 'valor' => '10', 'base_minima_uvt' => 0, 'vigente_desde' => '2026-01-01', 'activo' => 1),
            array('id' => 31, 'nombre' => 'ReteIVA productos', 'tributo' => '05', 'aplica_a' => 'PRODUCTO', 'base_calculo' => 'IVA',
                'tipo_valor' => 'PORCENTAJE', 'valor' => '15', 'base_minima_uvt' => 0, 'vigente_desde' => '2026-01-01', 'activo' => 1),
        );
        $r = $m->calcular($e);
        $this->_igual('EX-07 Productos usa la regla específica (15% de 114.000)', 17100, $this->_valor($r, 31));
        $this->_igual('EX-07 Servicios usa la regla TODOS (10% de 76.000)', 7600, $this->_valor($r, 30, 'SERVICIO'));
        $this->_igual('EX-07 TODOS no se cobra también sobre productos', null, $this->_valor($r, 30, 'PRODUCTO'));
        $this->_igual('EX-07 Total sin doble cobro', 24700, $r['total_retenciones']);

        // Solo regla TODOS: se aplica sobre la suma de los dos grupos
        $e['filas'] = array($e['filas'][0]);
        $r = $m->calcular($e);
        $this->_igual('EX-08 Solo regla TODOS: sobre el IVA total (10% de 190.000)', 19000, $this->_valor($r, 30, 'TODOS'));

        // Dos reglas iguales: gana la de vigencia más reciente y hay aviso
        $e = $this->_entrada(array('cliente' => array('practica_reteiva' => 0, 'practica_reteica' => 0)));
        $e['filas'][] = array('id' => 40, 'nombre' => 'ReteFuente productos - declarante (agosto)', 'tributo' => '06', 'aplica_a' => 'PRODUCTO',
            'base_calculo' => 'SUBTOTAL', 'condicion_declarante' => 'DECLARANTE', 'tipo_valor' => 'PORCENTAJE', 'valor' => '3',
            'base_minima_uvt' => 10, 'vigente_desde' => '2026-08-01', 'activo' => 1);
        $r = $m->calcular($e);
        $this->_igual('EX-09 Duplicada: usa la más reciente (3% de 600.000)', 18000, $this->_valor($r, 40));
        $this->_igual('EX-09 Duplicada: deja aviso', 1, count($r['avisos']));

        // Fila inactiva no se usa
        $e = $this->_entrada();
        $e['filas'][0]['activo'] = 0;
        $r = $m->calcular($e);
        $this->_igual('EX-10 Fila inactiva no se usa', null, $this->_valor($r, 1));

        // Redondeo a 2 decimales
        $e = $this->_entrada(array('cliente' => array('practica_reteiva' => 0, 'practica_reteica' => 0)));
        $e['lineas'][0] = array('tipo' => 'PRODUCTO', 'subtotal' => 523741, 'iva' => 99510.79);
        $r = $m->calcular($e);
        $this->_igual('EX-11 Redondeo a 2 decimales (2,5% de 523.741 = 13.093,525)', 13093.53, $this->_valor($r, 1));

        // Las retenciones no cambian el total de la factura
        $r = $m->calcular($this->_entrada());
        $this->_igual('EX-12 Neto = total - retenciones', 1190000 - 66500, $r['neto_a_pagar']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AUXILIARES
    // ─────────────────────────────────────────────────────────────────────────

    /** Valor de la retención de una regla (y grupo), o null si no se calculó */
    private function _valor($r, $impuestoId, $grupo = null) {
        foreach ($r['retenciones'] as $ret) {
            if ($ret['impuesto_id'] === $impuestoId && ($grupo === null || $ret['grupo'] === $grupo)) {
                return $ret['valor'];
            }
        }
        return null;
    }

    private function _contarTributo($r, $tributo) {
        $n = 0;
        foreach ($r['retenciones'] as $ret) {
            if ($ret['tributo_codigo'] === $tributo) {
                $n++;
            }
        }
        return $n;
    }

    private function _igual($nombre, $esperado, $obtenido) {
        $ok = (is_numeric($esperado) && is_numeric($obtenido))
            ? abs((float) $esperado - (float) $obtenido) < 0.001
            : $esperado === $obtenido;
        if ($ok) {
            $this->_ok++;
            $this->out('  OK    ' . $nombre);
        } else {
            $this->_fallas++;
            $this->out('  FALLA ' . $nombre . ' -> esperado ' . var_export($esperado, true) . ', obtenido ' . var_export($obtenido, true));
        }
    }
}
