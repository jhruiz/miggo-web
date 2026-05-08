<?php
App::uses('AppModel', 'Model');

class Ciudadesmiggo extends AppModel {

	public $displayField = 'nombre';

    public function obtenerCiudadesDpto($dpto){
        $ciudades = $this->find('list', array('conditions' => array('departamento_id' => $dpto)));
        return $ciudades;
    }       

}
