<?php
App::uses('AppController', 'Controller');

/**
 * Reportes de retenciones en ventas (paso 7) y certificados de retención.
 *
 * Reportes (todos con filtros de fechas, cliente y tipo, y exportación a Excel):
 *   detalle      Retenciones que me practicaron
 *   pendientes   Certificados pendientes
 *   resumen      Resumen del período (por tipo y ReteICA por ciudad)
 *   diferencias  Diferencias estimado vs. real
 *
 * Fuente: retenciones_aplicadas (lo que el cliente realmente retuvo). Solo se
 * muestran las retenciones de la empresa del usuario.
 *
 * Los certificados se guardan en una carpeta NO pública (fuera de webroot) y
 * solo se descargan desde aquí, por usuarios de la misma empresa.
 */
class ReporteretencionesController extends AppController {

    /** Tamaño máximo del certificado: 5 MB */
    const MAX_BYTES_CERTIFICADO = 5242880;

    /** Extensión => firma inicial del archivo (se valida el contenido, no solo el nombre) */
    private $_tiposCertificado = array(
        'pdf'  => array('%PDF'),
        'png'  => array("\x89PNG"),
        'jpg'  => array("\xFF\xD8\xFF"),
        'jpeg' => array("\xFF\xD8\xFF"),
    );

    private $_reportes = array(
        'detalle'     => 'Retenciones que me practicaron',
        'pendientes'  => 'Certificados pendientes',
        'resumen'     => 'Resumen del período',
        'diferencias' => 'Diferencias estimado vs. real',
    );

    /**
     * Pantalla de reportes.
     * GET: reporte, desde, hasta, cliente, tributo, certificado
     */
    public function index() {
        $this->loadModel('RetencionAplicada');

        $reporte = $this->_reporteSolicitado();
        $filtros = $this->RetencionAplicada->normalizarFiltrosReporte($this->request->query);
        $datos = $this->_datosReporte($reporte, $filtros);

        $reportes = $this->_reportes;
        $tributos = RetencionAplicada::$tributosReporte;
        $queryActual = $this->_queryFiltros($reporte, $filtros);
        $this->set(compact('reporte', 'reportes', 'filtros', 'datos', 'tributos', 'queryActual'));
    }

    /**
     * Exporta a Excel el reporte con los mismos filtros de la pantalla.
     */
    public function exportar() {
        $this->loadModel('RetencionAplicada');

        $reporte = $this->_reporteSolicitado();
        $filtros = $this->RetencionAplicada->normalizarFiltrosReporte($this->request->query);
        $datos = $this->_datosReporte($reporte, $filtros);

        $titulo = $this->_reportes[$reporte] . ' (' . $filtros['desde'] . ' a ' . $filtros['hasta'] . ')';
        list($titulos, $filas) = $this->_tablaExportacion($reporte, $datos);

        $this->set(compact('titulo', 'titulos', 'filas'));
        $this->render('exportar', 'export_xls');
    }

    /**
     * Registra un certificado para las retenciones marcadas.
     * POST multipart: data[Certificado][ids][], data[Certificado][fecha], data[Certificado][archivo]
     */
    public function registrarcertificado() {
        $this->request->onlyAllow('post');
        $this->loadModel('RetencionAplicada');

        $datos = isset($this->request->data['Certificado']) ? $this->request->data['Certificado'] : array();
        $ids = isset($datos['ids']) ? (array) $datos['ids'] : array();
        $fecha = isset($datos['fecha']) ? (string) $datos['fecha'] : '';
        $archivo = isset($datos['archivo']) ? $datos['archivo'] : null;

        $error = $this->_validarCertificado($ids, $fecha, $archivo);
        if ($error !== null) {
            $this->Session->setFlash($error);
            return $this->redirect($this->referer(array('action' => 'index'), true));
        }

        $empresaId = (int) $this->Auth->user('empresa_id');
        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $relativo = $empresaId . '/cert_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $ext;

        $base = $this->RetencionAplicada->directorioCertificados();
        if (!$this->_prepararDirectorio($base, $base . $empresaId)) {
            CakeLog::write('error', 'Certificados de retención: no se pudo crear la carpeta ' . $base . $empresaId);
            $this->Session->setFlash(__('No se pudo guardar el certificado. Por favor, inténtelo de nuevo.'));
            return $this->redirect($this->referer(array('action' => 'index'), true));
        }
        $destino = $base . str_replace('/', DS, $relativo);
        if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
            CakeLog::write('error', 'Certificados de retención: no se pudo mover el archivo a ' . $destino);
            $this->Session->setFlash(__('No se pudo guardar el certificado. Por favor, inténtelo de nuevo.'));
            return $this->redirect($this->referer(array('action' => 'index'), true));
        }

