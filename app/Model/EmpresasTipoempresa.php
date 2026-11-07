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

    /**
     * Indica si una empresa tiene asignado un tipo de empresa según su código.
     *
     * @param  int    $empresaId
     * @param  string $codigo     Código del tipo de empresa (ej: 'MF' = Multifactura)
     * @return bool
     */
    public function empresaTieneTipo($empresaId, $codigo) {
        $arr_join = array(
            array(
                'table'      => 'tipoempresas',
                'alias'      => 'TE',
                'type'       => 'INNER',
                'conditions' => array('EmpresasTipoempresa.tipoempresa_id = TE.id'),
            ),
        );

        $registro = $this->find('first', array(
            'joins'      => $arr_join,
            'fields'     => array('TE.id'),
            'conditions' => array(
                'EmpresasTipoempresa.empresa_id' => $empresaId,
                'TE.codigo'                      => $codigo,
            ),
            'recursive' => '-1',
        ));

        return !empty($registro);
    }

}