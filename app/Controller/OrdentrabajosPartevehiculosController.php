<?php
App::uses('AppController', 'Controller');
/**
 * Paises Controller
 *
 * @property Paise $Paise
 * @property PaginatorComponent $Paginator
 */
class OrdentrabajosPartevehiculosController extends AppController {
    
    /**
     * actualiza por ajax el estado de una parte de un vehiculo asociada a una orden de trabajo
     */
    public function actualizarEstadoParteOrden(){
        $this->loadModel('OrdentrabajosPartevehiculo');
        $this->autoRender = false;        
        
        $posData = $this->request->data;
        $ordenId = $posData['ordenId'];
        $estadoId = $posData['estadoId'];
        $parteId = $posData['parteId'];
        
        //se obtiene el registro a actualizar
        $arrEstP = $this->OrdentrabajosPartevehiculo->obtenerParteEstadoOrden($ordenId, $parteId);
        
        //se obtiene la informacion del estado por id
        $arrEstado = $this->OrdentrabajosPartevehiculo->actualizarEstadoParte($arrEstP['OrdentrabajosPartevehiculo']['id'], $estadoId);
                
        echo json_encode(array('resp' => $arrEstado));
    }
    
    /**
     * obtener las partes seleccionadas como extras
     */
    public function ajaxObtenerExtras(){
        $this->loadModel('OrdentrabajosPartevehiculo');
        $this->loadModel('Abonofactura');
        $this->autoRender = false;        
        
        $posData = $this->request->data;
        $ordenId = $posData['ordenId'];
        
        //se obtienen los abonos realizados a la prefactura
        $abonos = $this->Abonofactura->obtenerAbonosOrdenId($ordenId);
        
        //se obtiene el registro a actualizar
        $arrEstE = $this->OrdentrabajosPartevehiculo->obtenerPartesExtra($ordenId);
        
        echo json_encode(array('resp' => $arrEstE, 'abonos' => $abonos));        
    }
    
    /**
     * obtiene todas las partes del vehiculo y su estado, todo relacionado 
     * a una orden de trabajo especifica
     */
    public function obtenerPartesOrdenTrabajo(){
        $this->loadModel('OrdentrabajosPartevehiculo');
        $this->loadModel('OrdentrabajosSuministro');
        $this->autoRender = false;        
        
        $posData = $this->request->data;
        $ordenId = $posData['ordenId'];        
        
        //se obtiene el registro de partes y estados
        $arrEstE = $this->OrdentrabajosPartevehiculo->obtenerPartesOrdenTrabajo($ordenId);
        
        $arrSuministros = $this->OrdentrabajosSuministro->obtenerSuministrosProductos($ordenId);
        
        echo json_encode(array('resp' => $arrEstE, 'suministros' => $arrSuministros));          
    }
    
}
