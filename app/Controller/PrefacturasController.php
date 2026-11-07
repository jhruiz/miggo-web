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

            $empresaId = $this->Auth->user('empresa_id');
            
            //se obtienen las ordenes de pedido con la informacion de la orden de trabajo y vehiculo
            $prefacturas = $this->Prefactura->obtenerPrefacturas($usuarioId, $placa, $cliente, $empresaId);

            //se obtiene el listado de prefacturas
            $estados = $this->Estadosprefactura->obtenerListaEstados();
            
            //obtiene el detalle de las prefacturas
            $arrPrefacturasDetalle = $this->Prefacturasdetalle->obtenerDetallePrefacturas($empresaId);            
                     
            //se cuenta el valor total de las prefacturas
            $prefactValor = 0;
            if(!empty($arrPrefacturasDetalle)){                
                foreach ($arrPrefacturasDetalle as $pf){
                    
                    //se obtiene el valor de las prefacturas
                    if(!empty($pf['Prefacturasdetalle']['impuesto'])){
                        $impuesto = floatval("1." . $pf['Prefacturasdetalle']['impuesto']);                        
                        
                        //se calcula el valor base menos el descuento
                        $valBase = (ceil((floatval($pf['Prefacturasdetalle']['costoventa'] * $pf['Prefacturasdetalle']['cantidad'])/$impuesto))) - floatval($pf['Prefacturasdetalle']['descuento']);                        
                        $prefactValor += ceil($valBase * $impuesto);
                    }else{
                        $prefactValor += (floatval($pf['Prefacturasdetalle']['costoventa'] * $pf['Prefacturasdetalle']['cantidad']) - floatval($pf['Prefacturasdetalle']['descuento']));
                    }
                }
            }
            
            $nombre = !empty($this->passedArgs['cliente']) ? $this->passedArgs['cliente'] : '';
            $vehiculo = !empty($this->passedArgs['vehiculo']) ? $this->passedArgs['vehiculo'] : '';
            $this->set(compact('prefacturas', 'prefactValor', 'estados','cliente','vehiculo'));
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
            $this->loadModel('Estadopedido');
            $this->loadModel('Empresa');
            $this->loadModel('Canalventa');
            $this->loadModel('EmpresasTipoempresa');
            
            if (!$this->Prefactura->exists($id)) {
                    throw new NotFoundException(__('La orden de compra no existe.'));
            }
            $usuarioId = $this->Auth->user('id');
            $empresaId = $this->Auth->user('empresa_id');
            $tipoPago = $this->Tipopago->find('list');            
            $options = array('conditions' => array('Prefactura.' . $this->Prefactura->primaryKey => $id));
            
            // $notaFactura = $this->Notafactura->find('list');
            $notaFactura = $this->Notafactura->obtenerNotasFacturasEmpresa($empresaId);
            $vendedor = $this->Usuario->obtenerUsuarioEmpresa($empresaId);
            $relacionEmpresa = $this->Relacionempresa->obtenerListaEmpresasRelacion($empresaId);
            $arrEmprea = $this->Empresa->obtenerEmpresaPorId($empresaId);
                        
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
            
            /*Se obtiene la url de las imagenes de las empresas*/
            $strDato = "urlImgEmpresa";
            $urlImg = $this->Configuraciondato->obtenerValorDatoConfig($strDato);
            
            //se obtienen los abonos realizados a la prefactura
            $abonos = $this->Abonofactura->obtenerAbonosPrefactura($prefactura['Prefactura']['id']);
            
            //se obtiene el listado de las prefacturas
            $estados = $this->Estadosprefactura->obtenerListaEstados();

            // Se obtiene el listado de canal de ventas
            $canalventas = $this->Canalventa->obtenerCanalVentas($empresaId);

            // Se obtiene el listaod de estados del pedido
            $estadosPedido = $this->Estadopedido->obtenerListaEstadoPedidos(); 

            // Datos de la orden
            $numOrden = $prefactura['Prefactura']['numeroorden'];
            $fechaOrden = $prefactura['Prefactura']['fechaorden'];

            // Se obtiene el tipo de empresa
            $tipoEmpresa = $this->EmpresasTipoempresa->obtenerTipoEmpresa( $empresaId );
            $strTipoEmpresa = '';
            if( !empty($tipoEmpresa) ) {
                foreach($tipoEmpresa as $te) {
                    $strTipoEmpresa == '' ? $strTipoEmpresa .= $te['TE']['codigo'] : $strTipoEmpresa .= '-' . $te['TE']['codigo'];
                }
            }

            // ── FASE 3: selección de sede (centro de costo) ANTES de facturar ──
            $this->loadModel('Centroscosto');

            // Garantiza que el usuario tenga al menos un centro de costo.
            $this->Centroscosto->garantizarCentroUsuario(
                $usuarioId,
                $empresaId,
                $arrEmprea['Empresa']['nombre']
            );

            // Centros de costo del usuario (cada uno = una empresa/sede)
            $centrosUsuario = $this->Centroscosto->obtenerCentrosPorUsuario($usuarioId);
            $centrosSede = array();
            foreach ($centrosUsuario as $cc) {
                $centrosSede[] = array(
                    'id'         => (int) $cc['Centroscosto']['id'],
                    'nombre'     => $cc['Centroscosto']['nombre'],
                    'empresa_id' => (int) $cc['Centroscosto']['empresa_id'],
                );
            }

            // Regla: 1 sede → autoselecciona; 2+ → modal bloqueante.
            // Al entrar se limpia la sede activa (salvo recarga tras confirmar en el
            // modal), para que el usuario pueda corregir si eligió mal.
            $requiereSeleccionSede = false;
            if (count($centrosSede) === 1) {
                $this->fijarEmpresaActiva($centrosSede[0]['empresa_id'], $centrosSede[0]['id']);
            } elseif (count($centrosSede) === 0) {
                $this->fijarEmpresaActiva($empresaId, 0);
            } else {
                if ($this->Session->read('Facturacion.sedeRecienConfirmada')) {
                    $this->Session->delete('Facturacion.sedeRecienConfirmada');
                } else {
                    $this->limpiarEmpresaActiva();
                }
                if (!$this->centrocostoActivo()) {
                    $requiereSeleccionSede = true;
                }
            }

            // Centro de costo a usar en el hidden del formulario:
            //   - el activo recién elegido/autoseleccionado, o
            //   - el ya guardado en la prefactura (al reabrir una existente)
            $centrocostoActivo = $this->centrocostoActivo();
            $centrocostoPrefactura = $centrocostoActivo
                ? $centrocostoActivo
                : (!empty($prefactura['Prefactura']['centrocosto_id']) ? $prefactura['Prefactura']['centrocosto_id'] : '');

            // ── Recargar los datos de apoyo con la EMPRESA ACTIVA (sede) ──
            // Los combos de vendedor, canal, notas, cuentas, tipos de pago y los
            // datos de empresa deben corresponder a la sede sobre la que se factura.
            $empresaFacturacion = $this->empresaActiva();
            if ($empresaFacturacion != $empresaId) {
                $empresaId       = $empresaFacturacion;
                $notaFactura     = $this->Notafactura->obtenerNotasFacturasEmpresa($empresaId);
                $vendedor        = $this->Usuario->obtenerUsuarioEmpresa($empresaId);
                $relacionEmpresa = $this->Relacionempresa->obtenerListaEmpresasRelacion($empresaId);
                $arrEmprea       = $this->Empresa->obtenerEmpresaPorId($empresaId);
                $cuentas         = $this->Cuenta->obtenerCuentasEmpresa($empresaId);
                $canalventas     = $this->Canalventa->obtenerCanalVentas($empresaId);
            }

            $ttalAbonos = 0;
            foreach ($abonos as $abn){
                $ttalAbonos += $abn['Abonofactura']['valor'];
            }

            $this->set(compact('prefactura', 'arrOrdenT', 'ttalAbonos', 'id', 'estados', 'arrEmprea', 'urlImg', 'numOrden', 'fechaOrden', 'estadosPedido'));
            $this->set(compact('usuarioId','empresaId','tipoPago','notaFactura','vendedor','relacionEmpresa', 'cuentas', 'urlImgWP', 'canalventas', 'strTipoEmpresa'));
            $this->set(compact('centrosSede', 'requiereSeleccionSede', 'centrocostoActivo', 'centrocostoPrefactura')); 
	}

