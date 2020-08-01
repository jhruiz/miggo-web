<?php
App::uses('AppModel', 'Model');

class OrdentrabajosPartevehiculo extends AppModel {
        
    /**
     * Se asocian las partes del vehiculo con la orden de trabajo y se asigna 
     * como etado inicial "No aplica"
     * @param type $parteVId
     * @param type $estadoId
     * @param type $ordenId
     * @return boolean
     */
    public function crearPartesOrdenTrabajo($parteVId, $estadoId, $ordenId){
        $data = array();                        

        $OrdenTParteV = new OrdentrabajosPartevehiculo();                        

        $data['partevehiculo_id'] = $parteVId;
        $data['estadoparte_id'] = $estadoId;
        $data['ordentrabajo_id'] = $ordenId;

        if($OrdenTParteV->save($data)){
            return true;
        }else{
            return false;
        } 
    }
    
    /**
     * Elimina todas las partes de un vehiculo asociadas a una orden de trabajo
     * @param type $ordenId
     * @return boolean
     */
    public function eliminarOrdenTrabajoPartesVehiculo($ordenId){
        if($this->deleteAll(['OrdentrabajosPartevehiculo.ordentrabajo_id' => $ordenId])){
            return true;
        }else{
            return false;
        }        
    }
    
    /**
     * Se obtiene la parte del vehiculo asociada a la orden de trabajo
     * @param type $ordenId
     * @param type $parteId
     */
    public function obtenerParteEstadoOrden($ordenId, $parteId){
        $arrPO = $this->find('first', array(
            'conditions' => array(
                'OrdentrabajosPartevehiculo.ordentrabajo_id' => $ordenId,
                'OrdentrabajosPartevehiculo.partevehiculo_id' => $parteId
                ), 
            'recursive' => '-1' 
            ));
        
        return $arrPO;
    }
    
    /**
     * Se actualiza el estado de una parte asociada a una orden
     * @param type $id
     * @param type $estadoId
     * @return boolean
     */
    public function actualizarEstadoParte($id, $estadoId){
        $data = array();                        

        $OrdenTParteV = new OrdentrabajosPartevehiculo();                        

        $data['id'] = $id;
        $data['estadoparte_id'] = $estadoId;

        if($OrdenTParteV->save($data)){
            return true;
        }else{
            return false;
        }         
    }
    
    public function obtenerEstadosPartesOrden($idOrden){
        $arr_join = array(); 
        array_push($arr_join, array(
            'table' => 'partevehiculos', 
            'alias' => 'PV', 
            'type' => 'INNER',
            'conditions' => array(
                'PV.id = OrdentrabajosPartevehiculo.partevehiculo_id',
                )                
        ));      

        $infoPart = $this->find('all', array(
            'joins' => $arr_join, 
            'conditions' => array('OrdentrabajosPartevehiculo.ordentrabajo_id' => $idOrden),
            'fields' => array(
                'PV.id',
                'PV.descripcion',
                'OrdentrabajosPartevehiculo.*'
                ),
            'recursive' => '-1'                
            ));            

        return $infoPart;            
    }
    
