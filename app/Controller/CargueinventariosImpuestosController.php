<?php
App::uses('AppController', 'Controller');
/**
 * CargueinventariosImpuestos Controller
 *
 * @property CargueinventariosImpuesto $CargueinventariosImpuesto
 * @property PaginatorComponent $Paginator
 */
class CargueinventariosImpuestosController extends AppController {

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
		$this->CargueinventariosImpuesto->recursive = 0;
		$this->set('cargueinventariosImpuestos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->CargueinventariosImpuesto->exists($id)) {
			throw new NotFoundException(__('Invalid cargueinventarios impuesto'));
		}
		$options = array('conditions' => array('CargueinventariosImpuesto.' . $this->CargueinventariosImpuesto->primaryKey => $id));
		$this->set('cargueinventariosImpuesto', $this->CargueinventariosImpuesto->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->CargueinventariosImpuesto->create();
			if ($this->CargueinventariosImpuesto->save($this->request->data)) {
				$this->Session->setFlash(__('The cargueinventarios impuesto has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cargueinventarios impuesto could not be saved. Please, try again.'));
			}
		}
		$cargueinventarios = $this->CargueinventariosImpuesto->Cargueinventario->find('list');
		$impuestos = $this->CargueinventariosImpuesto->Impuesto->find('list');
		$this->set(compact('cargueinventarios', 'impuestos'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->CargueinventariosImpuesto->exists($id)) {
			throw new NotFoundException(__('Invalid cargueinventarios impuesto'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->CargueinventariosImpuesto->save($this->request->data)) {
				$this->Session->setFlash(__('The cargueinventarios impuesto has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cargueinventarios impuesto could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CargueinventariosImpuesto.' . $this->CargueinventariosImpuesto->primaryKey => $id));
			$this->request->data = $this->CargueinventariosImpuesto->find('first', $options);
		}
		$cargueinventarios = $this->CargueinventariosImpuesto->Cargueinventario->find('list');
		$impuestos = $this->CargueinventariosImpuesto->Impuesto->find('list');
		$this->set(compact('cargueinventarios', 'impuestos'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->CargueinventariosImpuesto->id = $id;
		if (!$this->CargueinventariosImpuesto->exists()) {
			throw new NotFoundException(__('Invalid cargueinventarios impuesto'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->CargueinventariosImpuesto->delete()) {
			$this->Session->setFlash(__('The cargueinventarios impuesto has been deleted.'));
		} else {
			$this->Session->setFlash(__('The cargueinventarios impuesto could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
