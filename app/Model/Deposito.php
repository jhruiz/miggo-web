<?php
App::uses('AppModel', 'Model');
/**
 * Deposito Model
 *
 * @property Empresa $Empresa
 * @property Ciudade $Ciudade
 * @property Estado $Estado
 * @property Usuario $Usuario
 * @property Tipodeposito $Tipodeposito
 * @property Regimene $Regimene
 * @property Cargueinventario $Cargueinventario
 * @property Descargueinventario $Descargueinventario
 * @property Cliente $Cliente
 */
class Deposito extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'descripcion';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
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
		'tipodeposito_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'regimene_id' => array(
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
		'Empresa' => array(
			'className' => 'Empresa',
			'foreignKey' => 'empresa_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Ciudade' => array(
			'className' => 'Ciudade',
			'foreignKey' => 'ciudade_id',
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
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Tipodeposito' => array(
			'className' => 'Tipodeposito',
			'foreignKey' => 'tipodeposito_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Regimene' => array(
			'className' => 'Regimene',
			'foreignKey' => 'regimene_id',
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
		'Cargueinventario' => array(
			'className' => 'Cargueinventario',
			'foreignKey' => 'deposito_id',
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
			'foreignKey' => 'deposito_id',
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
		'Cliente' => array(
			'className' => 'Cliente',
			'joinTable' => 'depositos_clientes',
			'foreignKey' => 'deposito_id',
			'associationForeignKey' => 'cliente_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'Usuario' => array(
			'className' => 'Usuario',
			'joinTable' => 'depositos_usuarios',
			'foreignKey' => 'deposito_id',
			'associationForeignKey' => 'usuario_id',
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
 * Se obtienen los depositos asignados a un usaurio
 *
 * @var $usuarioId
 * return $depositos
 */        
        public function obtenerDepositoUsuario($usuarioId){
            $arr_join = array(); 

            array_push($arr_join, array(
                'table' => 'depositos_usuarios', 
                'alias' => 'DU', 
                'type' => 'INNER',
                'conditions' => array(
                    'DU.deposito_id=Deposito.id')
                
            ));
            
            $arrDepositos = $this->find('all', array(
                'joins' => $arr_join,                  
                'conditions' => array(
                    'DU.usuario_id' => $usuarioId
                    ),
                'recursive' => '-1'                
                ));
                                            
            return $arrDepositos;
        }                    
        
        public function obtenerListaDepositosUsuario($usuarioId){
            $join = array(
                array(
                    'table' => 'depositos_usuarios', 
                    'alias' => 'DU', 
                    'type' => 'INNER',
                    'conditions' => array(
                        'DU.deposito_id=Deposito.id',
                        'DU.usuario_id' => $usuarioId)));
            
            
            $depositos = $this->find('list', array('joins' => $join, 'recursive' => '-1'));     
            return $depositos;            
        }
        
        public function obtenerDepositoPorId($depositoId){
            $infoDeposito = $this->find('first', array('conditions' => array('Deposito.id' => $depositoId), 'fields' => 'Deposito.descripcion', 'recursive' => '-1'));
            return $infoDeposito['Deposito']['descripcion'];
        }
        
        public function obtenerInfoDepositoPorId($depositoId){
            $infoDeposito = $this->find('first', array('conditions' => array('Deposito.id' => $depositoId), 'recursive' => '-1'));
            return $infoDeposito;
        }
        
        public function actualizarConsecutivoFactura($depositoId, $consecutivo){
            $data = array();
            
            $deposito = new Deposito();
            
            $data['id'] = $depositoId;
            $data['numresolucionactual'] = $consecutivo;
            
            if($deposito->save($data)){
                return true;
            }else{
                return false;
            }
        }
        
        public function obtenerDepositoEmpresa($empresaId){
            $listDeposito = $this->find('list', array('conditions' => array('Deposito.empresa_id' => $empresaId), 'recursive' => '-1', 'order' => 'Deposito.descripcion'));
            return $listDeposito;
        }
        
        //se obtiene toda la informacion de los depositos de una empresa
        public function obtenerInfoDepositosEmpresa($empresaId){
            $infoDepositos = $this->find('all', array('conditons' => array('Deposito.empresa_id' => $empresaId), 'recursive' => '-1'));
            return $infoDepositos; 
		}
		
		public function obtenerBodegasEstadisticas($empresaId){
			$depositos = $this->find('list', array(
				'conditions' => array(
					'Deposito.empresa_id' => $empresaId,
					'Deposito.estadisticas' => '1'
				),
				'recursive' => '-1'));

			return $depositos;
		}

}
