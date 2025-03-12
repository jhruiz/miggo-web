<?php
App::uses('AppController', 'Controller');

/** controlador de abonos de facturas */
class AbonofacturasController extends AppController {
    /** obtiene las cuentas de la empresa */
	public $components = array('Paginator');

        public function index(){
            $this->loadModel('Abonofactura');
            $this->loadModel('Tipopago');
            $this->loadModel('Cuenta');
    
            $empresaId = $this->Auth->user('empresa_id');
    
            $tipocuenta = '';
            if (!empty($this->passedArgs['tipocuentas'])) {
                $filter['Abonofactura.cuenta_id'] = $this->passedArgs['tipocuentas'];
                $tipocuenta = $this->passedArgs['tipocuentas'];
            }
    
            $tipopagos = '';
            if (!empty($this->passedArgs['tipopagos'])) {
                $filter['Abonofactura.tipopago_id'] = $this->passedArgs['tipopagos'];
                $tipopagos = $this->passedArgs['tipopagos'];
            }
    
            $fechaInicio = '';
            $fechaFin = '';
            if (!empty($this->passedArgs['fechaInicio']) && !empty($this->passedArgs['fechaFin'])) {
                $filter['Abonofactura.created BETWEEN ? AND ?'] = array($this->passedArgs['fechaInicio'] . ' 00:00:01', $this->passedArgs['fechaFin'] . ' 23:23:59');
                $fechaInicio = $this->passedArgs['fechaInicio'];
                $fechaFin = $this->passedArgs['fechaFin'];
            }
    
            $codigoDian = '';
            if (!empty($this->passedArgs['codigoDian'])) {
                $filter = null;
                $filter['F.consecutivodian'] = $this->passedArgs['codigoDian'];
                $codigoDian = $this->passedArgs['codigoDian'];
            }
            
            $numeroFactura = '';
            if (!empty($this->passedArgs['numeroFactura'])) {
                $filter = null;
                $filter['F.codigo'] = $this->passedArgs['numeroFactura'];
                $numeroFactura = $this->passedArgs['numeroFactura'];
            }
            
            $numeroPrefactura = '';
            if (!empty($this->passedArgs['numeroPrefactura'])) {
                $filter = null;
                $filter['Abonofactura.prefactura_id'] = $this->passedArgs['numeroPrefactura'];
                $numeroPrefactura = $this->passedArgs['numeroPrefactura'];
            }
            
            if (empty($this->passedArgs['codigoDian']) && empty($this->passedArgs['numeroFactura']) && empty($this->passedArgs['numeroPrefactura'])
                && empty($this->passedArgs['fechaInicio']) && empty($this->passedArgs['fechaFin'])) {
                $filter['Abonofactura.created BETWEEN ? AND ?'] = array(date("Y-m-d") . ' 00:00:01', date("Y-m-d") . ' 23:23:59');
            } 
    
            $filter['Abonofactura.empresa_id'] = $empresaId;
    
            //se obtiene el listado de cuentas
            $tipoCuentas = $this->Cuenta->obtenerCuentasEmpresa($empresaId);
    
            //se obtiene el listado de tipos de pago
            $tipoPago = $this->Tipopago->obtenerListaTiposPagos($empresaId);
    
            //se obtienen los abonos realizados a prefacturas
            $abonosPrefacturas = $this->Abonofactura->reporteAbonosPrefacturas($filter);


            //se obtienen los abonos realizados a cuentas por cobrar
            $abonosCuentas = $this->Abonofactura->reporteAbonosCuentas($filter);

            $abonos = array_merge($abonosPrefacturas, $abonosCuentas);

            $this->set(compact('tipoPago', 'abonos', 'tipoCuentas', 'tipocuenta', 'tipopagos', 'fechaInicio','fechaFin', 'codigoDian', 'numeroFactura', 'numeroPrefactura'));
        }
        
