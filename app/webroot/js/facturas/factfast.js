function actualizarInfoProducto(existenciaActual) {
    $('#lbl-stock-dinamico').text(existenciaActual + ' un.')
}

function establecerTipoFactura(tipoFactura) {
    if( tipoFactura === null || tipoFactura === '' || tipoFactura === '0')  {
        $("#doc-dv").prop("checked", true);
        $('#esFacturaDV').val('0');
    } else {
        $("#doc-fe").prop("checked", true);
        $('#esFacturaDV').val('1');
    }
}

function cambiarTipoFactura() {
    const opcTipoFactura = document.querySelector('input[name="doc_selector"]:checked');
    var esFactura = opcTipoFactura.id === 'doc-fe' ? '1' : '0';
    $('#esFacturaDV').val(esFactura);
}

function gestionarPagoExacto() {
    var txtTotalFactura = $('.thTFCIVA').first().text();
    $('#FacturaValorFactura').val(txtTotalFactura);
    calcularLiquidacionCaja();
}

var poblarTablaFacturaVentaRapida = function ( valoresTabla ) {

    // Corrección de la validación y concatenación nativa de JavaScript
    var nombreImagen = (valoresTabla.imgProd && valoresTabla.imgProd !== "") 
        ? "productos/" + valoresTabla.empresaId + "/" + valoresTabla.imgProd 
        : "productos/no-image-placeholder.jpg";

    $('#productosFacturas').append('<tr id="tr_' + valoresTabla.idReg + '">' + 
        // Se inyecta la etiqueta img con una clase de control y el manejador onerror por si el archivo físico no existe
        '<td><div class="pos-table-img-wrapper"><img src="' + $('#url-proyecto').val() + 'img/' + nombreImagen + '" class="pos-table-thumb" onerror="this.src=\'' + $('#url-proyecto').val() + 'img/productos/no-image-placeholder.jpg\';"></div></td>' + 
        '<td>' + valoresTabla.descProd + '(' + valoresTabla.codProd + ')' + '</td>' +                     
        '<td><input type="text" name="cant_' + valoresTabla.idReg + '" class="form-control" id="cant_' + valoresTabla.idReg + '" value="' + valoresTabla.cantProd + '" onblur="actualizarCantidadPrefact(this);">&nbsp;</td>' +
        '<td><input type="text" name="precio_' + valoresTabla.idReg + '" class="form-control numericPrice ttalUnit" id="precio_' + valoresTabla.idReg + '" value="' + valoresTabla.precioventa + '" onblur="actualizarPrecioPrefact(this);">&nbsp;</td>' +
        '<td><input type="text" name="pordtto_' + valoresTabla.idReg + '" class="form-control ttalPorDtto" id="pordtto_' + valoresTabla.idReg + '" value="' + valoresTabla.prcDsc + '" onblur="actualizarPorcentajeDtto(this);">&nbsp;' +
        '<input type="hidden" name="valdtto_' + valoresTabla.idReg + '" class="form-control ttalValDtto numericPrice" id="valdtto_' + valoresTabla.idReg + '" value="' + valoresTabla.vlrDsc + '" readonly>&nbsp;' +
        '<input type="hidden" name="valor_iva_' + valoresTabla.idReg + '" class="form-control valor_iva numericPrice" id="valor_iva_' + valoresTabla.idReg + '" value="' + valoresTabla.valIVA + '" readonly>&nbsp;' +
        '<input type="hidden" name="porc_iva_' + valoresTabla.idReg + '" class="form-control porc_iva numericPrice" id="porc_iva_' + valoresTabla.idReg + '" value="' + valoresTabla.prcIVA + '" readonly>&nbsp;' +
        '<input type="hidden" name="valor_ica_' + valoresTabla.idReg + '" class="form-control valor_ica numericPrice" id="valor_ica_' + valoresTabla.idReg + '" value="' + valoresTabla.valINC + '" readonly>&nbsp;' +
        '<input type="hidden" name="porc_ica_' + valoresTabla.idReg + '" class="form-control porc_ica numericPrice" id="porc_ica_' + valoresTabla.idReg + '" value="' + valoresTabla.prcINC + '" readonly>&nbsp;' +
        '<input type="hidden" name="inc_bolsa_' + valoresTabla.idReg + '" class="form-control inc_bolsa numericPrice" id="inc_bolsa_' + valoresTabla.idReg + '" value="' + valoresTabla.varorINCBolsa + '" readonly>&nbsp;' +
        '<input type="hidden" name="valor_con_iva_' + valoresTabla.idReg + '" class="form-control valor_con_iva numericPrice" id="valor_con_iva_' + valoresTabla.idReg + '" value="' + valoresTabla.valorConIva + '" readonly>&nbsp;</td>' +
        '<td><button type="button" class="btn-pos-delete" id="' + valoresTabla.idReg + '" onclick="eliminarProductoPrefactura(this)"><i class="fa fa-times"></i></button></td></tr>'
    );                                                              
    $('.numericPrice').number(true, 2);

    $("#doc-dv").prop("disabled", true);
    $("#doc-fe").prop("disabled", true);
        
    actualizarInfoProducto(valoresTabla.existenciaactual);
};

