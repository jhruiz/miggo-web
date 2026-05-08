<?php
App::uses('AppModel', 'Model');

class Departamento extends AppModel {

	public $displayField = 'descripcion';

        public function obtenerDptosPais($pais){
            $dptos = $this->find('list', array('conditions' => array('paisemiggos_id' => $pais)));
            return $dptos;
        }       

}
