<?php
App::uses('AppController', 'Controller');
/**
 * Cotizaciones Controller
 *
 * @property Cotizacione $Cotizacione
 * @property PaginatorComponent $Paginator
 */
class CotizacionesdetallesController extends AppController {
    
    /**
     * guarda el detalle de la cotizacion
     */
    public function ajaxGuardarDetalleCotiza(){
        $this->autoRender = false;
        $this->loadModel('Cotizacionesdetalle');                
        $this->loadModel('Cargueinventario');                
        $this->loadModel('Producto');                

        $posData = $this->request->data;        
        
        $catizacionId = $posData['catizacionId'];
        $nomProduct = $posData['nomProduct'];
        
        
        $data = array();
        
        $data['cantidad'] = '1';
        $data['costoventa'] = '0';        
        $data['cotizacione_id'] = $catizacionId;
        $data['costototal'] = '0';
        $data['nombreproducto'] = $nomProduct;
        
        $resp = $this->Cotizacionesdetalle->crearDetalleCotizacion($data);
         
        if($resp){
            //se obtiene la informacion del producto
            echo json_encode(array('valid' => '1', 'resp' => $resp));        
        }else{
            echo json_encode(array('valid' => '0'));        
        }        
    }
    
    /**
     * Elimina un registro del detalle de la cotizacion
     */
    public function ajaxEliminarDetalleCotiza(){
        $this->autoRender = false;
        $this->loadModel('Cotizacionesdetalle');
        
        $posData = $this->request->data;
        $data = array();
        
        $data['Cotizacionesdetalle.id'] = $posData['idDetCot'];

        $resp = $this->Cotizacionesdetalle->eliminarDetalleCotizacion($data);
        
        if($resp){
            echo json_encode(array('resp' => '1'));
        }else{
            echo json_encode(array('resp' => '0'));
        }
    }
    
    /**
     * actualizar la cantidad solicitada para la cotizacion
     */
    public function ajaxActualizarCantidadCotiza(){
        $this->autoRender = false;
        $this->loadModel('Cotizacionesdetalle');
        
        $posData = $this->request->data;
        $data = array();
        
        $cotDetId = $posData['cotDetId'];
        $nuevaCant = $posData['nuevaCant'];
        $valUnit = $posData['valUnit'];
        
        $data['id'] = $cotDetId;
        $data['cantidad'] = $nuevaCant;
        $data['costototal'] = $nuevaCant * $valUnit;

        $resp = $this->Cotizacionesdetalle->crearDetalleCotizacion($data);

        if($resp){
            echo json_encode(array('resp' => '1'));
        }else{
            echo json_encode(array('resp' => '0'));
        }                
    }    
    
    /**
     * Actualiza el valor unitario y total de un producto de una cotizacion
     */
    public function ajaxActualizarValorUnitarioCotiza(){
        $this->autoRender = false;
        $this->loadModel('Cotizacionesdetalle');
        
        $posData = $this->request->data;
        
        $data = array();
        
        $cotDetId = $posData['cotDetId'];
        $nuevoValor = $posData['nuevoValor'];
        $cantidad = $posData['cantidad'];
        
        $data['id'] = $cotDetId;
        $data['costoventa'] = $nuevoValor;
        $data['costototal'] = intval($nuevoValor) * intval($cantidad);

        $resp = $this->Cotizacionesdetalle->crearDetalleCotizacion($data);

        if($resp){
            echo json_encode(array('resp' => '1'));
        }else{
            echo json_encode(array('resp' => '0'));
        }                  
    }
    
    public function ajaxObtenerDetallesCotizacion(){
        $this->loadModel('Cotizacionesdetalle');

        $this->autoRender = false;
        $posData = $this->request->data;
        $cotizacionId = $posData['cotizacionId'];

        /*Se obtienen los depositos en los cuales está el usuario*/
        $arrCotDet = $this->Cotizacionesdetalle->obtenerCotizacionProductos($cotizacionId);
        
        echo json_encode(array('resp' => $arrCotDet));             
    }        
}