<?php
App::uses('AppModel', 'Model');

class Canalventa extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'descripcion';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Factura' => array(
			'className' => 'Factura',
			'foreignKey' => 'canalventa_id',
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

    public function obtenerCanalVentas($empresa_id) {
        return $this->find('list', array('conditions' => array('Canalventa.empresa_id' => $empresa_id), 'recursive' => '-1'));
    }

}
