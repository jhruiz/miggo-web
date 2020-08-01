<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
App::uses ('ProductosController', 'Controller');
/**
 * Cargueinventarios Controller
 *
 * @property Cargueinventario $Cargueinventario
 * @property PaginatorComponent $Paginator
 */
class CargueinventariosController extends AppController {

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
            	
            $this->loadModel('Cuentaspendiente');
            $this->loadModel('Deposito');
            $empresaId = $this->Auth->user('empresa_id');
            $this->Cargueinventario->recursive = 0;
            
            $producto = "";
            $deposito = "";
            
            $data = array();
            if(isset($this->passedArgs['producto']) && $this->passedArgs['producto'] != ""){
                $data['Cargueinventario.producto_id'] = $this->passedArgs['producto'];
                $producto = $this->passedArgs['producto'];
            }
            
            if(isset($this->passedArgs['deposito']) && $this->passedArgs['deposito'] != ""){
                $data['Cargueinventario.deposito_id'] = $this->passedArgs['deposito'];
                $deposito = $this->passedArgs['deposito'];
            }
            
            $data['Cargueinventario.empresa_id'] = $empresaId;
            
            /*se obtiene el stock que tiene la empresa en el inventario*/
//            $cargueinventariosP = $this->Cargueinventario->obtenerCargueInventario($data); 
            
            $cargueinventariosP = $this->Paginator->paginate('Cargueinventario',$data);
            
            $totalUnidades = 0;
            $valorInventario = 0;
            for ($i = 0; $i < count($cargueinventariosP); $i++){
                
                /*se valida si la existencia del producto está por debajo del mínimo*/
                if($cargueinventariosP[$i]['Cargueinventario']['existenciaactual'] < $cargueinventariosP[$i]['Producto']['existenciaminima']){
                    $cargueinventariosP[$i]['Cargueinventario']['color'] = 'danger';
                }else{
                    $cargueinventariosP[$i]['Cargueinventario']['color'] = 'success';
                }
            }
            
            /*Se obtienen las cuentas pendientes que tiene la empresa*/
            $cuentasPendientes = $this->Cuentaspendiente->obtenerCuentasPendientesEmpresa($empresaId);
            
            $totalDeuda = 0;
            foreach ($cuentasPendientes as $ctasPend){
                $totalDeuda += $ctasPend['Cuentaspendiente']['totalobligacion'];
            }

            /*se obtienen los resultados del stock*/
            $totalUnidades = $this->Cargueinventario->cantidadStockEmpresa($empresaId);

            /** Se obtiene la información del valor del inventario*/
            $infoInventario = $this->Cargueinventario->informacionInventario($empresaId);
            foreach($infoInventario as $val){
                $valorInventario += ((double)$val['Cargueinventario']['costoproducto'] * (double)$val['Cargueinventario']['existenciaactual']);
            }

            //Se obtienen los depositos de la empresa del usaurio que se encuentra en sesion
            $depositos = $this->Deposito->obtenerDepositoEmpresa($empresaId);
            
//            $this->set('cargueinventariosP', $this->Paginator->paginate('Cargueinventario', $data));
            $this->set(compact('cargueinventariosP', 'cuentasPendientes', 'totalUnidades', 'valorInventario', 'totalDeuda', 'depositos', 'empresaId'));
            $this->set(compact('producto', 'deposito'));                        
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
            	
		if (!$this->Cargueinventario->exists($id)) {
			throw new NotFoundException(__('Invalid cargueinventario'));
		}
		$options = array('conditions' => array('Cargueinventario.' . $this->Cargueinventario->primaryKey => $id));
		$this->set('cargueinventario', $this->Cargueinventario->find('first', $options));
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
            	
            $this->loadModel('Producto');
            $this->loadModel('Configuraciondato');
            $this->loadModel('Deposito');
            $this->loadModel('Tipopago');
            $this->loadModel('Proveedore');
            $this->loadModel('Impuesto');            

            $arrEmpresa = $this->Auth->user('Empresa');
            $empresaId = $arrEmpresa['id'];

            $usuarioId = $this->Auth->user('id');

            $datConf = "urlImgProducto";
            $urlImg = $this->Configuraciondato->obtenerValorDatoConfig($datConf);                
            $productos = $this->Producto->obtenerProductosEmpresa($empresaId);
            $listDeposito = $this->Deposito->obtenerDepositoUsuario($usuarioId);
            $impuestos = $this->Impuesto->obtenerImpuestosInfo($empresaId);
            $estados = $this->Cargueinventario->Estado->find('list');		
            $tipopagos = $this->Tipopago->find('list');
            $proveedores = $this->Proveedore->obtenerProveedoresEmpresa($empresaId);
            $this->set(compact('productos', 'listDeposito', 'impuestos', 'usuarioId', 'estados', 'tipopagos', 'urlImg', 'proveedores', 'empresaId'));
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
            	
