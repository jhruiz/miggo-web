<?php
App::uses('AppModel', 'Model');
/**
 * Configuraciondato Model
 *
 */
class Configuraciondato extends AppModel {    

    ///Funcion para obtener la informacion de un dato de configuracion por medio de su id
    public function obtenerValorDatoConfig($strDato) {
        $valorDato = "";
        if (!empty($strDato)) {
            $infoDatoConfig = $this->find('all', array('conditions' => array('nombre' => $strDato), 'recursive' => 0));
            $valorDato = $infoDatoConfig[0]['Configuraciondato']['valor'];
        }
        return $valorDato;
    }

    /**
     * Valor de la UVT de un año (clave uvt_AAAA).
     * A diferencia de obtenerValorDatoConfig(), no falla si la clave no existe:
     * devuelve null, y el motor de retenciones debe avisar
     * "Falta configurar el valor de la UVT para el año AAAA".
     *
     * @param  int $anio
     * @return float|null
     */
    public function obtenerUvtAnio($anio) {
        $valor = $this->field('valor', array('Configuraciondato.nombre' => 'uvt_' . (int) $anio));
        if ($valor === false || $valor === null || !is_numeric($valor) || (float) $valor <= 0) {
            return null;
        }
        return (float) $valor;
    }
}
