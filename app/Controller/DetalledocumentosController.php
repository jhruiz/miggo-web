<?php
App::uses('AppController', 'Controller');
/**
 * Detalledocumentos Controller
 *
 * @property Detalledocumento $Detalledocumento
 * @property PaginatorComponent $Paginator
 */
class DetalledocumentosController extends AppController {

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
		$this->Detalledocumento->recursive = 0;
		$this->set('detalledocumentos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Detalledocumento->exists($id)) {
			throw new NotFoundException(__('Invalid detalledocumento'));
		}
		$options = array('conditions' => array('Detalledocumento.' . $this->Detalledocumento->primaryKey => $id));
		$this->set('detalledocumento', $this->Detalledocumento->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Detalledocumento->create();
			if ($this->Detalledocumento->save($this->request->data)) {
				$this->Session->setFlash(__('The detalledocumento has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The detalledocumento could not be saved. Please, try again.'));
			}
		}
		$productos = $this->Detalledocumento->Producto->find('list');
		$depositoorigens = $this->Detalledocumento->Depositoorigen->find('list');
		$depositodestinos = $this->Detalledocumento->Depositodestino->find('list');
		$proveedores = $this->Detalledocumento->Proveedore->find('list');
		$tipopagos = $this->Detalledocumento->Tipopago->find('list');
		$this->set(compact('productos', 'depositoorigens', 'depositodestinos', 'proveedores', 'tipopagos'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Detalledocumento->exists($id)) {
			throw new NotFoundException(__('Invalid detalledocumento'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Detalledocumento->save($this->request->data)) {
				$this->Session->setFlash(__('The detalledocumento has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The detalledocumento could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Detalledocumento.' . $this->Detalledocumento->primaryKey => $id));
			$this->request->data = $this->Detalledocumento->find('first', $options);
		}
		$productos = $this->Detalledocumento->Producto->find('list');
		$depositoorigens = $this->Detalledocumento->Depositoorigen->find('list');
		$depositodestinos = $this->Detalledocumento->Depositodestino->find('list');
		$proveedores = $this->Detalledocumento->Proveedore->find('list');
		$tipopagos = $this->Detalledocumento->Tipopago->find('list');
		$this->set(compact('productos', 'depositoorigens', 'depositodestinos', 'proveedores', 'tipopagos'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Detalledocumento->id = $id;
		if (!$this->Detalledocumento->exists()) {
			throw new NotFoundException(__('Invalid detalledocumento'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Detalledocumento->delete()) {
			$this->Session->setFlash(__('The detalledocumento has been deleted.'));
		} else {
			$this->Session->setFlash(__('The detalledocumento could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function viewmovements($id = null){
		$arrMovements = $this->Detalledocumento->obtenerMovimientosProducto($id);
		$this->set(compact('arrMovements')); 
	}	
}
