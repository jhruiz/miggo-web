<?php
App::uses('AppController', 'Controller');
/**
 * Paises Controller
 *
 * @property Paise $Paise
 * @property PaginatorComponent $Paginator
 */
class ItemsgastosController extends AppController {

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
            
            $this->Itemsgasto->recursive = 0;
            $this->set('itemsgastos', $this->Paginator->paginate('Itemsgasto', $paginate));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
                    
                    $this->request->data['Itemsgasto']['empresa_id'] = $this->Auth->user('empresa_id');                    
                    $this->Itemsgasto->create();
                    if ($this->Itemsgasto->save($this->request->data)) {
                            $this->Session->setFlash(__('El item del gasto ha sido guardado.'));
                            return $this->redirect(array('action' => 'index'));
                    } else {
                            $this->Session->setFlash(__('El item del gasto no pudo ser guardado. Por favor, inténtelo de nuevo.'));
                    }
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Itemsgasto->exists($id)) {
			throw new NotFoundException(__('El item no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Itemsgasto->save($this->request->data)) {
				$this->Session->setFlash(__('El item del gasto ha sido guardado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El item del gasto no pudo ser guardado. Por favor, inténtelo de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Itemsgasto.' . $this->Itemsgasto->primaryKey => $id));
			$this->request->data = $this->Itemsgasto->find('first', $options);
		}
	}
}
