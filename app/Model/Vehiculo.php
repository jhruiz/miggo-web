<?php
App::uses('AppModel', 'Model');

class Vehiculo extends AppModel {
        
    /**
     * obtiene la informacion completa de todos los vehiculos
     * @return type
     */
    public function obtenerInfoVehiculos(){
        $vehiculo = $this->find('all');
        return $vehiculo;
    }
    
    /**
     * Obtiene la información de los vehiculos que coinciden por placa
     * @param type $placa
     * @return type
     */
    public function obtenerDatosVehiculo($placa){
        $arrV = $this->find('all', array(
            'conditions' => array(
                    'LOWER(Vehiculo.placa) LIKE' => '%'. $placa . '%',
            ), 
            'order' => 'Vehiculo.placa',
            'recursive' => '-1'));

        return $arrV;        
    }
    
    /**
     * Se obtiene la informacion del vehiculo por id
     * @param type $id
     * @return type
     */
    public function obtenerVehiculoPorId($id){
        $arrV = $this->find('first', array('conditions' => array('Vehiculo.id' => $id), 'recursive' => '-1'));
        return $arrV;
    }
    
    public function actualizarDatosVehiculo($data){
        $vehiculo = new Vehiculo();                

        if($vehiculo->save($data)){
            return $vehiculo->id;
        }else{
            return '0';
        }           
    }
}