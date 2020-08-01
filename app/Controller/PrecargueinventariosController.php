<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Precargueinventarios Controller
 *
 * @property Precargueinventario $Precargueinventario
 * @property PaginatorComponent $Paginator
 */
class PrecargueinventariosController extends AppController {

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
            	
            $this->loadModel('Deposito');              
            $this->loadModel('Proveedore');
            $this->loadModel('Tipopago');
            $this->loadModel('Tipopagopago');
            
            /*Se obtiene la empresa del usuario en sesion*/
            $arrEmpresa = $this->Auth->user('Empresa');
            
            /*Se obtiene el usuario que se encuentra gestionando el inventario para mostrar sólo sus productos*/
            $usuarioId = $this->Auth->user('id');
            $arrInfoPreCargue = $this->Precargueinventario->obtenerPrecargueUsuario($usuarioId);
            $costoProducto = 0;
            $totalProductos = 0;
            $ttalPrecioMax = 0;
            $ttalPrecioMin = 0;
            $ttalPrecioVenta = 0;
            $costoTotal = 0;
            for($i = 0; $i < count($arrInfoPreCargue); $i++){
                $arrInfoPreCargue[$i]['Precargueinventario']['costototal'] = $arrInfoPreCargue[$i]['Precargueinventario']['cantidad'] * $arrInfoPreCargue[$i]['Precargueinventario']['costoproducto'];                
                $costoProducto +=  $arrInfoPreCargue[$i]['Precargueinventario']['costoproducto'];
                $totalProductos +=  $arrInfoPreCargue[$i]['Precargueinventario']['cantidad'];
                $ttalPrecioMax +=  $arrInfoPreCargue[$i]['Precargueinventario']['preciomaximo'];
                $ttalPrecioMin +=  $arrInfoPreCargue[$i]['Precargueinventario']['preciominimo'];
                $ttalPrecioVenta +=  $arrInfoPreCargue[$i]['Precargueinventario']['precioventa'];
                $costoTotal +=  $arrInfoPreCargue[$i]['Precargueinventario']['costototal'];                
            }
            /*Cantidad de items*/
            $cantItems = $i;
            
            /*Se obtiene el listado de depósitos*/
            $listDepositos = $this->Deposito->obtenerListaDepositosUsuario($usuarioId);
            
            /*Se obtiene el listado de proveedores de la empresa*/
            $listProveedores = $this->Proveedore->obtenerProveedoresEmpresa($arrEmpresa['id']);
            
            /*Se obtiene el listado de los tipos de pago*/
            $listTipoPago = $this->Tipopagopago->obtenerListaTiposPagos();
            
            $this->set(compact('arrInfoPreCargue', 'costoProducto', 'totalProductos', 'ttalPrecioMax', 'ttalPrecioMin', 'ttalPrecioVenta', 'costoTotal'));            
            $this->set(compact('listDepositos', 'listProveedores', 'listTipoPago', 'cantItems'));
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
            	
            /*se reagistra la actividad del uso de la aplicacion*/
            $usuariosController = new UsuariosController();
            $usuarioAct = $this->Auth->user('id');
            $usuariosController->registraractividad($usuarioAct);
            	
		if (!$this->Precargueinventario->exists($id)) {
			throw new NotFoundException(__('Invalid precargueinventario'));
		}
		$options = array('conditions' => array('Precargueinventario.' . $this->Precargueinventario->primaryKey => $id));
		$this->set('precargueinventario', $this->Precargueinventario->find('first', $options));
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
            	
            /*se reagistra la actividad del uso de la aplicacion*/
            $usuariosController = new UsuariosController();
            $usuarioAct = $this->Auth->user('id');
            $usuariosController->registraractividad($usuarioAct);
            	
		if ($this->request->is('post')) {
                    
			$this->Precargueinventario->create();
			if ($this->Precargueinventario->save($this->request->data)) {
				$this->Session->setFlash(__('The precargueinventario has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The precargueinventario could not be saved. Please, try again.'));
			}
		}
		$productos = $this->Precargueinventario->Producto->find('list');
		$depositos = $this->Precargueinventario->Deposito->find('list');
		$usuarios = $this->Precargueinventario->Usuario->find('list');
		$estados = $this->Precargueinventario->Estado->find('list');
		$proveedores = $this->Precargueinventario->Proveedore->find('list');
		$tipopagos = $this->Precargueinventario->Tipopago->find('list');
		$this->set(compact('productos', 'depositos', 'usuarios', 'estados', 'proveedores', 'tipopagos'));
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
            	
            /*se reagistra la actividad del uso de la aplicacion*/
            $usuariosController = new UsuariosController();
            $usuarioAct = $this->Auth->user('id');
            $usuariosController->registraractividad($usuarioAct);
            	
