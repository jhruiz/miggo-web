<?php
App::uses('AppModel', 'Model');

class Resolucionfactura extends AppModel {

	public $displayField = 'prefijo';

	public $belongsTo = array(
		'Empresa' => array(
			'className' => 'Empresa',
			'foreignKey' => 'empresa_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
     
    /**
     * Se obtiene la resolucion configurada
     */
    public function obtenerResolucion( $empresaId, $tipoDocumento ){
        $result = $this->find('first', 
                                array('conditions' => 
                                    array(
                                        'Resolucionfactura.empresa_id' => $empresaId, 
                                        'Resolucionfactura.tipodocumentoventa_id' => $tipoDocumento,
                                        'Resolucionfactura.estado_id' => '1'
                                     ), 'recursive' => '-1'));
        return $result;
    }

    /**
     * Actualiza el consecutivo de la resolución
     */
    public function actualizarResolucion( $empresaId, $tipoDocumento ) {

        $info = $this->find('first', 
                                array('conditions' => 
                                    array(
                                        'Resolucionfactura.empresa_id' => $empresaId,
                                        'Resolucionfactura.tipodocumentoventa_id' => $tipoDocumento,
                                        'Resolucionfactura.estado_id' => '1'
                                    ), 'recursive' => '-1'));

        $data = array();
            
        $resolucion = new Resolucionfactura();
        
        $data['id'] = $info['Resolucionfactura']['id'];
        $data['consecutivoactual'] = $info['Resolucionfactura']['consecutivoactual'] + 1;
        
        if($resolucion->save($data)){
            return true;
        }else{
            return false;
        }

    }

    /**
     * Se valida si la resolución de la empresa está proximo 
     * a vencerse ya sea por fecha o por numeración
     */
    public function obtenerDetalleResolucion( $empresaId ) {
        $resp = ['porFecha' => '', 'porDias' => ''];

        $data = $this->find('first', array(
            'conditions' => array(
                'Resolucionfactura.empresa_id' => $empresaId,
                'Resolucionfactura.tipodocumentoventa_id' => '1',
                'Resolucionfactura.estado_id' => '1'
            ), 
            'recursive' => '-1'
            )
        );

        /**Validar vencimiento por fecha */
        $fechaFinObj = new DateTime($data['Resolucionfactura']['fechafin']);
        $fechaActualObj = new DateTime();
        
        // Calcular la diferencia entre las fechas
        $diffFechas = ($fechaFinObj->diff($fechaActualObj))->days;
        if( $diffFechas < 5 ) {
            $resp['porFecha'] = "Restan $diffFechas días para el vencimiento de tu resolución, es hora de renovarla";
        }

        /**Validar vecimiento por numeración */
        $diffConsecutivos = $data['Resolucionfactura']['resolucionfin'] - ($data['Resolucionfactura']['consecutivoactual'] - 1);
        if( $diffConsecutivos < 20 ) {
            $resp['porDias'] = "Tienes $diffConsecutivos consecutivos disponibles de tu resolución, es hora de renovar tu resolución";
        }

        return $resp;
    }
}
