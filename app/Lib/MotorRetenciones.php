<?php
/**
 * MotorRetenciones
 *
 * Cálculo de las retenciones que un cliente le practica a la empresa en una
 * factura de venta: ReteIVA (05), ReteFuente (06) y ReteICA (07).
 *
 * Es cálculo puro: no lee ni escribe en la base de datos. Recibe todos los datos
 * ya cargados (ver Factura::calcularRetenciones) y devuelve el resultado. Por eso
 * se puede probar con valores fijos (shell retenciones_prueba).
 *
 * Reglas aprobadas (documento "Requerimientos técnicos – Retenciones en ventas",
 * secciones 2 y 4, con los ajustes acordados):
 *  - Solo facturas electrónicas, con cliente y con al menos una casilla practica_*.
 *  - Exclusiones por las responsabilidades de la empresa (por código, nunca por id).
 *  - Sin UVT del año de la factura no se calcula nada.
 *  - Líneas agrupadas en PRODUCTO y SERVICIO. La base de ReteFuente y ReteICA es el
 *    subtotal del grupo (antes de IVA, después de descuentos, sin INC ni bolsa).
 *    La base de ReteIVA es el IVA del grupo.
 *  - La base mínima (UVT × valor UVT) se compara contra el subtotal del grupo, con >=.
 *  - Una regla de PRODUCTO o SERVICIO tiene prioridad sobre una regla de TODOS.
 *  - Cada retención se redondea a 2 decimales.
 *  - Las retenciones no cambian el total de la factura: son informativas.
 */
class MotorRetenciones {

    const TRIBUTO_RETEIVA    = '05';
    const TRIBUTO_RETEFUENTE = '06';
    const TRIBUTO_RETEICA    = '07';

    const PRODUCTO = 'PRODUCTO';
    const SERVICIO = 'SERVICIO';
    const TODOS    = 'TODOS';

    /** Decimales de cada valor calculado (igual que el resto de la aplicación) */
    const DECIMALES = 2;

    /** Responsabilidades fiscales de la empresa que excluyen un tributo */
    const RESP_GRAN_CONTRIBUYENTE = 'O-13';
    const RESP_AUTORRETENEDOR     = 'O-15';
    const RESP_REGIMEN_SIMPLE     = 'O-47';
    const RESP_NO_RESPONSABLE_IVA = 'R-99-PN';

