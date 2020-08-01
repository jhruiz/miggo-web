<?php
App::uses('AppModel', 'Model');
/**
 * Gasto Model
 *
 * @property Usuario $Usuario
 * @property Empresa $Empresa
 * @property Cuenta $Cuenta
 */
class Gasto extends AppModel {

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
		'cuenta_id' => array(
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
		'Empresa' => array(
			'className' => 'Empresa',
			'foreignKey' => 'empresa_id',
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
         * retorna los gastos realizados en un rango de fechas
         * @param type $fechaInicio
         * @param type $fechaFin
         * @param type $empresaId
         * @return type
         */
        public function obtenerGastosEmpresa($fechaInicio, $fechaFin, $empresaId, $cuenta = null, $itemId){

            $arrFilters = [];
            if(!empty($cuenta)){
                $arrFilters['Gasto.cuenta_id'] = $cuenta;
            }
            
            if(!empty($itemId)){
                $arrFilters['Gasto.itemsgasto_id'] = $itemId;
            }
            
            $arrFilters['Gasto.empresa_id'] = $empresaId;
            $arrFilters['Gasto.fechagasto BETWEEN ? AND ?'] = array($fechaInicio, $fechaFin);

            $infoGastos = $this->find('all', array(
                'conditions' => $arrFilters,
                'recursive' => '0'));

            return $infoGastos;
        }
        
        /**
         * obtiene los gastos o los traslados
         * @param type $fechaInicio
         * @param type $fechaFin
         * @param type $empresaId
         * @param type $cuenta
         * @param type $tipo
         * @return type
         */
        public function obtenerGastosTrasladosEmpresa($fechaInicio, $fechaFin, $empresaId, $cuenta = null){

            $arrFilters = [];
            if(!empty($cuenta)){
                $arrFilters['Gasto.cuenta_id'] = $cuenta;
            }
            
            $arrFilters['Gasto.empresa_id'] = $empresaId;
            $arrFilters['Gasto.fechagasto BETWEEN ? AND ?'] = array($fechaInicio, $fechaFin);
            $arrFilters['Gasto.traslado'] = '0';
            
            $infoGastos = $this->find('all', array(
                'conditions' => $arrFilters,
                'recursive' => '0'));

            return $infoGastos;            
        }
        
        
        /**
         * obtiene los gastos o los traslados
         * @param type $fechaInicio
         * @param type $fechaFin
         * @param type $empresaId
         * @param type $cuenta
         * @param type $tipo
         * @return type
         */
        public function obtenerIngresosTrasladosEmpresa($fechaInicio, $fechaFin, $empresaId, $cuenta = null){

            $arrFilters = [];
            if(!empty($cuenta)){
                $arrFilters['Gasto.cuentadestino'] = $cuenta;
            }
            
            $arrFilters['Gasto.empresa_id'] = $empresaId;
            $arrFilters['Gasto.fechagasto BETWEEN ? AND ?'] = array($fechaInicio, $fechaFin);
            $arrFilters['Gasto.traslado'] = '1';    
            
            $infoGastos = $this->find('all', array(
                'conditions' => $arrFilters,
                'recursive' => '0'));

            return $infoGastos;            
        }   
        
        /**
         * Se obtienen los gastos de una cuenta específica en una fecha específica
         * @param type $fechaInicio
         * @param type $fechaFin
         * @param type $empresaId
         * @param type $cuenta
         * @return type
         */
        public function obtenerGastosCuenta($fecha, $empresaId, $cuenta){

            $arrFilters = [];
            $arrFilters['Gasto.cuenta_id'] = $cuenta;            
            $arrFilters['Gasto.empresa_id'] = $empresaId;
            $arrFilters['Gasto.fechagasto'] = $fecha;

            $infoGastos = $this->find('all', array(
                'conditions' => $arrFilters,
                'recursive' => '0'));

            return $infoGastos;
        }
        
        /**
         * Se obtienen los traslados que ha recibido una cuenta especifica en una fecha especifica
         * @param type $fecha
         * @param type $empresaId
         * @param type $cuentaId
         * @return type
         */
        public function obtenerIngresosTrasladosCuenta($fecha, $empresaId, $cuentaId){
            $arrFilters = [];
            $arrFilters['Gasto.cuentadestino'] = $cuentaId;
            $arrFilters['Gasto.empresa_id'] = $empresaId;
            $arrFilters['Gasto.fechagasto'] = $fecha;
            
            $infoTraslado = $this->find('all', array(
                'conditions' => $arrFilters,
                'recursive' => '0'
            ));
            
            return $infoTraslado;
        }
        
        /**
         * Se obtienen los gastos por id
         * @param type $id
         * @return type
         */
        public function obtenerGastoPorId($id){
            $gasto = $this->find('first', array('conditions' => array('Gasto.id' => $id), 'recursive' => '-1'));
            return $gasto;
        }
        
        /**
         * Actualiza el registro del gasto
         * @param type $data
         * @return boolean
         */
        public function actualizarGasto($data){
            
            $gasto = new Gasto();
            
            if($gasto->save($data)){
                return true;
            }else{
                return false;
            }
        }        
}
