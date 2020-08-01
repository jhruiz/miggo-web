<?php
App::uses('AppModel', 'Model');
/**
 * Categoria Model
 *
 * @property Empresa $Empresa
 * @property Usuario $Usuario
 * @property Producto $Producto
 */
class Cierrecaja extends AppModel {

        //guarda el cierre diario por caja
        public function guardarCierreCaja($data){
            $cuenta = new Cierrecaja();

            if($cuenta->save($data)){
                return '1';
            }else{
                return '0';
            }
        }
        
        public function obtenerCierreCajas($empresaId, $fecha, $cuenta = null){
            
            $filter = array();
            
            if(!empty($cuenta)){
                $filter['caja_id'] = $cuenta;
            }
            
            $filter['Cierrecaja.empresa_id'] = $empresaId;
            $filter['Cierrecaja.created BETWEEN ? AND ?'] = array($fecha . " 00:00:01", $fecha . " 23:59:59");
            
            $cierre = $this->find('all', array(
                'conditions' =>
                    $filter
                    ,
                'recursive' => '-1'
                ));
            return $cierre;            
        }

}
