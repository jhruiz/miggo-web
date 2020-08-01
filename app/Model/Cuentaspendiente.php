<?php
App::uses('AppModel', 'Model');
/**
 * Cuentaspendiente Model
 *
 * @property Documento $Documento
 * @property Producto $Producto
 * @property Deposito $Deposito
 * @property Proveedore $Proveedore
 * @property Usuario $Usuario
 */
class Cuentaspendiente extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'documento_id' => array(
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
		'Documento' => array(
			'className' => 'Documento',
			'foreignKey' => 'documento_id',
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
		),
		'Deposito' => array(
			'className' => 'Deposito',
			'foreignKey' => 'deposito_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Proveedore' => array(
			'className' => 'Proveedore',
			'foreignKey' => 'proveedore_id',
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
        
        public function guardarCuentasPendientes($dctoId,$pdrId,$dptoId,$costProd,$cantidad,$provId,$numFact,$usrId,$emprId,$ttOblig,$fechaPago){
            $data=array();                        
              
            $cuentaspendientes = new Cuentaspendiente();                        

            if(!empty($dctoId)){ $data['documento_id'] = $dctoId; }

            if(!empty($pdrId)){ $data['producto_id' ] = $pdrId; }
            
            if(!empty($dptoId)){ $data['deposito_id'] = $dptoId; }
            
            if(!empty($costProd)){ $data['costoproducto'] = $costProd; }
            
            if(!empty($cantidad)){ $data['cantidad'] = $cantidad; }
            
            if(!empty($provId)){ $data['proveedore_id'] = $provId; }
            
            if(!empty($numFact)){ $data['numerofactura'] = $numFact; }
            
            if(!empty($usrId)){ $data['usuario_id'] = $usrId; }
            
            if(!empty($emprId)){ $data['empresa_id'] = $emprId; }
            
            if(!empty($ttOblig)){ $data['totalobligacion'] = $ttOblig; }
            
            if(!empty($fechaPago)){ $data['fechapago'] = $fechaPago; }            
            
            if($cuentaspendientes->save($data)){
                return true;
            }else{
                return false;
            }            
        }
        
        public function obtenerCuentasPendientesEmpresa($empresaId){
            $ctasPendientes = $this->find('all', array('conditions' => array('Cuentaspendiente.empresa_id' => $empresaId),'recursive' => '-1'));
            return $ctasPendientes;
        }
        
        public function obtenerCuentaPendienteId($cuentaId){
            $ctasPendiente = $this->find('first', array('conditions' => array('Cuentaspendiente.id' => $cuentaId), 'recursive' => '-1'));
            return $ctasPendiente;
        }
        
        public function actualizarCuentaPendiente($cuentaPendienteId,$saldoCuentaPendiente){
            $cuentaPendiente = new Cuentaspendiente();            
            $data = array();
            $data['id'] = $cuentaPendienteId;
            $data['totalobligacion'] = $saldoCuentaPendiente;
            
            if($cuentaPendiente->save($data)){
                return true;
            }else{
                return false;
            }
        }
        
        public function eliminarCuentaPendiente($id){
            if($this->deleteAll(['Cuentaspendiente.id' => $id])){
                return true;
            }else{
                return false;
            }            
        }
        
        public function obtenerCuentasPendientes($filter){
            
            $arr_join = array(); 
            array_push($arr_join, array(
                'table' => 'productos', 
                'alias' => 'PR', 
                'type' => 'INNER',
                'conditions' => array(
                    'PR.id=Cuentaspendiente.producto_id'
                    )                
            ));

            array_push($arr_join, array(
                'table' => 'proveedores', 
                'alias' => 'PV', 
                'type' => 'INNER',
                'conditions' => array(
                    'PV.id=Cuentaspendiente.proveedore_id'
                    )                
            ));

            $cuentasPend = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'PR.id',
                    'PR.descripcion',
                    'PR.codigo',
                    'PV.id',
                    'PV.nombre',
                    'PV.diascredito',
                    'Cuentaspendiente.*'
                ),
                'conditions' => array($filter),
                'order' => 'Cuentaspendiente.id',
                'recursive' => '-1'                
                ));            

            return $cuentasPend;        
            
            
		}
		
		public function obtenerCuentasParaCalendario($empresaId, $fechaInicio, $fechaFin){
			$arr_join = array(); 
			
            array_push($arr_join, array(
                'table' => 'productos', 
                'alias' => 'P', 
                'type' => 'INNER',
                'conditions' => array(
                    'P.id=Cuentaspendiente.producto_id'
                    )                
            ));          
			
            array_push($arr_join, array(
                'table' => 'depositos', 
                'alias' => 'D', 
                'type' => 'INNER',
                'conditions' => array(
                    'D.id=Cuentaspendiente.deposito_id'
                    )                
            ));          
			
            array_push($arr_join, array(
                'table' => 'proveedores', 
                'alias' => 'PV', 
                'type' => 'INNER',
                'conditions' => array(
                    'PV.id=Cuentaspendiente.proveedore_id'
                    )                
            ));          
            
            array_push($arr_join, array(
                'table' => 'usuarios', 
                'alias' => 'U', 
                'type' => 'INNER',
                'conditions' => array(
                    'U.id=Cuentaspendiente.usuario_id'
                    )                
            ));                 
            
            array_push($arr_join, array(
                'table' => 'documentos', 
                'alias' => 'DC', 
                'type' => 'INNER',
                'conditions' => array(
                    'DC.id=Cuentaspendiente.documento_id'
                    )                
            ));                 
                       
            $cuentaspendientes = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'P.id',
                    'P.descripcion',
                    'D.id',
                    'D.descripcion',
                    'PV.id',
					'PV.nombre',
					'PV.diascredito',
					'DC.id',
					'DC.codigo',
                    'U.id',
                    'U.nombre',
                    'Cuentaspendiente.*',
                ),
                'conditions' => array(
                    'Cuentaspendiente.empresa_id' => $empresaId,
                    'Cuentaspendiente.fechapago BETWEEN ? AND ? ' => array($fechaInicio, $fechaFin),                    
                    ), 
                'recursive' => '-1'));
            return $cuentaspendientes;     			
		}
}
