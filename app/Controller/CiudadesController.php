<?php
App::uses('AppController', 'Controller');
/**
 * Ciudades Controller
 *
 * @property Ciudade $Ciudade
 * @property PaginatorComponent $Paginator
 */
class CiudadesController extends AppController {

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
            
            $this->loadModel('Paise');
            $paises = $this->Paise->obtenerListaPaises();
            $paginate = array();
            if(isset($this->passedArgs['nombre']) && $this->passedArgs['nombre'] != ""){
                $paginate['LOWER(Ciudade.descripcion) LIKE'] = '%' . strtolower($this->passedArgs['nombre']) . '%';
            }    
            
            if(isset($this->passedArgs['pais']) && $this->passedArgs['pais'] != ""){
                $paginate['Ciudade.paise_id'] = $this->passedArgs['pais'];
            }            
            
            $this->Ciudade->recursive = 0;
            $this->set('ciudades', $this->Paginator->paginate('Ciudade', $paginate));
            $this->set(compact('paises'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Ciudade->exists($id)) {
			throw new NotFoundException(__('La ciudad no existe.'));
		}
		$options = array('conditions' => array('Ciudade.' . $this->Ciudade->primaryKey => $id));
		$this->set('ciudade', $this->Ciudade->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Ciudade->create();
			if ($this->Ciudade->save($this->request->data)) {
				$this->Session->setFlash(__('La ciudad ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La ciudad no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		}
		$paises = $this->Ciudade->Paise->find('list');
		$this->set(compact('paises'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Ciudade->exists($id)) {
			throw new NotFoundException(__('La ciudad no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Ciudade->save($this->request->data)) {
				$this->Session->setFlash(__('La ciudad ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La ciudad no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Ciudade.' . $this->Ciudade->primaryKey => $id));
			$this->request->data = $this->Ciudade->find('first', $options);
		}
		$paises = $this->Ciudade->Paise->find('list');
		$this->set(compact('paises'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Ciudade->id = $id;
		if (!$this->Ciudade->exists()) {
			throw new NotFoundException(__('La ciudad no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Ciudade->delete()) {
			$this->Session->setFlash(__('La ciudad ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La ciudad no pudo ser eliminada. Por favor, inténtelo de nuevo.'));
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
