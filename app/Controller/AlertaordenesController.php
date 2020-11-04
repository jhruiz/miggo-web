<?php
App::uses('AppController', 'Controller');

class AlertaordenesController extends AppController
{

    public $components = array('Paginator');

    public function gestionalertas($ordenTrabajoId)
    {
        $this->loadModel('Alerta');
        $this->loadModel('Unidadesmedida');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Ordentrabajo');
        $this->loadModel('Usuario');

        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //se obtiene el listado de alertas
        $alertas = $this->Alerta->obtenerListaAlertas($empresa_id);

        //Se obtienen las unidades de medida
        $unidadesMed = $this->Unidadesmedida->listaUnidadesMedida();

        //se obtiene el listado de alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);

        $fechaActual = date('Y-m-d');

        //se obtiene la información de la orden de trabajo y del vehiculo
        $filter['Ordentrabajo.id'] = $ordenTrabajoId;
        $infoOrdenCliV = $this->Ordentrabajo->obtenerOrdenesTrabajo($filter);
        //se obtiene el listado de usuarios
        $usuarios = $this->Usuario->obtenerUsuarioEmpresa($empresa_id);

        $this->set(compact('ordenTrabajoId', 'empresa_id', 'alertas'));
        $this->set(compact('unidadesMed', 'estadoAlertas', 'fechaActual', 'infoOrdenCliV','usuarios'));
    }
    
    public function guardaralertas()
    {
        $this->loadModel('Alertaordene');
        $this->autoRender = false;
        $postData = $this->request->data;

    

        $resp = $this->Alertaordene->guardaralerta($postData);
        echo json_encode(array('resp' => $resp));

    }
    
    public function guardaralertafactura()
    {
        $this->loadModel('Alertaordene');
        $this->autoRender = false;
        $postData = $this->request->data;
        // print_r($postData);
        $resp = $this->Alertaordene->guardaralertafactura($postData);
        echo json_encode(array('resp' => $resp));

    }

