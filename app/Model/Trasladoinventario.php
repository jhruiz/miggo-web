<?php
App::uses('AppModel', 'Model');
/**
 * Trasladoinventario Model
 *
 * @property Producto $Producto
 * @property Depositoorigen $Depositoorigen
 * @property Depositodestino $Depositodestino
 * @property Usuario $Usuario
 * @property Empresa $Empresa
 */
class Trasladoinventario extends AppModel {

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
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_id',
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
		)
	);
        
        public function obtenerTrasladoInventario($empresaId){        
            $trasladoInventario = $this->find('all', array('conditions' => array('Trasladoinventario.empresa_id' => $empresaId), 'recursive' => '0'));
            return $trasladoInventario;
        }
        
        
        /*Se guarda el producto seleccionado para el traslado de deposito*/
        public function guardarProductoTraslado($productoId,$depositoorigenId,$depositodestinoId,$cantidadProd,$usuarioId,$empresa_id){
            $data = array();
            $trasladoInventario = new Trasladoinventario();
            
            $data['producto_id'] = $productoId;
            $data['depositoorigen_id'] = $depositoorigenId;
            $data['depositodestino_id'] = $depositodestinoId;
            $data['cantidadtraslado'] = $cantidadProd;
            $data['usuario_id'] = $usuarioId;
            $data['empresa_id'] = $empresa_id;
            
            if($trasladoInventario->save($data)){
                return $trasladoInventario->id;
            }else{
                return '0';
            }
        }
        
        /*Se obtiene la información del traslado del inventario*/
        public function infoTrasladoInventario($trasladoId){
            $infoTraslado = $this->find('first', array('conditions' => array('Trasladoinventario.id' => $trasladoId), 'recursive' => '-1'));
            return $infoTraslado;
        }     
        
        /*
         * Se actualiza la cantidad ingresada en el campo cantidad
         */
        public function actualizarCantidadTraslado($trasladoId,$cantidad){
            $data = array();
            $trasladoInventario = new Trasladoinventario();
            
            $data['id'] = $trasladoId;
            $data['cantidadtraslado'] = $cantidad;
            
            if($trasladoInventario->save($data)){
                return true;
            }else{
                return false;
            }
        }   
        
        /*
         * Se obtiene la información del traslado de inventario que se está realizando
         */
        public function obtenerInfoTrasladoInventario($empresaId){
            $infoTraslado = $this->find('all', array('conditions', array('Trasladoinventario.empresa_id' => $empresaId), 'recursive' => '-1'));
            return $infoTraslado;
        }
        
}
