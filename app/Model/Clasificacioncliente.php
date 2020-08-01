<?php
App::uses('AppModel', 'Model');

class Clasificacioncliente extends AppModel {

    public $displayField = 'descripcion';    

    public function obtenerListClasificacion(){

        $listClasificacion = $this->find('list');
        return $listClasificacion;                      
    }
}
