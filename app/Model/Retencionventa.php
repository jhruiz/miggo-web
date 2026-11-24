<?php
App::uses('AppModel', 'Model');

/**
 * Retencionventa Model
 *
 * Retenciones que un cliente le practica a la empresa en una factura de venta:
 * ReteIVA (05), ReteFuente (06) y ReteICA (07).
 *
 * Usa la misma tabla `impuestos` que los impuestos, pero solo lee y escribe filas
 * de clase RETENCION. Cada fila es una regla completa: tributo, tipo de ítem,
 * ciudad (solo ReteICA), condición de la empresa, tarifa, base mínima en UVT y
 * vigencia.
 *
 * Referencia: "Requerimientos técnicos – Retenciones en ventas (MIGGO)", sección 3.3.
 *
 * @property Taxe          $Taxe
 * @property Ciudadesmiggo $Ciudadesmiggo
 */
class Retencionventa extends AppModel {

    public $useTable = 'impuestos';

    public $displayField = 'nombre';

    const CLASE = 'RETENCION';

    /** Códigos DIAN de los tributos de retención */
    const TRIBUTO_RETEIVA    = '05';
    const TRIBUTO_RETEFUENTE = '06';
    const TRIBUTO_RETEICA    = '07';

    /**
     * Campos que definen el cálculo. En una retención ya usada en facturas no se
     * pueden cambiar: se cierra la fila con vigente_hasta y se crea una nueva
     * vigencia, para que las facturas viejas conserven su cálculo.
     */
    public static $camposFiscales = array(
        'tax_id', 'aplica_a', 'base_calculo', 'tipo_valor', 'ciudadesmiggo_id',
        'condicion_declarante', 'valor', 'base_minima_uvt', 'vigente_desde',
    );

    public $belongsTo = array(
        'Taxe' => array(
            'className'  => 'Taxe',
            'foreignKey' => 'tax_id',
        ),
        'Ciudadesmiggo' => array(
            'className'  => 'Ciudadesmiggo',
            'foreignKey' => 'ciudadesmiggo_id',
        ),
    );

    public $validate = array(
        'nombre' => array(
            'notBlank' => array(
                'rule'     => 'notBlank',
                'required' => 'create',
                'message'  => 'El nombre es obligatorio.',
            ),
            'maxLength' => array(
                'rule'    => array('maxLength', 150),
                'message' => 'El nombre no puede superar 150 caracteres.',
            ),
        ),
        'empresa_id' => array(
            'naturalNumber' => array(
                'rule'     => 'naturalNumber',
                'required' => 'create',
                'message'  => 'Empresa no válida.',
            ),
        ),
        'tax_id' => array(
            'tributo' => array(
                'rule'     => 'validarTributo',
                'required' => 'create',
                'message'  => 'Una retención debe ser ReteFuente, ReteIVA o ReteICA',
            ),
        ),
        'aplica_a' => array(
            'inList' => array(
                'rule'     => array('inList', array('PRODUCTO', 'SERVICIO', 'TODOS')),
                'required' => 'create',
                'message'  => 'Indique si la retención aplica a productos, servicios o todos.',
            ),
        ),
        'tipo_valor' => array(
            'inList' => array(
                'rule'     => array('inList', array('PORCENTAJE', 'POR_MIL')),
                'required' => 'create',
                'message'  => 'Las retenciones se calculan en porcentaje o por mil',
            ),
        ),
        'condicion_declarante' => array(
            'inList' => array(
                'rule'       => array('inList', array('TODOS', 'DECLARANTE', 'NO_DECLARANTE')),
                'allowEmpty' => true,
                'message'    => 'Condición de la empresa no válida.',
            ),
        ),
        'valor' => array(
            'tarifa' => array(
                'rule'     => 'validarTarifa',
                'required' => 'create',
                'message'  => 'La tarifa no es válida: debe ser un número entre 0 y 100 (porcentaje) o entre 0 y 1000 (por mil).',
            ),
        ),
        'base_minima_uvt' => array(
            'numeric' => array(
                'rule'       => 'numeric',
                'allowEmpty' => true,
                'message'    => 'La base mínima debe ser un número.',
            ),
            'noNegativa' => array(
                'rule'       => array('comparison', '>=', 0),
                'allowEmpty' => true,
                'message'    => 'La base mínima no puede ser negativa.',
            ),
        ),
        'vigente_desde' => array(
            'fecha' => array(
                'rule'     => array('date', 'ymd'),
                'required' => 'create',
                'message'  => 'Indique desde cuándo aplica',
            ),
        ),
        'vigente_hasta' => array(
            'fecha' => array(
                'rule'       => array('date', 'ymd'),
                'allowEmpty' => true,
                'message'    => 'La fecha "vigente hasta" no es válida.',
            ),
        ),
        'cuenta_contable' => array(
            'maxLength' => array(
                'rule'       => array('maxLength', 20),
                'allowEmpty' => true,
                'message'    => 'La cuenta contable no puede superar 20 caracteres.',
            ),
        ),
        'activo' => array(
            'boolean' => array(
                'rule'       => 'boolean',
                'allowEmpty' => true,
                'message'    => 'Estado no válido.',
            ),
        ),
    );

