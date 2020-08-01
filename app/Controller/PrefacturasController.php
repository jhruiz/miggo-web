<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');

/**
 * Prefacturas Controller
 *
 * @property Prefactura $Prefactura
 * @property PaginatorComponent $Paginator
 */
class PrefacturasController extends AppController {

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

            $this->loadModel('Estadosprefactura');
            $this->loadModel('Prefacturasdetalle');
            
            /*se reagistra la actividad del uso de la aplicacion*/
            $usuariosController = new UsuariosController();
            $usuarioAct = $this->Auth->user('id');
            $usuariosController->registraractividad($usuarioAct);            
            
            $cliente = "";
            $placa = "";
            
            if(!empty($this->passedArgs['cliente'])){
                $cliente = strtolower($this->passedArgs['cliente']);
            }            
            
            if(!empty($this->passedArgs['vehiculo'])){
                $placa = strtolower($this->passedArgs['vehiculo']);
            }            
            
            $perfilId = $this->Auth->user('perfile_id');              
            
            $usuarioId = "";
            
            if($perfilId != '1' && $perfilId != '4' && $perfilId != '5'){
                $usuarioId = $this->Auth->user('id');
            }
            
            //se obtienen las ordenes de pedido con la informacion de la orden de trabajo y vehiculo
            $prefacturas = $this->Prefactura->obtenerPrefacturas($usuarioId, $placa, $cliente);

            //se obtiene el listado de prefacturas
            $estados = $this->Estadosprefactura->obtenerListaEstados();
            
            //obtiene el detalle de las prefacturas
            $arrPrefacturasDetalle = $this->Prefacturasdetalle->obtenerDetallePrefacturas();            
                     
            //se cuenta el valor total de las prefacturas
            $prefactValor = 0;
            if(!empty($arrPrefacturasDetalle)){                
                foreach ($arrPrefacturasDetalle as $pf){
                    
                    //se obtiene el valor de las prefacturas
                    if(!empty($pf['Prefacturasdetalle']['impuesto'])){
                        $impuesto = floatval("1." . $pf['Prefacturasdetalle']['impuesto']);                        
                        
                        //se calcula el valor base menos el descuento
                        $valBase = (ceil((floatval($pf['Prefacturasdetalle']['costoventa'])/$impuesto))) - floatval($pf['Prefacturasdetalle']['descuento']);                        
                        $prefactValor += ceil($valBase * $impuesto);
                    }else{
                        $prefactValor += (floatval($pf['Prefacturasdetalle']['costoventa']) - floatval($pf['Prefacturasdetalle']['descuento']));
                    }
                }
            }
            
