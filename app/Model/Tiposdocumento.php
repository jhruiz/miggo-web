<?php
App::uses('AppModel', 'Model');
/**
 * Tiposdocumento Model
 *
 * @property Documento $Documento
 */
class Tiposdocumento extends AppModel {

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
		'Documento' => array(
			'className' => 'Documento',
			'foreignKey' => 'tiposdocumento_id',
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
        
        public function obtenerListaTiposDocumentos(){
            $tipoDocs = $this->find('list', array('order' => array('Tiposdocumento.descripcion')));
            return $tipoDocs;
        }

}
