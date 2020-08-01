<?php
App::uses('AppModel', 'Model');
/**
 * Cotizacione Model
 *
 * @property Empresa $Empresa
 * @property Usuario $Usuario
 * @property Cargueinventario $Cargueinventario
 */
class Cotizacione extends AppModel {
           
    /**
     * Se obtienen las cotizaciones con los usuarios
     * @param type $arrFilter
     * @return type
     */
    public function obtenerCotizaciones($arrFilter){
        $arr_join = array(); 
        array_push($arr_join, array(
            'table' => 'usuarios', 
            'alias' => 'U', 
            'type' => 'LEFT',
            'conditions' => array(
                'U.id=Cotizacione.usuario_id'
                )                
        ));

        $arrCot = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'U.id',
                'U.nombre',
                'Cotizacione.*'
            ),
            'conditions' => array($arrFilter),
            'order' => 'Cotizacione.id',
            'recursive' => '-1'                
            ));            
        
        return $arrCot;         
    }
    
    /**
     * Se crea o actualiza una cotizacion
     * @param type $data
     * @return string
     */
    public function crearActualizarCotizacion($data){
        $Cotizacion = new Cotizacione();                

        if($Cotizacion->save($data)){
            return $Cotizacion->id;
        }else{
            return '0';
        }         
    }
    
    /**
     * Elimina la cotizacion
     * @param type $id
     * @return boolean
     */
    public function eliminarCotizacion($id){
        $data = array();
        
        $data['Cotizacione.id'] = $id;
        if($this->deleteAll($data)){
            return true;
        }else{
            return false;                
        }          
    }
    
    /**
     * Obtiene las cotizaciones con el cliente y el detalle
     * @param type $id
     * @return type
     */
    public function obtenerCotizacionCliDet($id){
        $arr_join = array(); 
        array_push($arr_join, array(
            'table' => 'clientes', 
            'alias' => 'C', 
            'type' => 'LEFT',
            'conditions' => array(
                'C.id=Cotizacione.cliente_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'cotizacionesdetalles',
            'alias' => 'CD',
            'type' => 'LEFT',
            'conditions' => array(
                'CD.cotizacione_id = Cotizacione.id'
            )
        ));

        $arrCot = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'C.*',
                'CD.*',
                'Cotizacione.*'
            ),
            'conditions' => array('Cotizacione.id' => $id),
            'recursive' => '-1'                
            ));            
        
        return $arrCot;          
    }
}
