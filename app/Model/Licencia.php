<?php
App::uses('AppModel', 'Model');
/**
 * Licencia Model
 *
 * @property Usuario $Usuario
 */
class Licencia extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'cantidaddias';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Usuario' => array(
			'className' => 'Usuario',
			'joinTable' => 'licencias_usuarios',
			'foreignKey' => 'licencia_id',
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

}
