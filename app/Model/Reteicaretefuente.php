<?php
App::uses('AppModel', 'Model');

class Reteicaretefuente extends AppModel {
    public $displayField = 'descripcion';
    
    /**
     * obtiene el listado de reteica de la empresa
     * @param type $empresaId
     * @return type
     */
    public function obtenerInfoReteica($empresaId){
        $infoReteica = $this->find('all', array(
            'conditions' => array(
                'Reteicaretefuente.empresa_id' => $empresaId, 
                'Reteicaretefuente.reteica' => '1'
                )
            ));
        return $infoReteica;
    }
    
    /**
     * obtiene el listado de retefuente de al empresa
     * @param type $empresaId
     * @return type
     */
    public function obtenerInfoRetefuente($empresaId){
        $infoRetefuente = $this->find('all', array(
            'conditions' => array(
                'Reteicaretefuente.empresa_id' => $empresaId, 
                'Reteicaretefuente.retefuente' => '1'
                )
            ));
        return $infoRetefuente;        
    }
    
    /**
     * obtiene el listado de reteica y retefuente
     * @param type $empresaId
     * @return type
     */
    public function obtenerListaReteicaRetefuente($empresaId){
        $listRicaRfte = $this->find('list', array('conditions' => array('Reteicaretefuente.empresa_id' => $empresaId)));
        return $listRicaRfte;        
    }
    
    
}
