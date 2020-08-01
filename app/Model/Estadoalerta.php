<?php
App::uses('AppModel', 'Model');

class Estadoalerta extends AppModel {

    public $displayField = 'descripcion';
    
    public function obtenerListaEstadoAlertas($empresa_id){
        $estadosAlerta = $this->find('list', array('conditions' => array('Estadoalerta.empresa_id' => $empresa_id)));
        return $estadosAlerta;
    }  

    /**
     * Se obtiene la alerta inicial para la empresa
     */
    public function obtenerEstadoAlertaInicial($empresa_id){
        $estadoAlerta = $this->find('first', array(
            'conditions' => array(
                'Estadoalerta.empresa_id' => $empresa_id,
                'Estadoalerta.inicial' => '1'
            )));

        return $estadoAlerta;
    }
}