        public function abonofactura(){
            $this->loadModel('Tipopago');
            
            $posData = $this->request->data;
            $ttales = $posData['ttales'];
            $empresaId = $this->Auth->user('empresa_id');
            
            //se obtienen las cuentas de la empresa
            $arrTiposPago = $this->Tipopago->obtenerListaTiposPagos($empresaId);
            
            $this->set(compact('ttales', 'arrTiposPago'));                        
        }
        
        /**
         * se realiza el abono del cliente
         */
        public function abonoCliente(){
            $this->loadModel('Prefactura');
            $this->loadModel('Abonofactura');
            $this->loadModel('Cuenta');
            $this->loadModel('Tipopago');
            $this->autoRender = false;
            
            $posData = $this->request->data;
            
            $ttalAbono = $posData['ttalAbono'];
            $tipoPagoId = $posData['tipopagoId'];
            $prefacturaId = $posData['prefacturaId'];
            
            $tipoPago = $this->Tipopago->obtenerTipoPagoPorId($tipoPagoId);
            $cuentaId = $tipoPago['Tipopago']['cuenta_id'];         
            
            $prefactId = "";
            $resp = false;
            
            //se obtienen la prefactura asociada al cliente
            $prefact = $this->Prefactura->obtenerPrefacturaPorId($prefacturaId);
            
            if(!empty($prefact)){
                $prefactId = $prefact['Prefactura']['id'];
                $empresaId = $this->Auth->user('empresa_id');
                $usuarioId = $this->Auth->user('id');
                
                $resp = $this->Abonofactura->guardarAbonoFactura($prefactId, $usuarioId, $ttalAbono, $empresaId, $cuentaId, $tipoPagoId);
                if($resp){
                    //se agrega el saldo a la cuenta
                    $infoCuenta = $this->Cuenta->obtenerDatosCuentaId($cuentaId);
                    $saldoFinal = $infoCuenta['Cuenta']['saldo'] + $ttalAbono;
                    //se actualiza el saldo de la cuenta
                    $this->Cuenta->actualizarSaldoCuenta($infoCuenta['Cuenta']['id'],$saldoFinal);                    
                }
            }
            
            echo json_encode(array('resp' => $resp));            
        }

        /**
         * Obtiene todos los abonos realizados a una prefactura
         */
        public function obtenerabonos(){
            $this->loadModel('Cuenta');

            $posData = $this->request->data;
            $prefacturaId = $posData['prefacturaId'];
            $empresaId = $this->Auth->user('empresa_id');
            
            //se obtienen los abonos asociados a la prefactura
            $abonos =  $this->Abonofactura->obtenerAbonosPrefactura($prefacturaId);

            //se obtienen las cuentas de la empresa
            $cuentas = $this->Cuenta->obtenerCuentasEmpresa($empresaId);
            
            $this->set(compact('abonos', 'cuentas', 'prefacturaId'));                        
        }

        /**
         * Elimina un abono en particular y crea el gasto para el mismo
         */
        public function eliminarabono() {
            $this->autoRender = false;
            $resp = false;

            $posData = $this->request->data;
            $idAbono = $posData['idAbono'];
            $valor = $posData['valor'];
            $cuenta = $posData['cuenta'];
            $prefactura = $posData['prefactura'];

            $empresaId = $this->Auth->user('empresa_id');

            if( $this->Abonofactura->eliminarAbono($idAbono) ) {
                $this->gastoPorDevAbono($prefactura, $valor, $cuenta, $idItem);
                $this->ajustarSaldoCuenta($cuenta, $valor);
                $resp = true;
            }

            echo json_encode(array('resp' => $resp)); 

        }

        /**
         * Ajusta el valor de un abono de una prefactura específica
         */
        public function ajustarabono() {
            $this->autoRender = false;
            $resp = false;

            $posData = $this->request->data;
            $idAbono = $posData['idAbono'];
            $valorIni = $posData['valorIni'];
            $valorFin = $posData['valorFin'];
            $cuenta = $posData['cuenta'];
            $prefactura = $posData['prefactura'];

            if ( $this->Abonofactura->ajustarAbono($idAbono, $valorFin))  {
                //Registrar el gasto
                $this->gastoPorAjusteAbono( $prefactura, $cuenta, $valorIni, $valorFin );
                //ajustar saldo cuenta
                $valor = $valorIni - $valorFin;
                $this->ajustarSaldoCuenta($cuenta, $valor);
                $resp = true;
            }

            echo json_encode(array('resp' => $resp)); 

        }

