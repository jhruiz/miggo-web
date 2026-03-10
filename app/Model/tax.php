<?php
App::uses('AppModel', 'Model');

class Tax extends AppModel {

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