<?php
App::uses('AppModel', 'Model');

class PartevehiculosTipovehiculo extends AppModel {
    
    /**
     * se obtienen las partes y el tipo de vehiculo
     * @param type $arrFilter
     * @return type
     */
    public function obtenerTipoParteV($arrFilter){
        $arr_join = array(); 
        array_push($arr_join, array(
            'table' => 'partevehiculos', 
            'alias' => 'PV', 
            'type' => 'INNER',
            'conditions' => array(
                'PV.id=PartevehiculosTipovehiculo.partevehiculo_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'tipovehiculos', 
            'alias' => 'TV', 
            'type' => 'INNER',
            'conditions' => array(
                'TV.id=PartevehiculosTipovehiculo.tipovehiculo_id'
                )                
        ));

        $arrResp = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'PV.id',
                'PV.descripcion',
                'TV.id',
                'TV.descripcion',
                'PartevehiculosTipovehiculo.*'
            ),
            'conditions' => array($arrFilter),
            'recursive' => '-1'                
            ));            

        return $arrResp;        
    }
    
    public function obtenerPartesPorTipoVehiculoId($tipoVId){
        
        $arr_join = array(); 
        
        array_push($arr_join, array(
            'table' => 'partevehiculos', 
            'alias' => 'PV', 
            'type' => 'INNER',
            'conditions' => array(
                'PV.id=PartevehiculosTipovehiculo.partevehiculo_id'
                )                
        ));        
        
        $arrPartes = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                    'PV.id',
                    'PV.descripcion',
                    'PartevehiculosTipovehiculo.*'
                    ),
            'conditions' => array('PartevehiculosTipovehiculo.tipovehiculo_id' => $tipoVId)
        ));
        
        return $arrPartes;
    }

}
