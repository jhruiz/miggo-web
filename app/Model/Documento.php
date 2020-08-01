<?php
App::uses('AppModel', 'Model');
/**
 * Documento Model
 *
 * @property Tiposdocumento $Tiposdocumento
 * @property Empresa $Empresa
 * @property Usuario $Usuario
 * @property Anotacione $Anotacione
 */
class Documento extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'codigo';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'tiposdocumento_id' => array(
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
		'Tiposdocumento' => array(
			'className' => 'Tiposdocumento',
			'foreignKey' => 'tiposdocumento_id',
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
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_id',
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
		'Anotacione' => array(
			'className' => 'Anotacione',
			'foreignKey' => 'documento_id',
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
        
        public function guardarDocumento($tipoDocumentoId,$empresaId,$usuarioId){
            $data = array();                        
              
            $documento = new Documento();                        
            
            $data['tiposdocumento_id']=$tipoDocumentoId;
            $data['empresa_id']=$empresaId;
            $data['usuario_id']=$usuarioId;
            $data['codigo']='1';
            
            
            if($documento->save($data)){
                return $documento->id;
            }else{
                return '0';
            }            
        }
        
        public function obtenerInfoDocumentoId($documentoId){
            $infoDoc = $this->find('first', array('conditions' => array('Documento.id' => $documentoId), 'recursive' => '-1'));
            return $infoDoc;
        }
        
        public function actualizarTipoDocumento($documentoId,$tipoDocumento,$usuarioId,$fechaActual){
            $data = array();
            
            $documento = new Documento();
            
            $data['id'] = $documentoId;
            $data['tiposdocumento_id'] = $tipoDocumento;
            $data['usuario_id'] = $usuarioId;
            $data['created'] = $fechaActual;
            
            if($documento->save($data)){
                return true;
            }else{
                return false;
            }
        }
        
        public function actualizarCodigoDocumento($documentoId){
            $data = array();
            
            $documento = new Documento();
            
            $data['id'] = $documentoId;
            $data['codigo'] = $documentoId;
            
            if($documento->save($data)){
                return true;
            }else{
                return false;
            }
        }        
}
