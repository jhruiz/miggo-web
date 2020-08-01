<?php
App::uses('AppModel', 'Model');
/**
 * Descargueinventario Model
 *
 * @property Producto $Producto
 * @property Deposito $Deposito
 * @property Usuario $Usuario
 * @property Tipopago $Tipopago
 */
class Descargueinventario extends AppModel {

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
		)
	);
        
        public function guardarProductoDescargue($productoId,$depositoId,$cantidadProd,$usuarioId,$empresa_id){
            $data = array();
            $descargueInventario = new Descargueinventario();
            
            $data['producto_id'] = $productoId;
            $data['deposito_id'] = $depositoId;
            $data['cantidaddescargue'] = $cantidadProd;
            $data['usuario_id'] = $usuarioId;
            $data['empresa_id'] = $empresa_id;
            
            if($descargueInventario->save($data)){
                return $descargueInventario->id;
            }else{
                return '0';
            }
        }
        
        public function obtenerDescargueInventario($empresaId){
            $descargueInventario = $this->find('all', array('conditions' => array('Descargueinventario.empresa_id' => $empresaId), 'recursive' => '0'));
            return $descargueInventario;
        }
        
        public function actualizarCantidadDescargue($descargueId,$cantidad){
            $data = array();
            $descargueInventario = new Descargueinventario();
            
            $data['id'] = $descargueId;
            $data['cantidaddescargue'] = $cantidad;
            
            if($descargueInventario->save($data)){
                return true;
            }else{
                return false;
            }
        }
        
        public function infoDescargueInventario($descargueId){
            $infoDescargue = $this->find('first', array('conditions' => array('Descargueinventario.id' => $descargueId), 'recursive' => '-1'));
            return $infoDescargue;
        }
        
        public function obtenerInfoDescargue($empresaId){
            $infoDescargue = $this->find('all', array('conditions' => array('Descargueinventario.empresa_id' => $empresaId), 'recursive' => '-1'));
            return $infoDescargue;
        }
}
