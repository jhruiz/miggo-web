<?php
App::uses('AppModel', 'Model');
/**
 * Anotacione Model
 *
 * @property Usuario $Usuario
 * @property Cliente $Cliente
 */
class Anotacione extends AppModel {

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
		'cliente_id' => array(
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
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        
        public function guardarNota($desc,$usuarioId,$documentoId){
            $data=array();                        
              
            $anotacion = new Anotacione();                        
            
            $data['descripcion']=$desc;
            $data['usuario_id']=$usuarioId;
            $data['documento_id']=$documentoId;
            
            if($anotacion->save($data)){
                return true;
            }else{
                return false;
            }                          
        }
        
        public function obtenerAnotacionesPorDocumentoId($documentoId){
            $arrAnotaciones = $this->find('all', array('conditions' => array('Anotacione.documento_id' => $documentoId), 'recursive' => '0'));
            return $arrAnotaciones;
        }
}