        /**
         * Registra el gasto generado por el ajuste del abono
         */
        public function gastoPorAjusteAbono( $prefactura, $cuenta, $valorIni, $valorFin ) {
            $idItem = "";
            $this->loadModel('Itemsgasto');
            $this->loadModel('Gasto');

            $empresaId = $this->Auth->user('empresa_id');
            $desc = 'AJUSTAR ABONO';
            $itemGasto = $this->Itemsgasto->obtenerItemGastoProv($empresaId, $desc);

            // valida que exista el item de gasto eliminar abonos para la empresa, sino, lo crea
            if( empty( $itemGasto ) ) {
                $idItem = $this->Itemsgasto->crearItemGasto($empresaId, $desc);
            } else {
                $idItem = $itemGasto['Itemsgasto']['id'];
            }

            $valor = $valorIni - $valorFin;

            //Crea el gasto por eliminación de abono
            $data = array(
                'descripcion' => 'Se ajusta el abono de la prefactura ' . $prefactura . ', cuyo valor era ' . $valorIni . ' y se deja en ' . $valorFin,
                'usuario_id' => $this->Auth->user('id'),
                'empresa_id' => $empresaId,
                'fechagasto' => date('Y-m-d H:i:s'),
                'created' => date('Y-m-d  H:i:s'),
                'valor' => $valor,
                'cuenta_id' => $cuenta,
                'traslado' => '0',
                'itemsgasto_id' => $idItem,
                'tipoempresa' => 'P',
                'empresaasg_id' => $empresaId
            );

            $this->Gasto->create();
            $this->Gasto->save($data);
        }
            
        /**
         * Registra el gasto generado por la eliminación del abono
         */
        public function gastoPorDevAbono($prefactura, $valor, $cuenta, $idItem) {
            $idItem = "";
            $this->loadModel('Itemsgasto');
            $this->loadModel('Gasto');

            $empresaId = $this->Auth->user('empresa_id');
            $desc = 'ELIMINAR ABONO';
            $itemGasto = $this->Itemsgasto->obtenerItemGastoProv($empresaId, $desc);

            // valida que exista el item de gasto eliminar abonos para la empresa, sino, lo crea
            if( empty( $itemGasto ) ) {
                $idItem = $this->Itemsgasto->crearItemGasto($empresaId, $desc);
            } else {
                $idItem = $itemGasto['Itemsgasto']['id'];
            }

            //Crea el gasto por eliminación de abono
            $data = array(
                'descripcion' => 'Se elimina abono de la prefactura ' . $prefactura,
                'usuario_id' => $this->Auth->user('id'),
                'empresa_id' => $empresaId,
                'fechagasto' => date('Y-m-d H:i:s'),
                'created' => date('Y-m-d  H:i:s'),
                'valor' => $valor,
                'cuenta_id' => $cuenta,
                'traslado' => '0',
                'itemsgasto_id' => $idItem,
                'tipoempresa' => 'P',
                'empresaasg_id' => $empresaId
            );

            $this->Gasto->create();
            $this->Gasto->save($data);
        }

        /**
         * Ajuste el saldo de la cuenta restando el valor 
         * del abono eliminado
         */
        public function ajustarSaldoCuenta($cuenta, $valor) {
            $this->loadModel('Cuenta');

            $infoCuenta = $this->Cuenta->obtenerDatosCuentaId($cuenta);
            $saldoFinal = floatval($infoCuenta['Cuenta']['saldo']) - floatval($valor);

            $this->Cuenta->actualizarSaldoCuenta($cuenta, $saldoFinal);
        }

        public function search() {
            $url = array();
            $url['action'] = 'index';

            foreach ($this->data as $kk => $vv) {
                $url[$kk] = $vv;
            }

            // redirect the user to the url
            $this->redirect($url, null, true);
        }
}
