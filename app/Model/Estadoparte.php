<?php
App::uses('AppModel', 'Model');

class Estadoparte extends AppModel {

    public $displayField = 'descripcion';
    /**
     * Se obtiene la lista de estados
     * @return type
     */
    public function obtenerListaEstados(){
        $estados = $this->find('list');
        return $estados;
    }

    /**
     * Se obtiene toda la informacion de los estados asignables a las partes
     * @return type
     */
    public function obtenerInfoEstadoPartes(){
        $estados = $this->find('all', array('recursive' => '-1'));
        return $estados;
    }
    
}
