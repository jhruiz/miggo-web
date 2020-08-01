<?php
App::uses('AppModel', 'Model');
/**
 * Empresa Model
 *
 * @property Ciudade $Ciudade
 * @property Impuesto $Impuesto
 * @property Tipopago $Tipopago
 * @property Deposito $Deposito
 * @property Tipodeposito $Tipodeposito
 * @property Perfile $Perfile
 * @property Usuario $Usuario
 */
class Empresa extends AppModel {

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
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Ciudade' => array(
			'className' => 'Ciudade',
			'foreignKey' => 'ciudade_id',
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
		'Impuesto' => array(
			'className' => 'Impuesto',
			'foreignKey' => 'empresa_id',
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
		'Tipopago' => array(
			'className' => 'Tipopago',
			'foreignKey' => 'empresa_id',
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
			'foreignKey' => 'empresa_id',
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
		'Tipodeposito' => array(
			'className' => 'Tipodeposito',
			'foreignKey' => 'empresa_id',
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
		'Perfile' => array(
			'className' => 'Perfile',
			'foreignKey' => 'empresa_id',
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
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'empresa_id',
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
        
        public function obtenerEmpresaPorId($empresaId){

            $datosEmpresa = $this->find('first', array('conditions' => array('Empresa.id' => $empresaId), 'recursive' => '-1'));
            return $datosEmpresa;
        }

}
