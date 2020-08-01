<?php
App::uses('AppModel', 'Model');
/**
 * Paise Model
 *
 * @property Ciudade $Ciudade
 */
class Itemsgasto extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'descripcion';
        
        public function obtenerListaItemsGastos($empresaId){
            $itemsgasto = $this->find('list', array(
                'conditions' => array(
                    'Itemsgasto.empresa_id' => $empresaId
                    ),
                'recursive' => '-1',
                'order' => 'Itemsgasto.descripcion'               
                ));
            return $itemsgasto;
        }   
        
        public function obtenerItemGastoProv($empresaId, $desc) {
            $itemGasto = $this->find('first', array(
                'conditions' => array(
                    'Itemsgasto.empresa_id' => $empresaId,
                    'Itemsgasto.descripcion' => $desc
                ),
                'recursive' => '-1'
            ));

            return $itemGasto;
        }

}
