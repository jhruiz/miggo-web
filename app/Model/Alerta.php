<?php
App::uses('AppModel', 'Model');

class Alerta extends AppModel {

    public $displayField = 'descripcion';    

    public function obtenerListaAlertas($empresa_id){

        $listAlertas = $this->find('list', array(
            'conditions' => array(
                'Alerta.empresa_id' => $empresa_id,
                'enlista' => '1'
            )));
        return $listAlertas;                      
    }

    public function obtnerAlertaEmpresa($empresa_id, $desc){
        $alerta = $this->find('first', array(
            'conditions' => array(
                'Alerta.empresa_id' => $empresa_id, '
                Alerta.descripcion' => $desc)));
        return $alerta;
    }
}

