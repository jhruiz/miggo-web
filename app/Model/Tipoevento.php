<?php
App::uses('AppModel', 'Model');

class Tipoevento extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'descripcion';
	
     public function obtenerTipoEventos(){
         $listTipoEventos = $this->find('list');
         return $listTipoEventos;
     }	

}
