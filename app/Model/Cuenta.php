<?php
App::uses('AppModel', 'Model');
/**
 * Cuenta Model
 *
 * @property Empresa $Empresa
 * @property Gasto $Gasto
 */
class Cuenta extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'descripcion';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Empresa' => array(
			'className' => 'Empresa',
			'foreignKey' => 'empresa_id',
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
		'Gasto' => array(
			'className' => 'Gasto',
			'foreignKey' => 'cuenta_id',
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
        
        public function obtenerCuentasEmpresa($empresaId){
			$cuentas = $this->find('list', array('conditions' => array('Cuenta.empresa_id' => $empresaId), 'recursive' => '-1'));
            return $cuentas;
        }
        
        public function obtenerDatosCuentaId($cuentaId){
            $cuenta = $this->find('first', array('conditions' => array('Cuenta.id' => $cuentaId), 'recursive' => '-1'));
            return $cuenta;
        }
        
        public function actualizarSaldoCuenta($cuentaId,$saldoFinal){     
            $cuenta = new Cuenta();
            $data = array();
            $data['id'] = $cuentaId;
            $data['saldo'] = $saldoFinal;            
            if($cuenta->save($data)){
				return true;
			}else{
				return false;
			}
        }
        
        
        public function obtenerCuentasDestino($empresaId){
            $filter = [];            
            $filter['Cuenta.empresa_id'] = $empresaId;
            $cuentas = $this->find('list', array('conditions' => $filter, 'recursive' => '-1'));
            return $cuentas;
        }
        
        public function obtenerInfoCuentas($empresaId){
            $cuentas = $this->find('all', array('conditions' => array('Cuenta.empresa_id' => $empresaId), 'recursive' => '-1'));
            return $cuentas;
        }

}
