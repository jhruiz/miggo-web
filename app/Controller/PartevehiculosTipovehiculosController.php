<?php
App::uses('AppController', 'Controller');

class PartevehiculosTipovehiculosController extends AppController {

    public $components = array('Paginator');

    public function index() {
        $this->loadModel('Tipovehiculo');
        $this->loadModel('Partevehiculo');
        $this->loadModel('PartevehiculosTipovehiculo');
        $arrFilter = array();

        if(isset($this->passedArgs['partevehiculo']) && !empty($this->passedArgs['partevehiculo'])){
            $arrFilter['PartevehiculosTipovehiculo.partevehiculo_id'] = $this->passedArgs['partevehiculo'];
        }
        
        if(isset($this->passedArgs['tipovehiculo']) && !empty($this->passedArgs['tipovehiculo'])){
            $arrFilter['PartevehiculosTipovehiculo.tipovehiculo_id'] = $this->passedArgs['tipovehiculo'];
        }

        $arrTipParVehiculo = $this->PartevehiculosTipovehiculo->obtenerTipoParteV($arrFilter); 
        
        //se obtiene el listado de tipos de vehiculos
        $arrTipoVehiculo = $this->Tipovehiculo->obtenerListaTipoVehiculo();
        
        //se obtiene el listado de partes de vehiculos
        $arrParteVehiculo = $this->Partevehiculo->obtenerListaParteVehiculo();
                
        $this->set(compact('arrTipoVehiculo', 'arrParteVehiculo', 'arrTipParVehiculo'));
    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function view($id = null) {
            if (!$this->PartevehiculosTipovehiculo->exists($id)) {
                    throw new NotFoundException(__('El Tipo Vehículo - Parte Vehículo no existe.'));
            }
            $arrFilter['PartevehiculosTipovehiculo.id'] = $id;
            $arrTipParVehiculo = $this->PartevehiculosTipovehiculo->obtenerTipoParteV($arrFilter); 

            $this->set(compact('arrTipParVehiculo'));
    }

/**
 * add method
 *
 * @return void
 */
    public function add() {
        if ($this->request->is('post')) {
                $this->PartevehiculosTipovehiculo->create();
                if ($this->PartevehiculosTipovehiculo->save($this->request->data)) {
                        $this->Session->setFlash(__('El Tipo Vehículo - Parte Vehículo ha sido guardado.'));
                        return $this->redirect(array('action' => 'index'));
                } else {
                        $this->Session->setFlash(__('El Tipo Vehículo - Parte Vehículo no pudo ser guardado. Por favor, inténtelo de nuevo.'));
                }
        }
            $this->loadModel('Tipovehiculo');
            $this->loadModel('Partevehiculo');
            
            //se obtiene el listado de tipos de vehiculos
            $arrTipoVehiculo = $this->Tipovehiculo->obtenerListaTipoVehiculo();

            //se obtiene el listado de partes de vehiculos
            $arrParteVehiculo = array();
            
            $this->set(compact('arrTipoVehiculo', 'arrParteVehiculo'));                      


    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function edit($id = null) {

            if (!$this->PartevehiculosTipovehiculo->exists($id)) {
                    throw new NotFoundException(__('El Tipo Vehículo - Parte Vehículo no existe.'));
            }
            if ($this->request->is(array('post', 'put'))) {
                    if ($this->PartevehiculosTipovehiculo->save($this->request->data)) {
                            $this->Session->setFlash(__('El Tipo Vehículo - Parte Vehículo ha sido guardado.'));
                            return $this->redirect(array('action' => 'index'));
                    } else {
                            $this->Session->setFlash(__('El Tipo Vehículo - Parte Vehículo no pudo ser guardado. Por favor, inténtelo de nuevo.'));
                    }
            } else {
                $this->loadModel('Tipovehiculo');
                $this->loadModel('Partevehiculo');

                //info tipo vehiculo - parte vehiculo
                $arrFilter['PartevehiculosTipovehiculo.id'] = $id;
                $arrTipParVehiculo = $this->PartevehiculosTipovehiculo->obtenerTipoParteV($arrFilter);                     

                //se obtiene el listado de tipos de vehiculos
                $arrTipoVehiculo = $this->Tipovehiculo->obtenerListaTipoVehiculo();

                //se obtiene el listado de partes de vehiculos
                $arrParteVehiculo = $this->Partevehiculo->obtenerListaParteVehiculo();

                $this->set(compact('arrTipoVehiculo', 'arrParteVehiculo', 'arrTipParVehiculo', 'id'));
            }
    }

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function delete($id = null) {
            $this->PartevehiculosTipovehiculo->id = $id;
            if (!$this->PartevehiculosTipovehiculo->exists()) {
                    throw new NotFoundException(__('El Tipo Vehículo - Parte Vehículo no existe.'));
            }
            $this->request->onlyAllow('post', 'delete');
            if ($this->PartevehiculosTipovehiculo->delete()) {
                    $this->Session->setFlash(__('El Tipo Vehículo - Parte Vehículo ha sido eliminado.'));
            } else {
                    $this->Session->setFlash(__('El Tipo Vehículo - Parte Vehículo no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
            }
            return $this->redirect(array('action' => 'index'));
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
     * Obtiene las partes del vehiculo, segun el vehiculo seleccionado y sus posibles estados
     */
    public function ajaxObtenerPartesVehiculo(){
        $this->loadModel('Vehiculo');
        $this->loadModel('PartevehiculosTipovehiculo');
        $this->loadModel('Estadoparte');
        $this->loadModel('OrdentrabajosPartevehiculo');
        $this->autoRender = false;

        $posData = $this->request->data;
        $vehiculoId = $posData['idVehiculo'];
        $ordenId = $posData['ordenId'];        
        
        //obtienen la informacion del vehiculo por id
        $arrVehiculo = $this->Vehiculo->obtenerVehiculoPorId($vehiculoId);
        
        //se obtienen las partes del vehiculo por tipo de vehiculo
        $arrPartesV = $this->PartevehiculosTipovehiculo->obtenerPartesPorTipoVehiculoId($arrVehiculo['Vehiculo']['tipovehiculo_id']);
                
        //se obtienen los estados que se le pueden asignar a las partes del vehiculo
        $arrEstPartes = $this->Estadoparte->obtenerInfoEstadoPartes();  
        
        //se eliminan las partes que tenga asignada la orden de trabajo
        $this->OrdentrabajosPartevehiculo->eliminarOrdenTrabajoPartesVehiculo($ordenId);
        
        //se asignan las partes del vehiculo a la orden de trabajo en estado "No aplica"
        foreach($arrPartesV as $val){
            $this->OrdentrabajosPartevehiculo->crearPartesOrdenTrabajo($val['PV']['id'], '1', $ordenId);
        }
        

        echo json_encode(array('partes' => $arrPartesV, 'estados' => $arrEstPartes));             
    }
    
    /**
     * se obtienen las partes de vehiculos que no tiene asigandas aun
     */
    public function ajaxObtenerPartesTipoVehiculo(){
        $this->loadModel('PartevehiculosTipovehiculo');
        $this->loadModel('Partevehiculo');
        $this->autoRender = false;

        $posData = $this->request->data;
        $tipoV = $posData['tipoV'];

        //se obtienen las partes asignadas al tipo de vehiculo  
        $arrParteAsig = $this->PartevehiculosTipovehiculo->obtenerPartesPorTipoVehiculoId($tipoV);
        
        //se obtiene el listado de las partes de vehiculos
        $arrPartes = $this->Partevehiculo->obtenerListaParteVehiculo();
        
        foreach ($arrParteAsig as $pa){
           if(in_array($pa['PV']['descripcion'], $arrPartes)){
               unset($arrPartes[$pa['PV']['id']]);
           }
        }
        echo json_encode(array('partes' => $arrPartes));                     
    }
}
