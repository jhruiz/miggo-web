<?php
App::uses('AppModel', 'Model');
/**
 * Regimene Model
 *
 * @property Deposito $Deposito
 */
class Regimene extends AppModel {

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
		'Deposito' => array(
			'className' => 'Deposito',
			'foreignKey' => 'regimene_id',
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
        
        public function obtenerRegimenPorId($id){
            $infoRegimen = $this->find('first', array('conditions' => array('Regimene.id' => $id), 'recursive' => '-1'));
            return $infoRegimen;
        }

}
