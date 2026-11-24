<?php
App::uses('AppModel', 'Model');
/**
 * Empresa Model
 *
 * @property Ciudade $Ciudade
 * @property Impuesto $Impuesto
 * @property Tipopago $Tipopago
 * @property Deposito $Deposito
 * @property Tipodeposito $Tipodeposito
 * @property Perfile $Perfile
 * @property Usuario $Usuario
 */
class Empresa extends AppModel {

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
		// Perfil tributario (retenciones en ventas)
		'tipo_persona' => array(
			'inList' => array(
				'rule' => array('inList', array('NATURAL', 'JURIDICA')),
				'message' => 'Seleccione si la empresa es persona natural o jurídica.',
			),
		),
		'declarante_renta' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				'message' => 'Valor no válido para declarante de renta.',
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
		'Ciudadesmiggo' => array(
			'className' => 'Ciudadesmiggo',
			'foreignKey' => 'ciudade_id',
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
		'Impuesto' => array(
			'className' => 'Impuesto',
			'foreignKey' => 'empresa_id',
			'dependent' => false,
			'conditions' => array('Impuesto.clase' => 'IMPUESTO'),
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Tipopago' => array(
			'className' => 'Tipopago',
			'foreignKey' => 'empresa_id',
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
			'foreignKey' => 'empresa_id',
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
		'Tipodeposito' => array(
			'className' => 'Tipodeposito',
			'foreignKey' => 'empresa_id',
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
		'Perfile' => array(
			'className' => 'Perfile',
			'foreignKey' => 'empresa_id',
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
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'empresa_id',
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
        
        /**
         * Enlaza las responsabilidades fiscales del RUT a la empresa.
         * Se enlaza solo donde se necesita (edición del perfil tributario) para no
         * agregar consultas a los demás usos del modelo Empresa.
         */
        public function vincularResponsabilidades() {
            $this->bindModel(array('hasAndBelongsToMany' => array(
                'Tiposresponsabilidade' => array(
                    'className'             => 'Tiposresponsabilidade',
                    'joinTable'             => 'empresas_tiposresponsabilidades',
                    'foreignKey'            => 'empresa_id',
                    'associationForeignKey' => 'tiposresponsabilidade_id',
                    'unique'                => 'keepExisting',
                ),
            )), false);
        }

        /**
         * Ids de las responsabilidades fiscales de una empresa.
         *
         * @param  int $empresaId
         * @return array
         */
        public function obtenerResponsabilidadesIds($empresaId) {
            $filas = $this->query(
                'SELECT tiposresponsabilidade_id FROM empresas_tiposresponsabilidades WHERE empresa_id = ?',
                array((int) $empresaId)
            );
            $ids = array();
            foreach ($filas as $f) {
                $ids[] = (int) $f['empresas_tiposresponsabilidades']['tiposresponsabilidade_id'];
            }
            return $ids;
        }

        public function obtenerEmpresaPorId($empresaId){

            $datosEmpresa = $this->find('first', array(
				'conditions' => array(
					'Empresa.id' => $empresaId
				), 
				'recursive' => '-1',
				'fields' => array(
					'Empresa.*'
				)
			));
            return $datosEmpresa;
        }

		/**
		 * Obtiene el listado completo de emrpesas
		 */
		public function obtenerListadoEmpresas() {
			return $this->find('list');
		}

}
