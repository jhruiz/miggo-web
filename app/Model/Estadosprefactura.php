<?php
App::uses('AppModel', 'Model');
/**
 * Paise Model
 *
 * @property Ciudade $Ciudade
 */
class Estadosprefactura extends AppModel {
    
    public $displayField = 'descripcion';
        
    public function obtenerListaEstados(){

        $estados = $this->find('list');
        return $estados;
    }       

}
