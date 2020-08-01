<?php
App::uses('AppController', 'Controller');

class MarcavehiculosController extends AppController {

	public $components = array('Paginator');

	public function index() {
            $paginate = array();
            
            if(isset($this->passedArgs['nombre']) && $this->passedArgs['nombre'] != ""){
                $paginate['LOWER(Marcavehiculo.descripcion) LIKE'] = '%' . strtolower($this->passedArgs['nombre']) . '%';
            }
            
            $this->Marcavehiculo->recursive = 0;
            $this->set('marcavehiculos', $this->Paginator->paginate('Marcavehiculo', $paginate));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Marcavehiculo->exists($id)) {
			throw new NotFoundException(__('La marca del vehículo no existe.'));
		}
		$options = array('conditions' => array('Marcavehiculo.' . $this->Marcavehiculo->primaryKey => $id));
		$this->set('marcavehiculo', $this->Marcavehiculo->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Marcavehiculo->create();
			if ($this->Marcavehiculo->save($this->request->data)) {
				$this->Session->setFlash(__('La marca de vehículo ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La marca de vehículo no pudo ser guardada. Por favor, inténtelo de nuevo.'));
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
		if (!$this->Marcavehiculo->exists($id)) {
			throw new NotFoundException(__('La marca de vehículo no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Marcavehiculo->save($this->request->data)) {
				$this->Session->setFlash(__('La marca de vehículo ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La marca de vehículo no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Marcavehiculo.' . $this->Marcavehiculo->primaryKey => $id));
			$this->request->data = $this->Marcavehiculo->find('first', $options);
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
		$this->Marcavehiculo->id = $id;
		if (!$this->Marcavehiculo->exists()) {
			throw new NotFoundException(__('La marca de vehículo no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Marcavehiculo->delete()) {
			$this->Session->setFlash(__('La marca de vehículo ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La marca de vehículo no pudo ser eliminada. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
        
        public function search() {
            $url = array();
            $url['action'] = 'index';

            foreach ($this->data as $kk => $vv) {
                $url[$kk] = $vv;
            }

            // redirect the user to the url
            $this->redirect($url, null, true);
        }
}
