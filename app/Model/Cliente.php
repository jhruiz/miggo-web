<?php
App::uses('AppModel', 'Model');
/**
 * Cliente Model
 *
 * @property Ciudade $Ciudade
 * @property Usuario $Usuario
 * @property Estado $Estado
 * @property Anotacione $Anotacione
 * @property Deposito $Deposito
 */
class Cliente extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nombre';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'ciudade_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'usuario_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'estado_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		// Datos tributarios (retenciones en ventas)
		'tipo_persona' => array(
			'inList' => array(
				'rule' => array('inList', array('NATURAL', 'JURIDICA', 'ENTIDAD_PUBLICA')),
				'allowEmpty' => true,
				'message' => 'Tipo de persona no válido.',
			),
		),
		'regimene_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'allowEmpty' => true,
				'message' => 'Régimen no válido.',
			),
		),
		'practica_retefuente' => array(
			'boolean' => array('rule' => array('boolean'), 'allowEmpty' => true),
		),
		'practica_reteiva' => array(
			'boolean' => array('rule' => array('boolean'), 'allowEmpty' => true),
		),
		'practica_reteica' => array(
			'boolean' => array('rule' => array('boolean'), 'allowEmpty' => true),
		),
		'datos_tributarios_revisados' => array(
			'boolean' => array('rule' => array('boolean'), 'allowEmpty' => true),
		),
	);

	/** Tipos de identificación que llevan dígito de verificación (cédula de ciudadanía y NIT) */
	protected $_tiposConDv = array(3, 6);

	/**
	 * Dígito de verificación (DIAN, módulo 11) de un NIT o cédula.
	 *
	 * Acepta el número con puntos, espacios o con el DV ya escrito ("900123456-7").
	 * Devuelve null si el valor no es un número válido (letras, muy corto o muy largo).
	 *
	 * @param  string $nit
	 * @return int|null
	 */
	public static function calcularDv($nit) {
		$nit = trim((string) $nit);
		if (strpos($nit, '-') !== false) {
			$nit = substr($nit, 0, strpos($nit, '-'));
		}
		$nit = str_replace(array('.', ',', ' '), '', $nit);
		if (!preg_match('/^\d{3,15}$/', $nit)) {
			return null;
		}
		$pesos = array(3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71);
		$suma = 0;
		$digitos = strrev($nit);
		for ($i = 0, $n = strlen($digitos); $i < $n; $i++) {
			$suma += ((int) $digitos[$i]) * $pesos[$i];
		}
		$resto = $suma % 11;
		return $resto > 1 ? 11 - $resto : $resto;
	}

	/**
	 * Antes de validar: ReteICA necesita la ciudad del cliente, porque la tarifa
	 * depende del municipio donde está el cliente.
	 */
	public function beforeValidate($options = array()) {
		$d = isset($this->data[$this->alias]) ? $this->data[$this->alias] : array();
		if (!empty($d['practica_reteica'])) {
			$ciudad = array_key_exists('ciudadesmiggo_id', $d) ? $d['ciudadesmiggo_id'] : null;
			if (!array_key_exists('ciudadesmiggo_id', $d) && !empty($d['id'])) {
				$ciudad = $this->field('ciudadesmiggo_id', array($this->alias . '.id' => $d['id']));
			}
			if (empty($ciudad)) {
				$this->invalidate('practica_reteica', 'Para ReteICA el cliente necesita una ciudad: selecciónela arriba.');
			}
		}
		return true;
	}

	/**
	 * Mantiene el dígito de verificación al guardar el NIT o el tipo de identificación.
	 */
	public function beforeSave($options = array()) {
		$d =& $this->data[$this->alias];
		if (array_key_exists('nit', $d) || array_key_exists('tipoidentificacione_id', $d)) {
			$id = !empty($d['id']) ? $d['id'] : $this->id;
			$nit = array_key_exists('nit', $d) ? $d['nit'] : (empty($id) ? null : $this->field('nit', array($this->alias . '.id' => $id)));
			$tipo = array_key_exists('tipoidentificacione_id', $d) ? $d['tipoidentificacione_id'] : (empty($id) ? 3 : $this->field('tipoidentificacione_id', array($this->alias . '.id' => $id)));

			$d['dv'] = in_array((int) $tipo, $this->_tiposConDv, true) ? self::calcularDv($nit) : null;
		}
		return true;
	}

	/**
	 * Enlaza las responsabilidades fiscales del RUT al cliente (solo donde se necesita).
	 */
	public function vincularResponsabilidades() {
		$this->bindModel(array('hasAndBelongsToMany' => array(
			'Tiposresponsabilidade' => array(
				'className'             => 'Tiposresponsabilidade',
				'joinTable'             => 'clientes_tiposresponsabilidades',
				'foreignKey'            => 'cliente_id',
				'associationForeignKey' => 'tiposresponsabilidade_id',
				'unique'                => 'keepExisting',
			),
		)), false);
	}

	/**
	 * Ids de las responsabilidades fiscales de un cliente.
	 *
	 * @param  int $clienteId
	 * @return array
	 */
	public function obtenerResponsabilidadesIds($clienteId) {
		$filas = $this->query(
			'SELECT tiposresponsabilidade_id FROM clientes_tiposresponsabilidades WHERE cliente_id = ?',
			array((int) $clienteId)
		);
		$ids = array();
		foreach ($filas as $f) {
			$ids[] = (int) $f['clientes_tiposresponsabilidades']['tiposresponsabilidade_id'];
		}
		return $ids;
	}

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Ciudadesmiggo' => array(
			'className' => 'Ciudadesmiggo',
			'foreignKey' => 'ciudadesmiggo_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Estado' => array(
			'className' => 'Estado',
			'foreignKey' => 'estado_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Anotacione' => array(
			'className' => 'Anotacione',
			'foreignKey' => 'cliente_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Deposito' => array(
			'className' => 'Deposito',
			'joinTable' => 'depositos_clientes',
			'foreignKey' => 'cliente_id',
			'associationForeignKey' => 'deposito_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);
        
        public function obtenerDatosCliente($nombre,$empresaId){
            $arrDatosCliente = $this->find('all', array(
                'conditions' => array(
                    'OR' => array(
                        'LOWER(Cliente.nombre) LIKE' => '%'. $nombre . '%',
                        'LOWER(Cliente.nit) LIKE' => '%'. $nombre . '%'
                    ),
                    'Cliente.empresa_id' => $empresaId                    
                ), 
                'order' => 'Cliente.nombre',
                'recursive' => '-1'));

            return $arrDatosCliente;
        }
        
        public function obtenerInformacionCliente($clienteId){
            $arrInfoCliente = $this->find('first', array('conditions' => array('Cliente.id' => $clienteId), 'recursive' => '0'));
            return $arrInfoCliente;
        }
        public function obtenerInformacionClienteFecha($clienteId){
            $clientes = $this->find('all', array(
                
                'fields' => array(
                    'cumpleanios',
                ),
                'conditions' => array('Cliente.id' => $clienteId),
                'recursive' => '-1',
                // 'group' => array('F.cliente_id', 'V.placa'),
                // 'order' => 'cta desc'
                ));  
            return $clientes;
        }

        public function guardarClienteNuevo($nit,$nombre,$dir,$tel,$ciudad,$pagweb,$email,$cel,$diasCred,$limCred,$cumple,$usrId,$estId,$empId){
            $data = array();
            
            $cliente = new Cliente();
            
            $data['nit'] = $nit;
            $data['nombre'] = $nombre;
            $data['direccion'] = $dir;
            $data['telefono'] = $tel;
            $data['ciudade_id'] = $ciudad;
            $data['paginaweb'] = $pagweb;
            $data['email'] = $email;
            $data['celular'] = $cel;            
            $data['diascredito'] = $diasCred;
            $data['limitecredito'] = $limCred;
            $data['cumpleanios'] = $cumple;
            $data['usuario_id'] = $usrId;
            $data['estado_id'] = $estId;
            $data['empresa_id'] = $empId;
            
            if($cliente->save($data)){
                return $cliente->id;
            }else{
                return false;
            }                    
        }
        
        public function obtenerClienteEmpresa($empresaId){
            $listClientes = $this->find('list', array('conditions' => array('Cliente.empresa_id' => $empresaId), 'recursive' => '-1', 'order' => 'Cliente.nombre'));
            return $listClientes;
        }
        
        public function obtenerInfoClientes($empresaId){
            $infoClientes = $this->find('all', array('conditions' => array('Cliente.empresa_id' => $empresaId), 'recursive' => '-1'));
            return $infoClientes;                    
        }
        
        public function guardarClienteCotizacion($data){
            
            $cliente = new Cliente();          
            
            if($cliente->save($data)){
                return $cliente->id;
            }else{
                return false;
            }             
        }
        
        public function obtenerClientePorIdentificacion($identificacion, $empresaId){
            $infoCliente = $this->find('first', array('conditions' => array('Cliente.nit' => $identificacion, 'Cliente.empresa_id' => $empresaId), 'recursive' => '-1'));
            return $infoCliente;
        }
        
        public function obtenerClientesQR($empresaId){
            $arr_join = array(); 
            
            array_push($arr_join, array(
                'table' => 'facturas', 
                'alias' => 'F', 
                'type' => 'INNER',
                'conditions' => array(
                    'F.cliente_id=Cliente.id'
                    )                
            ));
            
            array_push($arr_join, array(
                'table' => 'ordentrabajos', 
                'alias' => 'O', 
                'type' => 'INNER',
                'conditions' => array(
                    'O.id=F.ordentrabajo_id'
                    )                
            ));
            
            array_push($arr_join, array(
                'table' => 'vehiculos', 
                'alias' => 'V', 
                'type' => 'INNER',
                'conditions' => array(
                    'V.id=O.vehiculo_id'
                    )                
            ));


            $clientes = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'Count(F.cliente_id) as cta',
                    'Cliente.nombre',
                    'Cliente.celular',
                    'Cliente.telefono',
                    'V.placa',
                ),
                'conditions' => array('Cliente.empresa_id' => $empresaId),
                'recursive' => '-1',
                'group' => array('F.cliente_id', 'V.placa'),
                'order' => 'cta desc'
                ));            

            return $clientes;             
        }

        public function obtenerClientesReporte($empresaId, $filtros)
        {
            $arr_join = array();
            array_push($arr_join, array(
                'table' => 'ciudades',
                'alias' => 'CU',
                'type' => 'INNER',
                'conditions' => array(
                    'CU.id=Cliente.ciudade_id'),
    
            ));
            array_push($arr_join, array(
                'table' => 'estados',
                'alias' => 'E',
                'type' => 'INNER',
                'conditions' => array(
                    'E.id=Cliente.estado_id'),
    
            ));
            array_push($arr_join, array(
                'table' => 'clasificacionclientes',
                'alias' => 'C',
                'type' => 'LEFT',
                'conditions' => array(
                    'C.id=Cliente.clasificacioncliente_id'),
    
            ));

            $arrProductos = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'Cliente.*', 
                    'CU.descripcion',
                    'E.descripcion',
                    'C.descripcion',
                ),
                'conditions' => array(
                    $filtros,
                    'Cliente.empresa_id' => $empresaId,
                ),
                'recursive' => -1,
            ));
    
            return $arrProductos;
    
        }

}
