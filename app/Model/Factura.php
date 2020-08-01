<?php
App::uses('AppModel', 'Model');
/**
 * Factura Model
 *
 * @property Cliente $Cliente
 * @property Empresa $Empresa
 * @property Usuario $Usuario
 * @property Tipopago $Tipopago
 * @property Facturasdetalle $Facturasdetalle
 */
class Factura extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'codigo' => array(
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
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
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
		),
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_id',
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
		'Cuenta' => array(
			'className' => 'Cuenta',
			'foreignKey' => 'cuenta_id',
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
		'Facturasdetalle' => array(
			'className' => 'Facturasdetalle',
			'foreignKey' => 'factura_id',
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
		'FacturasNotafactura' => array(
			'className' => 'FacturasNotafactura',
			'foreignKey' => 'factura_id',
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
       
        
        public function guardarfactura($clienteId,$empresaId,$usuarioId,$fechaVence,$tipoPagoId,$pagoContado,$pagoCredito,
                $documentoId,$empRelacionada,$ordentrabajo, $esFactura, $cuenta_id, $observacion){
            $data = array();
            $factura = new Factura();

            if($clienteId != "" && $clienteId != null){
                $data['cliente_id'] = $clienteId;
            }            
            
            if($empRelacionada == ""){
                $data['empresa_id'] = $empresaId;
            }else{
                $data['relacionempresa'] = $empRelacionada;
            }
            
            $data['usuario_id'] = $usuarioId;
            $data['fechavence'] = $fechaVence;            
            $data['tipopago_id'] = $tipoPagoId;
            $data['pagocontado'] = $pagoContado;
            $data['pagocredito'] = $pagoCredito;
            $data['documento_id'] = $documentoId;
            
            if(!empty($ordentrabajo)){
                $data['ordentrabajo_id'] = $ordentrabajo;
            }
            
            $data['factura'] = $esFactura;
            $data['codigo'] = $esFactura;
            $data['cuenta_id'] = $cuenta_id;            
            $data['observacion'] = $observacion;            
            
            if($factura->save($data)){                
                return $factura->id;
            }else{
                return '0';
            }
            
        } 
        
        public function obtenerInfoFacturaPorId($facturaId){
            $infoFact = $this->find('first', array('conditions' => array('Factura.id' => $facturaId), 'recursive' => '0'));
            return $infoFact;
        }
        
        public function actualizarConsecutivoDianFactura($facturaId,$consFact){
            $data = array();
            $factura = new Factura();
            
            $data['id'] = $facturaId;
            $data['consecutivodian'] = $consFact;
            
            if($factura->save($data)){
                return true;
            }else{
                return false;
            }
        }          
        
        public function obtenerFacturasCierreDiario($fechaInicio,$fechaFin,$empresaId,$cuenta = null){

            $arrFilter = [];
            if(!empty($cuenta)){
                $arrFilter['Factura.cuenta_id'] = $cuenta;
            }            
            
            $arrFilter['Factura.created BETWEEN ? AND ?'] = array($fechaInicio,$fechaFin);
            $arrFilter['Factura.empresa_id'] = $empresaId;
            
            $detFact = $this->find('all', array(
                'conditions' =>
                    $arrFilter
                    ,
                'order' => 'Factura.created',
                'recursive' => '0'
                ));
            return $detFact;
        }
        
        public function actualizarCodigoFactura($facturaId){
            $data = array();
            $factura = new Factura();
            
            $data['id'] = $facturaId;
            $data['codigo'] = $facturaId;
            
            if($factura->save($data)){
                return true;
            }else{
                return false;
            }
        }
        
        public function obtenerFacturasOrdenesServicios($filter){
        $arr_join = array(); 
        array_push($arr_join, array(
            'table' => 'facturasdetalles', 
            'alias' => 'FD', 
            'type' => 'INNER',
            'conditions' => array(
                'FD.factura_id=Factura.id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'productos', 
            'alias' => 'PR', 
            'type' => 'INNER',
            'conditions' => array(
                'PR.id=FD.producto_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'categorias', 
            'alias' => 'CT', 
            'type' => 'INNER',
            'conditions' => array(
                'CT.id=PR.categoria_id'
                )                
        ));        
        
        array_push($arr_join, array(
            'table' => 'ordentrabajos', 
            'alias' => 'OT', 
            'type' => 'INNER',
            'conditions' => array(
                'OT.id=Factura.ordentrabajo_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'vehiculos',
            'alias' => 'VH',
            'type' => 'INNER',
            'conditions' => array(
                'VH.id=OT.vehiculo_id'
            )
        ));
                
        array_push($arr_join, array(
            'table' => 'usuarios', 
            'alias' => 'US',
            'type' => 'INNER',
            'conditions' => array(
                'US.id=OT.usuario_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'estadopagomecanicos',
            'alias' => 'EPM',
            'type' => 'LEFT',
            'conditions' => array(
                'Factura.estadopagomecanico_id' => 'EPM.id'
            )
        ));
        
        $ordenesT = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'FD.cantidad',
                'FD.costoventa',
                'FD.costototal',
                'PR.id',
                'PR.codigo',
                'PR.descripcion',
                'OT.id',
                'OT.codigo',
                'US.id',
                'US.nombre',
                'US.identificacion',
                'VH.placa',
                'EPM.*',
                'Factura.*',
            ),
            'conditions' => array('CT.servicio' => '1', $filter),
            'recursive' => '-1',
            'order' => 'Factura.id desc'
            ));            
        
        return $ordenesT;               
        }
        
    public function obtenerFacturas($filter){
        $arr_join = array(); 
        array_push($arr_join, array(
            'table' => 'facturasdetalles', 
            'alias' => 'FD', 
            'type' => 'INNER',
            'conditions' => array(
                'FD.factura_id=Factura.id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'depositos', 
            'alias' => 'DP', 
            'type' => 'INNER',
            'conditions' => array(
                'FD.deposito_id=DP.id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'clientes', 
            'alias' => 'CL', 
            'type' => 'LEFT',
            'conditions' => array(
                'CL.id=Factura.cliente_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'usuarios', 
            'alias' => 'US',
            'type' => 'INNER',
            'conditions' => array(
                'US.id=Factura.usuario_id'
                )                
        ));
        
        array_push($arr_join, array(
            'table' => 'productos', 
            'alias' => 'PR', 
            'type' => 'INNER',
            'conditions' => array(
                'PR.id=FD.producto_id'
                )                
        ));

        if(!empty($filter['FCV.tipopago_id'])){
            array_push($arr_join, array(
                'table' => 'factura_cuenta_valores',
                'alias' => 'FCV',
                'type' => 'LEFT',
                'conditions' => array(
                    'FCV.factura_id = Factura.id'
                )
            ));
        }

        $facturas = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'FD.cantidad',
                'FD.costoventa',
                'FD.costototal',
                'CL.id',
                'CL.nombre',
                'CL.nit',
                'PR.id',
                'PR.codigo',
                'PR.descripcion',
                'US.id',
                'US.nombre',
                'US.identificacion',
                'DP.id',
                'DP.descripcion',
                'Factura.*'
            ),
            'conditions' => array($filter),
            'recursive' => '-1'                
            ));         
        
        return $facturas;                
    }
    
    /**
     * Actualiza el valor de la venta de la factura
     * @param type $facturaId
     * @param type $pagoContado
     * @return boolean
     */
    public function actualizarValorVentaTotal($facturaId, $pagoContado){
        $data = array();
        $factura = new Factura();

        $data['id'] = $facturaId;
        $data['pagocontado'] = $pagoContado;

        if($factura->save($data)){
            return true;
        }else{
            return false;
        }        
    }
    
    /**
     * Obtiene las facturas y todos los pagos realizados a cada una
     * @param type $fechaInicio
     * @param type $fechaFin
     * @param type $empresaId
     * @param type $cuenta
     * @return type
     */
    public function obtenerFacturasTipoPagos($fechaInicio, $fechaFin, $empresaId, $cuenta){

        $arrFilter = [];
        if(!empty($cuenta)){
            $arrFilter['FCV.cuenta_id'] = $cuenta;
        }            

        $arrFilter['Factura.created BETWEEN ? AND ?'] = array($fechaInicio,$fechaFin);
        $arrFilter['Factura.empresa_id'] = $empresaId;

        $arr_join = array(); 
        array_push($arr_join, array(           
            'table' => 'factura_cuenta_valores', 
            'alias' => 'FCV', 
            'type' => 'INNER',
            'conditions' => array(
                'FCV.factura_id=Factura.id'
                )                
        ));            

        $detFact = $this->find('all', array(
            'joins' => $arr_join,
            'conditions' =>
                $arrFilter
                ,
            'fields' => array(
                'Factura.*',
                'Cliente.*',
                'Usuario.*',
                'FCV.*',
            ),
            'order' => 'Factura.created',
            'recursive' => '0'
            ));
        
        return $detFact;        
    }
    
    public function obtenerIndexFacturas($filtros){
           
            $arr_join = array(); 

            array_push($arr_join, array(           
                'table' => 'clientes', 
                'alias' => 'C', 
                'type' => 'LEFT',
                'conditions' => array(
                    'C.id=Factura.cliente_id'
                    )                
            ));    

            array_push($arr_join, array(           
                'table' => 'usuarios', 
                'alias' => 'U', 
                'type' => 'INNER',
                'conditions' => array(
                    'U.id=Factura.usuario_id'
                    )                
            ));   
            
            array_push($arr_join, array(
                'table' => 'ordentrabajos',
                'alias' => 'O',
                'type' => 'LEFT',
                'conditions' => array(
                    'O.id = Factura.ordentrabajo_id' 
                )
            ));   

            if(!empty($filtros['FCV.tipopago_id'])){
                array_push($arr_join, array(
                    'table' => 'factura_cuenta_valores',
                    'alias' => 'FCV',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'FCV.factura_id = Factura.id'
                    )
                ));
            }
            
            
            array_push($arr_join, array(
                'table' => 'vehiculos',
                'alias' => 'V',
                'type' => 'LEFT',
                'conditions' => array(
                    'V.id = O.vehiculo_id' 
                )
            ));
                            
            $listadoOficinasUsuario = array(
                'joins' => $arr_join,   
                'fields' => array(
                    'C.*',
                    'U.*',
                    'V.*',
                    'Factura.*'
                    ),
                'conditions' => array(
                    $filtros
                ),
//                'limit' => '20',
                'order' => 'Factura.id DESC',
                'group' => 'Factura.id',
                'recursive' => -1
                );                                 

            return $listadoOficinasUsuario;
    }
    
    public function actualizarEstadoPagoMec($id, $estado, $dateAct){
        $data = array();
        $factura = new Factura();
        
        $data['id'] = $id;
        $data['estadopagomecanico_id'] = $estado;
        $data['fechapagoservicio'] = $dateAct;            
        
        if($factura->save($data)){
            return true;
        }else{
            return false;
        }
    } 
    public function obtenerFacturasClientes($empresaId, $filtros){

        $arr_join = array(); 

        array_push($arr_join, array(           
            'table' => 'clientes', 
            'alias' => 'C', 
            'type' => 'INNER',
            'conditions' => array(
                'C.id=Factura.cliente_id'
                )                
        ));    
        
        array_push($arr_join, array(
            'table' => 'ordentrabajos',
            'alias' => 'O',
            'type' => 'LEFT',
            'conditions' => array(
                'O.id = Factura.ordentrabajo_id' 
            )
        ));  

        array_push($arr_join, array(           
            'table' => 'usuarios', 
            'alias' => 'U', 
            'type' => 'LEFT',
            'conditions' => array(
                'U.id=O.usuario_id'
                )                
        ));   
        
        array_push($arr_join, array(
            'table' => 'vehiculos',
            'alias' => 'V',
            'type' => 'LEFT',
            'conditions' => array(
                'V.id = O.vehiculo_id' 
            )
        ));
                        
        $facturasCliente = $this->find('all', array(
            'joins' => $arr_join,   
            'fields' => array(
                'COUNT(Factura.cliente_id) as conteo',
                'SUM(Factura.pagocontado) as valor',
                'C.*',
                'U.*',
                'V.*',
                'Factura.*'
                ),
            'conditions' => array(
                $filtros
            ),
            'limit' => '20',
            'order' => 'valor DESC, Factura.cliente_id, conteo DESC',
            'group' => 'Factura.cliente_id, O.usuario_id',
            'recursive' => -1
            ));           
            
            return $facturasCliente;

    }   

    public function obtenerFactuasClientes($empresaId, $fechaInicial = null, $fechaFinal = null) {
        $filterDate = '';
        if(!empty($fechaInicial) && !empty($fechaFinal)){
            $filterDate = array('Factura.created BETWEEN ? AND ? ' => array($fechaInicial, $fechaFinal));
        }

        $arr_join = array();

        array_push($arr_join, array(
            'table' => 'clientes', 
            'alias' => 'C', 
            'type' => 'INNER',
            'conditions' => array('Factura.cliente_id=C.id')                
        ));            

        $resp = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'count(Factura.cliente_id) as contador',
                'C.nombre'
            ),
            'conditions' => array(
                'Factura.empresa_id' => $empresaId,
                'C.estadisticas' => '1',
                $filterDate
            ),
            'group' => 'Factura.cliente_id',
            'order' => 'contador desc',
            'limit' => '5'
        ));

        return $resp;         
    }
    
}
