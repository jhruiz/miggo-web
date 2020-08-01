<?php
App::uses('AppController', 'Controller');
/**
 * Prefacturasdetalles Controller
 *
 * @property Prefacturasdetalle $Prefacturasdetalle
 * @property PaginatorComponent $Paginator
 */
class PrefacturasdetallesController extends AppController {

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
		$this->Prefacturasdetalle->recursive = 0;
		$this->set('prefacturasdetalles', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Prefacturasdetalle->exists($id)) {
			throw new NotFoundException(__('Invalid prefacturasdetalle'));
		}
		$options = array('conditions' => array('Prefacturasdetalle.' . $this->Prefacturasdetalle->primaryKey => $id));
		$this->set('prefacturasdetalle', $this->Prefacturasdetalle->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Prefacturasdetalle->create();
			if ($this->Prefacturasdetalle->save($this->request->data)) {
				$this->Session->setFlash(__('The prefacturasdetalle has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The prefacturasdetalle could not be saved. Please, try again.'));
			}
		}
		$cargueinventarios = $this->Prefacturasdetalle->Cargueinventario->find('list');
		$prefacturas = $this->Prefacturasdetalle->Prefactura->find('list');
		$this->set(compact('cargueinventarios', 'prefacturas'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Prefacturasdetalle->exists($id)) {
			throw new NotFoundException(__('Invalid prefacturasdetalle'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Prefacturasdetalle->save($this->request->data)) {
				$this->Session->setFlash(__('The prefacturasdetalle has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The prefacturasdetalle could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Prefacturasdetalle.' . $this->Prefacturasdetalle->primaryKey => $id));
			$this->request->data = $this->Prefacturasdetalle->find('first', $options);
		}
		$cargueinventarios = $this->Prefacturasdetalle->Cargueinventario->find('list');
		$prefacturas = $this->Prefacturasdetalle->Prefactura->find('list');
		$this->set(compact('cargueinventarios', 'prefacturas'));
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
            $prefactId = $posData['detalleId'];
            
            $this->loadModel('Prefactura');
            $this->loadModel('OrdentrabajosSuministro');
            
            /*se obtiene la informacion del detalle de la prefactura para actualizar el stock*/
            $arrDetPrefact = $this->Prefacturasdetalle->obtenerPrefacturaDetalleId($prefactId);
            
            /*se actualiza el stock con el registro eliminado*/
            if($this->actalizarcantidadpredelete($arrDetPrefact)){
                $detalleId['Prefacturasdetalle.id'] = $prefactId;
                
                //se elimina el suministro relacionado a la orden de trabajo siempre y cuando la prefactura tenga una
                $arrPrefact = $this->Prefactura->obtenerPrefacturaPorId($arrDetPrefact['Prefacturasdetalle']['prefactura_id']);
                if(!empty($arrPrefact['Prefactura']['ordentrabajo_id'])){
                    $arrOrdSum = $this->OrdentrabajosSuministro->obtenerOrdenSuministro($arrDetPrefact['Prefacturasdetalle']['cargueinventario_id'], 
                            $arrPrefact['Prefactura']['ordentrabajo_id']);
                    
                    //si existe el suministro, se elimina
                    if(!empty($arrOrdSum)){
                        $this->OrdentrabajosSuministro->eliminarSuministroOrden($arrOrdSum['OrdentrabajosSuministro']['id']);
                    }                    
                }
                
                if($this->Prefacturasdetalle->delete($detalleId)){
                    $resp = true;
                }else{
                    $resp = false;                
                }                
            }else{
                $resp = false;
            }            
            echo json_encode(array('resp' => $resp)); 
	}     
        
        public function actalizarcantidad(){
            $this->loadModel('Cargueinventario');
            $this->autoRender = false;
            $data = array();
            $posData = $this->request->data;            
            
            $cantidad = $posData['cantidad'];
            $id = $posData['id'];

            /*se obtiene la cantidad actual en prefactura*/
            $preFactDet = $this->Prefacturasdetalle->obtenerPrefacturaDetalleId($id);    
            $cantPreFact = $preFactDet['Prefacturasdetalle']['cantidad'];

            /*se suma la cantidad en el stock y lo que se habia descontado, a esa cantidad se le resta la nueva cantidad solicitada para la venta*/
            /*se descuenta la cantidad del producto prefacturado del inventario*/
            /*se obtiene la cantidad existente en el stock*/
            $inventActual = $this->Cargueinventario->obtenerInventarioId($preFactDet['Prefacturasdetalle']['cargueinventario_id']);
            $cantStock = $inventActual['Cargueinventario']['existenciaactual'];

            $existFinal = ($cantStock + $cantPreFact) - $cantidad;
            
            if($existFinal < '0'){
                /*si la cantidad final es menor a cero, no se realiza el cambio*/
                echo json_encode(array('resp' => false, 'cantStock' => $cantStock, 'cantidad' => $cantPreFact));
            }else{
                /*se actualiza la cantidad en stock tras la prefactura*/
                $this->Cargueinventario->actalizarExistenciaStock($preFactDet['Prefacturasdetalle']['cargueinventario_id'], $existFinal); 
                
                //se actualiza la cantidad del producto en la orden de trabajo si esta relacionada a una
                $this->actualizarSumOrdenTrabajo($preFactDet['Prefacturasdetalle']['prefactura_id'], $preFactDet['Prefacturasdetalle']['cargueinventario_id'], $cantidad);

                $data['id'] = $id;
                $data['cantidad'] = $cantidad;

                if($this->Prefacturasdetalle->save($data)){
                    $resp = true;
                }else{
                    $resp = false;
                }
                echo json_encode(array('resp' => $resp));                   
            }
        }
        
        public function actualizarcostoventa(){
            $this->loadModel('Cargueinventario');
            $this->autoRender = false;            
            $data = array();
            $posData = $this->request->data;
            $precioventa = $posData['precioventa'];
            $id = $posData['id'];
            
            /*se obtiene el detalle de la prefactura*/
            $preFactDet = $this->Prefacturasdetalle->obtenerPrefacturaDetalleId($id);    
            $cargueinventarioId = $preFactDet['Prefacturasdetalle']['cargueinventario_id'];            
            
            $inventActual = $this->Cargueinventario->obtenerInventarioId($cargueinventarioId);
            $preciominimo = $inventActual['Cargueinventario']['preciominimo'];
            $precioAutorizado = $inventActual['Cargueinventario']['precioventa'];
            
            if($precioventa < $preciominimo){
               $resp = false;
            }else{
                $data['id'] = $id;
                $data['costoventa'] = $precioventa;            

                if($this->Prefacturasdetalle->save($data)){
                    $resp = true;
                }else{
                    $resp = false;
                }                 
            }
           
            echo json_encode(array('resp' => $resp, 'precioventa' => $precioAutorizado));                                 
        }       
        
        public function obtenerPrefacturasDetalles(){
            $this->loadModel('Producto');
            $this->autoRender = false;
            $posData = $this->request->data;
            $prefacturaId = $posData['prefacId'];
            
            /*se obtienen todos los productos relacionados a la prefactura*/
            $arrDetFact = $this->Prefacturasdetalle->obtenerProductosPrefacturaPrefactId($prefacturaId);
            for($i = 0; $i < count($arrDetFact); $i++){
                $productoId = $arrDetFact[$i]['Cargueinventario']['producto_id'];
                $arrProducto = $this->Producto->obtenerInformacionProductoId($productoId);
                $nombreProd = $arrProducto['Producto']['descripcion'];
                $codigoProd = $arrProducto['Producto']['codigo'];
                $arrDetFact[$i]['Cargueinventario']['descprod'] = $nombreProd;
                $arrDetFact[$i]['Cargueinventario']['codprod'] = $codigoProd;
            }
            if(count($arrDetFact) > 0){
                echo json_encode(array('resp' => true, 'detFact' => $arrDetFact));
            }else{
                echo json_encode(array('resp' => false));
            }
            
        }
        
        
        public function actalizarcantidadpredelete($arrDetPrefact){
            $this->loadModel('Cargueinventario');
            
            $cantProd = $arrDetPrefact['Prefacturasdetalle']['cantidad'];
            $cargInvId = $arrDetPrefact['Prefacturasdetalle']['cargueinventario_id'];
            
            /*se obtiene la informacion actual del cargue de inventario del producto en gestion*/
            $arrCargueInv = $this->Cargueinventario->obtenerInventarioId($cargInvId);
            $cantFinal = $arrCargueInv['Cargueinventario']['existenciaactual'] + $cantProd;
            if($this->Cargueinventario->actalizarExistenciaStock($cargInvId, $cantFinal)){
                return true;
            }else{
                return false;
            }
            
        }
        
        /**
         * Se actualiza la cantidad del suministro relacionada a la orden de trabajo
         * @param type $prefactura_id
         * @param type $cantidad
         */
        public function actualizarSumOrdenTrabajo($prefactura_id, $idCargueInv, $cantidad){
            $this->autoRender = false;
            $this->loadModel('Prefactura');
            $this->loadModel('OrdentrabajosSuministro');
            
            //se obtiene la prefactura por id
            $arrPrefact = $this->Prefactura->obtenerPrefacturaPorId($prefactura_id);
            
            //se valida si tiene una orden de trabajo relacionada
            if(!empty($arrPrefact['Prefactura']['ordentrabajo_id'])){
                //se obtiene el suministro a editar la cantidad relacionado a la orden de trabajo
                $ordenSum = $this->OrdentrabajosSuministro->obtenerOrdenSuministro($idCargueInv, $arrPrefact['Prefactura']['ordentrabajo_id']);
                
                //si existe el suministro, se actualiza la cantidad solicitada
                if(!empty($ordenSum)){
                    $this->OrdentrabajosSuministro->actualizarCantidadSuministro($ordenSum['OrdentrabajosSuministro']['id'], $cantidad);
                }
            }
        }
        

}
 