    // ─────────────────────────────────────────────────────────────────────────
    // CREACIÓN SIN VALORES POR DEFECTO DEL ESQUEMA
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * CakePHP 2 carga en create() los valores por defecto de las columnas. Si
     * después se guarda con un id, esos valores sobrescriben los reales sin
     * avisar (por ejemplo aplica_a volvería a TODOS). Aquí create() no los carga:
     * en una inserción MySQL aplica sus propios DEFAULT, y beforeValidate
     * completa activo y condicion_declarante.
     */
    public function create($data = array(), $filterKey = false) {
        parent::create(false);
        if (!empty($data) && is_array($data)) {
            $this->set($data);
            if ($filterKey) {
                $this->set($this->primaryKey, false);
            }
        }
        return $this->data;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ALCANCE: este modelo solo ve filas de clase RETENCION
    // ─────────────────────────────────────────────────────────────────────────

    public function beforeFind($query) {
        $condicionClase = array($this->alias . '.clase' => self::CLASE);

        if (empty($query['conditions'])) {
            $query['conditions'] = $condicionClase;
        } else {
            $query['conditions'] = array_merge((array) $query['conditions'], $condicionClase);
        }
        return $query;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // VALIDACIÓN
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Completa, normaliza y valida las reglas que dependen de varios campos
     * (sección 3.3 del documento).
     */
    public function beforeValidate($options = array()) {
        if (!isset($this->data[$this->alias])) {
            return true;
        }
        $d =& $this->data[$this->alias];

        // 1. En edición, completar con los valores actuales para validar la fila entera
        $id = !empty($d['id']) ? $d['id'] : $this->id;
        $actual = array();
        if (!empty($id)) {
            $fila = $this->find('first', array(
                'conditions' => array($this->alias . '.id' => $id),
                'recursive'  => -1,
            ));
            if (empty($fila)) {
                $this->invalidate('nombre', 'La retención no existe.');
                return true;
            }
            $actual = $fila[$this->alias];
            foreach ($actual as $campo => $valor) {
                if (!array_key_exists($campo, $d)) {
                    $d[$campo] = $valor;
                }
            }
        }

        // 2. Normalizar formato
        foreach (array('ciudadesmiggo_id', 'vigente_hasta', 'cuenta_contable') as $campo) {
            if (isset($d[$campo]) && $d[$campo] === '') {
                $d[$campo] = null;
            }
        }
        if (isset($d['valor']) && $d['valor'] !== '' && $d['valor'] !== null) {
            $d['valor'] = str_replace(',', '.', trim($d['valor']));
            if (is_numeric($d['valor'])) {
                $d['valor'] = sprintf('%.4f', (float) $d['valor']);
            }
        }
        if (isset($d['base_minima_uvt']) && is_string($d['base_minima_uvt'])) {
            $d['base_minima_uvt'] = str_replace(',', '.', trim($d['base_minima_uvt']));
            if ($d['base_minima_uvt'] === '') {
                $d['base_minima_uvt'] = 0;
            }
        }
        if (!isset($d['activo']) || $d['activo'] === '') {
            $d['activo'] = 1;
        }
        if (empty($d['condicion_declarante'])) {
            $d['condicion_declarante'] = 'TODOS';
        }

        // 3. Reglas según el tributo
        $codigo = !empty($d['tax_id']) ? $this->Taxe->obtenerCodigoPorId($d['tax_id']) : null;

        if ($codigo === self::TRIBUTO_RETEIVA) {
            // ReteIVA: se calcula sobre el IVA, es nacional y no depende de la condición de la empresa
            $d['base_calculo']         = 'IVA';
            $d['ciudadesmiggo_id']     = null;
            $d['condicion_declarante'] = 'TODOS';
        } elseif ($codigo === self::TRIBUTO_RETEFUENTE) {
            // ReteFuente: sobre el subtotal, nacional
            $d['base_calculo']     = 'SUBTOTAL';
            $d['ciudadesmiggo_id'] = null;
        } elseif ($codigo === self::TRIBUTO_RETEICA) {
            // ReteICA: sobre el subtotal, por ciudad y siempre por mil
            $d['base_calculo']         = 'SUBTOTAL';
            $d['condicion_declarante'] = 'TODOS';
            if (empty($d['ciudadesmiggo_id'])) {
                $this->invalidate('ciudadesmiggo_id', 'Seleccione la ciudad de la ReteICA');
            }
            if (isset($d['tipo_valor']) && $d['tipo_valor'] !== 'POR_MIL') {
                $this->invalidate('tipo_valor', 'La ReteICA se expresa por mil');
            }
        }

        // 4. Una retención activa necesita tarifa mayor que 0
        if ((int) $d['activo'] === 1 && isset($d['valor']) && is_numeric($d['valor']) && (float) $d['valor'] <= 0) {
            $this->invalidate('valor', 'La tarifa debe ser mayor que 0 para guardar la retención como activa.');
        }

        // 5. Vigencia coherente
        if (!empty($d['vigente_desde']) && !empty($d['vigente_hasta']) && $d['vigente_hasta'] < $d['vigente_desde']) {
            $this->invalidate('vigente_hasta', 'La fecha "vigente hasta" no puede ser anterior a "vigente desde".');
        }

        // 6. Sin dos filas activas para la misma combinación con vigencias que se crucen
        if ((int) $d['activo'] === 1 && $codigo !== null && !empty($d['vigente_desde']) && $this->_hayCruceDeVigencia($d, $id)) {
            $this->invalidate('vigente_desde', 'Ya existe una retención vigente para esta combinación');
        }

        // 7. Una retención usada en facturas no cambia sus datos de cálculo
        if (!empty($id) && !empty($actual) && $this->estaEnUso($id)) {
            foreach (self::$camposFiscales as $campo) {
                if (array_key_exists($campo, $d) && !$this->_mismoValor($campo, $actual[$campo], $d[$campo])) {
                    $this->invalidate('valor', 'Esta retención ya se usó en facturas: para cambiar la tarifa, la base mínima o la vigencia cree una nueva vigencia.');
                    break;
                }
            }
        }

        return true;
    }

    /** El tributo debe ser 05, 06 o 07 */
    public function validarTributo($check) {
        $taxId = current($check);
        $codigo = $this->Taxe->obtenerCodigoPorId($taxId);
        return in_array($codigo, array(self::TRIBUTO_RETEIVA, self::TRIBUTO_RETEFUENTE, self::TRIBUTO_RETEICA), true);
    }

    /** Tarifa numérica: 0..100 en porcentaje, 0..1000 por mil */
    public function validarTarifa($check) {
        $valor = current($check);
        if (!is_numeric($valor) || (float) $valor < 0) {
            return false;
        }
        $tipo = isset($this->data[$this->alias]['tipo_valor']) ? $this->data[$this->alias]['tipo_valor'] : 'PORCENTAJE';
        $maximo = ($tipo === 'POR_MIL') ? 1000 : 100;
        return (float) $valor <= $maximo;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PROTECCIONES DE ESCRITURA
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Toda fila escrita por este modelo es de clase RETENCION, y nunca se
     * convierte una fila de clase IMPUESTO en retención.
     */
    public function beforeSave($options = array()) {
        $id = !empty($this->data[$this->alias]['id']) ? $this->data[$this->alias]['id'] : $this->id;
        if (!empty($id) && $this->_claseDeFila($id) !== self::CLASE) {
            return false;
        }
        $this->data[$this->alias]['clase'] = self::CLASE;
        return true;
    }

    /**
     * Solo se borran retenciones que no se han usado en facturas.
     * Las usadas se desactivan o se cierran con vigente_hasta.
     */
    public function beforeDelete($cascade = true) {
        if ($this->_claseDeFila($this->id) !== self::CLASE) {
            return false;
        }
        return !$this->estaEnUso($this->id);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CONSULTAS Y ACCIONES
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Indica si la retención aparece en el snapshot de alguna factura.
     *
     * @param  int $id
     * @return bool
     */
    public function estaEnUso($id) {
        $res = $this->query(
            'SELECT COUNT(*) AS n FROM factura_retenciones WHERE impuesto_id = ?',
            array((int) $id)
        );
        return !empty($res[0][0]['n']) && (int) $res[0][0]['n'] > 0;
    }

    /**
     * Una retención de la empresa, o array vacío si no existe o es de otra empresa.
     *
     * @param  int $id
     * @param  int $empresaId
     * @return array
     */
    public function obtenerRetencionPorId($id, $empresaId) {
        return $this->find('first', array(
            'conditions' => array(
                $this->alias . '.id'         => (int) $id,
                $this->alias . '.empresa_id' => (int) $empresaId,
            ),
            'recursive' => -1,
        ));
    }

    /**
     * Borra una retención si no se ha usado en facturas.
     *
     * @return array ['ok' => bool, 'msg' => string]
     */
    public function eliminarRetencion($id, $empresaId) {
        if (empty($this->obtenerRetencionPorId($id, $empresaId))) {
            return array('ok' => false, 'msg' => 'La retención no existe.');
        }
        if ($this->estaEnUso($id)) {
            return array('ok' => false, 'msg' => 'Esta retención ya se usó; puede desactivarla');
        }
        if ($this->delete($id, false)) {
            return array('ok' => true, 'msg' => 'Retención eliminada.');
        }
        return array('ok' => false, 'msg' => 'No se pudo eliminar la retención. Intente de nuevo.');
    }

    /**
     * Activa o desactiva una retención (permitido aunque ya se haya usado).
     * Al activar se valida la fila completa (tarifa mayor que 0 y sin cruce de
     * vigencias); al desactivar no hace falta. Los errores quedan en validationErrors.
     *
     * @return bool
     */
    public function cambiarEstado($id, $empresaId, $activo) {
        if (empty($this->obtenerRetencionPorId($id, $empresaId))) {
            return false;
        }
        if ($activo) {
            $this->create();
            return (bool) $this->save(array($this->alias => array('id' => (int) $id, 'activo' => 1)));
        }
        $this->id = (int) $id;
        return (bool) $this->saveField('activo', 0);
    }

    /**
     * Reglas de retención activas de una empresa, en el formato del motor
     * (MotorRetenciones). El motor filtra por tributo, vigencia, condición de la
     * empresa y ciudad; aquí solo se cargan.
     *
     * @param  int $empresaId
     * @return array
     */
    public function obtenerReglasParaMotor($empresaId) {
        $filas = $this->find('all', array(
            'conditions' => array(
                $this->alias . '.empresa_id' => (int) $empresaId,
                $this->alias . '.activo'     => 1,
            ),
            'fields' => array(
                $this->alias . '.id', $this->alias . '.nombre', $this->alias . '.aplica_a', $this->alias . '.base_calculo',
                $this->alias . '.ciudadesmiggo_id', $this->alias . '.condicion_declarante', $this->alias . '.tipo_valor',
                $this->alias . '.valor', $this->alias . '.base_minima_uvt', $this->alias . '.cuenta_contable',
                $this->alias . '.vigente_desde', $this->alias . '.vigente_hasta', $this->alias . '.activo',
                'Taxe.code',
            ),
            'contain'   => false,
            'recursive' => 0,
        ));

        $reglas = array();
        foreach ($filas as $f) {
            $r = $f[$this->alias];
            $reglas[] = array(
                'id'                   => $r['id'],
                'nombre'               => $r['nombre'],
                'tributo'              => isset($f['Taxe']['code']) ? (string) $f['Taxe']['code'] : '',
                'aplica_a'             => $r['aplica_a'],
                'base_calculo'         => $r['base_calculo'],
                'ciudad_id'            => $r['ciudadesmiggo_id'],
                'condicion_declarante' => $r['condicion_declarante'],
                'tipo_valor'           => $r['tipo_valor'],
                'valor'                => $r['valor'],
                'base_minima_uvt'      => $r['base_minima_uvt'],
                'cuenta_contable'      => $r['cuenta_contable'],
                'vigente_desde'        => $r['vigente_desde'],
                'vigente_hasta'        => $r['vigente_hasta'],
                'activo'               => $r['activo'],
            );
        }
        return $reglas;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AUXILIARES
    // ─────────────────────────────────────────────────────────────────────────

    /** Clase real de una fila de impuestos, sin el filtro de beforeFind */
    protected function _claseDeFila($id) {
        $fila = $this->find('first', array(
            'conditions' => array($this->alias . '.id' => (int) $id),
            'fields'     => array($this->alias . '.clase'),
            'recursive'  => -1,
            'callbacks'  => false,
        ));
        return !empty($fila) ? $fila[$this->alias]['clase'] : null;
    }

    /**
     * ¿Hay otra fila activa con la misma combinación cuya vigencia se cruce?
     * Combinación: empresa, tributo, aplica_a, ciudad, condición de la empresa.
     */
    protected function _hayCruceDeVigencia($d, $id) {
        $desde = $d['vigente_desde'];
        $hasta = !empty($d['vigente_hasta']) ? $d['vigente_hasta'] : '9999-12-31';

        $conditions = array(
            $this->alias . '.empresa_id'           => $d['empresa_id'],
            $this->alias . '.tax_id'               => $d['tax_id'],
            $this->alias . '.aplica_a'             => $d['aplica_a'],
            $this->alias . '.ciudadesmiggo_id'     => !empty($d['ciudadesmiggo_id']) ? $d['ciudadesmiggo_id'] : null,
            $this->alias . '.condicion_declarante' => $d['condicion_declarante'],
            $this->alias . '.activo'               => 1,
            $this->alias . '.vigente_desde <='     => $hasta,
            'OR' => array(
                $this->alias . '.vigente_hasta'    => null,
                $this->alias . '.vigente_hasta >=' => $desde,
            ),
        );
        if (!empty($id)) {
            $conditions[$this->alias . '.id !='] = $id;
        }

        return $this->find('count', array('conditions' => $conditions, 'recursive' => -1)) > 0;
    }

    /** Compara un campo fiscal tolerando diferencias de formato (2.5 vs "2.5000") */
    protected function _mismoValor($campo, $anterior, $nuevo) {
        if (in_array($campo, array('valor', 'base_minima_uvt'), true)) {
            return abs((float) $anterior - (float) $nuevo) < 0.00001;
        }
        if (in_array($campo, array('tax_id', 'ciudadesmiggo_id'), true)) {
            return (string) (int) $anterior === (string) (int) $nuevo;
        }
        return (string) $anterior === (string) $nuevo;
    }
}
