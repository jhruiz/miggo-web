<?php
App::uses('AppModel', 'Model');
/**
 * Relacionempresa Model
 *
 * @property Empresa $Empresa
 */
class Relacionempresa extends AppModel {

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
                'codigo' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Debe ingresar el código para la empresa',
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
        
        public function obtenerListaEmpresasRelacion($empresaId){
            $empRelacion = $this->find('list', array('conditions' => array('Relacionempresa.empresa_id' => $empresaId), 'recursive' => '-1'));
            return $empRelacion;
        }
        
        public function obtenerEmpresaRelacionadaPorId($relacionEmpresaId){
            $empRelacion = $this->find('first', array('conditions' => array('Relacionempresa.id' => $relacionEmpresaId), 'recursive' => '-1'));
            return $empRelacion;
        }
        
        public function obtenerInfoEmpresaRelacionada($codigo,$empresaId){
            $infoEmpresaRel = $this->find('first', array(
                'conditions' => array(
                    'Relacionempresa.empresa_id' => $empresaId, 
                    'Relacionempresa.codigo' => $codigo
                    ), 
                'recursive' => '-1'));
            return $infoEmpresaRel;
        }
        
        
        /**
         * Se obtiene la informacion de una empresa relacionada
         * @param type $empresaId
         * @return type
         */
        public function obtenerDatosEmpresaRemision($empresaId){
            $infoEmpresa = $this->find('first', array('conditions' => array('Relacionempresa.empresa_id' => $empresaId), 'recursive' => '-1'));
            
            return $infoEmpresa;
        }
        
        public function obtenerInformacionEmpresas($empresaId){
            $infoEmpresa = $this->find('all', array('conditions' => array('Relacionempresa.empresa_id' => $empresaId), 'recursive' => '-1'));
            return $infoEmpresa;
        }
}
