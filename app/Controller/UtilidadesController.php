<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Utilidades Controller
 *
 * @property Utilidade $Utilidade
 * @property PaginatorComponent $Paginator
 */
class UtilidadesController extends AppController {

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

            if(isset($this->passedArgs['Utilidade.fechaInicio']) && isset($this->passedArgs['Utilidade.fechaFin'])){
                $fechaInicio = $this->passedArgs['Utilidade.fechaInicio'];
                $fechaFin = $this->passedArgs['Utilidade.fechaFin'];
            }else{
                $fechaInicio = date('Y-m-d');
                $fechaFin = date('Y-m-d');
            }

            $empresaId = $this->Auth->user('empresa_id');            
            
            /*se recorre el registro de las utilidades*/
            $utilidades = $this->Utilidade->obtenerUtilidadesPorEmpresa($fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59', $empresaId);

	    $totalVenta = 0;
	    $utilidadBruta = 0;
            for($i = 0; $i < count($utilidades); $i++){
                $totalVenta += $utilidades[$i]['Utilidade']['costo_producto'] * $utilidades[$i]['Utilidade']['cantidad'];
                $utilidadBruta += $utilidades[$i]['Utilidade']['precioventa'] * $utilidades[$i]['Utilidade']['cantidad'];
                
            }
            $this->set(compact('utilidades','totalVenta','utilidadBruta','fechaInicio','fechaFin'));
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
            		
		if (!$this->Utilidade->exists($id)) {
			throw new NotFoundException(__('Invalid utilidade'));
		}
		$options = array('conditions' => array('Utilidade.' . $this->Utilidade->primaryKey => $id));
		$this->set('utilidade', $this->Utilidade->find('first', $options));
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
			$this->Utilidade->create();
			if ($this->Utilidade->save($this->request->data)) {
				$this->Session->setFlash(__('The utilidade has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The utilidade could not be saved. Please, try again.'));
			}
		}
		$cargueinventarios = $this->Utilidade->Cargueinventario->find('list');
		$empresas = $this->Utilidade->Empresa->find('list');
		$this->set(compact('cargueinventarios', 'empresas'));
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
            		
		if (!$this->Utilidade->exists($id)) {
			throw new NotFoundException(__('Invalid utilidade'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Utilidade->save($this->request->data)) {
				$this->Session->setFlash(__('The utilidade has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The utilidade could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Utilidade.' . $this->Utilidade->primaryKey => $id));
			$this->request->data = $this->Utilidade->find('first', $options);
		}
		$cargueinventarios = $this->Utilidade->Cargueinventario->find('list');
		$empresas = $this->Utilidade->Empresa->find('list');
		$this->set(compact('cargueinventarios', 'empresas'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Utilidade->id = $id;
		if (!$this->Utilidade->exists()) {
			throw new NotFoundException(__('Invalid utilidade'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Utilidade->delete()) {
			$this->Session->setFlash(__('The utilidade has been deleted.'));
		} else {
			$this->Session->setFlash(__('The utilidade could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

        public function search() {
            $url=array();
            $url['action'] = 'index';
            
            foreach ($this->data as $k => $v){
                foreach ($v as $kk => $vv){
                    $url[$k.'.'.$kk]=$vv;                           
                    } 
                }
            $this->redirect($url, null, true);
	}	
	
	public function rotacion() {
	    
            if(isset($this->passedArgs['Rotacion.fechaInicio']) && isset($this->passedArgs['Rotacion.fechaFin'])){
                $fechaInicio = $this->passedArgs['Rotacion.fechaInicio'];
                $fechaFin = $this->passedArgs['Rotacion.fechaFin'];
            }else{
                $fechaInicio = date('Y-m-d');
                $fechaFin = date('Y-m-d');
            }	
            
            $days = $this->diffDates($fechaInicio, $fechaFin);
            
            if($days == 0){
                $days = 1;
            }

            $empresaId = $this->Auth->user('empresa_id');            
            
            /*se recorre el registro de las utilidades*/
            $rotacion = $this->Utilidade->obtenerRotacion($fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59', $empresaId);

            $arrRotation = [];
            foreach($rotacion as $val){
                $arrProd['prom_venta'] = $val['0']['quant'] / $days;
                $arrProd['prom_precio_venta'] = $val['0']['precioventa'] / $val['0']['quant'];
                $arrProd['prom_utilidad_bruta'] = $val['0']['utilidadbruta'] / $val['0']['quant'];
                $arrProd['prom_utilidad_porc'] = $val['0']['utilidadporcentual'] / $val['0']['quant'];
                $arrProd['costo_producto'] = $val['0']['costoproducto'] / $val['0']['quant'];
                $arrProd['descripcion'] = $val['P']['descripcion'];
                
                $arrRotation[] = $arrProd;
            }
            
            $this->set(compact('arrRotation','fechaInicio','fechaFin'));            
	}
	
	public function diffDates($dateInit, $dateEnd){
        $date1 = new DateTime($dateInit);
        $date2 = new DateTime($dateEnd);
        $diff = $date1->diff($date2);
        
        return $diff->days;	
	}
	
	
    public function searchrotacion() {
        $url=array();
        $url['action'] = 'rotacion';
        
        foreach ($this->data as $k => $v){
            foreach ($v as $kk => $vv){
                $url[$k.'.'.$kk]=$vv;                           
                } 
            }
        $this->redirect($url, null, true);
	}	
}
