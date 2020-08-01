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
}
