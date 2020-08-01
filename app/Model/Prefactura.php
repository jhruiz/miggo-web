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
            $arrPrefactura = $this->find('first', array('conditions' => array('Prefactura.cliente_id' => $clienteId), 'recursive' => '-1'));
            return $arrPrefactura;
        }
        
        /**
         * obtiene la prefactura por medio de la orden de trabajo
         * @param type $ordenId
         * @return type
         */
        public function obtenerPrefactPorOrden($ordenId){
            $arrPrefact = $this->find('first', array('conditions' => array('Prefactura.ordentrabajo_id' => $ordenId), 'recursive' => '-1'));
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
        
        public function obtenerPrefacturas($usuarioId, $placa, $cliente){
            
            $filters = [];
            
            if(!empty($usuarioId)){
                $filters['Prefactura.usuario_id'] = $usuarioId;                
            }
            
            if(!empty($placa)){
                $filters['LOWER(VH.placa) LIKE'] = '%' . strtolower($placa) . '%';
            }
            
            if(!empty($cliente)){
                $filters['LOWER(CL.nombre) LIKE'] = '%'. strtolower($cliente) .'%';
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
                'table' => 'clientes',
                'alias' => 'C',
                'type' => 'INNER',
                'conditions' => array(
                    'C.id=Prefactura.cliente_id'
                )
            ));            
            
            array_push($arr_join, array(
                'table' => 'empresas',
                'alias' => 'EM',
                'type' => 'INNER',
                'conditions' => array(
                    'EM.id=C.empresa_id'
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

}
