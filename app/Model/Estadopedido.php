<?php
App::uses('AppModel', 'Model');

class Estadopedido extends AppModel {

	public $displayField = 'descripcion';


    public function obtenerListaEstadoPedidos(){

        $estados = $this->find('list', array('order' => 'Estadopedido.orden'));
        return $estados;
    }   

}
