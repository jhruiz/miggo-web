<?php
App::uses('AppModel', 'Model');

class Taxe extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


    /**
     * Tributos disponibles para crear impuestos.
     * Excluye los tributos de retención (05 ReteIVA, 06 ReteRenta, 07 ReteICA),
     * que solo se usan en el formulario de retenciones.
     */
    public function obtenerListadoImp() {
        return $this->find('list', array(
            'conditions' => array('NOT' => array('Taxe.code' => array('05', '06', '07'))),
            'recursive'  => '-1',
        ));
    }

    /**
     * Tributos de retención (id => nombre): 05 ReteIVA, 06 ReteRenta, 07 ReteICA.
     * Se usa en el formulario de retenciones.
     */
    public function obtenerTributosRetencion() {
        return $this->find('list', array(
            'conditions' => array('Taxe.code' => array('05', '06', '07')),
            'order'      => 'Taxe.code ASC',
            'recursive'  => '-1',
        ));
    }

    /**
     * Devuelve el código DIAN de un tributo (ej: '05') o null si no existe.
     */
    public function obtenerCodigoPorId($taxId) {
        $code = $this->field('code', array('Taxe.id' => (int) $taxId));
        return $code !== false ? $code : null;
    }

}