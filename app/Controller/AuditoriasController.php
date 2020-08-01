<?php
App::uses('AppController', 'Controller');
/**
 * Auditorias Controller
 *
 * @property Auditoria $Auditoria
 * @property PaginatorComponent $Paginator
 */
class AuditoriasController extends AppController {

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
		$this->Auditoria->recursive = 0;
		$this->set('auditorias', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Auditoria->exists($id)) {
			throw new NotFoundException(__('Invalid auditoria'));
		}
		$options = array('conditions' => array('Auditoria.' . $this->Auditoria->primaryKey => $id));
		$this->set('auditoria', $this->Auditoria->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Auditoria->create();
			if ($this->Auditoria->save($this->request->data)) {
				$this->Session->setFlash(__('The auditoria has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The auditoria could not be saved. Please, try again.'));
			}
		}
		$usuarios = $this->Auditoria->Usuario->find('list');
		$this->set(compact('usuarios'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Auditoria->exists($id)) {
			throw new NotFoundException(__('Invalid auditoria'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Auditoria->save($this->request->data)) {
				$this->Session->setFlash(__('The auditoria has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The auditoria could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Auditoria.' . $this->Auditoria->primaryKey => $id));
			$this->request->data = $this->Auditoria->find('first', $options);
		}
		$usuarios = $this->Auditoria->Usuario->find('list');
		$this->set(compact('usuarios'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Auditoria->id = $id;
		if (!$this->Auditoria->exists()) {
			throw new NotFoundException(__('Invalid auditoria'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Auditoria->delete()) {
			$this->Session->setFlash(__('The auditoria has been deleted.'));
		} else {
			$this->Session->setFlash(__('The auditoria could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
