<?php
App::uses('AppModel', 'Model');
/**
 * PrecargueinventariosImpuesto Model
 *
 * @property Precargueinventario $Precargueinventario
 * @property Impuesto $Impuesto
 */
class PrecargueinventariosImpuesto extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'precargueinventario_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'impuesto_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Precargueinventario' => array(
			'className' => 'Precargueinventario',
			'foreignKey' => 'precargueinventario_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Impuesto' => array(
			'className' => 'Impuesto',
			'foreignKey' => 'impuesto_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        
        public function guardarPrecargueImpuesto($preCargueId,$impuestoId){
            $data=array();                        
                
            $preCargueImpuesto = new PrecargueinventariosImpuesto();  
             
            $data['precargueinventario_id'] = $preCargueId;            
            $data['impuesto_id'] = $impuestoId;
            
            if($preCargueImpuesto->save($data)){
                return true;
            }else{
                return false;
            }
        }        
        
        public function obtenerPrecargueInv($precargueInvId){
            $infoImp = $this->find('all', array('conditions' => array('PrecargueinventariosImpuesto.precargueinventario_id' => $precargueInvId),'recursive' => '-1'));
            return $infoImp;
        }        
}
