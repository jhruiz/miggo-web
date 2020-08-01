<?php
App::uses('AppController', 'Controller');

class OrdenestadosController extends AppController {
    
    /**
     * Se valida si el estado seleccionado es un estado final
     */
    public function ajaxValidarEstadoFin(){
        $this->loadModel('Ordenestado');
        $this->loadModel('Ordentrabajo');
        
        $this->autoRender = false;        
        
        $posData = $this->request->data;
        $estadoId = $posData['estadoId'];
        $ordenTId = $posData['ordenTId'];
        
        //se obtiene la informacion del estado por id
        $arrEstado = $this->Ordenestado->obtenerEstadoPorId($estadoId);
        
        $arrOrdenInfo= array();
        $ordenEstadoAct = "1";
        if(!empty($ordenTId)){
            $arrOrdenInfo = $this->Ordentrabajo->obtenerOrdenPorId($ordenTId);
            $ordenEstadoAct = $arrOrdenInfo['Ordentrabajo']['ordenestado_id'];
        }
                
        echo json_encode(array('resp' => $arrEstado['Ordenestado']['ordenfinal'], 'estadoAct' => $ordenEstadoAct));
    }
}

