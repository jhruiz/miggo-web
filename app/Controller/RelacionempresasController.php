<?php
App::uses('AppController', 'Controller');
App::uses ('ProductosController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Relacionempresas Controller
 *
 * @property Relacionempresa $Relacionempresa
 * @property PaginatorComponent $Paginator
 */
class RelacionempresasController extends AppController {

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
            $paginate['Relacionempresa.empresa_id'] = $empresaId;
            $this->Relacionempresa->recursive = 0;
            $this->set('relacionempresas', $this->Paginator->paginate('Relacionempresa',$paginate));
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
            	
            $this->loadModel('Configuraciondato');
            if (!$this->Relacionempresa->exists($id)) {
                    throw new NotFoundException(__('La empresa relacionada no existe.'));
            }
            $confDato = "urlImgEmpresa";
            $urlImagen = $this->Configuraciondato->obtenerValorDatoConfig($confDato);             
            $options = array('conditions' => array('Relacionempresa.' . $this->Relacionempresa->primaryKey => $id));
            $this->set('relacionempresa', $this->Relacionempresa->find('first', $options));
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
                $arrExt = explode("\.", $posData['Relacionempresa']['imagen']['name']); 

                $confDato = "dirImgEmpresa";
                $nombreImg = "imgEmp_" . $posData['Relacionempresa']['nit'];
                $this->request->data['Relacionempresa']['imagen'] = $nombreImg . "." . $arrExt['1'];                     

                $this->Relacionempresa->create();
                if ($this->Relacionempresa->save($this->request->data)) {
                    /*Se obtiene el id del ultimo registro ingresado*/
                    $empresaId = $posData['Relacionempresa']['empresa_id'];     
                    if($productos->subirArchivo($posData['Relacionempresa']['imagen'], $confDato, $nombreImg, $empresaId, $this->Auth->user('id'))){
                        $this->Session->setFlash(__('Se ha relacionado la información.'));
                        return $this->redirect(array('action' => 'index'));                               
                    }                        
                } else {
                        $this->Session->setFlash(__('La iformación ha relacionar no pudo ser guardada. Por favor, inténtelo de nuevo.'));
                }
            }
//		$empresas = $this->Relacionempresa->Empresa->find('list');
            $empresas = $this->Auth->user('empresa_id');
            $this->set(compact('empresas'));
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
            	
		if (!$this->Relacionempresa->exists($id)) {
			throw new NotFoundException(__('La empresa relacionada no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
                    
                    $posData = $this->request->data;
                    $productos = new ProductosController();

                    //Se obtiene la extension del archivo
                    $arrExt = explode("\.", $posData['Relacionempresa']['imagen']['name']); 

                    /*Se obtiene el id de la empresa*/
                    $empresaId = $posData['Relacionempresa']['empresa_id'];

                    $confDato = "dirImgEmpresa";
                    $nombreImg = "imgEmp_" . $posData['Relacionempresa']['nit'];
                    
                    if(count($arrExt) > '1'){
                        $this->request->data['Relacionempresa']['imagen'] = $nombreImg . "." . $arrExt['1']; 
                    }else{
                        $this->request->data['Relacionempresa']['imagen'] = "";
                    }
                    if ($this->Relacionempresa->save($this->request->data)) {
                        if(count($arrExt) > '1'){
                            if($productos->subirArchivo($posData['Relacionempresa']['imagen'], $confDato, $nombreImg, $empresaId, $this->Auth->user('id'))){
                                $this->Session->setFlash(__('Se ha relacionado la información.'));
                                return $this->redirect(array('action' => 'index'));                               
                            }                            
                        }else{
                            $this->Session->setFlash(__('Se ha relacionado la información.'));
                            return $this->redirect(array('action' => 'index')); 
                        }
                     
                    } else {
                            $this->Session->setFlash(__('La iformación ha relacionar no pudo ser guardada. Por favor, inténtelo de nuevo.'));
                    }
		} else {
                    $options = array('conditions' => array('Relacionempresa.' . $this->Relacionempresa->primaryKey => $id));
                    $this->request->data = $this->Relacionempresa->find('first', $options);
		}
		$empresas = $this->Relacionempresa->Empresa->find('list');
		$this->set(compact('empresas'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Relacionempresa->id = $id;
		if (!$this->Relacionempresa->exists()) {
			throw new NotFoundException(__('La empresa relacionada no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Relacionempresa->delete()) {
			$this->Session->setFlash(__('La información relacionada ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La información relacionada no pudo ser guardada. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
