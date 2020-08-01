<?php
App::uses('AppModel', 'Model');
/**
 * Cloudmenu Model
 *
 * @property Perfile $Perfile
 */
class Menuprincipale extends AppModel {

	public $displayField = 'descripcion';

        public function obtenerListadoMenuPrincipal(){
            $menus = $this->find('list', array('order' => array('menuprincipale.descripcion ASC')));
            return $menus;
        }
        
        public function obtenerMenuPorPerfil($perfil_id){
            
            $arr_join = array(); 
            array_push($arr_join, array(
                'table' => 'cloudmenus', 
                'alias' => 'C', 
                'type' => 'INNER',
                'conditions' => array(
                    'C.menu_principal=Menuprincipale.id')
                
            ));  
            
            array_push($arr_join, array(
                'table' => 'cloudmenus_perfiles', 
                'alias' => 'CMP', 
                'type' => 'INNER',
                'conditions' => array(
                    'C.id=CMP.cloudmenu_id')              
            ));  
            
            $arrMenuPerfil = $this->find('all', array(
                'joins' => $arr_join,
                'conditions' => array(
                    'CMP.perfile_id' => $perfil_id), 
                'order' => 'C.orden', 
                'fields'=>array(
                    'C.*',
                    'CMP.*',
                    'Menuprincipale.*'
                ),
                'recursive' => 0)
                    );     
            return $arrMenuPerfil;            
        }

}