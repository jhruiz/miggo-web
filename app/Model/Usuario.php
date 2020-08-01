<?php
App::uses('AppModel', 'Model');
/**
 * Usuario Model
 *
 * @property Perfile $Perfile
 * @property Estado $Estado
 * @property Empresa $Empresa
 * @property Anotacione $Anotacione
 * @property Cargueinventario $Cargueinventario
 * @property Cliente $Cliente
 * @property Deposito $Deposito
 * @property Descargueinventario $Descargueinventario
 * @property Categoria $Categoria
 * @property Licencia $Licencia
 */
class Usuario extends AppModel {

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
		'perfile_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(
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
		'empresa_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Perfile' => array(
			'className' => 'Perfile',
			'foreignKey' => 'perfile_id',
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
		),
		'Empresa' => array(
			'className' => 'Empresa',
			'foreignKey' => 'empresa_id',
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
			'foreignKey' => 'usuario_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Cargueinventario' => array(
			'className' => 'Cargueinventario',
			'foreignKey' => 'usuario_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'usuario_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Deposito' => array(
			'className' => 'Deposito',
			'foreignKey' => 'usuario_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Descargueinventario' => array(
			'className' => 'Descargueinventario',
			'foreignKey' => 'usuario_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Categoria' => array(
			'className' => 'Categoria',
			'foreignKey' => 'usuario_id',
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
			'joinTable' => 'depositos_usuarios',
			'foreignKey' => 'usuario_id',
			'associationForeignKey' => 'deposito_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'Licencia' => array(
			'className' => 'Licencia',
			'joinTable' => 'licencias_usuarios',
			'foreignKey' => 'usuario_id',
			'associationForeignKey' => 'licencia_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);
        
        
    /**
     * obtenerUsuarioPorUsername
     *
     * Obtiene la informacion del usuario ingresando el username
     * @param string $username
     * @return array
     */
     public function obtenerUsuarioPorUsername($username){
         $arrInfoUsuario = $this->find('first', array('conditions' => array('Usuario.username' => $username), 'recursive' => '-1'));
         return $arrInfoUsuario;         
     }        
     
     public function obtenerUsuarioPorId($usuarioId){
         $arrInfoUsuario = $this->find('first', array('conditions' => array('Usuario.id' => $usuarioId), 'recursive' => '-1'));
         return $arrInfoUsuario;
     }
     
     public function obtenerUsuariosLogin($empresaId){
         $arrUsrLogin = $this->find('all', array('conditions' => array('Usuario.empresa_id' => $empresaId, 'Usuario.estadologin' => '1'), 'recursive' => '-1'));
         return $arrUsrLogin;
     }
     
     public function obtenerUsuarioEmpresa($empresaId){
         $listUsuario = $this->find('list', array('conditions' => array('Usuario.empresa_id' => $empresaId), 'recursive' => '-1', 'order' => 'Usuario.nombre'));
         return $listUsuario;
     }
     
     //funcion que registra la ultima actividad del usuario en el sistema
     public function actualizarActividadUsuario($usuarioId,$fechaActual){
         $data = array();         
         
         $usuario = new Usuario();
         
         $data['id'] = $usuarioId;
         $data['validaciongestion'] = $fechaActual;
         
         if($usuario->save($data)){
             return true;
         }else{
             return false;
         }
     }
     
     
     public function guardarUsuario($params){
         
         $resp = false;

        try{
            $query = "INSERT INTO usuarios (nombre, identificacion, username, perfile_id, estado_id, "; 
            $query .= "password, estadologin, intentos, empresa_id) VALUES ";
            $query .= "('" . $params['nombre'] . "', " . $params['identificacion'] . ", '" . $params['username'] . "', ";
            $query .= $params['perfile_id'] . ", " . $params['estado_id'] . ", '" . $params['password'] . "', ";
            $query .= $params['estadologin'] . ", " . $params['intentos'] . ", " . $params['empresa_id'] . ")";
            
            $this->query($query);
            
            $resp = true;

        } catch (Exception $ex) {
            echo $ex->getMessage();
            $resp = false;
        }
        
        return $resp;
         
     }
}
