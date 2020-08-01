<?php
App::uses('AppController', 'Controller');
/**
 * Licencias Controller
 *
 * @property Licencia $Licencia
 * @property PaginatorComponent $Paginator
 */
class LicenciasController extends AppController {

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
		$this->Licencia->recursive = 0;
		$this->set('licencias', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Licencia->exists($id)) {
			throw new NotFoundException(__('La licencia no existe.'));
		}
		$options = array('conditions' => array('Licencia.' . $this->Licencia->primaryKey => $id));
		$this->set('licencia', $this->Licencia->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Licencia->create();
			if ($this->Licencia->save($this->request->data)) {
				$this->Session->setFlash(__('La licencia ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La licencia no ha sido guardad. Por favor, inténtelo de nuevo.'));
			}
		}
		$usuarios = $this->Licencia->Usuario->find('list');
		$this->set(compact('usuarios'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Licencia->exists($id)) {
			throw new NotFoundException(__('La licencia no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Licencia->save($this->request->data)) {
				$this->Session->setFlash(__('La licencia ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La licencia no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Licencia.' . $this->Licencia->primaryKey => $id));
			$this->request->data = $this->Licencia->find('first', $options);
		}
		$usuarios = $this->Licencia->Usuario->find('list');
		$this->set(compact('usuarios'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Licencia->id = $id;
		if (!$this->Licencia->exists()) {
			throw new NotFoundException(__('La licencia no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Licencia->delete()) {
			$this->Session->setFlash(__('La licencia ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La licencia no pudo ser eliminada. Por favor, inténtelo nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
