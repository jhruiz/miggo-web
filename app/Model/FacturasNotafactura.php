<?php
App::uses('AppModel', 'Model');
/**
 * FacturasNotafactura Model
 *
 * @property Factura $Factura
 * @property Notafactura $Notafactura
 * @property Usuario $Usuario
 */
class FacturasNotafactura extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'factura_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'notafactura_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'usuario_id' => array(
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
		'Factura' => array(
			'className' => 'Factura',
			'foreignKey' => 'factura_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Notafactura' => array(
			'className' => 'Notafactura',
			'foreignKey' => 'notafactura_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        
        public function guardarNotaFactura($facturaId,$notafactura,$vendedor){
            $data = array();
            $notaFactura = new FacturasNotafactura();
           
            $data['factura_id'] = $facturaId;
            $data['notafactura_id'] = $notafactura;            
            $data['usuario_id'] = $vendedor;
            
            if($notaFactura->save($data)){                
                return true;
            }else{
                return false;
            }            
        }       
        
        public function obtenerNotaFactura($facturaId){
            $notaFactura = $this->find('all', array('conditions' => array('FacturasNotafactura.factura_id' => $facturaId)));
            return $notaFactura;
        }        
}
