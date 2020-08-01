<?php
App::uses('AppModel', 'Model');

class Estadopagomecanico extends AppModel {

	public $displayField = 'descripcion';
        
        public function obtenerListaEstadoPago(){
            $estadoPago = $this->find('list');
            return $estadoPago;
        }       

}