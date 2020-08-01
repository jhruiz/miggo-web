<?php
App::uses('AppModel', 'Model');
/**
 * Paise Model
 *
 * @property Ciudade $Ciudade
 */
class OrdentrabajosSuministro extends AppModel {

    /**
     * Se guarda el suministro seleccionado relacionado a la orden
     * @param type $ordenId
     * @param type $cargueInvId
     * @param type $cantidad
     * @return boolean
     */
    public function guardarSuministroOrden($ordenId, $cargueInvId, $cantidad){
        $data = array();

        $ordenSum = new OrdentrabajosSuministro();

        $data['ordentrabajo_id'] = $ordenId;
        $data['cargueinventario_id'] = $cargueInvId;
        $data['cantidad'] = $cantidad;

        if($ordenSum->save($data)){
            return true;
        }else{
            return false;
        }            
    }
    
    /**
     * Se obtiene el suministro de una orden
     * @param type $cargueInvId
     * @param type $ordenId
     */
    public function obtenerOrdenSuministro($cargueInvId, $ordenId){
        $arrSumOrd = $this->find('first', array(
            'conditions' => array(
                'OrdentrabajosSuministro.ordentrabajo_id' => $ordenId,
                'OrdentrabajosSuministro.cargueinventario_id' => $cargueInvId                
                ), 'recursive' => '-1'
            ));
        
        return $arrSumOrd;
    }
    
    /**
     * Actualiza la cantidad del suministro para la orden
     * @param type $id
     * @param type $cantidad
     * @return boolean
     */
    public function actualizarCantidadSuministro($id, $cantidad){
        $data = array();

        $ordenSum = new OrdentrabajosSuministro();

        $data['id'] = $id;
        $data['cantidad'] = $cantidad;

        if($ordenSum->save($data)){
            return true;
        }else{
            return false;
        }          
    }
    
    /**
     * Se elimina un suministro de la orden
     * @param type $id
     * @return boolean
     */
    public function eliminarSuministroOrden($id){
        if($this->deleteAll(['OrdentrabajosSuministro.id' => $id])){
            return true;
        }else{
            return false;
        }          
    }
    
    /**
     * Se obtienen los suministros asociados a una orden de trabajo
     * @param type $ordenId
     * @return string
     */
    public function obtenerSuministrosOrden($ordenId){
        $arrSum = $this->find('all', array('conditions' => array('OrdentrabajosSuministro.ordentrabajo_id' => $ordenId), 'recursive' => '-1'));
        return $arrSum;
    }
    
    /**
     * Se obtienen los suministros de una orden con el cargue inventario y el producto
     * @param type $ordenId
     * @return type
     */
    public function obtenerSuministrosProductos($ordenId){
            $arr_join = array(); 
            array_push($arr_join, array(
                'table' => 'cargueinventarios', 
                'alias' => 'CI', 
                'type' => 'INNER',
                'conditions' => array(
                    'CI.id = OrdentrabajosSuministro.cargueinventario_id',
                    )                
            ));      
            
            array_push($arr_join, array(
                'table' => 'productos', 
                'alias' => 'P', 
                'type' => 'INNER',
                'conditions' => array(
                    'P.id=CI.producto_id',
                    )                
            ));           
            
            $infoSum = $this->find('all', array(
                'joins' => $arr_join, 
                'conditions' => array('OrdentrabajosSuministro.ordentrabajo_id' => $ordenId),
                'fields' => array(
                    'CI.id',
                    'P.id',
                    'P.descripcion',
                    'P.codigo',
                    'OrdentrabajosSuministro.*'
                    ),
                'recursive' => '-1'                
                ));            
            
            return $infoSum;          
    }

}
