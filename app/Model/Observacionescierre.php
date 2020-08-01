<?php
App::uses('AppModel', 'Model');
class Observacionescierre extends AppModel {
        
    public function obtenerObsFecha($fecha, $empresaId){
        $obs = $this->find('all', array('conditions' => array('Observacionescierre.fecha' => $fecha, 'Observacionescierre.empresa_id' => $empresaId)));
        return $obs;
    }       

    public function gestionObservacion($data){
        $observaciones = new Observacionescierre();
        
        if($observaciones->save($data)){
            return true;
        }else{
            return false;
        }        
    }

}