        $marcadas = $this->RetencionAplicada->registrarCertificado($empresaId, $ids, $relativo, $fecha);
        if ($marcadas === 0) {
            @unlink($destino);
            $this->Session->setFlash(__('No se registró el certificado: las retenciones seleccionadas no son válidas.'));
        } else {
            $this->Session->setFlash(__('Certificado registrado en %d retención(es).', $marcadas));
        }
        return $this->redirect($this->referer(array('action' => 'index'), true));
    }

    /**
     * Quita el certificado de las retenciones marcadas. Borra el archivo si
     * ninguna otra retención lo usa.
     * POST: data[Certificado][ids][]
     */
    public function quitarcertificado() {
        $this->request->onlyAllow('post');
        $this->loadModel('RetencionAplicada');

        $ids = isset($this->request->data['Certificado']['ids']) ? (array) $this->request->data['Certificado']['ids'] : array();
        if (empty($ids)) {
            $this->Session->setFlash(__('Seleccione al menos una retención.'));
            return $this->redirect($this->referer(array('action' => 'index'), true));
        }

        $res = $this->RetencionAplicada->quitarCertificado((int) $this->Auth->user('empresa_id'), $ids);
        $base = $this->RetencionAplicada->directorioCertificados();
        foreach ($res['archivos_sin_uso'] as $relativo) {
            $ruta = $this->_rutaCertificado($base, $relativo);
            if ($ruta !== null && is_file($ruta)) {
                @unlink($ruta);
            }
        }
        $this->Session->setFlash(__('Certificado quitado de %d retención(es).', $res['quitadas']));
        return $this->redirect($this->referer(array('action' => 'index'), true));
    }

    /**
     * Muestra el certificado de una retención de la empresa.
     */
    public function vercertificado($id = null) {
        $this->loadModel('RetencionAplicada');

        $relativo = $this->RetencionAplicada->archivoCertificado((int) $this->Auth->user('empresa_id'), $id);
        $ruta = $relativo ? $this->_rutaCertificado($this->RetencionAplicada->directorioCertificados(), $relativo) : null;
        if ($ruta === null || !is_file($ruta)) {
            throw new NotFoundException(__('El certificado no existe.'));
        }
        $this->response->file($ruta, array('download' => false, 'name' => basename($ruta)));
        return $this->response;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AUXILIARES
    // ─────────────────────────────────────────────────────────────────────────

    private function _reporteSolicitado() {
        $reporte = isset($this->request->query['reporte']) ? (string) $this->request->query['reporte'] : 'detalle';
        return isset($this->_reportes[$reporte]) ? $reporte : 'detalle';
    }

    private function _datosReporte($reporte, array $filtros) {
        $empresaId = (int) $this->Auth->user('empresa_id');
        switch ($reporte) {
            case 'pendientes':
                return $this->RetencionAplicada->reporteCertificadosPendientes($empresaId, $filtros);
            case 'resumen':
                return $this->RetencionAplicada->reporteResumen($empresaId, $filtros);
            case 'diferencias':
                return $this->RetencionAplicada->reporteDiferencias($empresaId, $filtros);
            default:
                return $this->RetencionAplicada->reporteDetalle($empresaId, $filtros);
        }
    }

    /** Parámetros GET actuales (para los enlaces de pestañas y exportación) */
    private function _queryFiltros($reporte, array $filtros) {
        return array_merge(array('reporte' => $reporte), $filtros);
    }

    /** Columnas y filas (valores simples) para el Excel */
    private function _tablaExportacion($reporte, array $datos) {
        $n = function ($v) {
            return number_format((float) $v, 2, ',', '');
        };
        $filas = array();
        switch ($reporte) {
            case 'pendientes':
                $titulos = array('NIT', 'Cliente', 'Retenciones sin certificado', 'Valor total', 'Más antigua');
                foreach ($datos['filas'] as $f) {
                    $filas[] = array($f['nit'], $f['cliente'], $f['cantidad'], $n($f['valor']), $f['mas_antigua']);
                }
                $filas[] = array('', 'Total', '', $n($datos['total_valor']), '');
                break;
            case 'resumen':
                $titulos = array('Tipo', 'Ciudad', 'Cantidad', 'Base', 'Valor');
                foreach ($datos['tributos'] as $f) {
                    $filas[] = array($f['nombre'], '', $f['cantidad'], $n($f['base']), $n($f['valor']));
                }
                foreach ($datos['ciudades'] as $f) {
                    $filas[] = array('ReteICA por ciudad', $f['ciudad'], $f['cantidad'], $n($f['base']), $n($f['valor']));
                }
                $filas[] = array('Total', '', '', '', $n($datos['total_valor']));
                break;
            case 'diferencias':
                $titulos = array('Fecha factura', 'Factura', 'NIT', 'Cliente', 'Retención', 'Calculada', 'Aplicada', 'Diferencia', 'Estado');
                foreach ($datos['filas'] as $f) {
                    $filas[] = array($f['fecha'], $f['factura'], $f['nit'], $f['cliente'], $f['retencion'],
                        $n($f['calculado']), $n($f['aplicado']), $n($f['diferencia']), $f['estado']);
                }
                $filas[] = array('', '', '', '', 'Total', $n($datos['total_calculado']), $n($datos['total_aplicado']), $n($datos['total_diferencia']), '');
                break;
            default:
                $titulos = array('Fecha', 'Origen', 'NIT', 'Cliente', 'Factura', 'Código', 'Retención', 'Base', 'Tarifa', 'Valor', 'Certificado', 'Fecha certificado');
                foreach ($datos['filas'] as $f) {
                    $filas[] = array($f['fecha'], $f['origen'], $f['nit'], $f['cliente'], $f['factura'], $f['tributo'], $f['retencion'],
                        $n($f['base']), $f['tarifa'], $n($f['valor']), $f['certificado'] ? 'Sí' : 'No', $f['certificado_fecha']);
                }
                $filas[] = array('', '', '', '', '', '', 'Total', $n($datos['total_base']), '', $n($datos['total_valor']), '', '');
        }
        return array($titulos, $filas);
    }

    /** Mensaje de error, o null si el certificado es válido */
    private function _validarCertificado(array $ids, $fecha, $archivo) {
        if (empty($ids)) {
            return __('Seleccione al menos una retención.');
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha) || strtotime($fecha) === false || $fecha > date('Y-m-d')) {
            return __('Indique una fecha de certificado válida (no puede ser futura).');
        }
        if (empty($archivo) || !is_array($archivo) || !isset($archivo['error']) || (int) $archivo['error'] !== UPLOAD_ERR_OK
            || empty($archivo['tmp_name']) || !is_uploaded_file($archivo['tmp_name'])) {
            return __('Adjunte el archivo del certificado.');
        }
        if ((int) $archivo['size'] <= 0 || (int) $archivo['size'] > self::MAX_BYTES_CERTIFICADO) {
            return __('El certificado no puede superar 5 MB.');
        }
        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        if (!isset($this->_tiposCertificado[$ext])) {
            return __('El certificado debe ser PDF, JPG o PNG.');
        }
        $inicio = (string) file_get_contents($archivo['tmp_name'], false, null, 0, 8);
        foreach ($this->_tiposCertificado[$ext] as $firma) {
            if (strncmp($inicio, $firma, strlen($firma)) === 0) {
                return null;
            }
        }
        return __('El contenido del archivo no corresponde a un PDF, JPG o PNG.');
    }

    /** Crea la carpeta y protege la raíz con un .htaccess que niega el acceso web */
    private function _prepararDirectorio($base, $carpeta) {
        if (!is_dir($carpeta) && !mkdir($carpeta, 0750, true)) {
            return false;
        }
        $htaccess = $base . '.htaccess';
        if (!is_file($htaccess)) {
            file_put_contents($htaccess, "<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n    Deny from all\n</IfModule>\n");
        }
        return true;
    }

    /** Ruta absoluta de un certificado; null si la ruta guardada no tiene el formato esperado */
    private function _rutaCertificado($base, $relativo) {
        if (!preg_match('#^(\d+)/(cert_\d{8}_\d{6}_[0-9a-f]{12}\.(pdf|png|jpg|jpeg))$#', (string) $relativo, $m)) {
            return null;
        }
        return $base . $m[1] . DS . $m[2];
    }
}