            $this->set(compact('prefacturas', 'prefactValor', 'estados'));
	} 

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
            $this->loadModel('Abonofactura');
            /*se reagistra la actividad del uso de la aplicacion*/
            $usuariosController = new UsuariosController();
            $usuarioAct = $this->Auth->user('id');
            $usuariosController->registraractividad($usuarioAct);

            $this->loadModel('Tipopago');
            $this->loadModel('Notafactura');
            $this->loadModel('Usuario');
            $this->loadModel('Relacionempresa');
            $this->loadModel('Ordentrabajo');
            $this->loadModel('Cuenta');
            $this->loadModel('Configuraciondato');
            $this->loadModel('Estadosprefactura');
            
            if (!$this->Prefactura->exists($id)) {
                    throw new NotFoundException(__('La orden de compra no existe.'));
            }
            $usuarioId = $this->Auth->user('id');
            $empresaId = $this->Auth->user('empresa_id');
            $tipoPago = $this->Tipopago->find('list');            
            $options = array('conditions' => array('Prefactura.' . $this->Prefactura->primaryKey => $id));
            
            $notaFactura = $this->Notafactura->find('list');
            $vendedor = $this->Usuario->obtenerUsuarioEmpresa($empresaId);
            $relacionEmpresa = $this->Relacionempresa->obtenerListaEmpresasRelacion($empresaId);
                        
            $prefactura = $this->Prefactura->find('first', $options);
            $cuentas = $this->Cuenta->obtenerCuentasEmpresa($empresaId); 
            
            //si existe orden de trabajo, se obtiene la informacion
            if(!empty($prefactura['Prefactura']['ordentrabajo_id'])){
                $arrFilter['Ordentrabajo.id'] = $prefactura['Prefactura']['ordentrabajo_id'];
                $arrOrdenT = $this->Ordentrabajo->obtenerOrdenesTrabajo($arrFilter);
            }            
            
            //se obtiene la url de la imagend e whatsapp
            $strDatoWP = "ulrImgWP";
            $urlImgWP = $this->Configuraciondato->obtenerValorDatoConfig($strDatoWP);              
            
            //se obtienen los abonos realizados a la prefactura
            $abonos = $this->Abonofactura->obtenerAbonosPrefactura($prefactura['Prefactura']['id']);
            
            //se obtiene el listado de las prefacturas
            $estados = $this->Estadosprefactura->obtenerListaEstados();

            $ttalAbonos = 0;
            foreach ($abonos as $abn){
                $ttalAbonos += $abn['Abonofactura']['valor'];
            }
            $this->set(compact('prefactura', 'arrOrdenT', 'ttalAbonos', 'id', 'estados'));
            $this->set(compact('usuarioId','empresaId','tipoPago','notaFactura','vendedor','relacionEmpresa', 'cuentas', 'urlImgWP')); 
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

        $this->loadModel('Prefacturasdetalle');
        $this->loadModel('Cargueinventario');
        $this->loadModel('OrdentrabajosSuministro');
        $this->autoRender = false;
        $posData = $this->request->data;
        $usuarioId = $posData['usuarioId'];            
        $clienteId = $posData['clienteId'];
        $cargueinventarioId = $posData['cargueinventarioId'];
        $cantidadventa = $posData['cantidadventa'];
        $precioventa = $posData['precioventa'];
        $valorDescuento = isset($posData['valorDescuento']) && !empty($posData['valorDescuento']) ? $posData['valorDescuento'] : 0;
        $porcentajeDescuento = isset($posData['porcentajeDescuento']) && !empty($posData['porcentajeDescuento']) ? $posData['porcentajeDescuento'] : 0;
        $impuesto = $posData['impuesto'];
        
        if(isset($posData['prefactId']) && !empty($posData['prefactId'])){
            $prefactId = $posData['prefactId'];
            $arrPrefactId = $this->Prefactura->obtenerPrefacturaPorId($prefactId);
        }else{
            /*Se valida si existe la prefactura*/
//            $arrPrefactId = $this->Prefactura->obtenerPrefacturaId($clienteId);
//            if(isset($arrPrefactId['Prefactura'])){
//                $prefactId = $arrPrefactId['Prefactura']['id'];
//            }else{
                /*Se guarda la prefactura y se obtiene el id para almacenar el detalle*/
                $prefactId = $this->Prefactura->guardarPrefactura($usuarioId,$clienteId);                 
//            }             
        }
            

        /*se descuenta la cantidad del producto prefacturado del inventario*/
        /*se obtiene la cantidad existente en el stock*/
        $inventActual = $this->Cargueinventario->obtenerInventarioId($cargueinventarioId);
        $cantStock = $inventActual['Cargueinventario']['existenciaactual'];
        $existFinal = $cantStock - $cantidadventa;

        /*se actualiza la cantidad en stock tras la prefactura*/
        $this->Cargueinventario->actalizarExistenciaStock($cargueinventarioId, $existFinal);
        if($prefactId != '0'){
            $detalleId = $this->Prefacturasdetalle->guardarDetallePrefactura($cantidadventa,$precioventa,$cargueinventarioId,
                    $prefactId, $valorDescuento, $porcentajeDescuento, $impuesto);
            
            //guarda el producto en la orden de trabajo
            if(!empty($arrPrefactId['Prefactura']['ordentrabajo_id'])){            
                $this->OrdentrabajosSuministro->guardarSuministroOrden($arrPrefactId['Prefactura']['ordentrabajo_id'], $cargueinventarioId, $cantidadventa);
            }
            
            if($detalleId != '0' && $detalleId != ""){
                echo json_encode(array('resp' => $detalleId, 'prefactId' => $prefactId));
            }else{
                echo json_encode(array('resp' => $detalleId, 'prefactId' => $prefactId));
            }
        }
    }

        
