<?php
App::uses('AppController', 'Controller');
/**
 * Paises Controller
 *
 * @property Paise $Paise
 * @property PaginatorComponent $Paginator
 */
class CategoriacomprasController extends AppController {

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
            if(isset($this->passedArgs['Categoriacompra']['buscarcategoria']) && $this->passedArgs['Categoriacompra']['buscarcategoria'] != ""){
                $paginate['LOWER(Categoriacompra.descripcion) LIKE'] = '%' . strtolower($this->passedArgs['Categoriacompra']['buscarcategoria']) . '%';
            }
            
            if(isset($this->passedArgs['Categoriacompra']['empresa_id']) && $this->passedArgs['Categoriacompra']['empresa_id'] != ""){
                $paginate['Categoriacompra.empresa_id'] = $this->passedArgs['Categoriacompra']['empresa_id'];
            }
            
            $empresaId = $this->Auth->user('empresa_id');
            
            $this->Categoriacompra->recursive = 0;
            $this->set('categoriacompras', $this->Paginator->paginate('Categoriacompra', $paginate));
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
		if (!$this->Categoriacompra->exists($id)) {
			throw new NotFoundException(__('La categoría para compras no existe.'));
		}
		$options = array('conditions' => array('Categoriacompra.' . $this->Categoriacompra->primaryKey => $id));
                $this->set('categoriacompra', $this->Categoriacompra->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
                    $this->Categoriacompra->create();
                    if ($this->Categoriacompra->save($this->request->data)) {
                            $this->Session->setFlash(__('La categoría ha sido creada.'));
                            return $this->redirect(array('action' => 'index'));
                    } else {
                            $this->Session->setFlash(__('La categoría no pudo ser guardada. Por favor, inténtelo de nuevo.'));
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
		if (!$this->Categoriacompra->exists($id)) {
			throw new NotFoundException(__('La categoría de compra no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Categoriacompra->save($this->request->data)) {
				$this->Session->setFlash(__('La categoría de compra ha sido guardada.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('La categoría de compra no pudo ser guardada. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Categoriacompra.' . $this->Categoriacompra->primaryKey => $id));
			$this->request->data = $this->Categoriacompra->find('first', $options);
		}
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
