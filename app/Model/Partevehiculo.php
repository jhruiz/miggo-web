<?php
App::uses('AppModel', 'Model');

class Partevehiculo extends AppModel {

	public $displayField = 'descripcion';
        
        public function obtenerListaParteVehiculo(){
            $parteV = $this->find('list');
            return $parteV;
        }

}
