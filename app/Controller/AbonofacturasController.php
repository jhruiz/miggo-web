<?php
App::uses('AppController', 'Controller');

class AbonofacturasController extends AppController {

	public $components = array('Paginator');
        
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
                
                $resp = $this->Abonofactura->guardarAbonoFactura($prefactId, $usuarioId, $ttalAbono, $empresaId, $cuentaId);
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
