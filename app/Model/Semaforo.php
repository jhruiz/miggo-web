<?php
App::uses('AppModel', 'Model');
/**
 * Semaforo Model
 *
 * @property Bandeja $Bandeja
 */
class Semaforo extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed
        
        public function obtenerSemaforos(){
            
            $semaforos = $this->find('all', array('recursive' => -1));
            if(count($semaforos) > 0 ){
                for($i=0; $i<count($semaforos); $i++){
                    $arrSemaforos[$i]['value'] =$semaforos[$i]['Semaforo']['id'];
                    $arrSemaforos[$i]['data'] = $semaforos[$i]['Semaforo']['rangoinicial'] . "-" . $semaforos[$i]['Semaforo']['rangofinal'];
                    $arrSemaforos[$i]['color'] = $semaforos[$i]['Semaforo']['color'];
                }                
                return $semaforos;                
            }                

        }
		
        public function obtenerValoresSemaforos(){
            $arrSemaforos = $this->find('all', array('recursive' => '-1'));
            return $arrSemaforos;
        }		

}
