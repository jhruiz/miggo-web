<?php
App::uses('AppModel', 'Model');

class Tipovehiculo extends AppModel {

	public $displayField = 'descripcion';
        
        public function obtenerListaTipoVehiculo(){
            $tipovehiculo = $this->find('list');
            return $tipovehiculo;
        }

}
