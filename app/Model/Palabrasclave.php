<?php
App::uses('AppModel', 'Model');

class Palabrasclave extends AppModel {

    public function obtenerPalabrasProducto($productoId) {
        return $this->find('list', array(
            'conditions' => array('producto_id' => $productoId),
            'fields' => array('palabra')
        ));
    }

     

}
