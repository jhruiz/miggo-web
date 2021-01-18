<?php
App::uses('AppModel', 'Model');
/**
 * Paise Model
 *
 * @property Ciudade $Ciudade
 */
class CategoriacomprasCompra extends AppModel {
   
    /**
     * guarda la compra de categorias compras
     * @param type $infoCatCom
     * @param type $idCompra
     * @return boolean
     */
    public function guardarCategoriaComprasCompra($dataCompra){

        $data = array();
        $catCompComp = new CategoriacomprasCompra();

        $data['producto_id'] = $dataCompra['productoId'];
        $data['cantidad'] = $dataCompra['cantidad'];
        $data['compra_id'] = $dataCompra['compraId'];
        $data['costounitario'] = $dataCompra['costoUnitario'];
        $data['costottal'] = $dataCompra['costoTtal'];
        $data['prciva'] = $dataCompra['prciva'];
        $data['vlriva'] = $dataCompra['vlrIva'];

        if($catCompComp->save($data)){
            return $catCompComp->id;
        }else{
            return false;
        }        
    }
    
    /**
     * obtiene las categorias de la compra por el id de la compra
     * @param type $compra_id
     * @return type
     */
    public function obtenerCatCompraPorCompraId($compra_id){
        $catComCom = $this->find('all', array('conditions' => array('CategoriacomprasCompra.compra_id' => $compra_id)));
        return $catComCom; 
    }
    
    /**
     * Elimina todas las categorias relacionadas a una compra
     * @param type $compra_id
     * @return boolean
     */
    public function eliminarDetalleCategoriaCompra($compra_id){
        $data = array();
        
        $data['CategoriacomprasCompra.compra_id'] = $compra_id;
        if($this->deleteAll($data)){
            return true;
        }else{
            return false;                
        }          
    }

}
