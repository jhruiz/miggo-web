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
        $this->loadModel('Deposito');                
        $this->loadModel('CargueinventariosImpuesto');                

        $posData = $this->request->data;        

        $catizacionId = $posData['catizacionId'];
        $descripcionProd = $posData['nomProduct'];
        $productoId = $posData['idProd'];
        $tipoCot = $posData['tipoCot'];
        $usuarioId = $this->Auth->user('id');

        /**Se obtiene la info del producto */
        $produtoInfo = $this->Cargueinventario->obtenerProductoPorId($productoId);

        /**Se obtiene la información del impuesto para el producto */
        $impuesto = $this->CargueinventariosImpuesto->obtenerImpuestosProducto($produtoInfo['Cargueinventario']['id']);
                        
        $tasaIvaPorc = 0;
        $tasaIncPorc = 0;
        $tasaBolsaVal = 0;
        if ( $tipoCot == '1') {
            foreach( $impuesto as $imp ) {

                // tratamiento para el IVA
                if( $imp['TX']['code'] ==  '01' ) {
                    $tasaIvaPorc = (string) ($imp['IMP']['valor'] ?? 0);
                }
                
                // tratamiento para el INC
                if( $imp['TX']['code'] == '04' ) {
                    $tasaIncPorc = (string) ($imp['IMP']['valor'] ?? 0);     
                }

                // tratamiento para el impuesto a la bolsa
                if( $imp['TX']['code'] == '22' ) {
                    $tasaBolsaVal = (string) ($imp['IMP']['valor'] ?? 0);
                }   
            }
        }

        $data = array();

        $data['cantidad'] = '1';
        $data['costoventa'] = $produtoInfo['Cargueinventario']['precioventa'];        
        $data['cargueinventario_id'] = $produtoInfo['Cargueinventario']['id'];
        $data['cotizacione_id'] = $catizacionId;
        $data['costototal'] = $produtoInfo['Cargueinventario']['precioventa'];
        $data['nombreproducto'] = 'N/A';
        $data['descuento'] = '0';
        $data['porcentaje'] = '0';
        $data['impuesto'] = $tasaIvaPorc;
        $data['impoconsumo'] = $tasaIncPorc;
        $data['incbolsa'] = $tasaBolsaVal;
        
        $resp = $this->Cotizacionesdetalle->crearDetalleCotizacion($data);        

        if($resp){
            //se obtiene la informacion del producto
            echo json_encode(array('valid' => '1', 'resp' => $resp, 'prod' => $produtoInfo, 'data' => $data));        
        }else{
            echo json_encode(array('valid' => '0'));        
        }        
    }
    
    /**
     * guarda el detalle de la cotizacion
     */
    public function ajaxCrearPrefactura(){ 
        $this->autoRender = false;
        $this->loadModel('Cotizacionesdetalle');                
        $this->loadModel('Cargueinventario');                
        $this->loadModel('Producto');                

        $posData = $this->request->data;        
        
        $catizacionId = $posData['catizacionId'];
        $nomProduct = $posData['nomProduct'];
        $idProd = $posData['idProd'];
        
        $data = array();
        
        $prod = [];
        if(!empty($idProd)){
            $prod = $this->Cargueinventario->obtenerInventarioId($idProd);
        }

        $data['cantidad'] = '1';
        $data['costoventa'] = !empty($prod['Cargueinventario']) ? $prod['Cargueinventario']['precioventa'] : '0';        
        $data['cargueinventario_id'] = $prod['Cargueinventario']['id'];
        $data['cotizacione_id'] = $catizacionId;
        $data['costototal'] = !empty($prod['Cargueinventario']) ? $prod['Cargueinventario']['precioventa'] : '0';
        $data['nombreproducto'] = $nomProduct;
        
        $resp = $this->Cotizacionesdetalle->crearDetalleCotizacion($data);        

        if($resp){
            //se obtiene la informacion del producto
            echo json_encode(array('valid' => '1', 'resp' => $resp, 'prod' => $prod));        
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
        
        $data['id'] = $posData['cotDetId'];
        $data['cantidad'] = $posData['nuevaCant'];

        $resp = $this->Cotizacionesdetalle->crearDetalleCotizacion($data);

        if($resp){
            echo json_encode(array('resp' => '1'));
        }else{
            echo json_encode(array('resp' => '0'));
        }                
    }   
    
    public function actualizarPorcentajeValorDttoCot() {
        $this->autoRender = false;
        $this->loadModel('Cotizacionesdetalle');
        
        $posData = $this->request->data;
    
        $data = array();
        
        $data['id'] = $posData['idCot'];
        $data['descuento'] = $posData['valDtto'];
        $data['porcentaje'] = $posData['nuevoPor'];

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
        
        $data['id'] = $posData['cotDetId'];
        $data['costoventa'] = $posData['nuevoValor'];

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
     
        echo json_encode(array('est' => true, 'resp' => $arrCotDet));             
    }        

    /**
     * Crea la orden de trabajo desde la cotización
     */
    public function crearOrdenTrabajo( $tipoCot, $cotizacionId ) {
        $this->loadModel('Ordentrabajo'); 
        $this->loadModel('Ordenestado'); 
        $this->loadModel('Cotizacione'); 

        /** Obtiene el estado para la OT en estado final con (3) o sin (2) impuestos */
        $ordenFinal = $tipoCot == '1' ? '3' : '2';
        $infoEstado = $this->Ordenestado->obtenerEstadoOrdenFinal( $ordenFinal );

        /** Obtiene la información de la cotización */
        $infoCotizacion = $this->Cotizacione->obtenerCotizacionCliDet($cotizacionId);

        $data = array();

        //se obtiene la informacion del formulario para realizar la creacion de la orden de trabajo
        $data['ordenestado_id'] = $infoEstado['Ordenestado']['id'];
        $data['kilometraje'] = '0';
        $data['vehiculo_id'] = !empty($infoCotizacion['0']['Cotizacione']['vehiculo_id']) ? $infoCotizacion['0']['Cotizacione']['vehiculo_id'] : null;
        $data['usuario_id'] = $infoCotizacion['0']['Cotizacione']['usuario_id'];
        $data['cliente_id'] = $infoCotizacion['0']['Cotizacione']['cliente_id'];
        $data['fecha_ingreso'] = date("Y-m-d H:i:s");
        $data['fecha_salida'] = null;
        $data['plantaservicio_id'] = null;
        $data['codigo'] = date("YmdHis");
        $data['soat'] = null;
        $data['tecnomecanica'] = null;
        
        return $this->Ordentrabajo->crearActualizarOrdenTrabajo($data);

    }
    
    public function ajaxObtenerDetallesCotizacionPrefactura(){
        $this->loadModel('Cotizacionesdetalle');
        $this->loadModel('Prefactura');
        $this->loadModel('Prefacturasdetalle');
        $this->loadModel('OrdentrabajosSuministro');
        $this->loadModel('Cargueinventario');

        $this->autoRender = false;
        $posData = $this->request->data;
        $cotizacionId = $posData['cotizacionId'];
        $crearOT = $posData['crearOT']; 
        $tipoCot = $posData['tipoCot'];
        $idOT = null;
        $resp = false;

        /**Se valida si se quiere crear la OT y si va o no con impuestos para el estado **/
        if($crearOT == '1') {
            $idOT = $this->crearOrdenTrabajo( $tipoCot, $cotizacionId );
        }

        /*Se obtienen los depositos en los cuales está el usuario*/
        $arrCotDet = $this->Cotizacionesdetalle->obtenerCotizacionProductos($cotizacionId);

        /**Se crea la prefactura y se obtiene el id */
        $prefacturaId = $this->Prefactura->guardarPrefactura($arrCotDet[0]['C']['usuario_id'],$arrCotDet[0]['C']['cliente_id'], $idOT, $tipoCot);   //$usuarioId,$clienteId,$idOrdenT = null, $esFactura

        if($prefacturaId) {
            for ($i = 0; $i < count($arrCotDet); ++$i){
    
                /**Actualiza la cantidad en el inventario */
                $inventActual = $this->Cargueinventario->obtenerInventarioId($arrCotDet[$i]['Cotizacionesdetalle']['cargueinventario_id']);
                $existFinal = $inventActual['Cargueinventario']['existenciaactual'] - $arrCotDet[$i]['Cotizacionesdetalle']['cantidad'];
                $this->Cargueinventario->actalizarExistenciaStock($arrCotDet[$i]['Cotizacionesdetalle']['cargueinventario_id'], $existFinal);
    
                /**Crear el detalle de la prefactura */
                $this->Prefacturasdetalle->guardarDetallePrefactura($arrCotDet[$i]['Cotizacionesdetalle']['cantidad'],
                                                                    $arrCotDet[$i]['Cotizacionesdetalle']['costoventa'],
                                                                    $arrCotDet[$i]['Cotizacionesdetalle']['cargueinventario_id'],
                                                                    $prefacturaId, 
                                                                    $arrCotDet[$i]['Cotizacionesdetalle']['descuento'], 
                                                                    $arrCotDet[$i]['Cotizacionesdetalle']['porcentaje'], 
                                                                    $arrCotDet[$i]['Cotizacionesdetalle']['impuesto'], 
                                                                    $arrCotDet[$i]['Cotizacionesdetalle']['impoconsumo'], 
                                                                    $arrCotDet[$i]['Cotizacionesdetalle']['incbolsa']);

                if($idOT){
                    /**Crea el suministro para la OT */
                    $this->OrdentrabajosSuministro->guardarSuministroOrden($idOT, $arrCotDet[$i]['Cotizacionesdetalle']['cargueinventario_id'], $arrCotDet[$i]['Cotizacionesdetalle']['cantidad']);
                }
            
            }   

            /**Se elimina la cotización */
            //$this->Cotizacione->eliminarCotizacion($cotizacionId);
            
            $resp = true;
        }
        echo json_encode(array('resp' => $resp));
    }
}