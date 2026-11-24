<?php
App::uses('AppModel', 'Model');
/**
 * Cuentascliente Model
 *
 * @property Documento $Documento
 * @property Deposito $Deposito
 * @property Cliente $Cliente
 * @property Usuario $Usuario
 * @property Empresa $Empresa
 * @property Factura $Factura
 */
class Cuentascliente extends AppModel {

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
		'factura_id' => array(
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
		'Deposito' => array(
			'className' => 'Deposito',
			'foreignKey' => 'deposito_id',
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
		),
		'Factura' => array(
			'className' => 'Factura',
			'foreignKey' => 'factura_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        
        public function guardarCuentaPorCobrar($documentoId,$depositoId,$clienteId,$usuarioId,$empresaId,$total,$facturaId){        
            $data = array();
            $cuentasclientes = new Cuentascliente();
            
            $data['documento_id'] = $documentoId;
            $data['deposito_id'] = $depositoId;
            $data['cliente_id'] = $clienteId;
            $data['usuario_id'] = $usuarioId;
            $data['empresa_id'] = $empresaId;
            $data['totalobligacion'] = $total;
            $data['factura_id'] = $facturaId;
            $data['total'] = $total;
            $data['eliminar'] = '0';
            
            if($cuentasclientes->save($data)){
                return true;
            }else{
                return false;
            }           
        }
        
        public function obtenerDatosCuentaPendienteId($cuentaId){
            $cuentaPendiente = $this->find('first', array('conditions' => array('Cuentascliente.id' => $cuentaId), 'recursive' => '-1'));
            return $cuentaPendiente;
        }
        
        public function actualizarCuentaCliente($cuentaPendienteId,$saldoCuentaPendiente){
            $cuentaCliente = new Cuentascliente();            
            $data = array();
            $data['id'] = $cuentaPendienteId;
            $data['totalobligacion'] = $saldoCuentaPendiente;
            
            if($cuentaCliente->save($data)){
                return true;
            }else{
                return false;
            }            
        }
        
        /**
         * Registra el pago de una cuenta por cobrar con dinero y retenciones, en una
         * sola transacción (cuentasclientes, cuentas, abonofacturas y
         * retenciones_aplicadas son InnoDB):
         *   - el saldo de la CxC baja por dinero + retenciones;
         *   - la caja o banco del tipo de pago sube solo por el dinero;
         *   - el abono (abonofacturas.valor) es solo el dinero;
         *   - las retenciones quedan en retenciones_aplicadas (origen PAGO_CXC).
         * Si algo falla se deshace todo.
         *
         * @param  int    $cuentaClienteId
         * @param  mixed  $valorDinero  valor recibido en dinero (0 si solo hay retenciones)
         * @param  int    $tipopagoId   obligatorio si hay dinero
         * @param  array  $retenciones  [codigo => valor] (vacío si no hay)
         * @param  int    $usuarioId
         * @return array  ['ok' => bool, 'msg' => string]
         */
        public function registrarPagoConRetenciones($cuentaClienteId, $valorDinero, $tipopagoId, $retenciones, $usuarioId){
            $RetencionAplicada = ClassRegistry::init('RetencionAplicada');
            $Abonofactura = ClassRegistry::init('Abonofactura');
            $Tipopago = ClassRegistry::init('Tipopago');
            $Cuenta = ClassRegistry::init('Cuenta');

            $dinero = str_replace(array(',', ' ', '$'), '', trim((string) $valorDinero));
            if ($dinero === '') {
                $dinero = '0';
            }
            if (!is_numeric($dinero) || (float) $dinero < 0) {
                return array('ok' => false, 'msg' => 'El valor del pago no es válido.');
            }
            $dinero = round((float) $dinero, 2);

            $ds = $this->getDataSource();
            $ds->begin();
            try {
                $cxc = $this->obtenerDatosCuentaPendienteId($cuentaClienteId);
                if (empty($cxc)) {
                    throw new InvalidArgumentException('La cuenta por cobrar no existe.');
                }
                $cxc = $cxc['Cuentascliente'];
                $saldo = round((float) $cxc['totalobligacion'], 2);
                $facturaId = (int) $cxc['factura_id'];

                // Retenciones: solo facturas de clientes configurados (las demás se ignoran)
                $prep = array('filas' => array(), 'total' => 0.0);
                if ($facturaId > 0 && !empty($retenciones)) {
                    try {
                        $prep = $RetencionAplicada->prepararFilas($facturaId, $retenciones, RetencionAplicada::ORIGEN_PAGO_CXC, $usuarioId,
                            array('cuentascliente_id' => (int) $cuentaClienteId));
                    } catch (Exception $e) {
                        throw new InvalidArgumentException($e->getMessage());
                    }
                }

                $totalAplicado = round($dinero + $prep['total'], 2);
                if ($totalAplicado <= 0) {
                    throw new InvalidArgumentException('Debe ingresar un valor a pagar o una retención.');
                }
                if ($totalAplicado - $saldo > 0.005) {
                    throw new InvalidArgumentException('El valor aplicado (dinero + retenciones) supera el saldo pendiente.');
                }

                // 1. Saldo de la cuenta por cobrar
                if (!$this->actualizarCuentaCliente($cuentaClienteId, round($saldo - $totalAplicado, 2))) {
                    throw new Exception('No se pudo actualizar el saldo de la cuenta por cobrar.');
                }

                // 2. Dinero: caja o banco y abono
                $abonoId = null;
                if ($dinero > 0) {
                    $tipoPago = $Tipopago->obtenerTipoPagoPorId($tipopagoId);
                    if (empty($tipoPago)) {
                        throw new InvalidArgumentException('Seleccione el tipo de pago.');
                    }
                    $cuentaId = $tipoPago['Tipopago']['cuenta_id'];
                    $cuenta = $Cuenta->obtenerDatosCuentaId($cuentaId);
                    if (empty($cuenta)) {
                        throw new Exception('La cuenta asociada al tipo de pago no existe.');
                    }
                    if (!$Cuenta->actualizarSaldoCuenta($cuentaId, $cuenta['Cuenta']['saldo'] + $dinero)) {
                        throw new Exception('No se pudo actualizar el saldo de la cuenta.');
                    }

                    // Mismos campos que guardarAbonoFacturaCuentaCliente, pero se necesita el id
                    $Abonofactura->create();
                    $abono = $Abonofactura->save(array('Abonofactura' => array(
                        'factura_id'       => $cxc['factura_id'],
                        'prefactura_id'    => $cxc['prefactura_id'],
                        'usuario_id'       => $usuarioId,
                        'valor'            => $dinero,
                        'empresa_id'       => $cxc['empresa_id'],
                        'cuenta_id'        => $cuentaId,
                        'cuentacliente_id' => $cuentaClienteId,
                        'tipopago_id'      => $tipopagoId,
                    )));
                    if (!$abono) {
                        throw new Exception('No se pudo guardar el abono.');
                    }
                    $abonoId = (int) $Abonofactura->id;
                }

                // 3. Retenciones aplicadas, ligadas al abono si hubo dinero
                if (!empty($prep['filas'])) {
                    foreach ($prep['filas'] as $i => $fila) {
                        $prep['filas'][$i]['RetencionAplicada']['abonofactura_id'] = $abonoId;
                    }
                    if (!$RetencionAplicada->saveMany($prep['filas'], array('validate' => false, 'atomic' => false))) {
                        throw new Exception('No se pudieron guardar las retenciones aplicadas.');
                    }
                }

                $ds->commit();
                return array('ok' => true, 'msg' => 'El pago se ha realizado correctamente.');
            } catch (InvalidArgumentException $e) {
                $ds->rollback();
                return array('ok' => false, 'msg' => $e->getMessage());
            } catch (Exception $e) {
                $ds->rollback();
                CakeLog::write('error', 'Pago CxC ' . $cuentaClienteId . ': ' . $e->getMessage());
                return array('ok' => false, 'msg' => 'No se pudo realizar el pago. Por favor, inténtelo de nuevo.');
            }
        }

        public function obtenerCarteraCliente($clienteId){
            $arrCuentaCliente = $this->find('all', array('conditions' => array('Cuentascliente.cliente_id' => $clienteId), 'recursive' => '-1'));
            return $arrCuentaCliente;
        }
        
        public function eliminarCuenta($id){
            $cuentaCliente = new Cuentascliente();            
            $data = array();
            $data['id'] = $id;
            $data['eliminar'] = 1;
            
            if($cuentaCliente->save($data)){
                return true;
            }else{
                return false;
            }     
        }
        
        public function obtenerCuentasClientes($filtros){
            $arr_join = array(); 
            
            array_push($arr_join, array(
                'table' => 'usuarios', 
                'alias' => 'U', 
                'type' => 'INNER',
                'conditions' => array(
                    'U.id=Cuentascliente.usuario_id'
                    )                
            ));          
            
            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CL', 
                'type' => 'INNER',
                'conditions' => array(
                    'CL.id=Cuentascliente.cliente_id'
                    )                
            )); 
            
            array_push($arr_join, array(
                'table' => 'documentos', 
                'alias' => 'DC', 
                'type' => 'INNER',
                'conditions' => array(
                    'DC.id=Cuentascliente.documento_id'
                    )                
            )); 
            
            array_push($arr_join, array(
                'table' => 'facturas', 
                'alias' => 'F', 
                'type' => 'INNER',
                'conditions' => array(
                    'F.id=Cuentascliente.factura_id'
                    )                
            )); 
            
            array_push($arr_join, array(
                'table' => 'tipopagos', 
                'alias' => 'TP', 
                'type' => 'INNER',
                'conditions' => array(
                    'TP.id=Cuentascliente.tipopago_id'
                    )                
            )); 
            $cuentasClientes = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'U.id',
                    'U.nombre',
                    'CL.id',
                    'CL.diascredito',
                    'CL.limitecredito',
                    'CL.nombre',
                    'DC.id',
                    'DC.codigo',
                    'F.id',
                    'F.consecutivodian',
                    'F.consecutivodv',
                    'F.prefijo',
                    'Cuentascliente.*',
                    'TP.*'
                ),
                'conditions' => $filtros, 
                'recursive' => '-1'));
            return $cuentasClientes;            
        }
        
        /**
         * Realiza el registro de la cuenta por cobrar
         * @param type $empresaId
         * @param type $ttalOblig
         * @return boolean
         */
        public function guardarParcialCtaXCobrar($empresaId, $ttalOblig, $tipoPagoId, $prefactId, $userId, $fechaPago){
            $cuentaCliente = new Cuentascliente();            
            $data = array();
            
            $data['documento_id'] = '0';
            $data['deposito_id'] = '0';
            $data['factura_id'] = '0';
            $data['empresa_id'] = $empresaId;
            $data['totalobligacion'] = $ttalOblig;
            $data['tipopago_id'] = $tipoPagoId;
            $data['prefactura_id'] = $prefactId;
            $data['usuario_id'] = $userId;
            $data['fechapago'] = $fechaPago;           
            $data['total'] = $ttalOblig;           
            $data['eliminar'] = 0;

            if($cuentaCliente->save($data)){
                return $cuentaCliente->id;
            }else{
                return false;
            }                     
        }
        
        
        public function actualizarCuentaClienteFactura($documentoId, $clienteId, $facturaId, $prefacturaId){
            $this->updateAll(
                array(                            
                    'Cuentascliente.documento_id' => $documentoId,                                  
                    'Cuentascliente.cliente_id' => $clienteId,
                    'Cuentascliente.factura_id' => $facturaId,
                ),array(
                    'Cuentascliente.prefactura_id' => $prefacturaId));             
        }

        public function obtenerCuentasParaCalendario($empresaId, $fechaInicio, $fechaFin){
            $arr_join = array(); 
            
            array_push($arr_join, array(
                'table' => 'usuarios', 
                'alias' => 'U', 
                'type' => 'INNER',
                'conditions' => array(
                    'U.id=Cuentascliente.usuario_id'
                    )                
            ));          
            
            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'CL', 
                'type' => 'INNER',
                'conditions' => array(
                    'CL.id=Cuentascliente.cliente_id'
                    )                
            )); 
            
            array_push($arr_join, array(
                'table' => 'documentos', 
                'alias' => 'DC', 
                'type' => 'INNER',
                'conditions' => array(
                    'DC.id=Cuentascliente.documento_id'
                    )                
            )); 
            
            array_push($arr_join, array(
                'table' => 'facturas', 
                'alias' => 'F', 
                'type' => 'INNER',
                'conditions' => array(
                    'F.id=Cuentascliente.factura_id'
                    )                
            )); 
                       
            $cuentasClientes = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'U.id',
                    'U.nombre',
                    'CL.id',
                    'CL.diascredito',
                    'CL.limitecredito',
                    'CL.nombre',
                    'DC.id',
                    'DC.codigo',
                    'F.id',
                    'F.consecutivodian',
                    'F.codigo',
                    'Cuentascliente.*',
                ),
                'conditions' => array(
                    'Cuentascliente.empresa_id' => $empresaId,
                    'Cuentascliente.fechapago BETWEEN ? AND ? ' => array($fechaInicio, $fechaFin),                    
                    ), 
                'recursive' => '-1'));
            return $cuentasClientes;            
        }

        public function obtenerCuentasVencidas($empresaId, $fecha){
            
            $cuentas = $this->find('all', array(
                'conditions' => array(
                    'Cuentascliente.empresa_id' => $empresaId,
                    'Cuentascliente.fechapago < ' => $fecha,
                    'Cuentascliente.eliminar = 0'
                )    
            )); 

            return $cuentas;
        }

        public function obtenerCuentaPendienteFact($facturaId) {

                $arr_join = [];     
        
                array_push($arr_join, array(
                    'table' => 'tipopagos',
                    'alias' => 'TP',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'TP.id=Cuentascliente.tipopago_id',
                    ),
                ));  
        
                $cuentasPend = $this->find('all',array(
                    'joins' => $arr_join,
                    'fields' => array(
                        'TP.*',
                        'Cuentascliente.*',
                    ),            
                    'conditions' => array(
                        'Cuentascliente.factura_id' => $facturaId
                    ), 
                    'recursive' => '-1')
                );
        
                return $cuentasPend; 

        }

        /**
         * Obtiene las ventas a credito para el reporte de cierre diario
         */
        public function obtenerVentasCredito($empresaId, $fechaCierre) {

            $arr_join = array(); 
            
            array_push($arr_join, array(
                'table' => 'clientes', 
                'alias' => 'C', 
                'type' => 'INNER',
                'conditions' => array(
                    'C.id=Cuentascliente.cliente_id'
                    )                
            ));             
            
            array_push($arr_join, array(
                'table' => 'facturas', 
                'alias' => 'F', 
                'type' => 'INNER',
                'conditions' => array(
                    'F.id=Cuentascliente.factura_id'
                    )                
            ));            
            
            array_push($arr_join, array(
                'table' => 'usuarios', 
                'alias' => 'U', 
                'type' => 'INNER',
                'conditions' => array(
                    'U.id=F.usuario_id'
                    )                
            ));

            array_push($arr_join, array(
                'table' => 'tipopagos', 
                'alias' => 'T', 
                'type' => 'INNER',
                'conditions' => array(
                    'T.id=Cuentascliente.tipopago_id'
                    )                
            ));    
            
            $cuentasClientes = $this->find('all', array(
                'joins' => $arr_join,
                'fields' => array(
                    'U.id',
                    'U.nombre',
                    'C.id',
                    'C.nombre',
                    'F.id',
                    'F.consecutivodian',
                    'F.codigo',
                    'F.created',
                    'T.id',
                    'T.descripcion',
                    'Cuentascliente.*',
                ),
                'conditions' => array(
                    'Cuentascliente.empresa_id' => $empresaId,
                    'Cuentascliente.created BETWEEN ? AND ? ' => array($fechaCierre . ' 00:00:01', $fechaCierre . ' 23:59:59'),                    
                    ), 
                'recursive' => '-1'));
            return $cuentasClientes;             
            
        }


        /**
         * Obtiene la info del tipo pago a crédito para una factura específica
         */
        public function obtenerInfoTipoPagoCredito($facturaId){
            $arrCuentaCliente = $this->find('all', array('conditions' => array('Cuentascliente.factura_id' => $facturaId), 'recursive' => '-1'));
            return $arrCuentaCliente;
        }

}
