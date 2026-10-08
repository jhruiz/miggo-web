<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');

/**
 * CentroscostosController
 *
 * CRUD de centros de costo por empresa, con asignación de usuarios
 * mediante la relación muchos-a-muchos (centroscostos_usuarios).
 */
class CentroscostosController extends AppController {

    public $components = array('Paginator');

    // ─────────────────────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────────────────────

    public function index() {
        $usuariosController = new UsuariosController();
        $usuariosController->registraractividad($this->Auth->user('id'));

        $empresaId = $this->Auth->user('empresa_id');

        $filtros    = array();
        $filtNombre = '';

        if (!empty($this->passedArgs['nombre'])) {
            $filtros['Centrocosto.nombre LIKE'] = '%' . $this->passedArgs['nombre'] . '%';
            $filtNombre = $this->passedArgs['nombre'];
        }

        $centros = $this->Centrocosto->obtenerCentrosCosto($empresaId, $filtros);

        $this->set(compact('centros', 'filtNombre'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ADD
    // ─────────────────────────────────────────────────────────────────────────

    public function add() {
        $usuariosController = new UsuariosController();
        $usuariosController->registraractividad($this->Auth->user('id'));

        $this->loadModel('Usuario');

        $empresaId = $this->Auth->user('empresa_id');

        if ($this->request->is('post')) {
            $data = $this->_prepararDatos($this->request->data['Centrocosto'], $empresaId);
            $usuarioIds = !empty($this->request->data['Centrocosto']['usuarios'])
                ? $this->request->data['Centrocosto']['usuarios']
                : array();

            if ($this->Centrocosto->guardarCentroCosto($data, $usuarioIds)) {
                $this->Session->setFlash('El centro de costo ha sido creado correctamente.');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash('No se pudo guardar el centro de costo. Intente de nuevo.');
        }

        $this->_setFormVars($empresaId);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────────────────────────────────────

    public function edit($id = null) {
        $usuariosController = new UsuariosController();
        $usuariosController->registraractividad($this->Auth->user('id'));

        $this->loadModel('Usuario');

        $empresaId = $this->Auth->user('empresa_id');

        $centro = $this->Centrocosto->obtenerCentroCostoPorId($id, $empresaId);
        if (empty($centro)) {
            $this->Session->setFlash('El centro de costo no existe.');
            return $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $data = $this->_prepararDatos($this->request->data['Centrocosto'], $empresaId);
            $data['id'] = $id;
            $usuarioIds = !empty($this->request->data['Centrocosto']['usuarios'])
                ? $this->request->data['Centrocosto']['usuarios']
                : array();

            if ($this->Centrocosto->guardarCentroCosto($data, $usuarioIds)) {
                $this->Session->setFlash('El centro de costo ha sido actualizado correctamente.');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash('No se pudo actualizar el centro de costo. Intente de nuevo.');
        } else {
            $this->request->data = $centro;
            // Pre-seleccionar los usuarios ya asignados
            $usuariosAsignados = array();
            if (!empty($centro['Usuario'])) {
                foreach ($centro['Usuario'] as $u) {
                    $usuariosAsignados[] = $u['id'];
                }
            }
            $this->request->data['Centrocosto']['usuarios'] = $usuariosAsignados;
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

        // Validar que el centro sea editable antes de eliminar
        $centro = $this->Centrocosto->obtenerCentroCostoPorId($id, $empresaId);

        if (empty($centro)) {
            echo json_encode(array('resp' => false, 'msg' => 'El centro de costo no existe.'));
            return;
        }

        if ($centro['Centrocosto']['editable'] == '0') {
            echo json_encode(array('resp' => false, 'msg' => 'Este centro de costo no es editable y no puede eliminarse.'));
            return;
        }

        $resp = $this->Centrocosto->eliminarCentroCosto($id, $empresaId);

        echo json_encode(array(
            'resp' => $resp,
            'msg'  => $resp ? 'Centro de costo eliminado.' : 'No se pudo eliminar el centro de costo.',
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

    private function _prepararDatos($posData, $empresaId) {
        return array(
            'id'         => !empty($posData['id']) ? $posData['id'] : null,
            'nombre'     => trim($posData['nombre']),
            'empresa_id' => $empresaId,
            'editable'   => isset($posData['editable']) && $posData['editable'] ? '1' : '0',
        );
    }

    private function _setFormVars($empresaId) {
        $usuarios = $this->Usuario->obtenerUsuarioEmpresa($empresaId);
        $this->set(compact('usuarios', 'empresaId'));
    }
}
