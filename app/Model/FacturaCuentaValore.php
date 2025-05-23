<?php
App::uses('AppModel', 'Model');
/**
 * Paise Model
 *
 * @property Ciudade $Ciudade
 */
class FacturaCuentaValore extends AppModel
{

    public function guardarCuentaValorPrefact($cuenta_id, $val_pay_meth, $prefactId, $tipoPagoId, $usuarioId)
    {
        $facturaCuenta = new FacturaCuentaValore();

        $data = [];

        $data['cuenta_id'] = $cuenta_id;
        $data['valor'] = $val_pay_meth;
        $data['prefactura_id'] = $prefactId;
        $data['tipopago_id'] = $tipoPagoId;
        $data['usuario_id'] = $usuarioId;

        if ($facturaCuenta->save($data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Actualiza la factura_id de los pagos realizados en la factura una vez creada ésta
     * @param type $prefacturaId
     * @param type $facturaId
     */
    public function actualizarIdFacturaCuentaValor($facturaId, $usuarioId)
    {
        $this->updateAll(array(
            'FacturaCuentaValore.factura_id' => $facturaId),
            array(
                'FacturaCuentaValore.usuario_id' => $usuarioId,
                'FacturaCuentaValore.created > ' => date('Y-m-d 00:00:01'),
                'FacturaCuentaValore.factura_id is null'
            )
        );
    }

    /***
     * obtiene las facturas y todos los pagos realizados a cada una
     */
    public function obtenerMetodosPagosFacturas($filter)
    {
        $arr_join = array();

        array_push($arr_join, array(
            'table' => 'facturas',
            'alias' => 'F',
            'type' => 'LEFT',
            'conditions' => array(
                'F.id=FacturaCuentaValore.factura_id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'cuentas',
            'alias' => 'C',
            'type' => 'INNER',
            'conditions' => array(
                'C.id=FacturaCuentaValore.cuenta_id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'prefacturas',
            'alias' => 'P',
            'type' => 'LEFT',
            'conditions' => array(
                'P.id=FacturaCuentaValore.prefactura_id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'tipopagos',
            'alias' => 'T',
            'type' => 'INNER',
            'conditions' => array(
                'T.id=FacturaCuentaValore.tipopago_id',
            ),
        ));

        $factCuentVal = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'F.*',
                'C.*',
                'P.*',
                'T.*',
                'FacturaCuentaValore.*',
            ),
            'order' => 'F.id',
            'conditions' => $filter,
            'recursive' => '-1')
        );

        return $factCuentVal;
    }

    /**
     * obtiene los pagos y las cuentas afectadas para una factura específica
     * @param type $facturaId
     * @return type
     */
    public function obtenerPagosFactura($facturaId)
    {
        $arr_join = [];

        array_push($arr_join, array(
            'table' => 'cuentas',
            'alias' => 'C',
            'type' => 'INNER',
            'conditions' => array(
                'C.id=FacturaCuentaValore.cuenta_id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'tipopagos',
            'alias' => 'T',
            'type' => 'INNER',
            'conditions' => array(
                'T.id=FacturaCuentaValore.tipopago_id',
            ),
        ));

        $factCuentVal = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'C.*',
                'T.*',
                'FacturaCuentaValore.*',
            ),
            'order' => 'FacturaCuentaValore.factura_id',
            'conditions' => array('FacturaCuentaValore.factura_id' => $facturaId),
            'recursive' => '-1')
        );

        return $factCuentVal;
    }

    /**
     * Obtiene los pagos realizados a una factura
     */
    public function obtenerInfoTipoPagoEfectivo( $facturaId )
    {
        $arr_join = [];

        array_push($arr_join, array(
            'table' => 'tipopagos',
            'alias' => 'T',
            'type' => 'INNER',
            'conditions' => array(
                'T.id=FacturaCuentaValore.tipopago_id',
            ),
        ));

        $data = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'T.*',
                'FacturaCuentaValore.*',
            ),
            'conditions' => array(
                'FacturaCuentaValore.factura_id' => $facturaId
            ), 
            'recursive' => '-1'));
        return $data;
    }

}
