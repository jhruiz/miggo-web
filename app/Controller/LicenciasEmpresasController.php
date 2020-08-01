<?php
App::uses('AppController', 'Controller');
/**
 * LicenciasEmpresas Controller
 *
 * @property LicenciasEmpresa $LicenciasEmpresa
 * @property PaginatorComponent $Paginator
 */
class LicenciasEmpresasController extends AppController {

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
		$this->LicenciasEmpresa->recursive = 0;
		$this->set('licenciasEmpresas', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->LicenciasEmpresa->exists($id)) {
			throw new NotFoundException(__('La licencia para la empresa no existe.'));
		}
		$options = array('conditions' => array('LicenciasEmpresa.' . $this->LicenciasEmpresa->primaryKey => $id));
		$this->set('licenciasEmpresa', $this->LicenciasEmpresa->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->LicenciasEmpresa->create();
			if ($this->LicenciasEmpresa->save($this->request->data)) {
				$this->Session->setFlash(__('La licencia para la empresa ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La licencia para la empresa no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		}
		$licencias = $this->LicenciasEmpresa->Licencia->find('list');
		$empresas = $this->LicenciasEmpresa->Empresa->find('list');
		$estados = $this->LicenciasEmpresa->Estado->find('list');
		$this->set(compact('licencias', 'empresas', 'estados'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->LicenciasEmpresa->exists($id)) {
			throw new NotFoundException(__('La licencia para la empresa no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {                    
			if ($this->LicenciasEmpresa->save($this->request->data)) {
				$this->Session->setFlash(__('La licencia para la empresa ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Las licenciasss para la empresa no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('LicenciasEmpresa.' . $this->LicenciasEmpresa->primaryKey => $id));
			$this->request->data = $this->LicenciasEmpresa->find('first', $options);
		}
		$licencias = $this->LicenciasEmpresa->Licencia->find('list');
		$empresas = $this->LicenciasEmpresa->Empresa->find('list');
		$estados = $this->LicenciasEmpresa->Estado->find('list');
		$this->set(compact('licencias', 'empresas', 'estados'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->LicenciasEmpresa->id = $id;
		if (!$this->LicenciasEmpresa->exists()) {
			throw new NotFoundException(__('La licencia para la empresa no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->LicenciasEmpresa->delete()) {
			$this->Session->setFlash(__('La licencia para la empresa ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La licencia para la empresa no pudo ser eliminada. Por favor, inténte de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
