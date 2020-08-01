<?php
App::uses('AppController', 'Controller');
/**
 * Paises Controller
 *
 * @property Paise $Paise
 * @property PaginatorComponent $Paginator
 */
class PaisesController extends AppController {

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
                $paginate['LOWER(Paise.descripcion) LIKE'] = '%' . strtolower($this->passedArgs['nombre']) . '%';
            }
            
            $this->Paise->recursive = 0;
            $this->set('paises', $this->Paginator->paginate('Paise', $paginate));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Paise->exists($id)) {
			throw new NotFoundException(__('El pais no existe.'));
		}
		$options = array('conditions' => array('Paise.' . $this->Paise->primaryKey => $id));
		$this->set('paise', $this->Paise->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Paise->create();
			if ($this->Paise->save($this->request->data)) {
				$this->Session->setFlash(__('El pais ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El pais no pudo ser guardado. Por favor, inténtelo de nuevo.'));
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
		if (!$this->Paise->exists($id)) {
			throw new NotFoundException(__('El pais no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Paise->save($this->request->data)) {
				$this->Session->setFlash(__('El pais ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El pais no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Paise.' . $this->Paise->primaryKey => $id));
			$this->request->data = $this->Paise->find('first', $options);
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
		$this->Paise->id = $id;
		if (!$this->Paise->exists()) {
			throw new NotFoundException(__('El pais no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Paise->delete()) {
			$this->Session->setFlash(__('El pais ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El pais no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
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
