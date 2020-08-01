<?php
App::uses('AppModel', 'Model');
/**
 * Paise Model
 *
 * @property Ciudade $Ciudade
 */
class Compra extends AppModel {
   
    /**
     * se guarda la compra
     * @param type $dataCompra
     * @return boolean
     */
    public function guardarCompra($dataCompra){
        $data = array();
        $compra = new Compra();

        $data['fecha'] = $dataCompra['fechaFact'];
        $data['proveedore_id'] = $dataCompra['proveedorId'];
        $data['usuario_id'] = $dataCompra['usuarioId'];
        $data['numerofactura'] = $dataCompra['numFactura'];
        $data['prciva'] = $dataCompra['prcIVA'];
        $data['vlriva'] = $dataCompra['vlrIVA'];
        $data['prcreteica'] = $dataCompra['prcReteica'];
        $data['vlrreteica'] = $dataCompra['vlrReteica'];
        $data['reteica_id'] = $dataCompra['idReteica'];
        $data['prcretefuente'] = $dataCompra['prcRetefuente'];
        $data['vlrretefuente'] = $dataCompra['vlrRetefuente'];
        $data['retefuente_id'] = $dataCompra['idRetefuente'];

        if($compra->save($data)){
            return $compra->id;
        }else{
            return false;
        }        
    }
    
    /**
     * obtiene la informacion de la compra por id
     * @param type $id
     * @return type
     */
    public function obtenerInfoCompra($id){
        $compra = $this->find('all', array('conditions' => array('Compra.id' => $id)));
        return $compra;
    }
    
    public function obtenerCompras($proveedorId, $usuarioId, $numFactura, $FDesde, $FHasta, $empresaId){
        $filter = [];
        $arr_join = [];
        
        if(!empty($proveedorId)){ $filter['proveedore_id'] = $proveedorId; }
                
        if(!empty($usuarioId)){ $filter['usuario_id'] = $usuarioId; }
        
        if(!empty($numFactura)){ $filter['numerofactura'] = $numFactura; }
        
        if(!empty($FDesde) && !empty($FHasta)){ 
            $filter['Compra.fecha BETWEEN ? AND ?'] = array($FDesde . " 00:00:01", $FHasta . " 23:59:59");
        }

        array_push($arr_join, array(
            'table' => 'categoriacompras_compras',
            'alias' => 'CCC',
            'type' => 'INNER',
            'conditions' => array(
                'CCC.compra_id=Compra.id'
            )
        ));

        $infoCompras = $this->find('all', array(
            'joins' => $arr_join,                  
            'recursive' => '0',
            'conditions' => array($filter),
            'fields' => array(
                'Compra.*',
                'CCC.*',
            )
            ));         

        return $infoCompras;

    }

}