		if (!$this->Precargueinventario->exists($id)) {
			throw new NotFoundException(__('Invalid precargueinventario'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Precargueinventario->save($this->request->data)) {
				$this->Session->setFlash(__('The precargueinventario has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The precargueinventario could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Precargueinventario.' . $this->Precargueinventario->primaryKey => $id));
			$this->request->data = $this->Precargueinventario->find('first', $options);
		}
		$productos = $this->Precargueinventario->Producto->find('list');
		$depositos = $this->Precargueinventario->Deposito->find('list');
		$usuarios = $this->Precargueinventario->Usuario->find('list');
		$estados = $this->Precargueinventario->Estado->find('list');
		$proveedores = $this->Precargueinventario->Proveedore->find('list');
		$tipopagos = $this->Precargueinventario->Tipopago->find('list');
		$this->set(compact('productos', 'depositos', 'usuarios', 'estados', 'proveedores', 'tipopagos'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Precargueinventario->id = $id;
                $this->loadModel('PrecargueinventariosImpuesto');
		if (!$this->Precargueinventario->exists()) {
			throw new NotFoundException(__('El producto no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
                $this->PrecargueinventariosImpuesto->deleteAll(array('PrecargueinventariosImpuesto.precargueinventario_id' => $id), false);
		if ($this->Precargueinventario->delete()) {                    
			$this->Session->setFlash(__('El producto ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El producto no pudo ser eliminado. Por favor, int��ntelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
        
/**
 * precargarinventario method
 *
 * @throws NotFoundException
 * @param Post
 * @return void
 */        
        public function precargarinventario(){
                    
            $this->loadModel('PrecargueinventariosImpuesto');            
            $this->autoRender = false;            
            $posData = $this->request->data;  
            $posData['Cargueinventario']['costoproducto'] = str_replace(",", "", $posData['Cargueinventario']['costoproducto']); 
            $posData['Cargueinventario']['preciomaximo'] = str_replace(",", "", $posData['Cargueinventario']['preciomaximo']); 
            $posData['Cargueinventario']['preciominimo'] = str_replace(",", "", $posData['Cargueinventario']['preciominimo']); 
            $posData['Cargueinventario']['precioventa'] = str_replace(",", "", $posData['Cargueinventario']['precioventa']); 
       
            $preCargueId = $this->Precargueinventario->guardarPreCargueInventario($posData);
            
            foreach ($posData['impuestos'] as $imp){
                $this->PrecargueinventariosImpuesto->guardarPrecargueImpuesto($preCargueId,$imp);
            }           
        }
        
        public function actualizardepositoajax(){
            $this->autoRender = false;
            $posData = $this->request->data;
            $preCargueId = $posData['preCargueId'];
            $depositoId = $posData['depositoId'];
            $this->Precargueinventario->id = $preCargueId;
            $this->Precargueinventario->save(array('deposito_id' =>$depositoId));
        }
        
        public function actualizarvalorunitarioajax(){
            $this->autoRender = false;
            $posData = $this->request->data;
            $preCargueId = $posData['preCargueId'];
            $valorUnit = $posData['valUnit'];
            $this->Precargueinventario->id = $preCargueId;
            $this->Precargueinventario->save(array('costoproducto' =>$valorUnit));            
        }
        
        public function actualizarcantidadajax(){
            $this->autoRender = false;
            $posData = $this->request->data;
            $preCargueId = $posData['preCargueId'];
            $cantidad = $posData['cantidad'];
            $this->Precargueinventario->id = $preCargueId;
            $this->Precargueinventario->save(array('cantidad' =>$cantidad));            
        }
        
        public function actualizarvalormaximoajax(){
            $this->autoRender = false;
            $posData = $this->request->data;
            $preCargueId = $posData['preCargueId'];
            $valMaximo = $posData['valMaximo'];
            $this->Precargueinventario->id = $preCargueId;
            $this->Precargueinventario->save(array('preciomaximo' =>$valMaximo));             
        }
        
        public function actualizarvalorminimoajax(){
            $this->autoRender = false;
            $posData = $this->request->data;
            $preCargueId = $posData['preCargueId'];
            $valMinimo = $posData['valMinimo'];
            echo $valMinimo;
            $this->Precargueinventario->id = $preCargueId;
            $this->Precargueinventario->save(array('preciominimo' =>$valMinimo));             
        }
        
        public function actualizarprecioventaajax(){
            $this->autoRender = false;
            $posData = $this->request->data;
            $preCargueId = $posData['preCargueId'];
            $precioVenta = $posData['precioVenta'];
            $this->Precargueinventario->id = $preCargueId;
            $this->Precargueinventario->save(array('precioventa' =>$precioVenta));            
        }
        
        public function actualizarproveedorajax(){
            $this->autoRender = false;
            $posData = $this->request->data;
            $preCargueId = $posData['preCargueId'];
            $proveedorId = $posData['proveedorId'];
            $this->Precargueinventario->id = $preCargueId;
            $this->Precargueinventario->save(array('proveedore_id' =>$proveedorId));            
        }
        
        public function actualizartipopagoajax(){
            $this->autoRender = false;
            $posData = $this->request->data;
            $preCargueId = $posData['preCargueId'];
            $tipopagoId = $posData['tipopagoId'];
            $this->Precargueinventario->id = $preCargueId;
            $this->Precargueinventario->save(array('tipopago_id' =>$tipopagoId));            
        }
        
        public function actualizarnumerofacturaajax(){
            $this->autoRender = false;
            $posData = $this->request->data;
            $preCargueId = $posData['preCargueId'];
            $numFact = $posData['numFact'];
            $this->Precargueinventario->id = $preCargueId;
            $this->Precargueinventario->save(array('numerofactura' =>$numFact));             
        }
}
