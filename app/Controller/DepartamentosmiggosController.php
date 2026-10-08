<?php
App::uses('AppController', 'Controller');

class DepartamentosmiggosController extends AppController {

	public function obtenerdepartamentos() {
        $this->autoRender = false;
        $posData = $this->request->data;
        $dptos = $this->Departamentosmiggo->obtenerDptosPais($posData['pais']);
		echo json_encode(array('resp' => $dptos));
	}

}
