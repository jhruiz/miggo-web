<?php
App::uses('AppController', 'Controller');

class DepartamentosController extends AppController {

	public function obtenerdepartamentos() {
        $this->autoRender = false;
        $posData = $this->request->data;
        $dptos = $this->Departamento->obtenerDptosPais($posData['pais']);
		echo json_encode(array('resp' => $dptos));
	}

}
