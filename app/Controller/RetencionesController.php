<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');

/**
 * RetencionesController
 *
 * CRUD de retenciones por empresa (retefuente, reteica, reteiva, etc.)
 */
class RetencionesController extends AppController {

    public $components = array('Paginator');

    /**
     * Categorías de retención (withholdings_category).
     * Valores fijos; ajústalos según tu operación.
     */
    public static $categorias = array(
        1 => 'Retención en la fuente',
        2 => 'ReteIVA',
        3 => 'ReteICA',
    );

    /**
     * Tipos de tarifa (type_rate).
     */
    public static $tiposTarifa = array(
        1 => 'Porcentaje',
        2 => 'Valor fijo',
    );

    // ─────────────────────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────────────────────

    public function index() {
        $usuariosController = new UsuariosController();
        $usuariosController->registraractividad($this->Auth->user('id'));

        $this->loadModel('Cuenta');

        $empresaId = $this->Auth->user('empresa_id');

        $filtros   = array();
        $filtNombre = '';
        $filtActivo = '';

        if (!empty($this->passedArgs['nombre'])) {
            $filtros['Retencione.nombre LIKE'] = '%' . $this->passedArgs['nombre'] . '%';
            $filtNombre = $this->passedArgs['nombre'];
        }

        if (isset($this->passedArgs['activo']) && $this->passedArgs['activo'] !== '') {
            $filtros['Retencione.activo'] = $this->passedArgs['activo'];
            $filtActivo = $this->passedArgs['activo'];
        }

        $retenciones = $this->Retencione->obtenerRetenciones($empresaId, $filtros);

        $categorias  = self::$categorias;
        $tiposTarifa = self::$tiposTarifa;

        $this->set(compact('retenciones', 'categorias', 'tiposTarifa', 'filtNombre', 'filtActivo'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ADD
    // ─────────────────────────────────────────────────────────────────────────

    public function add() {
        $usuariosController = new UsuariosController();
        $usuariosController->registraractividad($this->Auth->user('id'));

        $this->loadModel('Cuenta');

        $empresaId = $this->Auth->user('empresa_id');

        if ($this->request->is('post')) {
            $data = $this->_prepararDatos($this->request->data['Retencione'], $empresaId);

            if ($this->Retencione->guardarRetencion($data)) {
                $this->Session->setFlash('La retención ha sido creada correctamente.');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash('No se pudo guardar la retención. Intente de nuevo.');
        }

        $this->_setFormVars($empresaId);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────────────────────────────────────

    public function edit($id = null) {
        $usuariosController = new UsuariosController();
        $usuariosController->registraractividad($this->Auth->user('id'));

        $this->loadModel('Cuenta');

        $empresaId = $this->Auth->user('empresa_id');

        $retencion = $this->Retencione->obtenerRetencionPorId($id, $empresaId);
        if (empty($retencion)) {
            $this->Session->setFlash('La retención no existe.');
            return $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $data = $this->_prepararDatos($this->request->data['Retencione'], $empresaId);
            $data['id'] = $id;

            if ($this->Retencione->guardarRetencion($data)) {
                $this->Session->setFlash('La retención ha sido actualizada correctamente.');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash('No se pudo actualizar la retención. Intente de nuevo.');
        } else {
            $this->request->data = $retencion;
        }

        $this->_setFormVars($empresaId);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DELETE (AJAX)
    // ─────────────────────────────────────────────────────────────────────────

    public function delete() {
        $this->autoRender = false;

        $posData   = $this->request->data;
        $id        = $posData['id'];
        $empresaId = $this->Auth->user('empresa_id');

        $resp = $this->Retencione->eliminarRetencion($id, $empresaId);

        echo json_encode(array(
            'resp' => $resp,
            'msg'  => $resp ? 'Retención eliminada.' : 'No se pudo eliminar la retención.',
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SEARCH
    // ─────────────────────────────────────────────────────────────────────────

    public function search() {
        $url = array('action' => 'index');
        foreach ($this->data as $k => $v) {
            $url[$k] = $v;
        }
        $this->redirect($url, null, true);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIVADOS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Normaliza los datos del formulario antes de guardar.
     */
    private function _prepararDatos($posData, $empresaId) {
        return array(
            'id'                    => !empty($posData['id']) ? $posData['id'] : null,
            'nombre'                => trim($posData['nombre']),
            'empresa_id'            => $empresaId,
            'porcentaje'            => floatval(str_replace(',', '', $posData['porcentaje'])),
            'activo'                => !empty($posData['activo']) ? '1' : '0',
            'categoriaretencion_id' => $posData['categoriaretencion_id'],
            'cuentaventa_id'        => $posData['cuentaventa_id'],
            'cuentacompra_id'       => $posData['cuentacompra_id'],
            'tipotarifa_id'         => $posData['tipotarifa_id'],
        );
    }

    /**
     * Variables comunes para los formularios add/edit.
     */
    private function _setFormVars($empresaId) {
        $cuentas     = $this->Cuenta->obtenerCuentasEmpresa($empresaId);
        $categorias  = self::$categorias;
        $tiposTarifa = self::$tiposTarifa;
        $this->set(compact('cuentas', 'categorias', 'tiposTarifa', 'empresaId'));
    }
}
