<?php
App::uses('AppController', 'Controller');
/**
 * Ventarapidas Controller
 *
 * @property Ventarapida $Ventarapida
 * @property PaginatorComponent $Paginator
 */
class VentarapidasController extends AppController {

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
		$this->Ventarapida->recursive = 0;
		$this->set('ventarapidas', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Ventarapida->exists($id)) {
			throw new NotFoundException(__('Invalid ventarapida'));
		}
		$options = array('conditions' => array('Ventarapida.' . $this->Ventarapida->primaryKey => $id));
		$this->set('ventarapida', $this->Ventarapida->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Ventarapida->create();
			if ($this->Ventarapida->save($this->request->data)) {
				$this->Session->setFlash(__('The ventarapida has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The ventarapida could not be saved. Please, try again.'));
			}
		}
		$facturas = $this->Ventarapida->Factura->find('list');
		$this->set(compact('facturas'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Ventarapida->exists($id)) {
			throw new NotFoundException(__('Invalid ventarapida'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Ventarapida->save($this->request->data)) {
				$this->Session->setFlash(__('The ventarapida has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The ventarapida could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Ventarapida.' . $this->Ventarapida->primaryKey => $id));
			$this->request->data = $this->Ventarapida->find('first', $options);
		}
		$facturas = $this->Ventarapida->Factura->find('list');
		$this->set(compact('facturas'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Ventarapida->id = $id;
		if (!$this->Ventarapida->exists()) {
			throw new NotFoundException(__('Invalid ventarapida'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Ventarapida->delete()) {
			$this->Session->setFlash(__('The ventarapida has been deleted.'));
		} else {
			$this->Session->setFlash(__('The ventarapida could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
