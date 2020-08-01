<?php
App::uses('AppModel', 'Model');
/**
 * Paise Model
 *
 * @property Ciudade $Ciudade
 */
class Marcavehiculo extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'descripcion';
        
        public function obtenerListaMarcavehiculos(){
            $marca = $this->find('list');
            return $marca;
        }

}
