<?php
App::uses('AppModel', 'Model');

class Ordenestado extends AppModel {
    
    public $displayField = 'descripcion';

    /**
     * Se obtiene el listado de los estados que puede tener la orden
     * @return type
     */
    public function obtenerListaEstados(){
        $estados = $this->find('list');
        return $estados;
    }
    
    /**
     * Se obtiene la informacion de un estado por el id
     * @param type $estadoId
     * @return type
     */
    public function obtenerEstadoPorId($estadoId){
        $estado = $this->find('first', array('conditions' => array('Ordenestado.id' => $estadoId), 'recursive' => '-1'));
        return $estado;
    }
    
    /**
     * se obtiene la informacion de los estados que puede tener la orden de trabajo
     * @return type
     */
    public function obtenerInfoEstados(){
        $estados = $this->find('all', array('recursive' => '-1'));
        return $estados;
    }
    
    /**
     * Se obtienen los estados catalogados como fin
     */
    public function obtenerEstadosFin(){
        $estados = $this->find('list', array('conditions' => array('Ordenestado.ordenfinal' => true)));
        return $estados;
    }

}
