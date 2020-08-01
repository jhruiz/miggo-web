<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Depositos Controller
 *
 * @property Deposito $Deposito
 * @property PaginatorComponent $Paginator
 */
class DepositosController extends AppController {

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
            $this->loadModel('Ciudade');
            
            if(isset($this->passedArgs['nombre']) && $this->passedArgs['nombre'] != ""){
                $paginate['LOWER(Deposito.descripcion) LIKE'] = '%' . strtolower($this->passedArgs['nombre']) . '%';
            }
            
            if(isset($this->passedArgs['encargado']) && $this->passedArgs['encargado'] != ""){
                $paginate['Deposito.usuario_id'] = $this->passedArgs['encargado'];
            }

            if(isset($this->passedArgs['ciudad']) && $this->passedArgs['ciudad'] != ""){
                $paginate['Deposito.ciudade_id'] = $this->passedArgs['ciudad'];
            }            
            
            $empresaId = $this->Auth->user('empresa_id');
            
            //se obtienen los usuarios de la empresa
            $usuarios = $this->Usuario->obtenerUsuarioEmpresa($empresaId);
            
            //se obtiene el listado de ciudades
            $ciudades = $this->Ciudade->obtenerListaCiudades();
            
            $paginate['Deposito.empresa_id'] = $empresaId;
            $this->Deposito->recursive = 0;
            $this->set('depositos', $this->Paginator->paginate('Deposito',$paginate));
            $this->set(compact('usuarios', 'ciudades'));
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
            	
		if (!$this->Deposito->exists($id)) {
			throw new NotFoundException(__('El depósito no existe.'));
		}
		$options = array('conditions' => array('Deposito.' . $this->Deposito->primaryKey => $id));
		$this->set('deposito', $this->Deposito->find('first', $options));
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
                    $this->request->data['Deposito']['numresolucionactual'] = $this->request->data['Deposito']['resolucioninicia'];
                    $this->Deposito->create();
                    if ($this->Deposito->save($this->request->data)) {
                            $this->Session->setFlash(__('El depósito ha sido guardado.'));
                            return $this->redirect(array('action' => 'index'));
                    } else {
                            $this->Session->setFlash(__('El depósito no pudo ser guardado. Por favor, inténtelo de nuevo.'));
                    }
		}
                /*Se obtiene la empresa del usuario logueado*/
                $arrEmpresa = $this->Auth->user('Empresa');
                $empresaId = $arrEmpresa['id'];
                
		$ciudades = $this->Deposito->Ciudade->find('list');
		$estados = $this->Deposito->Estado->find('list');
		$usuarios = $this->Deposito->Usuario->obtenerUsuarioEmpresa($empresaId);
		$tipodepositos = $this->Deposito->Tipodeposito->obtenerDepositoEmpresa($empresaId);
		$regimenes = $this->Deposito->Regimene->find('list');
		$clientes = $this->Deposito->Cliente->find('list');
		$this->set(compact('empresaId', 'ciudades', 'estados', 'usuarios', 'tipodepositos', 'regimenes', 'clientes'));
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
            	
		if (!$this->Deposito->exists($id)) {
			throw new NotFoundException(__('El depósito no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Deposito->save($this->request->data)) {
				$this->Session->setFlash(__('El depósito ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El depósito no ha sido guardado. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Deposito.' . $this->Deposito->primaryKey => $id));
			$this->request->data = $this->Deposito->find('first', $options);
		}
                
                /*Se obtiene la empresa del usuario logueado*/
                $arrEmpresa = $this->Auth->user('Empresa');
                $empresaId = $arrEmpresa['id'];
                
		$ciudades = $this->Deposito->Ciudade->find('list');
		$estados = $this->Deposito->Estado->find('list');
		$usuarios = $this->Deposito->Usuario->obtenerUsuarioEmpresa($empresaId);
		$tipodepositos = $this->Deposito->Tipodeposito->obtenerDepositoEmpresa($empresaId);
		$regimenes = $this->Deposito->Regimene->find('list');
		$clientes = $this->Deposito->Cliente->find('list');
		$this->set(compact('empresaId', 'ciudades', 'estados', 'usuarios', 'tipodepositos', 'regimenes', 'clientes'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Deposito->id = $id;
		if (!$this->Deposito->exists()) {
			throw new NotFoundException(__('El depósito no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Deposito->delete()) {
			$this->Session->setFlash(__('El depósito ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El depósito no ha sido eliminado. Por favor, inténtelo de nuevo.'));
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
