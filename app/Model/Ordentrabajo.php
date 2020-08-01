<?php
App::uses('AppModel', 'Model');

class Ordentrabajo extends AppModel {
        
    /**
     * Se obtienen las ordenes de trabajo creadas
     * @param type $arrFilter
     * @return type
     */
    public function obtenerOrdenesTrabajo($arrFilter){

        $arr_join = array(); 
        array_push($arr_join, array(
            'table' => 'ordenestados', 
            'alias' => 'OE', 
            'type' => 'LEFT',
            'conditions' => array(
                'OE.id=Ordentrabajo.ordenestado_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'vehiculos', 
            'alias' => 'VH', 
            'type' => 'LEFT',
            'conditions' => array(
                'VH.id=Ordentrabajo.vehiculo_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'usuarios', 
            'alias' => 'US', 
            'type' => 'LEFT',
            'conditions' => array(
                'US.id=Ordentrabajo.usuario_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'plantaservicios', 
            'alias' => 'PS', 
            'type' => 'LEFT',
            'conditions' => array(
                'PS.id=Ordentrabajo.plantaservicio_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'clientes', 
            'alias' => 'CL', 
            'type' => 'LEFT',
            'conditions' => array(
                'CL.id=Ordentrabajo.cliente_id'
                )                
        ));

        $ordEstados =  $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'OE.id',
                'OE.descripcion',
                'VH.id',
                'VH.placa',
                'VH.modelo',
                'VH.linea',
                'US.id',
                'US.nombre',
                'PS.id',
                'PS.descripcion',
                'CL.id',
                'CL.nombre',
                'CL.nit',
                'CL.direccion',
                'CL.telefono',
                'Ordentrabajo.*'
            ),
            'conditions' => array($arrFilter),
            'order' => 'Ordentrabajo.id DESC',
            'recursive' => '-1'
            ));                                        

            return $ordEstados;        
        
    }
        
    /**
     * Se obtienen las ordenes de trabajo creadas
     * @param type $arrFilter
     * @return type
     */
    public function obtenerIndexOrdenesTrabajo($arrFilter){

        $arr_join = array(); 
        array_push($arr_join, array(
            'table' => 'ordenestados', 
            'alias' => 'OE', 
            'type' => 'LEFT',
            'conditions' => array(
                'OE.id=Ordentrabajo.ordenestado_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'vehiculos', 
            'alias' => 'VH', 
            'type' => 'LEFT',
            'conditions' => array(
                'VH.id=Ordentrabajo.vehiculo_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'usuarios', 
            'alias' => 'US', 
            'type' => 'LEFT',
            'conditions' => array(
                'US.id=Ordentrabajo.usuario_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'plantaservicios', 
            'alias' => 'PS', 
            'type' => 'LEFT',
            'conditions' => array(
                'PS.id=Ordentrabajo.plantaservicio_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'clientes', 
            'alias' => 'CL', 
            'type' => 'LEFT',
            'conditions' => array(
                'CL.id=Ordentrabajo.cliente_id'
                )                
        ));

        $ordEstados =  array(
            'joins' => $arr_join,
            'fields' => array(
                'OE.id',
                'OE.descripcion',
                'VH.id',
                'VH.placa',
                'US.id',
                'US.nombre',
                'PS.id',
                'PS.descripcion',
                'CL.id',
                'CL.nombre',
                'CL.nit',
                'CL.direccion',
                'CL.telefono',
                'Ordentrabajo.*'
            ),
            'conditions' => array($arrFilter),
            'order' => 'Ordentrabajo.id DESC',
            'recursive' => '-1'
            );                                        

            return $ordEstados;        
        
    }
    
    /**
     * Se crea la orden de trabajo con cualquiera de los datos principales
     * @param type $data
     * @return string
     */
    public function crearActualizarOrdenTrabajo($data){
        $Ordentrabajo = new Ordentrabajo();                

        if($Ordentrabajo->save($data)){
            return $Ordentrabajo->id;
        }else{
            return '0';
        }            
    }
    
    /**
     * Obtiene la informacion básica de la orden de trabajo por id
     * @param type $id
     * @return type
     */
    public function obtenerOrdenPorId($id){
        $arrOrden = $this->find('first', array('conditions' => array('Ordentrabajo.id' => $id), 'recursive' => '-1'));
        return $arrOrden;
    }

    public function obtenerEstadistacasOrdenes($empresaId){
        $arr_join = array();

        array_push($arr_join, array(
            'table' => 'ordenestados', 
            'alias' => 'OE', 
            'type' => 'INNER',
            'conditions' => array('Ordentrabajo.ordenestado_id=OE.id')                
        ));            

        array_push($arr_join, array(
            'table' => 'usuarios', 
            'alias' => 'U', 
            'type' => 'INNER',
            'conditions' => array('Ordentrabajo.usuario_id=U.id')                
        ));            

        $resp = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'count(Ordentrabajo.ordenestado_id) as contador',
                'OE.descripcion'
            ),
            'conditions' => array(
                'U.empresa_id' => $empresaId
            ),
            'group' => 'Ordentrabajo.ordenestado_id'
        ));

        return $resp;         
    }

    public function obtenerOrdenesTecnicosTortas($empresaId, $fechaInicial = null, $fechaFinal = null) {        

        $filterDate = '';
        if(!empty($fechaInicial) && !empty($fechaFinal)){
            $filterDate = array('Ordentrabajo.fecha_ingreso BETWEEN ? AND ? ' => array($fechaInicial, $fechaFinal));
        }

        $arr_join = array();

        array_push($arr_join, array(
            'table' => 'usuarios', 
            'alias' => 'U', 
            'type' => 'INNER',
            'conditions' => array('Ordentrabajo.usuario_id=U.id')                
        ));            

        $resp = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'count(Ordentrabajo.usuario_id) as contador',
                'U.nombre'
            ),
            'conditions' => array(
                'U.empresa_id' => $empresaId,
                $filterDate
            ),
            'group' => 'Ordentrabajo.usuario_id',
            'order' => 'contador desc',
            'limit' => '5'
        ));

        return $resp;          
    }
}
