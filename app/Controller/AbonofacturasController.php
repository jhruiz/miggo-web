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