    /**
     * Calcula las retenciones de una factura.
     *
     * @param array $entrada
     *   - es_electronica (bool)
     *   - fecha          (string 'Y-m-d') fecha de la factura
     *   - total_factura  (float) total con impuestos, para el neto a pagar
     *   - cliente        (array|null) practica_retefuente, practica_reteiva,
     *                    practica_reteica, tipo_persona, ciudad_id, ciudad_nombre
     *   - empresa        (array) declarante_renta (bool), responsabilidades (códigos)
     *   - lineas         (array) cada una: tipo (PRODUCTO|SERVICIO), subtotal, iva
     *   - uvt            (float|null) valor de la UVT del año de la factura
     *   - filas          (array) reglas de retención de la empresa (ver normalizarFila)
     * @return array
     *   - retenciones       lista de retenciones calculadas (datos del snapshot)
     *   - total_retenciones
     *   - neto_a_pagar
     *   - avisos            mensajes para el usuario; no bloquean la factura
     *   - motivo            por qué no se calculó nada (null si se evaluó)
     *   - descartadas       reglas evaluadas que no aplicaron, con el motivo
     *   - grupos            subtotal e IVA por grupo
     */
    public function calcular(array $entrada) {
        $totalFactura = isset($entrada['total_factura']) ? (float) $entrada['total_factura'] : 0.0;
        $resultado = $this->resultadoVacio($totalFactura);

        // 1. ¿Aplica el cálculo a esta factura?
        $motivo = $this->motivoParaNoCalcular($entrada);
        if ($motivo !== null) {
            $resultado['motivo'] = $motivo;
            return $resultado;
        }
        $cliente = $entrada['cliente'];
        $empresa = isset($entrada['empresa']) ? $entrada['empresa'] : array();

        // 2. Tributos que practica el cliente, menos los excluidos por la empresa
        $tributos = $this->tributosDelCliente($cliente);
        $tributos = $this->aplicarExclusionesEmpresa($tributos, $empresa, $cliente);
        if (empty($tributos)) {
            $resultado['motivo'] = 'El perfil tributario de la empresa excluye las retenciones que practica el cliente.';
            return $resultado;
        }

        // 3. Sin UVT del año no se calcula nada
        if (empty($entrada['uvt']) || (float) $entrada['uvt'] <= 0) {
            $anio = substr((string) $entrada['fecha'], 0, 4);
            $resultado['motivo'] = 'Falta configurar el valor de la UVT para el año ' . $anio . '.';
            $resultado['avisos'][] = $resultado['motivo'];
            return $resultado;
        }
        $uvt = (float) $entrada['uvt'];

        // 4. Agrupar las líneas
        $grupos = $this->agruparLineas(isset($entrada['lineas']) ? $entrada['lineas'] : array());
        $resultado['grupos'] = $grupos;

        // 5. Reglas que aplican a esta factura
        $condicion = !empty($empresa['declarante_renta']) ? 'DECLARANTE' : 'NO_DECLARANTE';
        $ciudadId = !empty($cliente['ciudad_id']) ? (int) $cliente['ciudad_id'] : null;
        $filas = array();
        foreach (isset($entrada['filas']) ? $entrada['filas'] : array() as $fila) {
            $filas[] = $this->normalizarFila($fila);
        }
        $candidatas = $this->filtrarCandidatas($filas, $entrada['fecha'], $tributos, $condicion, $ciudadId);

        // 6. Aviso de ReteICA sin tarifa para la ciudad del cliente
        if (in_array(self::TRIBUTO_RETEICA, $tributos, true)) {
            $aviso = $this->avisoReteica($candidatas, $cliente);
            if ($aviso !== null) {
                $resultado['avisos'][] = $aviso;
            }
        }

        // 7. Elegir la regla de cada tributo y grupo, y calcular
        foreach ($tributos as $tributo) {
            $asignaciones = $this->asignarReglas($this->filasDelTributo($candidatas, $tributo), $resultado['avisos']);
            foreach ($asignaciones as $asignacion) {
                $fila = $asignacion['fila'];
                $grupo = $grupos[$asignacion['grupo']];
                $calculo = $this->aplicarRegla($fila, $grupo, $asignacion['grupo'], $uvt);
                if (isset($calculo['descartada'])) {
                    $resultado['descartadas'][] = $calculo['descartada'];
                } else {
                    $resultado['retenciones'][] = $calculo['retencion'];
                }
            }
        }

        // 8. Totales
        $total = 0.0;
        foreach ($resultado['retenciones'] as $r) {
            $total += $r['valor'];
        }
        $resultado['total_retenciones'] = $this->redondear($total);
        $resultado['neto_a_pagar'] = $this->redondear($totalFactura - $resultado['total_retenciones']);

        return $resultado;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PASOS DEL CÁLCULO (cada uno con una sola responsabilidad)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Motivo para no calcular nada, o null si la factura se evalúa.
     */
    public function motivoParaNoCalcular(array $entrada) {
        if (empty($entrada['es_electronica'])) {
            return 'Solo se calculan retenciones en facturas electrónicas.';
        }
        if (empty($entrada['cliente'])) {
            return 'La factura no tiene cliente.';
        }
        if (empty($entrada['fecha']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $entrada['fecha'])) {
            return 'La factura no tiene una fecha válida.';
        }
        if (empty($this->tributosDelCliente($entrada['cliente']))) {
            return 'El cliente no practica retenciones.';
        }
        return null;
    }

    /**
     * Tributos según las casillas del cliente.
     *
     * @return array códigos DIAN
     */
    public function tributosDelCliente(array $cliente) {
        $tributos = array();
        if (!empty($cliente['practica_retefuente'])) {
            $tributos[] = self::TRIBUTO_RETEFUENTE;
        }
        if (!empty($cliente['practica_reteiva'])) {
            $tributos[] = self::TRIBUTO_RETEIVA;
        }
        if (!empty($cliente['practica_reteica'])) {
            $tributos[] = self::TRIBUTO_RETEICA;
        }
        return $tributos;
    }

    /**
     * Quita los tributos que no aplican por las responsabilidades de la empresa
     * (sección 2.4):
     *  - O-15 Autorretenedor o O-47 Régimen Simple: no ReteFuente.
     *  - R-99-PN No responsable de IVA: no ReteIVA.
     *  - O-13 Gran contribuyente: no ReteIVA, salvo que el cliente sea entidad pública.
     */
    public function aplicarExclusionesEmpresa(array $tributos, array $empresa, array $cliente) {
        $resp = isset($empresa['responsabilidades']) ? array_map('strtoupper', array_map('trim', (array) $empresa['responsabilidades'])) : array();
        $excluidos = array();

        if (in_array(self::RESP_AUTORRETENEDOR, $resp, true) || in_array(self::RESP_REGIMEN_SIMPLE, $resp, true)) {
            $excluidos[] = self::TRIBUTO_RETEFUENTE;
        }
        if (in_array(self::RESP_NO_RESPONSABLE_IVA, $resp, true)) {
            $excluidos[] = self::TRIBUTO_RETEIVA;
        }
        $esEntidadPublica = isset($cliente['tipo_persona']) && $cliente['tipo_persona'] === 'ENTIDAD_PUBLICA';
        if (in_array(self::RESP_GRAN_CONTRIBUYENTE, $resp, true) && !$esEntidadPublica) {
            $excluidos[] = self::TRIBUTO_RETEIVA;
        }

        return array_values(array_diff($tributos, $excluidos));
    }

    /**
     * Suma subtotal e IVA por tipo de ítem. TODOS es la suma de los dos.
     * Una línea sin tipo reconocido se trata como PRODUCTO.
     */
    public function agruparLineas(array $lineas) {
        $grupos = array(
            self::PRODUCTO => array('subtotal' => 0.0, 'iva' => 0.0),
            self::SERVICIO => array('subtotal' => 0.0, 'iva' => 0.0),
        );
        foreach ($lineas as $l) {
            $tipo = (isset($l['tipo']) && $l['tipo'] === self::SERVICIO) ? self::SERVICIO : self::PRODUCTO;
            $grupos[$tipo]['subtotal'] += isset($l['subtotal']) ? (float) $l['subtotal'] : 0.0;
            $grupos[$tipo]['iva']      += isset($l['iva']) ? (float) $l['iva'] : 0.0;
        }
        foreach ($grupos as $tipo => $g) {
            $grupos[$tipo]['subtotal'] = $this->redondear($g['subtotal']);
            $grupos[$tipo]['iva']      = $this->redondear($g['iva']);
        }
        $grupos[self::TODOS] = array(
            'subtotal' => $this->redondear($grupos[self::PRODUCTO]['subtotal'] + $grupos[self::SERVICIO]['subtotal']),
            'iva'      => $this->redondear($grupos[self::PRODUCTO]['iva'] + $grupos[self::SERVICIO]['iva']),
        );
        return $grupos;
    }

    /**
     * Lleva una regla al formato que usa el motor (los valores llegan como texto).
     */
    public function normalizarFila(array $f) {
        return array(
            'id'                   => isset($f['id']) ? (int) $f['id'] : 0,
            'nombre'               => isset($f['nombre']) ? (string) $f['nombre'] : '',
            'tributo'              => isset($f['tributo']) ? (string) $f['tributo'] : '',
            'aplica_a'             => isset($f['aplica_a']) ? (string) $f['aplica_a'] : self::TODOS,
            'base_calculo'         => isset($f['base_calculo']) ? (string) $f['base_calculo'] : 'SUBTOTAL',
            'ciudad_id'            => !empty($f['ciudad_id']) ? (int) $f['ciudad_id'] : null,
            'condicion_declarante' => isset($f['condicion_declarante']) ? (string) $f['condicion_declarante'] : 'TODOS',
            'tipo_valor'           => isset($f['tipo_valor']) ? (string) $f['tipo_valor'] : 'PORCENTAJE',
            'valor'                => isset($f['valor']) ? (float) str_replace(',', '.', (string) $f['valor']) : 0.0,
            'base_minima_uvt'      => isset($f['base_minima_uvt']) ? (float) $f['base_minima_uvt'] : 0.0,
            'cuenta_contable'      => isset($f['cuenta_contable']) ? $f['cuenta_contable'] : null,
            'vigente_desde'        => isset($f['vigente_desde']) ? (string) $f['vigente_desde'] : null,
            'vigente_hasta'        => !empty($f['vigente_hasta']) ? (string) $f['vigente_hasta'] : null,
            'activo'               => isset($f['activo']) ? (int) $f['activo'] : 0,
        );
    }

    /**
     * Reglas que aplican a la factura (consulta 4.4 del documento):
     * activas, del tributo habilitado, vigentes en la fecha, de la condición de la
     * empresa, y para ReteICA solo la ciudad del cliente.
     */
    public function filtrarCandidatas(array $filas, $fecha, array $tributos, $condicion, $ciudadId) {
        $candidatas = array();
        foreach ($filas as $f) {
            if ($f['activo'] !== 1 || !in_array($f['tributo'], $tributos, true)) {
                continue;
            }
            if (empty($f['vigente_desde']) || $f['vigente_desde'] > $fecha) {
                continue;
            }
            if ($f['vigente_hasta'] !== null && $f['vigente_hasta'] < $fecha) {
                continue;
            }
            if (!in_array($f['condicion_declarante'], array('TODOS', $condicion), true)) {
                continue;
            }
            if ($f['tributo'] === self::TRIBUTO_RETEICA && ($ciudadId === null || $f['ciudad_id'] !== $ciudadId)) {
                continue;
            }
            $candidatas[] = $f;
        }
        return $candidatas;
    }

    /** Reglas candidatas de un tributo */
    public function filasDelTributo(array $candidatas, $tributo) {
        $filas = array();
        foreach ($candidatas as $f) {
            if ($f['tributo'] === $tributo) {
                $filas[] = $f;
            }
        }
        return $filas;
    }

    /**
     * Decide qué regla se aplica a cada grupo de un mismo tributo.
     *
     *  - Si hay regla de PRODUCTO o de SERVICIO, se aplica a su grupo (es específica).
     *  - La regla de TODOS cubre solo el grupo que no tenga regla específica.
     *  - Si no hay ninguna específica, la regla de TODOS se aplica sobre la suma de
     *    los dos grupos.
     *  - Si hay dos reglas para lo mismo, se usa la de vigencia más reciente y se
     *    deja un aviso.
     *
     * @return array lista de ['grupo' => ..., 'fila' => ...]
     */
    public function asignarReglas(array $filasTributo, array &$avisos) {
        $producto = $this->elegirUna($filasTributo, self::PRODUCTO, $avisos);
        $servicio = $this->elegirUna($filasTributo, self::SERVICIO, $avisos);
        $todos    = $this->elegirUna($filasTributo, self::TODOS, $avisos);

        if ($producto === null && $servicio === null) {
            return $todos === null ? array() : array(array('grupo' => self::TODOS, 'fila' => $todos));
        }

        $asignaciones = array();
        $paraProducto = $producto !== null ? $producto : $todos;
        $paraServicio = $servicio !== null ? $servicio : $todos;
        if ($paraProducto !== null) {
            $asignaciones[] = array('grupo' => self::PRODUCTO, 'fila' => $paraProducto);
        }
        if ($paraServicio !== null) {
            $asignaciones[] = array('grupo' => self::SERVICIO, 'fila' => $paraServicio);
        }
        return $asignaciones;
    }

    /**
     * La regla de un aplica_a; si hay varias, la de vigente_desde más reciente
     * (y a igual fecha, la de id mayor).
     */
    public function elegirUna(array $filas, $aplicaA, array &$avisos) {
        $elegida = null;
        $cantidad = 0;
        foreach ($filas as $f) {
            if ($f['aplica_a'] !== $aplicaA) {
                continue;
            }
            $cantidad++;
            if ($elegida === null
                || $f['vigente_desde'] > $elegida['vigente_desde']
                || ($f['vigente_desde'] === $elegida['vigente_desde'] && $f['id'] > $elegida['id'])) {
                $elegida = $f;
            }
        }
        if ($cantidad > 1) {
            $avisos[] = 'Hay ' . $cantidad . ' retenciones vigentes iguales; se usó "' . $elegida['nombre'] . '". Revise la configuración de retenciones.';
        }
        return $elegida;
    }

    /**
     * Aplica una regla sobre un grupo.
     *
     * @return array ['retencion' => ...] o ['descartada' => ...]
     */
    public function aplicarRegla(array $fila, array $grupo, $nombreGrupo, $uvt) {
        $subtotal = (float) $grupo['subtotal'];
        $tope = $this->redondear($fila['base_minima_uvt'] * $uvt);

        if ($subtotal <= 0) {
            return array('descartada' => $this->descartada($fila, $nombreGrupo, 'No hay ítems de este tipo en la factura.'));
        }
        if ($subtotal < $tope) {
            return array('descartada' => $this->descartada($fila, $nombreGrupo,
                'El subtotal (' . $this->formato($subtotal) . ') no alcanza la base mínima (' . $this->formato($tope) . ').'));
        }

        $base = ($fila['base_calculo'] === 'IVA') ? (float) $grupo['iva'] : $subtotal;
        if ($base <= 0) {
            return array('descartada' => $this->descartada($fila, $nombreGrupo, 'La base es 0 (sin IVA en este grupo).'));
        }

        $divisor = ($fila['tipo_valor'] === 'POR_MIL') ? 1000 : 100;
        $valor = $this->redondear($base * $fila['valor'] / $divisor);
        if ($valor <= 0) {
            return array('descartada' => $this->descartada($fila, $nombreGrupo, 'El valor calculado es 0.'));
        }

        return array('retencion' => array(
            'impuesto_id'      => $fila['id'],
            'tributo_codigo'   => $fila['tributo'],
            'nombre'           => $fila['nombre'],
            'aplica_a'         => $fila['aplica_a'],
            'grupo'            => $nombreGrupo,
            'ciudadesmiggo_id' => $fila['ciudad_id'],
            'subtotal_grupo'   => $subtotal,
            'base'             => $this->redondear($base),
            'tipo_valor'       => $fila['tipo_valor'],
            'tarifa'           => $fila['valor'],
            'base_minima_uvt'  => $fila['base_minima_uvt'],
            'valor_uvt'        => $uvt,
            'valor'            => $valor,
            'cuenta_contable'  => $fila['cuenta_contable'],
        ));
    }

    /**
     * Aviso cuando el cliente practica ReteICA pero no hay tarifa para su ciudad.
     */
    public function avisoReteica(array $candidatas, array $cliente) {
        if (empty($cliente['ciudad_id'])) {
            return 'El cliente practica ReteICA pero no tiene ciudad registrada; no se calculó ReteICA.';
        }
        if (empty($this->filasDelTributo($candidatas, self::TRIBUTO_RETEICA))) {
            $ciudad = !empty($cliente['ciudad_nombre']) ? $cliente['ciudad_nombre'] : 'la ciudad del cliente';
            return 'No hay tarifa de ReteICA configurada para ' . $ciudad . '.';
        }
        return null;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AUXILIARES
    // ─────────────────────────────────────────────────────────────────────────

    public function redondear($valor) {
        return round((float) $valor, self::DECIMALES);
    }

    protected function resultadoVacio($totalFactura) {
        return array(
            'retenciones'       => array(),
            'total_retenciones' => 0.0,
            'neto_a_pagar'      => $this->redondear($totalFactura),
            'avisos'            => array(),
            'motivo'            => null,
            'descartadas'       => array(),
            'grupos'            => array(),
        );
    }

    protected function descartada(array $fila, $grupo, $motivo) {
        return array('impuesto_id' => $fila['id'], 'nombre' => $fila['nombre'], 'grupo' => $grupo, 'motivo' => $motivo);
    }

    protected function formato($valor) {
        return '$' . number_format($valor, 2, ',', '.');
    }
}
