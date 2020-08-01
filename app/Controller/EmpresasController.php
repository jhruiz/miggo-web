<?php
App::uses('AppController', 'Controller');
App::uses ('ProductosController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Empresas Controller
 *
 * @property Empresa $Empresa
 * @property PaginatorComponent $Paginator
 */
class EmpresasController extends AppController {

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
            		
            $paginate = array();

            
            if(isset($this->passedArgs['nombre']) && $this->passedArgs['nombre'] != ""){
                $paginate['LOWER(Empresa.nombre) LIKE'] = '%' . strtolower($this->passedArgs['nombre']) . '%';
            }
            
            if(isset($this->passedArgs['nit']) && $this->passedArgs['nit'] != ""){
                $paginate['LOWER(Empresa.nit) LIKE'] = '%' . strtolower($this->passedArgs['nit']) . '%';
            }            
            
            $this->Empresa->recursive = 0;
            $this->set('empresas', $this->Paginator->paginate('Empresa', $paginate));
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
            $empresaId = $this->Auth->user('empresa_id');
            
            if($id == ""){
            	$id = $empresaId;
            }
            		
            $this->loadModel('Configuraciondato');
            if (!$this->Empresa->exists($id)) {
                    throw new NotFoundException(__('La empresa no existe.'));
            }
            $confDato = "urlImgEmpresa";
            $urlImagen = $this->Configuraciondato->obtenerValorDatoConfig($confDato);                
            
            $options = array('conditions' => array('Empresa.' . $this->Empresa->primaryKey => $id));
            $this->set('empresa', $this->Empresa->find('first', $options));
            $this->set(compact('urlImagen'));
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
                    $posData = $this->request->data;
                    $productos = new ProductosController();
                    
                    //Se obtiene la extension del archivo
                    $arrExt = split("\.", $posData['Empresa']['imagen']['name']);                
                    
                    $confDato = "dirImgEmpresa";
                    $nombreImg = "imgEmp_" . $posData['Empresa']['nit'];
                    $this->request->data['Empresa']['imagen'] = $nombreImg . "." . $arrExt['1'];                    
		
                    $this->Empresa->create();
                    if ($this->Empresa->save($this->request->data)) { 
                        /*Se obtiene el id del ultimo registro ingresado*/
                        $empresaId = $this->Empresa->getLastInsertID();
                        if($productos->subirArchivo($posData['Empresa']['imagen'], $confDato, $nombreImg, $empresaId, $this->Auth->user('id'))){
                            $this->Session->setFlash(__('La empresa ha sido guardada.'));
                            return $this->redirect(array('action' => 'index'));                                
                        }                            
                    } else {
                            $this->Session->setFlash(__('La empresa no pudo ser guardada. Por favor, inténtelo de nuevo.'));
                    }
		}
		$ciudades = $this->Empresa->Ciudade->find('list');
		$this->set(compact('ciudades'));
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
            		
		if (!$this->Empresa->exists($id)) {
			throw new NotFoundException(__('La empresa no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
                    
                    $posData = $this->request->data;
                    
                    $productos = new ProductosController();
                    
                    if(!empty($posData['Empresa']['imagen']['name'])){
                        //Se obtiene la extension del archivo
                        $arrExt = split("\.", $posData['Empresa']['imagen']['name']);                         
                        /*Se obtiene el id de la empresa*/
                        $empresaId = $id;
                        
                        $confDato = "dirImgEmpresa";
                        $nombreImg = "imgEmp_" . $posData['Empresa']['nit'];
                        $this->request->data['Empresa']['imagen'] = $nombreImg . "." . $arrExt['1'];                                             
                    }else{
                        unset($this->request->data['Empresa']['imagen']);
                    }

                    if ($this->Empresa->save($this->request->data)) {
                        if(!empty($posData['Empresa']['imagen']['name'])){
                            if($productos->subirArchivo($posData['Empresa']['imagen'], $confDato, $nombreImg, $empresaId, $this->Auth->user('id'))){
                                $this->Session->setFlash(__('La empresa ha sido guardada.'));
                                return $this->redirect(array('action' => 'view/' . $id));                                
                            }                             
                        }else{
                            $this->Session->setFlash(__('La empresa ha sido guardada.'));
                            return $this->redirect(array('action' => 'view/' . $id));                            
                        }
                       
                    } else {
                            $this->Session->setFlash(__('La empresa no pudo ser guardada. Por favor, inténtelo de nuevo.'));
                    }
		} else {
                    $options = array('conditions' => array('Empresa.' . $this->Empresa->primaryKey => $id));
                    $this->request->data = $this->Empresa->find('first', $options);
		}
		$ciudades = $this->Empresa->Ciudade->find('list');
		$this->set(compact('ciudades'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Empresa->id = $id;
		if (!$this->Empresa->exists()) {
			throw new NotFoundException(__('La empresa no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Empresa->delete()) {
			$this->Session->setFlash(__('La empresa ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La empresa no pudo ser eliminada. Por favor, inténtelo de nuevo.'));
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
