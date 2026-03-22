<?php
App::uses('AppController', 'Controller');
App::uses('UsuariosController', 'Controller');
/**
 * Productos Controller
 *
 * @property Producto $Producto
 * @property PaginatorComponent $Paginator
 */
class ProductosController extends AppController {

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
    public function index()
    {
        /*se reagistra la actividad del uso de la aplicacion*/
        $usuariosController = new UsuariosController();
        $usuarioAct = $this->Auth->user('id');
        $usuariosController->registraractividad($usuarioAct);

        $codigo = $this->passedArgs['codigo'];
        $referencia = $this->passedArgs['referencia'];
        $nombre = $this->passedArgs['nombre'];
        $categoria = $this->passedArgs['categorias'];

        $this->loadModel('Categoria');

        if (isset($this->passedArgs['codigo']) && $this->passedArgs['codigo'] != "") {
            $paginate['LOWER(Producto.codigo) LIKE'] = '%' . strtolower($this->passedArgs['codigo']) . '%';
        }

        if (isset($this->passedArgs['nombre']) && $this->passedArgs['nombre'] != "") {
            $paginate['LOWER(Producto.descripcion) LIKE'] = '%' . strtolower($this->passedArgs['nombre'] . '%');
        }

        if (isset($this->passedArgs['categorias']) && $this->passedArgs['categorias'] != "") {
            $paginate['Producto.categoria_id'] = $this->passedArgs['categorias'];
        }

        if (isset($this->passedArgs['referencia']) && $this->passedArgs['referencia'] != "") {
            $paginate['LOWER(Producto.referencia) LIKE'] = '%' . strtolower($this->passedArgs['referencia']) . '%';
        }

        $empresaId = $this->Auth->user('empresa_id');    
        $paginate['Producto.empresa_id'] = $empresaId;
        $paginate['Producto.estado'] = '1';
        $this->Producto->recursive = 0;

        
        //Se obtiene el listado de categorias de producos de la empresa
        $categorias = $this->Categoria->obtenerCategoriasEmpresa($empresaId);
        // $productos =
        $this->Paginator->paginate('Producto', $paginate);
        // var_dump($productos);
        $this->set('productos', $this->Paginator->paginate('Producto', $paginate));

        $this->set(compact('categorias', 'codigo', 'referencia', 'nombre', 'categoria'));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
	public function view($id = null) {
        if (!$this->Producto->exists($id)) {
            throw new NotFoundException(__('El producto no existe.'));
        }

        // Aumentamos recursividad o usamos containable para traer imágenes y palabras clave
        $producto = $this->Producto->find('first', array(
            'conditions' => array('Producto.id' => $id),
            'contain' => array('Categoria', 'Imagenesitem', 'Palabrasclave') 
        ));

        // Si no tienes configurado Containable, puedes hacerlo así:
        if(empty($producto['Imagenesitem'])){
            $this->loadModel('Imagenesitem');
            $producto['Imagenesitem'] = $this->Imagenesitem->find('all', array(
                'conditions' => array('producto_id' => $id)
            ));
        }
        if(empty($producto['Palabrasclave'])){
            $this->loadModel('Palabrasclave');
            $producto['Palabrasclave'] = $this->Palabrasclave->find('all', array(
                'conditions' => array('producto_id' => $id)
            ));
        }

        $empresaId = $this->Auth->user('empresa_id');

        $this->set(compact('producto', 'empresaId'));
  
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {            
            /*se reagistra la actividad del uso de la aplicacion*/
            $usuariosController = new UsuariosController();
            $usuarioAct = $this->Auth->user('id');
            $usuariosController->registraractividad($usuarioAct);
            	
		if ($this->request->is('post')) {
            $this->guardarProducto();
		}
        $empresaId = $this->Auth->user('empresa_id');     
		$categorias = $this->Producto->Categoria->obtenerCategoriasEmpresa($empresaId);                               
		$this->set(compact('categorias', 'empresaId'));
	}


    /**
     * Guarda la información del producto
     */
    public function guardarProducto() {
        $this->autoRender = false;
        $this->loadModel('Imagenesitem');
        $this->loadModel('Palabrasclave');

        $data = $this->request->data;
        $empresaId = $this->Auth->user('empresa_id'); // O la lógica que uses para la sesión

        // 1. Preparar datos del Producto
        $this->Producto->create();
        $productoData = $data['Producto'];
        $productoData['empresa_id'] = $empresaId;
        $productoData['created'] = date('Y-m-d H:i:s');
        
        // La tabla tiene un campo 'imagen', guardaremos la primera como principal
        if (!empty($data['Producto']['imagenes'][0]['name'])) {
            $productoData['imagen'] = "prod_" . time() . "_0.webp";
        }

        if ($this->Producto->save($productoData)) {
            $productoId = $this->Producto->id;

            // 2. PROCESAR PALABRAS CLAVE
            if (!empty($data['Producto']['palabras_clave'])) {

            // Borramos todas las existentes para este producto
            $this->Palabrasclave->deleteAll(array('Palabrasclave.producto_id' => $productoId), false);

                $tags = explode(',', $data['Producto']['palabras_clave']);
                foreach ($tags as $tag) {
                    $this->Palabrasclave->create();
                    $this->Palabrasclave->save([
                        'palabra' => trim($tag),
                        'producto_id' => $productoId,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            // 3. PROCESAR IMÁGENES (Conversión a WebP)
            if (!empty($data['Producto']['imagenes'])) {
                foreach ($data['Producto']['imagenes'] as $index => $imgData) {
                    // 1. Saltamos si hay error de subida
                    if ($imgData['error'] !== 0) continue;

                    // 2. VALIDACIÓN DE FORMATO (Solo permitimos JPG/JPEG)
                    $tipoMime = $imgData['type'];
                    if ($tipoMime !== 'image/jpeg' && $tipoMime !== 'image/jpg') {
                        $this->Session->setFlash("La imagen {$imgData['name']} fue omitida. Solo se permiten formatos JPG/JPEG.", 'default', array('class' => 'alert alert-warning'));
                        continue; // Salta a la siguiente imagen sin procesar el PNG
                    }

                    // 3. Si es JPG, procedemos con la conversión a WebP
                    $nombreArchivo = "prod_" . $productoId . "_" . time() . "_" . $index . ".webp";
                    $nombreCarpeta = WWW_ROOT . 'img' . DS . 'productos' . DS . $empresaId;
                    $rutaDestino = $nombreCarpeta . DS . $nombreArchivo;

                    if (!is_dir( $nombreCarpeta)) {
                        mkdir($nombreCarpeta, 0777, true); // Crea la carpeta si no existe
                    }

                    if ($this->convertirAWebp($imgData['tmp_name'], $rutaDestino)) {
                        // Guardar en la tabla imagenesitems
                        $this->Imagenesitem->create();
                        $this->Imagenesitem->save([
                            'url' => $nombreArchivo,
                            'producto_id' => $productoId,
                            'posicion' => $index,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    } else {
                        // Si falla, es porque la imagen era demasiado grande para la RAM
                        $this->Session->setFlash("La imagen {$imgData['name']} es demasiado grande y no pudo ser procesada.", 'default', array('class' => 'alert alert-warning'));
                    }
                
                }
            }

            $this->Session->setFlash('Producto creado correctamente con imágenes optimizadas.', 'default', array('class' => 'alert alert-success'));
            return $this->redirect(array('action' => 'index'));
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
        $this->loadModel('Palabrasclave');
        $this->loadModel('Imagenesitem');

        /*se reagistra la actividad del uso de la aplicacion*/
        $usuariosController = new UsuariosController();
        $usuarioAct = $this->Auth->user('id');
        $usuariosController->registraractividad($usuarioAct);
                        
		if (!$this->Producto->exists($id)) {
			throw new NotFoundException(__('El producto no existe.'));
		}
		if ($this->request->is(array('post', 'put'))) {

            $this->guardarProducto();

		} else {
			$options = array('conditions' => array('Producto.' . $this->Producto->primaryKey => $id));
			$this->request->data = $this->Producto->find('first', $options);
		}
        $empresaId = $this->Auth->user('empresa_id');     
		$categorias = $this->Producto->Categoria->obtenerCategoriasEmpresa($empresaId);  				               
        
        // 1. Obtener palabras clave actuales para mostrarlas en los Tags
        $palabras = $this->Palabrasclave->obtenerPalabrasProducto($id);

        // 2. Si no usas Containable, cárgalas manualmente:
        $imagenesActuales = $this->Imagenesitem->obtnenerImagenesProducto($id);
        
        $this->set('imagenesActuales', $imagenesActuales);
        $this->set('palabrasClavePrevias', implode(',', $palabras));
		$this->set(compact('categorias', 'empresaId'));
	}

    /**
     * Elimina una foto específica asociada a un producto
     */
    public function eliminar_foto_item($id = null) {
        $this->autoRender = false;
        $this->loadModel('Imagenesitem');
        
        if ($this->request->is('post') && $id) {
            $img = $this->Imagenesitem->findById($id);
            if ($img) {
                // Construir ruta física: webroot/img/productos/{empresa_id}/{nombre}
                $empresaId = $this->Auth->user('empresa_id'); // Ajusta según tu lógica de sesión
                $rutaFisica = WWW_ROOT . 'img' . DS . 'productos' . DS . $empresaId . DS . $img['Imagenesitem']['url'];

                // 1. Borrar archivo físico
                if (file_exists($rutaFisica)) {
                    unlink($rutaFisica);
                }

                // 2. Borrar registro en DB
                if ($this->Imagenesitem->delete($id)) {
                    return json_encode(['status' => 'success']);
                }
            }
        }
        return json_encode(['status' => 'error', 'message' => 'No se pudo eliminar']);
    }

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Producto->id = $id;
		if (!$this->Producto->exists()) {
			throw new NotFoundException(__('El producto no existe.'));
		}
		
		if ($this->Producto->actualizarEstadoProducto($id)) {
			$this->Session->setFlash(__('El producto ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El producto no pudo ser eliminado. Por favor, inténtelo de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

    /**
     * Convierte imagen a WebP con validación de memoria previa y redimensión
     */
    private function convertirAWebp($rutaOrigen, $rutaDestino, $calidad = 80) {
        // 1. Obtener dimensiones y tipo
        $info = @getimagesize($rutaOrigen);
        
        if (!$info) return false;

        $width  = $info[0];
        $height = $info[1];
        $mime   = $info['mime'];

        // 2. CÁLCULO PREVENTIVO DE MEMORIA
        // Un PNG/JPG en RAM usa: Ancho * Alto * 4 bytes (RGBA) * factor de seguridad
        $memoriaNecesaria = ($width * $height * 4) * 1.5; 
        
        // Obtenemos el límite real de PHP en bytes
        $limitStr = ini_get('memory_limit');
        $limitBytes = $this->_return_bytes($limitStr);

        // Si la imagen requiere más del 80% de la RAM total, abortamos para evitar Fatal Error
        if ($memoriaNecesaria > ($limitBytes * 0.8)) {
            CakeLog::write('error', "Imagen rechazada por exceso de dimensiones: $width x $height. RAM estimada: " . ($memoriaNecesaria / 1024 / 1024) . "MB");
            return false; 
        }

        // 3. CREAR RECURSO ORIGINAL (Aquí es donde daba el error antes)
        try {
            switch ($mime) {
                case 'image/jpeg': 
                    $src = @imagecreatefromjpeg($rutaOrigen); 
                    break;
                case 'image/png':  
                    // Si esto falla, intentamos cargarlo como string (más lento pero más seguro)
                    $src = @imagecreatefrompng($rutaOrigen); 
                    if (!$src) {
                        $data = file_get_contents($rutaOrigen);
                        $src = imagecreatefromstring($data);
                    }
                    break;
                default: return false;
            }
        } catch (Exception $e) {
            CakeLog::write('error', 'Fallo crítico abriendo imagen: ' . $e->getMessage());
            return false;
        }

        if (!$src) return false;

        // 4. REDIMENSIÓN (Máximo 1200px para ahorrar espacio y RAM posterior)
        $max_size = 1200;
        if ($width > $max_size || $height > $max_size) {
            if ($width > $height) {
                $new_width = $max_size;
                $new_height = floor($height * ($max_size / $width));
            } else {
                $new_height = $max_size;
                $new_width = floor($width * ($max_size / $height));
            }

            $dst = imagecreatetruecolor($new_width, $new_height);

            if ($mime == 'image/png') {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagedestroy($src); // Liberamos la memoria de la original gigante de inmediato
            $image_to_save = $dst;
        } else {
            $image_to_save = $src;
            if ($mime == 'image/png') {
                imagepalettetotruecolor($image_to_save);
                imagealphablending($image_to_save, true);
                imagesavealpha($image_to_save, true);
            }
        }

        // 5. GUARDAR COMO WEBP Y LIBERAR
        $resultado = imagewebp($image_to_save, $rutaDestino, $calidad);
        return $resultado;
    }

    /**
     * Función auxiliar para convertir límites de PHP (1G, 512M) a bytes
     */
    private function _return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int)$val;
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val * 1024;
    }
        
        
/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $arrImagen
 * @return void
 */        
        public function subirArchivo($arrImagen, $confDato, $nombreImg, $empresaId, $usuarioId){

            $this->loadModel('Auditoria');
            $this->loadModel('Configuraciondato');
            $this->loadModel('Usuario');

            $urlImagen = $this->Configuraciondato->obtenerValorDatoConfig($confDato) . $empresaId . '//'; 
            
            $carpeta = $urlImagen;
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }            

            $file = new File($arrImagen['tmp_name']);
            $path_parts = pathinfo($arrImagen['name']);
            $ext = $path_parts['extension'];
            
            $filename = "{$nombreImg}.{$ext}";
            $data = $file->read();
            $file->close();
            $file = new File($urlImagen . $filename,true);
            if( $file->write($data)){
                /*Se registra en auditoria el cargue del documento*/
                    $id = '0';
                    $accion = $this->Auditoria->accionAuditoria($id);
                    $arrDescripcion['nombreImg'] = $nombreImg;  
                    $descripcion = $this->Auditoria->descripcionAuditoria($id, $arrDescripcion);
                    $this->Auditoria->logAuditoria($usuarioId, $descripcion, $accion);                
                return TRUE;                    
            }else{
                return FALSE;
            }              
        }
        
        
/**
 * productocatalogo method
 *
 * @throws NotFoundException
 * @param
 * @return void
 */         
        public function productocatalogo(){
            $this->loadModel('Categoria');
            $empresaId = $this->Auth->user('empresa_id');
            $categorias = $this->Categoria->obtenerCategoriasEmpresa($empresaId);
            $this->set(compact('categorias', 'empresaId'));           
        }
        
        public function guardarproductoinventario(){
            $this->autoRender = false;
            $posData = $this->request->data;

            /*Se obtiene el id de la empresa a la que pertenece el usuario que realiza la gestion*/
            $arrEmpresa = $this->Auth->user('Empresa');
            $empresaId = $arrEmpresa['id'];                                   
            
            if(isset($posData['Producto']['imagen']['name']) && !empty($posData['Producto']['imagen']['name'])){
                //Se obtiene la extension del archivo
                $arrExt = explode(".", $posData['Producto']['imagen']['name']);                  
                
                $confDato = "dirImgProducto";
                $nombreImg = "lgPr_" . $posData['Producto']['codigo'];
                $this->request->data['Producto']['imagen'] = $nombreImg . "." . $arrExt['1'];                
            }else{
                unset($this->request->data['Producto']['imagen']);
            }            

            $this->Producto->create();
            if ($this->Producto->save($this->request->data)) {
                if(isset($posData['Producto']['imagen']['name']) && !empty($posData['Producto']['imagen']['name'])){
                    if($this->subirArchivo($posData['Producto']['imagen'], $confDato, $nombreImg, $empresaId, $this->Auth->user('id'))){
                        $post_id = $this->Producto->getLastInsertId();
                        echo json_encode(array('bool' => true, 'id' => $post_id));                                                   
                    }                    
                }else{
                    $post_id = $this->Producto->getLastInsertId();
                    echo json_encode(array('bool' => true, 'id' => $post_id));
                }

            } else {
                    echo json_encode(array('bool' => false));
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
        
        /**
         * se valida que el codigo sea unico
         */
        public function validarCodigoUnico(){
            $this->autoRender = false;

            $posData = $this->request->data;
            $codigo = $posData['codigo'];
            $empresaId = $this->Auth->user('empresa_id');

            $result = $this->Producto->obtenerProductoPorCodigo($codigo, $empresaId);
            
            $bresult = empty($result) ? false : true;

            echo json_encode(array('resp' => $bresult));             
        }
        
        /**
         * se valida que la referencia sea unica
         */
        public function validarReferenciaUnica(){
            $this->autoRender = false;

            $posData = $this->request->data;
            $referencia = $posData['referencia'];   
            $empresaId = $this->Auth->user('empresa_id');        

            $result = $this->Producto->obtenerProductoPorReferencia($referencia, $empresaId);
            
            $bresult = empty($result) ? false : true;
            echo json_encode(array('resp' => $bresult));                         
        }
        
        /**
         * valida si el codigo es unico al editar
         */
        public function validarCodigoUnicoEdit(){
            $this->autoRender = false;

            $posData = $this->request->data;
            $codigo = $posData['codigo'];           
            $productoId = $posData['productoId'];  
            $empresaId = $this->Auth->user('empresa_id');
            
            //busca un producto con el codigo ingresado
            $result = $this->Producto->obtenerProductoPorCodigo($codigo, $empresaId);

            if(!empty($result) && $result['Producto']['id'] != $productoId){
                
                //obtiene los datos del producto a editar
                $prod = $this->Producto->obtenerInformacionProductoId($productoId);
                
                $bresult = true;
                echo json_encode(array('resp' => $bresult, 'codigo' => $prod['Producto']['codigo']));
            }else{
                echo json_encode(array('resp' => false));
            }
        }
        
        /**
         * valida si la referencia ya ha sido asignada a otro producto
         */
        public function validarReferenciaUnicaEdit(){
            $this->autoRender = false;
            
            $posData = $this->request->data;
            $referencia = $posData['referencia'];
            $productoId = $posData['productoId'];
            $empresaId = $this->Auth->user('empresa_id');
            
            //busca un producto con la referencia ingresada
            $result = $this->Producto->obtenerProductoPorReferencia($referencia, $empresaId);
            
            if(!empty($result) && $result['Producto']['id'] != $productoId){
                //obtiene los datos del producto a editar
                $prod = $this->Producto->obtenerInformacionProductoId($productoId);
                $bresult = true;
                echo json_encode(array('resp' => $bresult, 'referencia' => $prod['Producto']['referencia']));
            }else{
                echo json_encode(array('resp' => false));
            }
        }
              

}