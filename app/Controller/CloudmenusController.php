<?php
App::uses('AppController', 'Controller');
App::uses ('ProductosController', 'Controller');
/**
 * Cloudmenus Controller
 *
 * @property Cloudmenu $Cloudmenu
 * @property PaginatorComponent $Paginator
 */
class CloudmenusController extends AppController {

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
            $paginate = array();
            if(isset($this->passedArgs['nombre']) && $this->passedArgs['nombre'] != ""){
                $paginate['LOWER(Cloudmenu.descripcion) LIKE'] = '%' . strtolower($this->passedArgs['nombre']) . '%';
            }
            $this->Cloudmenu->recursive = 0;
            $this->set('cloudmenus', $this->Paginator->paginate('Cloudmenu',$paginate));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
            
            $this->loadModel('Configuraciondato');
            
            if (!$this->Cloudmenu->exists($id)) {
                    throw new NotFoundException(__('El menú no existe.'));
            }
            
            $confDato = "urlImgMenu";
            $urlImagen = $this->Configuraciondato->obtenerValorDatoConfig($confDato);

            $options = array('conditions' => array('Cloudmenu.' . $this->Cloudmenu->primaryKey => $id));
            $this->set('cloudmenu', $this->Cloudmenu->find('first', $options));
            $this->set(compact('urlImagen'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
                    $posData = $this->request->data;
                    $productos = new ProductosController();
                    
                    $rutaMenu = "imgMenu";                       
                    
                    $confDato = "dirImgMenu";
                    $nombreImg = "lgM_" . $posData['Cloudmenu']['descripcion'];
                    $this->request->data['Cloudmenu']['imagen'] = $nombreImg . ".png";
                    
			$this->Cloudmenu->create();
			if ($this->Cloudmenu->save($this->request->data)) {
                            if($productos->subirArchivo($posData['Cloudmenu']['imagen'], $confDato, $nombreImg, $rutaMenu, $this->Auth->user('id'))){
				$this->Session->setFlash(__('El menú ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));                                
                            }
			} else {
				$this->Session->setFlash(__('El menú no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		}
		$perfiles = $this->Cloudmenu->Perfile->find('list');
		$this->set(compact('perfiles'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Cloudmenu->exists($id)) {
			throw new NotFoundException(__('El menú no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
                    $posData = $this->request->data;
                    $productos = new ProductosController();
                    
                    //Se obtiene la extension del archivo
                    $arrExt = split("\.", $posData['Cloudmenu']['imagen']['name']);   
                    
                    $rutaMenu = "imgMenu";                        
                    
                    $confDato = "dirImgMenu";
                    $nombreImg = "lgM_" . $posData['Cloudmenu']['descripcion'];
                    $this->request->data['Cloudmenu']['imagen'] = $nombreImg . "." . $arrExt['1'];
                    
			if ($this->Cloudmenu->save($this->request->data)) {
                            if($productos->subirArchivo($posData['Cloudmenu']['imagen'], $confDato, $nombreImg, $rutaMenu, $this->Auth->user('id'))){
				$this->Session->setFlash(__('El menú ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));                                
                            }
			} else {
				$this->Session->setFlash(__('El menú no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Cloudmenu.' . $this->Cloudmenu->primaryKey => $id));
			$this->request->data = $this->Cloudmenu->find('first', $options);
		}
		$perfiles = $this->Cloudmenu->Perfile->find('list');
		$this->set(compact('perfiles'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Cloudmenu->id = $id;
		if (!$this->Cloudmenu->exists()) {
			throw new NotFoundException(__('El menú no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Cloudmenu->delete()) {
			$this->Session->setFlash(__('El menú ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El menú no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
        
        
/**
 * Se obtiene el menu del usuario segun su perfil
 *
 * @throws NotFoundException
 * @param string POST
 * @return html
 */        
        public function obtenerMenuAjax(){
            $this->layout = 'ajax';
            $menuUsr = array();

            if ($this->request->is('post')) {
                $this->loadModel('Configuraciondato');
                $this->loadModel('Menuprincipale');
                
                ///Se obtiene el id del perfil del usuario que viene por post
                $perfilId = $this->request->data('perfiluser_id');
                
                /*Se obtiene la url del proyecto*/
                $datoConfP = "dirRutaPublica";
                $urlPublica = $this->Configuraciondato->obtenerValorDatoConfig($datoConfP); 
                
                /*Se obtiene la url las imagenes del menu*/
                $datoConf = "urlImgMenu";
                $urlImagMenu = $this->Configuraciondato->obtenerValorDatoConfig($datoConf);                

                /*se obtienen los items del menu sobre los cuales el usuario tiene permiso*/
                $this->loadModel('CloudmenusPerfile');           
                $menuUsr = $this->Menuprincipale->obtenerMenuPorPerfil($perfilId);
                
                $arrMenu = [];
                foreach ($menuUsr as $key => $mu){
                    $arrMenu[$mu['Menuprincipale']['id']]['data']['id'] = $mu['Menuprincipale']['id'];
                    $arrMenu[$mu['Menuprincipale']['id']]['data']['descripcion'] = $mu['Menuprincipale']['descripcion'];
                    $arrMenu[$mu['Menuprincipale']['id']]['data']['clase_icon'] = $mu['Menuprincipale']['clase_icon'];
                    $arrMenu[$mu['Menuprincipale']['id']]['submenu'][$key]['id'] = $mu['C']['id']; 
                    $arrMenu[$mu['Menuprincipale']['id']]['submenu'][$key]['descripcion'] = $mu['C']['descripcion']; 
                    $arrMenu[$mu['Menuprincipale']['id']]['submenu'][$key]['url'] = $mu['C']['url']; 
                    $arrMenu[$mu['Menuprincipale']['id']]['submenu'][$key]['ayuda'] = $mu['C']['ayuda'];                             
                }
                $this->set(compact('arrMenu', 'urlPublica', 'urlImagMenu'));
            }      
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
