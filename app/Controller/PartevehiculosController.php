<?php
App::uses('AppController', 'Controller');
/**
 * Paises Controller
 *
 * @property Paise $Paise
 * @property PaginatorComponent $Paginator
 */
class PartevehiculosController extends AppController {

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
            $paginate = array();
            
            if(isset($this->passedArgs['nombre']) && $this->passedArgs['nombre'] != ""){
                $paginate['LOWER(Partevehiculo.descripcion) LIKE'] = '%' . strtolower($this->passedArgs['nombre']) . '%';
            }
            
            $this->Partevehiculo->recursive = 0;
            $this->set('partevehiculos', $this->Paginator->paginate('Partevehiculo', $paginate));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Partevehiculo->exists($id)) {
			throw new NotFoundException(__('La parte del vehículo no existe no existe.'));
		}
		$options = array('conditions' => array('Partevehiculo.' . $this->Partevehiculo->primaryKey => $id));
		$this->set('partevehiculo', $this->Partevehiculo->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Partevehiculo->create();
			if ($this->Partevehiculo->save($this->request->data)) {
				$this->Session->setFlash(__('La parte del vehículo ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La parte del vehículo no pudo ser guardada. Por favor, inténtelo de nuevo.'));
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
		if (!$this->Partevehiculo->exists($id)) {
			throw new NotFoundException(__('La parte del vehículo no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Partevehiculo->save($this->request->data)) {
				$this->Session->setFlash(__('La parte del vehículo ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La parte del vehículo no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Partevehiculo.' . $this->Partevehiculo->primaryKey => $id));
			$this->request->data = $this->Partevehiculo->find('first', $options);
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
		$this->Partevehiculo->id = $id;
		if (!$this->Partevehiculo->exists()) {
			throw new NotFoundException(__('La parte del vehículo no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Partevehiculo->delete()) {
			$this->Session->setFlash(__('La parte del vehículo ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La parte del vehículo no pudo ser eliminada. Por favor, inténtelo de nuevo.'));
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
