<?php
App::uses('AppModel', 'Model');

class Abonofactura extends AppModel {

        
    /**
     * guarda los abonos de una prefactura
     * @param type $prefacturaId
     * @param type $usuarioId
     * @param type $valor
     * @param type $empresaId
     * @return boolean
     */
    public function guardarAbonoFactura($prefacturaId, $usuarioId, $valor, $empresaId, $cuentaId){

        $data=array();                        

        $abonoFactura = new Abonofactura();

        $data['prefactura_id'] = $prefacturaId;
        $data['usuario_id'] = $usuarioId;
        $data['valor'] = $valor;
        $data['empresa_id'] = $empresaId;
        $data['cuenta_id'] = $cuentaId;

        if($abonoFactura->save($data)){
            return true;
        }else{
            return false;
        }                          
    }

    /**
     * Se obtienen los abonos de una prefactura especifica
     * @param type $prefacturaId
     * @return type
     */
    public function obtenerAbonosPrefactura($prefacturaId){
        $abonos = $this->find('all', array(
            'conditions' => array(
                'Abonofactura.prefactura_id' => $prefacturaId
                )
            ));
        return $abonos;
    }
    
    /**
     * Se obtienen los abonos de una empresa entre unas fechas específicas
     * @param type $fechIni
     * @param type $fecFin
     * @param type $empresaId
     * @return type
     */
    public function obtenerAbonosCierreDiario($fechIni, $fecFin, $empresaId, $tipo){  
        $arr_join = array(); 
        
        if($tipo == '1'){
            $tipoAbono = 'Abonofactura.factura_id is null';
            array_push($arr_join, array(
                'table' => 'prefacturas', 
                'alias' => 'PF', 
                'type' => 'INNER',
                'conditions' => array(
                    'PF.id=Abonofactura.prefactura_id'
                    )                
            ));                   
 
        }else{
            $tipoAbono = 'Abonofactura.factura_id is not null';
            array_push($arr_join, array(
                'table' => 'facturas', 
                'alias' => 'PF', 
                'type' => 'INNER',
                'conditions' => array(
                    'PF.id=Abonofactura.factura_id'
                    )                
            ));                       
        } 
                
        array_push($arr_join, array(
            'table' => 'clientes', 
            'alias' => 'CL', 
            'type' => 'INNER',
            'conditions' => array(
                'PF.cliente_id=CL.id'
                )                
        ));  

        array_push($arr_join, array(
            'table' => 'cuentas',
            'alias' => 'C',
            'type' => 'INNER',
            'conditions' => array(
                'C.id=Abonofactura.cuenta_id'
            )
        ));
      
        $abonos = $this->find('all', array(
            'joins' => $arr_join,
            'conditions' => array(
                'Abonofactura.created BETWEEN ? AND ?' => array($fechIni, $fecFin),
                'Abonofactura.empresa_id' => $empresaId,
                $tipoAbono
            ),
            'fields' => array('Abonofactura.*', 'CL.*', 'C.*'),
            'recursive' => '-1'
            ));
        
        return $abonos;
    }
    
    /**
     * Actualiza los abonos con los id de la factura a la cual pertenece
     * @param type $prefacturaId
     * @param type $facturaId
     */
    public function asignarFacturaAbonos($prefacturaId, $facturaId){
        $this->updateAll(array(
            'Abonofactura.factura_id' => $facturaId), 
                array(
                    'Abonofactura.prefactura_id' => $prefacturaId)); 
    }
    
    /**
     * Obtener los abonos de una factura
     * @param type $facturaId
     * @return type
     */
    public function obtenerAbonosFactura($facturaId){
        $abonosFacts = $this->find('all', array('conditions' => array('Abonofactura.factura_id' => $facturaId), 'recursive' => '-1'));
        return $abonosFacts; 
    }
    
    /**
     * se obtienen los abonos realizados a una cuenta especifica en un rango de fechas
     * @param type $fechIni
     * @param type $fecFin
     * @param type $cuentaId
     * @return type
     */
    public function obtenerAbonosACuenta($fechIni, $fecFin, $cuentaId){
        $abonos = $this->find('all', array(
            'conditions' => array(
                'Abonofactura.created BETWEEN ? AND ?' => array($fechIni, $fecFin),
                'Abonofactura.cuenta_id' => $cuentaId, 
                'Abonofactura.factura_id is null'
            ),
            'recursive' => '-1'
            ));
        
        return $abonos;

    }
    
