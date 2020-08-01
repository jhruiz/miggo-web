<?php
App::uses('AppController', 'Controller');
/**
 * CloudmenusPerfiles Controller
 *
 * @property CloudmenusPerfile $CloudmenusPerfile
 * @property PaginatorComponent $Paginator
 */
class CloudmenusPerfilesController extends AppController {

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
            $this->loadModel('Cloudmenu');
            $this->loadModel('Perfile');
            
            $paginate = array();
            
            if(isset($this->passedArgs['menu']) && $this->passedArgs['menu'] != ""){
                $paginate['CloudmenusPerfile.cloudmenu_id'] = $this->passedArgs['menu'];
            }
            
            if(isset($this->passedArgs['perfil']) && $this->passedArgs['perfil'] != ""){
                $paginate['CloudmenusPerfile.perfile_id'] = $this->passedArgs['perfil'];
            }   
            
            $this->CloudmenusPerfile->recursive = 0;
            $this->set('cloudmenusPerfiles', $this->Paginator->paginate('CloudmenusPerfile',$paginate));
            
            //Se obtiene el listado de menus
            $menus = $this->Cloudmenu->obtenerListadoMenu();
            $perfiles = $this->Perfile->obtenerListaPerfiles();
            $this->set(compact('menus', 'perfiles'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->CloudmenusPerfile->exists($id)) {
			throw new NotFoundException(__('El menú - perfil no existe.'));
		}
		$options = array('conditions' => array('CloudmenusPerfile.' . $this->CloudmenusPerfile->primaryKey => $id));
		$this->set('cloudmenusPerfile', $this->CloudmenusPerfile->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->CloudmenusPerfile->create();
			if ($this->CloudmenusPerfile->save($this->request->data)) {
				$this->Session->setFlash(__('El menú - peril ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El menú - perfil no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		}
		$cloudmenus = $this->CloudmenusPerfile->Cloudmenu->find('list');
		$perfiles = $this->CloudmenusPerfile->Perfile->find('list');
		$this->set(compact('cloudmenus', 'perfiles'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->CloudmenusPerfile->exists($id)) {
			throw new NotFoundException(__('El menú - perfil no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->CloudmenusPerfile->save($this->request->data)) {
				$this->Session->setFlash(__('El menú - perfil ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El menú - perfil no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('CloudmenusPerfile.' . $this->CloudmenusPerfile->primaryKey => $id));
			$this->request->data = $this->CloudmenusPerfile->find('first', $options);
		}
		$cloudmenus = $this->CloudmenusPerfile->Cloudmenu->find('list');
		$perfiles = $this->CloudmenusPerfile->Perfile->find('list');
		$this->set(compact('cloudmenus', 'perfiles'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->CloudmenusPerfile->id = $id;
		if (!$this->CloudmenusPerfile->exists()) {
			throw new NotFoundException(__('El menú - perfil no existe.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->CloudmenusPerfile->delete()) {
			$this->Session->setFlash(__('El menú - perfil ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El menú - perfil no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
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
