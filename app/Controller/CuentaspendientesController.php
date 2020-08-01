<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Cuentaspendientes Controller
 *
 * @property Cuentaspendiente $Cuentaspendiente
 * @property PaginatorComponent $Paginator
 */
class CuentaspendientesController extends AppController {

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
            	
            $this->loadModel('Producto');
            $this->loadModel('Deposito');
            $this->loadModel('Proveedore');
            
            $rpproducto = "";
            $rpdeposito = "";
            $rpproveedor = "";
            $rpfactura = "";
            
            if(isset($this->passedArgs['productos']) && $this->passedArgs['productos'] != ""){
                $paginate['Cuentaspendiente.producto_id'] = $this->passedArgs['productos'];
                $rpproducto = $this->passedArgs['productos'];
            }

            if(isset($this->passedArgs['depositos']) && $this->passedArgs['depositos'] != ""){
                $paginate['Cuentaspendiente.deposito_id'] = $this->passedArgs['depositos'];
                $rpdeposito = $this->passedArgs['depositos'];
            }
            
            if(isset($this->passedArgs['proveedores']) && $this->passedArgs['proveedores'] != ""){
                $paginate['Cuentaspendiente.proveedore_id'] = $this->passedArgs['proveedores'];
                $rpproveedor = $this->passedArgs['proveedores'];
            }

            if(isset($this->passedArgs['facturas']) && $this->passedArgs['facturas'] != ""){
                $paginate['LOWER(Cuentaspendiente.numerofactura) LIKE'] = '%' . strtolower($this->passedArgs['facturas']) . '%';
                $rpfactura = strtolower($this->passedArgs['facturas']);
            }            
            
            $empresaId = $this->Auth->user('empresa_id');
                        
            $paginate['Cuentaspendiente.empresa_id'] = $empresaId;
            $this->Cuentaspendiente->recursive = 0;
            $cuentaspendientes = $this->Paginator->paginate('Cuentaspendiente',$paginate);

            $fechaActual = date('Y-m-d');
            $costoTotal = 0;
            $costoVencido = 0;
            $costoVigente = 0;

            //se obtienen el listado de productos de la empresa
            $productos = $this->Producto->obtenerListaProductosEmpresa($empresaId);
            
            //se obtiene el listado de depositos de la empresa
            $depositos = $this->Deposito->obtenerDepositoEmpresa($empresaId);
            
            //se obtiene el listado de proveedores por empresa
            $proveedores = $this->Proveedore->obtenerProveedoresEmpresa($empresaId); 
            
            for($i = 0; $i < count($cuentaspendientes); $i++){

                $cuentaspendientes[$i]['Cuentaspendiente']['fechalimitepago'] = $this->sumarDiasFecha($cuentaspendientes[$i]['Cuentaspendiente']['created'],$cuentaspendientes[$i]['Proveedore']['diascredito']);
                                
                $diff = $this->diffFechas($fechaActual, $cuentaspendientes[$i]['Cuentaspendiente']['fechalimitepago']);
                
                $costoTotal += ($cuentaspendientes[$i]['Cuentaspendiente']['totalobligacion']);
                        
                if($costoTotal > $cuentaspendientes[$i]['Proveedore']['limitecredito']){                    
                    $cuentaspendientes[$i]['Cuentaspendiente']['limitecredito'] = 'text-danger';
                }else{
                    $cuentaspendientes[$i]['Cuentaspendiente']['limitecredito'] = 'text';                    
                }                                    
                    
                if($diff <= '0'){
                    $cuentaspendientes[$i]['Cuentaspendiente']['color'] = 'danger';
                    $cuentaspendientes[$i]['Cuentaspendiente']['diasvencido'] = $diff;
                    $costoVencido += ($cuentaspendientes[$i]['Cuentaspendiente']['totalobligacion']);
                }else{
                    $cuentaspendientes[$i]['Cuentaspendiente']['color'] = 'success';
                    $cuentaspendientes[$i]['Cuentaspendiente']['diasvencido'] = $diff;
                    $costoVigente += ($cuentaspendientes[$i]['Cuentaspendiente']['totalobligacion']);
                }
            }
            
            $this->set(compact('cuentaspendientes','costoTotal', 'costoVencido', 'costoVigente', 'productos', 'depositos', 'proveedores'));
            $this->set(compact('rpproducto', 'rpdeposito', 'rpproveedor', 'rpfactura'));
            
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
            	
