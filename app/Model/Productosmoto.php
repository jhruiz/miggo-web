<?php
App::uses('AppModel', 'Model');

class Productosmoto extends AppModel {

    public function obtenerProductosMoto( $productoId ) {
        $prdMotos = $this->find('all', array(
            'conditions' => array(
                'Productosmoto.producto_id' => $productoId                 
                ), 
            'recursive' => '-1'));
        
            return $prdMotos; 
    }

}