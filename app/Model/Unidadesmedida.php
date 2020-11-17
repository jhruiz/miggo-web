<?php
App::uses('AppModel', 'Model');

class Unidadesmedida extends AppModel {

    public $displayField = 'descripcion';
    
    public function listaUnidadesMedida(){
        $unidades = $this->find('list');
        return $unidades;
    }  
    public function listaUnidadesMedidaDias(){
        $unidades = $this->find('list', array(                                             
            'conditions' => 'Unidadesmedida.id = 1',
            'recursive' => '-1',
            ));    
        return $unidades;
    }  
}