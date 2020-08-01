<?php
App::uses('AppModel', 'Model');
/**
 * Tipodeposito Model
 *
 * @property Empresa $Empresa
 * @property Deposito $Deposito
 */
class Tipodeposito extends AppModel {

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
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Deposito' => array(
			'className' => 'Deposito',
			'foreignKey' => 'tipodeposito_id',
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
	
     public function obtenerDepositoEmpresa($empresaId){
         $listDepositos = $this->find('list', array('conditions' => array('Tipodeposito.empresa_id' => $empresaId), 'recursive' => '-1', 'order' => 'Tipodeposito.descripcion'));
         return $listDepositos;
     }	

}
