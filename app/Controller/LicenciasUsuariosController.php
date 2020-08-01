<?php
App::uses('AppController', 'Controller');
/**
 * LicenciasUsuarios Controller
 *
 * @property LicenciasUsuario $LicenciasUsuario
 * @property PaginatorComponent $Paginator
 */
class LicenciasUsuariosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->LicenciasUsuario->recursive = 0;
		$this->set('licenciasUsuarios', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->LicenciasUsuario->exists($id)) {
			throw new NotFoundException(__('La licencia para el usuario no existe.'));
		}
		$options = array('conditions' => array('LicenciasUsuario.' . $this->LicenciasUsuario->primaryKey => $id));
		$this->set('licenciasUsuario', $this->LicenciasUsuario->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->LicenciasUsuario->create();
			if ($this->LicenciasUsuario->save($this->request->data)) {
				$this->Session->setFlash(__('La licencia para el usuario ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La licencia para el usuario no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		}
		$licencias = $this->LicenciasUsuario->Licencia->find('list');
		$usuarios = $this->LicenciasUsuario->Usuario->find('list');
		$estados = $this->LicenciasUsuario->Estado->find('list');
		$this->set(compact('licencias', 'usuarios', 'estados'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->LicenciasUsuario->exists($id)) {
			throw new NotFoundException(__('La licencia para el usuario no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->LicenciasUsuario->save($this->request->data)) {
				$this->Session->setFlash(__('La licencia para el usuario ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La licencia para el usuario no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('LicenciasUsuario.' . $this->LicenciasUsuario->primaryKey => $id));
			$this->request->data = $this->LicenciasUsuario->find('first', $options);
		}
		$licencias = $this->LicenciasUsuario->Licencia->find('list');
		$usuarios = $this->LicenciasUsuario->Usuario->find('list');
		$estados = $this->LicenciasUsuario->Estado->find('list');
		$this->set(compact('licencias', 'usuarios', 'estados'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->LicenciasUsuario->id = $id;
		if (!$this->LicenciasUsuario->exists()) {
			throw new NotFoundException(__('La licencia para el usuario no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->LicenciasUsuario->delete()) {
			$this->Session->setFlash(__('La licencia para el usuario ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La licencia para el usuario no pudo ser eliminada. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
