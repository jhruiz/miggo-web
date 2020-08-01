<?php
App::uses('AppController', 'Controller');
/**
 * Facturasdetalles Controller
 *
 * @property Facturasdetalle $Facturasdetalle
 * @property PaginatorComponent $Paginator
 */
class FacturasdetallesController extends AppController {

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
		$this->Facturasdetalle->recursive = 0;
		$this->set('facturasdetalles', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Facturasdetalle->exists($id)) {
			throw new NotFoundException(__('Invalid facturasdetalle'));
		}
		$options = array('conditions' => array('Facturasdetalle.' . $this->Facturasdetalle->primaryKey => $id));
		$this->set('facturasdetalle', $this->Facturasdetalle->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Facturasdetalle->create();
			if ($this->Facturasdetalle->save($this->request->data)) {
				$this->Session->setFlash(__('The facturasdetalle has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The facturasdetalle could not be saved. Please, try again.'));
			}
		}
		$facturas = $this->Facturasdetalle->Factura->find('list');
		$depositos = $this->Facturasdetalle->Deposito->find('list');
		$tipopagos = $this->Facturasdetalle->Tipopago->find('list');
		$productos = $this->Facturasdetalle->Producto->find('list');
		$this->set(compact('facturas', 'depositos', 'tipopagos', 'productos'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Facturasdetalle->exists($id)) {
			throw new NotFoundException(__('Invalid facturasdetalle'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Facturasdetalle->save($this->request->data)) {
				$this->Session->setFlash(__('The facturasdetalle has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The facturasdetalle could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Facturasdetalle.' . $this->Facturasdetalle->primaryKey => $id));
			$this->request->data = $this->Facturasdetalle->find('first', $options);
		}
		$facturas = $this->Facturasdetalle->Factura->find('list');
		$depositos = $this->Facturasdetalle->Deposito->find('list');
		$tipopagos = $this->Facturasdetalle->Tipopago->find('list');
		$productos = $this->Facturasdetalle->Producto->find('list');
		$this->set(compact('facturas', 'depositos', 'tipopagos', 'productos'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Facturasdetalle->id = $id;
		if (!$this->Facturasdetalle->exists()) {
			throw new NotFoundException(__('Invalid facturasdetalle'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Facturasdetalle->delete()) {
			$this->Session->setFlash(__('The facturasdetalle has been deleted.'));
		} else {
			$this->Session->setFlash(__('The facturasdetalle could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
