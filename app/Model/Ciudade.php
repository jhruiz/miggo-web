<?php
App::uses('AppModel', 'Model');
/**
 * Ciudade Model
 *
 * @property Paise $Paise
 * @property Cliente $Cliente
 * @property Deposito $Deposito
 * @property Empresa $Empresa
 */
class Ciudade extends AppModel {

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
		'paise_id' => array(
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
		'Paise' => array(
			'className' => 'Paise',
			'foreignKey' => 'paise_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'ciudade_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Deposito' => array(
			'className' => 'Deposito',
			'foreignKey' => 'ciudade_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Empresa' => array(
			'className' => 'Empresa',
			'foreignKey' => 'ciudade_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
        
        public function obtenerListaCiudades(){
            $ciudades = $this->find('list', array('order' => 'Ciudade.descripcion'));
            return $ciudades;
        }
        
        /**
         * Se obtiene una ciudad y el pais
         * @param type $id
         * @return type
         */
        public function obtenerCiudadPais($id){
            
            $arr_join = array(); 
            array_push($arr_join, array(
                'table' => 'paises', 
                'alias' => 'P', 
                'type' => 'INNER',
                'conditions' => array(
                    'P.id=Ciudade.paise_id'
                    )                
            ));

            $arrCiudades = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'P.id',
                    'P.descripcion',
                    'Ciudade.*'
                ),
                'conditions' => array('Ciudade.id' => $id),
                'recursive' => '-1'                
                ));            

            return $arrCiudades;        

        }

}
