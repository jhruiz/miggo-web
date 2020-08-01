<?php
App::uses('AppModel', 'Model');
/**
 * Cloudmenu Model
 *
 * @property Perfile $Perfile
 */
class Cloudmenu extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'descripcion';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Perfile' => array(
			'className' => 'Perfile',
			'joinTable' => 'cloudmenus_perfiles',
			'foreignKey' => 'cloudmenu_id',
			'associationForeignKey' => 'perfile_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);
        
        public function obtenerListadoMenu(){
            $menus = $this->find('list', array('order' => array('Cloudmenu.descripcion ASC')));
            return $menus;
        }

}
