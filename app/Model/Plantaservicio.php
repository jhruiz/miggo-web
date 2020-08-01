<?php
App::uses('AppModel', 'Model');
/**
 * Paise Model
 *
 * @property Ciudade $Ciudade
 */
class Plantaservicio extends AppModel {

	public $displayField = 'descripcion';
        
        public function obtenerListaPlantasServicio(){
            $plantaservicio = $this->find('list');
            return $plantaservicio;
        }

}
