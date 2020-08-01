<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Proveedores Controller
 *
 * @property Proveedore $Proveedore
 * @property PaginatorComponent $Paginator
 */
class ProveedoresController extends AppController {

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
            		
            $this->loadModel('Ciudade');
            $empresaId = $this->Auth->user('empresa_id');
             
            if(isset($this->passedArgs['nombre']) && $this->passedArgs['nombre'] != ""){
                $paginate['LOWER(Proveedore.nombre) LIKE'] = '%' . strtolower($this->passedArgs['nombre']) . '%';
            }
            
            if(isset($this->passedArgs['nit']) && $this->passedArgs['nit'] != ""){
                $paginate['LOWER(Proveedore.nit) LIKE'] = '%' . strtolower($this->passedArgs['nit']) . '%';
            }
            
            if(isset($this->passedArgs['ciudad']) && $this->passedArgs['ciudad'] != ""){
                $paginate['Proveedore.ciudade_id'] = $this->passedArgs['ciudad'];
            }            
            
            //Se obtiene el listado de ciudades
            $ciudades = $this->Ciudade->obtenerListaCiudades();
            
            $paginate['Proveedore.empresa_id'] = $empresaId;
            $this->Proveedore->recursive = 0;            
            $this->set('proveedores', $this->Paginator->paginate('Proveedore',$paginate));
            $this->set(compact('ciudades'));
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
            		
		if (!$this->Proveedore->exists($id)) {
			throw new NotFoundException(__('El proveedor no existe.'));
		}
		$options = array('conditions' => array('Proveedore.' . $this->Proveedore->primaryKey => $id));
		$this->set('proveedore', $this->Proveedore->find('first', $options));
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
			$this->Proveedore->create();

                        /*Se eliminan las comas del valor*/
                        $this->request->data['Proveedore']['limitecredito'] = str_replace(',', '', $this->request->data['Proveedore']['limitecredito']);
                			
			if ($this->Proveedore->save($this->request->data)) {
				$this->Session->setFlash(__('El proveedor ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El proveedor no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		}
                $empresaId = $this->Auth->user('empresa_id');
                $usuarioId = $this->Auth->user('id');                        
		$ciudades = $this->Proveedore->Ciudade->find('list');		
		$estados = $this->Proveedore->Estado->find('list');
		$this->set(compact('ciudades', 'usuarioId', 'estados', 'empresaId'));
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
            		
		if (!$this->Proveedore->exists($id)) {
			throw new NotFoundException(__('El proveedor no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
                        /*Se eliminan las comas del valor*/
                        $this->request->data['Proveedore']['limitecredito'] = str_replace(',', '', $this->request->data['Proveedore']['limitecredito']);    		
			if ($this->Proveedore->save($this->request->data)) {
				$this->Session->setFlash(__('El proveedor ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El proveedor no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Proveedore.' . $this->Proveedore->primaryKey => $id));
			$this->request->data = $this->Proveedore->find('first', $options);
		}
                $usuarioId = $this->Auth->user('id');  
		$ciudades = $this->Proveedore->Ciudade->find('list');                
		$estados = $this->Proveedore->Estado->find('list');
		$this->set(compact('ciudades', 'usuarioId', 'estados'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Proveedore->id = $id;
		if (!$this->Proveedore->exists()) {
			throw new NotFoundException(__('El proveedor no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Proveedore->delete()) {
			$this->Session->setFlash(__('El proveedor ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El proveedor no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
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
