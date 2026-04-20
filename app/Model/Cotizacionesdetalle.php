<?php
App::uses('AppModel', 'Model');
/**
 * Cotizacione Model
 *
 * @property Empresa $Empresa
 * @property Usuario $Usuario
 * @property Cargueinventario $Cargueinventario
 */
class Cotizacionesdetalle extends AppModel {
	
    /**
     * Se crea el detalle de la cotizacion
     * @param type $data
     * @return boolean
     */
    public function crearDetalleCotizacion($data){

        $cotDet = new Cotizacionesdetalle();

        if($cotDet->save($data)){
            return $cotDet->id;
        }else{
            return '0';
        }        
    }
    
    /**
     * elimina el detalle especifico de la cotizacion
     * @param type $data
     * @return boolean
     */
    public function eliminarDetalleCotizacion($data){
        if($this->deleteAll($data)){
            return true;
        }else{
            return false;                
        }   
    }
    
    /**
     * Se obtiene el registro de cotizacion detalle 
     * por medio del cargue inventario id y la cotizacion id
     * @param type $cargueInvId
     * @param type $cotizacionId
     */
    public function obtenerCotizacionDetalle($cargueInvId, $cotizacionId){
        $arrDetCot = $this->find('first', array(
            'conditions' => array(
                'Cotizacionesdetalle.cargueinventario_id' => $cargueInvId, 
                'Cotizacionesdetalle.cotizacione_id' => $cotizacionId
                ),
            'recursive' => '-1'
            ));
        return $arrDetCot;
    }
    
    /**
     * Obtienen todos los detalles de una cotizacion
     * @param type $idCotizacion
     * @return type
     */
    public function obtenerDetallePorCotId($idCotizacion){
        $arrCotDet = $this->find('all', array('conditions' => array('Cotizacionesdetalle.cotizacione_id' => $idCotizacion), 'recursive' => '-1'));
        return $arrCotDet;
    }
    
    public function obtenerCotizacionProductos($idCotizacion){
            $arr_join = array();                        
            
            array_push($arr_join, array(
                'table' => 'cotizaciones',
                'alias' => 'C',
                'type' => 'INNER',
                'conditions' => array(
                    'C.id=Cotizacionesdetalle.cotizacione_id'
                )
            ));            
            
            array_push($arr_join, array(
                'table' => 'usuarios',
                'alias' => 'U',
                'type' => 'INNER',
                'conditions' => array(
                    'U.id=C.usuario_id'
                )
            ));      
            
            array_push($arr_join, array(
                'table' => 'clientes',
                'alias' => 'CL',
                'type' => 'INNER',
                'conditions' => array(
                    'CL.id=C.cliente_id'
                )
            )); 

            array_push($arr_join, array(
                'table' => 'empresas',
                'alias' => 'EM',
                'type' => 'INNER',
                'conditions' => array(
                    'EM.id=U.empresa_id'
                )
            )); 
            
            array_push($arr_join, array(
                'table' => 'ciudades',
                'alias' => 'CIU',
                'type' => 'INNER',
                'conditions' => array(
                    'CIU.id=EM.ciudade_id'
                )
            )); 

            array_push($arr_join, array(
                'table' => 'paises',
                'alias' => 'PAI',
                'type' => 'INNER',
                'conditions' => array(
                    'PAI.id=CIU.paise_id'
                )
            ));  

            array_push($arr_join, array(
                'table' => 'cargueinventarios',
                'alias' => 'CI',
                'type' => 'INNER',
                'conditions' => array(
                    'CI.id=Cotizacionesdetalle.cargueinventario_id'
                )
            )); 

            array_push($arr_join, array(
                'table' => 'productos',
                'alias' => 'P',
                'type' => 'INNER',
                'conditions' => array(
                    'P.id=CI.producto_id'
                )
            ));

            array_push($arr_join, array(
                'table' => 'relacionempresas',
                'alias' => 'RE',
                'type' => 'LEFT',
                'conditions' => array(
                    'RE.empresa_id=EM.id'
                )
            ));

            $infoInventario = $this->find('all', array(
                'joins' => $arr_join, 
                'conditions' => array('Cotizacionesdetalle.cotizacione_id' => $idCotizacion),
                'fields' => array(
                    'C.*',
                    'U.*',
                    'CL.*',
                    'EM.*',
                    'CIU.*',
                    'PAI.*',
                    'CI.*',
                    'P.*',
                    'RE.*',
                    'Cotizacionesdetalle.*'
                ),
                'recursive' => '-1'                
                ));            
            
            return $infoInventario;         
    }

    /**
     * 
     */
    public function obtenerInfoDetallesCotizaInventario($cotDetId) {
        $arr_join = array();                        
        
        array_push($arr_join, array(
            'table' => 'cargueinventarios',
            'alias' => 'CI',
            'type' => 'INNER',
            'conditions' => array(
                'Ci.id=Cotizacionesdetalle.cargueinventario_id'
            )
        ));  

        $infoCotizacion = $this->find('all', array(
            'joins' => $arr_join, 
            'conditions' => array('Cotizacionesdetalle.id' => $cotDetId),
                'fields' => array(
                    'CI.*',
                    'Cotizacionesdetalle.*'
                ),
            'recursive' => '-1'                
            ));            
        
        return $infoCotizacion;   
    }
   
}
