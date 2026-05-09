<?php
App::uses('AppModel', 'Model');

class Ciudadesmiggo extends AppModel {

	public $displayField = 'nombre';

    public function obtenerCiudadesDpto($dpto){
        $ciudades = $this->find('list', array('conditions' => array('departamento_id' => $dpto)));
        return $ciudades;
    }    
    
    /**
     * Se obtiene una ciudad y el pais
     * @param type $id
     * @return type
     */
    public function obtenerUbicacion($id){
        
        $arr_join = array(); 
        array_push($arr_join, array(
            'table' => 'departamentos', 
            'alias' => 'D', 
            'type' => 'INNER',
            'conditions' => array(
                'D.id=Ciudadesmiggo.departamento_id'
                )                
        ));

        array_push($arr_join, array(
            'table' => 'paisesmiggos', 
            'alias' => 'P', 
            'type' => 'INNER',
            'conditions' => array(
                'P.id=D.paisemiggos_id'
                )                
        ));

        $arrCiudades = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'P.*',
                'D.*',
                'Ciudadesmiggo.*'
            ),
            'conditions' => array('Ciudadesmiggo.id' => $id),
            'recursive' => '-1'                
            ));            

        return $arrCiudades;        

    }

}
