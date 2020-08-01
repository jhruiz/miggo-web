<?php
App::uses('AppController', 'Controller');
/**
 * Anotaciones Controller
 *
 * @property Anotacione $Anotacione
 * @property PaginatorComponent $Paginator
 */
class AnotacionesController extends AppController {

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
		$this->Anotacione->recursive = 0;
		$this->set('anotaciones', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Anotacione->exists($id)) {
			throw new NotFoundException(__('La nota no existe.'));
		}
		$options = array('conditions' => array('Anotacione.' . $this->Anotacione->primaryKey => $id));
		$this->set('anotacione', $this->Anotacione->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Anotacione->create();
			if ($this->Anotacione->save($this->request->data)) {
				$this->Session->setFlash(__('La nota ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La nota no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		}
                /*Se obtiene el id del usuario en sesion*/
                $usuarioId = $this->Auth->user('id');
                
		$clientes = $this->Anotacione->Cliente->find('list');
		$this->set(compact('usuarioId', 'clientes'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Anotacione->exists($id)) {
			throw new NotFoundException(__('La nota no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Anotacione->save($this->request->data)) {
				$this->Session->setFlash(__('La nota ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La nota no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Anotacione.' . $this->Anotacione->primaryKey => $id));
			$this->request->data = $this->Anotacione->find('first', $options);
		}
                /*Se obtiene el id del usuario en sesion*/
                $usuarioId = $this->Auth->user('id');
                
		$clientes = $this->Anotacione->Cliente->find('list');
		$this->set(compact('usuarioId', 'clientes'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Anotacione->id = $id;
		if (!$this->Anotacione->exists()) {
			throw new NotFoundException(__('La nota no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Anotacione->delete()) {
			$this->Session->setFlash(__('La nota ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La nota no pudo ser eliminada. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
        
        public function notacargueinventario(){
            /*Se crea un input tipo text para agregar la nota sobre el cargue del inventario*/
            $posData = $this->request->data;
            $usuarioId = $posData['usuarioId'];
            $this->set(compact('usuarioId'));            
        }
        
        public function notadescargueinventario(){
            /*Se crea un input tipo text para agregar la nota sobre el cargue del inventario*/
            $posData = $this->request->data;
            $usuarioId = $posData['usuarioId'];
            $this->set(compact('usuarioId'));              
        }
        
        public function notatrasladoinventario(){
            /*Se crea un input tipo text para agregar la nota sobre el cargue del inventario*/
            $posData = $this->request->data;
            $usuarioId = $posData['usuarioId'];
            $this->set(compact('usuarioId'));                          
        }
}

