<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Impuestos Controller
 *
 * @property Impuesto $Impuesto
 * @property PaginatorComponent $Paginator
 */
class ImpuestosController extends AppController {

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
		$paginate['Impuesto.empresa_id'] = $empresaId; 
		$this->Impuesto->recursive = 0;
		$this->set('impuestos', $this->Paginator->paginate('Impuesto',$paginate));
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
            	
		if (!$this->Impuesto->exists($id)) {
			throw new NotFoundException(__('El impuesto no existe.'));
		}
		$options = array('conditions' => array('Impuesto.' . $this->Impuesto->primaryKey => $id));
		$this->set('impuesto', $this->Impuesto->find('first', $options));
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
			$this->Impuesto->create();
			if ($this->Impuesto->save($this->request->data)) {
				$this->Session->setFlash(__('El impuesto ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El impuesto no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		}
                $arrEmpresa = $this->Auth->user('Empresa');
                $empresaId = $arrEmpresa['id'];
                
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
            	
		if (!$this->Impuesto->exists($id)) {
			throw new NotFoundException(__('El impuesto no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Impuesto->save($this->request->data)) {
				$this->Session->setFlash(__('El impuesto ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El impuesto no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Impuesto.' . $this->Impuesto->primaryKey => $id));
			$this->request->data = $this->Impuesto->find('first', $options);
		}
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
		$this->Impuesto->id = $id;
		if (!$this->Impuesto->exists()) {
			throw new NotFoundException(__('El impuesto no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Impuesto->delete()) {
			$this->Session->setFlash(__('El impuesto ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El impuesto no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
