<?php
App::uses('AppController', 'Controller');
App::uses ('ProductosController', 'Controller');

class PublicidadmovilesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function indexCabecera() {
	    $this->loadModel('Configuraciondato');
	    $empresaId = $this->Auth->user('empresa_id');
	    $usuarioId = $this->Auth->user('id');
	    $confDato = "dirPublicidad";
	    
		if ($this->request->is('post')) {
		    $productos = new ProductosController();
		    $posData = $this->request->data;
		    
            //Se obtiene la extension del archivo
            $arrExt = split("\.", $posData['Publicidadmovile']['imagen']['name']); 	
            
            $nameImg = date('Ymdhis');
		    $pubMovil['url_img'] = $nameImg . "." . $arrExt['1'];
		    $pubMovil['empresa_id'] = $empresaId;
		    $pubMovil['mostrar'] = 1;
		    $pubMovil['ubicacion'] = 1;
		    $pubMovil['usuario_id'] = $usuarioId;
		        
		    if($productos->subirArchivo($posData['Publicidadmovile']['imagen'], $confDato, $nameImg, $empresaId, $usuarioId)){
		        if($this->Publicidadmovile->guardarPublicidad($pubMovil)){
		            return $this->redirect(array('action' => 'indexCabecera')); 
		        }else{
		            $this->Session->setFlash(__('La imagen no pudo ser cargada. Por favor, inténtelo de nuevo.'));     
		        }
		    } else {
		        $this->Session->setFlash(__('La imagen no pudo ser cargada. Por favor, inténtelo de nuevo.')); 
		    }
		    
		}	
		
		$urlImg = '/' . $this->Configuraciondato->obtenerValorDatoConfig($confDato);

        //se obtiene el listado de imágenes
        $ubicacion = '1';
        $pubMovil = $this->Publicidadmovile->obtenerInfoImagenes($empresaId, $ubicacion);

        $this->set(compact('pubMovil', 'urlImg'));
            
	}
	
	public function indexFooter() {
	    $this->loadModel('Configuraciondato');
	    $empresaId = $this->Auth->user('empresa_id');
	    $usuarioId = $this->Auth->user('id');
	    $confDato = "dirPublicidad";
	    
		if ($this->request->is('post')) {
		    $productos = new ProductosController();
		    $posData = $this->request->data;
		    
            //Se obtiene la extension del archivo
            $arrExt = split("\.", $posData['Publicidadmovile']['imagen']['name']); 	
            
            $nameImg = date('Ymdhis');
		    $pubMovil['url_img'] = $nameImg . "." . $arrExt['1'];
		    $pubMovil['empresa_id'] = $empresaId;
		    $pubMovil['mostrar'] = 1;
		    $pubMovil['ubicacion'] = 0;
		    $pubMovil['usuario_id'] = $usuarioId;
		        
		    if($productos->subirArchivo($posData['Publicidadmovile']['imagen'], $confDato, $nameImg, $empresaId, $usuarioId)){
		        if($this->Publicidadmovile->guardarPublicidad($pubMovil)){
		            return $this->redirect(array('action' => 'indexFooter')); 
		        }else{
		            $this->Session->setFlash(__('La imagen no pudo ser cargada. Por favor, inténtelo de nuevo.'));     
		        }
		    } else {
		        $this->Session->setFlash(__('La imagen no pudo ser cargada. Por favor, inténtelo de nuevo.')); 
		    }
		    
		}	
		
		$urlImg = '/' . $this->Configuraciondato->obtenerValorDatoConfig($confDato);

        //se obtiene el listado de imágenes
        $ubicacion = 0;
        $pubMovil = $this->Publicidadmovile->obtenerInfoImagenes($empresaId, $ubicacion);

        $this->set(compact('pubMovil', 'urlImg'));
            
	}	


/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit() {
        $this->autoRender = false;
        
        $posData = $this->request->data;
        
        print_r($posData);
        
        $data['id'] = $posData['idImg'];
        
        if($posData['visible'] == 'true'){
            $data['mostrar'] = 1;    
        } else {
            $data['mostrar'] = 0;
        }
        
        print_r($data);
        if($this->Publicidadmovile->guardarPublicidad($data)){
            echo json_encode(array('resp' => true)); 
        }else{
            echo json_encode(array('resp' => false)); 
        }
	    
    }

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null, $action) {
	    
	    $this->Publicidadmovile->eliminar($id);
	    
	    if($action == '1'){
	        return $this->redirect(array('action' => 'indexCabecera'));	    
	    }else{
	        return $this->redirect(array('action' => 'indexFooter'));	
	    }
		
		

	}
}
