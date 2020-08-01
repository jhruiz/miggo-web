<?php
App::uses('AppModel', 'Model');
/**
 * Producto Model
 *
 * @property Categoria $Categoria
 * @property Cargueinventario $Cargueinventario
 * @property Descargueinventario $Descargueinventario
 */
class Producto extends AppModel {

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
		'categoria_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'mostrarencatalogo' => array(
			'boolean' => array(
				'rule' => array('boolean'),
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
		'Categoria' => array(
			'className' => 'Categoria',
			'foreignKey' => 'categoria_id',
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
		'Cargueinventario' => array(
			'className' => 'Cargueinventario',
			'foreignKey' => 'producto_id',
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
		'Descargueinventario' => array(
			'className' => 'Descargueinventario',
			'foreignKey' => 'producto_id',
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
 * Se obtienen lo productos de una empresa
 *
 * @var $empresId
 * @array $arrProductos
 */  
        public function obtenerProductosEmpresa($empresaId){
            $arrProductos = $this->find('all', array('conditions' => array('Producto.empresa_id' => $empresaId, 'Producto.mostrarencatalogo' => '1'), 'recursive' => '-1'));            
            return $arrProductos;
            
        }
        
/**
 * Se obtienen un producto por id
 *
 * @var $productoId
 * @array $arrDatosProducto
 */  
        public function obtenerProductoPorId($productoId){
            $arrDatosProducto = $this->find('first', array(
                'conditions' => array(
                    'Producto.id' => $productoId
                    ), 
                'recursive' => '0',
                'fields' => array('Categoria.descripcion')
                ));            
            return $arrDatosProducto;
            
        }        

/**
 * Se obtienen un producto por id para cargar al inventario
 *
 * @var $productoId
 * @array $arrDatosProducto
 */  
        public function obtenerInfoProducto($productoId){
            
            $arr_join = array(); 
            array_push($arr_join, array(
                'table' => 'categorias', 
                'alias' => 'C', 
                'type' => 'INNER',
                'conditions' => array(
                    'C.id=Producto.categoria_id')
                
            ));                      
            
            $arrDatosProducto = $this->find('first', array(
                'joins' => $arr_join,
                'conditions' => array(
                    'Producto.id' => $productoId), 
                'recursive' => 0)
                    );
            return $arrDatosProducto;                        
        } 
        
        public function obtenerInformacionProductoId($productoId){
            $arrProd = $this->find('first', array('conditions' => array('Producto.id' => $productoId), 'recursive' => '-1'));
            return $arrProd;
        }
        
        public function obtenerProductoCargueInventario($descripcionProd, $empresaId){
            $arrProducto = $this->find('all', array(
                'conditions' => array(
                    'OR' => array(
                        'LOWER(Producto.descripcion) LIKE' => '%'. strtolower($descripcionProd) . '%',
                        'Producto.codigo LIKE' => '%'. $descripcionProd . '%',
                        ),
                    'Producto.empresa_id' => $empresaId),
                'recursive' => '-1'));
            
            return $arrProducto;        
        }
        
        public function obtenerProductoCargueBarcode($barcodeProd, $empresaId){
            $arrProducto = $this->find('first', array(
                'conditions' => array(
                        'Producto.codigo LIKE' => '%'. $barcodeProd . '%',
                        'Producto.empresa_id' => $empresaId),
                'recursive' => '-1'));
            
            return $arrProducto;        
        }  
        
        public function obtenerProductoDescargueInventario($descProducto,$empresaId,$depositoId){
            $arr_join = array(); 
            array_push($arr_join, array(
                'table' => 'cargueinventarios', 
                'alias' => 'C', 
                'type' => 'INNER',
                'conditions' => array(
                    'C.producto_id=Producto.id',
                    'C.deposito_id' => $depositoId)                
            ));                                  
            
            $arrProducto = $this->find('all', array(
                'joins' => $arr_join,
                'conditions' => array(
                    'OR' => array(
                        'LOWER(Producto.descripcion) LIKE' => '%'. $descProducto . '%',
                        'LOWER(Producto.codigo) LIKE' => '%'. $descProducto . '%',
                        ),
                    'Producto.empresa_id' => $empresaId),
                'recursive' => '-1'));
            
            return $arrProducto;             
        }
        
/**
 * Se obtiene el listado de productos por empresa
 *
 * @var $empresId
 * @array $arrProductos
 */  
        public function obtenerListaProductosEmpresa($empresaId){
            $arrProductos = $this->find('list', array(
                'conditions' => array(
                    'Producto.empresa_id' => $empresaId, 
                    'Producto.mostrarencatalogo' => '1'
                    ), 
                'recursive' => '-1', 
                'order' => 'Producto.descripcion'));            
            return $arrProductos;            
        }     
        
        /**
         * Obtiene el producto por codigo
         * @param type $codigo
         * @return string
         */
        public function obtenerProductoPorCodigo($codigo){
            $result = $this->find('first', array('conditions' => array('Producto.codigo' => $codigo), 'recursive' => '-1'));
            return $result;
        }
        
        /**
         * se obtiene el producto por referencia
         * @param type $referencia
         * @return type
         */
        public function obtenerProductoPorReferencia($referencia){
            $result = $this->find('first', array('conditions' => array('Producto.referencia' => $referencia), 'recursive' => '-1'));
            return $result;
        }
        
}
