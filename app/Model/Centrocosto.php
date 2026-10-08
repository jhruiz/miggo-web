<?php
App::uses('AppModel', 'Model');

/**
 * Centrocosto Model
 *
 * Centros de costo por empresa. Un usuario puede pertenecer a varios
 * centros de costo (incluso de distintas empresas) a través de la
 * tabla pivote centroscostos_usuarios.
 *
 * @property Empresa $Empresa
 * @property Usuario $Usuario
 */
class Centrocosto extends AppModel {

    public $useTable = 'centroscostos';

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
    );

    public $belongsTo = array(
        'Empresa' => array(
            'className'  => 'Empresa',
            'foreignKey' => 'empresa_id',
        ),
    );

    /**
     * Relación muchos-a-muchos con usuarios.
     * joinTable: centroscostos_usuarios
     */
    public $hasAndBelongsToMany = array(
        'Usuario' => array(
            'className'             => 'Usuario',
            'joinTable'             => 'centroscostos_usuarios',
            'foreignKey'            => 'centrocosto_id',
            'associationForeignKey' => 'usuario_id',
            'unique'                => 'keepExisting',
        ),
    );

    // ─────────────────────────────────────────────
    // LECTURA
    // ─────────────────────────────────────────────

    /**
     * Listado de centros de costo de una empresa (no eliminados).
     *
     * @param  int   $empresaId
     * @param  array $filtros
     * @return array
     */
    public function obtenerCentrosCosto($empresaId, $filtros = array()) {
        $conditions = array(
            'Centrocosto.empresa_id' => $empresaId,
            'Centrocosto.deleted'    => '0',
        );

        if (!empty($filtros)) {
            $conditions = array_merge($conditions, $filtros);
        }

        return $this->find('all', array(
            'conditions' => $conditions,
            'order'      => 'Centrocosto.nombre ASC',
            'recursive'  => 1, // trae Empresa + Usuario (HABTM)
        ));
    }

    /**
     * Obtiene un centro de costo por id, con sus usuarios asignados.
     *
     * @param  int $id
     * @param  int $empresaId
     * @return array
     */
    public function obtenerCentroCostoPorId($id, $empresaId) {
        return $this->find('first', array(
            'conditions' => array(
                'Centrocosto.id'         => $id,
                'Centrocosto.empresa_id' => $empresaId,
                'Centrocosto.deleted'    => '0',
            ),
            'recursive' => 1, // trae Empresa + Usuario (HABTM)
        ));
    }

    /**
     * Listado 'list' de centros de costo de la empresa.
     *
     * @param  int $empresaId
     * @return array
     */
    public function obtenerListaCentrosCosto($empresaId) {
        return $this->find('list', array(
            'conditions' => array(
                'Centrocosto.empresa_id' => $empresaId,
                'Centrocosto.deleted'    => '0',
            ),
            'order'     => 'Centrocosto.nombre ASC',
            'recursive' => -1,
        ));
    }

    /**
     * Centros de costo a los que pertenece un usuario (de cualquier empresa).
     *
     * @param  int $usuarioId
     * @return array
     */
    public function obtenerCentrosPorUsuario($usuarioId) {
        $joins = array(
            array(
                'table'      => 'centroscostos_usuarios',
                'alias'      => 'CCU',
                'type'       => 'INNER',
                'conditions' => array('CCU.centrocosto_id = Centrocosto.id'),
            ),
        );

        return $this->find('all', array(
            'joins'      => $joins,
            'fields'     => array('Centrocosto.*'),
            'conditions' => array(
                'CCU.usuario_id'      => $usuarioId,
                'Centrocosto.deleted' => '0',
            ),
            'order'     => 'Centrocosto.nombre ASC',
            'recursive' => -1,
        ));
    }

    // ─────────────────────────────────────────────
    // ESCRITURA
    // ─────────────────────────────────────────────

    /**
     * Guarda (crea o actualiza) un centro de costo junto con sus usuarios.
     * Usa saveAll para persistir también la relación HABTM.
     *
     * @param  array $data       Datos del Centrocosto
     * @param  array $usuarioIds Array de ids de usuarios a asociar
     * @return bool
     */
    public function guardarCentroCosto($data, $usuarioIds = array()) {
        $save = array(
            'Centrocosto' => $data,
            'Usuario'     => array('Usuario' => $usuarioIds),
        );

        if (empty($data['id'])) {
            $this->create();
            $save['Centrocosto']['created'] = date('Y-m-d H:i:s');
        }
        $save['Centrocosto']['modified'] = date('Y-m-d H:i:s');

        return (bool) $this->saveAll($save);
    }

    /**
     * Marca un centro de costo como eliminado (soft delete) y
     * limpia sus asociaciones de la tabla pivote.
     *
     * @param  int $id
     * @param  int $empresaId
     * @return bool
     */
    public function eliminarCentroCosto($id, $empresaId) {
        $centro = new Centrocosto();

        $ok = $centro->updateAll(
            array('Centrocosto.deleted' => '1'),
            array(
                'Centrocosto.id'         => $id,
                'Centrocosto.empresa_id' => $empresaId,
            )
        );

        if ($ok) {
            // Quitar las asignaciones de usuarios de la tabla pivote.
            // intval() asegura que el id sea numérico (previene inyección).
            $this->query('DELETE FROM centroscostos_usuarios WHERE centrocosto_id = ' . intval($id));
        }

        return (bool) $ok;
    }
}
