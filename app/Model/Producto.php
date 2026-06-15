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
		),
        'Imagenesitem' => array(
			'className' => 'Imagenesitem',
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
        $arrProductos = $this->find('all', array(
            'conditions' => array(
                'Producto.empresa_id' => $empresaId, 
                'Producto.mostrarencatalogo' => '1',
                'Producto.estado' => '1'
            ), 'recursive' => '-1',
            'limit' => '100'
        ));            
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
                'Producto.empresa_id' => $empresaId,
                'Producto.estado' => '1'),
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
                'Producto.empresa_id' => $empresaId,
                'Producto.estado' => '1'),
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
    public function obtenerProductoPorCodigo($codigo, $empresaId){
        $result = $this->find('first', array('conditions' => array('Producto.codigo' => $codigo, 'Producto.estado' => '1', 'Producto.empresa_id' => $empresaId), 'recursive' => '-1'));
        return $result;
    }
    
    /**
     * se obtiene el producto por referencia
     * @param type $referencia
     * @return type
     */
    public function obtenerProductoPorReferencia($referencia, $empresaId){
        $result = $this->find('first', array('conditions' => array('Producto.referencia' => $referencia, 'Producto.estado' => '1', 'Producto.empresa_id' => $empresaId), 'recursive' => '-1'));
        return $result;
    }

    /**
     * Obtener productos para reporte
     */
    public function obtenerProductosReporte($empresaId, $filtros)
    {
        $arr_join = array();
        array_push($arr_join, array(
            'table' => 'categorias',
            'alias' => 'C',
            'type' => 'INNER',
            'conditions' => array(
                'C.id=Producto.categoria_id'),

        ));

        $arrProductos = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'Producto.*', 
                'C.descripcion', 
            ),
            'conditions' => array(
                $filtros,
                'Producto.empresa_id' => $empresaId,
            ),
            'recursive' => -1,
        ));

        return $arrProductos;

    }

    /**
     * Actualiza el estado del producto a inactivo
     */
    public function actualizarEstadoProducto($id) {
        $data = array();         
        
        $producto = new Producto();
        
        $data['id'] = $id;
        $data['estado'] = '0';
        
        if($producto->save($data)){
            return true;
        }else{
            return false;
        }
    }
   
public function obtenerTopProductosDia($empresaId) {
    // Inicializamos el modelo para obtener la conexión a la base de datos
    $FacturaModel = ClassRegistry::init('Factura');
    $db = $FacturaModel->getDataSource();
    
    // Al pasar hoy como parámetro manual de PHP, evitamos cualquier conflicto de zonas horarias
    $fechaHoy = date('Y-m-09');

$sql = "SELECT 
                p.id AS producto_id,
                p.codigo AS producto_codigo,
                p.descripcion AS producto_descripcion,
                img.url AS producto_imagen,
                SUM(fd.cantidad) AS total_vendido
            FROM facturasdetalles fd
            INNER JOIN facturas f ON fd.factura_id = f.id
            INNER JOIN productos p ON fd.producto_id = p.id
            LEFT JOIN imagenesitems img ON img.producto_id = p.id AND img.posicion = 0
            WHERE f.eliminar = 0 
              AND DATE(f.created) = '{$fechaHoy}'
              AND f.empresa_id = {$empresaId}
            GROUP BY p.id, p.codigo, p.descripcion, img.url
            ORDER BY total_vendido DESC
            LIMIT 5;";

    // Ejecución directa de la consulta
    $resultados = $db->fetchAll($sql);
    
    // Formateo del array compatible con tu front-end en CakePHP
    $topProductos = array();
    foreach ($resultados as $fila) {
        $topProductos[] = array(
            'Producto' => array(
                'id'          => $fila['p']['producto_id'],
                'codigo'      => $fila['p']['producto_codigo'],
                'descripcion' => $fila['p']['producto_descripcion'],
                'imagen'      => !empty($fila['img']['producto_imagen']) ? $fila['img']['producto_imagen'] : ''
            ),
            '0' => array(
                'total_vendido' => $fila['0']['total_vendido']
            )
        );
    }

    return $topProductos;
}
}
