<?php
App::uses('AppModel', 'Model');
/**
 * DepositosUsuario Model
 *
 * @property Deposito $Deposito
 * @property Usuario $Usuario
 */
class DepositosUsuario extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'deposito_id' => array(
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
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Deposito' => array(
			'className' => 'Deposito',
			'foreignKey' => 'deposito_id',
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
		)
	);
        
        public function obtenerDepositosUsuario($usuarioId){
            $arrDeptoUsr = $this->find('all', array('conditions' => array('DepositosUsuario.usuario_id' => $usuarioId), 'recursive' => '-1', 'order' => 'DepositosUsuario.id'));
            return $arrDeptoUsr;
        }        
        
}
