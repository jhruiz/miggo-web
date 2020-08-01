<?php
App::uses('AppModel', 'Model');

class Publicidadmovile extends AppModel {

        
        public function obtenerInfoImagenes($empresaId, $ubicacion){
            $filter = array();
            
            $filter['Publicidadmovile.empresa_id'] = $empresaId;
            $filter['Publicidadmovile.ubicacion'] = $ubicacion;
            
            $pubMovil = $this->find('all', array(
                'conditions' =>
                    $filter,
                'recursive' => '-1'
                ));
            
            return $pubMovil;    
            
        }
        
        public function guardarPublicidad($data){
                
            $pubMovil=new Publicidadmovile();                        
            
            if($pubMovil->save($data)){
                return true;
            }else{
                return false;
            }            
        }
        
        public function eliminar($id){
            $pubMovil=new Publicidadmovile();     
            
            $data['id'] = $id;
            
            if($pubMovil->delete($data)){
                return true;
            }else{
                return false;
            }            
        }

}