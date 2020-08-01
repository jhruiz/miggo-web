<?php
App::uses('AppController', 'Controller');
/**
 * Perfiles Controller
 *
 * @property Perfile $Perfile
 * @property PaginatorComponent $Paginator
 */
class PerfilesController extends AppController {

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
            
            if(isset($this->passedArgs['nombre']) && $this->passedArgs['nombre'] != ""){
                $paginate['LOWER(Perfile.descripcion) LIKE'] = '%' . strtolower($this->passedArgs['nombre']) . '%';
            }
            
            $empresaId = $this->Auth->user('empresa_id');
            $paginate['Perfile.empresa_id'] = $empresaId;
            $this->Perfile->recursive = 0;
            $this->set('perfiles', $this->Paginator->paginate('Perfile',$paginate));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Perfile->exists($id)) {
			throw new NotFoundException(__('El perfil no existe.'));
		}
		$options = array('conditions' => array('Perfile.' . $this->Perfile->primaryKey => $id));
		$this->set('perfile', $this->Perfile->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Perfile->create();
			if ($this->Perfile->save($this->request->data)) {
				$this->Session->setFlash(__('El perfil ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El perfil no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		}
		$arrEmpresas = $this->Auth->user('Empresa');
                $empresaId = $arrEmpresas['id'];
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
		if (!$this->Perfile->exists($id)) {
			throw new NotFoundException(__('El perfil no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Perfile->save($this->request->data)) {
				$this->Session->setFlash(__('El perfil ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El perfil no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Perfile.' . $this->Perfile->primaryKey => $id));
			$this->request->data = $this->Perfile->find('first', $options);
		}
		$arrEmpresas = $this->Auth->user('Empresa');
                $empresaId = $arrEmpresas['id'];
		$this->set(compact('empresaId'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Perfile->id = $id;
		if (!$this->Perfile->exists()) {
			throw new NotFoundException(__('El perfil no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Perfile->delete()) {
			$this->Session->setFlash(__('El perfil ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El perfil no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
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
