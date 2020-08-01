<?php
App::uses('AppModel', 'Model');
/**
 * Detalledocumento Model
 *
 * @property Producto $Producto
 * @property Depositoorigen $Depositoorigen
 * @property Depositodestino $Depositodestino
 * @property Proveedore $Proveedore
 * @property Tipopago $Tipopago
 * @property Documento $Documento
 */
class Detalledocumento extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'producto_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'documento_id' => array(
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
		'Producto' => array(
			'className' => 'Producto',
			'foreignKey' => 'producto_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Proveedore' => array(
			'className' => 'Proveedore',
			'foreignKey' => 'proveedore_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Tipopago' => array(
			'className' => 'Tipopago',
			'foreignKey' => 'tipopago_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Documento' => array(
			'className' => 'Documento',
			'foreignKey' => 'documento_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
      
        public function guardarDetalleDocumento($prodId,$depOrgId,$depDestId,$costProd,$cant,$precioMax,
                $precioMin,$precioVen,$provId,$tipPagId,$numFact,$docId){
            $data=array();                        
              
            $documento = new Detalledocumento();                        
            
            $data['producto_id']=$prodId;
            $data['depositoorigen_id']=$depOrgId;
            $data['depositodestino_id']=$depDestId;
            $data['costoproducto']=$costProd;
            $data['cantidad']=$cant;
            $data['preciomaximo']=$precioMax;               
            $data['preciominimo']=$precioMin;
            $data['precioventa']=$precioVen;
            $data['proveedore_id']=$provId;
            $data['tipopago_id']=$tipPagId;
            $data['numerofactura']=$numFact;
            $data['documento_id']=$docId;            
            
            if($documento->save($data)){
                return true;
            }else{
                return false;
            }             
        }
        
        public function obtenerDetalleDocumento($documentoId){
            $arrDetalle = $this->find('all', array('conditions' => array('Detalledocumento.documento_id' => $documentoId), 'recursive' => '0'));
            return $arrDetalle;
        }
        
        public function obtenerMovimientosProducto($id){
        
		$arrMovimientos = $this->find('all', array(
		    'joins' => array(
		        array(
		            'table' => 'documentos',
		            'alias' => 'D',
		            'type' => 'INNER',
		            'conditions' => array(
		                'D.id = Detalledocumento.documento_id'
		            )
		        ), array(
		            'table' => 'tiposdocumentos',
		            'alias' => 'TD',
		            'type' => 'INNER',
		            'conditions' => array(
		                'TD.id = D.tiposdocumento_id'
		            )		        	
		        ), array(
		            'table' => 'productos',
		            'alias' => 'P',
		            'type' => 'INNER',
		            'conditions' => array(
		                'P.id = Detalledocumento.producto_id'
		            )		        
		        )
		    ),
		    'conditions' => array(
		        'Detalledocumento.producto_id' => $id
		    ),
		    'fields' => array('Detalledocumento.*', 'D.*' ,'TD.*', 'P.*'),
		    'order' => 'Detalledocumento.id'
		)); 				      
           
            return $arrMovimientos;
        }
}
