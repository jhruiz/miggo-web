<?php

App::uses('AppController', 'Controller');
App::uses ('ProductosController', 'Controller');
/**
 * Usuarios Controller
 *
 * @property Usuario $Usuario
 * @property PaginatorComponent $Paginator
 */
class UsuariosController extends AppController {

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
        /*se registra la actividad del usuario en la aplicacion*/
        $usuarioId = $this->Auth->user('id'); 
        $this->registraractividad($usuarioId);
            
        $paginate = array();
        if (isset($this->passedArgs['nombre']) && $this->passedArgs['nombre'] != "") {
            $paginate['LOWER(Usuario.nombre) LIKE'] = '%' . strtolower($this->passedArgs['nombre']) . "%";
        }

        if (isset($this->passedArgs['identificacion']) && ($this->passedArgs['identificacion'] != "")) {
            $paginate['Usuario.identificacion LIKE'] = '%' . $this->passedArgs['identificacion'] . "%";
        }        
        
        
        /*si el usuario es el admisnitrador, se muestran todos los usaurios del sistema*/
        /*caso contrario, se discriminan por empresa*/
        $perfilId = $this->Auth->user('perfile_id');
        if($perfilId != '1'){
            $empresaId = $this->Auth->user('empresa_id');
            $paginate['Usuario.empresa_id'] = $empresaId;            
        }


        $this->Usuario->recursive = 0;

        if (empty($paginate)) {
            $this->set('usuarios', $this->Paginator->paginate());
        } else {
            $this->set('usuarios', $this->Paginator->paginate('Usuario', $paginate));
        }
        
        $listPerfile = $this->Usuario->Perfile->find('list');
        
        $this->set(compact('listPerfile', 'listOficina'));
        
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        /*se registra la actividad del usuario en la aplicacion*/
        $usuarioId = $this->Auth->user('id'); 
        $this->registraractividad($usuarioId);
                
        $this->loadModel('Configuraciondato');

        if (!$this->Usuario->exists($id)) {
            throw new NotFoundException(__('Invalid usuario'));
        }
        
        $confDato = "urlImgUsuario";
        $urlImagen = $this->Configuraciondato->obtenerValorDatoConfig($confDato) . $id . "/";
        
