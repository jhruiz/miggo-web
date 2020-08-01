<?php
App::uses('AppController', 'Controller');
/**
 * Semaforos Controller
 *
 * @property Semaforo $Semaforo
 * @property PaginatorComponent $Paginator
 */
class SemaforosController extends AppController {

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
		$this->Semaforo->recursive = 0;
		$this->set('semaforos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
            
		if (!$this->Semaforo->exists($id)) {
			throw new NotFoundException(__('El semáforo no existe'));
		}
		$options = array('conditions' => array('Semaforo.' . $this->Semaforo->primaryKey => $id));
		$this->set('semaforo', $this->Semaforo->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Semaforo->create();
			if ($this->Semaforo->save($this->request->data)) {
				$this->Session->setFlash(__('El semáforo ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El semáforo no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
            
		if (!$this->Semaforo->exists($id)) {
			throw new NotFoundException(__('El semáforo no existe'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Semaforo->save($this->request->data)) {
				$this->Session->setFlash(__('El semáforo ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El semáforo no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Semaforo.' . $this->Semaforo->primaryKey => $id));
			$this->request->data = $this->Semaforo->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
            
		$this->Semaforo->id = $id;
		if (!$this->Semaforo->exists()) {
			throw new NotFoundException(__('El semáforo no existe'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Semaforo->delete()) {
			$this->Session->setFlash(__('El semáforo ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El semáforo no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
