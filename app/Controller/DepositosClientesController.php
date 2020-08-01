<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * DepositosClientes Controller
 *
 * @property DepositosCliente $DepositosCliente
 * @property PaginatorComponent $Paginator
 */
class DepositosClientesController extends AppController {

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
            /*se reagistra la actividad del uso de la aplicacion*/
            $usuariosController = new UsuariosController();
            $usuarioAct = $this->Auth->user('id');
            $usuariosController->registraractividad($usuarioAct);
            	
            $this->loadModel('Cliente');
            $this->loadModel('Deposito');
            
            if(isset($this->passedArgs['cliente']) && $this->passedArgs['cliente'] != ""){
                $paginate['DepositosCliente.cliente_id'] = $this->passedArgs['cliente'];
            }

            if(isset($this->passedArgs['deposito']) && $this->passedArgs['deposito'] != ""){
                $paginate['DepositosCliente.deposito_id'] = $this->passedArgs['deposito'];
            }
            
            $empresaId = $this->Auth->user('empresa_id');
            
            //Se obtiene el listado de clientes de la empresa
            $clientes = $this->Cliente->obtenerClienteEmpresa($empresaId);
            
            //Se obtiene el listado de depositos de la empresa
            $depositos = $this->Deposito->obtenerDepositoEmpresa($empresaId);
            
            $paginate['DepositosCliente.empresa_id'] = $empresaId;
            $this->DepositosCliente->recursive = 0;
            $this->set('depositosClientes', $this->Paginator->paginate('DepositosCliente',$paginate));
            $this->set(compact('depositos', 'clientes'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
            /*se reagistra la actividad del uso de la aplicacion*/
            $usuariosController = new UsuariosController();
            $usuarioAct = $this->Auth->user('id');
            $usuariosController->registraractividad($usuarioAct);
            	
		if (!$this->DepositosCliente->exists($id)) {
			throw new NotFoundException(__('La relación cliente - depósito no existe.'));
		}
		$options = array('conditions' => array('DepositosCliente.' . $this->DepositosCliente->primaryKey => $id));
		$this->set('depositosCliente', $this->DepositosCliente->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
            /*se reagistra la actividad del uso de la aplicacion*/
            $usuariosController = new UsuariosController();
            $usuarioAct = $this->Auth->user('id');
            $usuariosController->registraractividad($usuarioAct);
            	
		if ($this->request->is('post')) {
			$this->DepositosCliente->create();
			if ($this->DepositosCliente->save($this->request->data)) {
				$this->Session->setFlash(__('La relación cliente - depósito ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La relación cliente - depósito no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		}
                $empresaId = $this->Auth->user('empresa_id');
                $depositos = $this->DepositosCliente->Deposito->obtenerDepositoEmpresa($empresaId);
		$clientes = $this->DepositosCliente->Cliente->obtenerClienteEmpresa($empresaId);
		$this->set(compact('clientes','depositos', 'empresaId'));                
                
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
            /*se reagistra la actividad del uso de la aplicacion*/
            $usuariosController = new UsuariosController();
            $usuarioAct = $this->Auth->user('id');
            $usuariosController->registraractividad($usuarioAct);
            	
		if (!$this->DepositosCliente->exists($id)) {
			throw new NotFoundException(__('La relación cliente - depósito no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->DepositosCliente->save($this->request->data)) {
				$this->Session->setFlash(__('La relación cliente - depósito ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La relación cliente - depósito no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('DepositosCliente.' . $this->DepositosCliente->primaryKey => $id));
			$this->request->data = $this->DepositosCliente->find('first', $options);
		}
                
                $empresaId = $this->Auth->user('empresa_id');
                $depositos = $this->DepositosCliente->Deposito->obtenerDepositoEmpresa($empresaId);
		$clientes = $this->DepositosCliente->Cliente->obtenerClienteEmpresa($empresaId);
		$this->set(compact('clientes','depositos'));                 
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->DepositosCliente->id = $id;
		if (!$this->DepositosCliente->exists()) {
			throw new NotFoundException(__('La relación cliente - depósito no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->DepositosCliente->delete()) {
			$this->Session->setFlash(__('La relación cliente - depósito ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La relación cliente - depósito no pudo ser eliminada. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
        
        public function search() {
            $url = array();
            $url['action'] = 'index';

            foreach ($this->data as $kk => $vv) {
                $url[$kk] = $vv;
            }

            // redirect the user to the url
            $this->redirect($url, null, true);
        }        
        
}
