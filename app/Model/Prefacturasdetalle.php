<?php
App::uses('AppModel', 'Model');
/**
 * Prefacturasdetalle Model
 *
 * @property Cargueinventario $Cargueinventario
 * @property Prefactura $Prefactura
 */
class Prefacturasdetalle extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
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
		'cargueinventario_id' => array(
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
		'Cargueinventario' => array(
			'className' => 'Cargueinventario',
			'foreignKey' => 'cargueinventario_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Prefactura' => array(
			'className' => 'Prefactura',
			'foreignKey' => 'prefactura_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        
        public function guardarDetallePrefactura($cantidadventa,$precioventa,$cargueinventarioId,$prefactId, 
                $valorDescuento = null, $porcentajeDescuento = null, $impuesto = null){
            $data = array();
            $prefacturadetalle = new Prefacturasdetalle();
            
            $data['cantidad'] = $cantidadventa;
            $data['costoventa'] = $precioventa;
            $data['cargueinventario_id'] = $cargueinventarioId;
            $data['prefactura_id'] = $prefactId;
            $data['descuento'] = !empty($valorDescuento) ? $valorDescuento : 0;
            $data['porcentaje'] = !empty($porcentajeDescuento) ? $porcentajeDescuento : 0;
            $data['impuesto'] = !empty($impuesto) ? $impuesto : 0;
            
            if($prefacturadetalle->save($data)){                
                return $prefacturadetalle->id;
            }else{
                return '0';
            }
        }
        
        public function obtenerPrefacturaDetalleId($preFactDetId){
            $arrPreFDet = $this->find('first', array('conditions' => array('Prefacturasdetalle.id' => $preFactDetId), 'recursive' => '-1'));
            return $arrPreFDet;
        }
        
        public function obtenerDetallesPrefacturaPrefactId($prefacturaId){
            $arrDetPreFact = $this->find('first', array('conditions' => array('Prefacturasdetalle.prefactura_id' => $prefacturaId), 'recursive' => '-1'));
            return $arrDetPreFact;
        }
        
        public function obtenerProductosPrefacturaPrefactId($prefacturaId){
            $arrDetPreFact = $this->find('all', array('conditions' => array('Prefacturasdetalle.prefactura_id' => $prefacturaId), 'recursive' => '0'));
            return $arrDetPreFact;
        }
        
        
        /**
         * se obtiene el registro de detalle de prefactura por medio de la prefactura el cargue id
         * @param type $idPrefact
         * @param type $idCargueInv
         * @return type
         */
        public function obtenerCarguePrefact($idPrefact, $idCargueInv){
            $arrPrefD = $this->find('first', array(
                'conditions' => array(
                    'Prefacturasdetalle.cargueinventario_id' => $idCargueInv, 
                    'Prefacturasdetalle.prefactura_id' => $idPrefact
                    ),
                'recursive' => '-1'
                )
                    );
            return $arrPrefD;
        }
        
        /**
         * se actualiza la cantidad de unidades solicitadas
         * @param type $id
         * @param type $cantidad
         */
        public function actualizarCantidadUnidades($id, $cantidad){
            $data = array();
            $prefacturadetalle = new Prefacturasdetalle();

            $data['id'] = $id;
            $data['cantidad'] = $cantidad;

            $prefacturadetalle->save($data);
        }
        
        /**
         * Se elimina un suministro de la orden
         * @param type $id
         * @return boolean
         */
        public function eliminarRegistroPrefacturado($id){
            if($this->deleteAll(['Prefacturasdetalle.id' => $id])){
                return true;
            }else{
                return false;
            }          
        }

        /**
         * Actualiza el porcentaje y el valor del descuento aplicado sobre un producto especifico
         * @param type $id
         * @param type $valorDescuento
         * @param type $valorPorcentaje
         */
        public function actualizarPorcentajeValorDescuento($id, $valorDescuento, $valorPorcentaje){
            
            $data = array();
            $prefacturadetalle = new Prefacturasdetalle();

            $data['id'] = $id;
            $data['descuento'] = $valorDescuento;
            $data['porcentaje'] = $valorPorcentaje;

            if($prefacturadetalle->save($data)){
                return true;
            }else{
                return false;
            }            
            
        }
        
        /**
         * Obtiene todas las prefacturas
         * @return type
         */
        public function obtenerDetallePrefacturas(){
            $prefacDet = $this->find('all', array('recursive' => '-1'));
            return $prefacDet;
        }
        
        
}