        $options = array('conditions' => array('Usuario.' . $this->Usuario->primaryKey => $id));
        $this->set('usuario', $this->Usuario->find('first', $options));
        $this->set(compact('urlImagen'));        
    }   

    /**
     * add method
     *
     * @return void
     */
    public function add() {                
        
        /*se registra la actividad del usuario en la aplicacion*/
        $usuarioId = $this->Auth->user('id'); 
        $this->registraractividad($usuarioId);

        if ($this->request->is('post')) {
            $posData = $this->request->data;
            $productos = new ProductosController();
            
            if($posData['Usuario']['imagen']['name'] != ""){
                //Se obtiene la extension del archivo
                $arrExt = explode(".", $posData['Usuario']['imagen']['name']);   
                
                /*Se obtiene el id de la empresa a la que pertenece el usuario que realiza la gestion*/
                $usuarioId = $this->Auth->user('id');                   

                $confDato = "dirImgUsuario";
                $nombreImg = "ImUsr_" . $posData['Usuario']['identificacion'];
                $this->request->data['Usuario']['imagen'] = $nombreImg . "." . $arrExt['1'];	                    	                        
            }  else {
                unset($this->request->data['Usuario']['imagen']);
            }
            
            $this->Usuario->create();

            $datosUsuario = $this->request->data("Usuario");
            $datosUsuario['estadologin'] = $this->request->data["estadologin"];
            $datosUsuario['intentos'] = $this->request->data["intentos"];
            $datosUsuario['empresa_id'] = $this->request->data["empresa_id"];
            $datosUsuario['password'] = AuthComponent::password($datosUsuario['password']);            

            //Se guarda el usuario
            if ($this->Usuario->guardarUsuario($datosUsuario)) {
                
                //obtiene el usuario con el número de cedula                
                $usuarioNuevoId =$this->Usuario->getLastInsertID();
                if(isset($this->request->data['Usuario']['imagen'])){
                        if($productos->subirArchivo($posData['Usuario']['imagen'], $confDato, $nombreImg, $usuarioNuevoId, $usuarioId)){
                            $this->Session->setFlash(__('El usuario ha sido guardado.'));
                            return $this->redirect(array('action' => 'index'));
                        }                
                }else{
                    $this->Session->setFlash(__('El usuario ha sido guardado.'));
                    return $this->redirect(array('action' => 'index'));                
                }

            } else {
                $this->Session->setFlash(__('El usuario no pudo ser guardado. Por favor, inténtelo de nuevo.'));
                return $this->redirect(array('action' => 'index'));     
            }                            
        }
        
        
        
        /*si es el usuario administrador de la app se muestra el listado, sino se ingresa el dato oculto en el campo empresa*/
        /*Se obtiene el usuario en sesion*/
        $perfilId = $this->Auth->user('perfile_id');
        if($perfilId == '1'){
            $empresas = $this->Usuario->Empresa->find('list'); 
        }else{
            $empresas = $this->Auth->user('empresa_id');
        }
        $perfiles = $this->Usuario->Perfile->find('list', array('conditions' => array('Perfile.id <> 1')));
        $estados = $this->Usuario->Estado->find('list');
        $this->set(compact('perfiles', 'estados', 'empresas', 'perfilId'));
    }
    

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        /*se registra la actividad del usuario en la aplicacion*/
        $usuarioId = $this->Auth->user('id'); 
        $this->registraractividad($usuarioId);
            
        if (!$this->Usuario->exists($id)) {
            throw new NotFoundException(__('Invalid usuario'));
        }
        if ($this->request->is(array('post', 'put'))) {
            
            $posData = $this->request->data;
            $productos = new ProductosController();

            /*Se obtiene el id de la empresa a la que pertenece el usuario que realiza la gestion*/
            $usuarioId = $this->Auth->user('id');                   

            if($posData['Usuario']['imagen']['name'] != ""){
                //Se obtiene la extension del archivo
                $arrExt = split("\.", $posData['Usuario']['imagen']['name']);  

                $confDato = "dirImgUsuario";
                $nombreImg = "ImUsr_" . $posData['Usuario']['identificacion'];
                $this->request->data['Usuario']['imagen'] = $nombreImg . "." . $arrExt['1'];                            
            }  else {
                unset($this->request->data['Usuario']['imagen']);
            }
            
            if ($this->Usuario->save($this->request->data)) {
                if(isset($this->request->data['Usuario']['imagen'])){
                    if($productos->subirArchivo($posData['Usuario']['imagen'], $confDato, $nombreImg, $posData['Usuario']['id'], $usuarioId)){
                        $this->Session->setFlash(__('El usuario ha sido guardado.'));
                        return $this->redirect(array('action' => 'index'));
                    }                       
                }else{
                    $this->Session->setFlash(__('El usuario ha sido guardado.'));
                    return $this->redirect(array('action' => 'index'));                    
                }

            } else {
                $this->Session->setFlash(__('El usuario no pudo ser actualizado. Por favor, intentelo de nuevo.'));
            }
        } else {
            $options = array('conditions' => array('Usuario.' . $this->Usuario->primaryKey => $id));
            $this->request->data = $this->Usuario->find('first', $options);
        }

        /*si es el usuario administrador de la app se muestra el listado, sino se ingresa el dato oculto en el campo empresa*/
        /*Se obtiene el usuario en sesion*/
        $perfilId = $this->Auth->user('perfile_id');
        if($perfilId == '1'){
            $empresas = $this->Usuario->Empresa->find('list'); 
        }else{
            $empresas = $this->Auth->user('empresa_id');
        }
        $perfiles = $this->Usuario->Perfile->find('list', array('conditions' => array('Perfile.id <> 1')));
        $estados = $this->Usuario->Estado->find('list');
        $this->set(compact('perfiles', 'estados', 'empresas', 'perfilId'));        
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->Usuario->id = $id;
        if (!$this->Usuario->exists()) {
            throw new NotFoundException(__('Invalid usuario'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Usuario->delete()) {
            $this->Session->setFlash(__('The usuario has been deleted.'));
        } else {
            $this->Session->setFlash(__('The usuario could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function inactivar($id = null) {
        $this->loadModel('Auditoria');
        $this->Usuario->id = $id;
        if (!$this->Usuario->exists()) {
            throw new NotFoundException(__('Usuario Inválido'));
        }
        $this->request->onlyAllow('post', 'delete');
        $id = $this->request->params['pass'][0];
        $datosUsuario = $this->Usuario->obtenerUsuarioPorId($id);
        $estadReg = $datosUsuario['Usuario']['estadoregistro_id'];
        if ($estadReg == 1) {
            $newEst = 2;
        } else {
            $newEst = 1;
        }
        if ($this->Usuario->updateAll(array('estadoregistro_id' => $newEst, 'num_intentos' => 0), array('id' => $id))) {
            
            /*Se realiza el registro en auditoria para el usuario inactivado*/
            $accionAud = $this->Auditoria->accionAuditoria('5');
            $arrDescripcionAud['nombre'] = $datosUsuario['Usuario']['nombre'];
            $arrDescripcionAud['identificacion'] = $datosUsuario['Usuario']['identificacion'];
            $arrDescripcionAud['username'] = $datosUsuario['Usuario']['username'];
            $arrDescripcionAud['estado'] = "Inactiva";
            $descripcionAud = $this->Auditoria->descripcionAuditoria('5', $arrDescripcionAud);
            $this->Auditoria->logAuditoria($this->Auth->user('id'), $descripcionAud, $accionAud);              
            
            
            $this->Session->setFlash(__('Se ha cambiado el estado del Usuario'));
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(__('Se produjo un error, intentelo de nuevo'));
        }
    }

    public function activar($id = null) {
        $this->loadModel('Auditoria');
        $this->Usuario->id = $id;
        if (!$this->Usuario->exists()) {
            throw new NotFoundException(__('El usuario no existe'));
        }
        $this->request->onlyAllow('post', 'delete');
        $id = $this->request->params['pass'][0];
        $datosUsuario = $this->Usuario->obtenerUsuarioPorId($id);
        $estadReg = $datosUsuario['Usuario']['estadoregistro_id'];
        if ($estadReg == 2) {
            $newEst = 1;
        } else {
            $newEst = 2;
        }
        if ($this->Usuario->updateAll(array('estadoregistro_id' => $newEst, 'num_intentos' => 0), array('id' => $id))) {
            
            /*Se realiza el registro en auditoria para el usuario activado*/
            $accionAud = $this->Auditoria->accionAuditoria('6');
            $arrDescripcionAud['nombre'] = $datosUsuario['Usuario']['nombre'];
            $arrDescripcionAud['identificacion'] = $datosUsuario['Usuario']['identificacion'];
            $arrDescripcionAud['username'] = $datosUsuario['Usuario']['username'];
            $arrDescripcionAud['estado'] = "Activa";
            $descripcionAud = $this->Auditoria->descripcionAuditoria('5', $arrDescripcionAud);
            $this->Auditoria->logAuditoria($this->Auth->user('id'), $descripcionAud, $accionAud);
            $this->Session->setFlash(__('Se ha cambiado el estado del Usuario'));
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(__('Se produjo un error, intentelo de nuevo'));
        }
    }

    public function login() {
        $this->loadModel('Auditoria');
        $this->loadModel('LicenciasEmpresa');
        
        if ($this->request->is('post')) { 
            $username = $this->request->data['Usuario']['username'];
            $datosUser = $this->Usuario->obtenerUsuarioPorUsername($username);
            $tamArray = count($datosUser);
            if ($tamArray == 0) {
                $this->Session->setFlash(__('El usuario no existe'));
            } else {
                $numIntentos = $datosUser['Usuario']['intentos'];
                $estado = $datosUser['Usuario']['estado_id'];                
                $estadoLogin = $datosUser['Usuario']['estadologin'];
                $id = $datosUser['Usuario']['id'];
                $ultimoMovimiento = $datosUser['Usuario']['validaciongestion'];

                //Se valida si el usuario se encuentra en estado login
                if($estadoLogin == '1'){

                    $fechaActual = date('Y-m-d H:i:s');
                    $diffFecha = $this->obtenerDiffFechas($ultimoMovimiento,$fechaActual);
                    if($diffFecha > '20'){
                        $estadoLogin = '0';
                        $this->Usuario->updateAll(array('Usuario.estadologin' => 'false'), array('Usuario.id' => $id));
                    }else{
                        $estadoLogin = '1';
                    }                    
                }

                /*Se valida el estado de la licencia del usuario*/
                $arrEstadoLic = $this->validarEstadoLicenciaEmpresa($datosUser['Usuario']['empresa_id']);

                /*se obtienen la cantidad de licencias adquiridas por la empresa*/
                $cantidadLicencias = $arrEstadoLic['LicenciasEmpresa']['cantidad'];

                /*se obtienen la cantidad de usuarios de la empresa logueados*/
                $arrUsuarios = $this->Usuario->obtenerUsuariosLogin($datosUser['Usuario']['empresa_id']);

                if(count($arrUsuarios) < $cantidadLicencias){
                    if(isset($arrEstadoLic['LicenciasEmpresa']) && $arrEstadoLic['LicenciasEmpresa']['estado_id'] == '1'){
                        if ($estado == '1') {
                                if ($this->Auth->login()) {     
                                    $this->Usuario->updateAll(array('intentos' => 0, 'Usuario.estadologin' => true), array('Usuario.id' => $id));
                                    $this->redirect(array('action' => 'paginainicio')); // Se redirecciona al index de usuarios
                                } else {
                                    if ($numIntentos == '2') {
                                        $this->Usuario->updateAll(array('Usuario.estado_id' => 2), array('Usuario.id' => $id));

                                        /*Se realiza el registro en auditoria para el usuario inactivado*/
                                        $accionAud = $this->Auditoria->accionAuditoria('7');
                                        $arrDescripcionAud['nombre'] = $datosUser['Usuario']['nombre'];
                                        $arrDescripcionAud['identificacion'] = $datosUser['Usuario']['identificacion'];
                                        $arrDescripcionAud['username'] = $datosUser['Usuario']['username'];
                                        $arrDescripcionAud['estado'] = "Bloquea";
                                        $descripcionAud = $this->Auditoria->descripcionAuditoria('5', $arrDescripcionAud);
                                        $this->Auditoria->logAuditoria($datosUser['Usuario']['id'], $descripcionAud, $accionAud);   
                                        $this->Session->setFlash(__('Su usuario ha sido bloqueado por 3 intentos fallidos de autenticacion, se le notificara cuando quede activo'));
                                    } else {
                                        $this->Usuario->updateAll(
                                                array('Usuario.intentos' => $numIntentos + 1), array('Usuario.id' => $id)
                                        );
                                        $this->Session->setFlash(__('Contraseña y/o el nombre de usuario incorrectos. Por favor, intente de nuevo'));
                                    }
                                }                        
                        } else {
                            $this->Session->setFlash(__('Usuario bloqueado, no puede loguearse.Por favor contactar al administrador'));
                        }
                    }else{
                        $this->Session->setFlash(__('Su licencia ha expirado o no ha sido asignada, recuerde realizar el pago para la activación de su usuario.'));
                    }                    
                }else{
                    $this->Session->setFlash(__('Se ha alcanzado el límite máximo de licencias habilitadas para usuarios logueados.'));
                }
            }
        }
        if ($this->Session->check('Auth.User')) {
            $this->redirect(array('action' => 'index'));
        }
    }   

    public function beforeFilter() {
        parent::beforeFilter();
        Security::setHash('md5');
    }

    public function logout() {
        $id = $this->Auth->user('id');
        $this->Usuario->updateAll(array('Usuario.estadologin' => 'false'), array('Usuario.id' => $id));
        return $this->redirect($this->Auth->logout());
    }

    //Pagina inicial que se le muestra al usuario.
    public function paginainicio() {
        /*se registra la actividad del usuario en la aplicacion*/
        $usuarioId = $this->Auth->user('id'); 
        $this->registraractividad($usuarioId);
            
        $this->loadModel('Configuraciondato');
        
        /*Se obtiene la url del proyecto*/
        $datoConfP = "dirRutaPublica";
        $urlPublica = $this->Configuraciondato->obtenerValorDatoConfig($datoConfP);

        /*Se obtiene la url las imagenes del menu*/
        $datoConf = "urlImgMenu";
        $urlImagMenu = $this->Configuraciondato->obtenerValorDatoConfig($datoConf);
        
        $this->set(compact('urlPublica', 'urlImagMenu'));
        
    }

    public function isAuthorized($user) {
        return true;
    }
    
    public function usercambiocontrasenia() {

        $userSess = $this->Auth->user('id');

        if ($this->request->is(array('post', 'put'))) {

            //se obtiene la informacion del usuario con su id
            $dataUsuario = $this->Usuario->obtenerUsuarioPorId($userSess);
            
            $prePass = $dataUsuario['Usuario']['password'];
            $newPass = $this->request->data['Usuario']['password'];
            $newPassConf = $this->request->data['Usuario']['contraseniaConf'];
            $passCript = AuthComponent::password($this->request->data['Usuario']['passwordAnt']);
            
            if ($prePass == $passCript) {
                if ($newPass == $newPassConf) {
                    $newCript = AuthComponent::password($newPass);
                    $this->Usuario->updateAll(
                            array('Usuario.password' => "'$newCript'"), array('Usuario.id' => $userSess));
                    $this->Session->setFlash(__('Contraseña Actualizada.'));
                    return $this->redirect(array('action' => 'paginainicio'));
                } else {
                    $mensaje = "La Contraseña Nueva no coincide con la Confirmación.";
                    $this->set(compact('mensaje'));
                }
            } else {

                $mensaje = "La Contraseña Anterior no es correcta.";
                $this->set(compact('mensaje'));
            }
        }
        
        $this->set('usuario_id',$userSess);
        
    }
    
    public function validarcontrasenaantes(){
        
        $this->layout="ajax";
        $this->autoRender=false;
        $response=array();
        $response['estado']=false;
        $response['valido']=false;
        
        if($this->request->is("post")){
            
            $usuario=$this->request->data("usuario");
            $contrasena=$this->request->data("contrasenaAnt");
            
            if(!empty($usuario) && !empty($contrasena)){
                $response['estado']=true;
                $response['valido']=$this->Usuario->validarcontrasenaanterior($usuario,$contrasena);
            }
        }
        
        echo json_encode($response);
    }    
    
    public function admcontrasenia($usuarioId = null){
        $this->loadModel('Usuario');
        $datosUsuario = $this->Usuario->obtenerUsuarioPorId($usuarioId);
        $nombreUsuario = $datosUsuario['Usuario']['nombre'];          

        $this->set(compact('nombreUsuario', 'usuarioId'));   

        if ($this->request->is(array('post', 'put'))) {
            $id = $this->request->data['Usuario']['idUsuario'];
            $newPass = $this->request->data['Usuario']['password'];
            $newPassEnc = AuthComponent::password($newPass);
            $this->Usuario->updateAll(
                    array('Usuario.password' => "'$newPassEnc'"),
                    array('Usuario.id' => $id));
            //$this->send_mail($id); 
            $this->Session->setFlash(__('Password Actualizado.'));
            return $this->redirect(array('action' => 'listausuariosadmcontrasenia'));
        }		
    }
    
       public function searchAdmContrasenia() {
		// the page we will redirect to
		$url=array();
            
                $url['action'] = 'listausuariosadmcontrasenia';
                    
		foreach ($this->data as $k=>$v){
                    if($k!='Usuario'){
			foreach ($v as $kk=>$vv){ 
				$url[$k.'.'.$kk]=$vv; 
			} 
                   }
		}
		// redirect the user to the url
		$this->redirect($url, null, true);
	} 
        
        public function listausuariosadmcontrasenia(){

            $paginate=array();
            if(isset($this->passedArgs['Search.Nombre']) && !empty($this->passedArgs['Search.Nombre'])) {
                $paginate['Usuario.nombre LIKE'] = '%'.$this->passedArgs['Search.Nombre']."%";                        			
            }                
            if(isset($this->passedArgs['Search.Identificacion']) && !empty($this->passedArgs['Search.Identificacion'])) {
                $paginate['Usuario.identificacion LIKE'] = '%'.$this->passedArgs['Search.Identificacion']."%";                        			
            }                
            $this->Usuario->recursive = 0;		
                
            if(empty($paginate)){
                $this->set('usuarios', $this->Paginator->paginate());                           
            }else{
                $this->set('usuarios', $this->Paginator->paginate('Usuario',$paginate));                           
            }
	}     
        
        public function listausuarios(){
            $this->loadModel('OficinasUsuario');
            
            $usuarioId = $this->Auth->user('id');
            
            //se obtienen las oficinas a las cuales el usuario tiene permiso
            $arrOficinas = $this->OficinasUsuario->obtenerOficinasUsuario($usuarioId);
            
            for($i = 0; $i < count($arrOficinas); $i++){
                $arrOficinasId[$i] = $arrOficinas[$i]['OficinasUsuario']['oficina_id'];
            }

            //Se obtienen los usuarios que tienen permisos sobre las oficinas consultadas
            $arrUsuarios = $this->Usuario->obtenerUsuariosPorOficinaId($arrOficinasId);

            $this->set(compact('arrUsuarios'));
            
        }
        
        public function registraractividad($usuarioId){
            $fechaActual = date('Y-m-d H:i:s');
            
            //Se actualiza la fecha de la ultima gestion del usuario en el sistema
            $this->Usuario->actualizarActividadUsuario($usuarioId,$fechaActual);
        }
        
        public function obtenerDiffFechas($fechaFin,$fechaInicial){            
            $minutos = ceil((strtotime($fechaInicial) - strtotime($fechaFin)) / 60);            
            return $minutos;
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
        
        public function validarEstadoLicenciaEmpresa($empresaId){
            $this->loadModel('LicenciasEmpresa');
            $arrLicenciaEmpresa = $this->LicenciasEmpresa->obtenerLicenciaPorEmpresa($empresaId);            
            
            //Se valida si existe el registro de liciencia para la empresa
            if(count($arrLicenciaEmpresa) > 0){
                //Se obtiene la fecha actual para validar si la licencia para la empresa ha expirado
                $fechaActual = date('Y-m-d');
                if($fechaActual > $arrLicenciaEmpresa['LicenciasEmpresa']['fechafin']){
                    //Se actualiza el estado de la licencia
                    $estadoId = '2';
                    $arrLicenciaEmpresa['LicenciasEmpresa']['estado_id'] = $estadoId;
                    $this->LicenciasEmpresa->actualizarEstadoLicencia($arrLicenciaEmpresa['LicenciasEmpresa']['id'],$estadoId);
                } 
            }
            return $arrLicenciaEmpresa;            
        }
        
}
