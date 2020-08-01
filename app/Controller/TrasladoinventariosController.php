<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Trasladoinventarios Controller
 *
 * @property Trasladoinventario $Trasladoinventario
 * @property PaginatorComponent $Paginator
 */
class TrasladoinventariosController extends AppController {

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
            	            		
            $this->loadModel('Descargueinventario');
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
            		
		if (!$this->Trasladoinventario->exists($id)) {
			throw new NotFoundException(__('Invalid trasladoinventario'));
		}
		$options = array('conditions' => array('Trasladoinventario.' . $this->Trasladoinventario->primaryKey => $id));
		$this->set('trasladoinventario', $this->Trasladoinventario->find('first', $options));
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
			$this->Trasladoinventario->create();
			if ($this->Trasladoinventario->save($this->request->data)) {
				$this->Session->setFlash(__('The trasladoinventario has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The trasladoinventario could not be saved. Please, try again.'));
			}
		}
		$productos = $this->Trasladoinventario->Producto->find('list');
		$usuarios = $this->Trasladoinventario->Usuario->find('list');
		$empresas = $this->Trasladoinventario->Empresa->find('list');
		$this->set(compact('productos', 'depositoorigens', 'depositodestinos', 'usuarios', 'empresas'));
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
            		
		if (!$this->Trasladoinventario->exists($id)) {
			throw new NotFoundException(__('Invalid trasladoinventario'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Trasladoinventario->save($this->request->data)) {
				$this->Session->setFlash(__('The trasladoinventario has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The trasladoinventario could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Trasladoinventario.' . $this->Trasladoinventario->primaryKey => $id));
			$this->request->data = $this->Trasladoinventario->find('first', $options);
		}
		$productos = $this->Trasladoinventario->Producto->find('list');
		$usuarios = $this->Trasladoinventario->Usuario->find('list');
		$empresas = $this->Trasladoinventario->Empresa->find('list');
		$this->set(compact('productos', 'depositoorigens', 'depositodestinos', 'usuarios', 'empresas'));
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
            $trasladoId = $posData['trasladoId'];
            
            /*se obtiene la informacion del detalle del traslado de inventario*/
            $arrDetDescargue = $this->Trasladoinventario->infoTrasladoInventario($trasladoId);
            
            /*se actualiza el stock con el registro eliminado*/
            if($this->actalizarcantidadpredelete($arrDetDescargue)){
                $detalleId['Trasladoinventario.id'] = $trasladoId;
                if($this->Trasladoinventario->delete($detalleId)){
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
         * Se actualiza la cantidad a descargar cuando se modifica el input de cantidad
         */
        public function actalizarcantidadpredelete($arrDetTraslado){
            $this->loadModel('Cargueinventario');
            /*se obtiene la informacion del stock*/
            $infoCargueInventario = $this->Cargueinventario->obtenerCargueInventarioProdDep($arrDetTraslado['Trasladoinventario']['producto_id'], $arrDetTraslado['Trasladoinventario']['depositoorigen_id']);
            $existenciaStock =  $infoCargueInventario['Cargueinventario']['existenciaactual'];
            $cantidadTraslado = $arrDetTraslado['Trasladoinventario']['cantidadtraslado'];
            $existenciaFinal = $existenciaStock + $cantidadTraslado;
            if($this->Cargueinventario->actalizarExistenciaStock($infoCargueInventario['Cargueinventario']['id'], $existenciaFinal)){
                return true;
            }else{
                return false;
            }            
        }        
        
        /*
         * Se obtienen los registros de trasladoinventario al acceder al 
         * módulo de traslado de inventario
         */
        public function obtenerProductosTraslado(){          
            $this->autoRender = false;
            $posData = $this->request->data;
            $empresaId = $posData['empresaId'];   
            
            /*Se obtienen los productos que se habian agregado al traslado de deposito*/
            $trasladoInventario = $this->Trasladoinventario->obtenerTrasladoInventario($empresaId);

            if(count($trasladoInventario) > 0){
                echo json_encode(array('resp' => true, 'detTras' => $trasladoInventario));
            }else{
                echo json_encode(array('resp' => false));
            }            
        } 
                
        /*
         * Se obtienen todos los productos que concuerden con la descripcion del campo de busqueda autocomplete
         */
        public function ajaxProductoTrasladoInventario(){
            $this->loadModel('Producto');            
            $this->autoRender = false;
            $posData = $this->request->data;
            $descProducto = strtolower($posData['descProducto']); 
            $empresaId = $posData['empresaId'];
            $depositoId = $posData['depositoorigenId'];
            /*Se obtienen los productos por descripcion que se encuentran en el stock*/            
            $arrProductos = $this->Producto->obtenerProductoDescargueInventario($descProducto,$empresaId,$depositoId);            
            echo json_encode(array('resp' => $arrProductos));             
        }    
        
        /*
         * Se obtiene el producto seleccionado por el usuario en el filtro de autocompletar
         */
        public function ajaxOtenerProductoTraslado(){
            $this->loadModel('Cargueinventario');            
            $this->autoRender = false;
            $posData = $this->request->data;
            $productoId = $posData['productoId']; 
            $usuarioId = $posData['usuarioId'];
            $depositoOrigen = $posData['depositoOrigen'];
            $depositoDestino = $posData['depositoDestino'];
            
            /*Se obtienen los productos id que se encuentran en el stock en el deposito de origen*/            
            $arrProductos = $this->Cargueinventario->obtenerCargueInventarioProdDep($productoId,$depositoOrigen);
            $cantidadProd = '1';
            
            /*Se resta la cantidad del stock*/
            $cantidadActual = $arrProductos['Cargueinventario']['existenciaactual'];            
            
            if($cantidadActual == '0'){
                echo json_encode(array('boolResp' => '1'));
            }else{
                
                $cantidadFinal = $cantidadActual - $cantidadProd;
                $this->Cargueinventario->actalizarExistenciaStock($arrProductos['Cargueinventario']['id'], $cantidadFinal);
                
                /*Se registra el producto a trasladar del inventario*/
                $descId = $this->Trasladoinventario->guardarProductoTraslado($productoId,$depositoOrigen,$depositoDestino,$cantidadProd,$usuarioId,$arrProductos['Cargueinventario']['empresa_id']);

                if($descId != '0'){
                    echo json_encode(array('boolResp' => '2', 'resp' => $arrProductos, 'descId' => $descId));             
                }else{
                    echo json_encode(array('boolResp' => '3'));
                }                   
            }
        }
        
        
        /*
         * Se obtienen los productos a descargar seleccionado 
         * por medio del codigo de barras
         */
        public function ajaxProductoTrasladoBarcode(){
            $this->loadModel('Cargueinventario');            
            $this->autoRender = false;
            $posData = $this->request->data;
            $descProducto = $posData['descProducto']; 
            $depositoorigenId = $posData['depositoorigenId'];
            $depositodestinoId = $posData['depositodestinoId'];
            $usuarioId = $posData['usuarioId'];
            
            /*Se obtiene el producto por codigo de barras y deposito*/            
            $arrProductos = $this->Cargueinventario->obtenerProductosStock($depositoorigenId, $descProducto);
            $cantidadProd = '1';
            
            /*Se resta la cantidad del stock*/
            $cantidadActual = $arrProductos['0']['Cargueinventario']['existenciaactual'];            
            
            if($cantidadActual == '0'){
                echo json_encode(array('boolResp' => '1'));
            }else{                
                $cantidadFinal = $cantidadActual - $cantidadProd;
                $this->Cargueinventario->actalizarExistenciaStock($arrProductos['0']['Cargueinventario']['id'], $cantidadFinal);
                
                /*Se registra el producto a descargar del inventario*/
                $descId = $this->Trasladoinventario->guardarProductoTraslado($arrProductos['0']['Producto']['id'],$arrProductos['0']['Cargueinventario']['deposito_id'],$depositodestinoId,$cantidadProd,$usuarioId,$arrProductos['0']['Cargueinventario']['empresa_id']);

                if($descId != '0'){
                    echo json_encode(array('boolResp' => '2', 'resp' => $arrProductos, 'descId' => $descId));             
                }else{
                    echo json_encode(array('boolResp' => '3'));
                }                   
            }
        }    
        
        
        /*
         * Al cambiar la cantidad en el campo cantidad del formulario,
         * se actualiza la informacion de traslado y la cantidad en stock
         */
        public function actualizarCantidadTraslado(){
            $this->loadModel('Cargueinventario');
            $this->autoRender = false;
            $posData = $this->request->data;
            $cantidad = $posData['cantidad'];
            $trasladoId = $posData['descId'];
            
            if(!$this->validarTrasladoInventario($trasladoId, $cantidad)){
                 echo json_encode(array('resp' => '1'));
            }else{
                /*Se obtiene el producto que se habian agregado al descargue del inventario*/
                $trasladoActual = $this->Trasladoinventario->infoTrasladoInventario($trasladoId);
                
                /*Info actual en stock del producto*/
                $arrProductos = $this->Cargueinventario->obtenerCargueInventarioProdDep($trasladoActual['Trasladoinventario']['producto_id'],$trasladoActual['Trasladoinventario']['depositoorigen_id']);
                
                $cantidadTrasActual = $trasladoActual['Trasladoinventario']['cantidadtraslado'];
                $cantidadActualStock = $arrProductos['Cargueinventario']['existenciaactual'];
                
                $cantidadRealStock = $cantidadTrasActual + $cantidadActualStock;
                
                $cantidadFinalStock = $cantidadRealStock - $cantidad;
                
                $this->Cargueinventario->actalizarExistenciaStock($arrProductos['Cargueinventario']['id'], $cantidadFinalStock);
                
                $trasladoInventario = $this->Trasladoinventario->actualizarCantidadTraslado($trasladoId,$cantidad);
                
                if(count($trasladoInventario)){
                    echo json_encode(array('resp' => '2'));
                }else{
                    echo json_encode(array('resp' => '3'));
                }                                  
            }
        }        
        
        /*
         * Validar si existe la cantidad suficiente en stock para realizar el traslado del producto
         */
        public function validarTrasladoInventario($trasladoId, $cantidad){
            $this->loadModel('Cargueinventario');
            
            /*Se obtiene el cargue de inventario actual para garantizar que el traslado es viable en cantidad*/
            $infoTraslado = $this->Trasladoinventario->infoTrasladoInventario($trasladoId);
            $productoId = $infoTraslado['Trasladoinventario']['producto_id']; 
            $depositoId = $infoTraslado['Trasladoinventario']['depositoorigen_id'];
            
            $infoCargueInventario = $this->Cargueinventario->obtenerCargueInventarioProdDep($productoId, $depositoId);
            
            $existenciaActual = $infoCargueInventario['Cargueinventario']['existenciaactual'];
            $existenciaTraslado = $infoTraslado['Trasladoinventario']['cantidadtraslado'];
            $existenciaReal = $existenciaActual + $existenciaTraslado;

            if($existenciaReal < $cantidad){
                return false;
            }else{
                return true;
            }
        }        
        
         /*
         * Tras aprobar el traslado del inventario, se guarda el mismo        
         */    
        public function guardartrasladoinventarioajax(){
            $this->loadModel('Usuario');
            $this->loadModel('Detalledocumento');
            $this->loadModel('Auditoria');
            $this->loadModel('Anotacione');
            $this->loadModel('Cargueinventario');
            $this->loadModel('Documento');
            $this->autoRender = false;

            $posData = $this->request->data;
            $nota = $posData['nota'];
            $usuarioId = $posData['usuarioId'];
            $empresaId = $posData['empresaId'];

            /*Se obtiene toda la información del traslado*/
            $arrInfoTraslado = $this->Trasladoinventario->obtenerInfoTrasladoInventario($empresaId);

            /*se crea el documento con el tipo de documento trasladoinventario*/
            $tipoDocumentoId = '5';
            $documentoId = $this->Documento->guardarDocumento($tipoDocumentoId,$empresaId,$usuarioId);  
            
            /*se actualiza el codigo del documento ya que en mysql no se admite mas de un autoincrement*/
            $this->Documento->actualizarCodigoDocumento($documentoId);            
            
            /*Se recorre la información del traslado para obtener la información del stock*/
            $resp = true;
            foreach ($arrInfoTraslado as $desc){
                $productoId = $desc['Trasladoinventario']['producto_id'];
                $depDestino = $desc['Trasladoinventario']['depositodestino_id'];
                $cantidad = $desc['Trasladoinventario']['cantidadtraslado'];
                $depOrigen = $desc['Trasladoinventario']['depositoorigen_id'];
                /*se obtiene la información del stock*/
                $infoStock = $this->Cargueinventario->obtenerCargueInventarioProdDep($productoId, $depOrigen);
                
                if(!$this->Detalledocumento->guardarDetalleDocumento($productoId,$depOrigen,$depDestino,
                        $infoStock['Cargueinventario']['costoproducto'],$cantidad,$infoStock['Cargueinventario']['preciomaximo'],
                        $infoStock['Cargueinventario']['preciominimo'],$infoStock['Cargueinventario']['precioventa'],$infoStock['Cargueinventario']['proveedore_id'],
                        $infoStock['Cargueinventario']['tipopago_id'],$infoStock['Cargueinventario']['numerofactura'],$documentoId)){
                    $resp = false;
                }else{
                    
                    /*Se valida si existe el producto en el stock, de lo contrario se crea el registro*/
                    $valExistStock = $this->Cargueinventario->obtenerProductoPorIdDeposito($productoId,$depDestino);
                    if(count($valExistStock) > '0'){
                        $crgInvId = $valExistStock['Cargueinventario']['id'];
                        $crgInvCantNew = $valExistStock['Cargueinventario']['existenciaactual'] + $cantidad;
                        /*Se actualiza la cantidad*/
                        $this->Cargueinventario->actalizarExistenciaStock($crgInvId, $crgInvCantNew);
                    }else{
                        $this->Cargueinventario->guardarCargueInventario($productoId,$depDestino,$infoStock['Cargueinventario']['costoproducto'],
                                $cantidad,$infoStock['Cargueinventario']['preciomaximo'],$infoStock['Cargueinventario']['preciominimo'],
                                $infoStock['Cargueinventario']['precioventa'],$usuarioId,$estId = '1',$infoStock['Cargueinventario']['proveedore_id'],
                                $infoStock['Cargueinventario']['tipopago_id'],$infoStock['Cargueinventario']['numerofactura'],$empresaId);
                    }
                    
                    /*se elimina el registro de traslado de inventario*/                    
                    $detalleId['Trasladoinventario.id'] = $desc['Trasladoinventario']['id'];
                    $this->Trasladoinventario->delete($detalleId);
                    
                }
            }                        
            
            /*Se guarda la nota hecha sobre el documento*/
            $this->Anotacione->guardarNota($nota,$usuarioId,$documentoId);
            
            /*Se obtiene la info del documento cargado para el registro en auditoria*/
            $infoDoc = $this->Documento->obtenerInfoDocumentoId($documentoId);
            
            /*Se obtiene la acción de la auditoria*/
            $idAud = '3';
            $accion = $this->Auditoria->accionAuditoria($idAud);
            
            /*Se obtiene la descripcion de la auditoria*/
            $arrDescripcionAud['codigoDoc'] = $infoDoc['Documento']['codigo'];
            $descripcion = $this->Auditoria->descripcionAuditoria($idAud, $arrDescripcionAud);                        
            
            /*Se guarda la la auditoria*/
            $this->Auditoria->logAuditoria($usuarioId, $descripcion, $accion);
            echo json_encode(array('resp' => $resp, 'documentoId' => $documentoId));

        } 
}
