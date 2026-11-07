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
class Centroscosto extends AppModel {

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
    public function obtenerCentrosCosto($filtros = array()) {
        $conditions = array(
            'Centroscosto.deleted'    => '0',
        );

        if (!empty($filtros)) {
            $conditions = array_merge($conditions, $filtros);
        }

        return $this->find('all', array(
            'conditions' => $conditions,
            'order'      => 'Centroscosto.nombre ASC',
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
                'Centroscosto.id'         => $id,
                'Centroscosto.empresa_id' => $empresaId,
                'Centroscosto.deleted'    => '0',
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
                'Centroscosto.empresa_id' => $empresaId,
                'Centroscosto.deleted'    => '0',
            ),
            'order'     => 'Centroscosto.nombre ASC',
            'recursive' => -1,
        ));
    }

    /**
     * Listado 'list' de TODOS los centros de costo (de todas las empresas).
     * Usado para empresas tipo Multifactura (MF), que ven centros de cualquier empresa.
     *
     * @return array
     */
    public function obtenerListaTodosCentrosCosto() {
        return $this->find('list', array(
            'conditions' => array(
                'Centroscosto.deleted' => '0',
            ),
            'order'     => 'Centroscosto.nombre ASC',
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
                'conditions' => array('CCU.centrocosto_id = Centroscosto.id'),
            ),
        );

        return $this->find('all', array(
            'joins'      => $joins,
            'fields'     => array('Centroscosto.*'),
            'conditions' => array(
                'CCU.usuario_id'      => $usuarioId,
                'Centroscosto.deleted' => '0',
            ),
            'order'     => 'Centroscosto.nombre ASC',
            'recursive' => -1,
        ));
    }

    /**
     * Valida que un centro de costo esté asignado a un usuario y, de estarlo,
     * devuelve su empresa_id. Previene que un usuario fije como empresa activa
     * una sede que no le corresponde.
     *
     * @param  int $centroId
     * @param  int $usuarioId
     * @return int|false  empresa_id del centro si es válido, false si no
     */
    public function obtenerEmpresaCentroUsuario($centroId, $usuarioId) {
        $joins = array(
            array(
                'table'      => 'centroscostos_usuarios',
                'alias'      => 'CCU',
                'type'       => 'INNER',
                'conditions' => array('CCU.centrocosto_id = Centroscosto.id'),
            ),
        );

        $centro = $this->find('first', array(
            'joins'      => $joins,
            'fields'     => array('Centroscosto.id', 'Centroscosto.empresa_id'),
            'conditions' => array(
                'Centroscosto.id'      => (int) $centroId,
                'CCU.usuario_id'       => (int) $usuarioId,
                'Centroscosto.deleted' => '0',
            ),
            'recursive' => -1,
        ));

        return !empty($centro) ? (int) $centro['Centroscosto']['empresa_id'] : false;
    }

    /**
     * Devuelve solo los IDs de los centros de costo asignados a un usuario.
     * Útil para pre-seleccionar opciones en el modal de asignación.
     *
     * @param  int $usuarioId
     * @return array  Lista de ids (enteros)
     */
    public function obtenerIdsCentrosPorUsuario($usuarioId) {
        $joins = array(
            array(
                'table'      => 'centroscostos_usuarios',
                'alias'      => 'CCU',
                'type'       => 'INNER',
                'conditions' => array('CCU.centrocosto_id = Centroscosto.id'),
            ),
        );

        $rows = $this->find('all', array(
            'joins'      => $joins,
            'fields'     => array('Centroscosto.id'),
            'conditions' => array(
                'CCU.usuario_id'       => $usuarioId,
                'Centroscosto.deleted' => '0',
            ),
            'recursive' => -1,
        ));

        $ids = array();
        foreach ($rows as $row) {
            $ids[] = (int) $row['Centroscosto']['id'];
        }
        return $ids;
    }

    /**
     * Garantiza que un usuario tenga al menos un centro de costo para facturar.
     *
     * Orden de resolución:
     *   1. Si el usuario ya tiene centros asignados → no hace nada, devuelve el primero.
     *   2. Si no tiene, busca si la empresa ya tiene un centro de costo creado.
     *      - Si existe → se lo asigna al usuario.
     *      - Si no existe → crea un centro con el nombre de la empresa (editable=0)
     *        y se lo asigna.
     *
     * Pensado para llamarse al cargar la pantalla de facturación, de modo que
     * toda factura pueda llevar un centro de costo (incluso empresas no-MF).
     *
     * @param  int    $usuarioId
     * @param  int    $empresaId
     * @param  string $nombreEmpresa  Nombre a usar si hay que crear el centro
     * @return int|false  ID de un centro de costo asignado al usuario, o false si falla
     */
    public function garantizarCentroUsuario($usuarioId, $empresaId, $nombreEmpresa) {
        $usuarioId = (int) $usuarioId;
        $empresaId = (int) $empresaId;

        if ($usuarioId <= 0 || $empresaId <= 0) {
            return false;
        }

        // 1. ¿El usuario ya tiene centros? → usar el primero
        $idsUsuario = $this->obtenerIdsCentrosPorUsuario($usuarioId);
        if (!empty($idsUsuario)) {
            return $idsUsuario[0];
        }

        // 2. ¿La empresa ya tiene un centro de costo? (evita duplicados)
        $centroEmpresa = $this->find('first', array(
            'conditions' => array(
                'Centroscosto.empresa_id' => $empresaId,
                'Centroscosto.deleted'    => '0',
            ),
            'order'     => 'Centroscosto.id ASC',
            'recursive' => -1,
        ));

        if (!empty($centroEmpresa)) {
            $centroId = (int) $centroEmpresa['Centroscosto']['id'];
        } else {
            // 3. No existe → crear el centro con el nombre de la empresa
            $this->create();
            $ok = $this->save(array(
                'Centroscosto' => array(
                    'nombre'     => $nombreEmpresa,
                    'empresa_id' => $empresaId,
                    'editable'   => '0', // centro autogenerado, no editable desde el CRUD
                    'deleted'    => '0',
                    'created'    => date('Y-m-d H:i:s'),
                    'modified'   => date('Y-m-d H:i:s'),
                ),
            ));

            if (!$ok) {
                return false;
            }
            $centroId = (int) $this->id;
        }

        // 4. Asignar el centro al usuario en la tabla pivote
        //    (INSERT IGNORE por el UNIQUE centrocosto_id+usuario_id, evita duplicados)
        $this->query(
            'INSERT IGNORE INTO centroscostos_usuarios (centrocosto_id, usuario_id, created) '
            . "VALUES ($centroId, $usuarioId, '" . date('Y-m-d H:i:s') . "')"
        );

        return $centroId;
    }

    /**
     * Reemplaza las asignaciones de centros de costo de un usuario.
     * Borra las relaciones previas y crea las nuevas en la tabla pivote.
     *
     * @param  int   $usuarioId
     * @param  array $centroIds  Lista de ids de centros de costo a asignar
     * @return bool
     */
    public function asignarCentrosAUsuario($usuarioId, $centroIds = array()) {
        $usuarioId = (int) $usuarioId;
        if ($usuarioId <= 0) {
            return false;
        }

        // 1. Limpiar asignaciones previas del usuario
        $this->query('DELETE FROM centroscostos_usuarios WHERE usuario_id = ' . $usuarioId);

        // 2. Insertar las nuevas (si hay)
        if (!empty($centroIds)) {
            $ahora  = date('Y-m-d H:i:s');
            $values = array();
            foreach ($centroIds as $cid) {
                $cid = (int) $cid;
                if ($cid > 0) {
                    $values[] = "($cid, $usuarioId, '$ahora')";
                }
            }

            if (!empty($values)) {
                $sql = 'INSERT INTO centroscostos_usuarios (centrocosto_id, usuario_id, created) VALUES '
                     . implode(', ', $values);
                $this->query($sql);
            }
        }

        return true;
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
        $ok = $this->updateAll(
            array('Centroscosto.deleted' => '1'),
            array(
                'Centroscosto.id'         => $id,
                'Centroscosto.empresa_id' => $empresaId,
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
