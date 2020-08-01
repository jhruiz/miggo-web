<?php
App::uses('AppController', 'Controller');
/**
 * Cuentasclientes Controller
 *
 * @property Cuentascliente $Cuentascliente
 * @property PaginatorComponent $Paginator
 */
class CuentasclientesController extends AppController {

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
            $this->loadModel('Ventarapida');
            $empresaId = $this->Auth->user('empresa_id');
//            $paginate['Cuentascliente.empresa_id'] = $empresaId;
//            $this->Cuentascliente->recursive = 0;
//            $cuentasclientes = $this->Paginator->paginate('Cuentascliente',$paginate);
            $cuentasclientes = $this->Cuentascliente->obtenerCuentasClientes($empresaId);
            $fechaActual = date('Y-m-d');
            $costoTotal = 0;
            $costoVencido = 0;
            $costoVigente = 0;
            for($i = 0; $i < count($cuentasclientes); $i++){
                if($cuentasclientes[$i]['Cuentascliente']['cliente_id'] != ""){
                    $cuentasclientes[$i]['Cuentascliente']['fechalimitepago'] = $this->sumarDiasFecha($cuentasclientes[$i]['Cuentascliente']['created'],$cuentasclientes[$i]['CL']['diascredito']);
                }else{
                    $infoVentaRapida = $this->Ventarapida->obtenerInfoVentaFactId($cuentasclientes[$i]['Factura']['id']);

                    if(count($infoVentaRapida) > 0){
                        $cuentasclientes[$i]['CL']['nombre'] = $infoVentaRapida['Ventarapida']['cliente'];
                    }else{
                        $cuentasclientes[$i]['CL']['nombre'] = "";
                    }
                    $cuentasclientes[$i]['Cuentascliente']['fechalimitepago'] = "";
                }
           
                $diff = $this->diffFechas($fechaActual, $cuentasclientes[$i]['Cuentascliente']['fechalimitepago']);                
                        
                if($cuentasclientes[$i]['Cuentascliente']['totalobligacion'] > $cuentasclientes[$i]['CL']['limitecredito']){                    
                    $cuentasclientes[$i]['Cuentascliente']['limitecredito'] = 'text-danger';
                }else{
                    $cuentasclientes[$i]['Cuentascliente']['limitecredito'] = 'text';                    
                }                
                    
                    
                if($diff <= '0'){
                    $cuentasclientes[$i]['Cuentascliente']['color'] = 'danger';
                    $cuentasclientes[$i]['Cuentascliente']['diasvencido'] = $diff;
                    $costoVencido += $cuentasclientes[$i]['Cuentascliente']['totalobligacion'];
                }else{
                    $cuentasclientes[$i]['Cuentascliente']['color'] = 'success';
                    $cuentasclientes[$i]['Cuentascliente']['diasvencido'] = $diff;
                    $costoVigente += $cuentasclientes[$i]['Cuentascliente']['totalobligacion'];
                }
            } 
            $costoTotal = $costoVencido + $costoVigente;
            $this->set(compact('cuentasclientes', 'costoVencido', 'costoVigente', 'costoTotal'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Cuentascliente->exists($id)) {
			throw new NotFoundException(__('Invalid cuentascliente'));
		}
		$options = array('conditions' => array('Cuentascliente.' . $this->Cuentascliente->primaryKey => $id));
		$this->set('cuentascliente', $this->Cuentascliente->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Cuentascliente->create();
			if ($this->Cuentascliente->save($this->request->data)) {
				$this->Session->setFlash(__('The cuentascliente has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cuentascliente could not be saved. Please, try again.'));
			}
		}
		$documentos = $this->Cuentascliente->Documento->find('list');
		$depositos = $this->Cuentascliente->Deposito->find('list');
		$clientes = $this->Cuentascliente->Cliente->find('list');
		$usuarios = $this->Cuentascliente->Usuario->find('list');
		$empresas = $this->Cuentascliente->Empresa->find('list');
		$facturas = $this->Cuentascliente->Factura->find('list');
		$this->set(compact('documentos', 'depositos', 'clientes', 'usuarios', 'empresas', 'facturas'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Cuentascliente->exists($id)) {
			throw new NotFoundException(__('Invalid cuentascliente'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Cuentascliente->save($this->request->data)) {
				$this->Session->setFlash(__('The cuentascliente has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cuentascliente could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Cuentascliente.' . $this->Cuentascliente->primaryKey => $id));
			$this->request->data = $this->Cuentascliente->find('first', $options);
		}
		$documentos = $this->Cuentascliente->Documento->find('list');
		$depositos = $this->Cuentascliente->Deposito->find('list');
		$clientes = $this->Cuentascliente->Cliente->find('list');
		$usuarios = $this->Cuentascliente->Usuario->find('list');
		$empresas = $this->Cuentascliente->Empresa->find('list');
		$facturas = $this->Cuentascliente->Factura->find('list');
		$this->set(compact('documentos', 'depositos', 'clientes', 'usuarios', 'empresas', 'facturas'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Cuentascliente->id = $id;
		if (!$this->Cuentascliente->exists()) {
			throw new NotFoundException(__('Invalid cuentascliente'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Cuentascliente->delete()) {
			$this->Session->setFlash(__('The cuentascliente has been deleted.'));
		} else {
			$this->Session->setFlash(__('The cuentascliente could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
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
        
        public function diffFechas($fechaLimite, $fechaActual){
            $datetime1 = date_create($fechaLimite);
            $datetime2 = date_create($fechaActual);
            $interval = date_diff($datetime1, $datetime2);
            $dias = $interval->format('%R%a');
            return $dias;            
        }     
        
        public function obtenercuentacliente(){
            $this->loadModel('Tipopago');
            $posData = $this->request->data;
            $empresaId = $this->Auth->user('empresa_id');
            $cuentaId = $posData['pagoId'];
            
            //se obtienen los datos de la cuenta que se desea pagar
            $datosCuentaPendiente = $this->Cuentascliente->obtenerDatosCuentaPendienteId($cuentaId);

            //se obtienen las cuentas de la empresa
            $listaTiposPagos = $this->Tipopago->obtenerListaTiposPagos($empresaId);
            $this->set(compact('datosCuentaPendiente', 'listaTiposPagos', 'cuentaId'));
        }
        
        public function pagarcuentacliente(){
            $this->loadModel('Cuenta');
            $this->loadModel('Tipopago');
            $this->loadModel('Abonofactura');
            $this->autoRender = false;
            $posData = $this->request->data;
            
            $ttalPago = $posData['ttalPago'];
            $tipopagoId = $posData['tipopago'];
            $cuentaPendienteId = $posData['cuentapendiente'];
            $resp = true;
            
            //Se obtiene la informacion de la cuenta por cobrar
            $datosCuentaPendiente = $this->Cuentascliente->obtenerDatosCuentaPendienteId($cuentaPendienteId);
                        
            //Se obtiene la informacion de la cuenta a la que se sumara el pago
            $datosTipoPago = $this->Tipopago->obtenerTipoPagoPorId($tipopagoId);
            
            //se obtiene la informacion de la cuenta
            $datosCuenta = $this->Cuenta->obtenerDatosCuentaId($datosTipoPago['Tipopago']['cuenta_id']);
            
            //Se resta la cantidad paga de la cuenta pendiente
            $saldoCuentaPendiente = $datosCuentaPendiente['Cuentascliente']['totalobligacion'] - $ttalPago;
            
            if($this->Cuentascliente->actualizarCuentaCliente($cuentaPendienteId,$saldoCuentaPendiente)){
                //se actualiza el valor de la cuenta
                $nuevoSaldoCuenta = $datosCuenta['Cuenta']['saldo'] + $ttalPago;
                $this->Cuenta->actualizarSaldoCuenta($datosTipoPago['Tipopago']['cuenta_id'], $nuevoSaldoCuenta);
                
                $usuarioId = $this->Auth->user('id');
                
                //se guarda el registro del abono
                $this->Abonofactura->guardarAbonoFacturaCuentaCliente($datosCuentaPendiente['Cuentascliente']['factura_id'], 
                        $usuarioId, $ttalPago, $datosCuentaPendiente['Cuentascliente']['empresa_id'], 
                        $datosTipoPago['Tipopago']['cuenta_id'], $cuentaPendienteId,
                        $datosCuentaPendiente['Cuentascliente']['prefactura_id']);
                
            }else{
                $resp = false;
            }
            echo json_encode(array('resp' => $resp));
        } 
        
        public function eliminarcuentacliente(){
            $this->autoRender = false;
            $posData = $this->request->data;
            $posData['id'];
            $resp = $this->Cuentascliente->eliminarCuenta($posData['id']);
            echo $resp;
        }       
        
        /**
         * se obtienen los abonos por cuenta cliente id
         * @param type $id
         */
        public function verabonos($id = null){
            $this->loadModel('Abonofactura');
            $this->loadModel('Configuraciondato');
            
            //se obtiene la url de la imagend e whatsapp
            $strDatoWP = "ulrImgWP";
            $urlImgWP = $this->Configuraciondato->obtenerValorDatoConfig($strDatoWP);              
            
            //se obtiene el abono por cuenta cliente id
            $abonos = $this->Abonofactura->obtenerAbonosCuentasCliente($id);
            $this->set(compact('abonos', 'id', 'urlImgWP'));
        }
        
        public function ajaxobtenerabonos(){
            $this->loadModel('Abonofactura');
            $this->loadModel('Empresa');
            $this->loadModel('Relacionempresa');
            $this->autoRender = false;
            
            $posData = $this->request->data;
            $cuentaClienteId = $posData['cuentaClienteId'];
            $empresaId = $this->Auth->user('empresa_id');
            
            //se obtiene la informacion de los abonos
            $abonos = $this->Abonofactura->obtenerAbonosCuentasCliente($cuentaClienteId);            
            
            //se obtiene la informacion de la empresa relacionada
            $empRelacionada = $this->Relacionempresa->obtenerDatosEmpresaRemision($empresaId);
            
            echo json_encode(array('abonos' => $abonos, 'subemp' => $empRelacionada));
            
        }
}
