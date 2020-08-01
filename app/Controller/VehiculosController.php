<?php
App::uses('AppController', 'Controller');
/**
 * Paises Controller
 *
 * @property Paise $Paise
 * @property PaginatorComponent $Paginator
 */
class VehiculosController extends AppController {

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
        $paginate = array();
        $this->loadModel('Tipovehiculo');
        $this->loadModel('Marcavehiculo');

        if(isset($this->passedArgs['Vehiculos']['tipovehiculo_id']) && !empty($this->passedArgs['Vehiculos']['tipovehiculo_id'])){
            $paginate['Vehiculo.tipovehiculo_id'] = $this->passedArgs['Vehiculos']['tipovehiculo_id'];
        }

        if(isset($this->passedArgs['Vehiculos']['placa']) && !empty($this->passedArgs['Vehiculos']['placa'])){
            $paginate['LOWER(Vehiculo.placa) LIKE'] = '%' . strtolower($this->passedArgs['Vehiculos']['placa']) . '%';            
        }
        
        if(isset($this->passedArgs['Vehiculos']['marcavehiculo_id']) && !empty($this->passedArgs['Vehiculos']['marcavehiculo_id'])){
            $paginate['Vehiculo.marcavehiculo_id'] = $this->passedArgs['Vehiculos']['marcavehiculo_id'];
        }

        $this->Vehiculo->recursive = 0;
        $this->set('vehiculos', $this->Paginator->paginate('Vehiculo', $paginate));
        
        //se obtiene el listado de marcas de vehiculos
        $arrMarcas = $this->Marcavehiculo->obtenerListaMarcavehiculos();

        //se obtiene el listado de tipos de vehiculos
        $arrTipV = $this->Tipovehiculo->obtenerListaTipoVehiculo();
        $this->set(compact('arrTipV', 'arrMarcas'));
    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function view($id = null) {
        $this->loadModel('Tipovehiculo');
        $this->loadModel('Marcavehiculo');
        if (!$this->Vehiculo->exists($id)) {
                throw new NotFoundException(__('El vehículo no existe.'));
        }
        $options = array('conditions' => array('Vehiculo.' . $this->Vehiculo->primaryKey => $id));
        $this->set('vehiculo', $this->Vehiculo->find('first', $options));
        
        //se obtiene el listado de marcas de vehiculos
        $arrMarcas = $this->Marcavehiculo->obtenerListaMarcavehiculos();        

        //se obtiene el listado de tipos de vehiculos
        $arrTipV = $this->Tipovehiculo->obtenerListaTipoVehiculo();
        $this->set(compact('arrTipV', 'arrMarcas'));
    }

/**
 * add method
 *
 * @return void
 */
    public function add() {
        $this->loadModel('Tipovehiculo');
        $this->loadModel('Marcavehiculo');
        if ($this->request->is('post')) {
                $this->Vehiculo->create();
                if ($this->Vehiculo->save($this->request->data)) {
                        $this->Session->setFlash(__('El vehículo ha sido guardado.'));
                        return $this->redirect(array('action' => 'index'));
                } else {
                        $this->Session->setFlash(__('El vehiculo no pudo ser guardado. Por favor, inténtelo de nuevo.'));
                }
        }
        
        //se obtiene el listado de marcas de vehiculos
        $arrMarcas = $this->Marcavehiculo->obtenerListaMarcavehiculos();         

        //se obtiene el listado de tipos de vehiculos
        $arrTipV = $this->Tipovehiculo->obtenerListaTipoVehiculo();
        $this->set(compact('arrTipV', 'arrMarcas'));            
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function edit($id = null) {
        $this->loadModel('Tipovehiculo');
        $this->loadModel('Marcavehiculo');
        
        if (!$this->Vehiculo->exists($id)) {
                throw new NotFoundException(__('El vehículo no existe.'));
        }
        if ($this->request->is(array('post', 'put'))) {
                if ($this->Vehiculo->save($this->request->data)) {
                        $this->Session->setFlash(__('El vehículo ha sido guardado.'));
                        return $this->redirect(array('action' => 'index'));
                } else {
                        $this->Session->setFlash(__('El vehículo no pudo ser guardado. Por favor, inténtelo de nuevo.'));
                }
        } else {
            $options = array('conditions' => array('Vehiculo.' . $this->Vehiculo->primaryKey => $id));            
            $vehiculo = $this->request->data = $this->Vehiculo->find('first', $options);
            
             
            //se obtiene el listado de marcas de vehiculos
            $arrMarcas = $this->Marcavehiculo->obtenerListaMarcavehiculos();                

            //se obtiene el listado de tipos de vehiculos
            $arrTipV = $this->Tipovehiculo->obtenerListaTipoVehiculo();
            $this->set(compact('arrTipV', 'id', 'arrMarcas', 'vehiculo'));                          
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
        $this->Vehiculo->id = $id;
        if (!$this->Vehiculo->exists()) {
                throw new NotFoundException(__('El vehículo no existe.'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Vehiculo->delete()) {
                $this->Session->setFlash(__('El vehículo ha sido eliminado.'));
        } else {
                $this->Session->setFlash(__('El vehículo no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
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
     * obtiene el vehiculo por llamado ajax y retorna las coincidencias por placa
     */
    public function ajaxObtenerVehiculo(){
        $this->loadModel('Vehiculo');
        
        $this->autoRender = false;
        
        $posData = $this->request->data;
        $vehiculo = strtolower($posData['datosVehiculo']);
        $resp = $this->Vehiculo->obtenerDatosVehiculo($vehiculo);
        echo json_encode(array('resp' => $resp));          
    }
    
    /**
     * obtienen el vehiculo por id
     */
    public function ajaxObtenerVehiculoPorId(){
        $this->loadModel('Vehiculo');
        
        $this->autoRender = false;
        
        $posData = $this->request->data;
        $resp = $this->Vehiculo->obtenerVehiculoPorId($posData['vehiculoId']);
        echo json_encode(array('resp' => $resp));         
    }
    
    public function ajaxActualizarSoatTecno(){
        $this->loadModel('Vehiculo');
        
        $this->autoRender = false;
        
        $posData = $this->request->data;
        
        $data['id'] = $posData['vehiculoId'];
        $data['soat'] = $posData['soat'];
        $data['tecno'] = $posData['tecno'];
        
        $this->Vehiculo->actualizarDatosVehiculo($data);
    }
    
    public function ajaxValidarVehiculoUnico(){
        $this->loadModel('Vehiculo');
        
        $this->autoRender = false;
        
        $posData = $this->request->data;
        
        $placa = $posData['placaN'];
        
        if(!empty($placa)){
            $vehiculo = $this->Vehiculo->obtenerDatosVehiculo($placa);
            
            if(!empty($vehiculo)){
                echo json_encode(array('resp' => '1', 'data' => $vehiculo));
            }else{
                echo json_encode(array('resp' => '0'));
            }            
        }else{
            echo json_encode(array('resp' => '0'));
        }
    }
}