function generarObjetoTabla(prefactura, valoresTablaBC) {
    return {
            idReg: prefactura.resp,
            imgProd: prefactura.producto[0].II.url,
            cantProd: '1',
            descProd: prefactura.producto[0].Producto.descripcion,
            codProd: prefactura.producto[0].Producto.codigo,
            precioventa: valoresTablaBC.precioUnitarioFinal,
            valAntesImp: valoresTablaBC.valorBaseUnitario,
            prcDsc: '0',
            vlrDsc: '0',
            prcIVA: prefactura.tasaIvaPorc,
            valIVA: valoresTablaBC.valorIVA,
            prcINC: prefactura.tasaIncPorc,
            valINC: valoresTablaBC.valorINC,
            varorINCBolsa: valoresTablaBC.varorINCBolsa,
            valorConIva: valoresTablaBC.precioUnitarioFinal,
            empresaId: prefactura.producto[0].Cargueinventario.empresa_id,
            existenciaactual: prefactura.producto[0].Cargueinventario.existenciaactual
        };
}

function seleccionarProductoVentaRapida(data) {
    var usuarioId = $('#usuarioId').val();
    var clienteId = $('.id_cliente').val() || $('#FacturaIdcliente').val();
    const opcTipoFactura = document.querySelector('input[name="doc_selector"]:checked');
    var esFactura = opcTipoFactura.id === 'doc-fe' ? '1' : '0';
    var valorBuscador = $('#FacturaProducto').val();    

    $.ajax({
        url: $('#url-proyecto').val() + 'prefacturas/addProductoBarCode',
        data: { usuarioId: usuarioId, descProducto: valorBuscador, clienteId: clienteId, esFactura: esFactura },
        type: "POST",
        success: function(data) {                
            var prefactura = JSON.parse(data);
            
            if (prefactura.valido) {
                var valoresTablaBC = obtenerValoresTablaBC(prefactura);

                var valoresTabla = generarObjetoTabla(prefactura, valoresTablaBC);

                poblarTablaFacturaVentaRapida(valoresTabla);
                $('#prefacturaId').val(prefactura.prefactId);
                $('#FacturaProducto').val("");
                $('#datosProducto').hide().html("");
                
            } else {                        
                $('#FacturaProducto').val("");
                $('#datosProducto').hide().html("");                        
                bootbox.alert(prefactura.mensaje);
            }
            sumarTotales();
        }
    });                
}

