<?php
App::uses('AppModel', 'Model');

/**
 * Retencione Model
 *
 * Retenciones configuradas por empresa (retefuente, reteica, reteiva, etc.)
 *
 * @property Empresa $Empresa
 * @property Cuenta  $Cuentaventa
 * @property Cuenta  $Cuentacompra
 */
class Retencione extends AppModel {

    public $displayField = 'nombre';

    public $validate = array(
        'nombre' => array(
            'notBlank' => array(
                'rule'    => array('notBlank'),
                'message' => 'El nombre es obligatorio.',
            ),
        ),
        'empresa_id' => array(
            'numeric' => array('rule' => array('numeric')),
        ),
        'porcentaje' => array(
            'decimal' => array(
                'rule'    => array('numeric'),
                'message' => 'El porcentaje debe ser numérico.',
            ),
        ),
        'categoriaretencion_id' => array(
            'numeric' => array('rule' => array('numeric')),
        ),
        'cuentaventa_id' => array(
            'numeric' => array('rule' => array('numeric')),
        ),
        'cuentacompra_id' => array(
            'numeric' => array('rule' => array('numeric')),
        ),
        'tipotarifa_id' => array(
            'numeric' => array('rule' => array('numeric')),
        ),
    );

    public $belongsTo = array(
        'Empresa' => array(
            'className'  => 'Empresa',
            'foreignKey' => 'empresa_id',
        ),
        'Cuentaventa' => array(
            'className'  => 'Cuenta',
            'foreignKey' => 'cuentaventa_id',
        ),
        'Cuentacompra' => array(
            'className'  => 'Cuenta',
            'foreignKey' => 'cuentacompra_id',
        ),
    );

    /**
     * Comportamiento de cálculo derivado de la categoría de retención.
     * La categoría determina sin ambigüedad sobre qué base se calcula
     * y en qué unidad está expresada la tarifa.
     *
     *   base   → 'subtotal' (valor antes de IVA) | 'iva' (valor del IVA)
     *   unidad → 'porcentaje' (%) | 'pormil' (x1000)
     *
     * Para agregar una nueva categoría, basta con añadir una línea aquí.
     */
    public static $comportamiento = array(
        1 => array('base' => 'subtotal', 'unidad' => 'porcentaje'), // Retención en la fuente
        2 => array('base' => 'iva',      'unidad' => 'porcentaje'), // ReteIVA
        3 => array('base' => 'subtotal', 'unidad' => 'pormil'),     // ReteICA
    );

    /**
     * Devuelve el comportamiento (base y unidad) de una categoría.
     * Si la categoría no está mapeada, cae a subtotal + porcentaje.
     *
     * @param  int $categoriaId
     * @return array  ['base' => ..., 'unidad' => ...]
     */
    public static function obtenerComportamiento($categoriaId) {
        return isset(self::$comportamiento[$categoriaId])
            ? self::$comportamiento[$categoriaId]
            : array('base' => 'subtotal', 'unidad' => 'porcentaje');
    }

    // ─────────────────────────────────────────────
    // LECTURA
    // ─────────────────────────────────────────────

    /**
     * Listado de retenciones de una empresa (no eliminadas).
     * Trae la descripción de las cuentas de venta y compra.
     *
     * @param  int   $empresaId
     * @param  array $filtros  Condiciones adicionales opcionales
     * @return array
     */
    public function obtenerRetenciones($empresaId, $filtros = array()) {
        $joins = array();

        $joins[] = array(
            'table'      => 'cuentas',
            'alias'      => 'CV',
            'type'       => 'LEFT',
            'conditions' => array('CV.id = Retencione.cuentaventa_id'),
        );

        $joins[] = array(
            'table'      => 'cuentas',
            'alias'      => 'CC',
            'type'       => 'LEFT',
            'conditions' => array('CC.id = Retencione.cuentacompra_id'),
        );

        $conditions = array(
            'Retencione.empresa_id' => $empresaId,
            'Retencione.deleted'    => '0',
        );

        if (!empty($filtros)) {
            $conditions = array_merge($conditions, $filtros);
        }

        return $this->find('all', array(
            'joins'      => $joins,
            'fields'     => array(
                'Retencione.*',
                'CV.descripcion',
                'CC.descripcion',
            ),
            'conditions' => $conditions,
            'order'      => 'Retencione.nombre ASC',
            'recursive'  => -1,
        ));
    }

