<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Descargueinventarios Controller
 *
 * @property Descargueinventario $Descargueinventario
 * @property PaginatorComponent $Paginator
 */
class DescargueinventariosController extends AppController {

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
            $usuarioId = $this->Auth->user('id');
            $depositos = $this->Descargueinventario->Deposito->obtenerDepositoEmpresa($empresaId);
            $this->set(compact('empresaId', 'depositos', 'usuarioId'));
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
            	
		if (!$this->Descargueinventario->exists($id)) {
			throw new NotFoundException(__('Invalid descargueinventario'));
		}
		$options = array('conditions' => array('Descargueinventario.' . $this->Descargueinventario->primaryKey => $id));
		$this->set('descargueinventario', $this->Descargueinventario->find('first', $options));
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
			$this->Descargueinventario->create();
			if ($this->Descargueinventario->save($this->request->data)) {
				$this->Session->setFlash(__('The descargueinventario has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The descargueinventario could not be saved. Please, try again.'));
			}
		}
		$productos = $this->Descargueinventario->Producto->find('list');
		$depositos = $this->Descargueinventario->Deposito->find('list');
		$usuarios = $this->Descargueinventario->Usuario->find('list');
		$this->set(compact('productos', 'depositos', 'usuarios'));
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
            	
		if (!$this->Descargueinventario->exists($id)) {
			throw new NotFoundException(__('Invalid descargueinventario'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Descargueinventario->save($this->request->data)) {
				$this->Session->setFlash(__('The descargueinventario has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The descargueinventario could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Descargueinventario.' . $this->Descargueinventario->primaryKey => $id));
			$this->request->data = $this->Descargueinventario->find('first', $options);
		}
		$productos = $this->Descargueinventario->Producto->find('list');
		$depositos = $this->Descargueinventario->Deposito->find('list');
		$usuarios = $this->Descargueinventario->Usuario->find('list');
		$this->set(compact('productos', 'depositos', 'usuarios'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete() {
            $this->autoRender = false;
            $posData = $this->request->data;
            $descargueId = $posData['descargueId'];
            
            /*se obtiene la informacion del detalle del descargue de inventario*/
            $arrDetDescargue = $this->Descargueinventario->infoDescargueInventario($descargueId);
            
            /*se actualiza el stock con el registro eliminado*/
            if($this->actalizarcantidadpredelete($arrDetDescargue)){
                $detalleId['Descargueinventario.id'] = $descargueId;
                if($this->Descargueinventario->delete($detalleId)){
                    $resp = true;
                }else{
                    $resp = false;                
                }                
            }else{
                $resp = false;
            }            
            echo json_encode(array('resp' => $resp)); 
	}
        
        /*
         * Se obtienen todos los productos que concuerden con la descripcion del campo de busqueda autocomplete
         */
        public function ajaxProductoDescargueInventario(){
            $this->loadModel('Producto');            
            $this->autoRender = false;
            $posData = $this->request->data;
            $descProducto = $posData['descProducto']; 
            $empresaId = $posData['empresaId'];
            $depositoId = $posData['depositoId'];
            /*Se obtienen los productos por descripcion que se encuentran en el stock*/            
            $arrProductos = $this->Producto->obtenerProductoDescargueInventario($descProducto,$empresaId,$depositoId);            
            echo json_encode(array('resp' => $arrProductos));             
        }
        
        
        /*
         * Se obtiene el producto seleccionado por el usuario en el filtro de autocompletar
         */
        public function ajaxOtenerProductoDescargue(){
            $this->loadModel('Cargueinventario');            
            $this->autoRender = false;
            $posData = $this->request->data;
            $productoId = $posData['productoId']; 
            $usuarioId = $posData['usuarioId'];
            $depositoId = $posData['deposito_id'];
            
            /*Se obtienen los productos id que se encuentran en el stock*/            
            $arrProductos = $this->Cargueinventario->obtenerCargueInventarioProdDep($productoId, $depositoId);
            $cantidadProd = '1';
            
            /*Se resta la cantidad del stock*/
            $cantidadActual = $arrProductos['Cargueinventario']['existenciaactual']; 
            
            if($cantidadActual <= '0'){
                echo json_encode(array('boolResp' => '1'));
            }else{
                
                $cantidadFinal = $cantidadActual - $cantidadProd;
                $this->Cargueinventario->actalizarExistenciaStock($arrProductos['Cargueinventario']['id'], $cantidadFinal);
                
                /*Se registra el producto a descargar del inventario*/
                $descId = $this->Descargueinventario->guardarProductoDescargue($productoId,$arrProductos['Cargueinventario']['deposito_id'],$cantidadProd,$usuarioId,$arrProductos['Cargueinventario']['empresa_id']);

                if($descId != '0'){
                    echo json_encode(array('boolResp' => '2', 'resp' => $arrProductos, 'descId' => $descId));             
                }else{
                    echo json_encode(array('boolResp' => '3'));
                }                   
            }
        }
        
        /*
         * Se obtienen los registros de descargueinventario al acceder al 
         * módulo de descargue de productos
         */
        public function obtenerProductosDescargue(){          
            $this->autoRender = false;
            $posData = $this->request->data;
            $empresaId = $posData['empresaId'];   
            
            /*Se obtienen los productos que se habian agregado al */
            $descargueInventario = $this->Descargueinventario->obtenerDescargueInventario($empresaId);

            if(count($descargueInventario) > 0){
                echo json_encode(array('resp' => true, 'detDesc' => $descargueInventario));
            }else{
                echo json_encode(array('resp' => false));
            }            
        }
        
        /*
         * Al cambiar la cantidad en el campo cantidad del formulario,
         * se actualiza la informacion de descargue y la cantidad en stock
         */
        public function actualizarCantidadDescargue(){
            $this->loadModel('Cargueinventario');
            $this->autoRender = false;
            $posData = $this->request->data;
            $cantidad = $posData['cantidad'];
            $descargueId = $posData['descId'];
            
            if(!$this->validarDescargueInventario($descargueId, $cantidad)){
                 echo json_encode(array('resp' => '1'));
            }else{
                /*Se obtiene el producto que se habian agregado al descargue del inventario*/
                $descargueActual = $this->Descargueinventario->infoDescargueInventario($descargueId);                
                
                /*Info actual en stock del producto*/
                $arrProductos = $this->Cargueinventario->obtenerCargueInventarioProdDep($descargueActual['Descargueinventario']['producto_id'],
                        $descargueActual['Descargueinventario']['deposito_id']);
                
                $cantidadDescActual = $descargueActual['Descargueinventario']['cantidaddescargue'];
                $cantidadActualStock = $arrProductos['Cargueinventario']['existenciaactual'];
                
                $cantidadRealStock = $cantidadDescActual + $cantidadActualStock;
                
                $cantidadFinalStock = $cantidadRealStock - $cantidad;
                
                $this->Cargueinventario->actalizarExistenciaStock($arrProductos['Cargueinventario']['id'], $cantidadFinalStock);
                
                $descargueInventario = $this->Descargueinventario->actualizarCantidadDescargue($descargueId,$cantidad);               
                
                if(count($descargueInventario)){
                    echo json_encode(array('resp' => '2'));
                }else{
                    echo json_encode(array('resp' => '3'));
                }                                  
            }
        }
        
        
        /*
         * Validar si existe la cantidad suficiente en stock para realizar el descargue del producto
         */
        public function validarDescargueInventario($descargueId, $cantidad){
            $this->loadModel('Cargueinventario');
            
            /*Se obtiene el cargue de inventario actual para garantizar que el descargue es viable en cantidad*/
            $infoDescargue = $this->Descargueinventario->infoDescargueInventario($descargueId);
            $productoId = $infoDescargue['Descargueinventario']['producto_id']; 
            $depositoId = $infoDescargue['Descargueinventario']['deposito_id'];
            
            $infoCargueInventario = $this->Cargueinventario->obtenerCargueInventarioProdDep($productoId, $depositoId);
            $existenciaActual = $infoCargueInventario['Cargueinventario']['existenciaactual'];
            $existenciaDescargue = $infoDescargue['Descargueinventario']['cantidaddescargue'];
            $existenciaReal = $existenciaActual + $existenciaDescargue;

            if($existenciaReal < $cantidad){
                return false;
            }else{
                return true;
            }
        }
        
        /*
         * Se actualiza la cantidad a descargar cuando se modifica el input de cantidad
         */
        public function actalizarcantidadpredelete($arrDetDescargue){
            $this->loadModel('Cargueinventario');
            /*se obtiene la informacion del stock*/
            $infoCargueInventario = $this->Cargueinventario->obtenerCargueInventarioProdDep($arrDetDescargue['Descargueinventario']['producto_id'], $arrDetDescargue['Descargueinventario']['deposito_id']);
            $existenciaStock =  $infoCargueInventario['Cargueinventario']['existenciaactual'];
            $cantidadDescargue = $arrDetDescargue['Descargueinventario']['cantidaddescargue'];
            $existenciaFinal = $existenciaStock + $cantidadDescargue;
            if($this->Cargueinventario->actalizarExistenciaStock($infoCargueInventario['Cargueinventario']['id'], $existenciaFinal)){
                return true;
            }else{
                return false;
            }            
        }
        
        /*
         * Se obtienen los productos a descargar seleccionado 
         * por medio del codigo de barras
         */
        public function ajaxProductoDesargueBarcode(){
            $this->loadModel('Cargueinventario');            
            $this->autoRender = false;
            $posData = $this->request->data;
            $descProducto = $posData['descProducto']; 
            $depositoId = $posData['depositoId'];
            $usuarioId = $posData['usuarioId'];
            
            /*Se obtiene el producto por codigo de barras y deposito*/            
            $arrProductos = $this->Cargueinventario->obtenerProductosStock($depositoId, $descProducto);
            $cantidadProd = '1';
            
            /*Se resta la cantidad del stock*/
            $cantidadActual = $arrProductos['0']['Cargueinventario']['existenciaactual'];            
            
            if($cantidadActual == '0'){
                echo json_encode(array('boolResp' => '1'));
            }else{                
                $cantidadFinal = $cantidadActual - $cantidadProd;
                $this->Cargueinventario->actalizarExistenciaStock($arrProductos['0']['Cargueinventario']['id'], $cantidadFinal);
                
                /*Se registra el producto a descargar del inventario*/
                $descId = $this->Descargueinventario->guardarProductoDescargue($arrProductos['0']['Producto']['id'],$arrProductos['0']['Cargueinventario']['deposito_id'],$cantidadProd,$usuarioId,$arrProductos['0']['Cargueinventario']['empresa_id']);

                if($descId != '0'){
                    echo json_encode(array('boolResp' => '2', 'resp' => $arrProductos, 'descId' => $descId));             
                }else{
                    echo json_encode(array('boolResp' => '3'));
                }                   
            }
        }        
}
