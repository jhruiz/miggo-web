<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Facturas Controller
 *
 * @property Factura $Factura
 * @property PaginatorComponent $Paginator
 */
class FacturasController extends AppController
{
    public $components = array('Paginator');

    public function index()
    {

        /*se reagistra la actividad del uso de la aplicacion*/
        $usuariosController = new UsuariosController();
        $usuarioAct = $this->Auth->user('id');
        $usuariosController->registraractividad($usuarioAct);

        $rpcodigo = "";
        $rpconsecutivo = "";
        $rpvendedor = "";
        $rpfecha = "";
        $rpvencimiento = "";
        $rptipopago = "";
        $esFactura = "";
        $rpfechaFin = "";

        $this->loadModel('Tipopago');
        $this->loadModel('Usuario');
        $this->loadModel('Cuenta');

        if (isset($this->passedArgs['codigo']) && $this->passedArgs['codigo'] != "") {
            $paginate['Factura.consecutivodv LIKE'] = '%' . $this->passedArgs['codigo'] . '%';
            $rpcodigo = $this->passedArgs['codigo'];
        }

        if (isset($this->passedArgs['consecutivo']) && $this->passedArgs['consecutivo'] != "") {
            $paginate['Factura.consecutivodian LIKE'] = '%' . $this->passedArgs['consecutivo'] . '%';
            $rpconsecutivo = $this->passedArgs['consecutivo'];
        }

        if (isset($this->passedArgs['vendedor']) && $this->passedArgs['vendedor'] != "") {
            $paginate['Factura.usuario_id'] = $this->passedArgs['vendedor'];
            $rpvendedor = $this->passedArgs['vendedor'];
        }

        if (!empty($this->passedArgs['fechafactura']) && empty($this->passedArgs['fechafactura_fin'])) {
            $paginate['Factura.created BETWEEN ? AND ?'] = array($this->passedArgs['fechafactura'] . ' 00:00:00', $this->passedArgs['fechafactura'] . ' 23:59:59');
            $rpfecha = $this->passedArgs['fechafactura'] . ' 00:00:00';
            $rpfechaFin = $this->passedArgs['fechafactura'] . ' 23:59:59';
        }

        if (!empty($this->passedArgs['fechafactura_fin']) && empty($this->passedArgs['fechafactura'])) {
            $paginate['Factura.created BETWEEN ? AND ?'] = array($this->passedArgs['fechafactura_fin'] . ' 00:00:00', $this->passedArgs['fechafactura_fin'] . ' 23:59:59');
            $rpfecha = $this->passedArgs['fechafactura_fin'] . ' 00:00:00';
            $rpfechaFin = $this->passedArgs['fechafactura_fin'] . ' 23:59:59';
        }

        if (!empty($this->passedArgs['fechafactura']) && !empty($this->passedArgs['fechafactura_fin'])) {
            $paginate['Factura.created BETWEEN ? AND ?'] = array($this->passedArgs['fechafactura'] . ' 00:00:00', $this->passedArgs['fechafactura_fin'] . ' 23:59:59');
            $rpfecha = $this->passedArgs['fechafactura'] . ' 00:00:00';
            $rpfechaFin = $this->passedArgs['fechafactura_fin'] . ' 23:59:59';
        }

        if (isset($this->passedArgs['fechavence']) && $this->passedArgs['fechavence'] != "") {
            $paginate['Factura.fechavence BETWEEN ? AND ?'] = array($this->passedArgs['fechavence'] . ' 00:00:00', $this->passedArgs['fechavence'] . ' 23:59:59');
            $rpvencimiento = $this->passedArgs['fechavence'];
        }

        if (isset($this->passedArgs['tipopago']) && $this->passedArgs['tipopago'] != "") {
            $paginate['FCV.tipopago_id'] = $this->passedArgs['tipopago'];
            $rptipopago = $this->passedArgs['tipopago'];
        }

        if (isset($this->passedArgs['vehiculo']) && $this->passedArgs['vehiculo']) {
            $paginate['LOWER(V.placa) LIKE'] = '%' . strtolower($this->passedArgs['vehiculo']) . '%';
            $rpplaca = $this->passedArgs['vehiculo'];
        }

        if (isset($this->passedArgs['cliente']) && $this->passedArgs['cliente']) {
            $paginate['LOWER(C.nombre) LIKE'] = '%' . strtolower($this->passedArgs['cliente']) . '%';
            $rpplaca = $this->passedArgs['vehiculo'];
        }

        if (isset($this->passedArgs['factura']) && !empty($this->passedArgs['factura'])) {
            $paginate['Factura.factura'] = true;
            $esFactura = '1';
        } else if (isset($this->passedArgs['factura']) && $this->passedArgs['factura'] == '0') {
            $paginate['Factura.factura'] = false;
            $esFactura = '0';
        }

        $empresaId = $this->Auth->user('empresa_id');
        //Se obtiene el listado de tipos de pago para el filtro
        $tipoPago = $this->Tipopago->obtenerListaTiposPagos($empresaId);

        //se obtienen los usuarios de la empresa para el filtro
        $usuario = $this->Usuario->obtenerUsuarioEmpresa($empresaId);
        $paginate['Factura.empresa_id'] = $empresaId;
        $paginate['Factura.eliminar'] = 0;

        $this->Factura->recursive = 0;
        $this->Paginator->settings = $this->Factura->obtenerIndexFacturas($paginate);
        $facturas = $this->Paginator->paginate('Factura');


        $this->set(compact('facturas'));
        $this->set(compact('tipoPago', 'usuario', 'rpcodigo', 'rpconsecutivo', 'rpvendedor', 'rpfecha', 'rpvencimiento', 'rptipopago'));
        $this->set(compact('esFactura', 'rpfechaFin'));

    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function view($id = null)
    {
        /*se reagistra la actividad del uso de la aplicacion*/
        $usuariosController = new UsuariosController();
        $usuarioAct = $this->Auth->user('id');
        $usuariosController->registraractividad($usuarioAct);

        $this->loadModel('Empresa');
        $this->loadModel('Ventarapida');
        $this->loadModel('Usuario');
        $this->loadModel('Facturasdetalle');
        $this->loadModel('Configuraciondato');
        $this->loadModel('Tipopago');
        $this->loadModel('Regimene');
        $this->loadModel('Relacionempresa');
        $this->loadModel('FacturasNotafactura');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Ordentrabajo');
        $this->loadModel('OrdentrabajosPartevehiculo');
        $this->loadModel('Estadoparte');
        $this->loadModel('OrdentrabajosSuministro');
        $this->loadModel('Vehiculo');
        $this->loadModel('Tipovehiculo');
        $this->loadModel('Marcavehiculo');
        $this->loadModel('Paise');
        $this->loadModel('Ciudade');
        $this->loadModel('FacturaCuentaValore');
        $this->loadModel('Abonofactura');
        $this->loadModel('Deposito');
        $this->loadModel('Resolucionfactura');

        /*se obtiene la información de la factura por el id*/
        $infoFact = $this->Factura->obtenerInfoFacturaPorId($id);

        //se obtiene la factura cuenta valor
        $factCV = $this->FacturaCuentaValore->obtenerPagosFactura($id);

        //se obtiene la informacion de los abonos
        $factAbonos = $this->Abonofactura->obtenerAbonosFactura($id); 
        
        // obtiene el pago a credito asociado al cliente
        $factCredit = $this->Cuentascliente->obtenerCuentaPendienteFact($id);

        $tipoDocumentoVenta = $infoFact['Factura']['factura'] == '1' ? '1' : '2';
        $infoEmpresa = $this->Empresa->obtenerEmpresaPorId($infoFact['Factura']['empresa_id']);

        /** Se obtiene la información de la resolución para la factura o documento de venta */
        $infoResolucion = $this->Resolucionfactura->obtenerResolucion($infoFact['Factura']['empresa_id'], $tipoDocumentoVenta);
        
        /*Se obtiene la información del vendedor*/
        $infoVendedor = $this->Usuario->obtenerUsuarioPorId($infoFact['Factura']['usuario_id']);

        /*Se obtiene el detalle de la factura*/
        $infoDetFact = $this->Facturasdetalle->obtenerFacturaDetalleFactId($id);

        /*Se recorre el detalle para obtener el total de la venta y el total de productos*/
        $ttalUnid = '0';

        $ttalServ = 0;
        $ttalRep = 0;

        for ($i = 0; $i < count($infoDetFact); $i++) {

            $ttalUnid += $infoDetFact[$i]['Facturasdetalle']['cantidad'];
            $infoDetFact[$i]['Facturasdetalle']['baseiva'] = 'N/A';
            $infoDetFact[$i]['Facturasdetalle']['valorconiva'] = $infoDetFact[$i]['Facturasdetalle']['costototal'];
            $infoDetFact[$i]['Facturasdetalle']['valoriva'] = 'N/A';
            $infoDetFact[$i]['Facturasdetalle']['iva'] = 'N/A';
        }     

        $impuestos = '0';

        $iva = $impuestos;

        /*se obtiene el consecutivo de la factura*/
        $consecutivoFact = !empty($infoFact['Factura']['consecutivodian']) ? $infoFact['Factura']['consecutivodian'] : $consecutivoFact = $infoFact['Factura']['consecutivodv'];

        /*se valida si fue una venta rapida*/
        $infoVentaRapida = $this->Ventarapida->obtenerInfoVentaFactId($id);

        /*Se obtiene el nombre para el total de manos de obra*/
        $strDato = "service";
        $serviceName = $this->Configuraciondato->obtenerValorDatoConfig($strDato);

        /*Se obtiene el nombre para el total de repuestos*/
        $strDato = "product";
        $productName = $this->Configuraciondato->obtenerValorDatoConfig($strDato);

        /*Se obtiene la url de las imagenes de las empresas*/
        $strDato = "urlImgEmpresa";
        $urlImg = $this->Configuraciondato->obtenerValorDatoConfig($strDato);

        //se obtiene la url de la imagend e whatsapp
        $strDato = "ulrImgWP";
        $urlImgWP = $this->Configuraciondato->obtenerValorDatoConfig($strDato);

        /*se obtiene el tipo de pago de la transacción*/
        $infoTipoPago = $this->Tipopago->obtenerTipoPagoPorId($infoFact['Factura']['tipopago_id']);

        /*se obtiene el regimen del deposito*/
        if (count($infoDetFact) > 0) {
            $regimen = $this->Regimene->obtenerRegimenPorId($infoDetFact['0']['Deposito']['regimene_id']);
        }

        /*se obtiene la cartera del cliente*/
        $totalCartera = '0';
        if (isset($infoFact['Cliente']['id']) && $infoFact['Cliente']['id'] != "") {
            $arrCartera = $this->Cuentascliente->obtenerCarteraCliente($infoFact['Cliente']['id']);
            if (count($arrCartera) > '0') {
                for ($j = 0; $j < count($arrCartera); $j++) {
                    $totalCartera += $arrCartera[$j]['Cuentascliente']['totalobligacion'];
                }
            }
        }

        /*Se obtiene la nota de la factura*/
        $notaFactura = $this->FacturasNotafactura->obtenerNotaFactura($id);

        //se obtiene la fecha actual
        $fechaActual = $this->formatoFecha($infoFact['Factura']['created']);

        //se obtiene el pais segun la ciudad
        $arrPais = $this->Paise->obtenerListaPaises();

        //se obtiene la ciudad y el pais
        $arrUbicacion = $this->Ciudade->obtenerCiudadPais($infoEmpresa['Empresa']['ciudade_id']);

        $arrInfoOrd = array();
        /*Se valida si existe una orden de trabajo relacionada*/
        if (!empty($infoFact['Factura']['ordentrabajo_id'])) {
            //se obtiene la informacion de la orden de trabajo
            $arrFilter['Ordentrabajo.id'] = $infoFact['Factura']['ordentrabajo_id'];
            $arrInfoOrd = $this->Ordentrabajo->obtenerOrdenesTrabajo($arrFilter);

            //se obtienen las partes del vehiculo relacionadas a la orden de trabajo
            $partesV = $this->OrdentrabajosPartevehiculo->obtenerEstadosPartesOrden($infoFact['Factura']['ordentrabajo_id']);

            //se obtienen los estados de las partes de los vehiculos
            $pEstados = $this->Estadoparte->obtenerListaEstados();

            //se obtienen los suministros de la orden
            $arrSums = $this->OrdentrabajosSuministro->obtenerSuministrosProductos($infoFact['Factura']['ordentrabajo_id']);

            //se obtiene la informacion del vehiculo relacionado en la orden de trabajo
            $arrVeh = $this->Vehiculo->obtenerVehiculoPorId($arrInfoOrd['0']['Ordentrabajo']['vehiculo_id']);

            //se obtiene la lista de las marcas de vehiculos
            $arrMarca = $this->Marcavehiculo->obtenerListaMarcavehiculos();

        }

        $empresaId = $this->Auth->user('empresa_id');

        //si es una remision, se obtiene la información de una empresa relacionada
        $infoRemision = [];
        if (empty($infoFact['Factura']['factura'])) {
            $infoRemision = $this->Relacionempresa->obtenerDatosEmpresaRemision($empresaId);
        }

        $this->set(compact('infoFact', 'infoEmpresa', 'infoVendedor', 'infoVentaRapida', 'infoDetFact', 'consecutivoFact', 'urlImg', 'infoTipoPago'));
        $this->set(compact('ttalUnid', 'subTtalVent', 'regimen', 'iva', 'notaFactura', 'totalCartera', 'arrInfoOrd', 'factCredit'));
        $this->set(compact('partesV', 'pEstados', 'arrSums', 'arrVeh', 'arrMarca', 'fechaActual', 'arrPais', 'arrUbicacion', 'urlImgWP'));
        $this->set(compact('infoRemision', 'infoResolucion', 'factCV', 'factAbonos', 'ttalServ', 'ttalRep', 'serviceName', 'productName', 'nombreDocumento'));
    }

/**
 * add method
 *
 * @return void
 */
    public function add()
    {
        /*se reagistra la actividad del uso de la aplicacion*/
        $usuariosController = new UsuariosController();
        $usuarioAct = $this->Auth->user('id');
        $usuariosController->registraractividad($usuarioAct);

        $this->loadModel('Tipopago');
        $this->loadModel('Notafactura');
        $this->loadModel('Usuario');
        $this->loadModel('Relacionempresa');
        $this->loadModel('Cuenta');
        $this->loadModel('Configuraciondato');
        $this->loadModel('Empresa');
        $this->loadModel('Canalventa');

        if ($this->request->is('post')) {
            $this->Factura->create();
            if ($this->Factura->save($this->request->data)) {
                $this->Session->setFlash(__('The factura has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The factura could not be saved. Please, try again.'));
            }
        }
        $empresaId = $this->Auth->user('empresa_id');
        $usuarioId = $this->Auth->user('id');
        $tipoPago = $this->Tipopago->find('list');
        $notaFactura = $this->Notafactura->obtenerNotasFacturasEmpresa($empresaId);
        $vendedor = $this->Usuario->obtenerUsuarioEmpresa($empresaId);
        $relacionEmpresa = $this->Relacionempresa->obtenerListaEmpresasRelacion($empresaId);
        $cuentas = $this->Cuenta->obtenerCuentasEmpresa($empresaId);
        $arrEmprea = $this->Empresa->obtenerEmpresaPorId($empresaId);

        //se obtiene la url de la imagend e whatsapp
        $strDatoWP = "ulrImgWP";
        $urlImgWP = $this->Configuraciondato->obtenerValorDatoConfig($strDatoWP);

        /*Se obtiene la url de las imagenes de las empresas*/
        $strDato = "urlImgEmpresa";
        $urlImg = $this->Configuraciondato->obtenerValorDatoConfig($strDato);

        // Se obtiene el listado de canal de ventas
        $canalventas = $this->Canalventa->obtenerCanalVentas($empresaId);

        $this->set(compact('empresaId', 'usuarioId', 'tipoPago', 'notaFactura', 'vendedor', 'relacionEmpresa', 'cuentas', 'urlImgWP', 'arrEmprea', 'urlImg', 'canalventas'));
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function edit($id = null)
    {
        /*se reagistra la actividad del uso de la aplicacion*/
        $usuariosController = new UsuariosController();
        $usuarioAct = $this->Auth->user('id');
        $usuariosController->registraractividad($usuarioAct);

        if (!$this->Factura->exists($id)) {
            throw new NotFoundException(__('Invalid factura'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Factura->save($this->request->data)) {
                $this->Session->setFlash(__('The factura has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The factura could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Factura.' . $this->Factura->primaryKey => $id));
            $this->request->data = $this->Factura->find('first', $options);
        }
        $clientes = $this->Factura->Cliente->find('list');
        $empresas = $this->Factura->Empresa->find('list');
        $usuarios = $this->Factura->Usuario->find('list');
        $this->set(compact('clientes', 'empresas', 'usuarios'));
    }

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function delete()
    {
        $this->autoRender = false;
        $id = $this->request->data['facturaId'];

        $this->loadModel('Documento');
        $this->loadModel('Cargueinventario');
        $this->loadModel('Facturasdetalle');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Ventarapida');
        $this->loadModel('Utilidade');
        $this->loadModel('FacturaCuentaValore');        

        $infoFact = $this->Factura->obtenerInfoFacturaPorId($id);
    
        $usuarioId = $this->Auth->user('id');

        /*se obtiene el documento de la factura*/
        $documentoId = $infoFact['Factura']['documento_id'];

        /*se obtiene la fecha actual*/
        $fechaActual = date('Y-m-d G:i:s');

        /*se actualiza el tipo de documento de factura de venta a factura cancelada*/
        $facturaCancelada = '3';
        if ($this->Documento->actualizarTipoDocumento($documentoId, $facturaCancelada, $usuarioId, $fechaActual)) {

            /*Se eliminan las cuentas pendientes que se encuentren relacionadas con la factura*/
            $this->Cuentascliente->deleteAll(array('Cuentascliente.factura_id' => $id), false);

            /*Se eliminan los registros de ventas rapidas que se encuentren relacionadas con la factura*/
            $this->Ventarapida->deleteAll(array('Ventarapida.factura_id' => $id), false);

            /**Se eliminan las utilidades generadas por la factura*/
            $this->Utilidade->deleteAll(array('Utilidade.factura_id' => $id), false);

            /**Se recorre el detalle de la factura para restaurar el inventario */
            foreach ($infoFact['Facturasdetalle'] as $detFact) {
                /*se obtiene el id del cargue inventario*/
                $infoCargueInventario = $this->Cargueinventario->obtenerCargueInventarioProdDep($detFact['producto_id'], $detFact['deposito_id']);
                
                if($infoCargueInventario['Producto']['inventario'] == '1') {
                    $existFinal = $infoCargueInventario['Cargueinventario']['existenciaactual'] + $detFact['cantidad'];
                    $this->Cargueinventario->actalizarExistenciaStock($infoCargueInventario['Cargueinventario']['id'], $existFinal);
                }

            }
            
            if ($this->Factura->actualizarEstadoFacturaEliminar($id)) {
                echo json_encode(array('resp' => true, 'msg' => ''));
            } else {
                echo json_encode(array('resp' => false, 'msg' => 'La nota crédito no pudo ser registrada. Por favor, inténtelo de nuevo.'));
            }

        } else {
            echo json_encode(array('resp' => false, 'msg' => 'La nota crédito no pudo ser registrada. Por favor, inténtelo de nuevo.'));
        }
    }

    public function pagofactura()
    {
        $this->loadModel('Tipopago');
        $this->loadModel('Empresa');
        //se obtiene el listado de tipos de pago
        $empresaId = $this->Auth->user('empresa_id');
        $listTipPag = $this->Tipopago->obtenerListaTiposPagos($empresaId);
        $empresa = $this->Empresa->obtenerEmpresaPorId($empresaId);

        $posData = $this->request->data;
        $totalFacturar = $posData['valorCompra'];
        $this->set(compact('totalFacturar', 'listTipPag', 'empresa'));
        /*se carga el ctp para agregar los valores a pagar*/
    }

    public function facturarProductos()
    {
        $this->loadModel('Prefacturasdetalle');
        $this->loadModel('Prefactura');
        $this->loadModel('Documento');
        $this->loadModel('Cargueinventario');
        $this->loadModel('DepositosUsuario');
        $this->loadModel('Deposito');
        $this->loadModel('Detalledocumento');
        $this->loadModel('Facturasdetalle');
        $this->loadModel('Empresa');
        $this->loadModel('Ventarapida');
        $this->loadModel('Utilidade');
        $this->loadModel('Cuentascliente');
        $this->loadModel('FacturasNotafactura');
        $this->loadModel('Relacionempresa');
        $this->loadModel('Abonofactura');
        $this->loadModel('FacturaCuentaValore');

        $this->autoRender = false;
        $posData = $this->request->data;
        $datFact = $posData['Factura'];
        $datCliNuevo = $posData['Nuevo'];
        $datVentaRapida = $posData['Rapida'];
        $userId = $this->Auth->user('id');

        /*Se obtienen los datos de la empresa para obtener la ciudad de la misma y asignarla al cliente*/
        $arrEmpresa = $this->Empresa->obtenerEmpresaPorId($datFact['empresa']);

        /*Se valida si es un cliente registrado o un cliente nuevo para el id del cleinte*/
        if ($datCliNuevo['nuevonombre'] != "") {
            $clienteId = $this->crearClienteNuevo($datCliNuevo, $datFact['empresa'], $datFact['usuario'], $arrEmpresa['Empresa']['ciudade_id']);
            $diasCredito = $datCliNuevo['nuevodiascredito'];
        } else if ($datVentaRapida['rapidanombre'] != "") {
            $clienteId = null;
            $diasCredito = '30';
        } else {
            $clienteId = $datFact['idcliente'];
            $diasCredito = $datFact['diascredcliente'];
        }

        $arrIdsProd = array();

        /*se obtienen los ids de los detalles de la factura para obtener los productos a facturar*/
        foreach ($posData as $k => $v) {
            if ($k != 'Factura') {
                $arrObtId = explode("_", $k);
                if ($arrObtId['0'] == 'cant') {
                    $arrIdsProd[] = $arrObtId['1'];
                } else {
                    continue;
                }
            }
        }

        $fechaActual = date('Y-m-d');
        $fechaVence = null;

        /*se crea el documento con el tipo de documento factura*/
        $tipoDocumentoId = '2';
        $documentoId = $this->Documento->guardarDocumento($tipoDocumentoId, $datFact['empresa'], $datFact['usuario']);
        /*se crea el documento para la factura que se está generando*/

        /*se actualiza el codigo del documento ya que en mysql no se admite mas de un autoincrement*/
        $this->Documento->actualizarCodigoDocumento($documentoId);

        $esFactura = isset($datFact['esfactura']) ? '1' : '0';
        $ordenTrabajo = isset($datFact['ordentrabajo']) && !empty($datFact['ordentrabajo']) ? $datFact['ordentrabajo'] : "";
        /*Se crea la factura*/
        $facturaId = $this->Factura->guardarfactura($clienteId, $datFact['empresa'], $datFact['vendedor'], $fechaVence,
            null, $datFact['pagocontado'], $datFact['pagocredito'], $documentoId, $datFact['empresaRelacionada'],
            $ordenTrabajo, $esFactura, null, $datFact['observacion'], $datFact['canalventa']);

        /*Se actualiza el codigo de la factura con el id de la factura ya que MySql solo acepta un autoincrement*/
        $this->Factura->actualizarCodigoFactura($facturaId);

        if ($clienteId == "" || $clienteId == null) {
            $this->Ventarapida->guardarInfoClienteVentaRapida($facturaId, $datVentaRapida['rapidanombre'], $datVentaRapida['rapidanit'], $datVentaRapida['rapidatelefono'], $datVentaRapida['rapidadireccion']);
        }

        /*se guarda la nota relacionada a la factura*/
        if (isset($datFact['notafactura']) && $datFact['notafactura'] != "") {
            $this->FacturasNotafactura->guardarNotaFactura($facturaId, $datFact['notafactura'], $datFact['vendedor']);
        }

        /*Se obtiene el depósito del usuario*/
        $arrDptos = $this->DepositosUsuario->obtenerDepositosUsuario($datFact['usuario']);
        $arrDatDpto = $this->Deposito->obtenerInfoDepositoPorId($arrDptos['0']['DepositosUsuario']['deposito_id']);

        //si en la factuacion se selecciona que no es factura, se asigna regimen simplificado
        if ($esFactura == '0') {
            $arrDatDpto['Deposito']['regimene_id'] = '2';
        } else {
            $arrDatDpto['Deposito']['regimen_id'] = '1';
        }

        /*Se valida si la venta se realiza con una empresa relacionada*/
        if ($datFact['empresaRelacionada'] != "" && $datFact['empresaRelacionada'] != null) {
            $consFact = null;
        } else {
            /*si el regimen es simplificado, se toma como consecutivo el autoincrement de la tabla factura*/
            /*si el regimen es comun, se obtiene el consecutivo del deposito y se actualiza*/
            /*id 1 = comun     id 2 = simplificado*/
            $consFact = $this->obtenerConsecutivoFactura($arrDatDpto, $facturaId);
        }
        /* se valida si la fecha de vencimiento es diferente de null,
        de ser asi, se confirma el pago a crédito y se registra el
        valor pendiente por cobrar al cliente */
        $depositoId = "";
        if ($fechaVence != null) {
            $depositoId = $arrDptos['0']['DepositosUsuario']['deposito_id'];
            $this->Cuentascliente->guardarCuentaPorCobrar($documentoId, $depositoId, $clienteId, $datFact['usuario'], $datFact['empresa'], $datFact['pagocredito'], $facturaId);
        }

        $prefacturaId = "";
        $ttalVenta = 0;
        for ($i = 0; $i < count($arrIdsProd); $i++) {

            /*se obtiene el detalle de la prefactura*/
            $detallePrefactura = $this->Prefacturasdetalle->obtenerPrefacturaDetalleId($arrIdsProd[$i]);

            if ($prefacturaId == "") {
                $prefacturaId = $detallePrefactura['Prefacturasdetalle']['prefactura_id'];
            }

            /*se obtienen los datos del cargueinventario*/
            $arrCrgInv = $this->Cargueinventario->obtenerInventarioId($detallePrefactura['Prefacturasdetalle']['cargueinventario_id']);

            /*Se guarda el detalle del documento*/
            $this->Detalledocumento->guardarDetalleDocumento($arrCrgInv['Cargueinventario']['producto_id'], $arrCrgInv['Cargueinventario']['deposito_id'],
                $depDestId = "", $arrCrgInv['Cargueinventario']['precioventa'], $detallePrefactura['Prefacturasdetalle']['cantidad'],
                $arrCrgInv['Cargueinventario']['preciomaximo'], $arrCrgInv['Cargueinventario']['preciominimo'],
                $detallePrefactura['Prefacturasdetalle']['costoventa'], $arrCrgInv['Cargueinventario']['proveedore_id'],
                null, $consFact['consecutivo'], $documentoId);

            $impuesto = 0;
            if ($esFactura && !empty($detallePrefactura['Prefacturasdetalle']['impuesto'])) {
                $impuesto = $detallePrefactura['Prefacturasdetalle']['impuesto'];
            }

            /*Se guarda el detalle de la factura*/
            $costoTotalProd = $detallePrefactura['Prefacturasdetalle']['cantidad'] * $detallePrefactura['Prefacturasdetalle']['costoventa'];
            if ($this->Facturasdetalle->guardarDetalleFactura($facturaId, $arrCrgInv['Cargueinventario']['deposito_id'],
                $arrCrgInv['Cargueinventario']['producto_id'], $detallePrefactura['Prefacturasdetalle']['cantidad'],
                $detallePrefactura['Prefacturasdetalle']['costoventa'], $costoTotalProd, $detallePrefactura['Prefacturasdetalle']['descuento'],
                $detallePrefactura['Prefacturasdetalle']['porcentaje'], $impuesto)) {
                /*se elimina el registro de prefacturadetalle*/
                // $this->eliminarDetallePrefactura($detallePrefactura['Prefacturasdetalle']['id']);
            }

            /*Se calculan las utilidades de la venta y se guarda la utilidad del producto*/
            $subValUnit = $detallePrefactura['Prefacturasdetalle']['costoventa'];
            $cantidadVta = $detallePrefactura['Prefacturasdetalle']['cantidad'];
            $subPrecioTtal = $subValUnit * $cantidadVta;

            //se obtiene el precio base del producto
            $valorIva = 0;
            if (!empty($impuesto)) {
                $subBaseSinIva = intval($subPrecioTtal / (($impuesto / 100) + 1));
                $valorIva = $subPrecioTtal - $subBaseSinIva;
            } else {
                $subBaseSinIva = $subPrecioTtal;
            }

            //valor del descuento
            $valorDtto = 0;
            if (!empty($detallePrefactura['Prefacturasdetalle']['porcentaje'])) {
                $valorDtto = intval($subBaseSinIva * ($detallePrefactura['Prefacturasdetalle']['porcentaje'] / 100));
            }

            //precio total sumando la base con el iva y restando el descuento
            $subPrecioFinal = $subBaseSinIva + $valorIva - $valorDtto;
            $ttalVenta += $subPrecioFinal;

            //el precio final del producto por unidad
            $finalUnitario = $subPrecioFinal / $cantidadVta;

            //se obtiene la utilidad bruta y la porcentual
            $utilidadBruta = $subPrecioFinal - ($arrCrgInv['Cargueinventario']['costoproducto'] * $cantidadVta);

            //si el precio final es 0 es porque se dio un descuento del 100%
            if ($subPrecioFinal > 0) {
                $utilidadPorcentual = $utilidadBruta / ($subPrecioFinal * 100);
            } else {
                $utilidadPorcentual = '0';
            }

            $this->Utilidade->guardarUtilidadProducto(
                $arrCrgInv['Cargueinventario']['id'], $cantidadVta, $finalUnitario, $utilidadBruta, number_format($utilidadPorcentual, 2),
                $arrEmpresa['Empresa']['id'], $facturaId, $arrCrgInv['Cargueinventario']['costoproducto']);
        }

        //actualiza el valor final de venta de la factura en pagocontado
        $this->Factura->actualizarValorVentaTotal($facturaId, $ttalVenta);

        //se actualiza la factura cuenta valor
        $this->FacturaCuentaValore->actualizarIdFacturaCuentaValor($facturaId, $userId);

        /*se valida si la prefactura contien ordenes de compra*/
        $detallePrefact = $this->Prefacturasdetalle->obtenerDetallesPrefacturaPrefactId($prefacturaId);
        
        $this->Prefactura->actualizarEstadoPrefacturaEliminar($prefacturaId);

        //se actualiza el id de la factura en los abonos correspondientes
        $this->Abonofactura->asignarFacturaAbonos($prefacturaId, $facturaId);

        $this->Cuentascliente->actualizarCuentaClienteFactura($documentoId, $clienteId, $facturaId, $prefacturaId);

        echo json_encode(array('resp' => $facturaId));
    }

    public function calcularFechaVencimientoCredito($fechaActual, $diasCredito)
    {

        $diasCredito = !empty($diasCredito) ? $diasCredito : 30;
        $fecha = new DateTime($fechaActual);
        $fechaVence = $fecha->add(new DateInterval('P' . $diasCredito . 'D'));
        return $fechaVence->format('Y-m-d');
    }

    /**
     * obtiene el consecutivo de la resolucion para el documento
     */
    public function obtenerConsecutivoFactura($arrDatDpto, $facturaId)
    {
        $this->loadModel('Resolucionfactura');
        
        $empresaId = $this->Auth->user('empresa_id');

        $arrconsecutivo = [
            'prefijo' => '',
            'consecutivo' => ''
        ];

        $tipoDocumento = $arrDatDpto['Deposito']['regimene_id'] == '1' ? '1' : '2';

        $infoResolucion = $this->Resolucionfactura->obtenerResolucion( $empresaId, $tipoDocumento );

        $arrconsecutivo = [
            'consecutivo' => !empty($infoResolucion['Resolucionfactura']['consecutivoactual']) ? $infoResolucion['Resolucionfactura']['consecutivoactual'] : 1,
            'prefijo' => !empty($infoResolucion['Resolucionfactura']['prefijo']) ? $infoResolucion['Resolucionfactura']['prefijo'] : 'FV-DV'
        ];

        /*se actualiza el consecutivo de la factura consecutivodian*/
        $this->Factura->actualizarConsecutivoDianFactura($facturaId, $arrconsecutivo, $tipoDocumento);

        /*se incrementa el numero de resolucion actual y se actualiza*/
        if (!empty($infoResolucion['Resolucionfactura']['id'])) {
            /*se actualiza el consecutivo de la factura consecutivodian*/
            $infoResolucion['Resolucionfactura'] = $this->Resolucionfactura->actualizarResolucion( $empresaId, $tipoDocumento );
        }

        return $arrconsecutivo;
    }

    public function eliminarDetallePrefactura($id)
    {
        $this->loadModel('Prefacturasdetalle');
        $detalle['Prefacturasdetalle.id'] = $id;
        if ($this->Prefacturasdetalle->delete($detalle)) {
            $resp = true;
        } else {
            $resp = false;
        }
        return $resp;
    }

    public function eliminarPrefactura($id)
    {
        $this->loadModel('Prefactura');
        $prefactura['Prefactura.id'] = $id;
        if ($this->Prefactura->delete($prefactura)) {
            $resp = true;
        } else {
            $resp = false;
        }
        return $resp;
    }

    public function facturacionclientenuevo()
    {
        $this->loadModel('Prefacturasdetalle');
        $this->loadModel('Cargueinventario');
        $this->loadModel('Prefactura');

        $this->autoRender = false;
        $posData = $this->request->data;
        $cargueinventarioId = $posData['cargueinventarioId'];
        $cantidadventa = $posData['cantidadventa'];
        $precioventa = $posData['precioventa'];
        $prefacturaId = $posData['prefacturaId'];
        $usuarioId = $posData['usuarioId'];
        $impuesto = $posData['impuesto'];
        $porcentajeDescuento = $posData['porcentajeDescuento'];
        $valorDescuento = $posData['valorDescuento'];

        /*Se valida si existe la prefactura*/
        if ($prefacturaId == "" || $prefacturaId == null) {
            /*Se guarda la prefactura y se obtiene el id para almacenar el detalle*/
            $prefacturaId = $this->Prefactura->guardarPrefactura($usuarioId, $clienteId = null);
        }

        /*se descuenta la cantidad del producto prefacturado del inventario*/
        /*se obtiene la cantidad existente en el stock*/
        $inventActual = $this->Cargueinventario->obtenerInventarioId($cargueinventarioId);
        $cantStock = $inventActual['Cargueinventario']['existenciaactual'];
        $existFinal = $cantStock - $cantidadventa;

        // valida que el producto este configurado para venta con inventario
        if($inventActual['Producto']['inventario'] == '1'){
            /*se actualiza la cantidad en stock tras la prefactura*/
            $this->Cargueinventario->actalizarExistenciaStock($cargueinventarioId, $existFinal);
        }
        
        $detalleId = $this->Prefacturasdetalle->guardarDetallePrefactura($cantidadventa, $precioventa, $cargueinventarioId, $prefacturaId,
            $valorDescuento, $porcentajeDescuento, $impuesto);
        if ($detalleId != '0' && $detalleId != "") {
            echo json_encode(array('resp' => $detalleId, 'prefact' => $prefacturaId));
        } else {
            echo json_encode(array('resp' => $detalleId, 'prefact' => $prefacturaId));
        }
    }

    public function crearClienteNuevo($datCliNuevo, $empresaId, $usuarioId, $ciudadId)
    {
        $this->loadModel('Cliente');
        $clienteId = $this->Cliente->guardarClienteNuevo($datCliNuevo['nuevonit'], $datCliNuevo['nuevonombre'], $datCliNuevo['nuevodireccion'],
            $datCliNuevo['nuevotelefono'], $ciudadId, $datCliNuevo['nuevopaginaweb'], $datCliNuevo['nuevoemail'], $datCliNuevo['nuevocelular'],
            $datCliNuevo['nuevodiascredito'], $datCliNuevo['nuevolimitecredito'], $datCliNuevo['nuevocumpleanios'], $usuarioId, $estId = '1', $empresaId);
        return $clienteId;
    }

    public function cierrediario()
    {
        $this->loadModel('Ventarapida');
        $this->loadModel('Cargueinventario');
        $this->loadModel('Gasto');
        $this->loadModel('Cuenta');
        $this->loadModel('Abonofactura');
        $this->loadModel('Prefactura');
        $this->loadModel('Tipopago');
        $this->loadModel('Cierrecaja');
        $this->loadModel('Observacionescierre');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Cuentaspendiente');

        $flgCierre = true;
        $cuenta = "";
        $rpcuenta = "";
        if (isset($this->passedArgs['Cierrediario.Cuenta']) && !empty($this->passedArgs['Cierrediario.Cuenta'])) {
            $cuenta = $this->passedArgs['Cierrediario.Cuenta'];
            $rpcuenta = $cuenta;
            $flgCierre = false;
        }

        if (isset($this->passedArgs['Cierrediario.Fecha']) && !empty($this->passedArgs['Cierrediario.Fecha'])) {
            $fechaCierre = $this->passedArgs['Cierrediario.Fecha'];
            $rpfechacierre = $this->passedArgs['Cierrediario.Fecha'];
            $flgCierre = false;
        } else {
            $fechaCierre = date('Y-m-d');
            $rpfechacierre = date('Y-m-d');
        }

        $empresaId = $this->Auth->user('empresa_id');

        /*se obtienen las facturas generadas durante la fecha actual o la seleccionada*/
        $detFacts = $this->Factura->obtenerFacturasTipoPagos($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $cuenta);

        $ventasFactura = [];
        $estadoCuentas = [];
        $cantFacturas = 0;
        $cantNotasCred = 0;
        if (!empty($detFacts)) {

            foreach ($detFacts as $df) {

                if (!isset($estadoCuentas[$df['FCV']['cuenta_id']])) {
                    $estadoCuentas[$df['FCV']['cuenta_id']]['ing_ventas'] = 0;
                }

                //se obtiene el detalle de la venta de cada factura por cada tipo de pago
                $ventasFactura[] = [
                    'fact_codigo' => $df['Factura']['consecutivodv'],
                    'consecutivodian' => !empty($df['Factura']['consecutivodian']) ? $df['Factura']['consecutivodian'] : "",
                    'cliente_nit' => !empty($df['Cliente']['nit']) ? $df['Cliente']['nit'] : "",
                    'cliente_nombre' => !empty($df['Cliente']['nombre']) ? $df['Cliente']['nombre'] : "",
                    'usuario_nombre' => !empty($df['Usuario']['nombre']) ? $df['Usuario']['nombre'] : "",
                    'usuario_identificacion' => !empty($df['Usuario']['identificacion']) ? $df['Usuario']['identificacion'] : "",
                    'fcv_cuenta' => $df['FCV']['cuenta_id'],
                    'fcv_tipopago' => $df['FCV']['tipopago_id'],
                    'fcv_valor' => $df['FCV']['valor'],
                    'fact_eliminada' => $df['Factura']['eliminar'],
                    'created' => $df['Factura']['created']
                ];

                if( $df['Factura']['eliminar'] == '1' ) {
                    $cantNotasCred ++;
                } else {
                    $cantFacturas ++;
                }

                //se obtiene el ingreso por venta en cada cuenta
                $estadoCuentas[$df['FCV']['cuenta_id']]['ing_ventas'] += $df['FCV']['valor'];
            }
        }

        //se obtiene el registro de gastos de una cuenta, solo el gasto, no el gasto por traslado
        $infoGastos = $this->Gasto->obtenerGastosTrasladosEmpresa($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $cuenta);
        if (!empty($infoGastos)) {
            foreach ($infoGastos as $gts) {
                if (!isset($estadoCuentas[$gts['Gasto']['cuenta_id']]['gastos'])) {
                    $estadoCuentas[$gts['Gasto']['cuenta_id']]['gastos'] = 0;
                }
                $estadoCuentas[$gts['Gasto']['cuenta_id']]['gastos'] += $gts['Gasto']['valor'];
            }
        }

        //se obtiene el registro de traslados
        $infoTraslados = $this->Gasto->obtenerIngresosTrasladosEmpresa($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $cuenta);
        //se obtiene la sumatoria de ingresos por traslado por cuenta
        foreach ($infoTraslados as $it) {

            //ingresos por traslados
            if (!isset($estadoCuentas[$it['Gasto']['cuentadestino']]['ing_traslados'])) {
                $estadoCuentas[$it['Gasto']['cuentadestino']]['ing_traslados'] = 0;
            }

            //gastos por traslados
            if (!isset($estadoCuentas[$it['Gasto']['cuenta_id']]['gasto_traslados'])) {
                $estadoCuentas[$it['Gasto']['cuenta_id']]['gasto_traslados'] = 0;
            }

            $estadoCuentas[$it['Gasto']['cuentadestino']]['ing_traslados'] += $it['Gasto']['valor'];
            $estadoCuentas[$it['Gasto']['cuenta_id']]['gasto_traslados'] += $it['Gasto']['valor'];
        }

        //se obtiene el listado de cuentas de la empresa
        $listCuenta = $this->Cuenta->obtenerCuentasEmpresa($empresaId);

        //se obtiene la lista de tipos de pagos
        $listTipoPago = $this->Tipopago->obtenerListaTiposPagos($empresaId);

        //se obtienen los abonos de la fecha indicada para las prefacturas
        $arrAbonos = [];
        $abonosPrefactura = $this->Abonofactura->obtenerAbonosCierreDiario($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, "1");
        if (!empty($abonosPrefactura)) {
            foreach ($abonosPrefactura as $abnpf) {

                if (!isset($estadoCuentas[$abnpf['Abonofactura']['cuenta_id']]['abono_prefact'])) {
                    $estadoCuentas[$abnpf['Abonofactura']['cuenta_id']]['abono_prefact'] = 0;
                }

                $arrAbonos[] = [
                    'fecha' => $abnpf['Abonofactura']['created'],
                    'valor' => $abnpf['Abonofactura']['valor'],
                    'cliente' => $abnpf['CL']['nombre'],
                    'cuenta' => $abnpf['C']['descripcion'],
                    'prefactura' => $abnpf['Abonofactura']['prefactura_id'],
                    'factura' => $abnpf['Abonofactura']['factura_id'],
                    'usuario' => $abnpf['U']['nombre']
                ];

                $estadoCuentas[$abnpf['Abonofactura']['cuenta_id']]['abono_prefact'] += $abnpf['Abonofactura']['valor'];

            }
        }

        $abonosFactura = $this->Abonofactura->obtenerAbonosCierreDiario($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, "0");

        if (!empty($abonosFactura)) {
            foreach ($abonosFactura as $abnf) {

                if (!isset($estadoCuentas[$abnf['Abonofactura']['cuenta_id']]['abono_fact'])) {
                    $estadoCuentas[$abnf['Abonofactura']['cuenta_id']]['abono_fact'] = 0;
                }

                $arrAbonos[] = [
                    'fecha' => $abnf['Abonofactura']['created'],
                    'valor' => $abnf['Abonofactura']['valor'],
                    'cliente' => $abnf['CL']['nombre'],
                    'cuenta' => $abnf['C']['descripcion'],
                    'prefactura' => $abnf['Abonofactura']['prefactura_id'],
                    'factura' => $abnf['Abonofactura']['factura_id'],
                    'usuario' => $abnpf['U']['nombre']
                ];

                $estadoCuentas[$abnf['Abonofactura']['cuenta_id']]['abono_fact'] += $abnf['Abonofactura']['valor'];

            }
        }

        //se obtiene el estado actual de las cuentas
        $ctasEstAct = $this->Cuenta->obtenerInfoCuentas($empresaId);
        if (!empty($ctasEstAct)) {
            foreach ($ctasEstAct as $eaCtas) {
                if (!isset($estadoCuentas[$eaCtas['Cuenta']['id']]['estado_actual'])) {
                    $estadoCuentas[$eaCtas['Cuenta']['id']]['estado_actual'] = 0;
                }

                $estadoCuentas[$eaCtas['Cuenta']['id']]['estado_actual'] += $eaCtas['Cuenta']['saldo'];
            }
        }

        $cierreCaja = [];
        $anotDay = false;
        if ($fechaCierre != date('Y-m-d')) {
            $cierreDiario = $this->Cierrecaja->obtenerCierreCajas($empresaId, $fechaCierre, $cuenta);
            $anotDay = true;
        }

        //se obtienen las observaciones del cierre
        $arrObsCierre = $this->Observacionescierre->obtenerObsFecha($fechaCierre, $empresaId);
        $obsCierre = !empty($arrObsCierre['0']) ? $arrObsCierre['0']['Observacionescierre']['descripcion'] : "";

        //se obtienen las cuentas pendientes de los clientes registradas en el dia consultado
        $ctasClientes = $this->Cuentascliente->obtenerVentasCredito($empresaId, $fechaCierre);

        $ctasPendientes = $this->Cuentaspendiente->obtenerComprasCredito($empresaId, $fechaCierre);

        $this->set(compact('ventasFactura', 'listCuenta', 'fechaCierre', 'rpfechacierre', 'infoTraslados'));
        $this->set(compact('infoGastos', 'arrAbonos', 'flgCierre', 'rpcuenta', 'estadoCuentas', 'listTipoPago'));
        $this->set(compact('cierreDiario', 'anotDay', 'obsCierre', 'ctasClientes', 'ctasPendientes', 'cantFacturas', 'cantNotasCred'));
    }

    public function buscarcierre()
    {
        $url = array();
        $url['action'] = 'cierrediario';

        foreach ($this->data as $k => $v) {
            foreach ($v as $kk => $vv) {
                $url[$k . '.' . $kk] = $vv;
            }
        }
        $this->redirect($url, null, true);
    }

    public function validarImpuestoProducto($dtFt)
    {
        $this->loadModel('CargueinventariosImpuesto');
        $impuestoProducto = $this->CargueinventariosImpuesto->obtenerImpuestosProductoId($dtFt['Producto']['id'], $dtFt['Facturasdetalle']['deposito_id']);
        $arrImpuestos = array();
        for ($i = 0; $i < count($impuestoProducto); $i++) {
            $sumImpuesto = 1 + (str_replace(",", ".", $impuestoProducto[$i]['I']['valor']) / 100);
            $impuesto = str_replace(",", ".", $impuestoProducto[$i]['I']['valor']) / 100;
            $arrImpuestos[$i]['decripcion'] = $impuestoProducto[$i]['I']['descripcion'];
            $arrImpuestos[$i]['valorProd'] = $dtFt['Facturasdetalle']['costototal'] - round($dtFt['Facturasdetalle']['costototal'] * $impuesto);
            $arrImpuestos[$i]['valorImpuesto'] = round($dtFt['Facturasdetalle']['costototal'] * $impuesto);
            $arrImpuestos['decripcion'] = $impuestoProducto[$i]['I']['descripcion'];
            $arrImpuestos['valorProd'] = $dtFt['Facturasdetalle']['costototal'] - round($dtFt['Facturasdetalle']['costototal'] * $impuesto);
            $arrImpuestos['valorImpuesto'] = round($dtFt['Facturasdetalle']['costototal'] * $impuesto);
            $arrImpuestos['baseIva'] = $impuestoProducto[$i]['I']['valor'];
        }

        return $arrImpuestos;
    }

    public function search()
    {
        $url = array();
        $url['action'] = 'index';

        foreach ($this->data as $kk => $vv) {
            $url[$kk] = $vv;
        }

        // redirect the user to the url
        $this->redirect($url, null, true);
    }

    public function searchNC()
    {
        $url = array();
        $url['action'] = 'notascredito';

        foreach ($this->data as $kk => $vv) {
            $url[$kk] = $vv;
        }

        // redirect the user to the url
        $this->redirect($url, null, true);
    }

    public function validarImpuestoCierreDiario($productoId, $depositoId, $pagoContado, $pagoCredito)
    {
        $this->loadModel('CargueinventariosImpuesto');
        $impuestoProducto = $this->CargueinventariosImpuesto->obtenerImpuestosProductoId($productoId, $depositoId);
        $totalImpuestoContado = 0;
        $totalImpuestoCredito = 0;

        /*se obtiene el valor agregado sobre el pago de contado y a credito*/
        for ($i = 0; $i < count($impuestoProducto); $i++) {
            $impuesto = str_replace(",", ".", $impuestoProducto[$i]['I']['valor']) / 100;
            $totalImpuestoContado += $pagoContado * $impuesto;
            $totalImpuestoCredito += $pagoCredito * $impuesto;
        }

        $totalImpuesto['pagoCont'] = $totalImpuestoContado;
        $totalImpuesto['pagoCred'] = $totalImpuestoCredito;

        return $totalImpuesto;
    }

    /**
     * Se obtiene el reporte de mecanicos y servicios prestados con sus respectivos totales
     */
    public function repOrdenesPorMecanico()
    {
        $this->loadModel('Usuario');
        $this->loadModel('Marcavehiculo');
        $this->loadModel('Estadopagomecanico');

        $filter = array();
        //se buscan los servicios por mecanico
        $rpusuario = null;
        if (isset($this->passedArgs['usuario']) && !empty($this->passedArgs['usuario'])) {
            $filter['OT.usuario_id'] = $this->passedArgs['usuario'];
            $rpusuario = $this->passedArgs['usuario'];
        }

        $rpcreatedIni = null;
        $rpcreatedFin = null;
        if (!empty($this->passedArgs['fecha_inicio']) && empty($this->passedArgs['fecha_fin'])) {
            $filter['Factura.created >'] = $this->passedArgs['fecha_inicio'] . " 00:00:01";
            $rpcreatedIni = $this->passedArgs['fecha_inicio'] . " 00:00:01";
        }else if (empty($this->passedArgs['fecha_inicio']) && !empty($this->passedArgs['fecha_fin'])) {
            $filter['Factura.created <'] = $this->passedArgs['fecha_fin'] . " 23:59:59";
            $rpcreatedFin = $this->passedArgs['fecha_fin'] . " 00:00:01";
        }else if (!empty($this->passedArgs['fecha_inicio']) && !empty($this->passedArgs['fecha_fin'])) {
            $filter['Factura.created BETWEEN ? AND ?'] = array($this->passedArgs['fecha_inicio'] . " 00:00:01", $this->passedArgs['fecha_fin'] . " 23:59:59");
            $rpcreatedIni = $this->passedArgs['fecha_inicio'] . " 00:00:01";
            $rpcreatedFin = $this->passedArgs['fecha_fin'] . " 23:59:59";
        } else {
            $filter['Factura.created BETWEEN ? AND ?'] = array(date('Y-m-d') . " 00:00:01", date('Y-m-d') . " 23:59:59");
            $rpcreatedIni = date('Y-m-d') . " 00:00:01";
            $rpcreatedFin = date('Y-m-d') . " 23:59:59";
        }

        $empresaId = $this->Auth->user('empresa_id');

        $filter['Factura.empresa_id'] = $empresaId;
        $filter['Factura.eliminar'] = 0;
        
        //se obtiene la informacion de las facturas que tienen relacionada una orden de trabajo con productos catalogados como servicios
        $arrFactOrdenes = null;
        if (!empty($filter)) {
            $arrFactOrdenes = $this->Factura->obtenerFacturasOrdenesServicios($filter);
        }
        
        //se obtiene el listado de mecanicos
        $listMecanicos = $this->Usuario->obtenerUsuarioEmpresa($empresaId);

        //se obtiene el listado de marcas de vehiculos
        $listMarcasVeh = $this->Marcavehiculo->obtenerListaMarcavehiculos();

        $estadoPago = $this->Estadopagomecanico->obtenerListaEstadoPago();

        //totalizadores
        $totServ = 0;
        $subTot = 0;
        $total = 0;

        $this->set(compact('arrFactOrdenes', 'listMecanicos', 'totServ', 'subTot', 'total', 'estadoPago', 'listMarcasVeh', 'rpusuario', 'rpcreatedIni', 'rpcreatedFin'));
    }

    public function searchOrdMec()
    {
        $url = array();
        $url['action'] = 'repOrdenesPorMecanico';

        foreach ($this->data as $kk => $vv) {
            $url[$kk] = $vv;
        }

        // redirect the user to the url
        $this->redirect($url, null, true);
    }

    /**
     * Se guarda el dinero que se paga de contado en la cuenta seleccionada por el usuario
     * @param type $pagocontado
     * @param type $cuentaId
     */
    public function enviarDineroCuenta($pagocontado, $cuentaId, $abonos)
    {
        $this->loadModel('Cuenta');
        $arrDatCuenta = $this->Cuenta->obtenerDatosCuentaId($cuentaId);
        $saldoFinal = intval($arrDatCuenta['Cuenta']['saldo']) + (intval($pagocontado) - intval($abonos));

        $this->Cuenta->actualizarSaldoCuenta($cuentaId, $saldoFinal);
    }

    /**
     * se obtiene la fecha actual
     */
    public function obtenerFechaActual()
    {
        $mAct = date('m');

        //array meses
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        $fechaActual = $meses[$mAct - 1] . " " . date('d') . ", " . date('Y');

        return $fechaActual;
    }

    /**
     * Se obtienen los estados iniciales de una caja en el dia seleccionado
     * @param type $listCuenta
     * @param type $fechaCierre
     * @param type $empresaId
     * @return type
     */
    public function obtenerSaldosIniciales($listCuenta, $fechaCierre, $empresaId)
    {
        $this->loadModel('Gasto');
        $this->loadModel('Cuenta');
        $estCuenta = [];

        foreach ($listCuenta as $key => $val) {
            $estCuenta[$key] = [
                'nombre' => $val,
                'saldo' => !empty($estCuenta[$key]['saldo']) ? $estCuenta[$key]['saldo'] : 0,
                'gasto' => !empty($estCuenta[$key]['gasto']) ? $estCuenta[$key]['gasto'] : 0,
                'gasto_traslado' => !empty($estCuenta[$key]['gasto_traslado']) ? $estCuenta[$key]['gasto_traslado'] : 0,
                'ingreso_traslado' => !empty($estCuenta[$key]['ingreso_traslado']) ? $estCuenta[$key]['ingreso_traslado'] : 0,
                'ventas' => !empty($estCuenta[$key]['ventas']) ? $estCuenta[$key]['ventas'] : 0,
                'abonos' => !empty($estCuenta[$key]['abonos']) ? $estCuenta[$key]['abonos'] : 0,
                'saldo_inicial' => "",
            ];

            //se obtiene el saldo de la cuenta
            $cuentas = $this->Cuenta->obtenerDatosCuentaId($key);
            if (!empty($cuentas)) {
                $estCuenta[$key]['saldo'] = $cuentas['Cuenta']['saldo'];
                if (empty($estCuenta[$key]['saldo_inicial'])) {
                    $estCuenta[$key]['saldo_inicial'] = $cuentas['Cuenta']['saldo'];
                }
            }

            //se obtienen los gastos de la cuenta
            $gastos = $this->Gasto->obtenerGastosCuenta($fechaCierre, $empresaId, $key);
            if (!empty($gastos)) {
                foreach ($gastos as $gt) {
                    $estCuenta[$key]['saldo'] += $gt['Gasto']['valor'];
                    $estCuenta[$key]['gasto'] += $gt['Gasto']['valor'];
                }
            }

            //se obtienen los ingresos por traslados en la cuenta
            $traslados = $this->Gasto->obtenerIngresosTrasladosCuenta($fechaCierre, $empresaId, $key);

            if (!empty($traslados)) {
                foreach ($traslados as $tr) {
                    $estCuenta[$key]['saldo'] -= $tr['Gasto']['valor'];
                    $estCuenta[$key]['ingreso_traslado'] += $tr['Gasto']['valor'];
                }
            }

            //se obtienen las ventas registradas a una cuenta
            $facturas = $this->Factura->obtenerFacturasCierreDiario($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, $key);
            if (!empty($facturas)) {
                foreach ($facturas as $ft) {
                    $estCuenta[$key]['saldo'] -= $ft['Factura']['pagocontado'];
                    $estCuenta[$key]['ventas'] += $ft['Factura']['pagocontado'];
                }
            }

            //se obtienen los abonos realizados la cuenta que aun no han sido facturados
            $arrAbonos = $this->Abonofactura->obtenerAbonosACuenta($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $key);
            if (!empty($arrAbonos)) {
                foreach ($arrAbonos as $abn) {
                    $estCuenta[$key]['saldo'] -= $abn['Abonofactura']['valor'];
                    $estCuenta[$key]['abonos'] += $abn['Abonofactura']['valor'];
                }
            }
        }
        return $estCuenta;
    }

    /**
     * Guarda el cierre de cajas
     */
    public function ajaxcierrecajas()
    {
        $this->autoRender = false;
        $this->loadModel('Gasto');
        $this->loadModel('Cuenta');
        $this->loadModel('Abonofactura');
        $this->loadModel('Prefactura');
        $this->loadModel('Tipopago');
        $this->loadModel('Cierrecaja');

        $fechaCierre = date('Y-m-d');

        $empresaId = $this->Auth->user('empresa_id');
        $usuarioId = $this->Auth->user('id');

        /*se obtienen las facturas generadas durante la fecha actual o la seleccionada*/
        $detFacts = $this->Factura->obtenerFacturasTipoPagos($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, null);
        $estadoCuentas = [];
        if (!empty($detFacts)) {
            foreach ($detFacts as $df) {
                if (!isset($estadoCuentas[$df['FCV']['cuenta_id']])) {
                    $estadoCuentas[$df['FCV']['cuenta_id']]['ing_ventas'] = 0;
                }

                //se obtiene el ingreso por venta en cada cuenta
                $estadoCuentas[$df['FCV']['cuenta_id']]['ing_ventas'] += $df['FCV']['valor'];
            }
        }

        //se obtiene el registro de gastos de una cuenta, solo el gasto, no el gasto por traslado
        $infoGastos = $this->Gasto->obtenerGastosTrasladosEmpresa($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, null);
        if (!empty($infoGastos)) {
            foreach ($infoGastos as $gts) {
                if (!isset($estadoCuentas[$gts['Gasto']['cuenta_id']]['gastos'])) {
                    $estadoCuentas[$gts['Gasto']['cuenta_id']]['gastos'] = 0;
                }
                //se guardan los gastos por cuenta
                $estadoCuentas[$gts['Gasto']['cuenta_id']]['gastos'] += $gts['Gasto']['valor'];
            }
        }

        //se obtiene el registro de traslados
        $infoTraslados = $this->Gasto->obtenerIngresosTrasladosEmpresa($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, null);
        //se obtiene la sumatoria de ingresos por traslado por cuenta
        foreach ($infoTraslados as $it) {

            //ingresos por traslados
            if (!isset($estadoCuentas[$it['Gasto']['cuentadestino']]['ing_traslados'])) {
                $estadoCuentas[$it['Gasto']['cuentadestino']]['ing_traslados'] = 0;
            }

            //gastos por traslados
            if (!isset($estadoCuentas[$it['Gasto']['cuenta_id']]['gasto_traslados'])) {
                $estadoCuentas[$it['Gasto']['cuenta_id']]['gasto_traslados'] = 0;
            }

            $estadoCuentas[$it['Gasto']['cuentadestino']]['ing_traslados'] += $it['Gasto']['valor'];
            $estadoCuentas[$it['Gasto']['cuenta_id']]['gasto_traslados'] += $it['Gasto']['valor'];
        }

        //se obtienen los abonos de la fecha indicada para las prefacturas
        $abonosPrefactura = $this->Abonofactura->obtenerAbonosCierreDiario($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, "1");
        if (!empty($abonosPrefactura)) {
            foreach ($abonosPrefactura as $abnpf) {

                if (!isset($estadoCuentas[$abnpf['Abonofactura']['cuenta_id']]['abono_prefact'])) {
                    $estadoCuentas[$abnpf['Abonofactura']['cuenta_id']]['abono_prefact'] = 0;
                }

                //se guardan los abonos por cuenta
                $estadoCuentas[$abnpf['Abonofactura']['cuenta_id']]['abono_prefact'] += $abnpf['Abonofactura']['valor'];
            }
        }

        $abonosFactura = $this->Abonofactura->obtenerAbonosCierreDiario($fechaCierre . ' 00:00:00', $fechaCierre . ' 23:59:59', $empresaId, "0");
        if (!empty($abonosFactura)) {
            foreach ($abonosFactura as $abnf) {

                if (!isset($estadoCuentas[$abnf['Abonofactura']['cuenta_id']]['abono_prefact'])) {
                    $estadoCuentas[$abnf['Abonofactura']['cuenta_id']]['abono_prefact'] = 0;
                }

                //se obtienen los abonos por prefactura
                $estadoCuentas[$abnf['Abonofactura']['cuenta_id']]['abono_prefact'] += $abnf['Abonofactura']['valor'];

            }
        }

        //se obtiene el estado actual de las cuentas
        $ctasEstAct = $this->Cuenta->obtenerInfoCuentas($empresaId);
        if (!empty($ctasEstAct)) {
            foreach ($ctasEstAct as $eaCtas) {
                if (!isset($estadoCuentas[$eaCtas['Cuenta']['id']]['estado_actual'])) {
                    $estadoCuentas[$eaCtas['Cuenta']['id']]['estado_actual'] = 0;
                }

                //se obtienen los abonos por factura
                $estadoCuentas[$eaCtas['Cuenta']['id']]['estado_actual'] += $eaCtas['Cuenta']['saldo'];
            }
        }

        //se guarda el estado de cada cuenta
        $resp = false;
        foreach ($estadoCuentas as $key => $cta) {

            $saldoInicial = 0;
            $saldoInicial += $cta['estado_actual'];
            $saldoInicial -= isset($cta['ing_ventas']) ? $cta['ing_ventas'] : 0;
            $saldoInicial += isset($cta['gastos']) ? $cta['gastos'] : 0;
            $saldoInicial -= isset($cta['ing_traslados']) ? $cta['ing_traslados'] : 0;
            $saldoInicial += isset($cta['gasto_traslados']) ? $cta['gasto_traslados'] : 0;
            $saldoInicial -= isset($cta['abono_prefact']) ? $cta['abono_prefact'] : 0;

            $data = [];
            $data['usuario_id'] = $usuarioId;
            $data['empresa_id'] = $empresaId;
            $data['caja_id'] = $key;
            $data['saldo_inicial'] = abs($saldoInicial);
            $data['ventas'] = abs(isset($cta['ing_ventas']) ? $cta['ing_ventas'] : 0);
            $data['gastos'] = abs(isset($cta['gastos']) ? $cta['gastos'] : 0);
            $data['traslados_ing'] = abs(isset($cta['ing_traslados']) ? $cta['ing_traslados'] : 0);
            $data['traslados_gas'] = abs(isset($cta['gasto_traslados']) ? $cta['gasto_traslados'] : 0);
            $data['abonos'] = abs(isset($cta['abono_prefact']) ? $cta['abono_prefact'] : 0);
            $resp = $this->Cierrecaja->guardarCierreCaja($data);
        }

        echo json_encode(array('resp' => $resp));

    }

    /**
     * se da un formato amigable a una fecha
     * @param type $fecha
     */
    public function formatoFecha($fecha)
    {
        $fechaEntera = strtotime($fecha);
        $anio = date("Y", $fechaEntera);
        $mes = date("m", $fechaEntera);
        $dia = date("d", $fechaEntera);
        $hora = date("H", $fechaEntera);
        $min = date("i", $fechaEntera);
        $seg = date("s", $fechaEntera);

        //array meses
        $meses = array(
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre');

        $fechaActual = $meses[$mes] . " " . $dia . ", " . $anio . " " . $hora . ":" . $min . ":" . $seg;

        return $fechaActual;
    }

    public function detalleventa($id = null)
    {
        $this->loadModel('Abonofactura');
        $this->loadModel('Cuentascliente');
        $this->loadModel('FacturaCuentaValore');
        $this->loadModel('Empresa');
        $this->loadModel('Ventarapida');
        $this->loadModel('Usuario');
        $this->loadModel('Facturasdetalle');
        $this->loadModel('Configuraciondato');
        $this->loadModel('Tipopago');
        $this->loadModel('Regimene');
        $this->loadModel('Relacionempresa');
        $this->loadModel('FacturasNotafactura');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Ordentrabajo');
        $this->loadModel('OrdentrabajosPartevehiculo');
        $this->loadModel('Estadoparte');
        $this->loadModel('OrdentrabajosSuministro');
        $this->loadModel('Vehiculo');
        $this->loadModel('Tipovehiculo');
        $this->loadModel('Marcavehiculo');
        $this->loadModel('Paise');
        $this->loadModel('Ciudade');

        /*se obtiene la información de la factura por el id*/
        $infoFact = $this->Factura->obtenerInfoFacturaPorId($id);

        //se obtiene la factura cuenta valor
        $factCV = $this->FacturaCuentaValore->obtenerPagosFactura($id);

        //se obtiene la informacion de los abonos
        $factAbonos = $this->Abonofactura->obtenerAbonosFactura($id);

        //se obtiene la informacion de la empresa
        $infoEmpresa = $this->Empresa->obtenerEmpresaPorId($infoFact['Factura']['empresa_id']);

        /*Se obtiene la información del vendedor*/
        $infoVendedor = $this->Usuario->obtenerUsuarioPorId($infoFact['Factura']['usuario_id']);

        /*Se obtiene el detalle de la factura*/
        $infoDetFact = $this->Facturasdetalle->obtenerFacturaDetalleFactId($id);

        /*Se recorre el detalle para obtener el total de la venta y el total de productos*/
        foreach ($infoDetFact as $val) {
            $val['Facturasdetalle']['baseiva'] = 'N/A';
            $val['Facturasdetalle']['valorconiva'] = $val['Facturasdetalle']['costototal'];
            $val['Facturasdetalle']['valoriva'] = 'N/A';
            $val['Facturasdetalle']['iva'] = 'N/A';
        }

        /*se obtiene el consecutivo de la factura*/
        if (!empty($infoFact['Factura']['consecutivodian'])) {
            $consecutivoFact = $infoFact['Factura']['consecutivodian'];
        } else {
            $consecutivoFact = $infoFact['Factura']['consecutivodv'];
        }

        /*se valida si fue una venta rapida*/
        $infoVentaRapida = $this->Ventarapida->obtenerInfoVentaFactId($id);

        /*se obtiene la cartera del cliente*/
        $totalCartera = '0';
        if (isset($infoFact['Cliente']['id']) && $infoFact['Cliente']['id'] != "") {
            $arrCartera = $this->Cuentascliente->obtenerCarteraCliente($infoFact['Cliente']['id']);
            if (count($arrCartera) > '0') {
                for ($j = 0; $j < count($arrCartera); $j++) {
                    $totalCartera += $arrCartera[$j]['Cuentascliente']['totalobligacion'];
                }
            }
        }

        //se obtiene la fecha actual
        $fechaActual = $this->formatoFecha($infoFact['Factura']['created']);

        //se obtiene la ciudad y el pais
        $arrUbicacion = $this->Ciudade->obtenerCiudadPais($infoEmpresa['Empresa']['ciudade_id']);

        $empresaId = $this->Auth->user('empresa_id');

        //si es una remision, se obtiene la información de una empresa relacionada
        $infoRemision = [];
        if (empty($infoFact['Factura']['factura'])) {
            $infoRemision = $this->Relacionempresa->obtenerDatosEmpresaRemision($empresaId);
        }

        $arrInfoOrd = array();
        /*Se valida si existe una orden de trabajo relacionada*/
        $arrVeh = [];
        if (!empty($infoFact['Factura']['ordentrabajo_id'])) {
            //se obtiene la informacion de la orden de trabajo
            $arrFilter['Ordentrabajo.id'] = $infoFact['Factura']['ordentrabajo_id'];
            $arrInfoOrd = $this->Ordentrabajo->obtenerOrdenesTrabajo($arrFilter);

            //se obtiene la informacion del vehiculo relacionado en la orden de trabajo
            $arrVeh = $this->Vehiculo->obtenerVehiculoPorId($arrInfoOrd['0']['Ordentrabajo']['vehiculo_id']);

            //se obtiene la lista de las marcas de vehiculos
            $arrMarca = $this->Marcavehiculo->obtenerListaMarcavehiculos();

        }

        //se obtiene el listado de usuarios de la empresa
        $usrEmpresa = $this->Usuario->obtenerUsuarioEmpresa($empresaId);

        $this->set(compact('infoEmpresa', 'infoVendedor', 'totalCartera', 'arrVeh', 'arrMarca'));
        $this->set(compact('consecutivoFact', 'fechaActual', 'arrUbicacion', 'usrEmpresa'));

        $this->set(compact('infoFact', 'infoVentaRapida', 'infoDetFact'));
        $this->set(compact('infoRemision', 'infoResolucion', 'factCV', 'factAbonos'));

    }

    public function actestadopagomec()
    {
        $this->autoRender = false;

        $dateAct = date('Y-m-d H:i:s');

        $resp = $this->Factura->actualizarEstadoPagoMec($this->request->data['factura_id'], $this->request->data['estado'], $dateAct);

        echo json_encode(array('resp' => $resp, 'fecha' => $dateAct));

    }

    public function reportefacturasclientes()
    {
        $this->loadModel('Usuario');

        $filtros = [];

        $nitCliente = $this->passedArgs['nitCliente'];
        $placa = $this->passedArgs['placa'];
        $fechaInicio = $this->passedArgs['fecha_inicio'];
        $fechaFin = $this->passedArgs['fecha_fin'];
        $mecanico = $this->passedArgs['usuario'];

        if (!empty($this->passedArgs['usuario'])) {
            $filtros['O.usuario_id'] = $this->passedArgs['usuario'];
        }

        if (!empty($this->passedArgs['fecha_inicio']) && empty($this->passedArgs['fecha_fin'])) {
            $filtros['Factura.created BETWEEN ? AND ?'] = array(
                $this->passedArgs['fecha_inicio'] . ' 00:00:00',
                date('Y-m-d') . ' 23:59:59');
        }

        if (empty($this->passedArgs['fecha_inicio']) && !empty($this->passedArgs['fecha_fin'])) {
            $filtros['Factura.created BETWEEN ? AND ?'] = array(
                date('Y-01-01' . ' 00:00:01'),
                $this->passedArgs['fecha_fin'] . ' 23:59:59');
        }

        if (!empty($this->passedArgs['fecha_inicio']) && !empty($this->passedArgs['fecha_fin'])) {
            $filtros['Factura.created BETWEEN ? AND ?'] = array(
                $this->passedArgs['fecha_inicio'] . ' 00:00:01',
                $this->passedArgs['fecha_fin'] . ' 23:59:59');
        }
        if (!empty($this->passedArgs['nitCliente'])) {
            $filtros['C.nit'] = $this->passedArgs['nitCliente'];
        }
        if (!empty($this->passedArgs['placa'])) {
            $filtros['V.placa'] = $this->passedArgs['placa'];
        }
        $empresaId = $this->Auth->user('empresa_id');

        $filtros['Factura.empresa_id'] = $empresaId;

        $usuarios = $this->Usuario->obtenerUsuarioEmpresa($empresaId);
        $factClientes = $this->Factura->obtenerFacturasClientes($empresaId, $filtros);
        $totalValor = 0;
        $totalFactura = 0;
        for ($i = 0; $i < count($factClientes); $i++) {
            $totalValor += $factClientes[$i]['0']['valor'];
            $totalFactura += $factClientes[$i]['0']['conteo'];
        }
        $this->set(compact('factClientes', 'usuarios', 'totalValor', 'totalFactura', 'nitCliente', 'placa', 'fechaInicio', 'fechaFin', 'mecanico'));

    }

    public function observacionescierre()
    {
        $this->loadModel('Observacionescierre');
        $this->autoRender = false;

        $posData = $this->request->data;

        $obs = $this->Observacionescierre->obtenerObsFecha(date('Y-m-d'), $this->Auth->user('empresa_id'));

        if (!empty($obs)) {
            $data['id'] = $obs['0']['Observacionescierre']['id'];
            $data['descripcion'] = $posData['obs'];
        } else {
            $data['fecha'] = date('Y-m-d');
            $data['descripcion'] = $posData['obs'];
            $data['empresa_id'] = $this->Auth->user('empresa_id');
        }

        $resp = $this->Observacionescierre->gestionObservacion($data);

        echo json_encode(array('resp' => $resp));
    }

    /**
     * Genera una vista con el listado de tipo de pago y el valor para la nota credito
     */
    public function datosnotacredito() {
        $this->loadModel('Tipopago');
        $this->loadModel('Facturasdetalle');
        $this->loadModel('FacturaCuentaValore');
        $this->loadModel('Abonofactura');

        $facturaId = $this->request->data['facturaId'];
        $empresaId = $this->Auth->user('empresa_id');
                
        //obtiene los tipos de pago de una empresa
        $lstTipPagos = $this->Tipopago->obtenerListaTiposPagos($empresaId);
        $ttalFact = 0;

        //obtiene los pagos de la factura en factura_cuenta_valores
        $fcv = $this->FacturaCuentaValore->obtenerPagosFactura($facturaId);
        foreach($fcv as $val) {
            $ttalFact += floatval($val['FacturaCuentaValore']['valor']);
        }

        //obtiene la información de la factura para validar envío a la Dian
        $infoFact = $this->Factura->obtenerInfoFacturaPorId($facturaId);
        $syncDian = ($infoFact['Empresa']['syncdian'] == '1' && $infoFact['Factura']['factura']) ? '1' : '0';

        //obtiene los pagos de la factura en abonos
        $abonos = $this->Abonofactura->obtenerAbonosFactura($facturaId);
        foreach($abonos as $valA) {
            $ttalFact += floatval($valA['Abonofactura']['valor']);
        }

        $this->set(compact('lstTipPagos', 'facturaId', 'ttalFact', 'syncDian'));
    }

    public function searchFacCli()
    {
        $url = array();
        $url['action'] = 'reportefacturasclientes';

        foreach ($this->data as $kk => $vv) {
            $url[$kk] = $vv;
        }

        // redirect the user to the url
        $this->redirect($url, null, true);
    }

    /**
     * Función para organizar datos de sincronización de factura a la DIAN
     */
    public function obtenerFacturaParaDian() {
        $this->autoRender = false;
        $this->loadModel('Factura');
        $facturaId = $this->request->data['facturaId'];

        $prevBalance = ['previous_balance' => '0'];

        //Obtiene la información de la factura
        $factura = $this->Factura->obtenerInfoFacturaPorId($facturaId);

        //Genera y organiza la información para el apartado de resolución
        $infoRes = $this->generarInfoResolucion( $factura );
        
        //Obtiene la información del cliente de la factura
        $infoCliente = $this->generarInfoCliente( $factura );

        //Obtiene la información del tipo de pago
        $infoTipoPago = $this->generarInfoTipoPago( $factura );
        
        //Obtiene los totales generales de la factura
        $infoPagoGeneral = $this->generarInfoPagoGeneral( $factura );

        //Valida que todos los resultados sean un array procesable
        if ($this->validateArrays($infoRes, $infoCliente, $infoTipoPago, $prevBalance, $infoPagoGeneral)) {

            $jsonFactura = json_encode(array_merge($infoRes, $infoCliente, $infoTipoPago, $prevBalance, $infoPagoGeneral));

            echo json_encode(array(
                'token' => $factura['Empresa']['tokendian'],
                'nitEmpresa' => $factura['Empresa']['nit'],
                array_merge($infoRes, $infoCliente, $infoTipoPago, $prevBalance, $infoPagoGeneral)
            ));

        } else {
            // Actualiza el estado de la factura
            // $this->actualizarEstadoFactura( $val['id'], config('custom.DIAN_ESTADO_ERROR'), $conn );
            // $this->actualizarMensajeFactura( $val['id'] , config('custom.MENSAJE_ERROR'), $conn );
        }
    }

    /**
     * Genera el arreglo con la información de la resolución de la empresa
     */
    public function generarInfoResolucion($factura) {
        $this->loadModel('Resolucionfactura');
        $this->loadModel('Notafactura');
        $tipoDocumento = '1';

        //Obtiene la información del deposito con la configuración de la resolución
        $infoDep = $this->Resolucionfactura->obtenerResolucion( $factura['Factura']['empresa_id'], $tipoDocumento );

        //Obtiene el listado de notas para facturas de la empresa
        $listaNotasF = $this->Notafactura->obtenerNotasFacturasEmpresa($factura['Factura']['empresa_id']);

        $footNote = "";
        if(isset($factura['FacturasNotafactura']['0']['notafactura_id'])) {
            $footNote = $listaNotasF[$factura['FacturasNotafactura']['0']['notafactura_id']];
        }

        return [
            "number" => $factura['Factura']['consecutivodian'],
            "prefix" => $factura['Factura']['prefijo'],
            "type_document_id" =>  $factura['Empresa']['typedocument'],
            "date" =>  date('Y-m-d'),
            "time" =>  date('H:i:s'),
            "resolution_number" =>  $infoDep['Resolucionfactura']['resolucion'],
            "notes" => $factura['Factura']['observacion'],
            "foot_note" => $footNote
        ];

    }

    /**
     * Genera el arreglo con la información del cliente de la factura
     */
    public function generarInfoCliente( $factura ) {

        if ( !isset( $factura['Cliente'] ) ) { 
            return $this->formatearInfoCliente([
                'nit' => '1234567890',
                'nombre' => 'Cliente anónimo',
                'celular' => '3101234567',
                'direccion' => 'Calle 1 # 2 - 3',
                'email' => 'noemail@hotmail.com',
                'tipoidentificacione_id' => '3'
            ], $factura['Empresa']['municipio_id']);
        }
    
        return $this->formatearInfoCliente($factura['Cliente'], $factura['Empresa']['municipio_id']);

    }

        /**
     * Calcula el digito de verificacion de un nit 
     */
    private function calcularDigitoVerificacion($nit)  {

        // Factores predefinidos para la multiplicación
        $factors = [3, 7, 13, 17, 19, 23, 29, 37, 41];
        
        // Convertir el NIT en una cadena y asegurarse de que sea solo dígitos
        $nit = (string) preg_replace('/\D/', '', $nit);

        // Inicializar la suma
        $sum = 0;
        
        // Longitud del NIT
        $length = strlen($nit);

        // Recorrer cada dígito del NIT, multiplicarlo por el factor correspondiente y sumarlo
        for ($i = 0; $i < $length; $i++) {
            $sum += $nit[$i] * $factors[$length - $i - 1];
        }

        // Obtener el residuo de la división de la suma por 11
        $remainder = $sum % 11;

        // Determinar el dígito de verificación
        if ($remainder == 0 || $remainder == 1) {
            return $remainder;
        } else {
            return 11 - $remainder;
        }
    }

    /**
     * Genera la información del cliente siempre y cuando se encuentre relacionado 
     * a la factura en la base de datos
     */
    private function formatearInfoCliente($infoCliente, $municipio_id) {

        $typeRegimen = $this->obtenerOrganizacion($infoCliente['tipoidentificacione_id']);
        
        // Manejar el caso en que el cliente es una organización (tipoRegimen = 1)
        $identification = $infoCliente['nit'] != null && $infoCliente['nit'] != "" ? $infoCliente['nit'] : '1234567890';
        $identification = $this->obtenerIdentificacion($identification);
          
        if ($typeRegimen == '1' && strlen($identification) > 9) {
            $identification = substr($identification, 0, 9);
        }

        $dv = $typeRegimen == '1' ? $this->calcularDigitoVerificacion($identification) : null;

        $cliente = [
            "identification_number" => $identification,
            "dv" => $dv,
            "name" => !empty($infoCliente['nombre']) ? $infoCliente['nombre'] : 'Cliente anónimo',
            "phone" => !empty($infoCliente['celular']) ? $infoCliente['celular'] : '3101234567',
            "address" => !empty($infoCliente['direccion']) ? $infoCliente['direccion'] : 'Calle 1 # 2 - 3',
            "email" => !empty($infoCliente['email']) ? $infoCliente['email'] : 'noemail@hotmail.comm',
            "merchant_registration" => '0000-00',
            "type_document_identification_id" => $infoCliente['tipoidentificacione_id'],
            "type_organization_id" => $typeRegimen,
            "municipality_id" => $municipio_id,
            "type_regime_id" => $typeRegimen
        ];

        // Si el cliente no es una organización, removemos el campo 'dv'
        if ($typeRegimen != '1') {
            unset($cliente['dv']);
        }
    
        return ['customer' => $cliente];
    }

    /**
     * Obtiene el número de identificación del cliente
     */
    public function obtenerIdentificacion( $identificacion ) {
        if (strpos($identificacion, '-') !== false) {
            $identificacion = explode('-', $identificacion);
            return str_replace(" ", "", $identificacion['0']);
        } else {
            return str_replace(" ", "", $identificacion);
        } 
    }

    /**
     * Obtiene el tipo de organización basado en su tipo de identificación
     */
    public function obtenerOrganizacion( $tipoIdentificacion ) {

        if (in_array($tipoIdentificacion, [1, 2, 3, 4, 5, 7, 8])) {
            return '2';
        }
        
        if (in_array($tipoIdentificacion, [6, 9, 10, 11, 12])) {
            return '1';
        }

        return null;

    }

    /**
     * Genera la información del tipo de pago de la factura
     */
    public function generarInfoTipoPago( $factura ) {
        
        $this->loadModel('FacturaCuentaValore');
        $this->loadModel('Cuentascliente');

        $infoTipoPago = $this->FacturaCuentaValore->obtenerInfoTipoPagoEfectivo( $factura['Factura']['id'] );
  
        if( isset( $infoTipoPago['0']['FacturaCuentaValore'] ) ){
  
          return [
            "payment_form" => [
              "payment_form_id" => '1',
              "payment_method_id" => $infoTipoPago['0']['T']['transferencia'] ? '47' : '10' 
            ]
          ];
  
        } else {
          
          $infoTipoPago = $this->Cuentascliente->obtenerInfoTipoPagoCredito( $factura['Factura']['id'] );
          
          if( isset( $infoTipoPago['0']['Cuentascliente'] ) ) {

            $duration_measure = isset($factura['Cliente']['id']) ? ($factura['Cliente']['diascredito'] ?? 30) : 30;
  
            // $date = date_create($infoTipoPago['0']['Cuentascliente']['created']);
            $date = date('Y-m-d');;

            return [
              "payment_form" => [
                "payment_form_id" => '2',
                "payment_method_id" => '30',
                "payment_due_date" => $this->sumarDiasFecha(date('Y-m-d'), $duration_measure),
                "duration_measure" => $duration_measure
              ]
            ];
  
          }
  
        }
  
        return [
            "payment_form" => [
              "payment_form_id" => '1',
              "payment_method_id" => '10'
            ]
          ];
  
     }


    /**
     * Suma una cantidad de días a una fecha específica
     */
    public function sumarDiasFecha( $fecha, $dias ) {

        $fecha = new DateTime($fecha);

        $fecha->modify("+$dias days");

        return $fecha->format('Y-m-d');

    }


    /**
     * Genera la información general de la factura
     */
    public function generarInfoPagoGeneral( $factura ) {
        $this->loadModel('Facturasdetalle');

        if ( !isset( $factura['Facturasdetalle']['0'] ) ) {
          return false;
        }
        
        $sumValSinIva = 0;
        $sumValConIva = 0;
        $arrImpuestos = [];
        $arrLineas = [];

        //Obtiene la información del detalle de la factura
        $facturaDetalle = $this->Facturasdetalle->obtenerFacturaDetalleFactId( $factura['Factura']['id'] );
  
        foreach ( $facturaDetalle as $val ) {

          $costoTotal = $val['Facturasdetalle']['costototal'];
          $descuento = $val['Facturasdetalle']['descuento'];
          $impuesto = $val['Facturasdetalle']['impuesto'];
  
          list($valSinImp, $valIva) = $this->calcularValores($costoTotal, $descuento, $impuesto);
  
          // Suma de valores con y sin IVA
          $sumValSinIva += $valSinImp;
          $sumValConIva += round( ( $valSinImp + $valIva ), 2 );
  
          // Información de los impuestos por cada producto
          $arrImpuestos[] = $this->obtenerImpuestoPorProducto( $valIva, $valSinImp, $val['Facturasdetalle']['impuesto'] );
  
          // Información detallada de las lineas
          $arrLineas[] = $this->obtenerDetalleLineas( $val, $arrImpuestos[count($arrImpuestos) - 1] );
          
        }
  
        // Información de los totales de la venta con y sin iva
        $arrTotales = $this->obtenerTotalesVenta( $sumValSinIva, $sumValConIva );
  
        return ['legal_monetary_totals' => $arrTotales, 'tax_totals' => $arrImpuestos, 'invoice_lines' => $arrLineas];
  
    }

    /**
     * Calcular los valores de impuestos y productos
     */
    private function calcularValores( $costoTotal, $descuento, $impuesto ) {

        if ( $impuesto > 0 ) {

            $impuesto /= 100;
            $valSinImp = (round( ( $costoTotal ) / ( 1 + $impuesto ), 2 )) - $descuento;
            $valIva = round( $valSinImp * $impuesto, 2 );
        
        } else {
            $valSinImp = round( ( $costoTotal - $descuento ), 2 );
            $valIva = number_format( 0, 2 );
        }

        return [number_format($valSinImp, 2, '.', ''), number_format($valIva, 2, '.', '')];
    }    

    /**
     * Retorna un arreglo con los totales de venta con y sin iva
     */
    public function obtenerTotalesVenta( $sumValSinIva, $sumValConIva ) {

        return [
        'line_extension_amount' => round( $sumValSinIva, 2),
        'tax_exclusive_amount' => round( $sumValSinIva, 2),
        'tax_inclusive_amount' => round( $sumValConIva, 2),
        'payable_amount' => round( $sumValConIva, 2)
        ];
    }

    /**
     * Retorna un arreglo con el impuesto de un producto
     */
    public function obtenerImpuestoPorProducto( $valIva, $valSinImp, $impuesto){

        return [
        'tax_id' => '1',
        'tax_amount' => $valIva,
        'taxable_amount' => $valSinImp,
        'percent' => number_format($impuesto, 2)
        ];
    }

    /**
     * Genera la información de las lineas de la factura
     */
    public function obtenerDetalleLineas($val, $arrImpuestos)
    {
        // Inicializa el array de productos con los valores proporcionados
        $arrProductos = [
            'unit_measure_id' => '70',
            'invoiced_quantity' => $val['Facturasdetalle']['cantidad'],
            'line_extension_amount' => $arrImpuestos['taxable_amount'],
            'free_of_charge_indicator' => false,
            'tax_totals' => [$arrImpuestos],
            'description' => $val['P']['descripcion'],
            'code' => $val['P']['codigo'],
            'type_item_identification_id' => 4,
            'price_amount' => $val['Facturasdetalle']['costoventa'],
            'base_quantity' => $val['Facturasdetalle']['cantidad']
        ];
    
        // Verifica si hay descuento y lo añade al array de productos si es necesario
        if ($val['Facturasdetalle']['descuento'] > 0) {
            $arrProductos['allowance_charges']['0'] = [
                "discount_id" => '1',
                "charge_indicator" => false,
                "allowance_charge_reason" => "Descuento General",
                "amount" => $val['Facturasdetalle']['descuento'],
                "base_amount" => $val['Facturasdetalle']['costototal']
            ];
        }
    
        return $arrProductos;
    }    

    function validateArrays(...$arrays){
        foreach ($arrays as $array) {
            if (!is_array($array) || empty($array)) {
                return false;
            }
        }
        return true;
    }


    /**
     * Consumir el servicio de facturación de la DIAN
     */
    public function guardarInfoDian( ) {

        $this->autoRender = false;
        $this->loadModel('Factura');
        $facturaId = $this->request->data['facturaId'];
        $statusCode = $this->request->data['statusCode'];
        $cufe = $this->request->data['cufe'];
        $QR = $this->request->data['QR'];
        
        $result = $this->Factura->actualizarInfoDian( $facturaId, $statusCode, $cufe, $QR );

        echo json_encode(array(
            'result' => $result
        ));

    }

    /**
     * Consumir el servicio de facturación de la DIAN
     */
    public function guardarNCInfoDian( ) {

        $this->autoRender = false;
        $this->loadModel('Factura');
        $this->loadModel('Resolucionfactura');

        $empresaId = $this->Auth->user('empresa_id');
        $facturaId = $this->request->data['facturaId'];
        $statusCode = $this->request->data['statusCode'];
        $cude = $this->request->data['cude'];
        $NCQR = $this->request->data['QR'];
        $consecutivo = $this->request->data['consecutivoNC'];
        $prefijo = $this->request->data['prefijoNC'];

        $documentoId = '4';
        $result = $this->Resolucionfactura->actualizarResolucion( $empresaId, $documentoId );
        
        $result = $this->Factura->actualizarNCInfoDian( $facturaId, $statusCode, $cude, $NCQR, $consecutivo, $prefijo );

        echo json_encode(array(
            'result' => $result
        ));

    }

    /**
     * Genera la información requerida para la factura de refencia en la nota crédito
     */
    public function generarFacturaReferencia($factura) {
        $this->loadModel('Deposito');

        return [
            "billing_reference" => [
              "number" => $factura['Factura']['prefijo'] . $factura['Factura']['consecutivodian'],
              "uuid" => $factura['Factura']['diancufe'],
              "issue_date" => date('Y-m-d')
            ]
          ];
    }

    /**
     * genera la información general para la nota credito
     */
    public function generarInformacionGeneral($factura) {
        $this->loadModel('Resolucionfactura');

        //Obtiene la información del deposito con la configuración de la resolución
        $documentoId = '4';
        $infoNC = $this->Resolucionfactura->obtenerResolucion($factura['Factura']['empresa_id'], $documentoId);

        $descNotaCredito = "El usuario " . $factura['Usuario']['nombre'] . " genera nota credito sobre la factura No. " . $factura['Factura']['consecutivodian'];

        if( !isset($infoNC['Resolucionfactura']['id'])) {
            return [];
        }

        return [
            "discrepancyresponsecode" => 2,
            "discrepancyresponsedescription" => $descNotaCredito,
            "notes" => $descNotaCredito,
            "prefix" => $infoNC['Resolucionfactura']['prefijo'],
            "number" => $infoNC['Resolucionfactura']['consecutivoactual'],
            "type_document_id" => $infoNC['Resolucionfactura']['tipodocumentoventa_id'],
            "date" =>  date('Y-m-d'),
            "time" =>  date('H:i:s'),
            "establishment_name" =>  $infoNC['Empresa']['nombre'],
            "establishment_address" =>  !empty($infoNC['Empresa']['direccion']) ? $infoNC['Empresa']['direccion'] : 'Calle 1 # 2 - 3',
            "establishment_phone" =>  !empty($infoNC['Empresa']['telefono1']) ? $infoNC['Empresa']['telefono1'] : '3111234567',
            "establishment_municipality" =>  $infoNC['Empresa']['municipio_id'],
            "sendmail" =>  true,
            "sendmailtome" =>  true
        ];
    }

    /**
     * Genera el json para la nota crédito hacia la Dian
     */
    public function obtenerNCParaDian() {
        $this->autoRender = false;
        $this->loadModel('Factura');
        $facturaId = $this->request->data['facturaId'];

        //Obtiene la información de la factura
        $factura = $this->Factura->obtenerInfoFacturaPorId($facturaId);

        //Generar factura de referencia
        $billingReference = $this->generarFacturaReferencia($factura);

        //Generar datos generales de la nota crédito
        $generalInfo = $this->generarInformacionGeneral($factura);

        //Obtiene la información del cliente de la factura
        $infoCliente = $this->generarInfoCliente( $factura );

        //Obtiene los totales generales de la factura
        $infoPagoGeneral = $this->generarInfoPagoGeneral( $factura );

        // Verificar si existe la clave invoice_lines
        if (isset($infoPagoGeneral['invoice_lines'])) {
            // Asignar el valor a la nueva clave
            $infoPagoGeneral['credit_note_lines'] = $infoPagoGeneral['invoice_lines'];
            // Eliminar la clave antigua
            unset($infoPagoGeneral['invoice_lines']);
        }

        //Valida que todos los resultados sean un array procesable
        if ($this->validateArrays($billingReference, $generalInfo, $infoCliente, $infoPagoGeneral)) {

            echo json_encode(array(
                'status' => true,
                'token' => $factura['Empresa']['tokendian'], 
                'nitEmpresa' => $factura['Empresa']['nit'],
                'prefijo' => $generalInfo['prefix'],
                'consecutivo' => $generalInfo['number'],
                array_merge($billingReference, $generalInfo, $infoCliente, $infoPagoGeneral)
            ));

        } else {
            echo json_encode(array(
                'status' => false
            ));
        }       

    }

    /**
     * Obtiene la información para el reporte de notas crédito
     */
    public function notascredito()
    {

        /*se reagistra la actividad del uso de la aplicacion*/
        $usuariosController = new UsuariosController();
        $usuarioAct = $this->Auth->user('id');
        $usuariosController->registraractividad($usuarioAct);

        $rpcodigo = "";
        $rpconsecutivo = "";
        $rpvendedor = "";
        $rpfecha = "";
        $rpvencimiento = "";
        $rptipopago = "";
        $esFactura = "";
        $rpfechaFin = "";

        $this->loadModel('Tipopago');
        $this->loadModel('Usuario');
        $this->loadModel('Cuenta');

        if (isset($this->passedArgs['codigo']) && $this->passedArgs['codigo'] != "") {
            $paginate['Factura.codigo LIKE'] = '%' . $this->passedArgs['codigo'] . '%';
            $rpcodigo = $this->passedArgs['codigo'];
        }

        if (isset($this->passedArgs['consecutivo']) && $this->passedArgs['consecutivo'] != "") {
            $paginate['Factura.consecutivodian LIKE'] = '%' . $this->passedArgs['consecutivo'] . '%';
            $rpconsecutivo = $this->passedArgs['consecutivo'];
        }

        if (isset($this->passedArgs['vendedor']) && $this->passedArgs['vendedor'] != "") {
            $paginate['Factura.usuario_id'] = $this->passedArgs['vendedor'];
            $rpvendedor = $this->passedArgs['vendedor'];
        }

        if (!empty($this->passedArgs['fechafactura']) && empty($this->passedArgs['fechafactura_fin'])) {
            $paginate['Factura.created BETWEEN ? AND ?'] = array($this->passedArgs['fechafactura'] . ' 00:00:00', $this->passedArgs['fechafactura'] . ' 23:59:59');
            $rpfecha = $this->passedArgs['fechafactura'] . ' 00:00:00';
            $rpfechaFin = $this->passedArgs['fechafactura'] . ' 23:59:59';
        }

        if (!empty($this->passedArgs['fechafactura_fin']) && empty($this->passedArgs['fechafactura'])) {
            $paginate['Factura.created BETWEEN ? AND ?'] = array($this->passedArgs['fechafactura_fin'] . ' 00:00:00', $this->passedArgs['fechafactura_fin'] . ' 23:59:59');
            $rpfecha = $this->passedArgs['fechafactura_fin'] . ' 00:00:00';
            $rpfechaFin = $this->passedArgs['fechafactura_fin'] . ' 23:59:59';
        }

        if (!empty($this->passedArgs['fechafactura']) && !empty($this->passedArgs['fechafactura_fin'])) {
            $paginate['Factura.created BETWEEN ? AND ?'] = array($this->passedArgs['fechafactura'] . ' 00:00:00', $this->passedArgs['fechafactura_fin'] . ' 23:59:59');
            $rpfecha = $this->passedArgs['fechafactura'] . ' 00:00:00';
            $rpfechaFin = $this->passedArgs['fechafactura_fin'] . ' 23:59:59';
        }

        if (isset($this->passedArgs['fechavence']) && $this->passedArgs['fechavence'] != "") {
            $paginate['Factura.fechavence BETWEEN ? AND ?'] = array($this->passedArgs['fechavence'] . ' 00:00:00', $this->passedArgs['fechavence'] . ' 23:59:59');
            $rpvencimiento = $this->passedArgs['fechavence'];
        }

        if (isset($this->passedArgs['tipopago']) && $this->passedArgs['tipopago'] != "") {
            $paginate['FCV.tipopago_id'] = $this->passedArgs['tipopago'];
            $rptipopago = $this->passedArgs['tipopago'];
        }

        if (isset($this->passedArgs['vehiculo']) && $this->passedArgs['vehiculo']) {
            $paginate['LOWER(V.placa) LIKE'] = '%' . strtolower($this->passedArgs['vehiculo']) . '%';
            $rpplaca = $this->passedArgs['vehiculo'];
        }

        if (isset($this->passedArgs['cliente']) && $this->passedArgs['cliente']) {
            $paginate['LOWER(C.nombre) LIKE'] = '%' . strtolower($this->passedArgs['cliente']) . '%';
            $rpplaca = $this->passedArgs['vehiculo'];
        }

        if (isset($this->passedArgs['factura']) && !empty($this->passedArgs['factura'])) {
            $paginate['Factura.factura'] = true;
            $esFactura = '1';
        } else if (isset($this->passedArgs['factura']) && $this->passedArgs['factura'] == '0') {
            $paginate['Factura.factura'] = false;
            $esFactura = '0';
        }

        $empresaId = $this->Auth->user('empresa_id');
        //Se obtiene el listado de tipos de pago para el filtro
        $tipoPago = $this->Tipopago->obtenerListaTiposPagos($empresaId);

        //se obtienen los usuarios de la empresa para el filtro
        $usuario = $this->Usuario->obtenerUsuarioEmpresa($empresaId);
        $paginate['Factura.empresa_id'] = $empresaId;
        $paginate['Factura.eliminar'] = 1;

        $this->Factura->recursive = 0;
        $this->Paginator->settings = $this->Factura->obtenerIndexFacturas($paginate);
        $facturas = $this->Paginator->paginate('Factura');


        $this->set(compact('facturas'));
        $this->set(compact('tipoPago', 'usuario', 'rpcodigo', 'rpconsecutivo', 'rpvendedor', 'rpfecha', 'rpvencimiento', 'rptipopago'));
        $this->set(compact('esFactura', 'rpfechaFin'));

    }
}
