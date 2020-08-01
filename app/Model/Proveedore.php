<?php
App::uses('AppModel', 'Model');
/**
 * Proveedore Model
 *
 * @property Ciudade $Ciudade
 * @property Usuario $Usuario
 * @property Estado $Estado
 */
class Proveedore extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nombre';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'ciudade_id' => array(
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
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Ciudade' => array(
			'className' => 'Ciudade',
			'foreignKey' => 'ciudade_id',
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
		),
		'Estado' => array(
			'className' => 'Estado',
			'foreignKey' => 'estado_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        
        
/**
 * Se obtienen los proveedores relacionados a una empresa
 *
 * @var $empresaId
 * return $arrProveedores
 */        
        public function obtenerProveedoresEmpresa($empresaId){
            $arrProveedores = $this->find('list', array('conditions' => array('Proveedore.empresa_id' => $empresaId)));
            return $arrProveedores;
        }
        
        /**
         * obtiene la informacion del proveedor por id
         * @param type $id
         * @return type
         */
        public function obtenerProveedorPorId($id){
            $infoProveedor = $this->find('first', array('conditions' => array('Proveedore.id' => $id), 'recursive' => '-1'));
            return $infoProveedor;
        }
        
}
