<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
App::uses('Validation', 'Utility');
/**
 * Impuestos Controller
 *
 * @property Impuesto $Impuesto
 * @property PaginatorComponent $Paginator
 */
class ImpuestosController extends AppController {

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
            	
		$empresaId = $this->Auth->user('empresa_id');
		$paginate['Impuesto.empresa_id'] = $empresaId; 
		// Las retenciones se listan en su propia pantalla
		$paginate['Impuesto.clase'] = 'IMPUESTO';
		$this->Impuesto->recursive = 0;

		$this->set('impuestos', $this->Paginator->paginate('Impuesto',$paginate));
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
            	
		if (!$this->Impuesto->exists($id)) {
			throw new NotFoundException(__('El impuesto no existe.'));
		}
		$options = array('conditions' => array('Impuesto.' . $this->Impuesto->primaryKey => $id));
		$this->set('impuesto', $this->Impuesto->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel('Taxe');

		/*se reagistra la actividad del uso de la aplicacion*/
		$usuariosController = new UsuariosController();
		$usuarioAct = $this->Auth->user('id');
		$usuariosController->registraractividad($usuarioAct);
            	
		if ($this->request->is('post')) {
			$this->Impuesto->create();
			if ($this->Impuesto->save($this->request->data)) {
				$this->Session->setFlash(__('El impuesto ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El impuesto no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		}
		$arrEmpresa = $this->Auth->user('Empresa');
		$empresaId = $arrEmpresa['id'];
		$taxes = $this->Taxe->obtenerListadoImp();
                
		$this->set(compact('empresaId', 'taxes'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->loadModel('Taxe');

		/*se reagistra la actividad del uso de la aplicacion*/
		$usuariosController = new UsuariosController();
		$usuarioAct = $this->Auth->user('id');
		$usuariosController->registraractividad($usuarioAct);
            	
		if (!$this->Impuesto->exists($id)) {
			throw new NotFoundException(__('El impuesto no existe.'));
		}
		// Las retenciones no se editan desde este formulario
		if ($this->Impuesto->field('clase', array('Impuesto.id' => $id)) === 'RETENCION') {
			$this->Session->setFlash(__('Las retenciones se gestionan desde su propia pantalla.'));
			return $this->redirect(array('action' => 'index'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Impuesto->save($this->request->data)) {
				$this->Session->setFlash(__('El impuesto ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El impuesto no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Impuesto.' . $this->Impuesto->primaryKey => $id));
			$this->request->data = $this->Impuesto->find('first', $options);
		}
		$arrEmpresa = $this->Auth->user('Empresa');
		$empresaId = $arrEmpresa['id'];
		$taxes = $this->Taxe->obtenerListadoImp();
                
		$this->set(compact('empresaId', 'taxes'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Impuesto->id = $id;
		if (!$this->Impuesto->exists()) {
			throw new NotFoundException(__('El impuesto no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		// Las retenciones no se borran desde aquí (las usadas en facturas solo se desactivan)
		if ($this->Impuesto->field('clase', array('Impuesto.id' => $id)) === 'RETENCION') {
			$this->Session->setFlash(__('Las retenciones se gestionan desde su propia pantalla.'));
			return $this->redirect(array('action' => 'index'));
		}
		if ($this->Impuesto->delete()) {
			$this->Session->setFlash(__('El impuesto ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El impuesto no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	// ─────────────────────────────────────────────────────────────────────────
	// RETENCIONES EN VENTAS
	//
	// Filas de clase RETENCION de la tabla impuestos, gestionadas con el modelo
	// Retencionventa (validaciones de la sección 3.3 del documento de requerimientos).
	// Siempre se trabaja con la empresa del usuario logueado.
	// ─────────────────────────────────────────────────────────────────────────

	/** Campos que el usuario puede enviar desde el formulario de retención */
	private $_camposRetencion = array(
		'nombre', 'tax_id', 'aplica_a', 'tipo_valor', 'ciudadesmiggo_id', 'condicion_declarante',
		'valor', 'base_minima_uvt', 'cuenta_contable', 'vigente_desde', 'vigente_hasta', 'activo',
	);

	/** Campos editables cuando la retención ya se usó en facturas */
	private $_camposEditablesEnUso = array('nombre', 'cuenta_contable', 'vigente_hasta', 'activo');

	/**
	 * Listado de retenciones con filtros por tributo, ciudad y "vigentes hoy".
	 */
	public function retenciones() {
		$this->_registrarActividad();
		$this->loadModel('Retencionventa');
		$this->loadModel('Configuraciondato');

		$empresaId = $this->Auth->user('empresa_id');
		$filtros = array(
			'tax_id'   => isset($this->request->query['tax_id']) ? $this->request->query['tax_id'] : '',
			'ciudad'   => isset($this->request->query['ciudad']) ? $this->request->query['ciudad'] : '',
			'vigentes' => isset($this->request->query['vigentes']) ? $this->request->query['vigentes'] : '',
		);

		$conditions = array('Retencionventa.empresa_id' => $empresaId);
		if ($filtros['tax_id'] !== '') {
			$conditions['Retencionventa.tax_id'] = (int) $filtros['tax_id'];
		}
		if ($filtros['ciudad'] !== '') {
			$conditions['Retencionventa.ciudadesmiggo_id'] = (int) $filtros['ciudad'];
		}
		if ($filtros['vigentes'] === '1') {
			$hoy = date('Y-m-d');
			$conditions['Retencionventa.activo'] = 1;
			$conditions['Retencionventa.vigente_desde <='] = $hoy;
			$conditions[] = array('OR' => array(
				'Retencionventa.vigente_hasta' => null,
				'Retencionventa.vigente_hasta >=' => $hoy,
			));
		}

		$retenciones = $this->Retencionventa->find('all', array(
			'conditions' => $conditions,
			'order'      => array('Taxe.code' => 'ASC', 'Retencionventa.aplica_a' => 'ASC', 'Retencionventa.vigente_desde' => 'DESC'),
			'recursive'  => 0,
		));
		foreach ($retenciones as $i => $r) {
			$retenciones[$i]['enUso'] = $this->Retencionventa->estaEnUso($r['Retencionventa']['id']);
		}

		// Ciudades que tienen alguna ReteICA configurada (para el filtro)
		$conCiudad = $this->Retencionventa->find('all', array(
			'conditions' => array('Retencionventa.empresa_id' => $empresaId, 'Retencionventa.ciudadesmiggo_id IS NOT NULL'),
			'fields'     => array('Ciudadesmiggo.id', 'Ciudadesmiggo.nombre'),
			'recursive'  => 0,
		));
		$ciudadesFiltro = array();
		foreach ($conCiudad as $c) {
			$ciudadesFiltro[$c['Ciudadesmiggo']['id']] = $c['Ciudadesmiggo']['nombre'];
		}
		asort($ciudadesFiltro);

		$uvtAnio = (int) date('Y');
		$uvt = $this->Configuraciondato->obtenerUvtAnio($uvtAnio);
		$tributos = $this->_tributosRetencion();

		$this->set(compact('retenciones', 'filtros', 'tributos', 'ciudadesFiltro', 'uvt', 'uvtAnio'));
	}

	/**
	 * Crear una retención.
	 */
	public function addretencion() {
		$this->_registrarActividad();
		$this->loadModel('Retencionventa');

		if ($this->request->is('post')) {
			$data = $this->_datosRetencion($this->request->data);
			$data['empresa_id'] = $this->Auth->user('empresa_id');

			$this->Retencionventa->create();
			if ($this->Retencionventa->save(array('Retencionventa' => $data))) {
				$this->Session->setFlash(__('La retención ha sido guardada.'));
				return $this->redirect(array('action' => 'retenciones'));
			}
			$this->Session->setFlash(__('La retención no pudo ser guardada. Revise los campos marcados.'));
		} else {
			$this->request->data = array('Retencionventa' => array(
				'aplica_a'             => 'PRODUCTO',
				'tipo_valor'           => 'PORCENTAJE',
				'condicion_declarante' => 'TODOS',
				'base_minima_uvt'      => '0',
				'vigente_desde'        => date('Y-m-d'),
				'activo'               => 1,
			));
		}

		$this->_variablesFormularioRetencion(array(), false);
	}

	/**
	 * Editar una retención. Si ya se usó en facturas, solo se pueden cambiar
	 * nombre, cuenta contable, vigente hasta y estado.
	 */
	public function editretencion($id = null) {
		$this->_registrarActividad();
		$this->loadModel('Retencionventa');

		$empresaId = $this->Auth->user('empresa_id');
		$retencion = $this->Retencionventa->obtenerRetencionPorId($id, $empresaId);
		if (empty($retencion)) {
			$this->Session->setFlash(__('La retención no existe.'));
			return $this->redirect(array('action' => 'retenciones'));
		}
		$enUso = $this->Retencionventa->estaEnUso($id);

		if ($this->request->is(array('post', 'put'))) {
			$data = $this->_datosRetencion($this->request->data);
			if ($enUso) {
				$data = array_intersect_key($data, array_flip($this->_camposEditablesEnUso));
			}
			$data['id'] = (int) $id;

			if ($this->Retencionventa->save(array('Retencionventa' => $data))) {
				$this->Session->setFlash(__('La retención ha sido guardada.'));
				return $this->redirect(array('action' => 'retenciones'));
			}
			$this->Session->setFlash(__('La retención no pudo ser guardada. Revise los campos marcados.'));
			// Completar lo que no viene en el POST (campos deshabilitados) para volver a pintar el formulario
			$this->request->data['Retencionventa'] = (isset($this->request->data['Retencionventa']) ? $this->request->data['Retencionventa'] : array()) + $retencion['Retencionventa'];
		} else {
			$this->request->data = $retencion;
		}

		$this->_variablesFormularioRetencion($retencion, $enUso);
	}

	/**
	 * Nueva vigencia: cierra la retención actual el día anterior a la nueva fecha
	 * y crea una fila nueva con la misma combinación (tributo, tipo de ítem,
	 * ciudad y condición) y la nueva tarifa / base mínima. Es la forma de
	 * cambiar una tarifa sin alterar las facturas ya emitidas.
	 */
	public function nuevavigencia($id = null) {
		$this->_registrarActividad();
		$this->loadModel('Retencionventa');

		$empresaId = $this->Auth->user('empresa_id');
		$anterior = $this->Retencionventa->obtenerRetencionPorId($id, $empresaId);
		if (empty($anterior)) {
			$this->Session->setFlash(__('La retención no existe.'));
			return $this->redirect(array('action' => 'retenciones'));
		}
		$ant = $anterior['Retencionventa'];

		if ($this->request->is(array('post', 'put'))) {
			$post = $this->_datosRetencion($this->request->data);

			// La combinación se conserva; solo cambian tarifa, base mínima, vigencia y datos descriptivos
			$nueva = array(
				'empresa_id'           => $empresaId,
				'tax_id'               => $ant['tax_id'],
				'aplica_a'             => $ant['aplica_a'],
				'tipo_valor'           => $ant['tipo_valor'],
				'ciudadesmiggo_id'     => $ant['ciudadesmiggo_id'],
				'condicion_declarante' => $ant['condicion_declarante'],
			);
			foreach (array('nombre', 'valor', 'base_minima_uvt', 'cuenta_contable', 'vigente_desde', 'vigente_hasta', 'activo') as $campo) {
				$nueva[$campo] = isset($post[$campo]) ? $post[$campo] : null;
			}

			$desde = $nueva['vigente_desde'];
			if (empty($desde) || !Validation::date($desde, 'ymd') || $desde <= $ant['vigente_desde']) {
				$this->Session->setFlash(__('La nueva vigencia debe empezar después del %s.', $ant['vigente_desde']));
			} else {
				$db = $this->Retencionventa->getDataSource();
				$db->begin();
				$ok = true;

				// Cerrar la anterior el día antes, si su vigencia alcanza la nueva fecha
				if (empty($ant['vigente_hasta']) || $ant['vigente_hasta'] >= $desde) {
					$this->Retencionventa->id = (int) $id;
					$ok = (bool) $this->Retencionventa->saveField('vigente_hasta', date('Y-m-d', strtotime($desde . ' -1 day')));
				}
				if ($ok) {
					$this->Retencionventa->create();
					$ok = (bool) $this->Retencionventa->save(array('Retencionventa' => $nueva));
				}

				if ($ok) {
					$db->commit();
					$this->Session->setFlash(__('Nueva vigencia creada. La anterior quedó vigente hasta el %s.', date('Y-m-d', strtotime($desde . ' -1 day'))));
					return $this->redirect(array('action' => 'retenciones'));
				}
				$db->rollback();
				$this->Session->setFlash(__('No se pudo crear la nueva vigencia. Revise los campos marcados.'));
			}
			$this->request->data['Retencionventa'] = $nueva + $ant;
		} else {
			$this->request->data = $anterior;
			$this->request->data['Retencionventa']['vigente_desde'] = '';
			$this->request->data['Retencionventa']['vigente_hasta'] = '';
		}

		$this->_variablesFormularioRetencion($anterior, false);
		$this->set('anterior', $ant);
	}

	/**
	 * Borrar una retención (solo si no se ha usado en facturas).
	 */
	public function eliminarretencion($id = null) {
		$this->request->onlyAllow('post');
		$this->loadModel('Retencionventa');

		$res = $this->Retencionventa->eliminarRetencion($id, $this->Auth->user('empresa_id'));
		$this->Session->setFlash(__($res['msg']));
		return $this->redirect(array('action' => 'retenciones'));
	}

	/**
	 * Activar o desactivar una retención.
	 */
	public function estadoretencion($id = null) {
		$this->request->onlyAllow('post');
		$this->loadModel('Retencionventa');

		$empresaId = $this->Auth->user('empresa_id');
		$retencion = $this->Retencionventa->obtenerRetencionPorId($id, $empresaId);
		if (empty($retencion)) {
			$this->Session->setFlash(__('La retención no existe.'));
			return $this->redirect(array('action' => 'retenciones'));
		}

		$activar = (int) $retencion['Retencionventa']['activo'] !== 1;
		if ($this->Retencionventa->cambiarEstado($id, $empresaId, $activar)) {
			$this->Session->setFlash($activar ? __('Retención activada.') : __('Retención desactivada.'));
		} else {
			$mensajes = array();
			foreach ($this->Retencionventa->validationErrors as $m) {
				$mensajes = array_merge($mensajes, (array) $m);
			}
			$this->Session->setFlash(__('No se pudo cambiar el estado. ') . implode(' ', array_unique($mensajes)));
		}
		return $this->redirect(array('action' => 'retenciones'));
	}

	// ── Auxiliares de retenciones ───────────────────────────────────────────

	/** Registra la actividad del usuario (mismo patrón del resto del controlador) */
	private function _registrarActividad() {
		$usuariosController = new UsuariosController();
		$usuariosController->registraractividad($this->Auth->user('id'));
	}

	/** Toma del POST solo los campos permitidos de la retención */
	private function _datosRetencion($requestData) {
		$post = isset($requestData['Retencionventa']) ? $requestData['Retencionventa'] : array();
		$data = array_intersect_key($post, array_flip($this->_camposRetencion));
		if (array_key_exists('activo', $data)) {
			$data['activo'] = !empty($data['activo']) ? 1 : 0;
		}
		return $data;
	}

	/** Tributos de retención como "ReteIVA (05)" => id */
	private function _tributosRetencion() {
		$this->loadModel('Taxe');
		$filas = $this->Taxe->find('all', array(
			'conditions' => array('Taxe.code' => array('05', '06', '07')),
			'order'      => 'Taxe.code ASC',
			'recursive'  => -1,
		));
		$tributos = array();
		foreach ($filas as $t) {
			$tributos[$t['Taxe']['id']] = $t['Taxe']['name'] . ' (' . $t['Taxe']['code'] . ')';
		}
		return $tributos;
	}

	/** Departamentos de Colombia y ciudades del departamento seleccionado */
	private function _variablesUbicacion($dptoId, $ciudadId) {
		$this->loadModel('Paisesmiggo');
		$this->loadModel('Departamentosmiggo');
		$this->loadModel('Ciudadesmiggo');

		if (empty($dptoId) && !empty($ciudadId)) {
			$dptoId = $this->Ciudadesmiggo->field('departamento_id', array('Ciudadesmiggo.id' => (int) $ciudadId));
		}
		$colombiaId = $this->Paisesmiggo->field('id', array('Paisesmiggo.nombre' => 'Colombia'));
		$departamentos = $colombiaId ? $this->Departamentosmiggo->obtenerDptosPais($colombiaId) : array();
		asort($departamentos);
		$ciudades = !empty($dptoId) ? $this->Ciudadesmiggo->obtenerCiudadesDpto($dptoId) : array();
		asort($ciudades);

		$this->set(array(
			'departamentos'     => $departamentos,
			'ciudades'          => $ciudades,
			'dptoSeleccionado'  => $dptoId,
		));
	}

	/** Variables comunes de los formularios de retención */
	private function _variablesFormularioRetencion($retencion, $enUso) {
		$this->loadModel('Configuraciondato');
		$this->loadModel('Taxe');

		$tributos = $this->_tributosRetencion();
		$tributosCodigo = $this->Taxe->find('list', array(
			'fields'     => array('Taxe.id', 'Taxe.code'),
			'conditions' => array('Taxe.code' => array('05', '06', '07')),
			'recursive'  => -1,
		));

		$d = isset($this->request->data['Retencionventa']) ? $this->request->data['Retencionventa'] : array();
		$this->_variablesUbicacion(
			isset($d['departamento_id']) ? $d['departamento_id'] : null,
			isset($d['ciudadesmiggo_id']) ? $d['ciudadesmiggo_id'] : null
		);

		$uvtAnio = (int) date('Y');
		$uvt = $this->Configuraciondato->obtenerUvtAnio($uvtAnio);

		$this->set(compact('tributos', 'tributosCodigo', 'uvt', 'uvtAnio', 'enUso'));
	}
}