    /**
     * Obtiene los abonos realizados a una prefactura,
     * los mismos se obtienen con el id de la orden de trabajo
     * @param type $ordenId
     * @return type
     */
    public function obtenerAbonosOrdenId($ordenId){
        $arr_join = [];
        
        array_push($arr_join, array(
            'table' => 'prefacturas',
            'alias' => 'PF',
            'type' => 'INNER',
            'conditions' => array(
                'PF.id=Abonofactura.prefactura_id'
            )
        ));
        
        array_push($arr_join, array(
            'table' => 'ordentrabajos',
            'alias' => 'OT',
            'type' => 'INNER',
            'conditions' => array(
                'OT.id=PF.ordentrabajo_id'
            )
        ));
        
        array_push($arr_join, array(
            'table' => 'usuarios',
            'alias' => 'U',
            'type' => 'INNER',
            'conditions' => array(
                'U.id=Abonofactura.usuario_id'
            )
        ));
        
        array_push($arr_join, array(
            'table' => 'clientes',
            'alias' => 'CL',
            'type' => 'INNER',
            'conditions' => array(
                'CL.id=PF.cliente_id'
            )
        ));        
      
        $abonos = $this->find('all', array(
            'joins' => $arr_join,
            'conditions' => array(
                'OT.id' => $ordenId
            ),
            'fields' => array(
                'Abonofactura.id', 
                'Abonofactura.valor', 
                'Abonofactura.created', 
                'U.id',
                'U.nombre',
                'CL.id',
                'CL.nombre'
                ),
            'recursive' => '-1'
            ));
        
        return $abonos;        
    }
    
    /**
     * guarda los abonos de una factura con cuenta pendiente por un cliente
     * @param type $prefacturaId
     * @param type $usuarioId
     * @param type $valor
     * @param type $empresaId
     * @return boolean
     */
    public function guardarAbonoFacturaCuentaCliente($facturaId, $usuarioId, $valor, $empresaId, $cuentaId, $cuentaClienteId, $prefacturaId){

        $data=array();                        

        $abonoFactura = new Abonofactura();
        
        $data['factura_id'] = $facturaId;
        $data['prefactura_id'] = $prefacturaId;
        $data['usuario_id'] = $usuarioId;
        $data['valor'] = $valor;
        $data['empresa_id'] = $empresaId;
        $data['cuenta_id'] = $cuentaId;
        $data['cuentacliente_id'] = $cuentaClienteId;

        if($abonoFactura->save($data)){
            return true;
        }else{
            return false;
        }                          
    }    
    
    /**
     * se obtienen los abonos realizados a las cuentas del cliente
     * @param type $cuentaClienteId
     * @return type
     */
    public function obtenerAbonosCuentasCliente($cuentaClienteId){
        $arr_join = [];
        
        array_push($arr_join, array(
            'table' => 'usuarios',
            'alias' => 'U',
            'type' => 'INNER',
            'conditions' => array(
                'U.id=Abonofactura.usuario_id'
            )
        ));        
        
        array_push($arr_join, array(
            'table' => 'cuentas',
            'alias' => 'C',
            'type' => 'INNER',
            'conditions' => array(
                'C.id=Abonofactura.cuenta_id'
            )
        ));        
        
        array_push($arr_join, array(
            'table' => 'cuentasclientes',
            'alias' => 'CC',
            'type' => 'INNER',
            'conditions' => array(
                'CC.id=Abonofactura.cuentacliente_id',
                'CC.id' => $cuentaClienteId
            )
        ));        
        
        array_push($arr_join, array(
            'table' => 'clientes',
            'alias' => 'CL',
            'type' => 'INNER',
            'conditions' => array(
                'CL.id=CC.cliente_id'
            )
        ));        
        
        array_push($arr_join, array(
            'table' => 'tipopagos',
            'alias' => 'TP',
            'type' => 'INNER',
            'conditions' => array(
                'TP.id=CC.tipopago_id'
            )
        ));        
        
        array_push($arr_join, array(
            'table' => 'facturas',
            'alias' => 'F',
            'type' => 'INNER',
            'conditions' => array(
                'F.id=Abonofactura.factura_id'
            )
        ));        
      
        $abonos = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'Abonofactura.*',
                'U.*',
                'C.*',
                'CC.*',
                'CL.*',
                'TP.*',
                'F.*'
                ),
            'recursive' => '-1'
            ));        
        
        return $abonos;          
    }
}
