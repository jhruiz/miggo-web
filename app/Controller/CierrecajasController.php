    <?php
App::uses('AppController', 'Controller');
/**
 * Paises Controller
 *
 * @property Paise $Paise
 * @property PaginatorComponent $Paginator
 */
class CierrecajasController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
            $this->loadModel('Cuenta');
            $this->loadModel('Observacionescierre');
            
            $fecha = date('Y-m-d');
            $cuenta = "";                              

            if(isset($this->passedArgs['Cierrecaja']['Fecha']) && !empty($this->passedArgs['Cierrecaja']['Fecha'])){
                $fecha = $this->passedArgs['Cierrecaja']['Fecha'];
            }
            
            if(isset($this->passedArgs['Cierrecaja']['Cuenta']) && !empty($this->passedArgs['Cierrecaja']['Cuenta'])){
                $cuenta = $this->passedArgs['Cierrecaja']['Cuenta'];
            }
            
            $empresaId = $this->Auth->user('empresa_id');
            
            //se obtiene el listado de cajas
            $listCuentas = $this->Cuenta->obtenerCuentasEmpresa($empresaId);            
            
            //se obtiene el cierre de cajas
            $cierreDiario = $this->Cierrecaja->obtenerCierreCajas($empresaId, $fecha, $cuenta);

            //se obtienen las observaciones del cierre
            $arrObsCierre = $this->Observacionescierre->obtenerObsFecha(date('Y-m-d'), $empresaId);
            $obsCierre = !empty($arrObsCierre['0']) ? $arrObsCierre['0']['Observacionescierre']['descripcion'] : "";
            
            $this->set(compact('listCuentas', 'cierreDiario', 'fecha', 'cuenta', 'obsCierre'));
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
