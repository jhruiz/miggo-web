<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * DepositosUsuarios Controller
 *
 * @property DepositosUsuario $DepositosUsuario
 * @property PaginatorComponent $Paginator
 */
class DepositosUsuariosController extends AppController {

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
            	
            $this->loadModel('Usuario');
            $this->loadModel('Deposito');
            
            if(isset($this->passedArgs['usuarios']) && $this->passedArgs['usuarios'] != ""){
                $paginate['DepositosUsuario.usuario_id'] = $this->passedArgs['usuarios'];
            }

            if(isset($this->passedArgs['depositos']) && $this->passedArgs['depositos'] != ""){
                $paginate['DepositosUsuario.deposito_id'] = $this->passedArgs['depositos'];
            }            
            
            $empresaId = $this->Auth->user('empresa_id');
            
            //Se obtiene el listado de usuarios por empresa
            $usuarios = $this->Usuario->obtenerUsuarioEmpresa($empresaId);
            
            //Se obtiene el listado de depositos por empresa
            $depositos = $this->Deposito->obtenerDepositoEmpresa($empresaId);
            
            $paginate['DepositosUsuario.empresa_id'] = $empresaId;
            $this->DepositosUsuario->recursive = 0;
            $this->set('depositosUsuarios', $this->Paginator->paginate('DepositosUsuario',$paginate));
            $this->set(compact('usuarios', 'depositos'));
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
            	
		if (!$this->DepositosUsuario->exists($id)) {
			throw new NotFoundException(__('La relación Usuario - Depósito no existe.'));
		}
		$options = array('conditions' => array('DepositosUsuario.' . $this->DepositosUsuario->primaryKey => $id));
		$this->set('depositosUsuario', $this->DepositosUsuario->find('first', $options));
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
			$this->DepositosUsuario->create();
			if ($this->DepositosUsuario->save($this->request->data)) {
				$this->Session->setFlash(__('La relación Usuario - Depósito ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La relación Usuario - Depósito no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		}
                $empresaId = $this->Auth->user('empresa_id');
                $depositos = $this->DepositosUsuario->Deposito->obtenerDepositoEmpresa($empresaId);                
		$usuarios = $this->DepositosUsuario->Usuario->obtenerUsuarioEmpresa($empresaId);
		$this->set(compact('usuarios','depositos', 'empresaId'));                 
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
            	
		if (!$this->DepositosUsuario->exists($id)) {
			throw new NotFoundException(__('La relación Usuario - Depósito no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->DepositosUsuario->save($this->request->data)) {
				$this->Session->setFlash(__('La relación Usuario - Depósito ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La relación Usuario - Depósito no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('DepositosUsuario.' . $this->DepositosUsuario->primaryKey => $id));
			$this->request->data = $this->DepositosUsuario->find('first', $options);
		}
                $empresaId = $this->Auth->user('empresa_id');
                $depositos = $this->DepositosUsuario->Deposito->obtenerDepositoEmpresa($empresaId);                
		$usuarios = $this->DepositosUsuario->Usuario->obtenerUsuarioEmpresa($empresaId);
		$this->set(compact('usuarios','depositos'));                 
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->DepositosUsuario->id = $id;
		if (!$this->DepositosUsuario->exists()) {
			throw new NotFoundException(__('La relación Usuario - Depósito no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->DepositosUsuario->delete()) {
			$this->Session->setFlash(__('La relación Usuario - Depósito ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La relación Usuario - Depósito no pudo ser eliminada. Por favor, inténtelo de nuevo.'));
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
