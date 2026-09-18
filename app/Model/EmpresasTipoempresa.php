<?php
App::uses('AppModel', 'Model');

class EmpresasTipoempresa extends AppModel {

    public function obtenerTipoEmpresa( $empresaId ) {
        $arr_join = array(); 
            
        array_push($arr_join, array(
            'table' => 'tipoempresas', 
            'alias' => 'TE', 
            'type' => 'INNER',
            'conditions' => array(
                'EmpresasTipoempresa.tipoempresa_id=TE.id'
                )                
        ));              
                   
        $tipoEmpresa = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'TE.*'
            ),
            'conditions' => array(
                'EmpresasTipoempresa.empresa_id' => $empresaId                    
                ), 
            'recursive' => '-1'));
        return $tipoEmpresa; 
    }

}