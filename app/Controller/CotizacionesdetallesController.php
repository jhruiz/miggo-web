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
        $idProd = $posData['idProd'];

        $data = array();
        
        $prod = [];
        if(!empty($idProd)){
            $prod = $this->Cargueinventario->obtenerInventarioId($idProd);
        }

        $data['cantidad'] = '1';
        $data['costoventa'] = !empty($prod['Cargueinventario']) ? $prod['Cargueinventario']['precioventa'] : '0';        
        $data['cargueinventario_id'] = !empty($prod['Cargueinventario']['id']) ? $prod['Cargueinventario']['id'] : '0';
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
    
    public function ajaxObtenerDetallesCotizacionPrefactura(){
        $this->loadModel('Cotizacionesdetalle');
        $this->loadModel('Prefactura');
        $this->loadModel('Prefacturasdetalle');

        $this->autoRender = false;
        $posData = $this->request->data;
        $cotizacionId = $posData['cotizacionId'];

        /*Se obtienen los depositos en los cuales está el usuario*/
        $arrCotDet = $this->Cotizacionesdetalle->obtenerCotizacionProductos($cotizacionId);
    
            $array = $arrCotDet;
            $array_num = count($array);
            $estadoPrefactura=false;
            $prefacturaId= "";


        for ($i = 1; $i < $array_num; ++$i){
            
            
            if($estadoPrefactura==false){
           
                $dataFac = array();
                $dataFac['usuarioId'] = $arrCotDet[0]['C']['usuario_id'];
                $dataFac['clienteId'] = $arrCotDet[0]['C']['cliente_id'];
                $dataFac['cargueinventarioId'] = $arrCotDet[0]['Cotizacionesdetalle']['cargueinventario_id'];
                $dataFac['cantidadventa']= $arrCotDet[0]['Cotizacionesdetalle']['cantidad'];
                $dataFac['precioventa ']=$arrCotDet[0]['Cotizacionesdetalle']['costoventa'];
                $dataFac['valorDescuento']= 0;
                $dataFac['porcentajeDescuento']= 0;
                $dataFac['impuesto']= $arrCotDet[0]['I']['valor'];
                $dataFac['prefactId']= $this->Prefactura->guardarPrefactura($dataFac['usuarioId'],$dataFac['clienteId']); 
                $estadoPrefactura=true;
                $prefacturaId=$dataFac['prefactId'];
                //      /*se descuenta la cantidad del producto prefacturado del inventario*/
                // /*se obtiene la cantidad existente en el stock*/
                $inventActual = $this->Cargueinventario->obtenerInventarioId($dataFac['cargueinventarioId']);
                
                $inventActual = $this->Cargueinventario->obtenerInventarioId($arrCotDet[0]['Cotizacionesdetalle']['cargueinventario_id']);
                $cantStock = $inventActual['Cargueinventario']['existenciaactual'];
                $existFinal = $cantStock - $dataFac['cantidadventa'];
                
                /*se actualiza la cantidad en stock tras la prefactura*/
                $this->Cargueinventario->actalizarExistenciaStock($dataFac['cargueinventarioId'], $existFinal);
                
                if($dataFac['prefactId'] != '0'){
                        $detalleId = $this->Prefacturasdetalle->guardarDetallePrefactura($dataFac['cantidadventa'],$dataFac['precioventa ']
                        ,$dataFac['cargueinventarioId'] ,$dataFac['prefactId'], $dataFac['valorDescuento'], 
                        $dataFac['porcentajeDescuento'], $dataFac['impuesto']);
                }

                if($detalleId != '0' && $detalleId != ""){
                    echo json_encode(array('resp' => $detalleId, 'prefactId' => $dataFac['prefactId']));
                }else{
                    echo json_encode(array('resp' => $detalleId, 'prefactId' => $dataFac['prefactId']));
                }
            }
    
            if($estadoPrefactura==true)    
            {
                $dataFac = array();
                $dataFac['usuarioId'] = $arrCotDet[$i]['C']['usuario_id'];
                $dataFac['clienteId'] = $arrCotDet[$i]['C']['cliente_id'];
                $dataFac['cargueinventarioId'] = $arrCotDet[$i]['Cotizacionesdetalle']['cargueinventario_id'];
                $dataFac['cantidadventa']= $arrCotDet[$i]['Cotizacionesdetalle']['cantidad'];
                $dataFac['precioventa ']=$arrCotDet[$i]['Cotizacionesdetalle']['costoventa'];
                $dataFac['valorDescuento']= 0;
                $dataFac['porcentajeDescuento']= 0;
                $dataFac['impuesto']= $arrCotDet[$i]['I']['valor'];

                //      /*se descuenta la cantidad del producto prefacturado del inventario*/
                // /*se obtiene la cantidad existente en el stock*/
                $inventActual = $this->Cargueinventario->obtenerInventarioId($dataFac['cargueinventarioId']);
                
                $inventActual = $this->Cargueinventario->obtenerInventarioId($arrCotDet[0]['Cotizacionesdetalle']['cargueinventario_id']);
                $cantStock = $inventActual['Cargueinventario']['existenciaactual'];
                $existFinal = $cantStock - $dataFac['cantidadventa'];
                
                /*se actualiza la cantidad en stock tras la prefactura*/
                $this->Cargueinventario->actalizarExistenciaStock($dataFac['cargueinventarioId'], $existFinal);
                
                if($dataFac['prefactId'] != '0'){
                        $detalleId = $this->Prefacturasdetalle->guardarDetallePrefactura($dataFac['cantidadventa'],$dataFac['precioventa ']
                        ,$dataFac['cargueinventarioId'] ,$prefacturaId, $dataFac['valorDescuento'], 
                        $dataFac['porcentajeDescuento'], $dataFac['impuesto']);
                }
                
            }

        }    

    }
}