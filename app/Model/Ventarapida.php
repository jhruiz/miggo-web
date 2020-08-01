<?php
App::uses('AppModel', 'Model');
/**
 * Ventarapida Model
 *
 * @property Factura $Factura
 */
class Ventarapida extends AppModel {

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
		)
	);
        
        public function obtenerInfoVentaFactId($facturaId){
            $arrInfoVenta = $this->find('first', array('conditions' => array('Ventarapida.factura_id' => $facturaId), 'recursive' => '-1'));
            return $arrInfoVenta;
        }
        
        public function guardarInfoClienteVentaRapida($facturaId,$cliente,$identificacion,$telefono,$direccion){
            $data = array();
            
            $ventarapida = new Ventarapida();
            
            $data['factura_id'] = $facturaId;
            $data['cliente'] = $cliente;
            $data['identificacion'] = $identificacion;
            $data['telefono'] = $telefono;
            $data['direccion'] = $direccion;
            
            if($ventarapida->save($data)){
                return true;
            }else{
                return false;
            }           
        }
}
