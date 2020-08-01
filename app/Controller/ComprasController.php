<?php
App::uses('AppController', 'Controller');

class ComprasController extends AppController {


	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
    public function index() {
        $paginate = array();
        $this->loadModel('Proveedore');
        $this->loadModel('Usuario');
        
        $proveedorId = "";
        $usuarioId = "";
        $numFactura = "";
        $FDesde = "";
        $FHasta = "";
        //filtro por proveedor
        if(!empty($this->passedArgs['proveedore_id'])){
            $paginate['Compra.proveedore_id'] = $this->passedArgs['proveedore_id'];
            $proveedorId = $this->passedArgs['proveedore_id'];
        }          
        
        //filtro por usuario
        if(!empty($this->passedArgs['usuario_id'])){
            $paginate['Compra.usuario_id'] = $this->passedArgs['usuario_id'];
            $usuarioId = $this->passedArgs['usuario_id'];
        }          
        
        //filtro por número de factura
        if(!empty($this->passedArgs['numFactura'])){
            $paginate['Compra.numerofactura'] = $this->passedArgs['numFactura'];
            $numFactura = $this->passedArgs['numFactura'];
        }          
        
        //filtro por fecha desde
        if(!empty($this->passedArgs['fechaDesde']) && empty($this->passedArgs['fechaHasta'])){
            $dateAct = date('Y-m-d');
            $paginate['Compra.fecha BETWEEN ? AND ?'] = array($this->passedArgs['fechaDesde'] . ' 00:00:00', $dateAct . ' 23:59:59');
            $FDesde = $this->passedArgs['fechaDesde'];
            $FHasta = $dateAct;
        }           
        
        //filtro por fecha hasta
        if(!empty($this->passedArgs['fechaHasta']) && empty($this->passedArgs['fechaDesde'])){
            $date = date('Y-01-01');
            $paginate['Compra.fecha BETWEEN ? AND ?'] = array($date . ' 00:00:00', $this->passedArgs['fechaHasta'] . ' 23:59:59');
            $FDesde = $date;
            $FHasta = $this->passedArgs['fechaHasta'];            
        }
        
        //filtro por ambas fechas
        if(!empty($this->passedArgs['fechaHasta']) && !empty($this->passedArgs['fechaDesde'])){
            $paginate['Compra.fecha BETWEEN ? AND ?'] = array($this->passedArgs['fechaDesde'] . ' 00:00:00', $this->passedArgs['fechaHasta'] . ' 23:59:59'); 
            $FDesde = $this->passedArgs['fechaDesde'];
            $FHasta = $this->passedArgs['fechaHasta'];      
        }        
        
        $empresaId = $this->Auth->user('empresa_id');
        
        //se obtiene el listado de proveedores
        $listProv = $this->Proveedore->obtenerProveedoresEmpresa($empresaId);
        
        //se obtiene el listado de usuarios
        $listUsr = $this->Usuario->obtenerUsuarioEmpresa($empresaId);

        $this->Compra->recursive = 0;
        $this->set('compras', $this->Paginator->paginate('Compra', $paginate));
        $this->set(compact('listProv', 'listUsr', 'proveedorId', 'usuarioId', 'numFactura', 'FDesde', 'FHasta'));
    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function view($id = null) {
        $this->loadModel('CategoriacomprasCompra');
        $this->loadModel('Categoriacompra');
        $this->loadModel('Reteicaretefuente');
        $this->loadModel('Usuario');
        $this->loadModel('Proveedore');
        if (!$this->Compra->exists($id)) {
                throw new NotFoundException(__('La compra no existe.'));
        }
        
        $empresaId = $this->Auth->user('empresa_id');
        
        //se obtiene la informacion de la compra
        $infoCompra = $this->Compra->obtenerInfoCompra($id);

        //obtiene la informacion de las categorias de las compras
        $catComprasComp = $this->CategoriacomprasCompra->obtenerCatCompraPorCompraId($id);

        //se obtiene las categorias de compras
        $listCat = $this->Categoriacompra->obtenerlistaCategoriasCompras($empresaId);
        
        //se obtiene el listado de proveedores
        $infoProv = $this->Proveedore->obtenerProveedorPorId($infoCompra['0']['Compra']['proveedore_id']);

        //se obtiene el listado de usuarios
        $infoUsr = $this->Usuario->obtenerUsuarioPorId($infoCompra['0']['Compra']['usuario_id']);
        
        //se obtiene la lista de reteica retefuente
        $listRicaRfte = $this->Reteicaretefuente->obtenerListaReteicaRetefuente($empresaId);
        
        $this->set(compact('infoCompra', 'catComprasComp', 'listCat', 'infoProv', 'infoUsr', 'listRicaRfte'));
        
    }

/**
 * add method
 *
 * @return void
 */
    public function add() {
        $this->loadModel('Proveedore');
        $this->loadModel('Categoriacompra');
        $this->loadModel('Impuesto');
        $this->loadModel('Tipopagopago');
        $this->loadModel('Cuenta');

        $empresaId = $this->Auth->user('empresa_id');
        $userId = $this->Auth->user('id');

        //se obtiene el listado de proveedores
        $listProv = $this->Proveedore->obtenerProveedoresEmpresa($empresaId);

        //se obtienen las categorias de compras
        $listCatCom = $this->Categoriacompra->obtenerlistaCategoriasCompras($empresaId);

        //se obtiene el impuesto configurdo como iva
        $iva = "IVA";
        $imp = $this->Impuesto->obtenerImpuestoPorNombre($iva, $empresaId);

        //metodos de pago
        $tipoPago = $this->Tipopagopago->obtenerListaTiposPagos();

        //cuentas para debitar el dinero
        $cuentas = $this->Cuenta->obtenerCuentasEmpresa($empresaId);

        $this->set(compact('empresaId', 'userId', 'listProv', 'listCatCom', 'imp', 'tipoPago', 'cuentas'));
    }
 
/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function delete($id = null) {
        $this->loadModel('CategoriacomprasCompra');
        $this->Compra->id = $id;
        if (!$this->Compra->exists()) {
                throw new NotFoundException(__('La compra no existe.'));
        }
        
        if ($this->Compra->delete()) {
            
            $resp = $this->CategoriacomprasCompra->eliminarDetalleCategoriaCompra($id);
            
            if($resp){
                
                $this->Session->setFlash(__('La compra ha sido eliminada.'));
            }else{
                $this->Session->setFlas(__('Los detalles de la compra no fueron eliminados.'));
            }                
        } else {
                $this->Session->setFlash(__('La compra no pudo ser eliminada. Por favor, inténtelo de nuevo.'));
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
     * obtiene la lista de la retefuente
     */
    public function obtenerlistaretefuente(){            

        $this->loadModel('Reteicaretefuente');
        $empresaId = $this->Auth->user('empresa_id');

        //se obtiene la informacion de la retefuente
        $datosRetefuente = $this->Reteicaretefuente->obtenerInfoRetefuente($empresaId);

        $this->set(compact('datosRetefuente'));            
    }

    /**
     * obtiene la lista del reteica
     */
    public function obtenerlistareteica(){
        $this->loadModel('Reteicaretefuente');
        $empresaId = $this->Auth->user('empresa_id');

        //se obtiene la información del reteica
        $datosReteica = $this->Reteicaretefuente->obtenerInfoReteica($empresaId);

        $this->set(compact('datosReteica'));
    }

    public function guardarcompradetalle(){
        $this->loadModel('CategoriacomprasCompra');
        $this->loadModel('Proveedore');

        $this->autoRender = false;
        $data = $this->request->data;
        
        $compra = $data['objCompra'];
        $categorias = $data['arrCatCompra'];
        $infoPago = $data['arrPago'];

        try{
            $resp = $this->Compra->guardarCompra($compra);

            if(!empty($resp)){
                foreach($categorias as $cat){
                    $this->CategoriacomprasCompra->guardarCategoriaComprasCompra($cat, $resp);
                }
            }

            //genera las cuentas por cobrar y/o descuenta el valor de las cajas
            if(!empty($infoPago)){

                //se obtiene la informacion del proveedor
                $provInfo = $this->Proveedore->obtenerProveedorPorId($compra['proveedorId']);                

                foreach($infoPago as $ip) {
                    if($ip['cuenta'] != 'crd'){
                        $this->realizarPagoEfectivo($ip['cuenta'], $ip['valor'], $provInfo['Proveedore']['nombre']);
                    }else{
                        $this->realizarPagoCredito($provInfo, $compra['numFactura'], $compra['usuarioId'], $ip['valor']);
                    }
                }
                
            }
         
        } catch (Exception $ex) {

            echo json_encode(array('resp' => 'false', 'msg' => $ex->getMessage())); 
        }
        
        echo json_encode(array('resp' => 'true')); 
    }

    public function sumarDiasFecha($fecha,$dias){
        $diasSum = !empty($dias) ? $dias : 30;
        $fechaNew = new DateTime($fecha);
        $fechaNew->add(new DateInterval('P' . $diasSum . 'D'));
        $fechaFin = $fechaNew->format('Y-m-d');
        return $fechaFin;          
    }  
    
    public function realizarPagoEfectivo($cuentaId, $valor, $proveedor){
        $this->loadModel('Cuenta');
        $this->loadModel('Gasto');
        $this->loadModel('Itemsgasto');

        $empresaId = $this->Auth->user('empresa_id');

        //obtiene la informacion de la cuenta
        $cuentaInfo = $this->Cuenta->obtenerDatosCuentaId($cuentaId);
        $nuevoSaldo = $cuentaInfo['Cuenta']['saldo'] - $valor;

        $resp = $this->Cuenta->actualizarSaldoCuenta($cuentaId,$nuevoSaldo);

        // si se descuenta el saldo, se crea el gasto de la cuenta paga
        if($resp){

            //se obtiene el item del gasto pago proveedores 
            $itemGasto = $this->Itemsgasto->obtenerItemGastoProv($empresaId, 'PAGO PROVEEDOR');

            $data['descripcion'] = "Pago factura a proveedor " . $proveedor;
            $data['usuario_id'] = $this->Auth->user('id');
            $data['empresa_id'] = $empresaId;
            $data['fechagasto'] = date('Y-m-d H:i:s');
            $data['valor'] = $valor;
            $data['cuenta_id'] = $cuentaId;
            $data['traslado'] = '0';
            $data['itemsgasto_id'] = !empty($itemGasto['Itemsgasto']['id']) ? $itemGasto['Itemsgasto']['id'] : null;
            $data['tipoempresa'] = 'P';
            $data['empresaasg_id'] = $empresaId;

            $this->Gasto->actualizarGasto($data);            
        }                
    }

    public function realizarPagoCredito($provInfo, $numFactura, $usuarioId, $valor){    

        $this->loadModel('Cuentaspendiente');

        $empresaId = $this->Auth->user('empresa_id');

        //se obtiene la fecha limite de pago para la cuenta por pagar
        $fechaLim = $this->sumarDiasFecha(date('Y-m-d'),$provInfo['Proveedore']['diascredito']); 
        
        $resp = $this->Cuentaspendiente->guardarCuentasPendientes(  null, null, null, null, 
                                                            null, $provInfo['Proveedore']['id'], $numFactura, $usuarioId,
                                                            $empresaId, $valor, $fechaLim );            
    }
        
}
