<?php
App::uses('AppModel', 'Model');

class Tipoidentificacione extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nombre';
	
     public function obtenerTipoIdentificaciones(){
         $listTipoIdentificaciones = $this->find('list');
         return $listTipoIdentificaciones;
     }	

}
