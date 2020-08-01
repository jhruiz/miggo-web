<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Gastos Controller
 *
 * @property Gasto $Gasto
 * @property PaginatorComponent $Paginator
 */
class GastosController extends AppController {

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
            $this->loadModel('Cuenta');
            $this->loadModel('Itemsgasto');
            $this->loadModel('Relacionempresa');
            $this->loadModel('Empresa');
            /*se reagistra la actividad del uso de la aplicacion*/
            $usuariosController = new UsuariosController();
            $usuarioAct = $this->Auth->user('id');
            $usuariosController->registraractividad($usuarioAct);
            
            $itemId = "";
            if(isset($this->passedArgs['Gasto']['itemsgasto_id']) && !empty($this->passedArgs['Gasto']['itemsgasto_id'])){
                $itemId = $this->passedArgs['Gasto']['itemsgasto_id'];
            }

            if(!empty($this->passedArgs['Gasto']['fechaInicio']) && !empty($this->passedArgs['Gasto']['fechaFin'])){
                $fechaInicio = $this->passedArgs['Gasto']['fechaInicio'];
                $fechaFin = $this->passedArgs['Gasto']['fechaFin'];
            }else{
                $fechaInicio = date('Y-m-d');
                $fechaFin = date('Y-m-d');
            }                   

            $empresaId = $this->Auth->user('empresa_id');
            
            $arrEmpresa = [];
            
            //se obtiene la infomacion de la empresa
            $infoEmpresa = $this->Empresa->obtenerEmpresaPorId($empresaId);
            
            $arrEmpresa[] = [
                'id' => $infoEmpresa['Empresa']['id'],
                'nombre' => $infoEmpresa['Empresa']['nombre'],
                'tipo' => 'P'
            ];
            
            //se obtinen las empresas relacionadas a la empresa
            $infoEmpresasRel = $this->Relacionempresa->obtenerInformacionEmpresas($empresaId);
            if(!empty($infoEmpresasRel)){
                foreach ($infoEmpresasRel as $ier){
                    $arrEmpresa[] = [
                        'id' => $ier['Relacionempresa']['id'],
                        'nombre' => $ier['Relacionempresa']['nombre'],
                        'tipo' => 'S'
                    ];
                }                
            }                       
            
            //se obtiene el listado de las cuentas
            $listCuentas = $this->Cuenta->obtenerCuentasEmpresa($empresaId);

            $infoGastos = $this->Gasto->obtenerGastosEmpresa($fechaInicio . " 00:00:00", $fechaFin . " 23:59:59", $empresaId, "",$itemId);
            
            //se obtiene el listado de items para gastos
            $itemsGasto = $this->Itemsgasto->obtenerListaItemsGastos($empresaId);

            $gastos = [];
            $ttalGastos = 0;
            if(!empty($infoGastos)){
                foreach ($infoGastos as $gts){

                    if($gts['Gasto']['traslado']) { continue; }
                    $ttalGastos += $gts['Gasto']['valor'];                    
                    
                    $empRelGasto = "";
                    foreach ($arrEmpresa as $empRel){
                        if($empRel['id'] == $gts['Gasto']['empresaasg_id'] && $empRel['tipo'] == $gts['Gasto']['tipoempresa']){
                            $empRelGasto = $empRel['nombre'];
                        }
                    }                    
                    
                    $gastos[] = [
                        'id' => $gts['Gasto']['id'],
                        'descripcion' => $gts['Gasto']['descripcion'],
                        'usuario' => $gts['Usuario']['nombre'],
                        'fechagasto' => $gts['Gasto']['fechagasto'],
                        'created' => $gts['Gasto']['created'],
                        'valor' => $gts['Gasto']['valor'],
                        'cuenta' => $gts['Cuenta']['descripcion'],
                        'traslado' => $gts['Gasto']['traslado'],
                        'cuentadestino' => !empty($gts['Gasto']['cuentadestino']) ? $listCuentas[$gts['Gasto']['cuentadestino']] : "",
                        'itemsgasto' => !empty($gts['Gasto']['itemsgasto_id']) ? $itemsGasto[$gts['Gasto']['itemsgasto_id']] : "",
                        'empRel' => $empRelGasto
                    ];
                }                
            }
           
