<?php
App::uses('AppController', 'Controller');

class CiudadesmiggosController extends AppController
{

    public function obtenerciudades() {
        $this->autoRender = false;
        $posData = $this->request->data;
        $ciudades = $this->Ciudadesmiggo->obtenerCiudadesDpto($posData['dpto']);
		echo json_encode(array('resp' => $ciudades));
	}


}