/**
 * add method
 *
 * @return void
 */
    public function add() {
        $this->loadModel('Prefacturasdetalle');
        $this->loadModel('Cargueinventario');
        $this->loadModel('OrdentrabajosSuministro');

        $this->autoRender = false;

        $posData = $this->request->data;
        $usuarioId = $posData['usuarioId'];            
        $clienteId = $posData['clienteId'];
        $esFactura = $posData['esFactura'];
        $cargueinventarioId = $posData['cargueinventarioId'];
        $cantidadventa = $posData['cantidadventa'];
        $precioventa = $posData['precioventa'];
        $valorDescuento = $posData['valorDescuento'];
        $porcentajeDescuento = $posData['porcentajeDescuento'];
        $impuesto = $posData['impuesto'];
        $prcINC = $posData['prcINC'];
        $valINCBolsa = $posData['valINCBolsa'];
        
        if(isset($posData['prefactId']) && !empty($posData['prefactId'])){
            $prefactId = $posData['prefactId'];
            $arrPrefactId = $this->Prefactura->obtenerPrefacturaPorId($prefactId);
        }else{
            /*Se guarda la prefactura y se obtiene el id para almacenar el detalle*/
            $prefactId = $this->Prefactura->guardarPrefactura($usuarioId, $clienteId, null, $esFactura);                 
        }
            
        /*se descuenta la cantidad del producto prefacturado del inventario*/
        /*se obtiene la cantidad existente en el stock*/
        $inventActual = $this->Cargueinventario->obtenerInventarioId($cargueinventarioId);
        $cantStock = $inventActual['Cargueinventario']['existenciaactual'];
        $existFinal = $cantStock - $cantidadventa;

        // se valida si el producto está configurado para vender con inventario
        if($inventActual['Producto']['inventario'] == '1') {
            /*se actualiza la cantidad en stock tras la prefactura*/
            $this->Cargueinventario->actalizarExistenciaStock($cargueinventarioId, $existFinal);
        }

        if($prefactId != '0'){

            $detalleId = $this->Prefacturasdetalle->guardarDetallePrefactura($cantidadventa,$precioventa,$cargueinventarioId,
                    $prefactId, $valorDescuento, $porcentajeDescuento, $impuesto, $prcINC, $valINCBolsa);
            
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
            $this->loadModel('CargueinventariosImpuesto');
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
            $cargueInvId = $posData['cargueInvId'];
            $esFactura = $posData['esFactura'];

            /*Se obtienen los depositos de la SEDE ACTIVA (centro de costo).
              empresaActiva() cae a la empresa del usuario si no hay sede fijada.*/
            $depositosId = $this->Deposito->obtenerDepositosEmpresaIds($this->empresaActiva());
            
            //Se obtiene el producto con el codigo obtenido del lector de codigos de barras
            //que se encuentren en el stock del deposito de la sede activa
            if( $descripcionProd != '' ) {
                $produtoInfo = $this->Cargueinventario->obtenerProductosStock($depositosId, $descripcionProd);
            } else {
                $produtoInfo = $this->Cargueinventario->obtenerProductosStockPorInvId($depositosId, $cargueInvId);
            }
            
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
                    $prefactId = $this->Prefactura->guardarPrefactura($usuarioId, $clienteId, null, $esFactura);                 
                }   
                
                if($produtoInfo['0']['Producto']['inventario'] == '1'){
                    /*se descuenta la cantidad del producto prefacturado del inventario*/
                    /*se obtiene la cantidad existente en el stock*/
                    $inventActual = $this->Cargueinventario->obtenerInventarioId($cargueinventarioId);
                    $cantStock = $inventActual['Cargueinventario']['existenciaactual'];
                    $existFinal = $cantStock - $cantidadventa;
                }

                /*se valida la disponibilidad del producto*/
                if($existFinal < 0){
                    $mensaje = "Por favor valide la disponibilidad del producto en stock";
                    echo json_encode(array('valido' => false, 'mensaje' => $mensaje));
                }else{

                    if($produtoInfo['0']['Producto']['inventario'] == '1'){
                        /*se actualiza la cantidad en stock tras la prefactura*/
                        $this->Cargueinventario->actalizarExistenciaStock($cargueinventarioId, $existFinal);
                    }

                    if($prefactId != '0'){

                        $impuesto = $this->CargueinventariosImpuesto->obtenerImpuestosProducto($cargueinventarioId);
                        
                        $tasaIvaPorc = 0;
                        $tasaIncPorc = 0;
                        $tasaBolsaVal = 0;
                        if ( $esFactura == '1') {
                            foreach( $impuesto as $imp ) {
    
                                // tratamiento para el IVA
                                if( $imp['TX']['code'] ==  '01' ) {
                                    $tasaIvaPorc = (string) ($imp['IMP']['valor'] ?? 0);
                                }
                                
                                // tratamiento para el INC
                                if( $imp['TX']['code'] == '04' ) {
                                    $tasaIncPorc = (string) ($imp['IMP']['valor'] ?? 0);     
                                }
    
                                // tratamiento para el impuesto a la bolsa
                                if( $imp['TX']['code'] == '22' ) {
                                    $tasaBolsaVal = (string) ($imp['IMP']['valor'] ?? 0);
                                }   
                            }
                        }

                        $detalleId = $this->Prefacturasdetalle->guardarDetallePrefactura($cantidadventa, $precioventa, $cargueinventarioId, $prefactId, 0, 0, $tasaIvaPorc, $tasaIncPorc, $tasaBolsaVal);

                        if($detalleId != '0' && $detalleId != ""){
                            echo json_encode(array('resp' => $detalleId, 'valido' => true, 'producto' => $produtoInfo, 'tasaIvaPorc' => $tasaIvaPorc, 'tasaIncPorc' => $tasaIncPorc, 'tasaBolsaVal' => $tasaBolsaVal,  'prefactId' => $prefactId));
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
            $this->loadModel('CargueinventariosImpuesto');
            $this->loadModel('Deposito');
            
            $this->autoRender = false;
            $posData = $this->request->data;

            $usuarioId = $posData['usuarioId']; 
            $prefacturaId = $posData['prefacturaId'];                       
            $descripcionProd = $posData['descProducto'];
            $esFactura = $posData['esFactura'];

            /*Se obtienen los depositos de la SEDE ACTIVA (centro de costo).
              empresaActiva() cae a la empresa del usuario si no hay sede fijada.*/
            $depositosId = $this->Deposito->obtenerDepositosEmpresaIds($this->empresaActiva());
            
            //Se obtiene el producto con el codigo obtenido del lector de codigos de barras
            //que se encuentren en el stock del deposito de la sede activa
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
                    $prefacturaId = $this->Prefactura->guardarPrefactura($usuarioId, null, null, $esFactura); 
                }      
                
                /*Se obtiene el impuesto del producto */
                $impuesto = $this->CargueinventariosImpuesto->obtenerImpuestosProducto($cargueinventarioId);
                if(empty($impuesto) || $esFactura == '0'){
                    $impto = 0;
                }else{
                    $impto = $impuesto['0']['Impuesto']['valor'];
                }

                if($produtoInfo['0']['Producto']['inventario'] == '1'){
                    /*se descuenta la cantidad del producto prefacturado del inventario*/
                    /*se obtiene la cantidad existente en el stock*/
                    $inventActual = $this->Cargueinventario->obtenerInventarioId($cargueinventarioId);
                    $cantStock = $inventActual['Cargueinventario']['existenciaactual'];
                    $existFinal = $cantStock - $cantidadventa;
                }

                /*se valida la disponibilidad del producto*/
                if($existFinal < 0){
                    $mensaje = "Por favor valide la disponibilidad del producto en stock";
                    echo json_encode(array('valido' => false, 'mensaje' => $mensaje));
                }else{

                    if($produtoInfo['0']['Producto']['inventario'] == '1'){
                        /*se actualiza la cantidad en stock tras la prefactura*/
                        $this->Cargueinventario->actalizarExistenciaStock($cargueinventarioId, $existFinal);
                    }

                    if($prefacturaId != '0') {
                        
                        $impuesto = $this->CargueinventariosImpuesto->obtenerImpuestosProducto($cargueinventarioId);
                        
                        $tasaIvaPorc = 0;
                        $tasaIncPorc = 0;
                        $tasaBolsaVal = 0;
                        if ( $esFactura == '1') {
                            foreach( $impuesto as $imp ) {

                                // tratamiento para el IVA
                                if( $imp['TX']['code'] ==  '01' ) {
                                    $tasaIvaPorc = (string) ($imp['IMP']['valor'] ?? 0);
                                }
                                
                                // tratamiento para el INC
                                if( $imp['TX']['code'] == '04' ) {
                                    $tasaIncPorc = (string) ($imp['IMP']['valor'] ?? 0);     
                                }

                                // tratamiento para el impuesto a la bolsa
                                if( $imp['TX']['code'] == '22' ) {
                                    $tasaBolsaVal = (string) ($imp['IMP']['valor'] ?? 0);
                                }   
                            }
                        }
                        
                        $detalleId = $this->Prefacturasdetalle->guardarDetallePrefactura($cantidadventa, $precioventa, $cargueinventarioId, $prefacturaId, 0, 0, $tasaIvaPorc, $tasaIncPorc, $tasaBolsaVal);
                        
                        if($detalleId != '0' && $detalleId != ""){
                            echo json_encode(array('resp' => $detalleId, 'valido' => true, 'producto' => $produtoInfo, 'tasaIvaPorc' => $tasaIvaPorc, 'tasaIncPorc' => $tasaIncPorc, 'tasaBolsaVal' => $tasaBolsaVal,  'prefactId' => $prefacturaId));
                        }else{
                            echo json_encode(array('resp' => $detalleId, 'valido' => false));
                        }
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
            $this->loadModel('Prefactura');
            $this->loadModel('Producto');
            $this->loadModel('Ordenestado');
            $this->loadModel('Ordentrabajo');

            /*se obtiene el detalle de la prefactura que se va eliminar*/
            $prefactDet = $this->Prefacturasdetalle->obtenerProductosPrefacturaPrefactId($id);

            /*se recorren los detalles de la factura para actualizar el stock y eliminar el registro*/
            for( $i = 0; $i < count($prefactDet); $i++){ 

                //se obtiene la información del producto
                $producto = $this->Producto->obtenerInfoProducto($prefactDet[$i]['Cargueinventario']['producto_id']);
                
                if($producto['Producto']['inventario'] == '1') {
                    /*se restaura la cantidad en el inventario*/
                    $cantFinal = $prefactDet[$i]['Prefacturasdetalle']['cantidad'] + $prefactDet[$i]['Cargueinventario']['existenciaactual'];
                    
                    if(empty($prefactDet['0']['Prefactura']['ordentrabajo_id'])){
                        /*se actualiza la cantidad en el stock*/
                        $this->Cargueinventario->actalizarExistenciaStock($prefactDet[$i]['Prefacturasdetalle']['cargueinventario_id'], $cantFinal);
                    }
                }

                /*una vez actualizado el inventario, se elimina el registro del detalle de la factura*/
                $this->Prefacturasdetalle->delete($prefactDet[$i]['Prefacturasdetalle']['id']);
            }

            //Si tiene orden de trabajo asociada, se cambia el estado a inicial
            if( $prefactDet['0']['Prefactura']['ordentrabajo_id'] > 0 ) {
                //se obtiene el estado inicial
                $estadoOrden = $this->Ordenestado->obtenerEstadoInicial();

                //se actualiza el estado de la orden de trabajo
                $data['id'] = $prefactDet['0']['Prefactura']['ordentrabajo_id'];
                $data['ordenestado_id'] = $estadoOrden['Ordenestado']['id'];
                $ordenId = $this->Ordentrabajo->crearActualizarOrdenTrabajo($data);
            }
            
            $this->Prefactura->id = $id;
            if (!$this->Prefactura->exists()) {
                    throw new NotFoundException(__('La Orden de Pedido no existe.'));
            }
          
            if ($this->Prefactura->actualizarEstadoPrefacturaEliminar($id)) {
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
            $this->loadModel('Configuraciondato');
            $this->loadModel('Empresa');
            $this->loadModel('Relacionempresa');

            $this->autoRender = false;
            $prefactId = $this->request->data['prefactId'];

            //se obtiene el detalle de la prefactura
            $arrPrefactDet = $this->Prefactura->obtenerPrefacturaDetalle($prefactId);

            $empresaId = !empty($this->empresaActiva()) ? $this->empresaActiva() : $this->Auth->user('empresa_id');

            //se obtiene la información de la empresa relacionada
            $infoRelEmpresa = $this->Relacionempresa->obtenerEmpresaRelacionadaPorId( $empresaId );
            
            $arrPrefactDet['0']['EM'] = $infoRelEmpresa['Empresa']; 
            $arrPrefactDet['0']['RE'] = $infoRelEmpresa['Relacionempresa']; 

            //se obtiene la ruta de la imagen de la empresa
            $strDato = "urlImgEmpresa";
            $urlImg = $this->Configuraciondato->obtenerValorDatoConfig($strDato);

            echo json_encode(array('resp' => $arrPrefactDet, 'urlImg' => $urlImg));   
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
        
        public function ajaxActualizarEstadoPedido(){
            $this->loadModel('Prefactura');
            $this->autoRender = false;
            
            $estadopedidoId = $this->request->data['estadoPedidoId'];
            $prefactId = $this->request->data['prefactId'];
            
            $resp = $this->Prefactura->actualizarEstadoPedido($prefactId, $estadopedidoId);
            
            echo json_encode(array('resp' => $resp));
        }

        /**
         * Guarda la fecha de la orden
         */
        public function guardarFechaOrden(){
            $this->loadModel('Prefactura');
            $this->autoRender = false;

            $fechaOrden = $this->request->data['fechaorden'];
            $prefacturaId = $this->request->data['prefact'];

            $resp = $this->Prefactura->guardarFechaOrdenPrefact($prefacturaId, $fechaOrden);

            echo json_encode(array('resp' => $resp));
        }

        /**
         * Guarda la fecha de la orden
         */
        public function guardarNumeroOrden(){
            $this->loadModel('Prefactura');
            $this->autoRender = false;

            $numOrden = $this->request->data['numeroorden'];
            $prefacturaId = $this->request->data['prefact'];

            $resp = $this->Prefactura->guardarNumOrdenPrefact($prefacturaId, $numOrden);

            echo json_encode(array('resp' => $resp));
        }

        public function asignarserialprefacturaid() {
            $this->loadModel('Serialesmoto');
            $this->autoRender = false;

            $color = $this->request->data['color'];
            $modelo = $this->request->data['modelo'];
            $serialId = $this->request->data['serialId'];
            $prefactDetalleId = $this->request->data['prefactDetalleId'];

            $resp = $this->Serialesmoto->asociarPrefacturaSeriales( $color, $modelo, $serialId, $prefactDetalleId );

            echo json_encode(array('resp' => $resp));
        }

        
}
