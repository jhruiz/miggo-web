<?php
App::uses('AppModel', 'Model');

class Resolucionnotacredito extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'prefijo';
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Empresa' => array(
			'className' => 'Empresa',
			'foreignKey' => 'empresa_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
     
    /**
     * Se obtiene la resolucion configurada para la nota crédito
     */
    public function obtenerResolucionNC($empresaId){
        $result = $this->find('first', array('conditions' => array('Resolucionnotacredito.empresa_id' => $empresaId), 'recursive' => '-1'));
        return $result;
    }

    /**
     * Actualiza el consecutivo de la resolución de facturación
     */
    public function actualizarResolucionNC($empresaId) {

        $infoNC = $this->find('first', array('conditions' => array('Resolucionnotacredito.empresa_id' => $empresaId), 'recursive' => '-1'));

        $data = array();
            
        $resolucion = new Resolucionnotacredito();
        
        $data['id'] = $infoNC['Resolucionnotacredito']['id'];
        $data['consecutivoactual'] = $infoNC['Resolucionnotacredito']['consecutivoactual'] + 1;
        
        if($resolucion->save($data)){
            return true;
        }else{
            return false;
        }

    }
}
