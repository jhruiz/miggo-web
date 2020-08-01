<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Tipodepositos Controller
 *
 * @property Tipodeposito $Tipodeposito
 * @property PaginatorComponent $Paginator
 */
class TipodepositosController extends AppController {

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
            	
            $empresaId = $this->Auth->user('empresa_id');
            $paginate['Tipodeposito.empresa_id'] = $empresaId;
            $this->Tipodeposito->recursive = 0;
            $this->set('tipodepositos', $this->Paginator->paginate('Tipodeposito',$paginate));  
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
            	
		if (!$this->Tipodeposito->exists($id)) {
			throw new NotFoundException(__('El tipo de depósito no existe.'));
		}
		$options = array('conditions' => array('Tipodeposito.' . $this->Tipodeposito->primaryKey => $id));
		$this->set('tipodeposito', $this->Tipodeposito->find('first', $options));
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
			$this->Tipodeposito->create();
			if ($this->Tipodeposito->save($this->request->data)) {
				$this->Session->setFlash(__('El tipo de depósito ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El tipo de depósito no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		}
                /*Se obtiene la información de la empresa a la cual pertenece el usuario en sesion*/
                $empresaId = $this->Auth->user('empresa_id');                
		$this->set(compact('empresaId'));
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
            	
		if (!$this->Tipodeposito->exists($id)) {
			throw new NotFoundException(__('El tipo de depósito no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Tipodeposito->save($this->request->data)) {
				$this->Session->setFlash(__('El tipo de depósito ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El tipo de depósito no ha sido guardado. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Tipodeposito.' . $this->Tipodeposito->primaryKey => $id));
			$this->request->data = $this->Tipodeposito->find('first', $options);
		}
                /*Se obtiene la información del usuario en sesion*/
                $arrEmpresa = $this->Auth->user('Empresa');
                $empresaId = $arrEmpresa['id'];
                
		$this->set(compact('empresaId'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Tipodeposito->id = $id;
		if (!$this->Tipodeposito->exists()) {
			throw new NotFoundException(__('El tipo de depósito no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Tipodeposito->delete()) {
			$this->Session->setFlash(__('El tipo de depósito ha sido eliminado'));
		} else {
			$this->Session->setFlash(__('El tipo de depósito no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
