<?php
App::uses('AppModel', 'Model');

class Taxe extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


    public function obtenerListadoImp() {
        return $this->find('list', array('recursive' => '-1'));
    }

}