var obtenerDatosProductoVentaRapida = function(e) {

    // Captura segura del evento de teclado cross-browser
    var eventoTeclado = e || window.event;
    var teclaPresionada = eventoTeclado.key || eventoTeclado.jsKey;

    var usuarioId = $('#usuarioId').val();
    var clienteId = $('.id_cliente').val() || $('#FacturaIdcliente').val();
    var valorBuscador = $('#FacturaProducto').val();
    const opcTipoFactura = document.querySelector('input[name="doc_selector"]:checked');
    var esFactura = opcTipoFactura.id === 'doc-fe' ? '1' : '0';

    // CASO 1: LECTURA RÁPIDA POR ENTER (Lector de Código de Barras / Selección Directa)
    if (teclaPresionada === 'Enter') {     
        eventoTeclado.preventDefault(); // Evitamos que el formulario se envíe solo
        if(typeof(clienteId) != "undefined" && clienteId != ""){
            $.ajax({
                url: $('#url-proyecto').val() + 'prefacturas/addProductoBarCode',
                data: { usuarioId: usuarioId, descProducto: valorBuscador, clienteId: clienteId, esFactura: esFactura },
                type: "POST",
                success: function(data) {                
                    var prefactura = JSON.parse(data);
                    
                    if (prefactura.valido) {
                        var valoresTablaBC = obtenerValoresTablaBC(prefactura);

                        var valoresTabla = generarObjetoTabla(prefactura, valoresTablaBC);

                        poblarTablaFacturaVentaRapida(valoresTabla);
                        $('#prefacturaId').val(prefactura.prefactId);
                        $('#FacturaProducto').val("");
                        $('#datosProducto').hide().html("");
                        
                    } else {                        
                        $('#FacturaProducto').val("");
                        $('#datosProducto').hide().html("");                        
                        bootbox.alert(prefactura.mensaje);
                    }
                    sumarTotales();
                }
            });   
        }else{
            bootbox.alert('Debe seleccionar un cliente.');
            $('#FacturaProducto').val("");                
        }             
    
    } 
    // CASO 2: LIMPIEZA INMEDIATA CUANDO EL BUSCADOR QUEDA VACÍO
    else if (valorBuscador.length <= 0) {
        $('#datosProducto').hide().html("");        
    } 
    // CASO 3: BÚSQUEDA PREDICTIVA TRADICIONAL MIENTRAS ESCRIBE (A partir de 3 caracteres)
    else {
        if (valorBuscador.length > 3) {
            $.ajax({
                url: $('#url-proyecto').val() + 'cargueinventarios/ajaxProductosVenta',
                data: { usuarioId: usuarioId, descProducto: valorBuscador },
                type: "POST",
                success: function(data) {
                    var producto = JSON.parse(data);
                    var uls = "";
                    for (var i = 0; i < producto.resp.length; i++) {
                        if (parseFloat(producto.resp[i].Cargueinventario.existenciaactual) >= parseFloat(1)) {
                            uls += "<a href='#' class='list-group-item list-group-item-info' ";
                            uls += "name='" + producto.resp[i].Producto.id + "' ";
                            uls += "id='" + producto.resp[i].Cargueinventario.id + "' ";
                            uls += "onClick='seleccionarProductoVentaRapida(this)'>" + producto.resp[i].Producto.descripcion;
                            uls += " - " + producto.resp[i].Producto.codigo;
                            uls += " Ref (" + producto.resp[i].Producto.referencia + ") bod. ";
                            uls += producto.resp[i].Deposito.descripcion + " - prov. ";
                            uls += producto.resp[i].Proveedore.nombre;
                            uls += "</a>";
                        }
                    }
                    $('#datosProducto').show().html(uls);
                }
            });                 
        } else {
            $('#datosProducto').hide().html("");                
        }
    }         
};

/**
 * Función puente auxiliar para los clics directos sobre el Top 5 de la cabecera
 */
function agregarProductoPorClick(codigoProducto) {
    $('#FacturaProducto').val(codigoProducto);
    // Simula la ejecución de un evento de teclado con Enter sobre el input real
    var e = $.Event("keyup", { key: "Enter" });
    obtenerDatosProductoVentaRapida(e);
}

/**
 * Duplica las filas de medios de pago en modo POS masivo
 */
var duplicarMediosPago = function() {
    // Buscamos la primera fila de pagos de forma segura y la clonamos con sus eventos internos
    var dvContainer = $(".contenedor_pagos").find('.dv_tip_val_pago').first().clone(true);
    
    // Limpiamos el valor del nuevo input clonado
    dvContainer.find('.valueFact').val("");
    
    // Reseteamos el selector de tipo de pago al estado inicial
    var tipoPago = dvContainer.find('.method_fact');
    tipoPago.val(0);
    
    // Ejecuta tu función nativa para el tratamiento de devoluciones/vueltos si aplica
    if (typeof mostrarCamposDevoluciones === "function") {
        mostrarCamposDevoluciones(tipoPago);
    }
    
    // Inyectamos la nueva fila al final del muelle
    $(".contenedor_pagos").append(dvContainer);
    
    // Reinicializamos el plugin de formato numérico de CakePHP sobre el nuevo input inyectado
    if ($.fn.number) {
        dvContainer.find('.numericPrice').number(true, 2);
    }
};

