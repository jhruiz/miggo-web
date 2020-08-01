<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Cuentas Controller
 *
 * @property Cuenta $Cuenta
 * @property PaginatorComponent $Paginator
 */
class CuentasController extends AppController {

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
            $paginate['Cuenta.empresa_id'] = $empresaId;              
            $this->Cuenta->recursive = 0;
            $this->set('cuentas', $this->Paginator->paginate('Cuenta',$paginate));
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
            		
		if (!$this->Cuenta->exists($id)) {
			throw new NotFoundException(__('La cuenta no existe.'));
		}
		$options = array('conditions' => array('Cuenta.' . $this->Cuenta->primaryKey => $id));
		$this->set('cuenta', $this->Cuenta->find('first', $options));
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
			$this->Cuenta->create();		
			$this->request->data['Cuenta']['saldo'] = str_replace(',', '', $this->request->data['Cuenta']['saldo']);                        
			if ($this->Cuenta->save($this->request->data)) {                            
                            $this->Session->setFlash(__('La cuenta ha sido guardada.'));
                            return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La cuenta no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		}
                $empresaId = $this->Auth->user('empresa_id');
		$this->set(compact('empresaId'));
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
            		
		if (!$this->Cuenta->exists($id)) {
			throw new NotFoundException(__('La cuenta no existe'));
		}
		if ($this->request->is(array('post', 'put'))) {
                        $this->request->data['Cuenta']['saldo'] = str_replace(',', '', $this->request->data['Cuenta']['saldo']);
			if ($this->Cuenta->save($this->request->data)) {
				$this->Session->setFlash(__('La cuenta ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La cuenta no pudo ser guardada. Por favor, inténtelo nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Cuenta.' . $this->Cuenta->primaryKey => $id));
			$this->request->data = $this->Cuenta->find('first', $options);
		}
		$empresas = $this->Cuenta->Empresa->find('list');
		$this->set(compact('empresas'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Cuenta->id = $id;
		if (!$this->Cuenta->exists()) {
			throw new NotFoundException(__('La cuenta no existe'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Cuenta->delete()) {
			$this->Session->setFlash(__('La cuenta ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La cuenta no pudo ser eliminada. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
        
        
        public function ajaxvalidarmontocuenta(){
            $this->autoRender = false;
            $posData = $this->request->data;
            $montoGasto = $posData['montoGasto'];
            $cuentaId = $posData['cuentaId'];            
            $datosCuenta = $this->Cuenta->obtenerDatosCuentaId($cuentaId);            
            if($datosCuenta['Cuenta']['saldo'] < $montoGasto){
                echo json_encode(array('resp' => false));
            }else{
                echo json_encode(array('resp' => true));
            }
        }
        
        public function ajaxobtenercuentadestino(){
            $this->autoRender = false;
            $posData = $this->request->data;
            $cuentaOrigenId = $posData['cuentaOrigenId'];
            $empresaId = $posData['empresaId'];
            $arrCuentas = $this->Cuenta->obtenerCuentasDestino($empresaId);
            
            unset($arrCuentas[$cuentaOrigenId]);
            
            if(!empty($arrCuentas)){
                echo json_encode(array('resp' => true, 'data' => $arrCuentas));
            }else{
                echo json_encode(array('resp' => false));
            }
		}
		
		public function ajaxobtenervalorcaja() {
			$this->loadModel('Cuenta');
			$this->autoRender = false;
			$posData = $this->request->data;

			$datosCuenta = $this->Cuenta->obtenerDatosCuentaId($posData['idCaja']);
			
			if(!empty($datosCuenta)){
				echo json_encode(array('resp' => true, 'data' => $datosCuenta['Cuenta']['saldo']));
			}else{
				echo json_encode(array('resp' => false));
			}
			
		}
}
