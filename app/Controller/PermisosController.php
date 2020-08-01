<?php
App::uses('AppController', 'Controller');
/**
 * Permisosfiles Controller
 *
 * @property Perfile $Perfile
 * @property PaginatorComponent $Paginator
 */
class PermisosController extends AppController {

	public function view($id = null) {
		if (!$this->Perfile->exists($id)) {
			throw new NotFoundException(__('El perfil no existe.'));
		}
		$options = array('conditions' => array('Perfile.' . $this->Perfile->primaryKey => $id));
		$this->set('perfile', $this->Perfile->find('first', $options));
    }
     
    
    
}