    public function index()
    {
        $this->loadModel('Alertaordene');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Alerta');
        $this->loadModel('Usuario');

        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //fecha actual
        $fechaAct = date('Y-m-d');
        $tipoAlerta = '';
        $cliente= '';
        $placa='';
        $tecnico= '';
        $tecnicoSelect= '';
        $idEstado= '';

        $filtros = array(); 

        if (isset($this->passedArgs['tipoAlerta']) && $this->passedArgs['tipoAlerta'] != "") {
            $tipoAlerta=  $this->passedArgs['tipoAlerta'];
            array_push($filtros, array(
                'AL.id' => $tipoAlerta,
                ));
        }
        if (isset($this->passedArgs['cliente']) && $this->passedArgs['cliente'] != "") {
            $cliente=  $this->passedArgs['cliente'];
            array_push($filtros, array(
                'LOWER(CL.nombre) LIKE' => '%' .strtolower($cliente) .  '%',            
            ));
        }
        if (isset($this->passedArgs['placa']) && $this->passedArgs['placa'] != "") {
            $placa=  $this->passedArgs['placa'];
            array_push($filtros, array(
                'LOWER(VH.placa) LIKE' => '%' .strtolower($placa) .  '%',         
            ));
        }
        if (isset($this->passedArgs['tecnico']) && $this->passedArgs['tecnico'] != "") {
            $tecnico=  $this->passedArgs['tecnico'];
            // array_push($filtros, array(
            // 'LOWER(US.nombre) LIKE' => '%' .strtolower($tecnico) .  '%',            
            // ));
        }
        if (isset($this->passedArgs['tecnicoSelect']) && $this->passedArgs['tecnicoSelect'] != "") {
            $tecnicoSelect=  $this->passedArgs['tecnicoSelect'];
            // array_push($filtros, array(
            // 'US.id' => $tecnicoSelect,            
            // ));
        }
        if (isset($this->passedArgs['estadoalerta']) && $this->passedArgs['estadoalerta'] != "") {
            $idEstado=  $this->passedArgs['estadoalerta'];
            array_push($filtros, array(
                'EA.id' => $idEstado,            
            ));
        }

        array_push($filtros, array(
            'EA.final' => '0',
            'EA.empresa_id' => $empresa_id,
            'Alertaordene.fecha_alerta <= ' => $fechaAct,
            'Alertaordene.factura_id = ' => Null,
            'Alertaordene.prefactura_id = ' => Null,
        ));

        if (!empty($this->passedArgs['estadoalerta'])) {
            $filtros['EA.id'] = $this->passedArgs['estadoalerta'];
        }

        //se obtiene el listado de estado alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);
        $estadoAlertasTabs = $this->Estadoalerta->find('all');
        $alertasOrdenes = $this->Alertaordene->obtenerInfoAlertaOrden($filtros);
        $alertas = $this->Alerta->obtenerListaAlertas($empresa_id);
        $tecnicosSelect = $this->Usuario->obtenerUsuarioEmpresa($empresa_id);
        $alertaDocumentos = $this->Alertaordene->obtnerAlertasRenovacionDocs($filtros);

        $this->set(compact('empresa_id', 'fechaAct', 'alertasOrdenes', 'estadoAlertasTabs', 'alertaDocumentos', 'estadoAlertas'));
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

    public function edit($id = null)
    {
        $this->loadModel('Alerta');
        $this->loadModel('Unidadesmedida');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Ordentrabajo');
        $this->loadModel('Usuario');

        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //se obtiene el listado de alertas
        $alertas = $this->Alerta->obtenerListaAlertas($empresa_id);

        //Se obtienen las unidades de medida
        $unidadesMed = $this->Unidadesmedida->listaUnidadesMedida();

        //se obtiene el listado de alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);

        //se obtiene la información de la orden de trabajo y del vehiculo
        $filtros = array(
            'Alertaordene.id' => $id,
        );

        $alertasOrdenes = $this->Alertaordene->obtenerInfoAlertaOrden($filtros);

        $this->set(compact('alertas'));
        $this->set(compact('unidadesMed', 'estadoAlertas', 'fechaActual', 'alertasOrdenes'));
    }

    public function editdocs($id = null)
    {
        $this->loadModel('Alerta');
        $this->loadModel('Unidadesmedida');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Ordentrabajo');

        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //se obtiene el listado de alertas
        $alertas = $this->Alerta->obtenerListaAlertas($empresa_id);

        //Se obtienen las unidades de medida
        $unidadesMed = $this->Unidadesmedida->listaUnidadesMedida();

        //se obtiene el listado de alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);

        //se obtiene la información de la orden de trabajo y del vehiculo
        $filtros = array(
            'Alertaordene.id' => $id,
        );

        $alertasOrdenes = $this->Alertaordene->obtnerAlertasRenovacionDocs($filtros);

        $this->set(compact('alertas'));
        $this->set(compact('unidadesMed', 'estadoAlertas', 'fechaActual', 'alertasOrdenes'));
    }

    public function actualizarllamadas()
    {
        $this->autoRender = false;

        $postData = $this->request->data;

        //se obtiene la información de la alerta
        $alertaInfo = $this->Alertaordene->obtenerInfoAlerta($postData['alerta_id']);

        $cantLlamadas = !empty($alertaInfo['Alertaordene']['cant_llamadas'])
        ? $alertaInfo['Alertaordene']['cant_llamadas'] + 1 : 1;

        $data['id'] = $postData['alerta_id'];
        $data['cant_llamadas'] = $cantLlamadas;
        $data['fecha_ultima_llamada'] = date('Y-m-d H:i:s');

        $resp = $this->Alertaordene->actualizarAlerta($data);

        echo json_encode(array('resp' => $resp, 'cant' => $cantLlamadas, 'fecha' => $data['fecha_ultima_llamada']));
    }

    public function actualizarestado()
    {
        $this->autoRender = false;

        $postData = $this->request->data;

        $data['id'] = $postData['alertaId'];
        $data['estadoalerta_id'] = $postData['estadoId'];

        $resp = $this->Alertaordene->actualizarAlerta($data);

        echo json_encode(array('resp' => $resp));
    }

    public function actualizarobservaciones()
    {
        $this->autoRender = false;

        $postData = $this->request->data;

        $data['id'] = $postData['alertaId'];
        $data['observaciones'] = $postData['observaciones'];

        $resp = $this->Alertaordene->actualizarAlerta($data);

        echo json_encode(array('resp' => $resp));
    }

    public function generaralertasoat()
    {
        $this->autoRender = false;

        $this->loadModel('Alerta');
        $this->loadModel('Estadoalerta');

        $dias = 5;

        $postData = $this->request->data;

        //Se obtiene el tipo de alerta para la oficina
        $desc = 'SOAT';
        $empresa_id = $this->Auth->user('empresa_id');
        $alertaInfo = $this->Alerta->obtnerAlertaEmpresa($empresa_id, $desc);
        $data['alerta_id'] = $alertaInfo['Alerta']['id'];
        $data['unidadesmedida_id'] = '1'; //dias
        $estadoAlertas = $this->Estadoalerta->obtenerEstadoAlertaInicial($empresa_id);
        $data['estadoalerta_id'] = $estadoAlertas['Estadoalerta']['id'];
        $data['fecha_alerta'] = $this->restarDiasFecha($postData['soat'], $dias);
        $data['fecha_mantenimiento'] = $postData['soat'];
        $data['observaciones'] = 'Renovacion del SOAT';
        $data['cliente_id'] = $postData['clienteId'];
        $data['vehiculo_id'] = $postData['vehiculoId'];
        $data['ordentrabajo_id'] = $postData['ordentrabajoId'];
        $data['usuario_id'] = $postData['usuarioId'];

        //se valida si ya existe una alerta para el vehiculo y el soat actual
        $validSoat = $this->Alertaordene->obtenerAlertaOrdenSoat($postData['clienteId'],
            $postData['vehiculoId'],
            $postData['soat'],
            $estadoAlertas['Estadoalerta']['id'],
            $alertaInfo['Alerta']['id']
        );

        if (empty($validSoat)) {
            $resp = $this->Alertaordene->actualizarAlerta($data);
        } else {
            $resp = '2';
        }

        echo json_encode(array('resp' => $resp));

    }
    public function generaralertacumpleanos()
    {
        $this->autoRender = false;
        $this->loadModel('Alerta');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Cliente');
        $dias = 5;
        $postData = $this->request->data;
        $idcliente = $postData['clienteId'];
        $fechacumpleaños = $this->Cliente->obtenerInformacionClienteFecha($idcliente);
        //Se obtiene el tipo de alerta para la oficina
        $desc = 'SOAT';
        $empresa_id = $this->Auth->user('empresa_id');
        $alertaInfo = $this->Alerta->obtnerAlertaEmpresa($empresa_id, $desc);
        $data['alerta_id'] = 11;
        $data['unidadesmedida_id'] = '1'; //dias
        $estadoAlertas = $this->Estadoalerta->obtenerEstadoAlertaInicial($empresa_id);
        $data['estadoalerta_id'] = $estadoAlertas['Estadoalerta']['id'];
        $data['fecha_alerta'] = $postData['fechacumple'];
        $data['fecha_mantenimiento'] = $postData['soat'];
        $data['observaciones'] = 'Cumpleaños';
        $data['cliente_id'] = $postData['clienteId'];
        $data['vehiculo_id'] = $postData['vehiculoId'];
        $data['factura_id'] = $postData['facturaId'];
        $data['prefactura_id'] = $postData['prefacturaId'];
        $data['usuario_id'] = $postData['usuarioId'];
        $data[' '] = $postData['prefacturaId'];

        // print_r($data['factura_id']);
        //se valida si ya existe una alerta para el vehiculo y el soat actual
        $validSoat = $this->Alertaordene->obtenerAlertaOrdenSoat($postData['clienteId'],
            $postData['vehiculoId'],
            $postData['soat'],
            $estadoAlertas['Estadoalerta']['id'],
            11
        );

        
        if (empty($validSoat)) {
            $resp = $this->Alertaordene->actualizarAlerta($data);
        } else {
            $resp = '2';
        }

        echo json_encode(array('resp' => $resp));

    }

    public function generaralertatecno()
    {
        $this->autoRender = false;

        $this->loadModel('Alerta');
        $this->loadModel('Estadoalerta');

        $dias = 5;

        $postData = $this->request->data;

        //Se obtiene el tipo de alerta para la oficina
        $desc = 'TECNOMECANICA';
        $empresa_id = $this->Auth->user('empresa_id');
        $alertaInfo = $this->Alerta->obtnerAlertaEmpresa($empresa_id, $desc);
        $data['alerta_id'] = $alertaInfo['Alerta']['id'];
        $data['unidadesmedida_id'] = '1'; //dias
        $estadoAlertas = $this->Estadoalerta->obtenerEstadoAlertaInicial($empresa_id);
        $data['estadoalerta_id'] = $estadoAlertas['Estadoalerta']['id'];
        $data['fecha_alerta'] = $this->restarDiasFecha($postData['tecnomecanica'], $dias);
        $data['fecha_mantenimiento'] = $postData['tecnomecanica'];
        $data['observaciones'] = 'Renovacion del tecnomecanica';
        $data['cliente_id'] = $postData['clienteId'];
        $data['vehiculo_id'] = $postData['vehiculoId'];
        $data['ordentrabajo_id'] = $postData['ordentrabajoId'];
        $data['usuario_id'] = $postData['usuarioId'];
        //se valida si ya existe una alerta para el vehiculo y el soat actual
        $validTecno = $this->Alertaordene->obtenerAlertaOrdenSoat($postData['clienteId'],
            $postData['vehiculoId'],
            $postData['tecnomecanica'],
            $estadoAlertas['Estadoalerta']['id'],
            $alertaInfo['Alerta']['id']
        );

        if (empty($validTecno)) {
            $resp = $this->Alertaordene->actualizarAlerta($data);
        } else {
            $resp = '2';
        }

        echo json_encode(array('resp' => $resp));
    }

    public function restarDiasFecha($fecha, $dias)
    {
        $fechaBase = date($fecha);
        $fechaResult = date("Y-m-d", strtotime($fechaBase . " - " . $dias . " days"));
        return $fechaResult;
    }

    public function indexfinalizadas()
    {
        $this->loadModel('Alertaordene');

        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //fecha actual
        $fechaAct = date('Y-m-d');

        //se obtienen las alertas y las ordenes de trabajo
        $filtros = array(
            'EA.final' => '1',
            'EA.empresa_id' => $empresa_id,
        );

        $alertasOrdenes = $this->Alertaordene->obtenerInfoAlertaOrden($filtros);

        $alertaDocumentos = $this->Alertaordene->obtnerAlertasRenovacionDocs($filtros);

        $this->set(compact('empresa_id', 'fechaAct', 'alertasOrdenes', 'alertaDocumentos'));
    }

    /////////////////////////////// INFORMACION PARA LAS ESTADISTICAS ///////////////////////////////
    public function estadisticasfinalizadas()
    {
        $this->autoRender = false;

        $tortas = [];

        $tortas[] = $this->obtenerAlertasFinalizadas();

        echo json_encode(array('resp' => $tortas));
    }

    public function obtenerAlertasFinalizadas()
    {
        $this->loadModel('Alertaordene');

        $alertasOrdenes = $this->Alertaordene->obtieneEstadoAlertaTortas($this->Auth->user('empresa_id'));

        $arrTitulos = [];
        $seriesData = [];

        foreach ($alertasOrdenes as $ao) {
            $arrTitulos[] = $ao['EA']['descripcion'];

            $seriesData[] = [
                'value' => $ao['0']['contador'],
                'name' => $ao['EA']['descripcion'],
            ];
        }

        $alertas = [
            'titulo' => 'Alertas Finalizadas',
            'legend_data' => $arrTitulos,
            'series_data' => $seriesData,
        ];

        return $alertas;
    }

    public function viewf($id = null)
    {
        $this->loadModel('Alerta');
        $this->loadModel('Unidadesmedida');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Ordentrabajo');

        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //se obtiene el listado de alertas
        $alertas = $this->Alerta->obtenerListaAlertas($empresa_id);

        //Se obtienen las unidades de medida
        $unidadesMed = $this->Unidadesmedida->listaUnidadesMedida();

        //se obtiene el listado de alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);

        //se obtiene la información de la orden de trabajo y del vehiculo
        $filtros = array(
            'Alertaordene.id' => $id,
        );

        $alertasOrdenes = $this->Alertaordene->obtenerInfoAlertaOrden($filtros);

        $this->set(compact('alertas'));
        $this->set(compact('unidadesMed', 'estadoAlertas', 'fechaActual', 'alertasOrdenes'));
    }

    public function viewfd($id = null)
    {
        $this->loadModel('Alerta');
        $this->loadModel('Unidadesmedida');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Ordentrabajo');

        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //se obtiene el listado de alertas
        $alertas = $this->Alerta->obtenerListaAlertas($empresa_id);

        //Se obtienen las unidades de medida
        $unidadesMed = $this->Unidadesmedida->listaUnidadesMedida();

        //se obtiene el listado de alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);

        //se obtiene la información de la orden de trabajo y del vehiculo
        $filtros = array(
            'Alertaordene.id' => $id,
        );

        $alertasOrdenes = $this->Alertaordene->obtnerAlertasRenovacionDocs($filtros);

        $this->set(compact('alertas'));
        $this->set(compact('unidadesMed', 'estadoAlertas', 'fechaActual', 'alertasOrdenes'));
    }
    public function alertafacturas()
    {
        $this->loadModel('Alertaordene');
        $this->loadModel('Estadoalerta');

        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //fecha actual
        $fechaAct = date('Y-m-d');

        //se obtienen las alertas y las ordenes de trabajo
        $filtros = array(
            'EA.final' => '0',
            'EA.empresa_id' => $empresa_id,
            // 'Alertaordene.fecha_alerta <= ' => $fechaAct,

            // variables para ajustar el filtro para solo facturas y prefacturas de la tabla alertaordenes
            'Alertaordene.km_prom_dia = ' => NULL,
            'Alertaordene.km_mantenimiento = ' => NULL,
            'Alertaordene.vehiculo_id = ' => NULL,
            'Alertaordene.factura_id != ' => NULL,
           
        );
        $filtrosPrefactura = array(
            'EA.final' => '0',
            'EA.empresa_id' => $empresa_id,

            // variables para ajustar el filtro para solo facturas y prefacturas de la tabla alertaordenes
            'Alertaordene.km_prom_dia = ' => NULL,
            'Alertaordene.km_mantenimiento = ' => NULL,
            'Alertaordene.vehiculo_id = ' => NULL,
            'Alertaordene.factura_id = ' => NULL,
            'Alertaordene.prefactura_id != ' => NULL,
        );

        if (!empty($this->passedArgs['estadoalerta'])) {
            $filtros['EA.id'] = $this->passedArgs['estadoalerta'];
            $filtrosPrefactura['EA.id'] = $this->passedArgs['estadoalerta'];
        }

        //se obtiene el listado de estado alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);
        $estadoAlertasTabs = $this->Estadoalerta->find('all');
        
        $alertasFacturas = $this->Alertaordene->obtenerInfoAlertaFactura($filtros);
        $alertasPreFacturas = $this->Alertaordene->obtenerInfoAlertaPreFactura($filtrosPrefactura);

        $alertaDocumentos = $this->Alertaordene->obtnerAlertasFacturaRenovacionDocs($filtros);
 
        $this->set(compact('empresa_id', 'fechaAct','alertasFacturas', 'alertasPreFacturas', 'alertasOrdenes', 'estadoAlertasTabs', 'alertaDocumentos', 'estadoAlertas'));
        $this->set(compact('alertafactura'));
       
    }

    public function editfacturas($id = null)
    {
        $this->loadModel('Alerta');
        $this->loadModel('Unidadesmedida');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Ordentrabajo');
        $this->loadModel('Factura');
        $this->loadModel('Alertaordene');
        $this->loadModel('Usuario');

       
        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //se obtiene el listado de alertas
        $alertas = $this->Alerta->obtenerListaAlertas($empresa_id);

        //Se obtienen las unidades de medida
        $unidadesMed = $this->Unidadesmedida->listaUnidadesMedida();

        //se obtiene el listado de alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);

        //se obtiene la información de la orden de trabajo y del vehiculo
        $filtros = array(
            'Alertaordene.id' => $id,
        );

        $alertaFactura = $this->Alertaordene->obtenerInfoAlertaFactura($filtros);

        $this->set(compact('alertas'));
        $this->set(compact('unidadesMed', 'estadoAlertas', 'fechaActual', 'alertaFactura'));
    }

    public function editprefacturas($id = null)
    {
        $this->loadModel('Alerta');
        $this->loadModel('Unidadesmedida');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Ordentrabajo');
        $this->loadModel('Factura');
        $this->loadModel('Alertaordene');

       
        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //se obtiene el listado de alertas
        $alertas = $this->Alerta->obtenerListaAlertas($empresa_id);

        //Se obtienen las unidades de medida
        $unidadesMed = $this->Unidadesmedida->listaUnidadesMedida();

        //se obtiene el listado de alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);

        //se obtiene la información de la orden de trabajo y del vehiculo
        $filtros = array(
            'Alertaordene.id' => $id,
        );

        $alertasPreFacturas = $this->Alertaordene->obtenerInfoAlertaPreFacturaEdit($filtros);

        $this->set(compact('alertas'));
        $this->set(compact('unidadesMed', 'estadoAlertas', 'fechaActual', 'alertasPreFacturas'));
    }


    public function editgeneral($id = null)
    {
        $this->loadModel('Alerta');
        $this->loadModel('Unidadesmedida');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Ordentrabajo');
        $this->loadModel('Factura');
        $this->loadModel('Alertaordene');

       
        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //se obtiene el listado de alertas
        $alertas = $this->Alerta->obtenerListaAlertas($empresa_id);

        //Se obtienen las unidades de medida
        $unidadesMed = $this->Unidadesmedida->listaUnidadesMedida();

        //se obtiene el listado de alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);

        //se obtiene la información de la orden de trabajo y del vehiculo
        $filtros = array(
            'Alertaordene.id' => $id,
        );

        $alertasGeneral= $this->Alertaordene->obtenerInfoAlertaGeneral($filtros);
 
        $this->set(compact('alertas'));
        $this->set(compact('unidadesMed', 'estadoAlertas', 'fechaActual', 'alertasGeneral'));
    }


    public function gestionalertasfacturas($ordenTrabajoId)
    {
        $this->loadModel('Alerta');
        $this->loadModel('Unidadesmedida');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Ordentrabajo');
        $this->loadModel('Cliente');

        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //se obtiene el listado de alertas
        $alertas = $this->Alerta->obtenerListaAlertas($empresa_id);

      
        //Se obtienen las unidades de medida
        $unidadesMed = $this->Unidadesmedida->listaUnidadesMedidaDias();

        //se obtiene el listado de alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);

        $fechaActual = date('Y-m-d');

        //se obtiene la información de la orden de trabajo y del vehiculo
        $filter['Ordentrabajo.id'] = $ordenTrabajoId;
        $infoOrdenCliV = $this->Ordentrabajo->obtenerOrdenesTrabajo($filter);

        $this->set(compact('ordenTrabajoId', 'empresa_id', 'alertas'));
        $this->set(compact('unidadesMed', 'estadoAlertas', 'fechaActual', 'infoOrdenCliV'));
    }
    public function gestionalertasfac($facturaId)
    {
        $this->loadModel('Alerta');
        $this->loadModel('Unidadesmedida');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Ordentrabajo');
        $this->loadModel('Factura');
        $this->loadModel('Usuario');

        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //se obtiene el listado de alertas
        $alertas = $this->Alerta->obtenerListaAlertasSinCumpleanos($empresa_id);

        //Se obtienen las unidades de medida
        $unidadesMed =  $this->Unidadesmedida->listaUnidadesMedidaDias();

        //se obtiene el listado de alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);

        $fechaActual = date('Y-m-d');
        //se obtiene el listado de usuarios
        $usuarios = $this->Usuario->obtenerUsuarioEmpresa($empresa_id);
        //se obtiene la información de la orden de trabajo y del vehiculo
        $filter['Factura.id'] = $facturaId;
        $infoFacturaCli = $this->Factura->obtenerInfoAlertaFacturaGenerate($filter);
        $id_factura = "";
        $id_factura = $facturaId;
    
        $this->set(compact('ordenTrabajoId', 'empresa_id', 'alertas'));
        $this->set(compact('unidadesMed', 'estadoAlertas', 'fechaActual', 'infoFacturaCli','id_factura','usuarios'));
    }
    public function gestionalertasprefac($facturaId)
    {
        $this->loadModel('Alerta');
        $this->loadModel('Unidadesmedida');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Ordentrabajo');
        $this->loadModel('Prefactura');
        $this->loadModel('Usuario');

        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //se obtiene el listado de alertas
        $alertas = $this->Alerta->obtenerListaAlertasSinCumpleanos($empresa_id);

        //se obtiene el listado de usuarios
        $usuarios = $this->Usuario->obtenerUsuarioEmpresa($empresa_id);
        
        //Se obtienen las unidades de medida
        $unidadesMed =  $this->Unidadesmedida->listaUnidadesMedidaDias();

        //se obtiene el listado de alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);

        //se obtiene el listado de usuarios
        $usuarios = $this->Usuario->obtenerUsuarioEmpresa($empresa_id);
        $fechaActual = date('Y-m-d');

        $filter['Prefactura.id'] = $facturaId;
        $id_pre_factura= "";
        $id_pre_factura= $facturaId;
        $infoFacturaCli = $this->Prefactura->obtenerInfoAlertaFacturaGenerate($filter);
        $this->set(compact('ordenTrabajoId', 'empresa_id', 'alertas'));
        $this->set(compact('unidadesMed', 'estadoAlertas', 'fechaActual', 'id_pre_factura','infoFacturaCli','id_factura','usuarios'));
    }
    public function gestionalertasgeneral()
    {
        $this->loadModel('Alerta');
        $this->loadModel('Unidadesmedida');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Ordentrabajo');
        $this->loadModel('Prefactura');
        $this->loadModel('Usuario');
        $this->loadModel('Cliente');

        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //se obtiene el listado de alertas
        $alertas = $this->Alerta->obtenerListaAlertasSinCumpleanos($empresa_id);
        
        //se obtiene el listado de usuarios
        $usuarios = $this->Usuario->obtenerUsuarioEmpresa($empresa_id);
        
        //se obtiene el listado de alertas
        $alertas = $this->Alerta->obtenerListaAlertasSinCumpleanos($empresa_id);

        //Se obtienen las unidades de medida
        $unidadesMed =  $this->Unidadesmedida->listaUnidadesMedidaDias();

        //se obtiene el listado de alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);

        $fechaActual = date('Y-m-d');

         //se obtiene el listado de clientes
         $clientes = $this->Cliente->obtenerClienteEmpresa($empresa_id);
   
        $this->set(compact('ordenTrabajoId', 'empresa_id', 'alertas'));
        $this->set(compact('unidadesMed', 'estadoAlertas', 'fechaActual','usuarios', 'id_pre_factura','infoFacturaCli','id_factura','clientes'));
    }
  
    public function alertas()
    {
        $this->loadModel('Alertaordene');
        $this->loadModel('Estadoalerta');
        $this->loadModel('Cliente');
        $this->loadModel('Usuario');

        //id de la empresa
        $empresa_id = $this->Auth->user('empresa_id');

        //fecha actual
        $fechaAct = date('Y-m-d');

        //se obtienen las alertas y las ordenes de trabajo
        $filtrosOrdenes = array(
            'EA.final' => '0',
            'EA.empresa_id' => $empresa_id,
            'Alertaordene.ordentrabajo_id != ' => NULL,
        );
      
        //se obtienen las alertas por factura
        $filtrosFactura = array(
            'EA.final' => '0',
            'EA.empresa_id' => $empresa_id,
          

            // variables para ajustar el filtro para solo facturas y prefacturas de la tabla alertaordenes
            'Alertaordene.km_prom_dia = ' => NULL,
            'Alertaordene.km_mantenimiento = ' => NULL,
            'Alertaordene.vehiculo_id = ' => NULL,
            'Alertaordene.factura_id != ' => NULL,
        );

        //se obtienen las alertas por prefactura
        
        $filtrosPrefactura = array(
            'EA.final' => '0',
            'EA.empresa_id' => $empresa_id,
           

            // variables para ajustar el filtro para solo facturas y prefacturas de la tabla alertaordenes
            'Alertaordene.km_prom_dia = ' => NULL,
            'Alertaordene.km_mantenimiento = ' => NULL,
            'Alertaordene.vehiculo_id = ' => NULL,
            'Alertaordene.factura_id = ' => NULL,
            'Alertaordene.prefactura_id != ' => NULL,
        );

        $filtrosGeneral = array(
            'EA.final' => '0',
            'EA.empresa_id' => $empresa_id,

            // variables para ajustar el filtro para solo facturas y prefacturas de la tabla alertaordenes
            'Alertaordene.km_prom_dia = ' => NULL,
            'Alertaordene.km_mantenimiento = ' => NULL,
            'Alertaordene.vehiculo_id = ' => NULL,
            'Alertaordene.ordentrabajo_id = ' => NULL,
            'Alertaordene.factura_id = ' => NULL,
            'Alertaordene.prefactura_id = ' => NULL,
        );


        // Filtro para los tabs, se filtra por el id del estado de la alerta en todos los filtros de las tablas 
        if (!empty($this->passedArgs['estadoalerta'])) {
            $filtrosOrdenes['EA.id'] = $this->passedArgs['estadoalerta'];
            $filtrosFactura['EA.id'] = $this->passedArgs['estadoalerta'];
            $filtrosPrefactura['EA.id'] = $this->passedArgs['estadoalerta'];
            $filtrosGeneral['EA.id'] = $this->passedArgs['estadoalerta'];
        }

        //se obtiene el listado de estado alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);
        $estadoAlertasTabs = $this->Estadoalerta->find('all');
     
        //se obtiene el listado de alertas por orden de trabajo
        $alertasOrdenes = $this->Alertaordene->obtenerInfoAlertaOrden($filtrosOrdenes);
        //se obtiene el listado de alertas por factura
        $alertasFacturas = $this->Alertaordene->obtenerInfoAlertaFactura($filtrosFactura);
        //se obtiene el listado de alertas por Pre factura
        $alertasPreFacturas = $this->Alertaordene->obtenerInfoAlertaPreFactura($filtrosPrefactura);
       
        $alertasGeneral = $this->Alertaordene->obtenerInfoAlertaGeneral($filtrosGeneral);

        $this->set(compact('estadoAlertas','alertasOrdenes','alertasFacturas','alertasGeneral','alertasPreFacturas', 'estadoAlertasTabs'));
    }

}

