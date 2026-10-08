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
}