/**
 * Procesa, suma y liquida los montos recibidos vs el total de la factura en caliente
 */
var calcularLiquidacionCaja = function() {
    
    var totalFactura = 0;
    var totalRecibido = 0;

    // Extraemos el valor del Total Factura eliminando el signo $, comas de miles y espacios
    var txtTotalFactura = $('.thTFCIVA').first().text();
    if (txtTotalFactura) {
        // Limpiamos el string para dejar solo números y puntos decimales
        var limpioTotal = txtTotalFactura.replace(/[\$\s]/g, '').replace(/,/g, '');
        totalFactura = parseFloat(limpioTotal) || 0;
    }

    // Recorremos todos los inputs de valor que estén visibles en el contenedor de pagos
    $('.valueFact').each(function() {
        var valorInput = $(this).val();
        if (valorInput) {
            // El plugin .number() a veces usa comas o formatos, limpiamos antes de convertir a float
            var limpioInput = valorInput.toString().replace(/[\$\s]/g, '').replace(/,/g, '');
            var montoFila = parseFloat(limpioInput) || 0;
            totalRecibido += montoFila;
        }
    });

    if(totalFactura > 0 && (totalRecibido == totalFactura || totalRecibido > totalFactura)) {
        $('#finalizar_compra').prop('disabled', false);
    }

    // Calculamos la diferencia matemática estricta
    var saldoFaltante = totalFactura - totalRecibido;

    // SETEAMOS EL MONTO RECIBIDO TOTAL (.thTUnitVR)
    // Usamos el formateador nativo con dos decimales para mantener la estética POS
    $('.thTUnitVR').text(totalRecibido.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

    // SETEAMOS EL SALDO FALTANTE / CAMBIO (.total-missing)
    var contenedorFaltante = $('.total-missing').find('.font-weight-bold');
    
    if (saldoFaltante > 0) {
        // CASO A: Aún falta dinero por cubrir (Texto en Rojo)
        contenedorFaltante.removeClass('text-success').addClass('text-danger');
        contenedorFaltante.text(saldoFaltante.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('.total-missing').find('span').first().text('Saldo Faltante:');
    } else if (saldoFaltante < 0) {
        // CASO B: El cliente pagó de más, hay que entregar vuelto (Texto en Verde)
        var cambioVuelto = Math.abs(saldoFaltante);
        contenedorFaltante.removeClass('text-danger').addClass('text-success');
        contenedorFaltante.text(cambioVuelto.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('.total-missing').find('span').first().text('Cambio / Vuelto:');
    } else {
        // CASO C: Pago exacto (Texto en Negro/Gris neutro)
        contenedorFaltante.removeClass('text-danger text-success').addClass('text-dark');
        contenedorFaltante.text('$ 0.00');
        $('.total-missing').find('span').first().text('Saldo Faltante:');
    }
};

/**
 * factura 
 * @returns {undefined}
 */
var facturarMediosPagos = function() {
    var arrPayMeth = [];

    // Buscamos directamente en el contenedor sin importar desde dónde se invoque el método
    $(".contenedor_pagos").find('.dv_tip_val_pago').each(function() {
        var inputValor = $(this).find('.valueFact').val();
        var tipoPago = $(this).find('.method_fact').val();

        // Limpieza rápida de comas de formato por si el plugin .number() está activo
        var valorLimpio = inputValor ? inputValor.toString().replace(/[\$\s]/g, '').replace(/,/g, '') : "";
        var montoFila = parseFloat(valorLimpio) || 0;

        // Si la fila tiene un monto asignado, la empaquetamos para el backend
        if (montoFila > 0) {
            var objOptPayMet = {
                payment_type: tipoPago,
                val_pay_meth: montoFila
            };
            arrPayMeth.push(objOptPayMet);  
        }
    });

    // Enviamos el arreglo limpio a tu función nativa de almacenamiento
    guardarMetodosValoresFFast(arrPayMeth);

    // Retrasamos la ejecución 2 segundos para dar tiempo a que los AJAX finalicen
    setTimeout(function() {
        imprimirEnTicketFacturaRapida();
    }, 2000);

};

/**
 * guarda los metodos de pago y su respectivo valor y llama la funcion para facturar
 * @param {type} arrPayMeth
 * @returns {undefined}
 */
var guardarMetodosValoresFFast = function(arrPayMeth){ 
    $('.icon-container').show();
    $('#btn_facturar_m').css('display', 'none');
    $('#btn_agregar').css('display', 'none');
    
    //se obtiene el id de la prefactura para realizar el abono
    var prefactId = "";
    if (typeof($('#prefactId').val()) == "undefined"){
        prefactId = $('#prefacturaId').val();
    }else{
        prefactId = $('#prefactId').val();
    }    
    
    $.ajax({        
        url: $('#url-proyecto').val() + 'facturaCuentaValores/guardarFacturaCuentasValores', 
        async : false,
        data: {arrPayMeth: arrPayMeth, prefactId: prefactId},
        type: "POST",
        success: function(data) {
             var resp = JSON.parse(data);
             submitFormFFast();
        }
   });      
};

function submitFormFFast(){

    var formData = new FormData($('#FacturaAddForm')[0]);  
    var prefacturaId = $('#prefacturaId').val();

    $('#fact_status').text('Guardando tu factura y sincronizando inventario.');
    
    $.ajax({
        url: $('#url-proyecto').val() + 'facturas/facturarProductos',
        type: 'POST',        
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            var respuesta = JSON.parse(data);

            if($('#esFacturaDV').val() == '1' && $('#syncdian').val() == '1') {
                obtenerFacturaDian(null, respuesta.resp, 2);
            }
        }
    });    
}


$(document).ready(function() {
 
    // Se ocultan totalizadores y se muestran en la medida que tengan valor
    $('.dv_descuento').hide();    
    $('.dv_imp_iva').hide();    
    $('.dv_imp_ica').hide();
    $('.dv_imp_bolsa').hide();


    $('#finalizar_compra').prop('disabled', true);
    $('.numericPrice').number(true, 2);
    establecerTipoFactura($('#tipoFactura').val());

    $('.valueFact').change(calcularLiquidacionCaja(this));

    // Vinculamos el evento clic de tu botón al método de duplicación pro
    $('#btn_abonos_fast').on('click', duplicarMediosPago);

    // Vinculamos el evento clic del botón de pago exacto a la gestión del pago exacto sin digitar
    $('#btn_pago_exacto').on('click', gestionarPagoExacto);
    
    // Vinculamos la papelera para que destruya la fila clonada (usando delegación de eventos)
    $(document).on('click', '.del_pay_meth', function(e) {
        e.preventDefault();
        // Evitamos borrar la primera fila si es la única que queda activa
        if ($('.dv_tip_val_pago').length > 1) {
            $(this).closest('.dv_tip_val_pago').remove();
            // Llama aquí a tus funciones de recálculo si es necesario (ej: calcularCambio o sumarTotales)
        } else {
            // Si es la única, solo reseteamos sus valores para no dejar la pantalla sin inputs
            $(this).closest('.dv_tip_val_pago').find('.valueFact').val("");
            $(this).closest('.dv_tip_val_pago').find('.method_fact').val(0);
        }
    });

    // 1. Escucha cada que escriben un número en cualquier input de valor de pago (incluyendo los clonados)
    $(document).on('keyup change', '.valueFact', function() {
        calcularLiquidacionCaja();
    });

    // 2. Escucha si se elimina una fila de pago para recalcular de inmediato
    $(document).on('click', '.del_pay_meth', function() {
        // Le damos un pequeño delay de 50ms para permitir que el DOM remueva la fila antes de calcular
        setTimeout(function() {
            calcularLiquidacionCaja();
        }, 500);
    });
});