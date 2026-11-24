<?php
App::uses('AppModel', 'Model');

/**
 * Tiposresponsabilidade Model
 *
 * Responsabilidades fiscales del RUT (O-13, O-15, O-23, O-47, R-99-PN...).
 * Se usan en el perfil tributario de la empresa y en el de los clientes.
 */
class Tiposresponsabilidade extends AppModel {

    public $displayField = 'descripcion';

    /**
     * Listado para los formularios: [id => 'O-13 · Gran contribuyente'].
     *
     * @return array
     */
    public function obtenerListadoParaFormulario() {
        $filas = $this->find('all', array(
            'fields'    => array('Tiposresponsabilidade.id', 'Tiposresponsabilidade.codigo', 'Tiposresponsabilidade.descripcion'),
            'order'     => array('Tiposresponsabilidade.codigo' => 'ASC'),
            'recursive' => -1,
        ));
        $lista = array();
        foreach ($filas as $f) {
            $t = $f['Tiposresponsabilidade'];
            $lista[$t['id']] = $t['codigo'] . ' · ' . $t['descripcion'];
        }
        return $lista;
    }
}
