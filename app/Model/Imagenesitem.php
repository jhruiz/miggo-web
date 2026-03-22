<?php
App::uses('AppModel', 'Model');

class Imagenesitem extends AppModel {


    public function obtnenerImagenesProducto($productoId) {
        return $this->find('all', array(
            'conditions' => array('producto_id' => $productoId),
            'order' => 'posicion ASC'
        ));
    }

}
