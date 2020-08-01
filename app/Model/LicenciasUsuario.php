<?php
App::uses('AppModel', 'Model');
/**
 * LicenciasUsuario Model
 *
 * @property Licencia $Licencia
 * @property Usuario $Usuario
 * @property Estado $Estado
 */
class LicenciasUsuario extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'licencia_id' => array(
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
		'codigo' => array(
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
		'Licencia' => array(
			'className' => 'Licencia',
			'foreignKey' => 'licencia_id',
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
 * Se obtiene la ultima licencia otorgada al usuario para ver su estado (activa, inactiva)
 *
 * @var $idUsuario
 */        
        public function obtenerLicenciaPorUsuario($idUsuario){
            $arrLicUsr = $this->find('first', array('conditions' => array('LicenciasUsuario.usuario_id' => $idUsuario), 'order' => 'LicenciasUsuario.id DESC', 'recursive' => -1));
            return ($arrLicUsr);
        }
}
