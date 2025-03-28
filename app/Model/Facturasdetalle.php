<?php
App::uses('AppModel', 'Model');
/**
 * Facturasdetalle Model
 *
 * @property Factura $Factura
 * @property Deposito $Deposito
 * @property Producto $Producto
 */
class Facturasdetalle extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'factura_id' => array(
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
		'cantidad' => array(
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
		'Factura' => array(
			'className' => 'Factura',
			'foreignKey' => 'factura_id',
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
		'Producto' => array(
			'className' => 'Producto',
			'foreignKey' => 'producto_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        
        
        public function guardarDetalleFactura($facturaId,$depositoId,$productoId,$cantidad,$costoventa,$costototal,$descuento,$porcentaje,$impuesto){

            $data = array();
            $detalleFactura = new Facturasdetalle();
            
            $data['factura_id'] = $facturaId;
            $data['deposito_id'] = $depositoId;
            $data['producto_id'] = $productoId;
            $data['cantidad'] = $cantidad;
            $data['costoventa'] = $costoventa;
            $data['costototal'] = $costototal;
            $data['descuento'] = !empty($descuento) ? $descuento : 0;
            $data['porcentaje'] = !empty($porcentaje) ? $porcentaje : 0;
            $data['impuesto'] = $impuesto;
            
            if($detalleFactura->save($data)){
                return true;
            }else{
                return false;
            }
        }
        
        public function obtenerFacturaDetalleFactId($facturaId){
			$arr_join = array(); 

			array_push($arr_join, array(
				'table' => 'facturas', 
				'alias' => 'F', 
				'type' => 'INNER',
				'conditions' => array('F.id=Facturasdetalle.factura_id')                
			));

			array_push($arr_join, array(
				'table' => 'depositos', 
				'alias' => 'D', 
				'type' => 'INNER',
				'conditions' => array('D.id=Facturasdetalle.deposito_id')                
			));

			array_push($arr_join, array(
				'table' => 'productos', 
				'alias' => 'P', 
				'type' => 'INNER',
				'conditions' => array('P.id=Facturasdetalle.producto_id')                
			));

			array_push($arr_join, array(
				'table' => 'categorias', 
				'alias' => 'C', 
				'type' => 'INNER',
				'conditions' => array('C.id=P.categoria_id')                
			));

            $infoDetFact = $this->find('all', array(
				'joins' => $arr_join,
				'fields' => array(
					'Facturasdetalle.*',
					'F.*',
					'D.*',
					'P.*',
					'C.*'
				),
				'conditions' => array(
					'Facturasdetalle.factura_id' => $facturaId
				), 
				'recursive' => '0'));

            return $infoDetFact;
        }

		/**
		 * Retorna el total de una factura sumando la información en el detalle
		 */
		public function totalFactura($facturaId) {
			$total = $this->find('all', array(
				'fields' => array(
					'Facturasdetalle.*',
					'Facturasdetalle.id',
					'sum(Facturasdetalle.costototal) AS ctotal'
				), 
				'conditions' => array(
					'Facturasdetalle.factura_id' => $facturaId
				)
			));

			return $total;

		}
}
