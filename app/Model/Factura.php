<?php
App::uses('AppModel', 'Model');
/**
 * Factura Model
 *
 * @property Cliente $Cliente
 * @property Empresa $Empresa
 * @property Usuario $Usuario
 * @property Tipopago $Tipopago
 * @property Facturasdetalle $Facturasdetalle
 */
class Factura extends AppModel
{

/**
 * Validation rules
 *
 * @var array
 */
    public $validate = array(
        'codigo' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'empresa_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'usuario_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
        'Cliente' => array(
            'className' => 'Cliente',
            'foreignKey' => 'cliente_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Usuario' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Tipopago' => array(
            'className' => 'Tipopago',
            'foreignKey' => 'tipopago_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Cuenta' => array(
            'className' => 'Cuenta',
            'foreignKey' => 'cuenta_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );

/**
 * hasMany associations
 *
 * @var array
 */
    public $hasMany = array(
        'Facturasdetalle' => array(
            'className' => 'Facturasdetalle',
            'foreignKey' => 'factura_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ),
        'FacturasNotafactura' => array(
            'className' => 'FacturasNotafactura',
            'foreignKey' => 'factura_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ),
    );

    public function guardarfactura($clienteId, $empresaId, $usuarioId, $fechaVence, $tipoPagoId, $pagoContado = 0, $pagoCredito = 0,
        $documentoId, $empRelacionada, $ordentrabajo, $esFactura, $cuenta_id, $observacion, $canalventa, $fechaOrden, $numeroOrden) {

        $data = array();
        $factura = new Factura();

        if ($clienteId != "" && $clienteId != null) {
            $data['cliente_id'] = $clienteId;
        }

        if ($empRelacionada == "") {
            $data['empresa_id'] = $empresaId;
        } else {
            $data['relacionempresa'] = $empRelacionada;
        }

        $data['usuario_id'] = $usuarioId;
        $data['canalventa_id'] = $canalventa;
        $data['fechavence'] = $fechaVence;
        $data['tipopago_id'] = $tipoPagoId;
        $data['pagocontado'] = $pagoContado;
        $data['pagocredito'] = $pagoCredito;
        $data['documento_id'] = $documentoId;

        if (!empty($ordentrabajo)) {
            $data['ordentrabajo_id'] = $ordentrabajo;
        }

        $data['factura'] = $esFactura;
        $data['codigo'] = $esFactura;
        $data['cuenta_id'] = $cuenta_id;
        $data['observacion'] = $observacion;
        $data['numeroorden'] = $numeroOrden;
        $data['fechaorden'] = $fechaOrden;

        if ($factura->save($data)) {
            return $factura->id;
        } else {
            return '0';
        }

    }

    public function obtenerInfoFacturaPorId($facturaId)
    {
        $infoFact = $this->find('first', array('conditions' => array('Factura.id' => $facturaId), 'recursive' => '0'));
        return $infoFact;
    }

    /**
     * Asigna el centro de costo (sede) a una factura ya creada.
     *
     * @param  int $facturaId
     * @param  int $centrocostoId
     * @return bool
     */
    public function asignarCentroCosto($facturaId, $centrocostoId)
    {
        if (empty($centrocostoId)) {
            return false;
        }
        $factura = new Factura();
        $data = array(
            'id'             => $facturaId,
            'centrocosto_id' => (int) $centrocostoId,
        );
        return (bool) $factura->save($data);
    }

    public function actualizarConsecutivoDianFactura($facturaId, $consFact, $tipoDocumento)
    {
        $data = array();
        $factura = new Factura();

        if( $tipoDocumento == 1 ) {
            $data['consecutivodian'] = $consFact['consecutivo'];
        } else {
            $data['consecutivodv'] = $consFact['consecutivo'];
        }

        $data['id'] = $facturaId;
        $data['prefijo'] = $consFact['prefijo'];

        if ($factura->save($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function obtenerFacturasCierreDiario($fechaInicio, $fechaFin, $empresaId, $cuenta = null)
    {

        $arrFilter = [];
        if (!empty($cuenta)) {
            $arrFilter['Factura.cuenta_id'] = $cuenta;
        }

        $arrFilter['Factura.created BETWEEN ? AND ?'] = array($fechaInicio, $fechaFin);
        $arrFilter['Factura.empresa_id'] = $empresaId;

        $detFact = $this->find('all', array(
            'conditions' =>
            $arrFilter
            ,
            'order' => 'Factura.created',
            'recursive' => '0',
        ));
        return $detFact;
    }

    public function actualizarCodigoFactura($facturaId)
    {
        $data = array();
        $factura = new Factura();

        $data['id'] = $facturaId;
        $data['codigo'] = $facturaId;

        if ($factura->save($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function obtenerFacturasOrdenesServicios($filter)
    {
        $arr_join = array();
        array_push($arr_join, array(
            'table' => 'facturasdetalles',
            'alias' => 'FD',
            'type' => 'INNER',
            'conditions' => array(
                'FD.factura_id=Factura.id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'productos',
            'alias' => 'PR',
            'type' => 'INNER',
            'conditions' => array(
                'PR.id=FD.producto_id',
            ),
        ));

        array_push($arr_join, array(
           'table' => 'categorias',
            'alias' => 'CT',
            'type' => 'INNER',
            'conditions' => array(
                'CT.id=PR.categoria_id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'ordentrabajos',
            'alias' => 'OT',
            'type' => 'INNER',
            'conditions' => array(
                'OT.id=Factura.ordentrabajo_id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'vehiculos',
            'alias' => 'VH',
            'type' => 'INNER',
            'conditions' => array(
                'VH.id=OT.vehiculo_id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'usuarios',
            'alias' => 'US',
            'type' => 'INNER',
            'conditions' => array(
                'US.id=OT.usuario_id',
            ),
        ));

        $ordenesT = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'FD.cantidad',
                'FD.costoventa',
                'FD.costototal',
                'PR.id',
                'PR.codigo',
                'PR.descripcion',
                'OT.id',
                'OT.codigo',
                'US.id',
                'US.nombre',
                'US.identificacion',
                'VH.id',
                'VH.placa',
                'VH.marcavehiculo_id',
                'VH.linea',
                'Factura.*'
            ),
            'conditions' => array('CT.servicio' => '1', $filter),
            'recursive' => '-1',
            'order' => 'Factura.id desc',
        ));

        return $ordenesT;
    }

    public function obtenerFacturas($filter)
    {
        $arr_join = array();
        array_push($arr_join, array(
            'table' => 'facturasdetalles',
            'alias' => 'FD',
            'type' => 'INNER',
            'conditions' => array(
                'FD.factura_id=Factura.id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'depositos',
            'alias' => 'DP',
            'type' => 'INNER',
            'conditions' => array(
                'FD.deposito_id=DP.id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'clientes',
            'alias' => 'CL',
            'type' => 'LEFT',
            'conditions' => array(
                'CL.id=Factura.cliente_id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'usuarios',
            'alias' => 'US',
            'type' => 'INNER',
            'conditions' => array(
                'US.id=Factura.usuario_id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'productos',
            'alias' => 'PR',
            'type' => 'INNER',
            'conditions' => array(
                'PR.id=FD.producto_id',
            ),
        ));

        if (!empty($filter['FCV.tipopago_id'])) {
            array_push($arr_join, array(
                'table' => 'factura_cuenta_valores',
                'alias' => 'FCV',
                'type' => 'LEFT',
                'conditions' => array(
                    'FCV.factura_id = Factura.id',
                ),
            ));
        }

        $facturas = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'FD.*',
                'CL.*',
                'PR.id',
                'PR.codigo',
                'PR.descripcion',
                'US.id',
                'US.nombre',
                'US.identificacion',
                'DP.id',
                'DP.descripcion',
                'Factura.*',
            ),
            'conditions' => array($filter),
            'recursive' => '-1',
        ));

        return $facturas;
    }

    /**
     * Actualiza el valor de la venta de la factura
     * @param type $facturaId
     * @param type $pagoContado
     * @return boolean
     */
    public function actualizarValorVentaTotal($facturaId, $pagoContado)
    {
        $data = array();
        $factura = new Factura();

        $data['id'] = $facturaId;
        $data['pagocontado'] = $pagoContado;

        if ($factura->save($data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Obtiene las facturas y todos los pagos realizados a cada una
     * @param type $fechaInicio
     * @param type $fechaFin
     * @param type $empresaId
     * @param type $cuenta
     * @return type
     */
    public function obtenerFacturasTipoPagos($fechaInicio, $fechaFin, $empresaId, $cuenta)
    {

        $arrFilter = [];
        if (!empty($cuenta)) {
            $arrFilter['FCV.cuenta_id'] = $cuenta;
        }

        $arrFilter['Factura.created BETWEEN ? AND ?'] = array($fechaInicio, $fechaFin);
        $arrFilter['Factura.empresa_id'] = $empresaId;

        $arr_join = array();
        array_push($arr_join, array(
            'table' => 'factura_cuenta_valores',
            'alias' => 'FCV',
            'type' => 'INNER',
            'conditions' => array(
                'FCV.factura_id=Factura.id',
            ),
        ));

        $detFact = $this->find('all', array(
            'joins' => $arr_join,
            'conditions' =>
            $arrFilter
            ,
            'fields' => array(
                'Factura.*',
                'Cliente.*',
                'Usuario.*',
                'FCV.*',
            ),
            'order' => 'Factura.created',
            'recursive' => '0',
        ));

        return $detFact;
    }

    public function obtenerIndexFacturas($filtros)
    {

        $arr_join = array();

        array_push($arr_join, array(
            'table' => 'clientes',
            'alias' => 'C',
            'type' => 'LEFT',
            'conditions' => array(
                'C.id=Factura.cliente_id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'usuarios',
            'alias' => 'U',
            'type' => 'INNER',
            'conditions' => array(
                'U.id=Factura.usuario_id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'ordentrabajos',
            'alias' => 'O',
            'type' => 'LEFT',
            'conditions' => array(
                'O.id = Factura.ordentrabajo_id',
            ),
        ));

        if (!empty($filtros['FCV.tipopago_id'])) {
            array_push($arr_join, array(
                'table' => 'factura_cuenta_valores',
                'alias' => 'FCV',
                'type' => 'LEFT',
                'conditions' => array(
                    'FCV.factura_id = Factura.id',
                ),
            ));
        }

        array_push($arr_join, array(
            'table' => 'vehiculos',
            'alias' => 'V',
            'type' => 'LEFT',
            'conditions' => array(
                'V.id = O.vehiculo_id',
            ),
        ));

        $listadoOficinasUsuario = array(
            'joins' => $arr_join,
            'fields' => array(
                'C.*',
                'U.*',
                'V.*',
                'Factura.*',
            ),
            'conditions' => array(
                $filtros,
            ),
//                'limit' => '20',
            'order' => 'Factura.id DESC',
            'group' => 'Factura.id',
            'recursive' => -1,
        );

        return $listadoOficinasUsuario;
    }

    public function actualizarEstadoPagoMec($id, $estado, $dateAct)
    {
        $data = array();
        $factura = new Factura();

        $data['id'] = $id;
        $data['estadopagomecanico_id'] = $estado;
        $data['fechapagoservicio'] = $dateAct;

        if ($factura->save($data)) {
            return true;
        } else {
            return false;
        }
    }
    public function obtenerFacturasClientes($empresaId, $filtros)
    {

        $arr_join = array();

        array_push($arr_join, array(
            'table' => 'clientes',
            'alias' => 'C',
            'type' => 'INNER',
            'conditions' => array(
                'C.id=Factura.cliente_id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'ordentrabajos',
            'alias' => 'O',
            'type' => 'LEFT',
            'conditions' => array(
                'O.id = Factura.ordentrabajo_id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'usuarios',
            'alias' => 'U',
            'type' => 'LEFT',
            'conditions' => array(
                'U.id=O.usuario_id',
            ),
        ));

        array_push($arr_join, array(
            'table' => 'vehiculos',
            'alias' => 'V',
            'type' => 'LEFT',
            'conditions' => array(
                'V.id = O.vehiculo_id',
            ),
        ));

        $facturasCliente = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'COUNT(Factura.cliente_id) as conteo',
                'SUM(Factura.pagocontado) as valor',
                'C.*',
                'U.*',
                'V.*',
                'Factura.*',
            ),
            'conditions' => array(
                $filtros,
            ),
            'limit' => '20',
            'order' => 'valor DESC, Factura.cliente_id, conteo DESC',
            'group' => 'Factura.cliente_id, O.usuario_id, V.id, Factura.id',
            'recursive' => -1,
        ));

        return $facturasCliente;

    }

    public function obtenerFactuasClientes($empresaId, $fechaInicial = null, $fechaFinal = null)
    {
        $filterDate = '';
        if (!empty($fechaInicial) && !empty($fechaFinal)) {
            $filterDate = array('Factura.created BETWEEN ? AND ? ' => array($fechaInicial, $fechaFinal));
        }

        $arr_join = array();

        array_push($arr_join, array(
            'table' => 'clientes',
            'alias' => 'C',
            'type' => 'INNER',
            'conditions' => array('Factura.cliente_id=C.id'),
        ));

        $resp = $this->find('all', array(
            'joins' => $arr_join,
            'fields' => array(
                'count(Factura.cliente_id) as contador',
                'C.nombre',
            ),
            'conditions' => array(
                'Factura.empresa_id' => $empresaId,
                'C.estadisticas' => '1',
                $filterDate,
            ),
            'group' => 'Factura.cliente_id',
            'order' => 'contador desc',
            'limit' => '5',
        ));

        return $resp;
    }
    public function obtenerInfoAlertaFactura($filtros){

        $arr_join = array();

        array_push($arr_join, array(
            'table' => 'alertaordenes', 
            'alias' => 'Alertaordene', 
            'type' => 'LEFT',
            'conditions' => array('Alertaordene.factura_id=Factura.id')                
        ));

        array_push($arr_join, array(
            'table' => 'usuarios', 
            'alias' => 'US', 
            'type' => 'LEFT',
            'conditions' => array('Factura.usuario_id=US.id')                
        ));

        array_push($arr_join, array(
            'table' => 'clientes', 
            'alias' => 'CL', 
            'type' => 'LEFT',
            'conditions' => array('Factura.cliente_id=CL.id')                
        ));
        
        array_push($arr_join, array(
            'table' => 'estadoalertas', 
            'alias' => 'EA', 
            'type' => 'INNER',
            'conditions' => array('EA.id=Alertaordene.estadoalerta_id')                
        ));
        
        array_push($arr_join, array(
            'table' => 'unidadesmedidas', 
            'alias' => 'UM', 
            'type' => 'LEFT',
            'conditions' => array('Alertaordene.unidadesmedida_id=UM.id')                
        ));
        
        array_push($arr_join, array(
            'table' => 'alertas', 
            'alias' => 'AL', 
            'type' => 'INNER',
            'conditions' => array('Alertaordene.alerta_id=AL.id')                
        ));
        
        $alertasOrdenes = $this->find('all', array(                
            'joins' => $arr_join, 
            'fields' => array(
                'EA.id',
                'EA.descripcion',
                'Alertaordene.*',
                'US.id',
                'US.nombre',
                'CL.id',
                'CL.nit',
                'CL.nombre',
                'CL.direccion',
                'CL.celular',
                'CL.cumpleanios',
                'UM.*',
                'AL.*'
            ),                             
            'conditions' => $filtros,
            'recursive' => '-1',
            ));            
        
        return $alertasOrdenes;            
    }
    public function obtenerInfoAlertaFacturaGenerate($filtros){

        $arr_join = array(); 
       
        array_push($arr_join, array(
            'table' => 'clientes', 
            'alias' => 'CL', 
            'type' => 'LEFT',
            'conditions' => array('Factura.cliente_id=CL.id')                
        ));
        
        $alertasOrdenes = $this->find('all', array(                
            'joins' => $arr_join, 
            'fields' => array(
                'CL.id',
                'CL.nit',
                'CL.nombre',
                'CL.direccion',
                'CL.celular',
                'CL.cumpleanios',
            ),                             
            'conditions' => $filtros,
            'recursive' => '-1', 
            ));            
        
        return $alertasOrdenes;            
    }
    

    public function actualizarEstadoFacturaEliminar($id)
    {
        $data = array();
        $factura = new Factura();

        $data['id'] = $id;
        $data['eliminar'] = "1";

        if ($factura->save($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function actualizarInfoDian( $facturaId, $statusCode, $cufe, $QR) {

        $data = array();
        $factura = new Factura();

        $data['id'] = $facturaId;
        $data['dianstatuscode'] = $statusCode;
        $data['diancufe'] = $cufe;
        $data['dianQRStr'] = $QR;

        if ($factura->save($data)) {
            return true;
        } else {
            return false;
        }        
    }

    /**
     * Actualiza la factura con la información de la Dian
     */
    public function actualizarNCInfoDian( $facturaId, $statusCode, $cufe, $QR, $consecutivo, $prefijo) {

        $data = array();
        $factura = new Factura();

        $data['id'] = $facturaId;
        $data['dianstatuscode'] = $statusCode;
        $data['diancude'] = $cufe;
        $data['dianNCQRStr'] = $QR;
        $data['prefijo'] = $prefijo;
        $data['consecutivonc'] = $consecutivo;

        if ($factura->save($data)) {
            return true;
        } else {
            return false;
        }        
    }

        /**
     * Obtiene los valores base para la factura
     */
    public function obtenerValorBaseProducto( $valoresIniciales ) {

        // --- 1. Conversión y Preparación de Variables ---    
        $precioVentaUnitario = (float)($valoresIniciales['precioVenta'] * $valoresIniciales['unidadesProd']) ?: 0;
        $descuentoPorcentaje = (float)($valoresIniciales['porcentajeDesc']) ?: 0; // Usado si se aplica por %

        // // Convertir tasas de porcentaje (%) a decimal (e.g., 19 -> 0.19)
        $prcIVA = ((float)($valoresIniciales['prcIVA']) ?: 0);
        $prcINC = ((float)($valoresIniciales['prcINC']) ?: 0);

        
        // // Factor para discriminar los impuestos (1 + tIVA + tINC)
        $factorRetiro = 1 + $prcIVA + $prcINC;

        // // --- 2. Descuento de Impuestos (Cálculo Inverso para Base Inicial) ---
        // // A. Valor Base Inicial (B): Precio Venta Unitario / Factor de Retiro
        $valorBaseInicial = $precioVentaUnitario / $factorRetiro; // Base sin impuestos ni descuento

        // // --- 3. Aplicar Descuento a la Base Inicial (B) para obtener la Nueva Base (B') ---
        $valorBaseNueva = 0;
        
        // // Asumimos que el descuento es aplicado como porcentaje
        $descuento = 0;
        if ($descuentoPorcentaje > 0) {
        //     // B. Aplicar descuento por porcentaje a la Base Inicial
            $factorDescuento = 1 - ($descuentoPorcentaje / 100);
            $valorBaseNueva = $valorBaseInicial * $factorDescuento;
            $descuento = $valorBaseInicial - $valorBaseNueva;
        } 
        // // Si no hay descuento
        else {
            $valorBaseNueva = $valorBaseInicial;
        }
        
        // // Asegurar que la nueva base no sea negativa
        $valorBaseNueva = max(0, $valorBaseNueva);
        
        // // --- 4. Recalcular Impuestos sobre la Nueva Base (B') ---
        // // C. Impuestos Totales (aplicados a la Nueva Base)
        $nuevoValIVA = $valorBaseNueva * $prcIVA;
        $nuevoValINC = $valorBaseNueva * $prcINC;
        
        // // D. Valor Total Unitario Final (Suma de verificación: B' + nuevo IVA + nuevo INC)
        $precioFinalUnitario = $valorBaseNueva + $nuevoValIVA + $nuevoValINC;

        // // --- 5. Retorno (Redondeo para valores contables) ---
        // // Redondeamos todos los resultados finales a 2 decimales.
        return [
            "valorBaseUnitario"   => round($valorBaseNueva, 2),
            "valorIVA"            => round($nuevoValIVA, 2),
            "valorINC"            => round($nuevoValINC, 2),
            "varorINCBolsa"       => (float)($valoresIniciales['valBolsa'] * $valoresIniciales['unidadesProd']),
            "precioUnitarioFinal" => round($precioFinalUnitario, 2),
            "descuento"           => round($descuento, 2)
        ];

    }

    /**
     * Obtiene la información de la factura para impresion rápida
     */
    public function obtenerInfoFacturaImpresionRapida( $facturaId ) {
        $arr_join = array(); 
       
        array_push($arr_join, array(
            'table' => 'empresas', 
            'alias' => 'E', 
            'type' => 'INNER',
            'conditions' => array('Factura.empresa_id=E.id')                
        ));
       
        array_push($arr_join, array(
            'table' => 'resolucionfacturas', 
            'alias' => 'RF', 
            'type' => 'INNER',
            'conditions' => array('E.id=RF.empresa_id')                
        ));
       
        array_push($arr_join, array(
            'table' => 'ciudadesmiggos', 
            'alias' => 'CIUM', 
            'type' => 'INNER',
            'conditions' => array('E.ciudade_id=CIUM.id')                
        ));
       
        array_push($arr_join, array(
            'table' => 'departamentosmiggos', 
            'alias' => 'DEPM', 
            'type' => 'INNER',
            'conditions' => array('CIUM.departamento_id=DEPM.id')                
        ));
       
        array_push($arr_join, array(
            'table' => 'clientes', 
            'alias' => 'CL', 
            'type' => 'LEFT',
            'conditions' => array('Factura.cliente_id=CL.id')                
        ));
       
        array_push($arr_join, array(
            'table' => 'usuarios', 
            'alias' => 'U', 
            'type' => 'INNER',
            'conditions' => array('Factura.usuario_id=U.id')                
        ));

        $infoGeneralFactura = $this->find('all', array(                
            'joins' => $arr_join, 
            'fields' => array(
                'E.*',
                'RF.*',
                'CIUM.*',
                'DEPM.*',
                'CL.*',
                'U.*',
            ),                             
            'conditions' => array('Factura.id' => $facturaId),
            'recursive' => '-1', 
            ));            
        
        return $infoGeneralFactura;
    }

    /**
     * Obtiene la última factura de un cliente
     */
    public function obtenerUltimaFacturaCliente( $clienteId, $usuarioId ) {

        return $this->find('first', array(
            'conditions' => array(
                'Factura.cliente_id' => $clienteId,
                'Factura.usuario_id' => $usuarioId
            ),
            'recursive' => '-1',
            'order' => 'Factura.id desc',
        ));

    }

    // ─────────────────────────────────────────────────────────────────────────
    // RETENCIONES EN VENTAS (solo lectura: calcula, no guarda)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Calcula las retenciones que el cliente le practica a la empresa en una
     * factura. No guarda nada: el cálculo lo hace MotorRetenciones con los datos
     * cargados aquí.
     *
     * La base de cada línea sale de obtenerValorBaseProducto(), la misma función que
     * arma las líneas de la factura electrónica: subtotal antes de IVA, después del
     * descuento, sin INC ni bolsa.
     *
     * @param  int $facturaId
     * @return array resultado de MotorRetenciones::calcular(), o con 'motivo' si
     *               la factura no existe
     */
    public function calcularRetenciones($facturaId) {
        App::uses('MotorRetenciones', 'Lib');
        $motor = new MotorRetenciones();

        $factura = $this->find('first', array(
            'conditions' => array('Factura.id' => (int) $facturaId),
            'fields'     => array('Factura.id', 'Factura.factura', 'Factura.cliente_id', 'Factura.empresa_id', 'Factura.created'),
            'recursive'  => -1,
        ));
        if (empty($factura)) {
            $res = $motor->calcular(array());
            $res['motivo'] = 'La factura no existe.';
            return $res;
        }
        $f = $factura['Factura'];
        $fecha = !empty($f['created']) ? substr($f['created'], 0, 10) : null;

        return $this->_ejecutarMotorRetenciones(
            (int) $f['factura'] === 1,
            $fecha,
            $f['cliente_id'],
            $f['empresa_id'],
            $this->_lineasParaRetenciones($f['id'])
        );
    }

    /**
     * Total de la factura con todos los impuestos, calculado con las mismas líneas
     * que usa el motor de retenciones (base + IVA + INC + bolsa).
     *
     * @param  int $facturaId
     * @return float
     */
    public function totalConImpuestos($facturaId) {
        $total = 0.0;
        foreach ($this->_lineasParaRetenciones((int) $facturaId) as $l) {
            $total += $l['total'];
        }
        return round($total, 2);
    }

    /**
     * Calcula (sin guardar) las retenciones de una prefactura que se está armando,
     * para mostrarlas como información antes de facturar. Usa la fecha de hoy,
     * que será la fecha de la factura.
     *
     * @param  int  $prefacturaId
     * @param  int  $clienteId     cliente seleccionado en pantalla (null si no hay)
     * @param  int  $empresaId     empresa (sede) que factura
     * @param  bool $esElectronica
     * @return array resultado de MotorRetenciones::calcular()
     */
    public function calcularRetencionesPrefactura($prefacturaId, $clienteId, $empresaId, $esElectronica) {
        return $this->_ejecutarMotorRetenciones(
            (bool) $esElectronica,
            date('Y-m-d'),
            $clienteId,
            $empresaId,
            $this->_lineasPrefacturaParaRetenciones($prefacturaId)
        );
    }

    /**
     * Carga los datos de la empresa, del cliente, la UVT y las reglas, y llama al
     * motor. Es el único punto que arma la entrada del motor (factura y prefactura).
     */
    protected function _ejecutarMotorRetenciones($esElectronica, $fecha, $clienteId, $empresaId, array $lineas) {
        App::uses('MotorRetenciones', 'Lib');
        $motor = new MotorRetenciones();

        $totalFactura = 0.0;
        foreach ($lineas as $l) {
            $totalFactura += $l['total'];
        }

        $Configuraciondato = ClassRegistry::init('Configuraciondato');
        $Retencionventa = ClassRegistry::init('Retencionventa');

        return $motor->calcular(array(
            'es_electronica' => $esElectronica,
            'fecha'          => $fecha,
            'total_factura'  => $totalFactura,
            'cliente'        => $this->_clienteParaRetenciones($clienteId),
            'empresa'        => $this->_empresaParaRetenciones($empresaId),
            'lineas'         => $lineas,
            'uvt'            => $fecha ? $Configuraciondato->obtenerUvtAnio((int) substr($fecha, 0, 4)) : null,
            'filas'          => $Retencionventa->obtenerReglasParaMotor($empresaId),
        ));
    }

    /**
     * Una línea (de factura o de prefactura) en el formato del motor. La base sale
     * de obtenerValorBaseProducto(), igual que en la facturación electrónica.
     */
    protected function _normalizarLineaRetencion(array $d, $servicio) {
        $valores = $this->obtenerValorBaseProducto(array(
            'unidadesProd'   => (float) $d['cantidad'],
            'precioVenta'    => (float) $d['costoventa'],
            'porcentajeDesc' => (float) $d['porcentaje'],
            'prcIVA'         => (float) $d['impuesto'] / 100,
            'prcINC'         => (float) $d['impoconsumo'] / 100,
            'valBolsa'       => (float) $d['incbolsa'],
        ));
        return array(
            'detalle_id' => (int) $d['id'],
            'tipo'       => ((int) $servicio === 1) ? 'SERVICIO' : 'PRODUCTO',
            'subtotal'   => $valores['valorBaseUnitario'],
            'iva'        => $valores['valorIVA'],
            // Total de la línea con todos los impuestos (para el neto a pagar)
            'total'      => $valores['valorBaseUnitario'] + $valores['valorIVA'] + $valores['valorINC'] + $valores['varorINCBolsa'],
        );
    }

    /**
     * Líneas de la prefactura (producto vía cargueinventarios) con su tipo, subtotal e IVA.
     */
    protected function _lineasPrefacturaParaRetenciones($prefacturaId) {
        $Prefacturasdetalle = ClassRegistry::init('Prefacturasdetalle');
        $detalle = $Prefacturasdetalle->find('all', array(
            'joins' => array(
                array('table' => 'cargueinventarios', 'alias' => 'CI', 'type' => 'INNER', 'conditions' => array('CI.id = Prefacturasdetalle.cargueinventario_id')),
                array('table' => 'productos', 'alias' => 'P', 'type' => 'INNER', 'conditions' => array('P.id = CI.producto_id')),
                array('table' => 'categorias', 'alias' => 'C', 'type' => 'LEFT', 'conditions' => array('C.id = P.categoria_id')),
            ),
            'fields' => array(
                'Prefacturasdetalle.id', 'Prefacturasdetalle.cantidad', 'Prefacturasdetalle.costoventa', 'Prefacturasdetalle.porcentaje',
                'Prefacturasdetalle.impuesto', 'Prefacturasdetalle.impoconsumo', 'Prefacturasdetalle.incbolsa', 'C.servicio',
            ),
            'conditions' => array('Prefacturasdetalle.prefactura_id' => (int) $prefacturaId),
            'recursive'  => -1,
        ));

        $lineas = array();
        foreach ($detalle as $d) {
            $lineas[] = $this->_normalizarLineaRetencion($d['Prefacturasdetalle'], $d['C']['servicio']);
        }
        return $lineas;
    }

    /**
     * Calcula y guarda el snapshot de retenciones de una factura recién creada.
     *
     *  - Si la factura ya tiene snapshot, no hace nada: una factura emitida nunca se
     *    recalcula.
     *  - Si no aplican retenciones (no electrónica, sin cliente, cliente sin casillas,
     *    sin UVT...), no escribe nada y la factura queda como hoy.
     *  - Guarda las filas de factura_retenciones y los totales informativos de la
     *    factura en una sola transacción.
     *  - Nunca interrumpe la facturación: si algo falla, deshace solo el snapshot,
     *    lo deja en el log y devuelve false. La factura queda creada sin retenciones.
     *
     * @param  int $facturaId
     * @return bool true si guardó o no había nada que guardar
     */
    public function guardarRetenciones($facturaId) {
        $facturaId = (int) $facturaId;
        $FacturaRetencion = ClassRegistry::init('FacturaRetencion');
        $ds = $this->getDataSource();
        $enTransaccion = false;

        try {
            if ($FacturaRetencion->facturaTieneSnapshot($facturaId)) {
                return true;
            }

            $resultado = $this->calcularRetenciones($facturaId);
            foreach ($resultado['avisos'] as $aviso) {
                CakeLog::write('info', 'Retenciones factura ' . $facturaId . ': ' . $aviso);
            }
            if (empty($resultado['retenciones'])) {
                return true;
            }

            $ahora = date('Y-m-d H:i:s');
            $filas = array();
            foreach ($resultado['retenciones'] as $r) {
                $filas[] = array('FacturaRetencion' => array(
                    'factura_id'       => $facturaId,
                    'impuesto_id'      => $r['impuesto_id'],
                    'tributo_codigo'   => $r['tributo_codigo'],
                    'nombre'           => $r['nombre'],
                    'aplica_a'         => $r['grupo'],
                    'ciudadesmiggo_id' => $r['ciudadesmiggo_id'],
                    'subtotal_grupo'   => sprintf('%.2f', $r['subtotal_grupo']),
                    'base'             => sprintf('%.2f', $r['base']),
                    'tipo_valor'       => $r['tipo_valor'],
                    'tarifa'           => sprintf('%.4f', $r['tarifa']),
                    'base_minima_uvt'  => sprintf('%.2f', $r['base_minima_uvt']),
                    'valor_uvt'        => sprintf('%.2f', $r['valor_uvt']),
                    'valor'            => sprintf('%.2f', $r['valor']),
                    'created'          => $ahora,
                    'modified'         => $ahora,
                ));
            }

            $ds->begin();
            $enTransaccion = true;

            if (!$FacturaRetencion->saveMany($filas, array('validate' => false, 'atomic' => false))) {
                throw new Exception('No se pudieron guardar las filas de factura_retenciones.');
            }
            $actualizada = $this->updateAll(
                array(
                    'Factura.total_retenciones' => sprintf('%.2f', $resultado['total_retenciones']),
                    'Factura.neto_a_pagar'      => sprintf('%.2f', $resultado['neto_a_pagar']),
                ),
                array('Factura.id' => $facturaId)
            );
            if (!$actualizada) {
                throw new Exception('No se pudieron actualizar los totales de retenciones de la factura.');
            }

            $ds->commit();
            return true;
        } catch (Exception $e) {
            if ($enTransaccion) {
                $ds->rollback();
            }
            CakeLog::write('error', 'Retenciones factura ' . $facturaId . ': no se guardó el snapshot. ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Líneas de la factura con su tipo (PRODUCTO / SERVICIO), subtotal e IVA.
     * Consulta propia (sin seriales ni motos) para que cada línea aparezca una sola vez.
     */
    protected function _lineasParaRetenciones($facturaId) {
        $Facturasdetalle = ClassRegistry::init('Facturasdetalle');
        $detalle = $Facturasdetalle->find('all', array(
            'joins' => array(
                array('table' => 'productos', 'alias' => 'P', 'type' => 'INNER', 'conditions' => array('P.id = Facturasdetalle.producto_id')),
                array('table' => 'categorias', 'alias' => 'C', 'type' => 'LEFT', 'conditions' => array('C.id = P.categoria_id')),
            ),
            'fields' => array(
                'Facturasdetalle.id', 'Facturasdetalle.cantidad', 'Facturasdetalle.costoventa', 'Facturasdetalle.porcentaje',
                'Facturasdetalle.impuesto', 'Facturasdetalle.impoconsumo', 'Facturasdetalle.incbolsa', 'C.servicio',
            ),
            'conditions' => array('Facturasdetalle.factura_id' => (int) $facturaId),
            'recursive'  => -1,
        ));

        $lineas = array();
        foreach ($detalle as $d) {
            // Mismos datos de entrada que generarInfoPagoGeneral (facturación electrónica)
            $lineas[] = $this->_normalizarLineaRetencion($d['Facturasdetalle'], $d['C']['servicio']);
        }
        return $lineas;
    }

    /** Datos tributarios del cliente para el motor, o null si no hay cliente */
    protected function _clienteParaRetenciones($clienteId) {
        if (empty($clienteId)) {
            return null;
        }
        $Cliente = ClassRegistry::init('Cliente');
        $c = $Cliente->find('first', array(
            'conditions' => array('Cliente.id' => (int) $clienteId),
            'fields'     => array('Cliente.practica_retefuente', 'Cliente.practica_reteiva', 'Cliente.practica_reteica',
                'Cliente.tipo_persona', 'Cliente.ciudadesmiggo_id', 'Ciudadesmiggo.nombre'),
            'recursive'  => 0,
            'contain'    => false,
        ));
        if (empty($c)) {
            return null;
        }
        return array(
            'practica_retefuente' => (int) $c['Cliente']['practica_retefuente'],
            'practica_reteiva'    => (int) $c['Cliente']['practica_reteiva'],
            'practica_reteica'    => (int) $c['Cliente']['practica_reteica'],
            'tipo_persona'        => $c['Cliente']['tipo_persona'],
            'ciudad_id'           => $c['Cliente']['ciudadesmiggo_id'],
            'ciudad_nombre'       => isset($c['Ciudadesmiggo']['nombre']) ? $c['Ciudadesmiggo']['nombre'] : null,
        );
    }

    /** Perfil tributario de la empresa: declarante y códigos de responsabilidad */
    protected function _empresaParaRetenciones($empresaId) {
        $Empresa = ClassRegistry::init('Empresa');
        $declarante = $Empresa->field('declarante_renta', array('Empresa.id' => (int) $empresaId));
        $codigos = $this->query(
            'SELECT t.codigo FROM empresas_tiposresponsabilidades et
             INNER JOIN tiposresponsabilidades t ON t.id = et.tiposresponsabilidade_id
             WHERE et.empresa_id = ?',
            array((int) $empresaId)
        );
        $responsabilidades = array();
        foreach ($codigos as $c) {
            $responsabilidades[] = $c['t']['codigo'];
        }
        return array(
            'declarante_renta'  => (int) $declarante === 1,
            'responsabilidades' => $responsabilidades,
        );
    }

}
