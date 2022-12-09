<?php
App::uses('AppModel', 'Model');
/**
 * Prefactura Model
 *
 * @property Usuario $Usuario
 * @property Cliente $Cliente
 * @property Prefacturasdetalle $Prefacturasdetalle
 */
class Prefactura extends AppModel {

/**
 * Validation rules
 *
 * @var array
 * 
 */
	public $validate = array(
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
		'cliente_id' => array(
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
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
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
		'Prefacturasdetalle' => array(
			'className' => 'Prefacturasdetalle',
			'foreignKey' => 'prefactura_id',
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
        
        public function guardarPrefactura($usuarioId,$clienteId,$idOrdenT = null){
            $data = array();
            
            $prefactura = new Prefactura();
            
            $data['usuario_id'] = $usuarioId;
            if($clienteId != "" && $clienteId != NULL){
                $data['cliente_id'] = $clienteId;
            }
            
            if(!empty($idOrdenT)){
                $data['ordentrabajo_id'] = $idOrdenT;
            }
            
            if($prefactura->save($data)){                
                return $prefactura->id;
            }else{
                return '0';
            }
        }
        
        public function obtenerPrefacturaId($clienteId){
            $arrPrefactura = $this->find('first', array(
                'conditions' => array(
                    'Prefactura.cliente_id' => $clienteId,
                    'Prefactura.eliminar' => '0'
                ), 
                'recursive' => '-1'));
            return $arrPrefactura;
        }
        
        /**
         * obtiene la prefactura por medio de la orden de trabajo
         * @param type $ordenId
         * @return type
         */
        public function obtenerPrefactPorOrden($ordenId){
            $arrPrefact = $this->find('first', array(
                'conditions' => array(
                    'Prefactura.ordentrabajo_id' => $ordenId,
                    'Prefactura.eliminar' => '0'
                ), 'recursive' => '-1'));
            return $arrPrefact;
        }
        
        /**
         * Se obtiene la informacion de la prefactura por id
         * @param type $id
         * @return type
         */
        public function obtenerPrefacturaPorId($id){
            $arrPrefact = $this->find('first', array('conditions' => array('Prefactura.id' => $id), 'recursive' => '-1'));
            return $arrPrefact; 
        }
        
        /**
         * obtiene el listado de prefacturas de una empresa
         */
        public function obtenerPrefacturas($usuarioId, $placa, $cliente, $empresaId = null){
            
            $filters = [];
            // Si la prefactura esta en estado eliminar 0 se muestra el registro 
            $filters['Prefactura.eliminar'] = 0;

            // if(!empty($usuarioId)){
            //     $filters['Prefactura.usuario_id'] = $usuarioId;                
            // }
            
            $filters['U.empresa_id'] = $empresaId;                

            if(!empty($placa)){
                $filters['LOWER(VH.placa) LIKE'] = '%' . strtolower($placa) . '%';
            }
            
            if(!empty($cliente)){
                $filters['LOWER(CL.nombre) LIKE'] = '%'. strtolower($cliente) .'%';
            }

            if(!empty($empresaId)){
                $filters['U.empresa_id'] = $empresaId;
            }
            
            $arr_join = [];
            
            array_push($arr_join, array(
                'table' => 'ordentrabajos',
                'alias' => 'OT',
                'type' => 'LEFT',
                'conditions' => array(
                    'OT.id = Prefactura.ordentrabajo_id' 
                )
            ));
            
            array_push($arr_join, array(
                'table' => 'vehiculos',
                'alias' => 'VH',
                'type' => 'LEFT',
                'conditions' => array(
                    'VH.id = OT.vehiculo_id'
                )
            ));    
            
            array_push($arr_join, array(
                'table' => 'clientes',
                'alias' => 'CL',
                'type' => 'LEFT',
                'conditions' => array(
                    'CL.id = Prefactura.cliente_id'
                )
            ));                      
            
            array_push($arr_join, array(
                'table' => 'usuarios',
                'alias' => 'U',
                'type' => 'LEFT',
                'conditions' => array(
                    'U.id = Prefactura.usuario_id'
                )
            ));                      

            $prefacturas = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'CL.id',
                    'CL.nombre',
                    'VH.placa',
                    'Prefactura.*'
                ),
                'conditions' => array($filters),
                'recursive' => '-1'                
                ));  
            
            return $prefacturas;
        }


