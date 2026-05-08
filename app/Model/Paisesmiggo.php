<?php
App::uses('AppModel', 'Model');

class Paisesmiggo extends AppModel {

	public $displayField = 'nombre';

        public function obtenerListaPaises(){
            $paises = $this->find('list');
            return $paises;
        }       

}
