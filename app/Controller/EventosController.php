<?php
App::uses('AppController', 'Controller');

class EventosController extends AppController {

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

    $this->loadModel('Tipoevento');
    $this->loadModel('Usuario');
    $this->loadModel('Estadoalerta');

    $this->Evento->recursive = 0;
    $fechaAct = date('Y-m-d');
    $empresaId = $this->Auth->user('empresa_id');

    $tipoEventos = $this->Tipoevento->obtenerTipoEventos();
    $usuarios = $this->Usuario->obtenerUsuarioEmpresa($empresaId); 
    $estados = $this->Estadoalerta->obtenerListaEstadoAlertas($empresaId);   

    $responsable = "";
    $tipoEvento = "";
    $estado = "";
    // se crea los filtros de busqueda

    if (isset($this->passedArgs['responsable']) && $this->passedArgs['responsable'] != "") {
        $filtros['LOWER(U.nombre) LIKE'] = '%' . strtolower($this->passedArgs['responsable']) . '%';
        $responsable = $this->passedArgs['responsable'];
    }
    if (isset($this->passedArgs['tipoEvento']) && $this->passedArgs['tipoEvento'] != "") {
        $filtros['LOWER(Evento.tipoevento_id) LIKE'] = '%' . strtolower($this->passedArgs['tipoEvento']) . '%';
        $tipoEvento = $this->passedArgs['tipoEvento'];
    }

    // Se valida si se envía estado alerta, se pasa el id de la alerta para realizar la consulta correspondiente a los tabs. 
    $idEstado = $this->passedArgs['estadoalerta'];
            
    if ($idEstado){
        $filtros['LOWER(U.nombre) LIKE'] = '%' . strtolower($this->passedArgs['responsable']) . '%';
        $filtros['LOWER(Evento.tipoevento_id) LIKE'] = '%' . strtolower($this->passedArgs['tipoEvento']) . '%';
        $eventosIndex = $this->Evento->obtenerEventosIndexBusqueda($empresaId,$idEstado, $filtros); 
    }
    else{
        $eventosIndex = $this->Evento->obtenerEventosIndex($empresaId, $filtros); 
    }

    $estadosTab = $this->Estadoalerta->obtenerListaEstadoTest($empresaId);
    $this->set(compact('eventosIndex','tipoEventos', 'usuarios', 'estados','estadosTab','fechaAct','responsable','tipoEvento','estado')); 
}

/**
 * add method
 *
 * @return void
 */
	public function crearevento() {
        
        $this->loadModel('Usuario');
        $this->loadModel('Tipoevento');
        $this->loadModel('Estadoalerta');        

        $empresaId = $this->Auth->user('empresa_id');

        //Se obtiene el estado inicial de la alerta
        $estado = $this->Estadoalerta->obtenerEstadoAlertaInicial($empresaId);

        $usuarios = $this->Usuario->obtenerUsuarioEmpresa($empresaId);

        $tipoEventos = $this->Tipoevento->obtenerTipoEventos();

		$this->set(compact('empresaId', 'usuarios', 'tipoEventos', 'estado')); 	

    }
    
    public function guardarevento(){        
        $this->loadModel('Evento');

		$this->autoRender = false;
        $posData = $this->request->data; 
        
        $data = [
            'tipoevento_id' => $posData['tEvento'],
            'usuario_id' => $posData['user_id'],
            'empresa_id' => $posData['empresaId'],
            'fecha' => $posData['date_event'],
            'descripcion' => $posData['desc_event'],
            'cliente' => $posData['client_name'],
            'telefono' => $posData['client_tel'],
            'placa' => $posData['placa'],
            'estadoalerta_id' => $posData['estadoId']
        ];
            
        $resp = $this->Evento->guardareveto($data);

        echo json_encode(array('resp' => $resp)); 
    }

	public function edit($id = null) {

        $this->loadModel('Estadoalerta');
        $this->loadModel('Usuario');
        $this->loadModel('Tipoevento');

        if (!$this->Evento->exists($id)) {
            throw new NotFoundException(__('El evento no existe.'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Evento->save($this->request->data)) {
                $this->Session->setFlash(__('El evento ha sido guardado con éxito.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('El evento no pudo ser guardado. Por favor, inténtelo de nuevo.'));
            }
        } else {
            $options = array('conditions' => array('Evento.' . $this->Evento->primaryKey => $id));
            $this->request->data = $this->Evento->find('first', $options);
        }

        $evento = $this->request->data;

        $empresaId = $this->Auth->user('empresa_id');

        //Se obtiene el estado inicial de la alerta
        $estado = $this->Estadoalerta->obtenerListaEstadoAlertas($empresaId);

        $usuarios = $this->Usuario->obtenerUsuarioEmpresa($empresaId);

        $tipoEventos = $this->Tipoevento->obtenerTipoEventos();

		$this->set(compact('evento', 'empresaId', 'estado', 'usuarios', 'tipoEventos'));        
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
}
