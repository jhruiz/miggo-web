<?php
App::uses('AppModel', 'Model');
/**
 * Precargueinventario Model
 *
 * @property Producto $Producto
 * @property Deposito $Deposito
 * @property Usuario $Usuario
 * @property Estado $Estado
 * @property Proveedore $Proveedore
 * @property Tipopago $Tipopago
 */
class Precargueinventario extends AppModel {

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
		'deposito_id' => array(
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
		'Producto' => array(
			'className' => 'Producto',
			'foreignKey' => 'producto_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Deposito' => array(
			'className' => 'Deposito',
			'foreignKey' => 'deposito_id',
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
		)
	);
        
        
        /**
        * precargueinventario method
        *
        * Registra el precargue de inventarios 
        *         
        * @param array $infoPreCargue
        * @return boolean
        */
        public static function guardarPreCargueInventario($infoPreCargue){
            
            $data=array();                        
                
            $preCargueInventario=new Precargueinventario();  
            
            $data['producto_id'] = $infoPreCargue['Cargueinventario']['producto_id'];
            $data['deposito_id'] = $infoPreCargue['depositos'];
            $data['costoproducto'] = $infoPreCargue['Cargueinventario']['costoproducto'];
            $data['cantidad'] = $infoPreCargue['Cargueinventario']['cantidad'];
            $data['preciomaximo'] = $infoPreCargue['Cargueinventario']['preciomaximo'];
            $data['preciominimo'] = $infoPreCargue['Cargueinventario']['preciominimo'];
            $data['precioventa'] = $infoPreCargue['Cargueinventario']['precioventa'];
            $data['usuario_id'] = $infoPreCargue['Cargueinventario']['usuario_id'];
            $data['estado_id'] = $infoPreCargue['estados'];
            $data['proveedore_id'] = $infoPreCargue['proveedores'];
            $data['tipopago_id'] = $infoPreCargue['tipopago'];
            $data['numerofactura'] = $infoPreCargue['Cargueinventario']['numerofactura'];         
            
            if($preCargueInventario->save($data)){
                return $preCargueInventario->id;
            }else{
                return $preCargueInventario->id;
            }
        }  
        
        /**
        * obtenerPrecargueUsuario method
        *
        * Se obtiene el registro del precargue al inventario por usuario
        *         
        * @param array $usuarioId
        * @return array
        */        
        public function obtenerPrecargueUsuario($usuarioId){
            $infoPrecargue = $this->find('all', array(
                'conditions' => array(
                    'Precargueinventario.usuario_id' => $usuarioId
                    ), 
                'recursive' => '0', 
                'order' => 'Precargueinventario.id'));
            return $infoPrecargue;
        }
        
        public function obtenerPrecargueInventario($usuarioId, $productoId){
            $infoPrecargue = $this->find('first', array(
                'conditions' => array(
                    'Precargueinventario.usuario_id' => $usuarioId, 
                    'Precargueinventario.producto_id' => $productoId),
                'recursive' => '-1'));
            return $infoPrecargue;
        }
        
        public function actualizarPrecargue($precargueinventarioId, $cantidadActual){
            $data = array();
            $precargueInventario = new Precargueinventario();
            
            $data['id'] = $precargueinventarioId;
            $data['cantidad'] = $cantidadActual;
            
            if($precargueInventario->save($data)){
                return true;                
            }else{
                return false;
            }
        }
}