    /**
     * Obtiene una retención por id (validando empresa y no eliminada).
     *
     * @param  int $id
     * @param  int $empresaId
     * @return array
     */
    public function obtenerRetencionPorId($id, $empresaId) {
        return $this->find('first', array(
            'conditions' => array(
                'Retencione.id'         => $id,
                'Retencione.empresa_id' => $empresaId,
                'Retencione.deleted'    => '0',
            ),
            'recursive' => -1,
        ));
    }

    /**
     * Listado tipo 'list' de retenciones activas de la empresa.
     *
     * @param  int $empresaId
     * @return array
     */
    public function obtenerListaRetenciones($empresaId) {
        return $this->find('list', array(
            'conditions' => array(
                'Retencione.empresa_id' => $empresaId,
                'Retencione.activo'     => '1',
                'Retencione.deleted'    => '0',
            ),
            'order'     => 'Retencione.nombre ASC',
            'recursive' => -1,
        ));
    }

    // ─────────────────────────────────────────────
    // ESCRITURA
    // ─────────────────────────────────────────────

    /**
     * Guarda (crea o actualiza) una retención.
     *
     * @param  array $data  Datos ya normalizados
     * @return int|false    ID guardado o false
     */
    public function guardarRetencion($data) {
        $retencion = new Retencione();

        if (empty($data['id'])) {
            $retencion->create();
            $data['created'] = date('Y-m-d H:i:s');
        }
        $data['modified'] = date('Y-m-d H:i:s');

        if ($retencion->save($data)) {
            return empty($data['id']) ? $retencion->id : $data['id'];
        }
        return false;
    }

    /**
     * Marca una retención como eliminada (soft delete).
     *
     * @param  int $id
     * @param  int $empresaId
     * @return bool
     */
    public function eliminarRetencion($id, $empresaId) {
        $retencion = new Retencione();
        return (bool) $retencion->updateAll(
            array('Retencione.deleted' => '1'),
            array(
                'Retencione.id'         => $id,
                'Retencione.empresa_id' => $empresaId,
            )
        );
    }

    // ─────────────────────────────────────────────
    // CÁLCULO (listo para conectar en facturas/compras)
    // ─────────────────────────────────────────────

    /**
     * Calcula el valor a retener de una retención sobre una operación.
     *
     * La base (subtotal o IVA) y la unidad (% o x1000) se derivan de la
     * categoría de la retención mediante el mapa $comportamiento, por lo
     * que no dependen de columnas adicionales en la tabla.
     *
     * Ejemplos:
     *   Retefuente 2.5% sobre subtotal 1.000.000 → 25.000
     *   ReteIVA 15% sobre IVA 190.000            → 28.500
     *   ReteICA 6.9x1000 sobre subtotal 1.000.000 → 6.900
     *
     * @param  array $retencion  Registro de la retención (debe incluir la clave 'Retencione')
     * @param  float $subtotal   Valor de la operación antes de IVA
     * @param  float $valorIva   Valor del IVA de la operación
     * @param  float $uvt        UVT vigente (para validar la base mínima). 0 = no validar mínimo
     * @return float             Valor a retener; 0 si no aplica por no superar la base mínima
     */
    public function calcularRetencion($retencion, $subtotal, $valorIva, $uvt = 0) {
        $r = $retencion['Retencione'];

        $comportamiento = self::obtenerComportamiento($r['categoriaretencion_id']);

        // 1. Base sobre la que se calcula
        $base = ($comportamiento['base'] === 'iva')
            ? floatval($valorIva)
            : floatval($subtotal);

        // 2. Validar base mínima en UVT (si está configurada y hay UVT)
        $baseMinimaUvt = isset($r['base_minima_uvt']) ? floatval($r['base_minima_uvt']) : 0;
        if ($baseMinimaUvt > 0 && $uvt > 0) {
            $minimoPesos = $baseMinimaUvt * floatval($uvt);
            if ($base < $minimoPesos) {
                return 0; // la operación no supera el tope mínimo: no se retiene
            }
        }

        // 3. Aplicar la tarifa según su unidad
        $tarifa = floatval($r['porcentaje']);
        if ($comportamiento['unidad'] === 'pormil') {
            $valor = $base * ($tarifa / 1000);
        } else {
            $valor = $base * ($tarifa / 100);
        }

        return round($valor, 2);
    }
}
