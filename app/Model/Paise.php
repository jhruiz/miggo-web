<?php
App::uses('AppModel', 'Model');
/**
 * Paise Model
 *
 * @property Ciudade $Ciudade
 */
class Paise extends AppModel {

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
		'Ciudade' => array(
			'className' => 'Ciudade',
			'foreignKey' => 'paise_id',
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
        
        public function obtenerListaPaises(){
            $paises = $this->find('list');
            return $paises;
        }       

}
