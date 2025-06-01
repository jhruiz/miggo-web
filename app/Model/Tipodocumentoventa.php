<?php
App::uses('AppModel', 'Model');

class Tipodocumentoventa extends AppModel {

	public $displayField = 'descripcion';

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Resolucionfactura' => array(
			'className' => 'Resolucionfactura',
			'foreignKey' => 'tipodocumentoventa_id',
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

}
