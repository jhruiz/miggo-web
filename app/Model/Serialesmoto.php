<?php
App::uses('AppModel', 'Model');

class Serialesmoto extends AppModel {

    //funcion que registra la ultima actividad del usuario en el sistema
    public function guardarSerialesMotos($data){
        $seriales = new Serialesmoto();
        
        if($seriales->save($data)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Actualiza los seriales con el id asignado en el cargue de inventarios
     */
    public function asignarCargueInventarioSerial($cargueinventarioId, $precargueInventarioId){

        //valida si el precargue tiene seriales asignados
        $seriales = $this->find('first', array(
            'conditions' => array(
                'Serialesmoto.precargueinventario_id' => $precargueInventarioId
            ),
            'recursive' => '-1'
        ));
        
        if( !empty($seriales) ) {
            $this->updateAll(array(
                'Serialesmoto.cargueinventario_id' => $cargueinventarioId), 
                    array(
                        'Serialesmoto.precargueinventario_id' => $precargueInventarioId)); 
        } 

    }

    /** Se obtienen todos los seriales asignados a un precargue */
    public function obtenerSerialesPrecargue( $precargueId ) {

        $seriales = $this->find('all', array(
            'conditions' => array(
                'Serialesmoto.precargueinventario_id' => $precargueId
            ),
            'recursive' => '-1'
        ));

        return $seriales;

    }

    /** Se obtienen todos los seriales asignados a un cargue de inventario*/
    public function obtenerSerialesCargueInv( $cargueId ) {

        $seriales = $this->find('all', array(
            'conditions' => array(
                'Serialesmoto.cargueinventario_id' => $cargueId
            ),
            'recursive' => '-1'
        ));

        return $seriales;

    }

    /**
     * Actualiza los seriales con el detalle de la factura al cual se está asignando
     */
    public function asociarPrefacturaSeriales( $color, $modelo, $serialId, $prefactDetalleId ){

        // Se actualiza a vacio si otro serial ya tiene asociada la prefacturadetalle_id

        $resp = $this->updateAll(
                array(
                    'Serialesmoto.color' => null,
                    'Serialesmoto.modelo' => null,
                    'Serialesmoto.prefacturasdetalle_id' => null
                ), 
                array(
                    'Serialesmoto.prefacturasdetalle_id' => $prefactDetalleId
                )
            );

            if( $resp ) {

                return $this->updateAll(
                        array(
                            'Serialesmoto.color' => "'" . $color . "'",
                            'Serialesmoto.modelo' => "'" . $modelo . "'",
                            'Serialesmoto.prefacturasdetalle_id' => $prefactDetalleId
                        ), 
                        array(
                            'Serialesmoto.id' => $serialId
                        )
                    );
            }

            return false;
    }

    public function asociarFacturaSeriales( $prefactDetalleId, $facturaId ) {
        //valida si existen seriales asociados al detalle de la prefactura
        $seriales = $this->find('first', array(
            'conditions' => array(
                'Serialesmoto.prefacturasdetalle_id' => $prefactDetalleId
            ),
            'recursive' => '-1'
        ));
        
        if( !empty($seriales) ) {
            $this->updateAll(array(
                'Serialesmoto.factura_id' => $facturaId,
                'Serialesmoto.estado_id' => '0'
                ), 
                    array(
                        'Serialesmoto.prefacturasdetalle_id' => $prefactDetalleId)); 
        } 
    }

}