    /**
     * obtener partes vehiculo de la orden marcados como extras
     * @param type $ordenId
     * @return type
     */
    public function obtenerPartesExtra($ordenId){
        $arr_join = array(); 
        array_push($arr_join, array(
            'table' => 'partevehiculos', 
            'alias' => 'PV', 
            'type' => 'INNER',
            'conditions' => array(
                'PV.id = OrdentrabajosPartevehiculo.partevehiculo_id',
                'PV.extra = 1'
                )                
        ));   
        
        array_push($arr_join, array(
            'table' => 'estadopartes', 
            'alias' => 'EP', 
            'type' => 'INNER',
            'conditions' => array(
                'EP.id = OrdentrabajosPartevehiculo.estadoparte_id'
                )                
        ));  
        
        array_push($arr_join, array(
            'table' => 'ordentrabajos', 
            'alias' => 'OT', 
            'type' => 'INNER',
            'conditions' => array(
                'OT.id = OrdentrabajosPartevehiculo.ordentrabajo_id'
                )                
        ));  
        
        array_push($arr_join, array(
            'table' => 'clientes', 
            'alias' => 'C', 
            'type' => 'INNER',
            'conditions' => array(
                'C.id = OT.cliente_id'
                )                
        ));  
        
        array_push($arr_join, array(
            'table' => 'vehiculos', 
            'alias' => 'V', 
            'type' => 'INNER',
            'conditions' => array(
                'V.id = OT.vehiculo_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'marcavehiculos', 
            'alias' => 'MV', 
            'type' => 'INNER',
            'conditions' => array(
                'MV.id = V.marcavehiculo_id'
                )                
        ));  

        $infoPart = $this->find('all', array(
            'joins' => $arr_join, 
            'conditions' => array('OrdentrabajosPartevehiculo.ordentrabajo_id' => $ordenId),
            'fields' => array(
                'PV.id',
                'PV.descripcion',
                'EP.id',
                'EP.descripcion',
                'C.id',
                'C.nombre',
                'C.nit',
                'C.telefono',
                'C.celular',
                'C.direccion',
                'V.id',
                'V.placa',
                'V.linea',
                'MV.id',
                'MV.descripcion',
                'OT.id',
                'OT.codigo',
                'OrdentrabajosPartevehiculo.*'
                ),
            'recursive' => '-1'                
            ));            

        return $infoPart;         
    }
    
    /**
     * obtener partes vehiculo de la orden
     * @param type $ordenId
     * @return type
     */
    public function obtenerPartesOrdenTrabajo($ordenId){
        $arr_join = array(); 
        array_push($arr_join, array(
            'table' => 'partevehiculos', 
            'alias' => 'PV', 
            'type' => 'INNER',
            'conditions' => array(
                'PV.id = OrdentrabajosPartevehiculo.partevehiculo_id'
                )                
        ));   
        
        array_push($arr_join, array(
            'table' => 'estadopartes', 
            'alias' => 'EP', 
            'type' => 'INNER',
            'conditions' => array(
                'EP.id = OrdentrabajosPartevehiculo.estadoparte_id'
                )                
        ));  
        
        array_push($arr_join, array(
            'table' => 'ordentrabajos', 
            'alias' => 'OT', 
            'type' => 'INNER',
            'conditions' => array(
                'OT.id = OrdentrabajosPartevehiculo.ordentrabajo_id'
                )                
        ));  
        
        array_push($arr_join, array(
            'table' => 'clientes', 
            'alias' => 'C', 
            'type' => 'INNER',
            'conditions' => array(
                'C.id = OT.cliente_id'
                )                
        ));  
        
        array_push($arr_join, array(
            'table' => 'vehiculos', 
            'alias' => 'V', 
            'type' => 'INNER',
            'conditions' => array(
                'V.id = OT.vehiculo_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'marcavehiculos', 
            'alias' => 'MV', 
            'type' => 'INNER',
            'conditions' => array(
                'MV.id = V.marcavehiculo_id'
                )                
        ));  

        $infoPart = $this->find('all', array(
            'joins' => $arr_join, 
            'conditions' => array('OrdentrabajosPartevehiculo.ordentrabajo_id' => $ordenId),
            'fields' => array(
                'PV.id',
                'PV.descripcion',
                'EP.id',
                'EP.descripcion',
                'C.id',
                'C.nombre',
                'C.nit',
                'C.telefono',
                'C.celular',
                'C.direccion',
                'V.id',
                'V.placa',
                'V.linea',
                'MV.id',
                'MV.descripcion',
                'OT.id',
                'OT.codigo',
                'OT.kilometraje',
                'OT.fecha_ingreso',
                'OT.fecha_salida',
                'OrdentrabajosPartevehiculo.*'
                ),
            'recursive' => '-1'                
            ));            

        return $infoPart;         
    }    

}

