<?php
App::uses('AppModel', 'Model');
/**
 * Impuesto Model
 *
 * @property Empresa $Empresa
 * @property Cargueinventario $Cargueinventario
 */
class Impuesto extends AppModel {

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
		'Empresa' => array(
			'className' => 'Empresa',
			'foreignKey' => 'empresa_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

        
/**
 * Se obtienen los impuestos que se han creado por empresa
 *
 * @var $empresaId
 * return $arrImpuestos
 */        
        public function obtenerImpuestosEmpresa($empresaId){
            $arrImpuestos = $this->find('list', array('conditions' => array('Impuesto.empresa_id' => $empresaId)));
            return $arrImpuestos;
        }
        
        
/**
 * Se obtienen la informacion completa de los impuestos que se han creado por empresa
 *
 * @var $empresaId
 * return $arrImpuestos
 */            
        public function obtenerImpuestosInfo($empresaId){
            $arrImpuestos = $this->find('all', array('conditions' => array('Impuesto.empresa_id' => $empresaId)));
            return $arrImpuestos;           
        }
        
        public function obtenerImpuestoPorNombre($impNombre, $empresaId){
            $arrImp = $this->find('all', array('conditions' => array('Impuesto.descripcion' => $impNombre, 'Impuesto.empresa_id' => $empresaId), 'recursive' => '-1'));
            return $arrImp;            
        }

}
