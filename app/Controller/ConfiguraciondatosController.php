<?php
App::uses('AppController', 'Controller');
/**
 * Configuraciondatos Controller
 *
 * @property Configuraciondato $Configuraciondato
 * @property PaginatorComponent $Paginator
 */
class ConfiguraciondatosController extends AppController {

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
		$this->Configuraciondato->recursive = 0;
		$this->set('configuraciondatos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Configuraciondato->exists($id)) {
			throw new NotFoundException(__('Invalid configuraciondato'));
		}
		$options = array('conditions' => array('Configuraciondato.' . $this->Configuraciondato->primaryKey => $id));
		$this->set('configuraciondato', $this->Configuraciondato->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Configuraciondato->create();
			if ($this->Configuraciondato->save($this->request->data)) {
				$this->Session->setFlash(__('The configuraciondato has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The configuraciondato could not be saved. Please, try again.'));
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
		if (!$this->Configuraciondato->exists($id)) {
			throw new NotFoundException(__('Invalid configuraciondato'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Configuraciondato->save($this->request->data)) {
				$this->Session->setFlash(__('The configuraciondato has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The configuraciondato could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Configuraciondato.' . $this->Configuraciondato->primaryKey => $id));
			$this->request->data = $this->Configuraciondato->find('first', $options);
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
		$this->Configuraciondato->id = $id;
		if (!$this->Configuraciondato->exists()) {
			throw new NotFoundException(__('Invalid configuraciondato'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Configuraciondato->delete()) {
			$this->Session->setFlash(__('The configuraciondato has been deleted.'));
		} else {
			$this->Session->setFlash(__('The configuraciondato could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
