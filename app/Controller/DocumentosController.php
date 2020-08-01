<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
App::import('Vendor', 'FPDI', array('file' => 'fpdi/fpdi.php'));
/**
 * Documentos Controller
 *
 * @property Documento $Documento
 * @property PaginatorComponent $Paginator
 */
class DocumentosController extends AppController {

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
	
            $this->loadModel('Tiposdocumento');
            $paginate = array();
            if(isset($this->passedArgs['codigo']) && $this->passedArgs['codigo'] != ""){
                $paginate['Documento.codigo LIKE'] = '%' . strtolower($this->passedArgs['codigo']) . '%';
            }
            
            if(isset($this->passedArgs['tipodocumento']) && $this->passedArgs['tipodocumento'] != ""){
                $paginate['Documento.tiposdocumento_id'] = $this->passedArgs['tipodocumento'];
            }
            
            $empresaId = $this->Auth->user('empresa_id');            
            //se obtienen los tipos de documentos de la aplicacion
            $tipoDocs = $this->Tiposdocumento->obtenerListaTiposDocumentos();
            $paginate['Documento.empresa_id'] = $empresaId;
            $this->Documento->recursive = 0;
            $this->set('documentos', $this->Paginator->paginate('Documento', $paginate));
            $this->set(compact('tipoDocs'));
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
            	
            $this->loadModel('Detalledocumento');
            $this->loadModel('Configuraciondato');
            $this->loadModel('Deposito');
            $this->loadModel('Anotacione');
            if (!$this->Documento->exists($id)) {
                    throw new NotFoundException(__('El documento no existe.'));
            }
            $options = array('conditions' => array('Documento.' . $this->Documento->primaryKey => $id));
            $this->set('documento', $this->Documento->find('first', $options));
            
            /*Se obtiene la url de las imagenes de las empresas*/
            $strDato = "urlImgEmpresa";
            $urlImg = $this->Configuraciondato->obtenerValorDatoConfig($strDato);
            
            
            /*Se obtiene el detalle del documento*/
            $detalleDoc = $this->Detalledocumento->obtenerDetalleDocumento($id);
            $cantProd = 0;
            $total = 0;
            for ($i = 0; $i < count($detalleDoc); $i++){
                if($detalleDoc[$i]['Detalledocumento']['depositoorigen_id'] != ""){
                    $detalleDoc[$i]['Detalledocumento']['depositoorigen_id'] = $this->Deposito->obtenerDepositoPorId($detalleDoc[$i]['Detalledocumento']['depositoorigen_id']);
                }
                if($detalleDoc[$i]['Detalledocumento']['depositodestino_id'] != ""){
                    $detalleDoc[$i]['Detalledocumento']['depositodestino_id'] = $this->Deposito->obtenerDepositoPorId($detalleDoc[$i]['Detalledocumento']['depositodestino_id']);
                } 
                
                $cantProd = $cantProd +  $detalleDoc[$i]['Detalledocumento']['cantidad'];
                $total += ($detalleDoc[$i]['Detalledocumento']['cantidad'] * $detalleDoc[$i]['Detalledocumento']['costoproducto']); 
            }
            
            /*Se obtienen las nostas del documento*/
            $arrAnotaciones = $this->Anotacione->obtenerAnotacionesPorDocumentoId($id);

            $totalProductos = $i;
            
