<?php
App::uses('AppController', 'Controller');
/**
 * Tipopagos Controller
 *
 * @property Tipopago $Tipopago
 * @property PaginatorComponent $Paginator
 */
class TipopagosController extends AppController {

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
            $this->loadModel('Cuenta');

            $arrEmpresas = $this->Auth->user('Empresa');
            $empresaId = $arrEmpresas['id'];

            //se obtiene el listado de cuentas
            $this->loadModel('Cuenta');
            $listCtas = $this->Cuenta->obtenerCuentasDestino($empresaId);            

            $this->Tipopago->recursive = 0;
            $this->set('tipopagos', $this->Paginator->paginate());
            $this->set(compact('listCtas')); 
            
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Tipopago->exists($id)) {
			throw new NotFoundException(__('El tipo de pago no existe.'));
		}
		$options = array('conditions' => array('Tipopago.' . $this->Tipopago->primaryKey => $id));

                $arrEmpresas = $this->Auth->user('Empresa');
                $empresaId = $arrEmpresas['id'];
                
                //se obtiene el listado de cuentas
                $this->loadModel('Cuenta');
                $listCtas = $this->Cuenta->obtenerCuentasDestino($empresaId);                
                
		$this->set('tipopago', $this->Tipopago->find('first', $options));
		$this->set(compact('listCtas'));                 
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) { 
                    $this->Tipopago->create();     
                    if ($this->Tipopago->save($this->request->data)) {
                            $this->Session->setFlash(__('El tipo de pago ha sido guardado.'));
                            return $this->redirect(array('action' => 'index'));
                    } else {
                            $this->Session->setFlash(__('El tipo de pago no pudo ser guardado. Por favor, inténtelo de nuevo.'));
                    }
		}
                
                $arrEmpresas = $this->Auth->user('Empresa');
                $empresaId = $arrEmpresas['id'];
                
                //se obtiene el listado de cuentas
                $this->loadModel('Cuenta');
                $listCtas = $this->Cuenta->obtenerCuentasDestino($empresaId);
                
		$this->set(compact('empresaId'));
		$estados = $this->Tipopago->Estado->find('list');
		$this->set(compact('estados', 'listCtas'));                
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Tipopago->exists($id)) {
			throw new NotFoundException(__('El tipo de pago no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Tipopago->save($this->request->data)) {
				$this->Session->setFlash(__('El tipo de pago ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El tipo de pago no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Tipopago.' . $this->Tipopago->primaryKey => $id));
			$this->request->data = $this->Tipopago->find('first', $options);
		}
		
		$arrEmpresas = $this->Auth->user('Empresa');
		$empresaId = $arrEmpresas['id'];
		
		//se obtiene el listado de cuentas
		$this->loadModel('Cuenta');
		$listCtas = $this->Cuenta->obtenerCuentasDestino($empresaId);

		$default = $this->request->data['Tipopago']['cuenta_id'];
                
		$this->set(compact('empresaId'));
		$estados = $this->Tipopago->Estado->find('list');
		$this->set(compact('estados', 'listCtas', 'default'));                  
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Tipopago->id = $id;
		if (!$this->Tipopago->exists()) {
			throw new NotFoundException(__('El tipo de pago no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Tipopago->delete()) {
			$this->Session->setFlash(__('El tipo de pago ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El tipo de pago no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
