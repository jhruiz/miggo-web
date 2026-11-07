<?php

//use Security;
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
//App::uses('AuthComponent', 'Controller/Component');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package        app.Controller
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    public $components = array(
        'Session',
        'Auth' => array(
            'loginAction' => array('controller' => 'usuarios', 'action' => 'login'),
            'loginRedirect' => array('controller' => 'usuarios', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'usuarios', 'action' => 'login'),
            'authError' => '',
            'authorize' => array('Controller'),
            'authenticate' => array(
                'Basic' => array(
                    'userModel' => 'Usuario',
                    'fields' => array(
                        'username' => 'username',
                        'password' => 'password',
                    ),
                ),
                'Form' => array(
                    'userModel' => 'Usuario',
                    'fields' => array(
                        'username' => 'username',
                        'password' => 'password',
                    ),
                ),
            )),
    );

    public function isAuthorized($user)
    {

        return true;
    }

    public function beforeFilter()
    {
        $this->loadModel('Ordentrabajo');
        $this->loadModel('Cargueinventario');
        $this->loadModel('Cuenta');
        $this->loadModel('Alertaordene');
        $this->loadModel('Evento');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Cuentaspendiente');
        $this->loadModel('Empresa');
        $this->loadModel('Prefactura');

        $empresaId = $this->Auth->user('empresa_id');
        $ordenTrabajos = $this->Ordentrabajo->obtenerEstadistacasOrdenes($empresaId);
        $productosBajos = $this->Cargueinventario->obtenerBajoStock($empresaId);
        $listCuentas = $this->Cuenta->obtenerCuentasEmpresa($empresaId);
        $prefactEccomerce = $this->Prefactura->obtenerPrefacturasEcommerce($empresaId);

        //alertas pendientes por gestionar
        $filtros = array(
            'EA.final = 0',
            'EA.empresa_id' => $empresaId,
            'Alertaordene.fecha_alerta < ' => date('Y-m-d'),
        );
        //obtienen todas las alertas pendientes
        $arlerts = count($this->Alertaordene->obtenerInfoAlertaOrden($filtros));

        //se obtiene la informacion de la empresa
        $infoEmp = $this->Empresa->obtenerEmpresaPorId($empresaId);

        //obtiene todos los eventos previos a una fecha y que no se hayan finalizado aun
        $eventos = $this->Evento->obtenerEventosVencidos($empresaId, date('Y-m-d 23:59:59'));

        //cuentas por cobrar
        $ctasXCobrar = $this->Cuentascliente->obtenerCuentasVencidas($empresaId, date('Y-m-d 23:59:59'));

        //cuentas por pagar
        $filterCtas = array('Cuentaspendiente.empresa_id' => $empresaId, 'Cuentaspendiente.eliminar = 0', 'Cuentaspendiente.fechapago <' => date('Y-m-d 23:59:59'));
        $ctasXPagar = $this->Cuentaspendiente->obtenerCuentasPendientes($filterCtas);

        $arrColMd = $this->contador(count($ordenTrabajos), 6);


        Security::setHash('md5');
        $this->set('logged_in', $this->Auth->loggedIn());
        $this->set('current_user', $this->Auth->user());
        $this->set('ordenTrabajos', $ordenTrabajos);
        $this->set('productosBajos', $productosBajos);
        $this->set('listCuentas', $listCuentas);
        $this->set('arlerts', $arlerts);
        $this->set('arrColMd', $arrColMd);
        $this->set('eventos', $eventos);
        $this->set('ctasXCobrar', $ctasXCobrar);
        $this->set('ctasXPagar', $ctasXPagar);
        $this->set('infoEmp', $infoEmp);
        $this->set('prefactEccomerce', $prefactEccomerce);

    }

    public function contador($items, $count)
    {
        $countTtal = $items + $count;

        $rows = 6;

        do {
            $rows += 6;
        } while ($rows < $countTtal);

        return $rows - $countTtal;

    }

    // ─────────────────────────────────────────────────────────────────────────
    // EMPRESA ACTIVA DE FACTURACIÓN (centro de costo)
    //
    // Cuando un usuario factura "en nombre de" una sede (centro de costo), la
    // empresa destino se guarda en sesión y es la fuente de verdad para
    // inventario, clientes, resolución y guardado durante esa operación.
    //
    // Si no hay empresa activa fijada, se cae a la empresa del usuario logueado.
    // ─────────────────────────────────────────────────────────────────────────

    const SESSION_EMPRESA_ACTIVA    = 'Facturacion.empresaActiva';
    const SESSION_CENTROCOSTO_ACTIVO = 'Facturacion.centrocostoActivo';

    /**
     * Fija la empresa activa de facturación y el centro de costo que la originó.
     *
     * @param int $empresaId
     * @param int $centrocostoId
     */
    protected function fijarEmpresaActiva($empresaId, $centrocostoId)
    {
        $this->Session->write(self::SESSION_EMPRESA_ACTIVA, (int) $empresaId);
        $this->Session->write(self::SESSION_CENTROCOSTO_ACTIVO, (int) $centrocostoId);
    }

    /**
     * Devuelve la empresa activa de facturación.
     * Si no hay ninguna fijada, devuelve la empresa del usuario logueado.
     *
     * @return int
     */
    protected function empresaActiva()
    {
        $empresa = $this->Session->read(self::SESSION_EMPRESA_ACTIVA);
        return !empty($empresa) ? (int) $empresa : (int) $this->Auth->user('empresa_id');
    }

    /**
     * Devuelve el centro de costo activo de facturación (o null si no hay).
     *
     * @return int|null
     */
    protected function centrocostoActivo()
    {
        $cc = $this->Session->read(self::SESSION_CENTROCOSTO_ACTIVO);
        return !empty($cc) ? (int) $cc : null;
    }

    /**
     * Limpia la empresa activa de facturación.
     * Se llama al terminar o abandonar el flujo de facturación para no
     * "arrastrar" la sede a otra operación.
     */
    protected function limpiarEmpresaActiva()
    {
        $this->Session->delete(self::SESSION_EMPRESA_ACTIVA);
        $this->Session->delete(self::SESSION_CENTROCOSTO_ACTIVO);
    }

}
