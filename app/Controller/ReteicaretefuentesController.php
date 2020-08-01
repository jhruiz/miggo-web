<?php
App::uses('AppController', 'Controller');
/**
 * Paises Controller
 *
 * @property Paise $Paise
 * @property PaginatorComponent $Paginator
 */
class ReteicaretefuentesController extends AppController {

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
	public function index() {
            $paginate = array();
            if(isset($this->passedArgs['Reteicaretefuente']['reteicaretefuente']) && $this->passedArgs['Reteicaretefuente']['reteicaretefuente'] != ""){
                $paginate['LOWER(Reteicaretefuente.descripcion) LIKE'] = '%' . strtolower($this->passedArgs['Reteicaretefuente']['reteicaretefuente']) . '%';
            }
            
            if(isset($this->passedArgs['Reteicaretefuente']['empresa_id']) && $this->passedArgs['Reteicaretefuente']['empresa_id'] != ""){
                $paginate['Reteicaretefuente.empresa_id'] = $this->passedArgs['Reteicaretefuente']['empresa_id'];
            }
            
            $empresaId = $this->Auth->user('empresa_id');
            
            $this->Reteicaretefuente->recursive = 0;
            $this->set('reteicaretefuentes', $this->Paginator->paginate('Reteicaretefuente', $paginate));
            $this->set(compact('empresaId'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Reteicaretefuente->exists($id)) {
			throw new NotFoundException(__('El registro no existe.'));
		}
		$options = array('conditions' => array('Reteicaretefuente.' . $this->Reteicaretefuente->primaryKey => $id));
                $this->set('reteicaretefuentes', $this->Reteicaretefuente->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
                    $this->Reteicaretefuente->create();
                    
                    //se valida el tipo de impuesto que selecciono el usuario
                    if(!empty($this->request->data['Reteicaretefuente']['retefuente'])){
                        $this->request->data['Reteicaretefuente']['retefuente'] = '1';
                    }else if($this->request->data['Reteicaretefuente']['reteica']){
                        $this->request->data['Reteicaretefuente']['reteica'] = '1';
                    }                    
                    
                    if ($this->Reteicaretefuente->save($this->request->data)) {
                            $this->Session->setFlash(__('El registro ha sido creado.'));
                            return $this->redirect(array('action' => 'index'));
                    } else {
                            $this->Session->setFlash(__('El registro no pudo ser guardado. Por favor, inténtelo de nuevo.'));
                    }                        

		}
            $empresaId = $this->Auth->user('empresa_id');
            $this->set(compact('empresaId'));                
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Reteicaretefuente->exists($id)) {
			throw new NotFoundException(__('El registro no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
                    
                        //se valida el tipo de impuesto que selecciono el usuario
                        if(!empty($this->request->data['Reteicaretefuente']['retefuente'])){
                            $this->request->data['Reteicaretefuente']['retefuente'] = '1';
                            $this->request->data['Reteicaretefuente']['reteica'] = '0';
                        }else if($this->request->data['Reteicaretefuente']['reteica']){
                            $this->request->data['Reteicaretefuente']['retefuente'] = '0';
                            $this->request->data['Reteicaretefuente']['reteica'] = '1';
                        } 
                    
			if ($this->Reteicaretefuente->save($this->request->data)) {
				$this->Session->setFlash(__('El registro ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El registro no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Reteicaretefuente.' . $this->Reteicaretefuente->primaryKey => $id));
			$this->request->data = $this->Reteicaretefuente->find('first', $options);

                        $reteIcaChk = $this->request->data['Reteicaretefuente']['reteica'] == '1' ? "checked" : "";
                        $reteFteChk = $this->request->data['Reteicaretefuente']['retefuente'] == '1' ? "checked" : "";
                        
		}
            $empresaId = $this->Auth->user('empresa_id');
            $this->set(compact('empresaId', 'reteIcaChk', 'reteFteChk'));                 
	}
        
        public function search() {
            $url = array();
            $url['action'] = 'index';

            foreach ($this->data as $kk => $vv) {
                $url[$kk] = $vv;
            }

            // redirect the user to the url
            $this->redirect($url, null, true);
        }
}
