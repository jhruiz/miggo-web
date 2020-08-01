<?php
App::uses('AppModel', 'Model');
/**
 * Notafactura Model
 *
 * @property Empresa $Empresa
 * @property Factura $Factura
 */
class Notafactura extends AppModel {

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
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Factura' => array(
			'className' => 'Factura',
			'joinTable' => 'facturas_notafacturas',
			'foreignKey' => 'notafactura_id',
			'associationForeignKey' => 'factura_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);
	
	public function obtenerNotasFacturasEmpresa($empresaId){
	    $notasFacturas = $this->find('list', array('conditions' => array('Notafactura.empresa_id' => $empresaId), 'recursive' => '-1'));
	    return $notasFacturas;
	}
}
