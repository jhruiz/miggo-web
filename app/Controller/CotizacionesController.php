<?php
App::uses('AppController', 'Controller');
/**
 * Cotizaciones Controller
 *
 * @property Cotizacione $Cotizacione
 * @property PaginatorComponent $Paginator
 */
class CotizacionesController extends AppController {

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
        $this->loadModel('Cotizacione');
        $this->loadModel('Cliente');
            
        $perfilId = $this->Auth->user('perfile_id');
        $empresaId = $this->Auth->user('empresa_id');
        $usuarioId = $this->Auth->user('id');
        $arrFilter = array();
               
        $flgE = true;
        if($perfilId != '1' && $perfilId != '4' && $perfilId != '5'){
            $arrFilter['Cotizacione.usuario_id'] = $usuarioId;
            $flgE = false;
        }
        
        //se obtienen toda la informacion de cotizaciones
        $arrCot = $this->Cotizacione->obtenerCotizaciones($arrFilter);

        //se obtiene el listado de clientes
        $arrCli = $this->Cliente->obtenerClienteEmpresa($empresaId);
            
        $this->set(compact('arrCot', 'arrCli'));
            
            
    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {

	}

/**
 * add method
 *
 * @return void
 */
	public function add() {            		
            $this->loadModel('Usuario');  
            $this->loadModel('Configuraciondato');  
            $this->loadModel('Empresa');  
            $this->loadModel('Ciudade');  
            
            $empresaId = $this->Auth->user('empresa_id');
            $usuarioId = $this->Auth->user('id');
            $vendedor = $this->Usuario->obtenerUsuarioEmpresa($empresaId);
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

            $this->set(compact('empresaId', 'usuarioId', 'vendedor', 'arrEmprea', 'urlImg', 'fecha', 'arrUbicacion', 'urlImgWP'));            
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {            
            $this->loadModel('Cotizacione');
            $this->loadModel('Usuario');
            $this->loadModel('Empresa');
            $this->loadModel('Ciudade');
            $this->loadModel('Configuraciondato');
            $this->loadModel('Vehiculo');
            
            //se obtienen los datos de la cotizacion
            $arrCotiza = $this->Cotizacione->obtenerCotizacionCliDet($id);
            
            //se obtiene la url de la imagend e whatsapp
            $strDatoWP = "ulrImgWP";
            $urlImgWP = $this->Configuraciondato->obtenerValorDatoConfig($strDatoWP);             
            
            //se saca la informacion del cliente registrado si existe
            $cliName = !empty($arrCotiza['0']['C']['nombre']) ? $arrCotiza['0']['C']['nombre'] : ""; 
            $cliDir = !empty($arrCotiza['0']['C']['direccion']) ? $arrCotiza['0']['C']['direccion'] : ""; 
            $cliNit = !empty($arrCotiza['0']['C']['nit']) ? $arrCotiza['0']['C']['nit'] : ""; 
            $cliDias = !empty($arrCotiza['0']['C']['diascredito']) ? $arrCotiza['0']['C']['diascredito'] : ""; 
            $cliTel = !empty($arrCotiza['0']['C']['celular']) ? $arrCotiza['0']['C']['celular'] : ""; 
            $cliLimC = !empty($arrCotiza['0']['C']['limitecredito']) ? $arrCotiza['0']['C']['limitecredito'] : ""; 
            $obs = !empty($arrCotiza['0']['Cotizacione']['observacion']) ? $arrCotiza['0']['Cotizacione']['observacion'] : ""; 
            
            //se obtiene la informacion del vehiculo
            $arrVehiculo = $this->Vehiculo->obtenerVehiculoPorId($arrCotiza['0']['Cotizacione']['vehiculo_id']);
            
            if(empty($arrCotiza['0']['C']['nombre'])){
                //se obtienen la informacion del cliente anonimo            
                $anomName = !empty($arrCotiza['0']['Cotizacione']['nombre_cliente']) ? $arrCotiza['0']['Cotizacione']['nombre_cliente'] : "";
                $anomCC = !empty($arrCotiza['0']['Cotizacione']['identificacion_cliente']) ? $arrCotiza['0']['Cotizacione']['identificacion_cliente'] : "";
                $anomTel = !empty($arrCotiza['0']['Cotizacione']['telefono_cliente']) ? $arrCotiza['0']['Cotizacione']['telefono_cliente'] : "";
                $anomDir = !empty($arrCotiza['0']['Cotizacione']['direccion_cliente']) ? $arrCotiza['0']['Cotizacione']['direccion_cliente'] : "";
            }else{
                $anomName = "";
                $anomCC = "";
                $anomTel = "";
                $anomDir = "";               
            }
            $empresaId = $this->Auth->user('empresa_id');
            $arrEmprea = $this->Empresa->obtenerEmpresaPorId($empresaId);
            $vendedor = $this->Usuario->obtenerUsuarioEmpresa($empresaId);
            
            /*Se obtiene la url de las imagenes de las empresas*/
            $strDato = "urlImgEmpresa";
            $urlImg = $this->Configuraciondato->obtenerValorDatoConfig($strDato);             
            
            //se obtiene la ciudad y el pais
            $arrUbicacion = $this->Ciudade->obtenerCiudadPais($arrEmprea['Empresa']['ciudade_id']);             
            
            $fecha = $this->obtenerFechaActual();
            
            $this->set(compact('arrCotiza', 'empresaId', 'arrVehiculo', 'urlImgWP'));
            $this->set(compact('cliName', 'cliDir', 'cliNit', 'cliDias', 'cliTel', 'cliLimC'));
            $this->set(compact('anomName', 'anomCC', 'anomTel', 'anomDir', 'vendedor'));
            $this->set(compact('arrEmprea', 'arrUbicacion', 'fecha', 'urlImg', 'obs'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
            $this->loadModel('Cotizacionesdetalle');

            $this->autoRender = false;
            //se obtienen los detalles de las cotizaciones
            $arrCotDet = $this->Cotizacionesdetalle->obtenerDetallePorCotId($id);
            foreach($arrCotDet as $det){
                $data = array();
                $data['Cotizacionesdetalle.id'] = $det['Cotizacionesdetalle']['id'];
                $this->Cotizacionesdetalle->eliminarDetalleCotizacion($data);
            }            
            
            if($this->Cotizacione->eliminarCotizacion($id)){
                $this->Session->setFlash(__('La cotización ha sido eliminada.'));  
                return $this->redirect(array('action' => 'index'));
            }else{
                $this->Session->setFlash(__('La cotización no pudo ser eliminada. Por favor, inténtelo de nuevo.'));  
                return $this->redirect(array('action' => 'index'));
            }
	}
        
        /**
         * Crea la cotizacion cuando el cliente se encuentra registrado en la base de datos
         */
        public function crearActualizarCotizacionClienteRegistrado(){
            $this->autoRender = false;
            $this->loadModel('Cotizacione');                

            $posData = $this->request->data;
            $data = array();
            
            //se obtiene la informacion del formulario para realizar la creacion de la cotizacion
            if(!empty($posData['Cotizacione']['cotizacion_id'])){
                $data['id'] = $posData['Cotizacione']['cotizacion_id'];
            }
            $data['usuario_id'] = !empty($posData['Cotizacione']['vendedor']) ? $posData['Cotizacione']['vendedor'] : "";
            $data['cliente_id'] = !empty($posData['Cotizacione']['idcliente']) ? $posData['Cotizacione']['idcliente'] : "";
            $data['nombre_cliente'] = null;
            $data['identificacion_cliente'] = null;
            $data['telefono_cliente'] = null;
            $data['direccion_cliente'] = null;

            $cotizacionId = $this->Cotizacione->crearActualizarCotizacion($data);

            echo json_encode(array('resp' => $cotizacionId));              
        }
        
        /**
         * Se crea la cotizacion cuando es de forma rapida
         */
        public function crearActualizarCotizacionRapida(){
            $this->autoRender = false;
            $this->loadModel('Cotizacione');                

            $posData = $this->request->data;
            $data = array();            
            
            //se obtiene la informacion del formulario para realizar la creacion de la cotizacion
            if(!empty($posData['Cotizacione']['cotizacion_id'])){
                $data['id'] = $posData['Cotizacione']['cotizacion_id'];
            }
            $data['usuario_id'] = !empty($posData['Cotizacione']['vendedor']) ? $posData['Cotizacione']['vendedor'] : "";
            $data['cliente_id'] = null;
            $data['nombre_cliente'] = !empty($posData['Rapida']['rapidanombre']) ? $posData['Rapida']['rapidanombre'] : "";
            $data['identificacion_cliente'] = !empty($posData['Rapida']['rapidanit']) ? $posData['Rapida']['rapidanit'] : "";
            $data['telefono_cliente'] = !empty($posData['Rapida']['rapidatelefono']) ? $posData['Rapida']['rapidatelefono'] : "";
            $data['direccion_cliente'] = !empty($posData['Rapida']['rapidadireccion']) ? $posData['Rapida']['rapidadireccion'] : "";

            $cotizacionId = $this->Cotizacione->crearActualizarCotizacion($data);

            echo json_encode(array('resp' => $cotizacionId));             
        }
        
        /**
         * Se aprueba la cotizacion y se convierte en orden de trabajo
         */
        public function ajaxAprobarCotizacion(){
            $this->autoRender = false;
            $this->loadModel('Cotizacione');                
            $this->loadModel('Ordentrabajo');                
            $this->loadModel('OrdentrabajosSuministro');                
            $this->loadModel('Cotizacionesdetalle');                
            $this->loadModel('Cargueinventario');                
            
            $posData = $this->request->data;
            $arrCotiza = $this->Cotizacione->obtenerCotizacionCliDet($posData['idCotizacion']);
            
            
            //se valida si existe la cantidad suficiente en stock para convertir la cotizacion en orden de trabajo
            $mensaje = "";
            $mensaje = $this->validarCantidadStockProductos($arrCotiza);
            
            if($mensaje == ""){
                $dataO = array();
                //se obtiene la informacion del formulario para realizar la creacion de la orden de trabajo
                $dataO['ordenestado_id'] = "1";
                $dataO['usuario_id'] = !empty($arrCotiza['0']['Cotizacione']['usuario_id']) ? $arrCotiza['0']['Cotizacione']['usuario_id'] : "";
                $dataO['cliente_id'] = !empty($arrCotiza['0']['Cotizacione']['cliente_id']) ? $arrCotiza['0']['Cotizacione']['cliente_id'] : "";
                $dataO['codigo'] = date("YmdHis");

                //se crea la orden de trabajo y se obtiene el id de la misma
                $idOrdenT = $this->Ordentrabajo->crearActualizarOrdenTrabajo($dataO);
                if(!empty($idOrdenT)){               
                    //se crea el detalle de la orde de trabajo 
                    foreach($arrCotiza as $cot){
                        //se obtiene la informacion del cargue de inventario para descontar las unidades
                        $arrCInv = $this->Cargueinventario->obtenerInventarioId($cot['CD']['cargueinventario_id']);
                        $cantFinal = $arrCInv['Cargueinventario']['existenciaactual'] - $cot['CD']['cantidad'];
                        
                        //se descargan las unidades del stock
                        $this->Cargueinventario->actalizarExistenciaStock($arrCInv['Cargueinventario']['id'], $cantFinal);
                                                
                        $this->OrdentrabajosSuministro->guardarSuministroOrden($idOrdenT, $cot['CD']['cargueinventario_id'], $cot['CD']['cantidad']);
                        $arrCD['Cotizacionesdetalle.id'] = $cot['CD']['id'];
                        $this->Cotizacionesdetalle->eliminarDetalleCotizacion($arrCD);
                    }

                    //se elimina la cotizacion
                    $this->Cotizacione->eliminarCotizacion($arrCotiza['0']['Cotizacione']['id']);                

                    if(!empty($idOrdenT)){
                        echo json_encode(array('resp' => '1','idOrden' => $idOrdenT));      
                    }else{
                        echo json_encode(array('resp' => '0'));
                    }                
                }else{
                    echo json_encode(array('resp' => '0'));
                }                
            }else{
                echo json_encode(array('resp' => '2', 'msg' => $mensaje));
            }

        }
        
        /**
         * Se valida la disponibilidad de los suministros en stock
         * @param type $arrCotiza
         * @return string
         */
        public function validarCantidadStockProductos($arrCotiza){
            $this->loadModel('Cargueinventario');
            $mensaje = "";
            foreach ($arrCotiza as $pc){

                //se obtiene la informacion del inventario
                $arrCInv = $this->Cargueinventario->obtenerInventarioId($pc['CD']['cargueinventario_id']);
                
                //se valida la cantidad en stock frente a la solicitada
                if($arrCInv['Cargueinventario']['existenciaactual'] < $pc['CD']['cantidad']){
                    $mensaje .= "- No hay las suficientes unidades del producto solicitado: " . $arrCInv['Producto']['descripcion'] . ". ";
                    $mensaje .= "Cantidad solicitada: " . $pc['CD']['cantidad'] . ". Cantidad en stock: " . $arrCInv['Cargueinventario']['existenciaactual'] . "<br>";
                }
            }
            return $mensaje;            
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
         * Se actualiza el responsable de la cotización
         */
        public function ajaxCambiarResponsableCotiza(){
            $this->autoRender = false;
            $this->loadModel('Cotizacione');                

            $posData = $this->request->data;
            $data = array();            
            
            $data['id'] = $posData['cotizacionId'];
            $data['usuario_id'] = $posData['vendedor'];
            
            $resp = $this->Cotizacione->crearActualizarCotizacion($data);
            
            if($resp == '0'){
                echo json_encode(array('resp' => '0')); 
            }else{
                echo json_encode(array('resp' => '1')); 
            }
                        
        }
        
        public function ajaxGuardarCotizacion(){
            $this->autoRender = false;
            $data = [];
            
            $data['id'] = $this->request->data['cotizacion']; 
            $data['observacion'] = $this->request->data['observacion'];
            
            $resp = $this->Cotizacione->crearActualizarCotizacion($data);
                            
            if($resp == '0'){
                echo json_encode(array('resp' => '0')); 
            }else{
                echo json_encode(array('resp' => '1')); 
            }            
        }
        
        public function ajaxGuardarVehiculo(){
            $this->autoRender = false;
            $data = [];
            
            $data['id'] = $this->request->data['cotizacionId'];
            $data['vehiculo_id'] = $this->request->data['vehiculoId'];
            
            $resp = $this->Cotizacione->crearActualizarCotizacion($data);
                            
            if($resp == '0'){
                echo json_encode(array('resp' => '0')); 
            }else{
                echo json_encode(array('resp' => '1')); 
            }                  
        }

}