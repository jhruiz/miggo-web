<?php
App::uses('AppModel', 'Model');

class Categoriacompra extends AppModel {
    public $displayField = 'descripcion';
    
    public function obtenerlistaCategoriasCompras($empresaId){
        $listCC = $this->find('list', array('conditions' => array('Categoriacompra.empresa_id' => $empresaId)));
        return $listCC;
    }
    
}