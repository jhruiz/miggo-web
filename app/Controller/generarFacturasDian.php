<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Factura;
use App\Models\Empresa;
use App\Models\Deposito;
use App\Models\Cliente;
use App\Models\FacturaCuentaValore;
use App\Models\Cuentascliente;
use App\Models\Facturasdetalle;
use DateTime; 

class sincronizarfacturasdian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sincronizarfacturas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proceso que toma las facturas de miggo y las sincroniza con la Dian';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

      // Obtiene las conexiones configuradas para el multi tenant
      $multiTenantConnection = $this->obtenerConexiones();
     
      foreach( $multiTenantConnection as $conn ) {
     
        // Obtienen las empresas configuradas para sincronizar facturas con la DIAN
        $empresas = $this->obtenerEmpresasConfiguradas( $conn );
        foreach ($empresas as $empresa) {
            $this->procesarEmpresa($empresa, $conn );
        }
      } 
     
    }

    /**
     * Procesar la información de la empresas que cuente con resolución de facturación configurada
     */
    private function procesarEmpresa($empresa, $conn) {
      // Obtiene la información de la resolución de la empresa
      $infoResolucion = $this->obtenerInfoResolucion($empresa['id'], $conn);
      
      // Valida que la empresa tenga configurada la información de la resolución
      if (isset($infoResolucion['id'])) {
          // Llama a función general que genera todos los llamados para la creación de la factura para la DIAN
          $this->generarFacturasDian($infoResolucion, $empresa['tokendian'], $empresa['typedocument'], $empresa['municipio_id'], $empresa['nit'], $conn);
      }
  }

  //**//**//**//**//**//**//**//** INICIO CONSULTAS //**//**//**//**//**//**//**//**//**

    /**
     * Obtiene las empresas configuradas para gestión de facturación electrónica
     */
    public function obtenerEmpresasConfiguradas( $conn ){

      $syncDian = config('custom.SYNC_DIAN');

      return (new Empresa)->setConnection($conn)->where('syncdian', $syncDian)->get();

    }

    /**
     * Obtiene la información de las facturas que se van enviar hacia la Dian
     */
    public function obtenerFacturas( $empresaId, $conn ) {

      return (new Factura)->setConnection($conn)->where('empresa_id', $empresaId)
        ->where('factura', 1)
        ->where('eliminar', 0)
        ->whereNull('dianestado_id')
        ->where('created', '>', now()->startOfDay())
        ->take(30)
        ->get();

    }

    /**
     * Obtiene la información de la resolución
     */
    public function obtenerInfoResolucion( $empresaId, $conn ) {

      return (new Deposito)->setConnection($conn)->where('empresa_id', $empresaId)
                          ->where('resolucionfacturacion', '<>', '')
                          ->first();

    } 

    /**
     * Obtiene la información del cliente de la factura
     */
    public function obtenerInfoCliente( $clienteId, $conn ) {
      
      return (new Cliente)->setConnection($conn)->where('id', $clienteId)->get();

    }

    /**
     * Obtiene la información del tipo de pago débito
     */
    public function obtenerInfoTipoPagoEfectivo( $facturaId, $conn ) {

      return (new FacturaCuentaValore)->setConnection($conn)->where('factura_id', $facturaId)
                                      ->join('tipopagos', 'tipopagos.id', '=', 'factura_cuenta_valores.tipopago_id')  
                                      ->get();

    }

    /**
     * Obtiene la información del tipo de pago a crédito
     */
    public function obtenerInfoTipoPagoCredito( $facturaId, $conn ) {

      return (new Cuentascliente)->setConnection($conn)->where('factura_id', $facturaId)->get();

    }

    /**
     * Obtiene la información del detalle de la factura
     */
    public function obtenerInfoFacturaDetalles( $facturaId, $conn ) {

      return (new Facturasdetalle)->setConnection($conn)->where('facturasdetalles.factura_id', $facturaId)
                              ->join('productos', 'productos.id', '=' ,'facturasdetalles.producto_id')
                              ->get();
    }

    /**
     * Actualiza el estado de la factura a procesando, sincronizada o error
     */
    private function actualizarEstadoFactura( $factura_id, $estado_id, $conn ) {

      (new Factura)->setConnection($conn)->where('id', $factura_id)
      ->update(['dianestado_id' => $estado_id]);

    }

    /**
     * Actualiza el mensaje de error en la factura
     */
    private function actualizarMensajeFactura( $factura_id,  $mensaje, $conn ) {

      (new Factura)->setConnection($conn)->where('id', $factura_id)
      ->update(['mensajedian' => $mensaje]);
    
    }

  //**//**//**//**//**//**//**//** FIN CONSULTAS //**//**//**//**//**//**//**//**//**

  //**//**//**//**//**//**//**//** INICIO FUNCIONES APOYO //**//**//**//**//**//**//**//**//**

    /**
   * Se crea y retorna un arreglo con todas las conexiones configuradas
   */
  private function obtenerConexiones() {

    return array(
      'mysql_prueba'
    );

  }

  /**
   * Obtiene el número de identificación del cliente
   */
  public function obtenerIdentificacion( $identificacion ) {
    if (strpos($identificacion, '-') !== false) {
      $identificacion = explode('-', $identificacion);
      return str_replace(" ", "", $identificacion['0']);
    } else {
        return str_replace(" ", "", $identificacion);
    } 
  }

  /**
   * Obtiene el tipo de organización basado en su tipo de identificación
   */
  public function obtenerOrganizacion( $tipoIdentificacion ) {

    if (in_array($tipoIdentificacion, [1, 2, 3, 4, 5, 7, 8])) {
        return config('custom.TYPE_ORGANIZATION_NAT');
    }
    
    if (in_array($tipoIdentificacion, [6, 9, 10, 11, 12])) {
        return config('custom.TYPE_ORGANIZATION_JUR');
    }

    return null;

}

  /**
   * Suma una cantidad de días a una fecha específica
   */
  public function sumarDiasFecha( $fecha, $dias ) {

    $fecha = new DateTime($fecha);

    $fecha->modify("+$dias days");

    return $fecha->format('Y-m-d');

}

  /**
   * Retorna un arreglo con el impuesto de un producto
   */
  public function obtenerImpuestoPorProducto( $valIva, $valSinImp, $impuesto){

    return [
      'tax_id' => config('custom.TAX'),
      'tax_amount' => $valIva,
      'taxable_amount' => $valSinImp,
      'percent' => number_format($impuesto, 2)
    ];
  }

  /**
   * Retorna un arreglo con los totales de venta con y sin iva
   */
  public function obtenerTotalesVenta( $sumValSinIva, $sumValConIva ) {

    return [
    'line_extension_amount' => number_format(round($sumValSinIva, 2), 2, '.', ''),
    'tax_exclusive_amount' => number_format(round($sumValSinIva, 2), 2, '.', ''),
    'tax_inclusive_amount' => number_format(round($sumValConIva, 2), 2, '.', ''),
    'payable_amount' => number_format(round($sumValConIva, 2), 2, '.', '')
    ];
  }

  /**
   * Calcular los valores de impuestos y productos
   */
  private function calcularValores( $costoTotal, $descuento, $impuesto ) {

    if ( $impuesto > 0 ) {

        $impuesto /= 100;
        $valSinImp = round( ( $costoTotal - $descuento ) / ( 1 + $impuesto ), 2 );
        $valIva = round( ( $costoTotal - $descuento ) - $valSinImp, 2 );
    
    } else {
        $valSinImp = round( ( $costoTotal - $descuento ), 2 );
        $valIva = number_format( 0, 2 );
    }

    return [number_format($valSinImp, 2, '.', ''), number_format($valIva, 2, '.', '')];
  }

  /**
   * Genera la información de las lineas de la factura
   */
  public function obtenerDetalleLineas($val, $arrImpuestos)
  {
      // Inicializa el array de productos con los valores proporcionados
      $arrProductos = [
          'unit_measure_id' => config('custom.UNIT_MEASURE'),
          'invoiced_quantity' => $val['cantidad'],
          'line_extension_amount' => $arrImpuestos['taxable_amount'],
          'free_of_charge_indicator' => config('custom.FREE_OF_CHARGE_INDICATOR'),
          'tax_totals' => [$arrImpuestos],
          'description' => $val['descripcion'],
          'code' => $val['codigo'],
          'type_item_identification_id' => config('custom.TYPE_ITEM_IDENTIFICATION'),
          'price_amount' => $val['costoventa'],
          'base_quantity' => $val['cantidad']
      ];
  
      // Verifica si hay descuento y lo añade al array de productos si es necesario
      if ($val['descuento'] > 0) {
          $arrProductos['allowance_charges']['0'] = [
              "discount_id" => config('custom.ALLOWANCE_CHARGES_DISCOUNT'),
              "charge_indicator" => config('custom.ALLOWANCE_CHARGES_INDICATOR'),
              "allowance_charge_reason" => config('custom.ALLOWANCE_CHARGES_REASON'),
              "amount" => $val['descuento'],
              "base_amount" => $val['costototal']
          ];
      }
  
      return $arrProductos;
  }

  /**
   * Obtiene las cabeceras necesarias para consumir el servicio de sincronizar las factuas con la DIAN
   */
  private function obtenerCabeceras( $token ){

    return [
      'Content-Type' => 'application/json',
      'Accept' => 'application/json',
      'Authorization' => 'Bearer ' . $token
      ];

  }

  /**
   * Valida si todos los parametros recibidos son un array
   */
  function validateArrays(...$arrays){
      foreach ($arrays as $array) {
          if (!is_array($array) || empty($array)) {
              return false;
          }
      }
      return true;
  }
  

  //**//**//**//**//**//**//**//** FIN FUNCIONES APOYO //**//**//**//**//**//**//**//**//**


  //**//**//**//**//**//**//**//** INICIO GENERACION FACTURAS //**//**//**//**//**//**//**//**//**

    /**
     * Genera la información de la resolución de la factura
     */
    public function generarInfoResolucion( $infoResolucion, $factura, $typeDocument ) {

      //Se obtiene la fecha de la factura
      $date = date_create($factura['created']);

      return [
        "number" => $factura['consecutivodian'],
        "prefix" => $infoResolucion['prefijo'],
        "type_document_id" =>  $typeDocument,
        "date" =>  date_format($date, 'Y-m-d'),
        "time" =>  date_format($date, 'H:i:s'),
        "resolution_number" =>  $infoResolucion['resolucionfacturacion'],
        "notes" => $factura['observacion']
      ];

    }

    /**
     * Genera la información del cliente de la factura
     */
    public function generarInfoCliente( $cliente_id, $municipio_id, $conn ) {

      $infoCliente = $this->obtenerInfoCliente( $cliente_id, $conn );

      if ( !isset( $infoCliente['0'] ) ) { 
          return $this->formatearInfoCliente([
              'nit' => '1234567890',
              'nombre' => 'Cliente anónimo',
              'celular' => '3101234567',
              'direccion' => 'Calle 1 # 2 - 3',
              'email' => 'noemail@hotmail.com',
              'tipoidentificacione_id' => '3'
          ], $municipio_id);
      }
  
      return $this->formatearInfoCliente($infoCliente[0], $municipio_id);
  }
  
  /**
   * Genera la información del cliente siempre y cuando se encuentre relacionado 
   * a la factura en la base de datos
   */
  private function formatearInfoCliente($infoCliente, $municipio_id) {
  
        $typeRegimen = $this->obtenerOrganizacion($infoCliente['tipoidentificacione_id']);
            
        // Manejar el caso en que el cliente es una organización (tipoRegimen = 1)
        $identification = $infoCliente['nit'] ?? '1234567890';
        $identification = $this->obtenerIdentificacion($identification);
          
        if ($typeRegimen == '1' && strlen($identification) > 9) {
            $identification = substr($identification, 0, 9);
        }
        
        $dv = $typeRegimen == '1' ? $this->calcularDigitoVerificacion($identification) : null;
        
        // Definir un array de cliente con valores por defecto
        $cliente = [
            "identification_number" => $identification,
            "dv" => $dv,
            "name" => !empty($infoCliente['nombre']) ? $infoCliente['nombre'] : 'Cliente anónimo',
            "phone" => !empty($infoCliente['celular']) ? $infoCliente['celular'] : '3101234567',
            "address" => !empty($infoCliente['direccion']) ? $infoCliente['direccion'] : 'Calle 1 # 2 - 3',
            "email" => !empty($infoCliente['email']) ? $infoCliente['email'] : 'noemail@hotmail.com',
            "merchant_registration" => config('custom.MERCHANT_REGISTRATION'),
            "type_document_identification_id" => $infoCliente['tipoidentificacione_id'],
            "type_organization_id" => $typeRegimen,
            "municipality_id" => $municipio_id,
            "type_regime_id" => $typeRegimen
        ];
    
        // Si el cliente no es una organización, removemos el campo 'dv'
        if ($typeRegimen != '1') {
            unset($cliente['dv']);
        }
    
        return ['customer' => $cliente];

  }
    
  /**
   * Calcula el digito de verificacion de un nit 
   */
  private function calcularDigitoVerificacion($nit)  {

        // Factores predefinidos para la multiplicación
        $factors = [3, 7, 13, 17, 19, 23, 29, 37, 41];
        
        // Convertir el NIT en una cadena y asegurarse de que sea solo dígitos
        $nit = (string) preg_replace('/\D/', '', $nit);

        // Inicializar la suma
        $sum = 0;
        
        // Longitud del NIT
        $length = strlen($nit);

        // Recorrer cada dígito del NIT, multiplicarlo por el factor correspondiente y sumarlo
        for ($i = 0; $i < $length; $i++) {
            $sum += $nit[$i] * $factors[$length - $i - 1];
        }

        // Obtener el residuo de la división de la suma por 11
        $remainder = $sum % 11;

        // Determinar el dígito de verificación
        if ($remainder == 0 || $remainder == 1) {
            return $remainder;
        } else {
            return 11 - $remainder;
        }
  }


    /**
     * Genera la información del tipo de pago de la factura
     */
    public function generarInfoTipoPago( $facturaId, $clienteId, $conn ) {

      $infoTipoPago = $this->obtenerInfoTipoPagoEfectivo( $facturaId, $conn );

      if( isset( $infoTipoPago['0'] ) ){

        return [
          "payment_form" => [
            "payment_form_id" => config('custom.PAYMENT_FORM_EF'),
            "payment_method_id" => $infoTipoPago['0']['transferencia'] ? config('custom.PAYMENT_METHOD_TRANSF') : config('custom.PAYMENT_METHOD_EF')
          ]
        ];

      } else {
        
        $infoTipoPago = $this->obtenerInfoTipoPagoCredito( $facturaId, $conn );
        
        if( isset( $infoTipoPago['0'] ) ) {

          $infoCliente = $this->obtenerInfoCliente( $clienteId, $conn );

          $duration_measure = isset($infoCliente[0]) ? ($infoCliente[0]['diascredito'] ?? config('custom.DURATION_MEASURE')) : config('custom.DURATION_MEASURE');

          $date = date_create($infoTipoPago['0']['created']);

          return [
            "payment_form" => [
              "payment_form_id" => config('custom.PAYMENT_FORM_CR'),
              "payment_method_id" => config('custom.PAYMENT_METHOD_CR'),
              "payment_due_date" => $this->sumarDiasFecha(date_format($date, 'Y-m-d'), $duration_measure),
              "duration_measure" => $duration_measure
            ]
          ];

        }

      }

      return [
          "payment_form_id" => config('custom.PAYMENT_FORM_EF'),
          "payment_method_id" => config('custom.PAYMENT_FORM_IND')
      ];

    }

    /**
     * Genera la información general de la factura
     */
    public function generarInfoPagoGeneral( $facturaId, $conn ) {

      $infoFacturaDetalle = $this->obtenerInfoFacturaDetalles( $facturaId, $conn );

      if ( !isset( $infoFacturaDetalle['0'] ) ) {
        return false;
      }
      
      $sumValSinIva = 0;
      $sumValConIva = 0;
      $arrImpuestos = [];
      $arrLineas = [];

      foreach ( $infoFacturaDetalle as $val ) {

        $costoTotal = $val['costototal'];
        $descuento = $val['descuento'];
        $impuesto = $val['impuesto'];

        list($valSinImp, $valIva) = $this->calcularValores($costoTotal, $descuento, $impuesto);

        // Suma de valores con y sin IVA
        $sumValSinIva += $valSinImp;
        $sumValConIva += round( ( $costoTotal - $descuento ), 2 );

        // Información de los impuestos por cada producto
        $arrImpuestos[] = $this->obtenerImpuestoPorProducto( $valIva, $valSinImp, $val['impuesto'] );

        // Información detallada de las lineas
        $arrLineas[] = $this->obtenerDetalleLineas( $val, $arrImpuestos[count($arrImpuestos) - 1] );
        
      }

      // Información de los totales de la venta con y sin iva
      $arrTotales = $this->obtenerTotalesVenta( $sumValSinIva, $sumValConIva );

      return ['legal_monetary_totals' => $arrTotales, 'tax_totals' => $arrImpuestos, 'invoice_lines' => $arrLineas];

    }

    /**
     * Consumir el servicio de facturación de la DIAN
     */
    private function sincronizarDian( $body, $token, $factura_id, $conn ) {

      $client = new Client();
      $headers = $this->obtenerCabeceras($token);
  
      try {
          $response = $client->request('POST', config('custom.API_DIAN') . 'ubl2.1/invoice', [
              'body' => $body,
              'headers' => $headers
          ]);
  
          $resp = json_decode($response->getBody()->getContents());
  
          // Valida si se obtiene un error al enviar a la DIAN  
          if (isset($resp->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->IsValid) &&
              $resp->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->IsValid == 'false') {
  
              $mensaje = $resp->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->ErrorMessage->string;
  
              $this->actualizarEstadoFactura($factura_id, config('custom.DIAN_ESTADO_ERROR'), $conn);
              $this->actualizarMensajeFactura($factura_id, $mensaje, $conn);
              return;
          }
  
          $this->actualizarEstadoFactura($factura_id, config('custom.DIAN_ESTADO_SINCRONIZADA'), $conn);
      } catch (RequestException $e) {
          // Maneja errores de solicitudes HTTP
          $this->actualizarEstadoFactura($factura_id, config('custom.DIAN_ESTADO_ERROR'), $conn);
          $this->actualizarMensajeFactura($factura_id, $e->getMessage(), $conn);
      } catch (GuzzleException $e) {
          // Maneja otros errores de Guzzle
          $this->actualizarEstadoFactura($factura_id, config('custom.DIAN_ESTADO_ERROR'), $conn);
          $this->actualizarMensajeFactura($factura_id, $e->getMessage(), $conn);
      } catch (\Exception $e) {
          // Maneja cualquier otro tipo de excepción
          $this->actualizarEstadoFactura($factura_id, config('custom.DIAN_ESTADO_ERROR'), $conn);
          $this->actualizarMensajeFactura($factura_id, $e->getMessage(), $conn);
      }
        
    }

    /**
     * Envía el correo de la factura al cliente
     */
    private function enviarCorreoFactura( $nitEmpresa, $prefijo, $consecutivodian, $token ) {
      
      $client = new Client();
      $headers = $this->obtenerCabeceras($token);
      
      $body = array(
        'company_idnumber' => $this->obtenerIdentificacion( $nitEmpresa ),
        'prefix' => $prefijo,
        'number' => $consecutivodian
      );

      $response = $client->request('POST', config('custom.API_DIAN') . 'send-email-customer', [
        'body' => json_encode($body),
        'headers' => $headers
      ]);

    }


    /**
     * Genera la información necesaria para enviar las facturas a la Dian
     */
    public function generarFacturasDian( $infoResolucion, $token, $typeDocument, $municipio_id, $nitEmpresa, $conn ) {

      //Se obtiene la información de las facturas
      $facturas = $this->obtenerFacturas( $infoResolucion['empresa_id'], $conn );
      
      if( !isset( $facturas['0'] ) ) {
        return false;
      }

      $prevBalance = ['previous_balance' => config('custom.PREVIOUS_BALANCE')];

      foreach( $facturas as $val ) {
      
        //Actualiza el estado de la factura
        $this->actualizarEstadoFactura( $val['id'], config('custom.DIAN_ESTADO_PROCESANDO'), $conn );

        //Organiza la información de la resolución
        $infoRes = $this->generarInfoResolucion( $infoResolucion, $val, $typeDocument );
        
        //Obtiene la información del cliente de la factura
        $infoCliente = $this->generarInfoCliente( $val['cliente_id'], $municipio_id, $conn );
        
        //Obtiene la información del tipo de pago
        $infoTipoPago = $this->generarInfoTipoPago( $val['id'], $val['cliente_id'], $conn );
        
        //Obtiene los totales generales de la factura
        $infoPagoGeneral = $this->generarInfoPagoGeneral( $val['id'], $conn );

        //Valida que todos los resultados sean un array procesable
        if ($this->validateArrays($infoRes, $infoCliente, $infoTipoPago, $prevBalance, $infoPagoGeneral)) {
          $jsonFactura = json_encode(array_merge($infoRes, $infoCliente, $infoTipoPago, $prevBalance, $infoPagoGeneral));
          $resp = $this->sincronizarDian( $jsonFactura, $token, $val['id'], $conn );

          //Realiza el envío del correo
          $this->enviarCorreoFactura( $nitEmpresa, $infoResolucion['prefijo'], $val['consecutivodian'], $token );
        } else {
          // Actualiza el estado de la factura
          $this->actualizarEstadoFactura( $val['id'], config('custom.DIAN_ESTADO_ERROR'), $conn );
          $this->actualizarMensajeFactura( $val['id'] , config('custom.MENSAJE_ERROR'), $conn );
        }

      }

    }

  //**//**//**//**//**//**//**//** FIN GENERACION FACTURAS //**//**//**//**//**//**//**//**//**


}
