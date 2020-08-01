<?php
App::uses('AppModel', 'Model');
/**
 * Tipopago Model
 *
 * @property Estado $Estado
 * @property Empresa $Empresa
 * @property Cargueinventario $Cargueinventario
 * @property Descargueinventario $Descargueinventario
 */
class Tipopago extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'descripcion';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'estado_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'empresa_id' => array(
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
		'Estado' => array(
			'className' => 'Estado',
			'foreignKey' => 'estado_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Empresa' => array(
			'className' => 'Empresa',
			'foreignKey' => 'empresa_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

        
/**
 * obtienen los tipos de pagos creados por empresa
 *
 * @var $empresaId
 * return $listTipoPago
 */        
        public function obtenerListaTiposPagos($empresaId){
            $listTipoPago = $this->find('list', array('conditions' => array('Tipopago.empresa_id' => $empresaId)));
            return $listTipoPago;                    
        }
        
        public function obtenerTipoPagoPorId($id){
            $infoTipoPago = $this->find('first', array('conditions' => array('Tipopago.id' => $id), 'recursive' => '-1'));
            return $infoTipoPago;
        }
        
        public function obtenerListaTiposPagosAll(){
            $listTipoPago = $this->find('list', array('order' => array('Tipopago.descripcion')));
            return $listTipoPago;                    
        }        

}
