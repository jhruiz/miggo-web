<?php
App::uses('AppModel', 'Model');

class Departamentosmiggo extends AppModel {

	public $displayField = 'descripcion';

        public function obtenerDptosPais($pais){
            $dptos = $this->find('list', array('conditions' => array('paisesmiggo_id' => $pais)));
            return $dptos;
        }       

}