        /**
         * Obtiene la información de las prefacturas para el reporte
         */
        public function obtenerPrefacturasReporte( $placa, $cliente, $empresaId ){
            $arr_join = [];
            array_push($arr_join, array(
                'table' => 'ordentrabajos',
                'alias' => 'OT',
                'type' => 'LEFT',
                'conditions' => array(
                    'Prefactura.ordentrabajo_id = OT.id'
                )
            ));   

            array_push($arr_join, array(
                'table' => 'clientes',
                'alias' => 'CL',
                'type' => 'LEFT',
                'conditions' => array(
                    'Prefactura.cliente_id = CL.id  '
                )
            ));    
            
            array_push($arr_join, array(
                'table' => 'vehiculos',
                'alias' => 'VH',
                'type' => 'LEFT',
                'conditions' => array(
                    'OT.vehiculo_id = VH.id'
                )
            ));   

            array_push($arr_join, array(
                'table' => 'estadosprefacturas',
                'alias' => 'ES',
                'type' => 'LEFT',
                'conditions' => array(
                    'Prefactura.estadoprefactura_id = ES.id'
                )
            ));

            array_push($arr_join, array(
                'table' => 'prefacturasdetalles',
                'alias' => 'PD',
                'type' => 'INNER',
                'conditions' => array(
                    'Prefactura.id = PD.prefactura_id'
                )
            ));    
            
            array_push($arr_join, array(
                'table' => 'cargueinventarios',
                'alias' => 'CI',
                'type' => 'INNER',
                'conditions' => array(
                    'PD.cargueinventario_id = CI.id'
                )
            ));   

            array_push($arr_join, array(
                'table' => 'productos',
                'alias' => 'PR',
                'type' => 'INNER',
                'conditions' => array(
                    'CI.producto_id = PR.id'
                )
            ));
            
            array_push($arr_join, array(
                'table' => 'usuarios',
                'alias' => 'U',
                'type' => 'INNER',
                'conditions' => array(
                    'U.id = Prefactura.usuario_id'
                )
            ));             
            
            $arrProductos = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'Prefactura.*', 
                    'CL.nombre', 
                    'VH.placa', 
                    'ES.descripcion', 
                    'PR.codigo', 
                    'PR.descripcion',  
                    'PD.costoventa', 
                    'U.id',
                    'U.nombre',
                    'U.identificacion',
                    'U.empresa_id'

                ),
                'conditions' => array(
                    // 'LOWER(VH.placa) LIKE' => "%".$placa."%",
                    'LOWER(CL.nombre) LIKE' => "%".$cliente."%",
                    'U.empresa_id' => $empresaId,
                    'Prefactura.eliminar' => '0'
                ),
                'recursive' => -1,
                'order' => array('Prefactura.id')
            )); 
            return $arrProductos;
            
        }
        
        public function obtenerPrefacturaDetalle($prefacturaId){
            $arr_join = array();                  
            
            array_push($arr_join, array(
                'table' => 'prefacturasdetalles',
                'alias' => 'PFD',
                'type' => 'INNER',
                'conditions' => array(
                    'PFD.prefactura_id=Prefactura.id'
                )
            ));   
            
            array_push($arr_join, array(
                'table' => 'usuarios',
                'alias' => 'U',
                'type' => 'INNER',
                'conditions' => array(
                    'U.id = Prefactura.usuario_id'
                )
            ));            
            
            array_push($arr_join, array(
                'table' => 'clientes',
                'alias' => 'C',
                'type' => 'LEFT',
                'conditions' => array(
                    'C.id=Prefactura.cliente_id'
                )
            ));            
            
            array_push($arr_join, array(
                'table' => 'empresas',
                'alias' => 'EM',
                'type' => 'INNER',
                'conditions' => array(
                    'EM.id=U.empresa_id'
                )
            ));            
            
            array_push($arr_join, array(
                'table' => 'ciudades',
                'alias' => 'CIU',
                'type' => 'INNER',
                'conditions' => array(
                    'CIU.id=EM.ciudade_id'
                )
            ));            
            
            array_push($arr_join, array(
                'table' => 'cargueinventarios',
                'alias' => 'CI',
                'type' => 'INNER',
                'conditions' => array(
                    'CI.id=PFD.cargueinventario_id'
                )
            ));            
            
            array_push($arr_join, array(
                'table' => 'paises',
                'alias' => 'PAI',
                'type' => 'INNER',
                'conditions' => array(
                    'PAI.id=CIU.paise_id'
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
                'table' => 'ordentrabajos',
                'alias' => 'OT',
                'type' => 'LEFT',
                'conditions' => array(
                    'OT.id=Prefactura.ordentrabajo_id'
                )
            ));            
            
            array_push($arr_join, array(
                'table' => 'vehiculos',
                'alias' => 'V',
                'type' => 'LEFT',
                'conditions' => array(
                    'V.id=OT.vehiculo_id'
                )
            ));   
            
            array_push($arr_join, array(
                'table' => 'relacionempresas',
                'alias' => 'RE',
                'type' => 'LEFT',
                'conditions' => array(
                    'RE.empresa_id=EM.id'
                )
            ));

            $infoPrefact = $this->find('all', array(
                'joins' => $arr_join, 
                'conditions' => array('Prefactura.id' => $prefacturaId),
                'fields' => array(
                    'PFD.*',
                    'C.*',
                    'CI.*',
                    'P.*',
                    'EM.*',
                    'OT.*',
                    'V.*',
                    'CIU.*',
                    'PAI.*',
                    'RE.*',
                    'Prefactura.*',
                ),
                'recursive' => '-1'                
                ));        
            
            return $infoPrefact;               
        }
        
        
        /**
         * Guarda la observacion de la prefactura
         * @param type $prefactId
         * @param type $observacion
         * @return string
         */
        public function actualizarObservacionPrefact($prefactId, $observacion){
            $data = array();
            
            $prefactura = new Prefactura();
            
            $data['id'] = $prefactId;
            $data['observacion'] = $observacion;
            
            if($prefactura->save($data)){                
                return '1';
            }else{
                return '0';
            }            
        }
        
        /**
         * Actualiza el cliente de la prefactura 
         */
        public function actualizarClientePrefactura($prefactId, $clienteId){
            $data = array();
            
            $prefactura = new Prefactura();
            
            $data['id'] = $prefactId;
            $data['cliente_id'] = $clienteId;
            
            if($prefactura->save($data)){                
                return '1';
            }else{
                return '0';
            }                 
        }
        
        /**
         * Actualiza el estado de la prefactura de una especifica
         * @param type $prefactId
         * @param type $estadoId
         * @return string
         */
        public function actualizarEstadoPrefactura($prefactId, $estadoId){
            $data = array();
            
            $prefactura = new Prefactura();
            
            $data['id'] = $prefactId;
            $data['estadoprefactura_id'] = $estadoId;
            
            if($prefactura->save($data)){
                return '1';
            }else{
                return '0';
            }
        }     

        /**
         * Actualiza el estado de la prefactura de una especifica
         * @param type $prefactId
         * @param type $estadoId
         * @return int
         */
        public function actualizarEstadoPrefacturaEliminar($id){
            $data = array();
            
            $prefactura = new Prefactura();
            
            $data['id'] = $id;
            $data['eliminar'] = 1;
           
            if($prefactura->save($data)){
                return '1';
            }else{
                return '0';
            }
        }        
        public function obtenerInfoAlertaPreFactura($filtros){

            $arr_join = array(); 
    
            array_push($arr_join, array(
                'table' => 'alertaordenes', 
                'alias' => 'Alertaordene', 
                'type' => 'LEFT',
                'conditions' => array('Alertaordene.Prefactura_id=Prefactura.id')                
            ));
    
            array_push($arr_join, array(
                'table' => 'usuarios', 
                'alias' => 'US', 
                'type' => 'LEFT',
                'conditions' => array('Prefactura.usuario_id=US.id')                
            ));
    
            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CL', 
                'type' => 'LEFT',
                'conditions' => array('Prefactura.cliente_id=CL.id')                
            ));
            
            // array_push($arr_join, array(
            //     'table' => 'estadoalertas', 
            //     'alias' => 'EA', 
            //     'type' => 'INNER',
            //     'conditions' => array('EA.id=Alertaordene.estadoalerta_id')                
            // ));
            
            // array_push($arr_join, array(
            //     'table' => 'unidadesmedidas', 
            //     'alias' => 'UM', 
            //     'type' => 'LEFT',
            //     'conditions' => array('Alertaordene.unidadesmedida_id=UM.id')                
            // ));
            
            // array_push($arr_join, array(
            //     'table' => 'alertas', 
            //     'alias' => 'AL', 
            //     'type' => 'INNER',
            //     'conditions' => array('Alertaordene.alerta_id=AL.id')                
            // ));
            
            // array_push($arr_join, array(
            //     'table' => 'plantaservicios', 
            //     'alias' => 'PS', 
            //     'type' => 'LEFT',
            //     'conditions' => array('O.plantaservicio_id=PS.id')                
            // ));
            
            // array_push($arr_join, array(
            //     'table' => 'ordenestados', 
            //     'alias' => 'OE', 
            //     'type' => 'LEFT',
            //     'conditions' => array('O.ordenestado_id=OE.id')                
            // ));
            
            $alertasOrdenes = $this->find('all', array(                
                'joins' => $arr_join, 
                'fields' => array(
                   
                    'CL.id',
                    'CL.nit',
                    'CL.nombre',
                    'CL.direccion',
                    'CL.celular',
                    'CL.cumpleanios',
                   
                ),                             
                'conditions' => $filtros,
                'recursive' => '-1',
                // 'order' => 'Alertaordene.id DESC' 
                ));            
            
            return $alertasOrdenes;            
        }

        public function obtenerInfoAlertaFacturaGenerate($filtros){

            $arr_join = array(); 
           
            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CL', 
                'type' => 'LEFT',
                'conditions' => array('Prefactura.cliente_id=CL.id')                
            ));
            
            $alertasOrdenes = $this->find('all', array(                
                'joins' => $arr_join, 
                'fields' => array(
                    'CL.id',
                    'CL.nit',
                    'CL.nombre',
                    'CL.direccion',
                    'CL.celular',
                    'CL.cumpleanios',
                ),                             
                'conditions' => $filtros,
                'recursive' => '-1', 
                ));            
            
            return $alertasOrdenes;            
        }
}