            $this->set(compact('gastos', 'itemsGasto', 'ttalGastos', 'itemId', 'fechaInicio', 'fechaFin', 'arrEmpresa'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
            $this->loadModel('Cuenta');
            $this->loadModel('Itemsgasto');
            /*se reagistra la actividad del uso de la aplicacion*/
            $usuariosController = new UsuariosController();
            $usuarioAct = $this->Auth->user('id');
            $usuariosController->registraractividad($usuarioAct);
            
            $empresaId = $this->Auth->user('empresa_id');            		
            $listCuentas = $this->Cuenta->obtenerCuentasEmpresa($empresaId);
            
            if (!$this->Gasto->exists($id)) {
                    throw new NotFoundException(__('El gasto no existe.'));
            }
            
            //se obtiene el listado de items para gastos
            $itemsGasto = $this->Itemsgasto->obtenerListaItemsGastos($empresaId);
            
            $options = array('conditions' => array('Gasto.' . $this->Gasto->primaryKey => $id));
            $this->set('gasto', $this->Gasto->find('first', $options));
            $this->set(compact('listCuentas', 'itemsGasto'));
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
            		
            $this->loadModel('Usuario');
            $this->loadModel('Cuenta');
            $this->loadModel('Itemsgasto');
            $this->loadModel('Empresa');
            $this->loadModel('Relacionempresa');
            
            if ($this->request->is('post')) {
                $posData = $this->request->data;
                $valor = str_replace(',', '', $posData['Gasto']['valor']);
                $cuentaId = $posData['Gasto']['cuenta_id'];                
                
                $this->descontarSaldoCuenta($valor,$cuentaId); 
                
                $infoEmpG = explode("_", $posData['Gasto']['empresagasto']);
                $this->request->data['Gasto']['empresaasg_id'] = $infoEmpG['0'];
                $this->request->data['Gasto']['tipoempresa'] = $infoEmpG['1'];
                
                if($posData['Gasto']['traslado']){                    
                    $this->trasladarSaldo($valor, $posData['Gasto']['cuentadestino']);
                }
                
                $this->request->data['Gasto']['valor'] = str_replace(',', '', $this->request->data['Gasto']['valor']);                  
                
                $this->Gasto->create();
                if ($this->Gasto->save($this->request->data)) {
                        $this->Session->setFlash(__('El gasto ha sido guardado.'));
                        return $this->redirect(array('action' => 'index'));
                } else {
                        $this->Session->setFlash(__('El gasto no pudo ser guardado. Por favor, inténtelo de nuevo.'));
                }
            }

            $empresaId = $this->Auth->user('empresa_id');
            
            $arrEmpresa = [];
            
            //se obtiene la infomacion de la empresa
            $infoEmpresa = $this->Empresa->obtenerEmpresaPorId($empresaId);
            
            $arrEmpresa[] = [
                'id' => $infoEmpresa['Empresa']['id'],
                'nombre' => $infoEmpresa['Empresa']['nombre'],
                'tipo' => 'P'
            ];
            
            //se obtinen las empresas relacionadas a la empresa
            $infoEmpresasRel = $this->Relacionempresa->obtenerInformacionEmpresas($empresaId);
            if(!empty($infoEmpresasRel)){
                foreach ($infoEmpresasRel as $ier){
                    $arrEmpresa[] = [
                        'id' => $ier['Relacionempresa']['id'],
                        'nombre' => $ier['Relacionempresa']['nombre'],
                        'tipo' => 'S'
                    ];
                }                
            }            
                    
            //se obtiene el listado de items para gastos
            $itemsGasto = $this->Itemsgasto->obtenerListaItemsGastos($empresaId);            
            $usuarios = $this->Usuario->obtenerUsuarioEmpresa($empresaId);
            $cuentas = $this->Cuenta->obtenerCuentasEmpresa($empresaId);
            $this->set(compact('usuarios', 'empresaId', 'cuentas', 'itemsGasto', 'arrEmpresa'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {    
            $this->loadModel('Cuenta');

            //se obtiene la informacion del usuario logueado
            $usuario_id = $this->Auth->user('id');
            
            //se obtiene la informacion del gasto
            $gasto = $this->Gasto->obtenerGastoPorId($id);
            
            //se obtiene el listado de cuentas
            $cuenta = $this->Cuenta->obtenerDatosCuentaId($gasto['Gasto']['cuenta_id']);
            
            $this->set(compact('usuario_id', 'id', 'gasto', 'cuenta'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Gasto->id = $id;
		if (!$this->Gasto->exists()) {
			throw new NotFoundException(__('El gasto no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Gasto->delete()) {
			$this->Session->setFlash(__('El gasto ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El gasto no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
        
        public function descontarSaldoCuenta($valor,$cuentaId){
            $this->loadModel('Cuenta');
            
            //Se obtienen los datos de la cuenta por id
            $datosCuenta = $this->Cuenta->obtenerDatosCuentaId($cuentaId);
            $saldoFinal = $datosCuenta['Cuenta']['saldo'] - $valor;
            
            //Se actualiza el saldo de la cuenta
            $this->Cuenta->actualizarSaldoCuenta($cuentaId,$saldoFinal);
        }
        
        /**
         * se realiza incremento de saldo por cuenta del traslado
         * @param type $valor
         * @param type $cuentadestino
         */
        public function trasladarSaldo($valor, $cuentadestino){
            $this->loadModel('Cuenta');
            
            //Se obtienen los datos de la cuenta por id
            $datosCuenta = $this->Cuenta->obtenerDatosCuentaId($cuentadestino);
            $saldoFinal = $datosCuenta['Cuenta']['saldo'] + $valor;
            
            //Se actualiza el saldo de la cuenta
            $this->Cuenta->actualizarSaldoCuenta($cuentadestino,$saldoFinal);
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
        
        public function actualizargasto(){
            $this->autoRender = false;
            $this->loadModel('Usuario');
            $this->loadModel('Cuenta');
            $posData = $this->request->data;         
            
            $valorActual = str_replace(",", "", $posData['valor_actual']);
            
            //se obtiene la información del usuario que realiza el registro
            $usuario = $this->Usuario->obtenerUsuarioPorId($posData['usuarioregistra_id']);
            $date = date('Y-m-d H:i:s');
            
            $restante = $valorActual - $posData['valor_nuevo'];
            
            //se obtiene la información de la cuenta
            $cuenta = $this->Cuenta->obtenerDatosCuentaId($posData['cuenta_id']);            
            
            if($restante < 0){
                $valNuevoSaldo = $cuenta['Cuenta']['saldo'] - ($restante * -1);
            }else{
                $valNuevoSaldo = $cuenta['Cuenta']['saldo'] + $restante;
            }            
            
            //actualiza el saldo de la cuenta
            $this->Cuenta->actualizarSaldoCuenta($posData['cuenta_id'],$valNuevoSaldo);
            
            $data = [];
            $descripcion = $posData['descripcion'] . ". ";
            $descripcion .= "El usuario " . $usuario['Usuario']['nombre'] . " - " . $usuario['Usuario']['identificacion'];
            $descripcion .= ", realizó cambio de valor del gasto el dia " . $date . ". ";
            $descripcion .= "Valor anterior $" . number_format($valorActual,2) . ". ";
            $descripcion .= "Valor nuevo $" . number_format($posData['valor_nuevo'],2) . ".";
            
            $data['id'] = $posData['gasto_id'];
            $data['usuario_id'] = $posData['usuarioregistra_id'];
            $data['valor'] = $posData['valor_nuevo'];            
            $data['valor'] = $posData['valor_nuevo'];            
            $data['descripcion'] = $descripcion;            
            
            $result  = $this->Gasto->actualizarGasto($data);
            
            echo json_encode(array('resp' => $result)); 
            
        }
}
