<?php
App::uses('AppModel', 'Model');

class Unidadesmedida extends AppModel {

    public $displayField = 'descripcion';
    
    public function listaUnidadesMedida(){
        $unidades = $this->find('list');
        return $unidades;
    }  
}