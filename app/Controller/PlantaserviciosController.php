<?php
App::uses('AppController', 'Controller');

class PlantaserviciosController extends AppController {
    public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
    public function index() {
        $paginate = array();

        $this->Plantaservicio->recursive = 0;
        $this->set('plantaservicios', $this->Paginator->paginate('Plantaservicio', $paginate));
    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Plantaservicio->exists($id)) {
			throw new NotFoundException(__('La planta de servicio no existe.'));
		}
		$options = array('conditions' => array('Plantaservicio.' . $this->Plantaservicio->primaryKey => $id));
		$this->set('plantaservicio', $this->Plantaservicio->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Plantaservicio->create();
			if ($this->Plantaservicio->save($this->request->data)) {
				$this->Session->setFlash(__('La planta de servicio ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La planta de servicio no pudo ser guardada. Por favor, inténtelo de nuevo.'));
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
		if (!$this->Plantaservicio->exists($id)) {
			throw new NotFoundException(__('La planta de servicio no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Plantaservicio->save($this->request->data)) {
				$this->Session->setFlash(__('La planta de servicio ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La planta de servicio no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Plantaservicio.' . $this->Plantaservicio->primaryKey => $id));
			$this->request->data = $this->Plantaservicio->find('first', $options);
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
            $this->Plantaservicio->id = $id;
            if (!$this->Plantaservicio->exists()) {
                    throw new NotFoundException(__('La planta de servicio no existe.'));
            }
            $this->request->onlyAllow('post', 'delete');
            if ($this->Plantaservicio->delete()) {
                    $this->Session->setFlash(__('La planta de servicio ha sido eliminada.'));
            } else {
                    $this->Session->setFlash(__('La planta de servicio no pudo ser eliminada. Por favor, inténtelo de nuevo.'));
            }
            return $this->redirect(array('action' => 'index'));
    }
}
