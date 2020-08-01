<?php
App::uses('AppController', 'Controller');
/**
 * Estados Controller
 *
 * @property Estado $Estado
 * @property PaginatorComponent $Paginator
 */
class EstadosController extends AppController {

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
		$this->Estado->recursive = 0;
		$this->set('estados', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Estado->exists($id)) {
			throw new NotFoundException(__('Invalid estado'));
		}
		$options = array('conditions' => array('Estado.' . $this->Estado->primaryKey => $id));
		$this->set('estado', $this->Estado->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Estado->create();
			if ($this->Estado->save($this->request->data)) {
				$this->Session->setFlash(__('The estado has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The estado could not be saved. Please, try again.'));
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
		if (!$this->Estado->exists($id)) {
			throw new NotFoundException(__('Invalid estado'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Estado->save($this->request->data)) {
				$this->Session->setFlash(__('The estado has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The estado could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Estado.' . $this->Estado->primaryKey => $id));
			$this->request->data = $this->Estado->find('first', $options);
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
		$this->Estado->id = $id;
		if (!$this->Estado->exists()) {
			throw new NotFoundException(__('Invalid estado'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Estado->delete()) {
			$this->Session->setFlash(__('The estado has been deleted.'));
		} else {
			$this->Session->setFlash(__('The estado could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
