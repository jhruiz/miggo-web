<?php
App::uses('AppController', 'Controller');
/**
 * Paises Controller
 *
 * @property Paise $Paise
 * @property PaginatorComponent $Paginator
 */
class FacturaCuentaValoresController extends AppController {
    
    public $components = array('Paginator');
    /**
     * guarda los metodos de pago y los valores de la factura
     */
    public function guardarFacturaCuentasValores(){
        
        $this->loadModel('Tipopago');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Prefactura');
        $this->autoRender = false;
        
        $payMeths = $this->request->data['arrPayMeth'];
        $prefactId = $this->request->data['prefactId'];
        $empresaId = $this->Auth->user('empresa_id');
        $userId = $this->Auth->user('id');
        
        $resp = false;

        foreach ($payMeths as $pm){
            //se obtiene la cuenta asociada al método de pago
            $infoTipoP = $this->Tipopago->obtenerTipoPagoPorId($pm['payment_type']);

            //si es contabilizar (credito interno), no se guarda el dinero en un banco y se registra la cuenta por cobrar
            if($infoTipoP['Tipopago']['contabilizar']){
                //se obtienen los datos de la prefactura
                $prefactData = $this->Prefactura->obtenerPrefacturaPorId($prefactId);
                $diasCredito = !empty($prefactData['Cliente']['diascredito']) ? $prefactData['Cliente']['diascredito'] : '30';
                $fechaAct = date('Y-m-d');
                $fechaPago = $this->sumarDiasFecha($fechaAct,$diasCredito);
                $this->Cuentascliente->guardarParcialCtaXCobrar($empresaId, $pm['val_pay_meth'], $infoTipoP['Tipopago']['id'], $prefactId, $userId, $fechaPago);
            }else{
                $resp = $this->FacturaCuentaValore->guardarCuentaValorPrefact($infoTipoP['Tipopago']['cuenta_id'], $pm['val_pay_meth'], $prefactId, $pm['payment_type'], $userId);

                if($resp){
                    $this->enviarDineroCuenta($pm['val_pay_meth'], $infoTipoP['Tipopago']['cuenta_id']);
                }                  
            }                        
        }
        
        echo json_encode(array('resp' => $resp));

    }

	public function sumarDiasFecha($fecha,$dias){
		if(empty($dias)){
			$dias = 30;
		}
		$fechaNew = new DateTime($fecha);
		$fechaNew->add(new DateInterval('P' . $dias . 'D'));
		$fechaFin = $fechaNew->format('Y-m-d');
		return $fechaFin;          
	}    
    
    public function enviarDineroCuenta($pagocontado, $cuentaId){
        $this->loadModel('Cuenta');
        $arrDatCuenta = $this->Cuenta->obtenerDatosCuentaId($cuentaId);
        $saldoFinal = intval($arrDatCuenta['Cuenta']['saldo']) + (intval($pagocontado));

        $this->Cuenta->actualizarSaldoCuenta($cuentaId,$saldoFinal);            
    }    
    
    public function index() {
        $this->loadModel('Cuenta');
        $this->loadModel('Tipopago');
        
        $empresaId = $this->Auth->user('empresa_id');
        
        
        if(!empty($this->passedArgs['tipocuentas'])){
            $filter['FacturaCuentaValore.cuenta_id'] = $this->passedArgs['tipocuentas'];
        }
        
        if(!empty($this->passedArgs['tipopagos'])) {
            $filter['FacturaCuentaValore.tipopago_id'] = $this->passedArgs['tipopagos'];
        }
        
        if(!empty($this->passedArgs['fechaInicio']) && !empty($this->passedArgs['fechaFin'])){
            $filter['F.created BETWEEN ? AND ?'] = array($this->passedArgs['fechaInicio'] . ' 00:00:01', $this->passedArgs['fechaFin'] . ' 23:23:59');
        } else {
            $filter['F.created BETWEEN ? AND ?'] = array(date("Y-m-d") . ' 00:00:01', date("Y-m-d") . ' 23:23:59');
        }
        
        $filter['F.empresa_id'] = $empresaId;
        
        //se obtiene el listado de cuentas
        $tipoCuentas = $this->Cuenta->obtenerCuentasEmpresa($empresaId);

        //se obtiene el listado de tipos de pago
        $tipoPago = $this->Tipopago->obtenerListaTiposPagos($empresaId);
        
        //se obtienen los metodos de pago usados para cancelar las facturas
        $pagosFacturas = $this->FacturaCuentaValore->obtenerMetodosPagosFacturas($filter);
        $this->set(compact('pagosFacturas', 'tipoCuentas', 'tipoPago'));
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
