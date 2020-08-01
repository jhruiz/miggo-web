<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Notafacturas Controller
 *
 * @property Notafactura $Notafactura
 * @property PaginatorComponent $Paginator
 */
class NotafacturasController extends AppController {

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
            	
            /*se reagistra la actividad del uso de la aplicacion*/
            $usuariosController = new UsuariosController();
            $usuarioAct = $this->Auth->user('id');
            $usuariosController->registraractividad($usuarioAct);
            	
            $empresa_id = $this->Auth->user('empresa_id');
            $paginate['Notafactura.empresa_id'] = $empresa_id;
            $this->Notafactura->recursive = 0;
            $this->set('notafacturas', $this->Paginator->paginate('Notafactura',$paginate));
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
            	
		if (!$this->Notafactura->exists($id)) {
			throw new NotFoundException(__('La nota no existe.'));
		}
		$options = array('conditions' => array('Notafactura.' . $this->Notafactura->primaryKey => $id));
		$this->set('notafactura', $this->Notafactura->find('first', $options));
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
			$this->Notafactura->create();
			if ($this->Notafactura->save($this->request->data)) {
				$this->Session->setFlash(__('La nota ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La nota no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		}
                $empresa = $this->Auth->user('empresa_id');		
		$this->set(compact('empresa', 'facturas'));
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
            	
		if (!$this->Notafactura->exists($id)) {
			throw new NotFoundException(__('La nota no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Notafactura->save($this->request->data)) {
				$this->Session->setFlash(__('La nota ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La nota no pudo ser guarada. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Notafactura.' . $this->Notafactura->primaryKey => $id));
			$this->request->data = $this->Notafactura->find('first', $options);
		}
		$empresas = $this->Notafactura->Empresa->find('list');
		$facturas = $this->Notafactura->Factura->find('list');
		$this->set(compact('empresas', 'facturas'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Notafactura->id = $id;
		if (!$this->Notafactura->exists()) {
			throw new NotFoundException(__('La nota no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Notafactura->delete()) {
			$this->Session->setFlash(__('La nota ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La nota no pudo ser eliminada. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
