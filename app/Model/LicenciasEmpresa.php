<?php
App::uses('AppModel', 'Model');
/**
 * LicenciasEmpresa Model
 *
 * @property Licencia $Licencia
 * @property Empresa $Empresa
 * @property Estado $Estado
 */
class LicenciasEmpresa extends AppModel {

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
		)
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
		'Empresa' => array(
			'className' => 'Empresa',
			'foreignKey' => 'empresa_id',
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
 * Se obtiene la ultima licencia otorgada a la para ver su estado (activa, inactiva)
 *
 * @var $idUsuario
 */        
        public function obtenerLicenciaPorEmpresa($empresaId){
            $arrLicEmp = $this->find('first', array('conditions' => array('LicenciasEmpresa.empresa_id' => $empresaId), 'order' => 'LicenciasEmpresa.id DESC', 'recursive' => -1));
            return $arrLicEmp;
        }   
        
        /*
         * Se actualiza el estado de la licencia a Inactivo
         */
        public function actualizarEstadoLicencia($licenciaId,$estadoId){
            $data = array();
            $licenciasEmpresa = new LicenciasEmpresa();
            
            $data['id'] = $licenciaId;
            $data['estado_id'] = $estadoId;
            
            if($licenciasEmpresa->save($data)){
                return true;
            }else{
                return false;
            }
        }        
}
