<?php
App::uses('AppModel', 'Model');

class Evento extends AppModel {

    public function guardareveto($data){
        $evento = new Evento();

        if($evento->save($data)){
            return true;
        }else{
            return false;
        }      
    }

    public function obtenerEventosParaCalendario($empresaId, $initDate, $endDate){
        $arr_join = array(); 
            
        array_push($arr_join, array(
            'table' => 'tipoeventos', 
            'alias' => 'TE', 
            'type' => 'INNER',
            'conditions' => array(
                'Evento.tipoevento_id=TE.id'
                )                
        ));          
        
        array_push($arr_join, array(
            'table' => 'usuarios', 
            'alias' => 'U', 
            'type' => 'INNER',
            'conditions' => array(
                'Evento.usuario_id=U.id'
                )                
        ));         
        
        array_push($arr_join, array(
            'table' => 'estadoalertas', 
            'alias' => 'EA', 
            'type' => 'INNER',
            'conditions' => array(
                'Evento.estadoalerta_id=EA.id'
                )                
        ));         
                   
        $eventos = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'U.id',
                'U.nombre',
                'TE.id',
                'TE.descripcion',
                'EA.id',
                'EA.descripcion',
                'Evento.*'
            ),
            'conditions' => array(
                'Evento.empresa_id' => $empresaId,
                'Evento.fecha BETWEEN ? AND ? ' => array($initDate, $endDate),                    
                ), 
            'recursive' => '-1'));
        return $eventos;          
    }

    public function obtenerEventosVencidos($empresaId, $fecha){
        $arr_join = array();
        array_push($arr_join, array(
            'table' => 'estadoalertas', 
            'alias' => 'EA', 
            'type' => 'INNER',
            'conditions' => array(
                'Evento.estadoalerta_id=EA.id',
                'EA.final <> 1'
                )                
        ));         
                   
        $eventos = $this->find('all', array(
            'joins' => $arr_join,
            'conditions' => array(
                'Evento.empresa_id' => $empresaId,
                'Evento.fecha < ' => $fecha,
                ), 
            'recursive' => '-1'));
        return $eventos;          
    }


    public function obtenerEventosIndexBusqueda($empresaId,$idEstado, $filtros){

        $arr_join = array();
        array_push($arr_join, array(
            'table' => 'estadoalertas', 
            'alias' => 'EA', 
            'type' => 'INNER',
            'conditions' => array(
                'Evento.estadoalerta_id=EA.id'
                )                
        ));         
        array_push($arr_join, array(
            'table' => 'usuarios', 
            'alias' => 'U', 
            'type' => 'INNER',
            'conditions' => array(
                'Evento.usuario_id = U.id'
                )                
        ));      
        $eventos = $this->find('all', array(
            'joins' => $arr_join,
            'conditions' => array(
                 $filtros,
                'Evento.empresa_id' => $empresaId,
                'Evento.estadoalerta_id' => $idEstado
                
                ), 
            'recursive' => '-1',
            'order' => 'fecha desc',
        ));
        return $eventos; 


    } 
    public function obtenerEventosIndex($empresaId, $filtros){

        $arr_join = array();
        array_push($arr_join, array(
            'table' => 'estadoalertas', 
            'alias' => 'EA', 
            'type' => 'INNER',
            'conditions' => array(
                'Evento.estadoalerta_id=EA.id'
                )                
        ));         
        array_push($arr_join, array(
            'table' => 'usuarios', 
            'alias' => 'U', 
            'type' => 'INNER',
            'conditions' => array(
                'Evento.usuario_id = U.id'
                )                
        ));         
                   
        $eventos = $this->find('all', array(
            'joins' => $arr_join,
            'conditions' => array(
                $filtros,
                'Evento.empresa_id' => $empresaId,
                
                ), 
            'recursive' => '-1',
            'order' => 'fecha desc',
        
        
        ));
        return $eventos; 


    } 
}