		if (!$this->Cuentaspendiente->exists($id)) {
			throw new NotFoundException(__('Invalid cuentaspendiente'));
		}
		$options = array('conditions' => array('Cuentaspendiente.' . $this->Cuentaspendiente->primaryKey => $id));
		$this->set('cuentaspendiente', $this->Cuentaspendiente->find('first', $options));
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
			$this->Cuentaspendiente->create();
			if ($this->Cuentaspendiente->save($this->request->data)) {
				$this->Session->setFlash(__('The cuentaspendiente has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cuentaspendiente could not be saved. Please, try again.'));
			}
		}
		$documentos = $this->Cuentaspendiente->Documento->find('list');
		$productos = $this->Cuentaspendiente->Producto->find('list');
		$depositos = $this->Cuentaspendiente->Deposito->find('list');
		$proveedores = $this->Cuentaspendiente->Proveedore->find('list');
		$usuarios = $this->Cuentaspendiente->Usuario->find('list');
		$this->set(compact('documentos', 'productos', 'depositos', 'proveedores', 'usuarios'));
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
            	
		if (!$this->Cuentaspendiente->exists($id)) {
			throw new NotFoundException(__('Invalid cuentaspendiente'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Cuentaspendiente->save($this->request->data)) {
				$this->Session->setFlash(__('The cuentaspendiente has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cuentaspendiente could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Cuentaspendiente.' . $this->Cuentaspendiente->primaryKey => $id));
			$this->request->data = $this->Cuentaspendiente->find('first', $options);
		}
		$documentos = $this->Cuentaspendiente->Documento->find('list');
		$productos = $this->Cuentaspendiente->Producto->find('list');
		$depositos = $this->Cuentaspendiente->Deposito->find('list');
		$proveedores = $this->Cuentaspendiente->Proveedore->find('list');
		$usuarios = $this->Cuentaspendiente->Usuario->find('list');
		$this->set(compact('documentos', 'productos', 'depositos', 'proveedores', 'usuarios'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Cuentaspendiente->id = $id;
		if (!$this->Cuentaspendiente->exists()) {
			throw new NotFoundException(__('Invalid cuentaspendiente'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Cuentaspendiente->delete()) {
			$this->Session->setFlash(__('The cuentaspendiente has been deleted.'));
		} else {
			$this->Session->setFlash(__('The cuentaspendiente could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
        
        public function sumarDiasFecha($fecha,$dias){
            $diasSum = !empty($dias) ? $dias : 30;
            $fechaNew = new DateTime($fecha);
            $fechaNew->add(new DateInterval('P' . $diasSum . 'D'));
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
        
        public function obtenercuentapendiente(){
            $this->loadModel('Cuenta');
            $posData = $this->request->data;
            $empresaId = $this->Auth->user('empresa_id');
            $cuentaId = $posData['pagoId'];
            
            //se obtienen los datos de la cuenta que se desea pagar
            $datosCuentaPendiente = $this->Cuentaspendiente->obtenerCuentaPendienteId($cuentaId);

            //se obtienen las cuentas de la empresa
            $listaCuentas = $this->Cuenta->obtenerCuentasEmpresa($empresaId);
           
            $this->set(compact('datosCuentaPendiente', 'listaCuentas', 'cuentaId'));            
        }
        
        public function pagarcuentapendiente(){
            $this->loadModel('Cuenta');
            $this->autoRender = false;
            $posData = $this->request->data;
            
            $ttalPago = $posData['ttalPago'];
            $cuentaId = $posData['cuenta'];
            $cuentaPendienteId = $posData['cuentapendiente'];
            $resp = true;
            //Se obtiene la informacion de la cuenta por cobrar
            $datosCuentaPendiente = $this->Cuentaspendiente->obtenerCuentaPendienteId($cuentaPendienteId);
            
            //Se obtiene la informacion de la cuenta a la que se sumara el pago
            $datosCuenta = $this->Cuenta->obtenerDatosCuentaId($cuentaId);
            
            //Se resta la cantidad paga de la cuenta pendiente
            $saldoCuentaPendiente = $datosCuentaPendiente['Cuentaspendiente']['totalobligacion'] - $ttalPago;
            
            if($this->Cuentaspendiente->actualizarCuentaPendiente($cuentaPendienteId,$saldoCuentaPendiente)){
                $nuevoSaldoCuenta = $datosCuenta['Cuenta']['saldo'] - $ttalPago;
                $this->Cuenta->actualizarSaldoCuenta($cuentaId, $nuevoSaldoCuenta);

                $this->crearGasto($datosCuentaPendiente, $ttalPago, $cuentaId);
            }else{
                $resp = false;
            }
            echo json_encode(array('resp' => $resp));            
        }        

        // se crea un gasto por el pago de la cuenta
        public function crearGasto($ctaPend, $ttalPago, $cuentaId){
            $this->loadModel('Itemsgasto');
            $this->loadModel('Gasto');

            $empresaId = $this->Auth->user('empresa_id');

            //se obtiene el item del gasto pago proveedores 
            $itemGasto = $this->Itemsgasto->obtenerItemGastoProv($empresaId, 'PAGO PROVEEDOR');

            $data['descripcion'] = "Pago cuenta pendiente a proveedor " . $ctaPend['Proveedore']['nombre'];
            $data['usuario_id'] = $this->Auth->user('id');
            $data['empresa_id'] = $empresaId;
            $data['fechagasto'] = date('Y-m-d H:i:s');
            $data['valor'] = $ttalPago;
            $data['cuenta_id'] = $cuentaId;
            $data['traslado'] = '0';
            $data['itemsgasto_id'] = !empty($itemGasto['Itemsgasto']['id']) ? $itemGasto['Itemsgasto']['id'] : null;
            $data['tipoempresa'] = 'P';
            $data['empresaasg_id'] = $empresaId;

            $this->Gasto->actualizarGasto($data);

        }
        
        //Se valida el saldo existente en la cuenta de la cual se va debitar el pago
        public function validarsaldoencuenta(){
            $this->loadModel('Cuenta');
            $this->autoRender = false;
            $resp = false;
            $posData = $this->request->data;
            $ttalPago = $posData['ttalPago'];
            $cuentaId = $posData['cuenta'];
            
            //Se obtienen los datos de la cuenta de la cual se desea debitar el pago
            $datosCuenta = $this->Cuenta->obtenerDatosCuentaId($cuentaId);

            if($datosCuenta['Cuenta']['saldo'] >= $ttalPago){
                $resp = true;
            }
            //ttalPago: ttalPago, cuenta: cuenta
            
            echo json_encode(array('resp' => $resp));            
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
        
        public function eliminarcuentapendiente(){
            $this->autoRender = false;
            $posData = $this->request->data;
            $posData['id'];
            $resp = $this->Cuentaspendiente->eliminarCuentaPendiente($posData['id']);
            echo $resp;            
        }      
}