		if (!$this->Cargueinventario->exists($id)) {
			throw new NotFoundException(__('Invalid cargueinventario'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Cargueinventario->save($this->request->data)) {
				$this->Session->setFlash(__('The cargueinventario has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cargueinventario could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Cargueinventario.' . $this->Cargueinventario->primaryKey => $id));
			$this->request->data = $this->Cargueinventario->find('first', $options);
		}
		$productos = $this->Cargueinventario->Producto->find('list');
		$depositos = $this->Cargueinventario->Deposito->find('list');
		$impuestos = $this->Cargueinventario->Impuesto->find('list');
		$usuarios = $this->Cargueinventario->Usuario->find('list');
		$estados = $this->Cargueinventario->Estado->find('list');
		$tipopagos = $this->Cargueinventario->Tipopago->find('list');
		$this->set(compact('productos', 'depositos', 'impuestos', 'usuarios', 'estados', 'tipopagos'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Cargueinventario->id = $id;
		if (!$this->Cargueinventario->exists()) {
			throw new NotFoundException(__('Invalid cargueinventario'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Cargueinventario->delete()) {
			$this->Session->setFlash(__('The cargueinventario has been deleted.'));
		} else {
			$this->Session->setFlash(__('The cargueinventario could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
        
        
/**
 * cargarinventario method
 *
 * @throws NotFoundException
 * @param 
 * @return void
 */       
        public function cargarinventario(){
            if ($this->request->is('post')) {
                $this->loadModel('Deposito');
                $this->loadModel('Producto');
                $this->loadModel('Estado');
                $this->loadModel('Proveedore');
                $this->loadModel('Tipopago');
                $this->loadModel('Impuesto');
                $this->loadModel('Configuraciondato');
                $this->loadModel('Cargueinventario');
                $this->loadModel('Tipopagopago');
                
                $producto_id = $this->request->data("producto_id");
                $usuario_id = $this->request->data("usuario_id");
                $empresa_id = $this->request->data("empresa_id");
                
                /*Se obtienen los depositos de la empresa*/
                $depositos = $this->Deposito->obtenerListaDepositosUsuario($usuario_id);

                /*Se obtiene la información del producto*/
                $producto = $this->Producto->obtenerInfoProducto($producto_id);

                /*Se obtienen los estados activo/inactivo*/
                $estados = $this->Estado->find('list');
                
                /*Se obtienen los impuestos por empresa*/
                $impuestos = $this->Impuesto->obtenerImpuestosInfo($empresa_id);
                
                /*Se obtienen los proveedores por empresa*/
                $proveedores = $this->Proveedore->obtenerProveedoresEmpresa($empresa_id);
                
                /*Se obtienen los tipos de pago por empresa*/
                $tipopagos = $this->Tipopagopago->obtenerListaTiposPagos();
                
                /*Se obtiene la información básica del producto en el Stock actual*/
                
                $cargueInventario = $this->Cargueinventario->obtenerProductoPorId($producto_id);
                if(count($cargueInventario) <= '0'){
                    $existenciaActual = '0';
                }else{
                    $existenciaActual = $cargueInventario['Cargueinventario']['existenciaactual'];
                }
                
                /*Se obtiene la url de las imagenes*/
                $datConf = "urlImgProducto";
                $urlImg = $this->Configuraciondato->obtenerValorDatoConfig($datConf) . '/' . $empresa_id . '/';                
                
                $this->set(compact('depositos', 'producto', 'estados', 'impuestos', 'proveedores', 'tipopagos', 'producto_id', 'usuario_id', 'empresa_id', 'urlImg', 'existenciaActual'));
            }
        }   
        
        public function ajaxProductosVenta(){
            $this->loadModel('Cargueinventario');
            $this->loadModel('Deposito');
            
            $this->autoRender = false;
            $posData = $this->request->data;
            $usuarioId = $posData['usuarioId'];
            $descProducto = $posData['descProducto'];
            
            /*Se obtienen los depositos en los cuales está el usuario*/
            $arrDepositos = $this->Deposito->obtenerDepositoUsuario($usuarioId);
                        
            /*Se obtiene el id del deposito*/
            $depositosId = array();
            foreach ($arrDepositos as $dpIdx){
                $depositosId[] = $dpIdx['Deposito']['id'];
            }

            /*Se obtienen los productos por deposito*/            
            $arrProductos = $this->Cargueinventario->obtenerProductosStock($depositosId,$descProducto);
            
            echo json_encode(array('resp' => $arrProductos));                         
        }        
        
        public function seleccionproductoventa(){
            $this->loadModel('Configuraciondato');
            $this->loadModel('CargueinventariosImpuesto');
            
            $posData = $this->request->data;
            
            $productoId = $posData['productoId'];

            $cargueInvId = isset($posData['cargueInvId']) && !empty($posData['cargueInvId']) ? $posData['cargueInvId'] : "";
            
            /*Se obtiene el producto del stock*/
            $arrProducto = $this->Cargueinventario->obtenerProductoStock($productoId, $cargueInvId);

            /*Se obtiene la url para la foto del producto*/
            $strDato = "urlImgProducto";
            $urlImgProducto = $this->Configuraciondato->obtenerValorDatoConfig($strDato);
            
            /*Se obtienen los impuestos grabados al producto*/
            $arrImpuestos = $this->CargueinventariosImpuesto->obtenerImpuestosProducto($arrProducto['Cargueinventario']['id']);
            
            $prcImpuesto = 0;
            $vlrAntesImp = $arrProducto['Cargueinventario']['precioventa'];
            $vlrImpuesto = 0;
            //se valida si existen impuestos
            if(!empty($arrImpuestos)){                
                $prcImpuesto = 1 + (floatval($arrImpuestos['0']['Impuesto']['valor'])/100);
                $vlrAntesImp = intval($arrProducto['Cargueinventario']['precioventa']/$prcImpuesto);
                $vlrImpuesto = intval($arrProducto['Cargueinventario']['precioventa'] - $vlrAntesImp);
            }
            
            $this->set(compact('arrProducto','urlImgProducto','arrImpuestos', 'prcImpuesto', 'vlrAntesImp', 'vlrImpuesto'));
        }
        
        public function seleccionproductoventaclientenuevo(){
            $this->loadModel('Configuraciondato');
            $this->loadModel('CargueinventariosImpuesto');
            $this->loadModel('Cliente');
            $this->loadModel('Empresa');
            
            $posData = $this->request->data;
            
            $productoId = $posData['productoId'];                     
            $cargueInvId = isset($posData['cargueInvId']) && !empty($posData['cargueInvId']) ? $posData['cargueInvId'] : "";
            
            /*Se obtiene el producto del stock*/
            $arrProducto = $this->Cargueinventario->obtenerProductoStock($productoId, $cargueInvId);            

            /*Se obtiene la url para la foto del producto*/
            $strDato = "urlImgProducto";
            $urlImgProducto = $this->Configuraciondato->obtenerValorDatoConfig($strDato);
            
            /*Se obtienen los impuestos grabados al producto*/
            $arrImpuestos = $this->CargueinventariosImpuesto->obtenerImpuestosProducto($arrProducto['Cargueinventario']['id']);
            
            $prcImpuesto = 0;
            $vlrAntesImp = $arrProducto['Cargueinventario']['precioventa'];
            $vlrImpuesto = 0;
            //se valida si existen impuestos
            if(!empty($arrImpuestos)){                
                $prcImpuesto = 1 + (floatval($arrImpuestos['0']['Impuesto']['valor'])/100);
                $vlrAntesImp = intval($arrProducto['Cargueinventario']['precioventa']/$prcImpuesto);
                $vlrImpuesto = intval($arrProducto['Cargueinventario']['precioventa'] - $vlrAntesImp);
            }            
            
            $this->set(compact('arrProducto','urlImgProducto','arrImpuestos'));   
            $this->set(compact('arrProducto','urlImgProducto','arrImpuestos', 'prcImpuesto', 'vlrAntesImp', 'vlrImpuesto'));
        }
        
        public function seleccionproductoordencompra(){
            $this->loadModel('Configuraciondato');
            $this->loadModel('CargueinventariosImpuesto');
            $this->loadModel('Cliente');
            $this->loadModel('Empresa');
            
            $posData = $this->request->data;
            
            $productoId = $posData['productoId'];                     
            
            /*Se obtiene el producto del stock*/
            $arrProducto = $this->Cargueinventario->obtenerProductoStock($productoId);

            /*Se obtiene la url para la foto del producto*/
            $strDato = "urlImgProducto";
            $urlImgProducto = $this->Configuraciondato->obtenerValorDatoConfig($strDato);
            
            /*Se obtienen los impuestos grabados al producto*/
            $arrImpuestos = $this->CargueinventariosImpuesto->obtenerImpuestosProducto($arrProducto['Cargueinventario']['id']);
                      
            $prcImpuesto = 0;
            $vlrAntesImp = $arrProducto['Cargueinventario']['precioventa'];
            $vlrImpuesto = 0;
            //se valida si existen impuestos
            if(!empty($arrImpuestos)){                
                $prcImpuesto = 1 + (floatval($arrImpuestos['0']['Impuesto']['valor'])/100);
                $vlrAntesImp = intval($arrProducto['Cargueinventario']['precioventa']/$prcImpuesto);
                $vlrImpuesto = intval($arrProducto['Cargueinventario']['precioventa'] - $vlrAntesImp);
            }              
            
            $this->set(compact('arrProducto','urlImgProducto','arrImpuestos', 'prcImpuesto', 'vlrAntesImp', 'vlrImpuesto'));             
        }      
        
        
        public function descontarProductos($cantidad, $id){
            /*se obtiene la cantidad en stock del producto*/
            $arrProducto = $this->Cargueinventario->obtenerProductoPorId($id);
            
            /*se resta la cantidad */
            $cantidadFinal = $arrProducto['Cargueinventario']['existenciaactual'] - $cantidad;
            
            $data = array();
            $data['id'] = $id;
            $data['existenciaactual'] = $cantidadFinal;
            
            if($this->Cargueinventario->save($data)){
                return true;
            }else{
                return false;
            }
        }
        
        public function ajaxProductoCargueInventario(){ 
            $this->loadModel('Producto');            
            $this->autoRender = false;
            $posData = $this->request->data;
            $descProducto = $posData['descProducto']; 
            $empresaId = $posData['empresaId'];
            /*Se obtienen los productos por descripcion*/            
            $arrProductos = $this->Producto->obtenerProductoCargueInventario($descProducto,$empresaId);            
            echo json_encode(array('resp' => $arrProductos));                         
        }
        
        public function ajaxProductoCargueBarcode(){  
            $this->loadModel('Producto');   
            $this->loadModel('Precargueinventario');
            $this->autoRender = false;
            $posData = $this->request->data;
            $descProducto = $posData['descProducto']; 
            $empresaId = $posData['empresaId'];
            /*Se obtiene el producto por codigo de barras*/            
            $arrProductos = $this->Producto->obtenerProductoCargueBarcode($descProducto, $empresaId);
  
            /*se valida si la consulta arroja resultado*/
            if(count($arrProductos) <= '0'){
                $mensaje = "No se encontraron productos con el código de barras " . $descProducto;
                echo json_encode(array('resp' => '1', 'mensaje' => $mensaje));
            }else{
                /*se valida si el producto ha sido pre cargado al inventario*/
                $usuarioId = $this->Auth->user('id');
                $infoPrecargue = $this->validarPrecargueInventario($usuarioId, $arrProductos['Producto']['id']);                
                if(count($infoPrecargue) <= '0'){
                    echo json_encode(array('resp' => '2', 'prod' => $arrProductos));
                }else{
                    /*se le aumenta un producto a la cantidad existente en precargue de inventario*/
                    $cantidadActual = $infoPrecargue['Precargueinventario']['cantidad'] + 1;
                    if($this->Precargueinventario->actualizarPrecargue($infoPrecargue['Precargueinventario']['id'], $cantidadActual)){
                        $mensaje = "Se agrego el producto " . $arrProductos['Producto']['descripcion'] . " correctamente al Cargue Parcial del Inventario";
                        echo json_encode(array('resp' => '3', 'mensaje' => $mensaje));
                    }else{
                        $mensaje = "No se pudo agregar el producto " . $arrProductos['Producto']['descripcion'] . ". Por favor, inténtelo nuevamente."; 
                        echo json_encode(array('resp' => '4', 'mensaje' => $mensaje));
                    }                    
                }                
            }                  
        }  
        
        public function validarPrecargueInventario($usuarioId, $productoId){
            $this->loadModel('Precargueinventario');
            $preCargueInfo = $this->Precargueinventario->obtenerPrecargueInventario($usuarioId, $productoId);
            return $preCargueInfo;
        }
        
        public function search() {
            $url = array();
            $url['action'] = 'index';
            
            foreach ($this->data as $kk => $vv) {
                if($kk != 'Cargueinventario'){
                    $url[$kk] = $vv;
                }                
            }
            // redirect the user to the url
            $this->redirect($url, null, true);
        }  
        
        public function ajaxProductoCargueIndexBarcode(){  
            $this->loadModel('Producto');   
            $this->autoRender = false;
            $posData = $this->request->data;
            $descProducto = $posData['descProducto']; 
            $empresaId = $posData['empresaId'];            
            
            /*Se obtiene el producto por codigo de barras*/            
            $arrProductos = $this->Producto->obtenerProductoCargueBarcode($descProducto, $empresaId);

            /*se valida si la consulta arroja resultado*/
            if(count($arrProductos) <= '0'){
                $mensaje = "No se encontraron productos con el código de barras " . $descProducto;
                echo json_encode(array('resp' => '1', 'mensaje' => $mensaje));
            }else{
                echo json_encode(array('resp' => '2', 'producto' =>$arrProductos));
            }                  
        }   
        
        /**
         * se obtiene el suministro por id de producto desde el stock y se relaciona con la orden de trabajo
         */
        public function ajaxObtenerSuministroOrden(){               

            $this->autoRender = false;
            $posData = $this->request->data;
            
            $this->loadModel('Prefactura');
            $this->loadModel('Prefacturasdetalle');
            $this->loadModel('CargueinventariosImpuesto');
            
            $ordenId = $posData['ordenId']; 
            $idProducto = $posData['idSuministro'];            
            $cargueInvId = isset($posData['cargueInvId']) && !empty($posData['cargueInvId']) ? $posData['cargueInvId'] : "";
            
            $cantidadventa = 1;
            
            //se valida si hay producto en stock
            $arrCargueInv = $this->Cargueinventario->obtenerProductoStock($idProducto, $cargueInvId);

            $cantStock = $arrCargueInv['Cargueinventario']['existenciaactual'];
            
            if($cantStock != '0'){
                $existFinal = intval($cantStock) - $cantidadventa;

                /*se actualiza la cantidad en stock tras la prefactura*/
                $this->Cargueinventario->actalizarExistenciaStock($arrCargueInv['Cargueinventario']['id'], $existFinal);  

                //se guarda el suministro relacionandolo a la orden de trabajo
                $resp = $this->relacionarSuministroOrden($arrCargueInv['Cargueinventario']['id'], $ordenId, $cantidadventa); 
                
                //se valida si existe una prefactura que tenga relacionada la orden en tramite
                $arrPrefact = $this->Prefactura->obtenerPrefactPorOrden($ordenId);
                if(!empty($arrPrefact)){//se agrega el producto al detalle de al prefactura
                    
                    $ciImpuesto = $this->CargueinventariosImpuesto->obtenerImpuestosProducto($cargueInvId);
                    $this->Prefacturasdetalle->guardarDetallePrefactura($cantidadventa,$arrCargueInv['Cargueinventario']['precioventa'],
                            $arrCargueInv['Cargueinventario']['id'],$arrPrefact['Prefactura']['id'], null, null, $ciImpuesto['0']['Impuesto']['valor']);
                }
                
                if($resp){
                    echo json_encode(array('resp' => '1', 'prod' => $arrCargueInv));
                }
            }else{
                echo json_encode(array('resp' => '0'));
            }                                                
        }
        
        /**
         * Se guarda el suministro seleccionado con la orden
         * @param type $cargueInvId
         * @param type $ordenId
         * @param type $cantidad
         * @return type
         */
        public function relacionarSuministroOrden($cargueInvId, $ordenId, $cantidad){
            $this->loadModel('OrdentrabajosSuministro');            
            
            $resp = $this->OrdentrabajosSuministro->guardarSuministroOrden($ordenId, $cargueInvId, $cantidad);
            
            return $resp;
        }
        
        /**
         * actualiza la cantidad de unidades requeridas para la orden de trabajo de un suministro
         */
        public function ajaxActualizarCantidadSumOrd(){
            $this->loadModel('OrdentrabajosSuministro');
            $this->loadModel('Prefacturasdetalle');
            $this->loadModel('Prefactura');
            $this->autoRender = false;
            $posData = $this->request->data;
            
            $ordenId = $posData['ordenId']; 
            $cargueInvId = $posData['cargueInvId'];    
            $cantNueva = $posData['cantNueva'];    
            
            //se obtiene el registro de suministros para la orden
            $arrSumOrd = $this->OrdentrabajosSuministro->obtenerOrdenSuministro($cargueInvId, $ordenId);
            
            //se obtiene el registro de cargue inventarios por id
            $arrCargueInv = $this->Cargueinventario->obtenerInventarioId($cargueInvId);
            
            $cantStock = $arrCargueInv['Cargueinventario']['existenciaactual'] + $arrSumOrd['OrdentrabajosSuministro']['cantidad'];
            
            if($cantStock > $cantNueva){
                $existFinal = $cantStock - $cantNueva;
                $this->Cargueinventario->actalizarExistenciaStock($arrCargueInv['Cargueinventario']['id'], $existFinal);
                $resp = $this->OrdentrabajosSuministro->actualizarCantidadSuministro($arrSumOrd['OrdentrabajosSuministro']['id'], $cantNueva);
                
                //se valida si existe una prefactura que tenga relacionada la orden en tramite
                $arrPrefact = $this->Prefactura->obtenerPrefactPorOrden($ordenId);
                if(!empty($arrPrefact)){//se modifica la cantidad solicitada en la orden de trabajo                    
                    //se obtiene la informacion del detalle de la prefactura
                    $arrPrefactD = $this->Prefacturasdetalle->obtenerCarguePrefact($arrPrefact['Prefactura']['id'], $arrCargueInv['Cargueinventario']['id']);
                    //se actualiza la cantidad solicitada en la orden de trabajo
                    $this->Prefacturasdetalle->actualizarCantidadUnidades($arrPrefactD['Prefacturasdetalle']['id'], $cantNueva);
                }                
                
                if($resp){
                     echo json_encode(array('resp' => '1'));
                }else{
                     echo json_encode(array('resp' => '2', 'cant' => $arrSumOrd['OrdentrabajosSuministro']['cantidad']));
                }
            }else{
                //no hay la cantidad suficiente en stock
                echo json_encode(array('resp' => '0', 'cant' => $arrSumOrd['OrdentrabajosSuministro']['cantidad']));
            }           
        }       
        
        /**
         * Se elimina el suministro relacionado a la orden de trabajo
         */
        public function ajaxEliminarSuministroOrden(){
            $this->loadModel('OrdentrabajosSuministro');
            $this->loadModel('Prefactura');
            $this->loadModel('Prefacturasdetalle');
            $this->autoRender = false;
            $posData = $this->request->data;
            
            $ordenId = $posData['ordenId']; 
            $cargueInvId = $posData['cargueInvId']; 
            
            //se obtiene el registro de suministros para la orden
            $arrSumOrd = $this->OrdentrabajosSuministro->obtenerOrdenSuministro($cargueInvId, $ordenId);
            
            //se obtiene el registro de cargue inventarios por id
            $arrCargueInv = $this->Cargueinventario->obtenerInventarioId($cargueInvId);
            
            $cantFinal = $arrCargueInv['Cargueinventario']['existenciaactual'] + $arrSumOrd['OrdentrabajosSuministro']['cantidad'];
            
            //se restaura la cantidad en el inventario
            $this->Cargueinventario->actalizarExistenciaStock($arrCargueInv['Cargueinventario']['id'], $cantFinal);
            
            //se elimina el registro del suministro para la orden
            $resp = $this->OrdentrabajosSuministro->eliminarSuministroOrden($arrSumOrd['OrdentrabajosSuministro']['id']);
            
            //se valida si existe una prefactura que tenga relacionada la orden en tramite
            $arrPrefact = $this->Prefactura->obtenerPrefactPorOrden($ordenId);
            if(!empty($arrPrefact)){//se modifica la cantidad solicitada en la orden de trabajo                    
                //se obtiene la informacion del detalle de la prefactura
                $arrPrefactD = $this->Prefacturasdetalle->obtenerCarguePrefact($arrPrefact['Prefactura']['id'], $arrCargueInv['Cargueinventario']['id']);
                //se actualiza la cantidad solicitada en la orden de trabajo
                $this->Prefacturasdetalle->eliminarRegistroPrefacturado($arrPrefactD['Prefacturasdetalle']['id']);
            }              
            
            if($resp){
                 echo json_encode(array('resp' => '1'));
            }else{
                 echo json_encode(array('resp' => '0'));
            }              
        }        

        
        /**
         * se obtinen los productos en el autocomplete para la cotizacion
         */
        public function ajaxObtenerProductoCotizacion(){
            $this->loadModel('Cargueinventario');
            $this->loadModel('Deposito');
            
            $this->autoRender = false;
            $posData = $this->request->data;
            $usuarioId = $posData['usuarioId'];
            $descProducto = $posData['descProducto'];
            
            /*Se obtienen los depositos en los cuales está el usuario*/
            $arrDepositos = $this->Deposito->obtenerDepositoUsuario($usuarioId);
                        
            /*Se obtiene el id del deposito*/
            $depositosId = array();
            foreach ($arrDepositos as $dpIdx){
                $depositosId[] = $dpIdx['Deposito']['id'];
            }

            /*Se obtienen los productos por deposito*/            
            $arrProductos = $this->Cargueinventario->obtenerProductosStock($depositosId,$descProducto);
            
            echo json_encode(array('resp' => $arrProductos));              
        }   
        
        public function cargarinvpln($mensaje = null, $errorCsv = null) {
            $this->loadModel('Configuraciondato');
            $confDato = 'planoInventario';
            $empresaId = $this->Auth->user('empresa_id');
            
            if ($this->request->is('post')) {
                $this->loadModel('Producto');
                $productos = new ProductosController();
                
                //se obtiene el archivo que llega por post
                $posData = $this->request->data;

                // se valida que se haya seleccionado un archivo
                if(empty($posData['Cargueplano']['cargarInventario']['name'])) {
                    $mensaje = 'Debe seleccionar un archivo CSV';
                    return $this->redirect(array('action' => 'cargarinvpln', base64_encode($mensaje), ''));
                }
                
                //se valida que el archivo sea csv
                $arrName = split('\.', $posData['Cargueplano']['cargarInventario']['name']);

                if($arrName['1'] != 'csv'){
                    $mensaje = 'Debe seleccionar un archivo con extensión CSV';
                    return $this->redirect(array('action' => 'cargarinvpln', base64_encode($mensaje), ''));
                }
                
                $nameImg = date('Ymdhis');
                
                $usuarioId = $this->Auth->user('id');

                if($productos->subirArchivo($posData['Cargueplano']['cargarInventario'], $confDato, $nameImg, $empresaId, $usuarioId)){
                    
                    //se obtiene la url del archivo 
                    $urlCsv = $this->Configuraciondato->obtenerValorDatoConfig($confDato) . $empresaId . '//' . $nameImg . '.csv';
                    $linea = 0;
                    $archivo = fopen($urlCsv, "r");
                    
                    $arrProductos = array();
                    $arrErrores = array();
                    $errorCsv = '';
                    $mensaje = 'El cargue del inventario fue exitoso!!!';
                    //Lo recorremos
                    while (($datos = fgetcsv($archivo, ",")) == true) 
                    {
                        
                        if($linea == 0){
                            $linea++;
                            continue;
                        }
                        
                        //se obtiene un producto por referencia
                        $producto = $this->Producto->obtenerProductoPorReferencia($datos['0']);
                        
                        if(!empty($producto) && $this->validarCargue($datos)){
                            //se despeja el id de la bodega
                            $arrBodega = split('-', $datos['2']);
                            
                            //se valida si existe impuesto y se despeja de ser asi
                            $imp = '';
                            if($datos['3'] != 'na') {
                                $arrImp = split('-', $datos['3']);
                                $imp = $arrImp['1'];
                            }
                            
                            //se despeja el id del proveedor
                            $arrProv = split('-', $datos['8']);
                            
                            $prod['producto_id'] = $producto['Producto']['id'];
                            $prod['cantidad'] = $datos['1'];
                            $prod['bodega_id'] = $arrBodega['1'];
                            $prod['impuesto_id'] = $imp;   
                            $prod['costo_producto'] = $datos['4'];
                            $prod['precio_maximo'] = $datos['5'];
                            $prod['precio_minimo'] = $datos['6'];
                            $prod['precio_venta'] = $datos['7'];
                            $prod['proveedore_id'] = $arrProv['1'];
                            $prod['tipopago'] = $datos['9'];
                            $prod['num_factura'] = $datos['10'];
                            $prod['empresa_id'] = $empresaId;
                            $prod['usuario_id'] = $usuarioId;
                            
                            $arrProductos[] = $prod;
                        }else{
                            $prodErr['producto_id'] = $datos['0'];
                            $prodErr['cantidad'] = $datos['1'];
                            $prodErr['bodega_id'] = $datos['2'];
                            $prodErr['impuesto_id'] = $datos['3'];   
                            $prodErr['costo_producto'] = $datos['4'];
                            $prodErr['precio_maximo'] = $datos['5'];
                            $prodErr['precio_minimo'] = $datos['6'];
                            $prodErr['precio_venta'] = $datos['7'];
                            $prodErr['proveedore_id'] = $datos['8'];
                            $prodErr['tipopago'] = $datos['9'];
                            $prodErr['num_factura'] = $datos['10'];
                            $arrErrores[]= $prodErr;
                        }
                    }
                    
                    //Cerramos el archivo
                    fclose($archivo);
                }else {
                    $mensaje = "No fue posible cargar el archivo plano. Por favor, inténtelo nuevamente.";
                }
                
                //se elimina el archivo de cargue de inventario
                unlink($urlCsv);
                
                $nameError = '';
                if(!empty($arrErrores)){
                    $nameError = date('Ymdhis');
                    $errorCsv = $this->Configuraciondato->obtenerValorDatoConfig($confDato) . $empresaId . '//' . $nameError . '.csv';
                    $mensaje = "No fue posible realizar el cargue de todos los productos debido a inconsistencias en el archivo. ";
                    $mensaje .= "Por favor, corrija los registros e inténtelo nuevamente. Para obtener los registros con error, seleccione el siguiente botón ";
                    $this->crearPlanoErrores($errorCsv, $arrErrores);
                }  
                
                $this->cargarInventarioXPlano($arrProductos);
                
                return $this->redirect(array('action' => 'cargarinvpln', base64_encode($mensaje), $nameError));
                
            }
            
            if(!empty($errorCsv)){
                $errorCsv = $this->Configuraciondato->obtenerValorDatoConfig($confDato) . $empresaId . '//' . $errorCsv . '.csv';    
            }
            
            
            $this->set(compact('mensaje', 'errorCsv', 'pst')); 
        }
        
        // valida que se hayan ingresado todos los datos del cargue de inventario
        public function validarCargue($datos){

            $resp = true;
            
            foreach($datos as $dat){
                
                if(empty($dat)) {
                    $resp = false;
                    break;
                }
            }
            
            return $resp;
        }
        
        //se crea el plano indicando los productos que no fue posible cargar
        public function crearPlanoErrores($urlCsv, $arrErrores) {

            $file_handle = fopen($urlCsv, 'w');
            $delimitador = ',';
            $encapsulador = '"';
            
            $arrCabecera[] = array( 'cod_producto', 'cantidad', 'cod_bodega', 
                                    'cod_impuesto', 'costo_producto', 'precio_maximo', 
                                    'precio_minimo', 'precio_venta', 'cod_proveedor',
                                    'cod_tipo_pago', 'num_factura');
                                    
            foreach($arrCabecera as $cab) {
                fputcsv($file_handle, $cab, $delimitador, $encapsulador);
            }
            
            foreach ($arrErrores as $linea) {
                fputcsv($file_handle, $linea, $delimitador, $encapsulador);
            }
            
            rewind($file_handle);
            fclose($file_handle);  

        }
        
        //se carga al inventario los productos relacionados en el documento 
        public function cargarInventarioXPlano($arrProductos){
            $this->loadModel('Documento');
            $this->loadModel('Detalledocumento');
            $this->loadModel('Proveedore');
            $this->loadModel('Cuentaspendiente');
            $this->loadModel('Cargueinventario');
            $this->loadModel('Anotacione');
            $this->loadModel('Auditoria');
            
            try {
                /*se crea el documento*/
                $tipoDocumentoId = '1';
                
                /*se guarda el documento y se obtiene el id del mismo*/
                $documentoId = $this->Documento->guardarDocumento($tipoDocumentoId,$arrProductos['0']['empresa_id'],$arrProductos['0']['usuario_id']);  
                
                /*se actualiza el codigo del documento ya que en mysql no se admite mas de un autoincrement*/
                $this->Documento->actualizarCodigoDocumento($documentoId); 
                
                
                /*se guarda la informacion del detalle del documento y del inventario*/
                foreach ($arrProductos as $infP){
                    
                    if(!$this->Detalledocumento->guardarDetalleDocumento(   $infP['producto_id'], $depOrg=null, $infP['bodega_id'],
                                                                            $infP['costo_producto'], $infP['cantidad'], $infP['precio_maximo'],
                                                                            $infP['precio_minimo'],$infP['precio_venta'],$infP['proveedore_id'],
                                                                            $infP['tipopago'], $infP['num_factura'], $documentoId)){  
                    }
                    
                    /*Se valida si el producto que se va cargar ya existe en el inventario*/
                    $infoProducto = $this->Cargueinventario->obtenerProductoPorIdDeposito($infP['producto_id'],$infP['bodega_id']);
                    if(count($infoProducto)>'0'){
                        /*Si existe se debe actualizar la información del inventario*/
                        $cantidadActual = $infoProducto['Cargueinventario']['existenciaactual'];
                        $costoActual = $infoProducto['Cargueinventario']['costoproducto'];
    
                        $cantidadACargar = $infP['cantidad'];
                        $costoACargar = $infP['costo_producto'];
    
                        $promedioPonderado = floor(($cantidadActual*$costoActual)+($cantidadACargar*$costoACargar))/($cantidadActual+$cantidadACargar);
                        $cantidadFinal = $cantidadActual+$cantidadACargar;
                        
                        $data = array();
                        $data['id'] = $infoProducto['Cargueinventario']['id'];
                        $data['deposito_id'] = $infP['bodega_id'];
                        $data['costoproducto'] = $promedioPonderado;
                        $data['existenciaactual'] = $cantidadFinal;
                        $data['preciomaximo'] = $infP['precio_maximo'];
                        $data['preciominimo'] = $infP['precio_minimo'];
                        $data['precioventa'] = $infP['precio_venta'];
                        $data['usuario_id'] = $infP['usuario_id'];
                        $data['estado_id'] = '1';
                        $data['proveedore_id'] = $infP['proveedore_id'];
                        $data['tipopago_id'] = null;
                        $data['numerofactura'] = $infP['num_factura'];
                        
                        /*Se actualiza el registro del producto en el inventario*/
                        $this->Cargueinventario->save($data);
                        
                    }else{
                        /*Si el producto no existe en el deposito, se crea*/
                        if(!$this->Cargueinventario->guardarCargueInventario(   $infP['producto_id'], $infP['bodega_id'], $infP['costo_producto'],
                                                                                $infP['cantidad'], $infP['precio_maximo'], $infP['precio_minimo'],
                                                                                $infP['precio_venta'], $infP['usuario_id'], '1',
                                                                                $infP['proveedore_id'], null, $infP['num_factura'], 
                                                                                $infP['empresa_id'])){
                           } 
                    }
                    
                    /*se actualizan los datos de los impuestos para el producto*/
                    $this->actualizarInfoImpuestos($infP['producto_id'], $infP['bodega_id'], $infP['impuesto_id']);
                    
                    /*Si el tipo de pago es crédito, se guarda en cuentas por pagar*/
                    if($infP['tipopago'] == '2'){
                        //se obtiene la información del proveedor
                        $infoProv = $this->Proveedore->obtenerProveedorPorId($infP['proveedore_id']);
                        $fechaPago = $this->sumarDiasFecha(date('Y-m-d'),$infoProv['Proveedore']['diascredito']);
    
                        $totalObligacion = ($infP['costo_producto'] * $infP['cantidad']);
                        $this->Cuentaspendiente->guardarCuentasPendientes(  $documentoId, $infP['producto_id'], $infP['bodega_id'],
                                                                            $infP['costo_producto'], $infP['cantidad'], $infP['proveedore_id'],
                                                                            $infP['num_factura'], $infP['usuario_id'], $arrProductos['empresa_id'], 
                                                                            $totalObligacion, $fechaPago);
                    }
                }
                
                /*Se guarda la nota hecha sobre el documento*/
                $this->Anotacione->guardarNota('Cargue inventario por archivo plano', $infP['usuario_id'], $documentoId);
                
                /*Se obtiene la info del documento cargado para el registro en auditoria*/
                $infoDoc = $this->Documento->obtenerInfoDocumentoId($documentoId);
                
                /*Se obtiene la acción de la auditoria*/
                $idAud = '1';
                $accion = $this->Auditoria->accionAuditoria($idAud);
                
                /*Se obtiene la descripcion de la auditoria*/
                $arrDescripcionAud['codigoDoc'] = $infoDoc['Documento']['codigo'];
                $descripcion = $this->Auditoria->descripcionAuditoria($idAud, $arrDescripcionAud);
                
                /*Se guarda la la auditoria*/
                $this->Auditoria->logAuditoria($infP['usuario_id'], $descripcion, $accion);
                
                return true;
                
            } catch (Exception $e) {
                return false;
            }
            
        }
        
        public function actualizarInfoImpuestos($productoId,$depositoId,$impuestoId){
            
            $this->loadModel('CargueinventariosImpuesto');
            
            /*Se obtiene el id del cargue del inventario*/
            $infoCargueInv = $this->Cargueinventario->obtenerProductoPorIdDeposito($productoId, $depositoId);
            
            /*Se elimina el regisro de impuestos que tenga asignado el cargue*/
            $this->CargueinventariosImpuesto->deleteAll(array('CargueinventariosImpuesto.cargueinventario_id' => $infoCargueInv['Cargueinventario']['id']), false);
            
            if(!empty($impuestoId)){
                $this->CargueinventariosImpuesto->guardarImpuestosCargueInv($infoCargueInv['Cargueinventario']['id'], $impuestoId);                
            }


        }         
        
        public function sumarDiasFecha($fecha,$dias){
            if(empty($dias)){
                $dias = 30;
            }
            $fechaNew = new DateTime($fecha);
            $fechaNew->add(new DateInterval('P' . $dias . 'D'));
            $fechaFin = $fechaNew->format('Y-m-d');
            return $fechaFin;          
        }
        
}