            $this->set(compact('detalleDoc', 'urlImg', 'totalProductos', 'cantProd', 'total', 'arrAnotaciones'));
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
			$this->Documento->create();
			if ($this->Documento->save($this->request->data)) {
				$this->Session->setFlash(__('El documento ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El documento no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		}
		$tiposdocumentos = $this->Documento->Tiposdocumento->find('list');
		$empresas = $this->Documento->Empresa->find('list');
		$usuarios = $this->Documento->Usuario->find('list');
		$this->set(compact('tiposdocumentos', 'empresas', 'usuarios'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
            echo "Holaaa"; die();
            /*se reagistra la actividad del uso de la aplicacion*/
            $usuariosController = new UsuariosController();
            $usuarioAct = $this->Auth->user('id');
            $usuariosController->registraractividad($usuarioAct);
            	
		if (!$this->Documento->exists($id)) {
			throw new NotFoundException(__('Invalid documento'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Documento->save($this->request->data)) {
				$this->Session->setFlash(__('The documento has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The documento could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Documento.' . $this->Documento->primaryKey => $id));
			$this->request->data = $this->Documento->find('first', $options);
		}
                echo "Hola"; die();
		$tiposdocumentos = $this->Documento->Tiposdocumento->find('list');
		$empresas = $this->Documento->Empresa->find('list');
		$usuarios = $this->Documento->Usuario->find('list');
		$this->set(compact('tiposdocumentos', 'empresas', 'usuarios'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Documento->id = $id;
		if (!$this->Documento->exists()) {
			throw new NotFoundException(__('Invalid documento'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Documento->delete()) {
			$this->Session->setFlash(__('The documento has been deleted.'));
		} else {
			$this->Session->setFlash(__('The documento could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
        
        public function guardarcargueinventarioajax(){
            $this->loadModel('Precargueinventario');
            $this->loadModel('Usuario');
            $this->loadModel('Detalledocumento');
            $this->loadModel('Auditoria');
            $this->loadModel('Anotacione');
            $this->loadModel('Cargueinventario');
            $this->loadModel('Cuentaspendiente');
            $this->loadModel('Proveedore');
            $this->autoRender = false;                 
            
            $posData = $this->request->data;
            
            $nota = $posData['nota'];
            $usuarioId = $posData['usuarioId'];
            
            /*se obtiene la información del precargue de inventario*/
            $infoPrecargue = $this->Precargueinventario->obtenerPrecargueUsuario($usuarioId);

            /*se crea el documento*/
            $tipoDocumentoId = '1';
            
            /*se guarda el documento y se obtiene el id del mismo*/
            $documentoId = $this->Documento->guardarDocumento($tipoDocumentoId,$infoPrecargue['0']['Usuario']['empresa_id'],$usuarioId);
            
            /*se actualiza el codigo del documento ya que en mysql no se admite mas de un autoincrement*/
            $this->Documento->actualizarCodigoDocumento($documentoId);            
            
            /*se guarda la informacion del detalle del documento y del inventario*/
            $resp = true;
            foreach ($infoPrecargue as $infP){
                if(!$this->Detalledocumento->guardarDetalleDocumento($infP['Producto']['id'],$depOrg=null,$infP['Deposito']['id'],
                        $infP['Precargueinventario']['costoproducto'],$infP['Precargueinventario']['cantidad'],$infP['Precargueinventario']['preciomaximo'],
                        $infP['Precargueinventario']['preciominimo'],$infP['Precargueinventario']['precioventa'],$infP['Proveedore']['id'],
                        $infP['Tipopago']['id'],$infP['Precargueinventario']['numerofactura'],$documentoId)){  
                    $resp = false;
                }
                
                /*Se valida si el producto que se va cargar ya existe en el inventario*/
                $infoProducto = $this->Cargueinventario->obtenerProductoPorIdDeposito($infP['Producto']['id'],$infP['Deposito']['id']);
                if(count($infoProducto)>'0'){
                    /*Si existe se debe actualizar la información del inventario*/
                    $cantidadActual = $infoProducto['Cargueinventario']['existenciaactual'];
                    $costoActual = $infoProducto['Cargueinventario']['costoproducto'];

                    $cantidadACargar = $infP['Precargueinventario']['cantidad'];
                    $costoACargar = $infP['Precargueinventario']['costoproducto'];

                    $promedioPonderado = floor(($cantidadActual*$costoActual)+($cantidadACargar*$costoACargar))/($cantidadActual+$cantidadACargar);
                    $cantidadFinal = $cantidadActual+$cantidadACargar;
                    
                    $data = array();
                    $data['id'] = $infoProducto['Cargueinventario']['id'];
                    $data['deposito_id'] = $infP['Deposito']['id'];
                    $data['costoproducto'] = $promedioPonderado;
                    $data['existenciaactual'] = $cantidadFinal;
                    $data['preciomaximo'] = $infP['Precargueinventario']['preciomaximo'];
                    $data['preciominimo'] = $infP['Precargueinventario']['preciominimo'];
                    $data['precioventa'] = $infP['Precargueinventario']['precioventa'];
                    $data['usuario_id'] = $usuarioId;
                    $data['estado_id'] = $infP['Estado']['id'];
                    $data['proveedore_id'] = $infP['Proveedore']['id'];
                    $data['tipopago_id'] = $infP['Tipopago']['id'];
                    $data['numerofactura'] = $infP['Precargueinventario']['numerofactura'];
                    /*Se actualiza el registro del producto en el inventario*/
                    $this->Cargueinventario->save($data);
                    
                }else{
                    /*Si el producto no existe en el deposito, se crea*/
                    if(!$this->Cargueinventario->guardarCargueInventario($infP['Producto']['id'],$infP['Deposito']['id'],
                           $infP['Precargueinventario']['costoproducto'],$infP['Precargueinventario']['cantidad'],$infP['Precargueinventario']['preciomaximo'],
                           $infP['Precargueinventario']['preciominimo'],$infP['Precargueinventario']['precioventa'],$usuarioId,$infP['Estado']['id'],
                           $infP['Proveedore']['id'],$infP['Tipopago']['id'],$infP['Precargueinventario']['numerofactura'], $infoPrecargue['0']['Usuario']['empresa_id'])){
                        $resp = false;
                       } 
                }
                
                /*se actualizan los datos de los impuestos para el producto*/
                $this->actualizarInfoImpuestos($infP['Producto']['id'], $infP['Deposito']['id'], $infP['Precargueinventario']['id']);                
                
                /*Si el tipo de pago es crédito, se guarda en cuentas por pagar*/
                if($infP['Precargueinventario']['tipopago_id'] == '2'){
                    //se obtiene la información del proveedor
                    $infoProv = $this->Proveedore->obtenerProveedorPorId($infP['Proveedore']['id']);
                    $fechaPago = $this->sumarDiasFecha(date('Y-m-d'),$infoProv['Proveedore']['diascredito']);

                    $totalObligacion = ($infP['Precargueinventario']['costoproducto'] * $infP['Precargueinventario']['cantidad']);
                    $this->Cuentaspendiente->guardarCuentasPendientes($documentoId,$infP['Producto']['id'],$infP['Deposito']['id'],
                            $infP['Precargueinventario']['costoproducto'],$infP['Precargueinventario']['cantidad'],$infP['Proveedore']['id'],
                            $infP['Precargueinventario']['numerofactura'],$usuarioId, $infoPrecargue['0']['Usuario']['empresa_id'],$totalObligacion,
                            $fechaPago);
                }

                if($resp){
                    $this->Precargueinventario->delete(array('Precargueinventario.id' => $infP['Precargueinventario']['id']));
                }
            }  
            
            /*Se guarda la nota hecha sobre el documento*/
            $this->Anotacione->guardarNota($nota,$usuarioId,$documentoId);
            
            /*Se obtiene la info del documento cargado para el registro en auditoria*/
            $infoDoc = $this->Documento->obtenerInfoDocumentoId($documentoId);
            
            /*Se obtiene la acción de la auditoria*/
            $idAud = '1';
            $accion = $this->Auditoria->accionAuditoria($idAud);
            
            /*Se obtiene la descripcion de la auditoria*/
            $arrDescripcionAud['codigoDoc'] = $infoDoc['Documento']['codigo'];
            $descripcion = $this->Auditoria->descripcionAuditoria($idAud, $arrDescripcionAud);
            
            /*Se guarda la la auditoria*/
            $this->Auditoria->logAuditoria($usuarioId, $descripcion, $accion);
            echo json_encode(array('resp' => $resp, 'documentoId' => $documentoId));
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
        
        public function guardarnotadocumentoajax(){
            $this->loadModel('Anotacione');
            $this->autoRender = false;   
            
            $posData = $this->request->data;
            $nota = $posData['nota'];
            $usuarioId = $posData['usuarioId']; 
            $documentoId = $posData['documentoId'];
            
            $resp = $this->Anotacione->guardarNota($nota,$usuarioId,$documentoId);
            echo json_encode(array('resp' => $resp));            
        }
        
        /*
         * Se aprueba y se realiza el deescargue del inventario
         */        
        public function guardardescargueinventarioajax(){
            $this->loadModel('Usuario');
            $this->loadModel('Detalledocumento');
            $this->loadModel('Auditoria');
            $this->loadModel('Anotacione');
            $this->loadModel('Cargueinventario');
            $this->loadModel('Descargueinventario');
            $this->autoRender = false;

            $posData = $this->request->data;
            $nota = $posData['nota'];
            $usuarioId = $posData['usuarioId'];
            $empresaId = $posData['empresaId'];
            
            /*Se obtiene toda la información del descargue*/
            $arrInfoDescargue = $this->Descargueinventario->obtenerInfoDescargue($empresaId);

            /*se crea el documento con el tipo de documento descargueinventario*/
            $tipoDocumentoId = '4';
            $documentoId = $this->Documento->guardarDocumento($tipoDocumentoId,$empresaId,$usuarioId);            
                        
            /*se actualiza el codigo del documento ya que en mysql no se admite mas de un autoincrement*/
            $this->Documento->actualizarCodigoDocumento($documentoId);
                        
            /*Se recorre la información del descargue para obtener la información del stock*/
            $resp = true;
            foreach ($arrInfoDescargue as $desc){
                $productoId = $desc['Descargueinventario']['producto_id'];
                $depositoId = $desc['Descargueinventario']['deposito_id'];
                $cantidad = $desc['Descargueinventario']['cantidaddescargue'];
                
                /*se obtiene la información del stock*/
                $infoStock = $this->Cargueinventario->obtenerCargueInventarioProdDep($productoId, $depositoId);
                
                if(!$this->Detalledocumento->guardarDetalleDocumento($productoId,$depOrg=null,$depositoId,
                        $infoStock['Cargueinventario']['costoproducto'],$cantidad,$infoStock['Cargueinventario']['preciomaximo'],
                        $infoStock['Cargueinventario']['preciominimo'],$infoStock['Cargueinventario']['precioventa'],$infoStock['Cargueinventario']['proveedore_id'],
                        $infoStock['Cargueinventario']['tipopago_id'],$infoStock['Cargueinventario']['numerofactura'],$documentoId)){
                    $resp = false;
                }else{
                    /*se elimina el registro de descargue de inventario*/                    
                    $detalleId['Descargueinventario.id'] = $desc['Descargueinventario']['id'];
                    $this->Descargueinventario->delete($detalleId);
                    
                }
            }                        
            
            /*Se guarda la nota hecha sobre el documento*/
            $this->Anotacione->guardarNota($nota,$usuarioId,$documentoId);
            
            /*Se obtiene la info del documento cargado para el registro en auditoria*/
            $infoDoc = $this->Documento->obtenerInfoDocumentoId($documentoId);
            
            /*Se obtiene la acción de la auditoria*/
            $idAud = '2';
            $accion = $this->Auditoria->accionAuditoria($idAud);
            
            /*Se obtiene la descripcion de la auditoria*/
            $arrDescripcionAud['codigoDoc'] = $infoDoc['Documento']['codigo'];
            $descripcion = $this->Auditoria->descripcionAuditoria($idAud, $arrDescripcionAud);                        
            
            /*Se guarda la la auditoria*/
            $this->Auditoria->logAuditoria($usuarioId, $descripcion, $accion);
            echo json_encode(array('resp' => $resp, 'documentoId' => $documentoId));

        }
        
        public function imprimirPrueba(){
            $this->autoRender = false;
            $pdf = new FPDI();
            $pdf->AddPage();
            $pdf->Text(90, 50, 'Print me!');
            //$pdf->Output();  
            $pdf->Open();

//            $pdf->Output("C:\\tif\\prueba.pdf", 'F');

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
            
        
        public function actualizarInfoImpuestos($productoId,$depositoId,$precargueInvId){
            $this->autoRender = false;
            
            $this->loadModel('CargueinventariosImpuesto');
            $this->loadModel('PrecargueinventariosImpuesto');
            
            /*Se obtienen los impuestos precargados pra el cargue de invantarios*/
            $precargueInv = $this->PrecargueinventariosImpuesto->obtenerPrecargueInv($precargueInvId);
            
            /*Se obtiene el id del cargue del inventario*/
            $infoCargueInv = $this->Cargueinventario->obtenerProductoPorIdDeposito($productoId,$depositoId);
            
            /*Se elimina el regisro de impuestos que tenga asignado el cargue*/
            $this->CargueinventariosImpuesto->deleteAll(array('CargueinventariosImpuesto.cargueinventario_id' => $infoCargueInv['Cargueinventario']['id']), false);
            
            /*Se almacenan los impuestos para el cargue de inventario*/
            foreach ($precargueInv as $pci){
                $this->CargueinventariosImpuesto->guardarImpuestosCargueInv($infoCargueInv['Cargueinventario']['id'],$pci['PrecargueinventariosImpuesto']['impuesto_id']);
            }
            
            /*Se eliminan los registros del precargue inventario impuestos*/
            $this->PrecargueinventariosImpuesto->deleteAll(array('PrecargueinventariosImpuesto.precargueinventario_id' => $precargueInvId), false);
            
        }               
}
