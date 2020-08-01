<?php
App::uses('AppModel', 'Model');
/**
 * CargueinventariosImpuesto Model
 *
 * @property Cargueinventario $Cargueinventario
 * @property Impuesto $Impuesto
 */
class CargueinventariosImpuesto extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'cargueinventario_id' => array(
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
		'Cargueinventario' => array(
			'className' => 'Cargueinventario',
			'foreignKey' => 'cargueinventario_id',
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
        
        public function obtenerImpuestosProducto($cargueInventarioId){
            $arrImpuestosProd = $this->find('all', array('conditions' => array('CargueinventariosImpuesto.cargueinventario_id' => $cargueInventarioId), 'recursive' => '0'));
            return $arrImpuestosProd;
        }
        
        public function obtenerImpuestosProductoId($productoId, $depositoId){
            $arr_join = array(); 
            array_push($arr_join, array(
                'table' => 'cargueinventarios', 
                'alias' => 'CI', 
                'type' => 'INNER',
                'conditions' => array(
                    'CI.id=CargueinventariosImpuesto.cargueinventario_id'     
                    )                
            ));
            
            array_push($arr_join, array(
                'table' => 'impuestos',
                'alias' => 'I',
                'type' => 'INNER',
                'conditions' => array(
                    'I.id=CargueinventariosImpuesto.impuesto_id'
                )
            ));
            
            $infoInventario = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'CI.id',
                    'I.descripcion',
                    'I.valor'                    
                    ),
                'conditions' => array(
                    'CI.producto_id' => $productoId,
                    'CI.deposito_id' => $depositoId
                    ),
                'recursive' => '-1'
                ));            
            
            return $infoInventario;
        }
        
        public function guardarImpuestosCargueInv($cargueInvId,$impuestoId){
            $data = array();
            
            $cargueImpuetos = new CargueinventariosImpuesto();
            
            $data['cargueinventario_id'] = $cargueInvId;
            $data['impuesto_id'] = $impuestoId;
            
            if($cargueImpuetos->save($data)){
                return true;
            }else{
                return false;
            }
        }          
}
