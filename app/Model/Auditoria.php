<?php
App::uses('AppModel', 'Model');
/**
 * Auditoria Model
 *
 * @property Usuario $Usuario
 */
class Auditoria extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'usuario_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        
        /**
        * logAuditoria method
        *
        * Registra un registro log en la tabla de Auditoria 
        *         
        * @param integer $usuario
        * @param integer $descripcion   Descripcion del log a registrar.
        * @param integer $accion     Accion del log a registrar.
        * @return boolean
        */
        public static function logAuditoria($usuario, $descripcion, $accion){
            
            $data=array();                        
                
            $auditoria=new Auditoria();                        
            
            $data['usuario_id']=$usuario;
            $data['descripcion']=$descripcion;
            $data['accion']=$accion;
            
            if($auditoria->save($data)){
                return true;
            }else{
                return false;
            }
        }
        
        public function afterFind($results, $primary = false) {
            foreach ($results as $key => $val) {
                if (isset($val['Auditoria']['created'])) {
                    $results[$key]['Auditoria']['created'] = $this->dateFormatAfterFind($val['Auditoria']['created']);
                }
            }
            return $results;
        }

        public function dateFormatAfterFind($dateString) {                        
            
            return date('d/m/Y', strtotime($dateString));
        }     
        
        public function accionAuditoria($id){
            $accionAud = "";
            if($id == '0'){
                $accionAud = "Cargue Imagen";
            }             
            
            if($id == '1'){
                $accionAud = "Cargue de Inventario";
            }
            
            if($id == '2'){
                $accionAud = "Descargue de Inventario";
            }
            
            if($id == '3'){
                $accionAud = "Traslado de Inventario";
            }
            
            return $accionAud;
        }
        
        public function descripcionAuditoria($id, $arrDescripcionAud){
            $descripcionAud = "";
            
            if($id == '0'){
                $descripcionAud = "Se carga la imagen " . $arrDescripcionAud['nombreImg'] . ".";
            }
            
            if($id == '1'){
                $descripcionAud = "Se crea el documento " . $arrDescripcionAud['codigoDoc'] . " por cargue de inventario.";
            }
            
            if($id == '2'){
                $descripcionAud = "Se crea el documento " . $arrDescripcionAud['codigoDoc'] . " por descargue de inventario.";
            }            
            
            if($id == '3'){
                $descripcionAud = "Se crea el documento " . $arrDescripcionAud['codigoDoc'] . " por traslado de inventario.";
            }
            
            return $descripcionAud;
        }        
}
