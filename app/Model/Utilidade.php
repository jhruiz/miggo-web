<?php
App::uses('AppModel', 'Model');
/**
 * Utilidade Model
 *
 * @property Cargueinventario $Cargueinventario
 * @property Empresa $Empresa
 */
class Utilidade extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
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
		'Cargueinventario' => array(
			'className' => 'Cargueinventario',
			'foreignKey' => 'cargueinventario_id',
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
        
        public function guardarUtilidadProducto($crgInvId,$cantidad,$valorVenta,$utilidadBruta,$utilidadPorcentual,$empresaId,$facturaId,$costoProd){
            $data = array();
            $utilidad = new Utilidade();
            
            $data['cargueinventario_id'] = $crgInvId;
            $data['cantidad'] = $cantidad;
            $data['precioventa'] = $valorVenta;
            $data['utilidadbruta'] = $utilidadBruta;
            $data['utilidadporcentual'] = $utilidadPorcentual;
            $data['empresa_id'] = $empresaId;
            $data['factura_id'] = $facturaId;
            $data['costo_producto'] = $costoProd;

            if($utilidad->save($data)){
                return true;                
            }else{
                return false;
            }
        }
        
        public function obtenerUtilidadesPorEmpresa($fechaInicio,$fechaFin,$empresaId){
            $arr_join = array(); 
            array_push($arr_join, array(
                'table' => 'cargueinventarios', 
                'alias' => 'CI', 
                'type' => 'INNER',
                'conditions' => array(
                    'CI.id=Utilidade.cargueinventario_id'
                    )                
            ));  
            
            array_push($arr_join, array(
                'table' => 'facturas', 
                'alias' => 'F', 
                'type' => 'INNER',
                'conditions' => array(
                    'F.id=Utilidade.factura_id'
                    )                
            ));  
            
            array_push($arr_join, array(
                'table' => 'depositos', 
                'alias' => 'DP', 
                'type' => 'INNER',
                'conditions' => array(
                    'DP.id=CI.deposito_id'
                    )                
            ));
            
            array_push($arr_join, array(
                'table' => 'productos', 
                'alias' => 'P', 
                'type' => 'INNER',
                'conditions' => array(
                    'P.id=CI.producto_id'
                    )                
            ));  
            
            array_push($arr_join, array(
                'table' => 'proveedores', 
                'alias' => 'PV', 
                'type' => 'LEFT',
                'conditions' => array(
                    'PV.id=CI.proveedore_id'
                    )                
            ));  
            
            $utilidades = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'P.id',
                    'P.descripcion',
                    'F.id',
                    'F.factura',
                    'F.codigo',
                    'F.consecutivodian',
                    'DP.id',
                    'DP.descripcion',
                    'PV.nombre',
                    'CI.costoproducto',
                    'Utilidade.*',
                ),
                'conditions' => array(
                    'Utilidade.empresa_id' => $empresaId, 
                    'Utilidade.created BETWEEN ? AND ?' => array($fechaInicio, $fechaFin)), 
                'recursive' => '-1'));
            return $utilidades;
        }

        public function obtnerVentasProdServ($empresaId, $tipo, $fechaInicial = null, $fechaFinal = null){
            $filterDate = '';
            if(!empty($fechaInicial) && !empty($fechaFinal)){
                $filterDate = array('Utilidade.created BETWEEN ? AND ? ' => array($fechaInicial, $fechaFinal));
            }

            $arrTipo = $tipo == 'p' ? 'C.servicio <> 1' : 'C.servicio = 1';

            $arr_join = array();

            array_push($arr_join, array(
                'table' => 'cargueinventarios', 
                'alias' => 'CI', 
                'type' => 'INNER',
                'conditions' => array('Utilidade.cargueinventario_id=CI.id')                
            ));            

            array_push($arr_join, array(
                'table' => 'productos', 
                'alias' => 'P', 
                'type' => 'INNER',
                'conditions' => array('CI.producto_id=P.id')                
            ));            
    
            array_push($arr_join, array(
                'table' => 'categorias', 
                'alias' => 'C', 
                'type' => 'INNER',
                'conditions' => array('P.categoria_id=C.id')                
            ));    
            
            array_push($arr_join, array(
                'table' => 'depositos',
                'alias' => 'DP',
                'type' => 'INNER',
                'conditions' => array('DP.id=CI.deposito_id')
            ));
    
            $resp = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'count(Utilidade.cargueinventario_id) as contador',
                    'P.descripcion',
                    'DP.descripcion'
                ),
                'conditions' => array(
                    'Utilidade.empresa_id' => $empresaId,
                    $arrTipo,
                    $filterDate
                ),
                'group' => 'Utilidade.cargueinventario_id',
                'order' => 'contador desc',
                'limit' => '5'
            ));
    
            return $resp; 
        }


        public function obtnerVentasBodega($bodega_id, $fechaInicial = null, $fechaFinal = null){
            $filterDate = '';
            if(!empty($fechaInicial) && !empty($fechaFinal)){
                $filterDate = array('Utilidade.created BETWEEN ? AND ? ' => array($fechaInicial, $fechaFinal));
            }    

            $arr_join = array();

            array_push($arr_join, array(
                'table' => 'cargueinventarios', 
                'alias' => 'CI', 
                'type' => 'INNER',
                'conditions' => array('Utilidade.cargueinventario_id=CI.id')                
            ));            

            array_push($arr_join, array(
                'table' => 'productos', 
                'alias' => 'P', 
                'type' => 'INNER',
                'conditions' => array('CI.producto_id=P.id')                
            ));            
    
            array_push($arr_join, array(
                'table' => 'categorias', 
                'alias' => 'C', 
                'type' => 'INNER',
                'conditions' => array('P.categoria_id=C.id')                
            ));            
    
            $resp = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'count(Utilidade.cargueinventario_id) as contador',
                    'P.descripcion'
                ),
                'conditions' => array(
                    'CI.deposito_id' => $bodega_id,
                    'C.servicio <> 1',
                    $filterDate
                ),
                'group' => 'Utilidade.cargueinventario_id',
                'order' => 'contador desc',
                'limit' => '10'
            ));
    
            return $resp; 
        }        
        
        public function obtenerRotacion($fechaInicio, $fechaFin, $empresaId){
            $arr_join = array(); 
            array_push($arr_join, array(
                'table' => 'cargueinventarios', 
                'alias' => 'CI', 
                'type' => 'INNER',
                'conditions' => array(
                    'CI.id=Utilidade.cargueinventario_id'
                    )                
            ));  
            
            array_push($arr_join, array(
                'table' => 'productos', 
                'alias' => 'P', 
                'type' => 'INNER',
                'conditions' => array(
                    'P.id=CI.producto_id'
                    )                
            ));  
            
            $rotacion = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'sum(Utilidade.cantidad) AS quant',
                    'sum(Utilidade.precioventa) AS precioventa',
                    'sum(Utilidade.utilidadbruta) AS utilidadbruta',
                    'sum(Utilidade.utilidadporcentual) AS utilidadporcentual',
                    'sum(Utilidade.costo_producto) AS costoproducto',
                    'P.descripcion'
                ),
                'group' => 'Utilidade.cargueinventario_id',
                'order' => 'quant DESC',
                'conditions' => array(
                    'Utilidade.empresa_id' => $empresaId, 
                    'Utilidade.created BETWEEN ? AND ?' => array($fechaInicio, $fechaFin)), 
                'recursive' => '-1'));
                
            return $rotacion;
        }


}
