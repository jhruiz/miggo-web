<?php
App::uses('AppController', 'Controller');

class CalendariosController extends AppController {
/**
 * index method
 *
 * @return void
 */
	public function calendar() {
        
	}

	public function datoscalendario() {
		$this->loadModel('Alertaordene');
		$this->loadModel('Cuentascliente');
		$this->loadModel('Evento');
		$this->loadModel('Cuentaspendiente');

		$this->autoRender = false;
		$posData = $this->request->data;  
		$empresaId = $this->Auth->user('empresa_id');
		
		$eventsCalendar = [];

		//obtiene todas las aleras; tanto generales como de documentos
		$alertas = $this->Alertaordene->obtenerAlertasRangoFechas($posData['initDate'], $posData['endDate'], $empresaId);

		foreach($alertas as $key => $val) {

			$arrDate = explode(' ',$val['Alertaordene']['fecha_alerta']);

			if(empty($eventsCalendar[$arrDate['0']])){
				$eventsCalendar[$arrDate['0']]['fecha'] = $arrDate['0'];
				$eventsCalendar[$arrDate['0']]['cantidad'] = 1;
			} else {
				$eventsCalendar[$arrDate['0']]['cantidad'] = $eventsCalendar[$arrDate['0']]['cantidad'] + 1;
			}			
		}

		//obtiene todas las cuentas por cobrar
		$cuentasXCobrar = $this->Cuentascliente->obtenerCuentasParaCalendario($empresaId, $posData['initDate'], $posData['endDate']);

		foreach($cuentasXCobrar as $key => $val) {

			$arrDate = explode(' ',$val['Cuentascliente']['fechapago']);

			if(empty($eventsCalendar[$arrDate['0']])){
				$eventsCalendar[$arrDate['0']]['fecha'] = $arrDate['0'];
				$eventsCalendar[$arrDate['0']]['cantidad'] = 1;
			} else {
				$eventsCalendar[$arrDate['0']]['cantidad'] = $eventsCalendar[$arrDate['0']]['cantidad'] + 1;
			}			
		}

		//obtiene todas las cuentas por pagar
		$cuentasXPagar = $this->Cuentaspendiente->obtenerCuentasParaCalendario($empresaId, $posData['initDate'], $posData['endDate']);

		foreach($cuentasXPagar as $key => $val) {

			$arrDate = explode(' ',$val['Cuentaspendiente']['fechapago']);

			if(empty($eventsCalendar[$arrDate['0']])){
				$eventsCalendar[$arrDate['0']]['fecha'] = $arrDate['0'];
				$eventsCalendar[$arrDate['0']]['cantidad'] = 1;
			} else {
				$eventsCalendar[$arrDate['0']]['cantidad'] = $eventsCalendar[$arrDate['0']]['cantidad'] + 1;
			}			
		}

		//obtiene todos los eventos
		$eventos = $this->Evento->obtenerEventosParaCalendario($empresaId, $posData['initDate'], $posData['endDate']);
		
		foreach($eventos as $key => $val) {

			$arrDate = explode(' ',$val['Evento']['fecha']);

			if(empty($eventsCalendar[$arrDate['0']])){
				$eventsCalendar[$arrDate['0']]['fecha'] = $arrDate['0'];
				$eventsCalendar[$arrDate['0']]['cantidad'] = 1;
			} else {
				$eventsCalendar[$arrDate['0']]['cantidad'] = $eventsCalendar[$arrDate['0']]['cantidad'] + 1;
			}			
		}

		echo json_encode(array('events' => $eventsCalendar));  		
	}

	public function eventoscalendario(){
		$this->loadModel('Alertaordene');
		$this->loadModel('Cuentascliente');
		$this->loadModel('Evento');
		$this->loadModel('Cuentaspendiente');

		$posData = $this->request->data;  
		$empresaId = $this->Auth->user('empresa_id');
		$initDate = $posData['eventDate'] . ' 00:00:00';
		$endDate = $posData['eventDate'] . ' 23:59:59';

		//alertas por documentos
		$alertasD = $this->Alertaordene->obtenerAlertasEmpresaFecha($initDate, $endDate, $empresaId, '1');

		//alertas por mantenimiento 
		$alertasM = $this->Alertaordene->obtenerAlertasEmpresaFecha($initDate, $endDate, $empresaId, '2');

		//cuentas por pagar
		$cuentasXPagar = $this->Cuentaspendiente->obtenerCuentasParaCalendario($empresaId, $initDate, $endDate);
		
		//obtiene los eventos
		$eventos = $this->Evento->obtenerEventosParaCalendario($empresaId, $initDate, $endDate);

		//cuentas por cobrar
		$cuentasXCobrar = $this->Cuentascliente->obtenerCuentasParaCalendario($empresaId, $initDate, $endDate);
		$fechaAct = date('Y-m-d');

		$this->set(compact('alertasD', 'fechaAct', 'alertasM', 'cuentasXCobrar', 'eventos', 'cuentasXPagar')); 		
	}

}