/**
 * add method
 *
 * @return void
 */
	public function addProductoBarCode() {
            $this->loadModel('Prefacturasdetalle');
            $this->loadModel('Cargueinventario');
            $this->loadModel('Deposito');
            
            $this->autoRender = false;
            $posData = $this->request->data;

            $usuarioId = $posData['usuarioId']; 
            if(isset($posData['clienteId'])){
                $clienteId = $posData['clienteId'];
            }else{
                $clienteId = null;
            }
            
            $descripcionProd = $posData['descProducto'];
            
            /*Se obtienen los depositos en los cuales está el usuario*/
            $arrDepositos = $this->Deposito->obtenerDepositoUsuario($usuarioId);

            /*Se obtiene el id del deposito*/
            $depositosId = array();
            foreach ($arrDepositos as $dpIdx){
                $depositosId[] = $dpIdx['Deposito']['id'];
            }            
            
            //Se obtiene el producto con el codigo obtenido del lector de codigos de barras
            //que se encuentren en el stock del deposito al cual pertenece el vendedor
            $produtoInfo = $this->Cargueinventario->obtenerProductosStock($depositosId, $descripcionProd);
            
            /*valida si se encuentra el producto descrito por el codigo de barras*/
            if(count($produtoInfo) <= '0'){
                    $mensaje = "No se cuentra el producto en stock con el codigo " . $descripcionProd;
                    echo json_encode(array('valido' => false, 'mensaje' => $mensaje));
            }else{
                /*Se obtiene el id del producto en stock*/
                $cargueinventarioId = $produtoInfo['0']['Cargueinventario']['id'];

                /*se asigna la cantidad de 1 ya que es por medio del lector de codigos de barras*/
                $cantidadventa = '1';

                /*Se asigna el precio de venta sugerido al producto*/
                $precioventa = $produtoInfo['0']['Cargueinventario']['precioventa'];

                /*Se valida si existe la prefactura*/
                $arrPrefactId = $this->Prefactura->obtenerPrefacturaId($clienteId);
                if(isset($arrPrefactId['Prefactura'])){
                    $prefactId = $arrPrefactId['Prefactura']['id'];
                }else{
                    /*Se guarda la prefactura y se obtiene el id para almacenar el detalle*/
                    $prefactId = $this->Prefactura->guardarPrefactura($usuarioId,$clienteId);                 
                }             

                /*se descuenta la cantidad del producto prefacturado del inventario*/
                /*se obtiene la cantidad existente en el stock*/
                $inventActual = $this->Cargueinventario->obtenerInventarioId($cargueinventarioId);
                $cantStock = $inventActual['Cargueinventario']['existenciaactual'];
                $existFinal = $cantStock - $cantidadventa;

                /*se valida la disponibilidad del producto*/
                if($existFinal < 0){
                    $mensaje = "Por favor valide la disponibilidad del producto en stock";
                    echo json_encode(array('valido' => false, 'mensaje' => $mensaje));
                }else{
                    /*se actualiza la cantidad en stock tras la prefactura*/
                    $this->Cargueinventario->actalizarExistenciaStock($cargueinventarioId, $existFinal);

                    if($prefactId != '0'){
                        $detalleId = $this->Prefacturasdetalle->guardarDetallePrefactura($cantidadventa,$precioventa,$cargueinventarioId,$prefactId);
                        if($detalleId != '0' && $detalleId != ""){
                            echo json_encode(array('resp' => $detalleId, 'valido' => true, 'producto' => $produtoInfo));
                        }else{
                            echo json_encode(array('resp' => $detalleId, 'valido' => false));
                        }
                    }                
                }                
            }            
	}
        

	public function addProductoClienteNuevoBarCode() {
            $this->loadModel('Prefacturasdetalle');
            $this->loadModel('Cargueinventario');
            $this->loadModel('Deposito');
            $this->loadModel('Prefactura');
            
            $this->autoRender = false;
            $posData = $this->request->data;

            $usuarioId = $posData['usuarioId']; 
            $prefacturaId = $posData['prefacturaId'];                       
            $descripcionProd = $posData['descProducto'];
            
            /*Se obtienen los depositos en los cuales está el usuario*/
            $arrDepositos = $this->Deposito->obtenerDepositoUsuario($usuarioId);

            /*Se obtiene el id del deposito*/
            $depositosId = array();
            foreach ($arrDepositos as $dpIdx){
                $depositosId[] = $dpIdx['Deposito']['id'];
            }            
            
            //Se obtiene el producto con el codigo obtenido del lector de codigos de barras
            //que se encuentren en el stock del deposito al cual pertenece el vendedor
            $produtoInfo = $this->Cargueinventario->obtenerProductosStock($depositosId, $descripcionProd);
            
            /*valida si se encuentra el producto descrito por el codigo de barras*/
            if(count($produtoInfo) <= '0'){
                    $mensaje = "No se cuentra el producto en stock con el codigo " . $descripcionProd;
                    echo json_encode(array('valido' => false, 'mensaje' => $mensaje));
            }else{
                /*Se obtiene el id del producto en stock*/
                $cargueinventarioId = $produtoInfo['0']['Cargueinventario']['id'];

                /*se asigna la cantidad de 1 ya que es por medio del lector de codigos de barras*/
                $cantidadventa = '1';

                /*Se asigna el precio de venta sugerido al producto*/
                $precioventa = $produtoInfo['0']['Cargueinventario']['precioventa'];

                /*Se valida si existe la prefactura*/
                if($prefacturaId == "" || $prefacturaId == NULL){
                    /*Se guarda la prefactura y se obtiene el id para almacenar el detalle*/
                    $prefacturaId = $this->Prefactura->guardarPrefactura($usuarioId,$clienteId = null); 
                }             

                /*se descuenta la cantidad del producto prefacturado del inventario*/
                /*se obtiene la cantidad existente en el stock*/
                $inventActual = $this->Cargueinventario->obtenerInventarioId($cargueinventarioId);
                $cantStock = $inventActual['Cargueinventario']['existenciaactual'];
                $existFinal = $cantStock - $cantidadventa;

                /*se valida la disponibilidad del producto*/
                if($existFinal < 0){
                    $mensaje = "Por favor valide la disponibilidad del producto en stock";
                    echo json_encode(array('valido' => false, 'mensaje' => $mensaje));
                }else{
                    /*se actualiza la cantidad en stock tras la prefactura*/
                    $this->Cargueinventario->actalizarExistenciaStock($cargueinventarioId, $existFinal);

                    $detalleId = $this->Prefacturasdetalle->guardarDetallePrefactura($cantidadventa,$precioventa,$cargueinventarioId,$prefacturaId);
                    if($detalleId != '0' && $detalleId != ""){
                        echo json_encode(array('resp' => $detalleId, 'valido' => true, 'producto' => $produtoInfo, 'prefact' => $prefacturaId));
                    }else{
                        echo json_encode(array('resp' => $detalleId, 'valido' => false));
                    }
                }                
            }            
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
            		
		if (!$this->Prefactura->exists($id)) {
			throw new NotFoundException(__('Invalid prefactura'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Prefactura->save($this->request->data)) {
				$this->Session->setFlash(__('The prefactura has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The prefactura could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Prefactura.' . $this->Prefactura->primaryKey => $id));
			$this->request->data = $this->Prefactura->find('first', $options);
		}
		$usuarios = $this->Prefactura->Usuario->find('list');
		$clientes = $this->Prefactura->Cliente->find('list');
		$this->set(compact('usuarios', 'clientes'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
            $this->loadModel('Prefacturasdetalle');
            $this->loadModel('Cargueinventario');
            
            /*se obtiene el detalle de la prefactura que se va eliminar*/
            $prefactDet = $this->Prefacturasdetalle->obtenerProductosPrefacturaPrefactId($id);

            /*se recorren los detalles de la factura para actualizar el stock y eliminar el registro*/
            for( $i = 0; $i < count($prefactDet); $i++){ 
                
                /*se restaura la cantidad en el inventario*/
                $cantFinal = $prefactDet[$i]['Prefacturasdetalle']['cantidad'] + $prefactDet[$i]['Cargueinventario']['existenciaactual'];
                
                /*se actualiza la cantidad en el stock*/
                if($this->Cargueinventario->actalizarExistenciaStock($prefactDet[$i]['Prefacturasdetalle']['cargueinventario_id'], $cantFinal)){
                    /*una vez actualizado el inventario, se elimina el registro del detalle de la factura*/
                    $this->Prefacturasdetalle->delete($prefactDet[$i]['Prefacturasdetalle']['id']);
                }
            }
            
            
            $this->Prefactura->id = $id;
            if (!$this->Prefactura->exists()) {
                    throw new NotFoundException(__('La Orden de Pedido no existe.'));
            }
            $this->request->onlyAllow('post', 'delete');
            if ($this->Prefactura->delete()) {
                    $this->Session->setFlash(__('La Orden de Pedido ha sido eliminada.'));
            } else {
                    $this->Session->setFlash(__('La Orden de Pedido no pudo ser eliminada. Por favor, inténtelo de nuevo.'));
            }
            return $this->redirect(array('action' => 'index'));
	}
        
        public function actualizarPorcentajeValorDtto(){
            $this->loadModel('Prefacturasdetalle');

            $this->autoRender = false;
            $posData = $this->request->data; 
            
            $porcentaje = $posData['nuevoPor'];
            $valDtto = $posData['valDtto'];
            $id = $posData['idPref'];
            
            $resp = $this->Prefacturasdetalle->actualizarPorcentajeValorDescuento($id, $valDtto, $porcentaje);

            if($resp){
                echo json_encode(array('resp' => '1'));
            }else{
                echo json_encode(array('resp' => '0'));
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
        
        /**
         * obtiene informacion completa de la prefactura para imprimir la misma
         */
        public function ajaxObtenerDetallesPrefactura(){
            $this->loadModel('Prefacturasdetalle');

            $this->autoRender = false;
            $prefactId = $this->request->data['prefactId'];

            //se obtiene el detalle de la prefactura
            $arrPrefactDet = $this->Prefactura->obtenerPrefacturaDetalle($prefactId);

            echo json_encode(array('resp' => $arrPrefactDet));   
        }
        
        /**
         * Guarda la observacion de la prefactura
         */
        public function guardarObservacionPrefact(){
            $this->loadModel('Prefactura');
            $this->autoRender = false;
            
            $prefactId = $this->request->data['prefact'];
            $observacion = $this->request->data['observacion'];
            
            $resp = $this->Prefactura->actualizarObservacionPrefact($prefactId, $observacion);
            
            echo json_encode(array('resp' => $resp));  
        }
        
        
        public function ajaxActualizarClientePrefactura(){
            $this->loadModel('Prefactura');
            $this->autoRender = false;
            
            $prefactId = $this->request->data['prefact'];
            $clienteId = $this->request->data['cliente_id'];
            
            $resp = $this->Prefactura->actualizarClientePrefactura($prefactId, $clienteId);
            
            echo json_encode(array('resp' => $resp));              
        }

        
        public function ajaxActualizarEstado(){
            $this->loadModel('Prefactura');
            $this->autoRender = false;
            
            $estadoId = $this->request->data['estadoId'];
            $prefactId = $this->request->data['prefactId'];
            
            $resp = $this->Prefactura->actualizarEstadoPrefactura($prefactId, $estadoId);
            
            echo json_encode(array('resp' => $resp));
        }
        
}
