<?php
App::uses('AppController', 'Controller');

class OrdentrabajosController extends AppController {


    public $components = array('Paginator');


    public function index() {
        $this->loadModel('Ordentrabajo');
        $this->loadModel('Usuario');
        $this->loadModel('Plantaservicio');
        $this->loadModel('Cliente');
        $this->loadModel('Ordenestado');
        $paginate = array();
        
        //se obtienen los estados finalizados
        $estFin = $this->Ordenestado->obtenerEstadosFin();
        
        $perfilId = $this->Auth->user('perfile_id');
        $usuarioId = $this->Auth->user('id');
        $arrFilter = array();
               
        $flgE = true;
        if($perfilId != '1' && $perfilId != '4' && $perfilId != '5'){
            $paginate['Ordentrabajo.usuario_id'] = $usuarioId;
            $flgE = false;
        }

        //filtros del index
        if(!empty($this->passedArgs['usuario'])){
            $paginate['Ordentrabajo.usuario_id'] = $this->passedArgs['usuario'];
        }
        
        if(!empty($this->passedArgs['cliente'])){
            $paginate['Ordentrabajo.cliente_id'] = $this->passedArgs['cliente'];
        }
        
        if(!empty($this->passedArgs['plantaservicio'])){
            $paginate['Ordentrabajo.plantaservicio_id'] = $this->passedArgs['plantaservicio'];
        }
        
        if(!empty($this->passedArgs['ordenestado'])){
            $paginate['Ordentrabajo.ordenestado_id'] = $this->passedArgs['ordenestado'];
        }
        
        if(!empty($this->passedArgs['vehiculo'])){
            $paginate['Ordentrabajo.vehiculo_id'] = $this->passedArgs['vehiculo'];
        }        
                
        //se obtienen los estados de las ordenes
        $arrOrdenT = $this->obtenerOrdenTrabajos($paginate);
        
        //se obtiene el listado de usuarios
        $arrUsr = $this->Usuario->obtenerUsuarioEmpresa($this->Auth->user('empresa_id'));
        
        //se obtiene el listado de plantas de servicios
        $arrPlantas = $this->Plantaservicio->obtenerListaPlantasServicio();
        
        //se obtiene el listado clientes
        $arrClientes = $this->Cliente->obtenerClienteEmpresa($this->Auth->user('empresa_id'));
        
        $arrOrdenEst = $this->Ordenestado->obtenerListaEstados();
                        
        $this->set('arrOrdenT', $this->Paginator->paginate('Ordentrabajo', $paginate));

        $this->set(compact('arrUsr', 'arrPlantas', 'arrClientes', 'arrOrdenEst', 'arrOrdenT','estFin', 'flgE'));
        
    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function view($id = null) {
        if (!$this->Ordentrabajo->exists($id)) {
            throw new NotFoundException(__('La orden de trabajo no existe.'));
        }
        $this->loadModel('Usuario');
        $this->loadModel('Plantaservicio');
        $this->loadModel('Ordentrabajo');
        $this->loadModel('Ordenestado');
        $this->loadModel('OrdentrabajosSuministro');
        $this->loadModel('Estadoparte');
        $this->loadModel('OrdentrabajosPartevehiculo');
        
        $empresaId = $this->Auth->user('empresa_id');       
        $usuarioId = $this->Auth->user('id');       
        
        $arrFilter['Ordentrabajo.id'] = $id;
        //se obtienen los estados de las ordenes
        $arrOrdenT = $this->Ordentrabajo->obtenerOrdenesTrabajo($arrFilter); 
        
        //se obtiene el listado de usuarios
        $arrUsr = $this->Usuario->obtenerUsuarioEmpresa($empresaId);     
        
        //se obtiene el listado de plantas de servicios
        $arrPlantas = $this->Plantaservicio->obtenerListaPlantasServicio();
        
        //se obtienen los estados
        $arrOrdenEst = $this->Ordenestado->obtenerListaEstados();        
        
        //se obtienen los suministros de la orden de trabajo
        $arrSums = $this->OrdentrabajosSuministro->obtenerSuministrosProductos($id);
        
        //se obtienen las partes del vehiculo
        $arrPartesV = $this->OrdentrabajosPartevehiculo->obtenerEstadosPartesOrden($id);

        //se obtiene el estado de las partes
        $arrEstadoP = $this->Estadoparte->obtenerListaEstados();
               
        $this->set(compact('arrUsr', 'arrPlantas', 'arrOrdenEst', 'empresaId', 'usuarioId', 'id', 'arrOrdenT', 'arrSums'));
        $this->set(compact('arrEstadoP', 'arrPartesV'));
    }

/**
 * add method
 *
 * @return void
 */
    public function add() {
        $this->loadModel('Usuario');
        $this->loadModel('Plantaservicio');
        $this->loadModel('Ordenestado');
        $this->loadModel('Empresa');
        $this->loadModel('Configuraciondato');
        $this->loadModel('Ciudade');
        
        if ($this->request->is('post')) {
            $this->Ordentrabajo->create();
            if ($this->Ordentrabajo->save($this->request->data)) {
                $this->Session->setFlash(__('La orden de trabajo ha sido guardada.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('La orden de trabajo no pudo ser guardada. Por favor, inténtelo de nuevo.'));
            }
        }        

        $empresaId = $this->Auth->user('empresa_id');
        
        $usuarioId = $this->Auth->user('id');
        
        //se obtiene el listado de usuarios
        $arrUsr = $this->Usuario->obtenerUsuarioEmpresa($empresaId);
        
        //se obtiene el listado de plantas de servicios
        $arrPlantas = $this->Plantaservicio->obtenerListaPlantasServicio();        
        
        //se obtienen los estados
        $arrOrdenEst = $this->Ordenestado->obtenerListaEstados();         
        
        $arrEmprea = $this->Empresa->obtenerEmpresaPorId($empresaId);
        /*Se obtiene la url de las imagenes de las empresas*/
        $strDato = "urlImgEmpresa";
        $urlImg = $this->Configuraciondato->obtenerValorDatoConfig($strDato);   

        //se obtiene la url de la imagend e whatsapp
        $strDatoWP = "ulrImgWP";
        $urlImgWP = $this->Configuraciondato->obtenerValorDatoConfig($strDatoWP);        
        
        //se obtiene la ciudad y el pais
        $arrUbicacion = $this->Ciudade->obtenerCiudadPais($arrEmprea['Empresa']['ciudade_id']);             

        $fecha = $this->obtenerFechaActual();        
        
        $this->set(compact('arrUsr', 'arrPlantas', 'arrOrdenEst', 'empresaId', 'usuarioId', 'flgV', 'arrEmprea', 'urlImg', 'urlImgWP'));        
        $this->set(compact('arrUbicacion', 'fecha'));        
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function edit($id = null) {
        $this->loadModel('Usuario');
        $this->loadModel('Plantaservicio');
        $this->loadModel('Ordentrabajo');
        $this->loadModel('Ordenestado');
        $this->loadModel('OrdentrabajosSuministro');
        $this->loadModel('Estadoparte');
        $this->loadModel('OrdentrabajosPartevehiculo');
        $this->loadModel('Empresa');
        $this->loadModel('Configuraciondato');
        $this->loadModel('Ciudade');
        
        $empresaId = $this->Auth->user('empresa_id');       
        $usuarioId = $this->Auth->user('id');       
        
        $arrFilter['Ordentrabajo.id'] = $id;
        //se obtienen los estados de las ordenes
        $arrOrdenT = $this->Ordentrabajo->obtenerOrdenesTrabajo($arrFilter);        
        
        //se obtiene el listado de usuarios
        $arrUsr = $this->Usuario->obtenerUsuarioEmpresa($empresaId);     
        
        //se obtiene el listado de plantas de servicios
        $arrPlantas = $this->Plantaservicio->obtenerListaPlantasServicio();
        
        //se obtienen los estados
        $arrOrdenEst = $this->Ordenestado->obtenerListaEstados();        
        
        //se obtienen los suministros de la orden de trabajo
        $arrSums = $this->OrdentrabajosSuministro->obtenerSuministrosProductos($id);
        
        //se obtienen las partes del vehiculo
        $arrPartesV = $this->OrdentrabajosPartevehiculo->obtenerEstadosPartesOrden($id);

        //se obtiene el estado de las partes
        $arrEstadoP = $this->Estadoparte->obtenerListaEstados();
        
        $arrEmprea = $this->Empresa->obtenerEmpresaPorId($empresaId);
        /*Se obtiene la url de las imagenes de las empresas*/
        $strDato = "urlImgEmpresa";
        $urlImg = $this->Configuraciondato->obtenerValorDatoConfig($strDato);   

        //se obtiene la url de la imagend e whatsapp
        $strDatoWP = "ulrImgWP";
        $urlImgWP = $this->Configuraciondato->obtenerValorDatoConfig($strDatoWP);          
        
        //se obtiene la ciudad y el pais
        $arrUbicacion = $this->Ciudade->obtenerCiudadPais($arrEmprea['Empresa']['ciudade_id']);             

        $fecha = $this->obtenerFechaActual();          
               
        $this->set(compact('arrUsr', 'arrPlantas', 'arrOrdenEst', 'empresaId', 'usuarioId', 'id', 'arrOrdenT', 'arrSums'));
        $this->set(compact('arrEstadoP', 'arrPartesV', 'urlImg', 'arrUbicacion', 'fecha', 'arrEmprea', 'urlImgWP'));
    }

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $idOrden
 * @return void
 */
    public function delete($idOrden = null) {
        $this->loadModel('OrdentrabajosPartevehiculo');
        $this->loadModel('OrdentrabajosSuministro');
        
        //se elimina el registro de las partes de los vehiculos
        $this->OrdentrabajosPartevehiculo->eliminarOrdenTrabajoPartesVehiculo($idOrden);        
        
        //se obtienen los suministros asignados a la orden de trabajo
        $arrOrdSums = $this->OrdentrabajosSuministro->obtenerSuministrosOrden($idOrden);
        foreach ($arrOrdSums as $val){
            //se restaura la existencia del inventario 
            $this->restaurarCargueInventario($val['OrdentrabajosSuministro']['cargueinventario_id'], $val['OrdentrabajosSuministro']['cantidad']); 
            
            //se elimina el registro del suministro
            $this->OrdentrabajosSuministro->eliminarSuministroOrden($val['OrdentrabajosSuministro']['id']);
        }
        
        //se elimina la orden de trabajo
        $this->Ordentrabajo->id = $idOrden;
        if (!$this->Ordentrabajo->exists()) {
            throw new NotFoundException(__('La orden de trabajo no existe.'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Ordentrabajo->delete()) {
            $this->Session->setFlash(__('La orden de trabajo ha sido eliminada.'));
        } else {
            $this->Session->setFlash(__('La orden de trabajo no pudo ser eliminada. Por favor, inténtelo de nuevo.'));
        }
        return $this->redirect(array('action' => 'index'));
    }
        
    /**
     * Funcion que obtiene los registros para filtrar las ordenes de trabajo
     */
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
     * Crea la orden de trabajo
     */
    public function ajaxCrearOrdenTrabajo(){
        $this->autoRender = false;
        $this->loadModel('Ordentrabajo');                

        $posData = $this->request->data;
        $data = array();
        
        //se obtiene la informacion del formulario para realizar la creacion de la orden de trabajo
        $data['ordenestado_id'] = !empty($posData['ordenestado']) ? $posData['ordenestado'] : "";
        $data['kilometraje'] = !empty($posData['Ordentrabajo']['Kilometraje']) ? $posData['Ordentrabajo']['Kilometraje'] : "";
        $data['vehiculo_id'] = !empty($posData['Ordentrabajo']['vehiculo']) ? $posData['Ordentrabajo']['vehiculo'] : "";
        $data['usuario_id'] = !empty($posData['usuario']) ? $posData['usuario'] : $this->Auth->user('id');
        $data['cliente_id'] = !empty($posData['cliente_id']) ? $posData['cliente_id'] : "";
        $data['fecha_ingreso'] = !empty($posData['Ordentrabajo']['fecha_ingreso']) ? $posData['Ordentrabajo']['fecha_ingreso'] : "";
        $data['fecha_salida'] = !empty($posData['Ordentrabajo']['fecha_salida']) ? $posData['Ordentrabajo']['fecha_salida'] : "";
        $data['plantaservicio_id'] = !empty($posData['plantaservicio']) ? $posData['plantaservicio'] : "";
        $data['codigo'] = date("YmdHis");
        $data['soat'] = !empty($posData['Ordentrabajo']['soat']) ? $posData['Ordentrabajo']['soat'] : "";
        $data['tecnomecanica'] = !empty($posData['Ordentrabajo']['tecnomecanica']) ? $posData['Ordentrabajo']['tecnomecanica'] : "";
        
        $ordenId = $this->Ordentrabajo->crearActualizarOrdenTrabajo($data);
        
        echo json_encode(array('resp' => $ordenId));  
              
    }
    
    /**
     * Se actualiza la orden de trabajo
     */
    public function ajaxActualizarOrdenTrabajo(){
        $this->autoRender = false;
        $this->loadModel('Ordentrabajo');
        
        $posData = $this->request->data;
        $data = array();
        
        $data['id'] = $posData['idOrdenT'];
        $data[$posData['campo']] = $posData['valor'];
        
        $ordenId = $this->Ordentrabajo->crearActualizarOrdenTrabajo($data);
        
        echo json_encode(array('resp' => $ordenId)); 
        
    }
    
    public function ajaxFinalizarOrdenTrabajo(){
        $this->autoRender = false;

        $this->loadModel('Ordentrabajo');
        $this->loadModel('Prefactura');
        $this->loadModel('Cargueinventario');
        $this->loadModel('Prefacturasdetalle');
        $this->loadModel('OrdentrabajosSuministro');
        $this->loadModel('Ordenestado');
        
        $posData = $this->request->data;
        $data = array();
        
        $data['id'] = $posData['idOrdenT'];
        $data['ordenestado_id'] = $posData['estadoId'];        
        
        $this->Ordentrabajo->crearActualizarOrdenTrabajo($data);
        
        //se obtiene la informacion del estado
        $arrEstadosO = $this->Ordenestado->obtenerEstadoPorId($posData['estadoId']);
        
        //Se valida que no exista una prefactura asociada a la orden de trabajo
        $arrPreFOrden = $this->Prefactura->obtenerPrefactPorOrden($posData['idOrdenT']);
        
        if(!empty($arrPreFOrden)){
            echo json_encode(array('resp' => $arrPreFOrden['Prefactura']['ordentrabajo_id'], 'estadofin' => $arrEstadosO['Ordenestado']['ordenfinal']));
        }else{
            //se obtiene la orden de trabajo
            $arrOrdenT = $this->Ordentrabajo->obtenerOrdenPorId($posData['idOrdenT']);

            //se obtienen los suministros de la orden de trabajo
            $arrSuministros = $this->OrdentrabajosSuministro->obtenerSuministrosOrden($posData['idOrdenT']);

            if(!empty($arrOrdenT)){
                //se crea la prefactura
                $preFactId = $this->Prefactura->guardarPrefactura($arrOrdenT['Ordentrabajo']['usuario_id'],$arrOrdenT['Ordentrabajo']['cliente_id'], $posData['idOrdenT']);

                //se valida la creacion de la prefactura
                if(!empty($preFactId)){
                    $arrSuministros = $this->OrdentrabajosSuministro->obtenerSuministrosOrden($posData['idOrdenT']);

                    //se crea el detalle de la prefactura
                    foreach ($arrSuministros as $sum){
                        //se obtiene la informacion del cargueinventario 
                        $arrCargueInv = $this->Cargueinventario->obtenerInventarioId($sum['OrdentrabajosSuministro']['cargueinventario_id']);

                        //se crea el detalle de la prefactura con los suministros de la orden de trabajo
                        $this->Prefacturasdetalle->guardarDetallePrefactura($sum['OrdentrabajosSuministro']['cantidad'],
                                $arrCargueInv['Cargueinventario']['precioventa'],$sum['OrdentrabajosSuministro']['cargueinventario_id'],$preFactId);
                    } 

                    echo json_encode(array('resp' => $preFactId, 'estadofin' => $arrEstadosO['Ordenestado']['ordenfinal']));
                }else{
                    echo json_encode(array('resp' => '0'));
                }
            }else{
                echo json_encode(array('resp' => '0')); 
            }            
        }
    }
    
    public function restaurarCargueInventario($carInvId, $cantidad){
        $this->loadModel('Cargueinventario');
        
        //se obtiene la informacion cargue inventario por id
        $arrCarInv = $this->Cargueinventario->obtenerInventarioId($carInvId);
        
        //se obtiene la cantidad actual
        $cantFinal = intval($arrCarInv['Cargueinventario']['existenciaactual']) + intval($cantidad);
        
        //se actualiza la cantidad actual del cargue inventario
        $this->Cargueinventario->actalizarExistenciaStock($carInvId, $cantFinal);
        
    }
    
    /**
     * se obtiene la fecha actual
     */
    public function obtenerFechaActual(){
        $mAct = date('m');

        //array meses
        $meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');

        $fechaActual = $meses[$mAct-1] . " " . date('d') . ", " . date('Y');

        return $fechaActual;
    }       
    
    /**
     * Se obtienen las ordenes de trabajo y se asigna el color del semaforo
     * @param type $arrFilter
     * @return type
     */
    public function obtenerOrdenTrabajos($arrFilter){
        $this->loadModel('Semaforo');  
           
        $this->Ordentrabajo->recursive = 0;
        $this->Paginator->settings = $this->Ordentrabajo->obtenerIndexOrdenesTrabajo($arrFilter);
        $ordenes = $this->Paginator->paginate('Ordentrabajo'); 
        
        if(!empty($ordenes)){
            //se obtienen los semaforos
            $semaforos = $this->Semaforo->obtenerValoresSemaforos();
            
            if(!empty($semaforos)){
                
                $fechaActual = date('Y-m-d');
                $dateAct = new DateTime($fechaActual);
                foreach ($ordenes as $key => $ord){                
                    //se obtiene la cantidad de dias que lleva 
                    $fechaOrden = new DateTime($ord['Ordentrabajo']['fecha_ingreso']);                
                    $diff = $fechaOrden->diff($dateAct);
                    $dias = $diff->days;                      

                    $semColor = "";
                    foreach($semaforos as $sem){                                                
                        if($dias >= $sem['Semaforo']['rango_inicial'] && $dias <= $sem['Semaforo']['rango_final']){
                            $semColor = $sem['Semaforo']['color'];
                            break;
                        }else{
                            $semColor = $sem['Semaforo']['color'];
                        }                       
                        
                    }  
                    
                    $ordenes[$key]['semaforo']['color'] = $semColor;
                    $ordenes[$key]['semaforo']['dias'] = $dias;
                }                
            }

        }
        return $ordenes;
        
    }

    public function ordenesPrefacturas(){
        $this->loadModel('Prefactura');
        $this->loadModel('Estadosprefactura');

        $filterOT = array('OE.ordenfinal <>' => '1');

        //////////////////////////////// INFORMACION DE LAS PREFACTURAS/////////////////////
        //obtiene todas las prefacturas
        $prefacturas = $this->Prefactura->obtenerPrefacturas(null, null, null);
        $estados = $this->Estadosprefactura->obtenerListaEstados();

        //////////////////////////////// INFORMACION DE ORDENES DE TRABAJO/////////////////////
        $ordenes = $this->Ordentrabajo->obtenerOrdenesTrabajo($filterOT);

        $this->set(compact('prefacturas', 'estados', 'ordenes'));
        
    }
                
}
