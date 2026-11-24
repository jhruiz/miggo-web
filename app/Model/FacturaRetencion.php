<?php
App::uses('AppModel', 'Model');

/**
 * FacturaRetencion Model
 *
 * Snapshot de las retenciones calculadas en una factura de venta. Guarda la
 * tarifa, la base y el valor de UVT usados para que un cambio posterior de
 * tarifas no altere facturas ya emitidas. Nunca se recalcula.
 *
 * aplica_a guarda el grupo sobre el que se calculó (PRODUCTO, SERVICIO o TODOS).
 */
class FacturaRetencion extends AppModel {

    public $useTable = 'factura_retenciones';

    public $belongsTo = array(
        'Factura' => array(
            'className'  => 'Factura',
            'foreignKey' => 'factura_id',
        ),
    );

    /**
     * ¿La factura ya tiene snapshot de retenciones?
     *
     * @param  int $facturaId
     * @return bool
     */
    public function facturaTieneSnapshot($facturaId) {
        return $this->find('count', array(
            'conditions' => array('FacturaRetencion.factura_id' => (int) $facturaId),
            'recursive'  => -1,
        )) > 0;
    }

    /** Nombre que se muestra al cliente para cada tributo (orden de presentación) */
    public static $etiquetas = array(
        '06' => 'ReteFuente',
        '05' => 'ReteIVA',
        '07' => 'ReteICA',
    );

    /**
     * Base y valor calculados por tributo (suma de los grupos del snapshot).
     *
     * @param  int $facturaId
     * @return array [codigo => ['base' => float, 'valor' => float]]
     */
    public function totalesPorTributo($facturaId) {
        $filas = $this->find('all', array(
            'conditions' => array('FacturaRetencion.factura_id' => (int) $facturaId),
            'fields'     => array('FacturaRetencion.tributo_codigo', 'SUM(FacturaRetencion.base) AS base', 'SUM(FacturaRetencion.valor) AS valor'),
            'group'      => array('FacturaRetencion.tributo_codigo'),
            'recursive'  => -1,
        ));
        $totales = array();
        foreach ($filas as $f) {
            $totales[$f['FacturaRetencion']['tributo_codigo']] = array(
                'base'  => round((float) $f[0]['base'], 2),
                'valor' => round((float) $f[0]['valor'], 2),
            );
        }
        return $totales;
    }

    /**
     * Retenciones guardadas de una factura agrupadas por tipo, para la vista y la
     * impresión. Lee solo el snapshot: nunca recalcula.
     *
     * @param  int $facturaId
     * @return array ['lineas' => [['tributo', 'etiqueta', 'valor']], 'total' => float]
     */
    public function obtenerResumenPorTributo($facturaId) {
        $filas = $this->find('all', array(
            'conditions' => array('FacturaRetencion.factura_id' => (int) $facturaId),
            'fields'     => array('FacturaRetencion.tributo_codigo', 'SUM(FacturaRetencion.valor) AS total'),
            'group'      => array('FacturaRetencion.tributo_codigo'),
            'recursive'  => -1,
        ));
        $porTributo = array();
        foreach ($filas as $f) {
            $porTributo[$f['FacturaRetencion']['tributo_codigo']] = round((float) $f[0]['total'], 2);
        }

        $lineas = array();
        $total = 0.0;
        foreach (self::$etiquetas as $codigo => $etiqueta) {
            if (!empty($porTributo[$codigo])) {
                $lineas[] = array('tributo' => $codigo, 'etiqueta' => $etiqueta, 'valor' => $porTributo[$codigo]);
                $total += $porTributo[$codigo];
            }
        }
        return array('lineas' => $lineas, 'total' => round($total, 2));
    }

    /**
     * Retenciones guardadas de una factura.
     *
     * @param  int $facturaId
     * @return array
     */
    public function obtenerPorFactura($facturaId) {
        return $this->find('all', array(
            'conditions' => array('FacturaRetencion.factura_id' => (int) $facturaId),
            'order'      => array('FacturaRetencion.tributo_codigo' => 'ASC', 'FacturaRetencion.id' => 'ASC'),
            'recursive'  => -1,
        ));
    }

    /**
     * Arma el arreglo `with_holding_tax_total` para el API de facturacion
     * electronica a partir del snapshot de la factura. Solo lectura.
     *
     * Agrupa por tributo + tarifa + tipo_valor. ReteICA se guarda por mil, la DIAN
     * pide porcentaje, por eso en POR_MIL percent = tarifa / 10.
     * Un tributo sin id en $mapaTaxIds se omite y se reporta en $omitidos.
     *
     * @param  int   $facturaId
     * @param  array $mapaTaxIds  [codigo DIAN => id en taxes del proveedor]
     * @param  array $omitidos    (salida) codigos sin id configurado
     * @return array lista de ['tax_id','percent','tax_amount','taxable_amount'], vacia si no hay
     */
    public function construirParaDian($facturaId, array $mapaTaxIds, &$omitidos = array()) {
        $omitidos = array();
        $grupos   = array();

        foreach ($this->obtenerPorFactura($facturaId) as $fila) {
            $f      = $fila['FacturaRetencion'];
            $codigo = $f['tributo_codigo'];

            if (empty($mapaTaxIds[$codigo]) || !is_numeric($mapaTaxIds[$codigo])) {
                $omitidos[$codigo] = $codigo;
                continue;
            }

            $tarifa  = (float) $f['tarifa'];
            $percent = ($f['tipo_valor'] === 'POR_MIL') ? $tarifa / 10 : $tarifa;
            $clave   = $codigo . '|' . number_format($percent, 4, '.', '');

            if (!isset($grupos[$clave])) {
                $grupos[$clave] = array(
                    'tax_id'         => (int) $mapaTaxIds[$codigo],
                    'percent'        => $percent,
                    'tax_amount'     => 0.0,
                    'taxable_amount' => 0.0,
                );
            }
            $grupos[$clave]['tax_amount']     += (float) $f['valor'];
            $grupos[$clave]['taxable_amount'] += (float) $f['base'];
        }

        $salida = array();
        foreach ($grupos as $g) {
            $salida[] = array(
                'tax_id'         => $g['tax_id'],
                'percent'        => number_format($g['percent'], 3, '.', ''),
                'tax_amount'     => number_format(round($g['tax_amount'], 2), 2, '.', ''),
                'taxable_amount' => number_format(round($g['taxable_amount'], 2), 2, '.', ''),
            );
        }
        return $salida;
    }
}
