<?php
App::uses('AppModel', 'Model');

class Tipopagopago extends AppModel {

	public $displayField = 'descripcion';

        
        public function obtenerListaTiposPagos(){
            $listTipoPago = $this->find('list');
            return $listTipoPago;                    
        }
        
}
