<?php
App::uses('AppController', 'Controller');

class TipovehiculosController extends AppController {

	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
    public function index() {
        $paginate = array();

        $this->Tipovehiculo->recursive = 0;
        $this->set('tipovehiculos', $this->Paginator->paginate('Tipovehiculo', $paginate));
    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Tipovehiculo->exists($id)) {
			throw new NotFoundException(__('El tipo de vehículo no existe.'));
		}
		$options = array('conditions' => array('Tipovehiculo.' . $this->Tipovehiculo->primaryKey => $id));
		$this->set('tipovehiculo', $this->Tipovehiculo->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Tipovehiculo->create();
			if ($this->Tipovehiculo->save($this->request->data)) {
				$this->Session->setFlash(__('El tipo de vehículo ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El tipo de vehículo no pudo ser guardado. Por favor, inténtelo de nuevo.'));
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
		if (!$this->Tipovehiculo->exists($id)) {
			throw new NotFoundException(__('El tipo de vehículo no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Tipovehiculo->save($this->request->data)) {
				$this->Session->setFlash(__('El tipo de vehículo ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El tipo de vehículo no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Tipovehiculo.' . $this->Tipovehiculo->primaryKey => $id));
			$this->request->data = $this->Tipovehiculo->find('first', $options);
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
		$this->Tipovehiculo->id = $id;
		if (!$this->Tipovehiculo->exists()) {
			throw new NotFoundException(__('El tipo de vehiculo no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Tipovehiculo->delete()) {
			$this->Session->setFlash(__('El tipo de vehículo ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El tipo de vehículo no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
        
}
