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

        $this->set(compact('ordenTrabajoId', 'empresa_id', 'alertas'));
        $this->set(compact('unidadesMed', 'estadoAlertas', 'fechaActual', 'infoOrdenCliV'));
    }

    public function guardaralertas()
    {
        $this->loadModel('Alertaordene');
        $this->autoRender = false;
        $postData = $this->request->data;

        $resp = $this->Alertaordene->guardaralerta($postData);
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
        ));

    
        // echo('<pre>');
        // var_dump($tecnicoSelect);
        // echo('</pre>');

        //se obtiene el listado de estado alertas
        $estadoAlertas = $this->Estadoalerta->obtenerListaEstadoAlertas($empresa_id);
        $estadoAlertasTabs = $this->Estadoalerta->find('all');
        $alertasOrdenes = $this->Alertaordene->obtenerInfoAlertaOrden($filtros);
        $alertas = $this->Alerta->obtenerListaAlertas($empresa_id);
        $tecnicosSelect = $this->Usuario->obtenerUsuarioEmpresa($empresa_id);
        $alertaDocumentos = $this->Alertaordene->obtnerAlertasRenovacionDocs($filtros);
       
        $this->set(compact('empresa_id', 'fechaAct', 'alertasOrdenes', 'estadoAlertasTabs', 'alertaDocumentos', 'estadoAlertas','tipoAlerta','cliente','placa','tecnico','alertas','tecnicosSelect','tecnicoSelect'));
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
}