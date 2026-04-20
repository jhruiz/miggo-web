var poblarTablaCotizacion = function ( valoresTabla ) {

    $('#dvTCot').append('<tr id="tr_' + valoresTabla.idReg + '">' + 
        '<td>' + valoresTabla.descProd + '</td>' + 
        '<td>' + valoresTabla.codProd + '</td>' +                         
        '<td><input type="text" name="cant_' + valoresTabla.idReg + '" class="form-control" id="cant_' + valoresTabla.idReg + '" value="' + valoresTabla.cantProd + '" onblur="actCantPrdCot(this);">&nbsp;</td>' +
        '<td><input type="text" name="cant_' + valoresTabla.idReg + '" class="form-control" id="unitfalt_' + valoresTabla.idReg + '" value="' + valoresTabla.unidadesFaltantes + '" disabled>&nbsp;</td>' + 
        '<td><input type="text" name="precio_' + valoresTabla.idReg + '" class="form-control numericPrice ttalUnit" id="precio_' + valoresTabla.idReg + '" value="' + valoresTabla.precioventa + '" onblur="actValUnitPrdCot(this);">&nbsp;</td>' +
        '<td><input type="text" name="total_' + valoresTabla.idReg + '" class="form-control ttales numericPrice ttalTotal" id="total_' + valoresTabla.idReg + '" value="' + valoresTabla.valAntesImp + '" readonly>&nbsp;</td>' +
        '<td><input type="text" name="pordtto_' + valoresTabla.idReg + '" class="form-control ttalPorDtto" id="pordtto_' + valoresTabla.idReg + '" value="' + valoresTabla.prcDsc + '" onblur="actualizarPorcentajeDttoCot(this);">&nbsp;</td>' +
        '<td><input type="text" name="valdtto_' + valoresTabla.idReg + '" class="form-control ttalValDtto numericPrice" id="valdtto_' + valoresTabla.idReg + '" value="' + valoresTabla.vlrDsc + '" readonly>&nbsp;</td>' +
        '<td><input type="text" name="valor_iva_' + valoresTabla.idReg + '" class="form-control valor_iva numericPrice" id="valor_iva_' + valoresTabla.idReg + '" value="' + valoresTabla.valIVA + '" readonly>&nbsp;</td>' +
        '<td><input type="text" name="porc_iva_' + valoresTabla.idReg + '" class="form-control porc_iva numericPrice" id="porc_iva_' + valoresTabla.idReg + '" value="' + valoresTabla.prcIVA + '" readonly>&nbsp;</td>' +
        '<td><input type="text" name="valor_ica_' + valoresTabla.idReg + '" class="form-control valor_ica numericPrice" id="valor_ica_' + valoresTabla.idReg + '" value="' + valoresTabla.valINC + '" readonly>&nbsp;</td>' +
        '<td><input type="text" name="porc_ica_' + valoresTabla.idReg + '" class="form-control porc_ica numericPrice" id="porc_ica_' + valoresTabla.idReg + '" value="' + valoresTabla.prcINC + '" readonly>&nbsp;</td>' +
        '<td><input type="text" name="inc_bolsa_' + valoresTabla.idReg + '" class="form-control inc_bolsa numericPrice" id="inc_bolsa_' + valoresTabla.idReg + '" value="' + valoresTabla.varorINCBolsa + '" readonly>&nbsp;</td>' +
        '<td><input type="text" name="valor_con_iva_' + valoresTabla.idReg + '" class="form-control valor_con_iva numericPrice" id="valor_con_iva_' + valoresTabla.idReg + '" value="' + valoresTabla.valorConIva + '" readonly>&nbsp;</td>' +
        '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + valoresTabla.idReg + '"onclick="eliminarPrdCot(this)"></td></tr>' 
    );                                               
    $('.numericPrice').number(true, 2);
        
};

/**
 * Se crea la cotizacion
 * @returns {undefined}
 */
function guardarCotizacionClienteRegistrado() {

    var formData = new FormData($('#CotizacioneAddForm')[0]);
    $('#CotizacioneTipoCotizacion').prop('disabled', true);

    $.ajax({
        url: $('#url-proyecto').val() + 'cotizaciones/crearActualizarCotizacionClienteRegistrado',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            var respuesta = JSON.parse(data);
            $('#cotizacionId').val(respuesta.resp);
            $('#btn_aprobar_cot').prop('disabled', false);
        }
    });
}

/**
 * Se selecciona el cliente para la cotizacion
 * @param {type} datos
 * @returns {undefined}
 */
function seleccionarCliente(datos) {
    var clienteId = datos.name;

    $.ajax({
        url: $('#url-proyecto').val() + 'clientes/ajaxObtenerInfoCliente',
        data: { clienteId: clienteId },
        type: "POST",
        success: function(data) {
            var cliente = JSON.parse(data);
            $('#CotizacioneDatoscliente').val(cliente.resp.Cliente.nombre);
            $('#CotizacioneNitcliente').val(cliente.resp.Cliente.nit);
            $('#CotizacioneTelefonocliente').val(cliente.resp.Cliente.celular);
            $('#CotizacioneDircliente').val(cliente.resp.Cliente.direccion);
            $('#CotizacioneDiascredcliente').val(cliente.resp.Cliente.diascredito);
            $('#CotizacioneLimitecredcliente').val(cliente.resp.Cliente.limitecredito);
            $('#CotizacioneIdcliente').val(cliente.resp.Cliente.id);
            $('#datosCliente').hide();
            activarFiltroProducto();
            guardarCotizacionClienteRegistrado();
        }
    });

}

/**
 * Activa el input para busqueda del producto
 * @returns {undefined}
 */
function activarFiltroProducto() {
    var cliente = $('#CotizacioneDatoscliente').val();
    if (cliente.length <= '0') {
        $('#CotizacioneProducto').prop('disabled', true);
    } else {
        $('#CotizacioneProducto').prop('disabled', false);
    }
}

/**
 * obtiene el cliente que se desea agregar a la cotizacion
 * @returns {undefined}
 */
var fnObtenerDatosCliente = function() {

    activarFiltroProducto();
    $('.nuevo').val("");
    $('.rapida').val("");
    var datosCliente = $('#CotizacioneDatoscliente').val();
    var empresaId = $('#empresaId').val();

    if (parseFloat(datosCliente.length) > 3) {
        $.ajax({
            url: $('#url-proyecto').val() + 'clientes/ajaxObtenerClientes',
            data: { datosCliente: datosCliente, empresaId: empresaId },
            type: "POST",
            success: function(data) {

                var cliente = JSON.parse(data);
                var uls = "";
                for (var i = 0; i < cliente.resp.length; i++) {
                    uls += "<a href='#' class='list-group-item list-group-item-info' name='" + cliente.resp[i].Cliente.id + "' onClick ='seleccionarCliente(this)'>" + cliente.resp[i].Cliente.nombre + " - " + cliente.resp[i].Cliente.nit + "</a>";
                }
                $('#datosCliente').show();
                $('#datosCliente').html(uls);
            }
        });

    } else {
        $('#datosCliente').show();
        $('#datosCliente').html("");
    }
};

/**
 * Se valia el formulario para crear un cliente nuevo
 * @returns {String}
 */
var validarDatosCliente = function() {
    var mensaje = "";

    if ($('#CotizacioneNuevonombre').val() == "") {
        mensaje += "- Debe ingresar el nombre del cliente.<br>";
    }

    if ($('#CotizacioneNuevonit').val() == "") {
        mensaje += "- Debe ingresar el Nit/C.C del cliente.<br>";
    }

    if ($('#CotizacioneNuevodireccion').val() == "") {
        mensaje += "- Debe ingresar la dirección del cliente.<br>";
    }

    if ($('#CotizacioneNuevodiscredito').val() == "") {
        mensaje += "- Debe ingresar los días de crédito para el cliente.<br>";
    }

    if ($('#CotizacioneNuevolimitecredito').val() == "") {
        mensaje += "- Debe ingresar el límite de crédito para el cliente.<br>";
    }

    return mensaje;
};

/**
 * Se crea el cliente nuevo para la cotizacion
 * @returns {undefined}
 */
var guardarCliente = function() {
    var mensaje = "";
    mensaje = validarDatosCliente();

    if (mensaje == "") {

        var formData = new FormData($('#CotizacioneAddForm')[0]);

        $.ajax({
            url: $('#url-proyecto').val() + 'clientes/ajaxCrearCliente',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                var respuesta = JSON.parse(data);
                if (respuesta.resp != '0') {
                    $('#CotizacioneIdcliente').val(respuesta.cliente);
                    $('#cotizacionId').val(respuesta.resp);
                    $('#btn_aprobar_cot').prop('disabled', false);
                    $('#CotizacioneProductousuarionuevo').prop('disabled', false);
                } else {
                    bootbox.alert('No fue posible crear el cliente y la cotización. Por favor, inténtelo de nuevo.')
                }

            }
        });

    } else {
        bootbox.alert(mensaje);
    }
};

/**
 * Crea o actualiza la cotizacion de rapida
 * @returns {undefined}
 */
var crearActualizaCotizacionRapida = function() {
    var mensaje = "";
    mensaje = validarFormCotizacionRapida();

    if (mensaje == "") {
        var formData = new FormData($('#CotizacioneAddForm')[0]);

        $.ajax({
            url: $('#url-proyecto').val() + 'cotizaciones/crearActualizarCotizacionRapida',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                var respuesta = JSON.parse(data);
                $('#cotizacionId').val(respuesta.resp);
            }
        });
    } else {
        bootbox.alert(mensaje);
    }
};

var aprobarCotizacion = function() {
    var idCotizacion = $('#cotizacionId').val();
    if (idCotizacion == "") {
        bootbox.alert('No existe cotización para aprobar.');
    } else {
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizaciones/ajaxAprobarCotizacion',
            data: { idCotizacion: idCotizacion },
            type: "POST",
            success: function(data) {

                var result = JSON.parse(data);
                if (result.resp == '1') {
                    window.location.href = $('#url-proyecto').val() + 'ordentrabajos/edit/' + result.idOrden;
                } else if (result.resp == '0') {
                    bootbox.alert('No fue posible aprobar la cotización. Por favor, inténtelo de nuevo.');
                } else if (result.resp == '2') {
                    bootbox.alert(result.msg);
                }

            }
        });
    }
};

/**
 * Actualiza la cantidad del producto solicitado en la cotizacion
 * @param {type} data
 * @returns {undefined}
 */
var actCantPrdCot = function(data) {
    var arrData = data.id.split("_");
    var nuevaCant = $('#' + data.id).val();
    if (nuevaCant != "") {
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizacionesdetalles/ajaxActualizarCantidadCotiza',
            data: { cotDetId: arrData['1'], nuevaCant: nuevaCant },
            type: "POST",
            success: function(response) {
                var resp = JSON.parse(response);
                if (resp.resp != '1') {
                    bootbox.alert('No fue posible actualizar el registro. Por favor, inténtelo de nuevo.');
                } else {

                    var unidadesFaltantes = parseFloat(resp.cotizacion['0'].Cotizacionesdetalle.cantidad) - parseFloat(resp.cotizacion['0'].CI.existenciaactual);
                    unidadesFaltantes = unidadesFaltantes > 0 ? unidadesFaltantes : 0;

                    $('#unitfalt_' + arrData['1']).val(unidadesFaltantes);

                    recalculoValoresTabla(arrData['1']);
                    calcularTtalCot();
                }
            }
        });
    } else {
        bootbox.alert('La cantidad no puede estar vacía');
    }

};

/**
 * Actualiza el valor unitario del producto
 * @param {type} data
 * @returns {undefined}
 */
var actValUnitPrdCot = function(data) {
    var arrData = data.id.split("_");
    var nuevoValor = $('#' + data.id).val();

    if (nuevoValor != "") {
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizacionesdetalles/ajaxActualizarValorUnitarioCotiza',
            data: { cotDetId: arrData['1'], nuevoValor: nuevoValor },
            type: "POST",
            success: function(response) {
                var resp = JSON.parse(response);
                if (resp.resp != '1') {
                    bootbox.alert('No fue posible actualizar el registro. Por favor, inténtelo de nuevo.');
                } else {
                    recalculoValoresTabla(arrData['1']);
                    calcularTtalCot();
                }
            }
        });
    } else {
        bootbox.alert('El valor del producto no puede estar vacio');
    }
};


/**
 * Actualiza el registro en base de datos del valor y el porcentaje de descuento sobre el producto
 * @param {type} nuevoPor
 * @param {type} valDtto
 * @param {type} idPref
 * @returns {undefined}
 */
var actualizarValorPorcentajeDttoCot = function( inputId ){

    var nuevoPor = $('#pordtto_' + inputId).val();
    var valDtto = $('#valdtto_' + inputId).val();

    $.ajax({
        url: $('#url-proyecto').val() + 'cotizacionesdetalles/actualizarPorcentajeValorDttoCot',
        data: {nuevoPor: nuevoPor, valDtto: valDtto, idCot: inputId},
        type: "POST",
        success: function(data) {
            var resp = JSON.parse(data);
            if(resp == '0'){
                bootbox.alert('No fue posible realizar la actualización del registro. Por favor, inténtelo de nuevo');                
            }
        }
    });  
    
};

var actualizarPorcentajeDttoCot = function(data){
    var arrId = (data.id).split("_");
        
    recalculoValoresTabla(arrId['1']);

    calcularTtalCot();

    actualizarValorPorcentajeDttoCot( arrId['1'] );
}


/**
 * Eliminar el registro seleccionado de la cotizacion
 * @param {type} data
 * @returns {undefined}
 */
var eliminarPrdCot = function(data) {
    var idDetCot = data.id;

    $.ajax({
        url: $('#url-proyecto').val() + 'cotizacionesdetalles/ajaxEliminarDetalleCotiza',
        data: { idDetCot: idDetCot },
        type: "POST",
        success: function(response) {
            var resp = JSON.parse(response);
            if (resp.resp == '1') {
                $('#tr_' + data.id).remove();
                calcularTtalCot();
            } else {
                bootbox.alert('No fue posible eliminar el registro. Por favor, inténtelo de nuevo.');
            }
        }
    });
};

/**
 * Seleccionar producto para la cotizacion
 * @param {type} data
 * @returns {undefined}
 */
var seleccionarProductoCotizacion = function(data) {

    var tipoCot = $('#CotizacioneTipoCotizacion').val();

    var nomProduct = "";
    var idProd = "";
    if (typeof(data.name) != "undefined") {
        nomProduct = data.text;
        idProd = data.name;
    } else {
        nomProduct = $('#CotizacioneProducto').val();
    }
    var cotizacionId = $('#cotizacionId').val();

    if (nomProduct != "") {
        //se guarda el registro del producto para la cotizacion
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizacionesdetalles/ajaxGuardarDetalleCotiza',
            data: { catizacionId: cotizacionId, nomProduct: nomProduct, idProd: idProd, tipoCot: tipoCot },
            type: "POST",
            success: function(response) {
                var resp = JSON.parse(response);
                var unidadesFaltantes = 0;
                if (resp.valid == '1') {

                    var valoresTablaBC = obtenerValoresTablaCotizacion( resp.data.costoventa , resp.data.impuesto, resp.data.impoconsumo, resp.data.incbolsa, '1', '0' );

                    unidadesFaltantes = parseFloat(resp.data.cantidad) - parseFloat(resp.prod.Cargueinventario.existenciaactual);  
                    var valoresTabla = {
                        idReg: resp.resp,
                        cantProd: '1',
                        descProd: resp.prod.Producto.descripcion,
                        codProd: resp.prod.Producto.codigo,
                        precioventa: valoresTablaBC.precioUnitarioFinal,
                        valAntesImp: valoresTablaBC.valorBaseUnitario,
                        prcDsc: '0',
                        vlrDsc: '0',
                        prcIVA: resp.data.impuesto,
                        valIVA: valoresTablaBC.valorIVA,
                        prcINC: resp.data.impoconsumo,
                        valINC: valoresTablaBC.valorINC,
                        varorINCBolsa: valoresTablaBC.varorINCBolsa,
                        valorConIva: valoresTablaBC.precioUnitarioFinal,
                        unidadesFaltantes: unidadesFaltantes > 0 ? unidadesFaltantes : 0
                    }

                    poblarTablaCotizacion ( valoresTabla );
                    $('#datosProducto').html("").hide();
                    $('#CotizacioneProducto').val("");

                } else {
                    bootbox.alert('No fue posible agregar el producto. Por favor, inténtelo de nuevo.');
                }

                calcularTtalCot();
            }
        });
    } else {
        bootbox.alert('El campo producto no debe estar vacio');
    }
};

var obtenerDetalleCotizacion = function(id) {
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizacionesdetalles/ajaxObtenerDetallesCotizacion',
            data: { cotizacionId: id },
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);

                if( resp.est ) {

                    var unidadesFaltantes = 0;
                    resp.resp.forEach(element => {
                        var valoresTablaBC = obtenerValoresTablaCotizacion( element.Cotizacionesdetalle.costoventa, 
                                                                            element.Cotizacionesdetalle.impuesto, 
                                                                            element.Cotizacionesdetalle.impoconsumo, 
                                                                            element.Cotizacionesdetalle.incbolsa,
                                                                            element.Cotizacionesdetalle.cantidad,
                                                                            element.Cotizacionesdetalle.porcentaje
                                                                         );

                        
                        unidadesFaltantes = element.Cotizacionesdetalle.cantidad - element.CI.existenciaactual;

                        var valoresTabla = {
                            idReg: element.Cotizacionesdetalle.id,
                            cantProd: element.Cotizacionesdetalle.cantidad,
                            descProd: element.P.descripcion,
                            codProd: element.P.codigo,
                            precioventa: element.Cotizacionesdetalle.costoventa,
                            valAntesImp: valoresTablaBC.valorBaseUnitario,
                            prcDsc: element.Cotizacionesdetalle.porcentaje,
                            vlrDsc: element.Cotizacionesdetalle.descuento,
                            prcIVA: element.Cotizacionesdetalle.impuesto,
                            valIVA: valoresTablaBC.valorIVA,
                            prcINC: element.Cotizacionesdetalle.impoconsumo,
                            valINC: valoresTablaBC.valorINC,
                            varorINCBolsa: element.Cotizacionesdetalle.incbolsa,
                            valorConIva: valoresTablaBC.precioUnitarioFinal,
                            unidadesFaltantes: unidadesFaltantes > 0 ? unidadesFaltantes : 0
                        }

                        poblarTablaCotizacion ( valoresTabla );
                    
                    });

                } else {
                    bootbox.alert('No fue posible cargar los productos de la cotización');
                }
                calcularTtalCot();
            }
        });
}

/**
 * Obtener los productos para cliente registrado
 * @returns {undefined}
 */
var fnObtenerProductoCotizacion = function() {

    var cotizacionId = $('#cotizacionId').val();
    var usuarioId = $('#CotizacioneVendedor').val();
    var descProd = $(this).val();

    if (cotizacionId == "") {
        bootbox.alert("No se ha creado la cotización.");
        $(this).val("");
    } else if(event.key === 'Enter' && cotizacionId != ""){

        $.ajax({
            url: $('#url-proyecto').val() + 'cargueinventarios/ajaxObtenerProductoCotizacion',
            data: { descProducto: descProd, usuarioId: usuarioId },
            type: "POST",
            success: function(data) {
                var producto = JSON.parse(data);

                if( producto.resp[0] ) {

                    var prod = {
                        'text': producto.resp[0].Producto.descripcion,
                        'name': producto.resp[0].Producto.id
                    }

                    seleccionarProductoCotizacion(prod)
                } else {
                    bootbox.alert("No se encontró un producto con el código " + descProd, ()=>{
                        $("#CotizacioneProducto").val("");
                    });
                }
            }
        });

    }else {
        if (descProd.length > 3) {
            $.ajax({
                url: $('#url-proyecto').val() + 'cargueinventarios/ajaxObtenerProductoCotizacion',
                data: { descProducto: descProd, usuarioId: usuarioId },
                type: "POST",
                success: function(data) {
                    var producto = JSON.parse(data);
                    var uls = "";
                    for (var i = 0; i < producto.resp.length; i++) {
                        uls += "<a href='#' class='list-group-item list-group-item-info' ";
                        uls += "name='" + producto.resp[i].Producto.id + "' ";
                        uls += "onClick ='seleccionarProductoCotizacion(this)'>" + producto.resp[i].Producto.descripcion;
                        uls += " - " + producto.resp[i].Producto.codigo;
                        uls += " Ref (" + producto.resp[i].Producto.referencia + ") bod. ";
                        uls += producto.resp[i].Deposito.descripcion + " - prov. ";
                        uls += producto.resp[i].Proveedore.nombre;
                        uls += "</a>";
                    }

                    $('#datosProducto').show();
                    $('#datosProducto').html(uls);
                }
            });
        } else {
            $('#datosProducto').hide();
            $('#datosProducto').html("");
        }
    }
};

/**
 * Se calcula el valor total de la cotizacion
 * @returns {undefined}
 */
var calcularTtalCot = function() {

        var ttalXIncBolsa=0;
        var unidadesProducto = 0;
        $(".inc_bolsa").each(function() {
            var idInpBolsa = $(this)['0'].id;
            var idInpCantidad = idInpBolsa.replace('inc_bolsa_', 'cant_');
            unidadesProducto = $('#' + idInpCantidad).val();
            ttalXIncBolsa = Number(parseFloat(ttalXIncBolsa) + (parseFloat($(this).val())*parseFloat(unidadesProducto)));
        });  

        var ttalUnit = 0;
        var ttalTotal = 0;
        var ttalValDtto = 0;
        var ttalesFinal = 0;
        var ttalesIva = 0;
        var ttalesIca = 0;
        var ttalesConIva = 0;
        var propina=0;
    
        //se suman todos los valores unitarios
        $( ".ttalUnit" ).each(function( index, val ) {
            ttalUnit += parseFloat($(this).val());
        });    
        
        $('.thTUnit').html(formatNumber(ttalUnit));
    
        //se suman todos los valores subtotales
        $( ".ttalTotal" ).each(function( index, val ) {
            ttalTotal += parseFloat($(this).val());
        });       

        $('.thTTotal').html(formatNumber(ttalTotal+propina));
        //se suman todos los valores de descuento
        $( ".ttalValDtto" ).each(function( index, val ) {
            ttalValDtto += parseFloat($(this).val());
        });        
        
        $('.thDtto').html(formatNumber(ttalValDtto));
        
        //se suman todos los valores de los totales finales
        $( ".ttalesFinal" ).each(function( index, val ) {
            ttalesFinal += parseFloat($(this).val());
        });        
        
        $('.thTFinal').html(formatNumber(ttalesFinal));
        
        //se suman los totales del iva
        $(".valor_iva").each(function(index, val){
            ttalesIva += parseFloat($(this).val());
        });
        
        $('.thIVA').html(formatNumber(ttalesIva));
        
        //se suman los totales del iva
        $(".valor_ica").each(function(index, val){
            ttalesIca += parseFloat($(this).val());
        });
        
        $('.thICA').html(formatNumber(ttalesIca));
        
        //se suman los totales con iva incluido
        $('.valor_con_iva').each(function(index, val){
            ttalesConIva += parseFloat($(this).val());
        });
    
        $('#inp_imp_bolsa').val(ttalXIncBolsa);
    
        $('.thTFCIVA').html(formatNumber(parseFloat(ttalesConIva)+parseFloat(ttalXIncBolsa)));

};

/**
 * Separadores de miles para los valores de la cotizacion que se van a imprimir
 * @param {type} num
 * @returns {Number|String}
 */
var formatNumber = function(num) {
    if (!num || num == 'NaN') return '-';
    if (num == 'Infinity') return '&#x221e;';
    
    // Convertir a número y manejar casos no numéricos
    num = parseFloat(num.toString().replace(/\$|\,/g, ''));
    if (isNaN(num)) {
        num = 0;
    }

    // Redondear a 2 decimales
    num = Math.round(num * 100) / 100;

    // Separar parte entera y decimal
    let parts = num.toFixed(2).split('.');
    let integerPart = parts[0];
    let decimalPart = parts[1] || '00'; // Asegurar 2 decimales

    // Agregar separadores de miles a la parte entera
    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    // Combinar parte entera y decimal
    return integerPart + '.' + decimalPart;
};



/**
 * Imprime la cotizacion
 * @returns {undefined}
 */
var generarPrefactura = function() {
    var cotizacionId = $('#cotizacionId').val();
    var crearOT = $('#CotizacioneCrearOt').val();
    var tipoCot = $('#CotizacioneTipoCotizacion').val();

    if (cotizacionId == "") {
        bootbox.alert('No se ha creado la cotización.');
    } else {
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizacionesdetalles/ajaxObtenerDetallesCotizacionPrefactura',
            data: { cotizacionId: cotizacionId, crearOT: crearOT, tipoCot: tipoCot },
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if (resp.resp) {
                    window.location.href = $('#url-proyecto').val() + 'cotizaciones/';
                }
            }
        }).fail(
            function( jqXHR, textStatus, errorThrown ) {
                bootbox.alert('No fue posible crear la prefactura');
            }
        );
    }
};

var cambiarResponsableCotiza = function() {
    var vendedor = $('#CotizacioneVendedor').val();
    var cotizacionId = $('#cotizacionId').val();

    $.ajax({
        url: $('#url-proyecto').val() + 'cotizaciones/ajaxCambiarResponsableCotiza',
        data: { vendedor: vendedor, cotizacionId: cotizacionId },
        type: "POST",
        success: function(data) {
            var resp = JSON.parse(data);
            if (resp.resp != '1') {
                bootbox.alert('No fue posible actualizar el responsable de la cotización. Por favor, inténtelo de nuevo.');
            }
        }
    });

};

/**
 * Formato a los input tipo date
 * @returns {undefined}
 */
var datePicker = function() {
    $(".date").datepicker({ dateFormat: 'yy-mm-dd' });
    $(".date").datepicker("option", "showAnim", "slideDown");
};

/**
 * Actualizar Datos del cliente registrado
 * @param {type} datos
 * @returns {undefined}
 */

var actualizarNitCliente = function() {
    var clienteId = $('#CotizacioneIdcliente').val();
    if (typeof(clienteId) != "undefined" && clienteId != "") {
        $.ajax({
            url: $('#url-proyecto').val() + 'clientes/ajaxActualizarNitCliente',
            data: { clienteId: clienteId, nit: $('#CotizacioneNitcliente').val() },
            type: "POST",
            success: function(data) {
                var respuesta = JSON.parse(data);
                bootbox.alert(respuesta.resp);
            }
        });
    } else {
        bootbox.alert('Debe seleccionar un cliente.');
    }
};

var actualizarTelefonoCliente = function() {
    var clienteId = $('#CotizacioneIdcliente').val();
    if (typeof(clienteId) != "undefined" && clienteId != "") {
        $.ajax({
            url: $('#url-proyecto').val() + 'clientes/ajaxActualizarTelCliente',
            data: { clienteId: clienteId, telefono: $('#CotizacioneTelefonocliente').val() },
            type: "POST",
            success: function(data) {
                var respuesta = JSON.parse(data);
                bootbox.alert(respuesta.resp);
            }
        });
    } else {
        bootbox.alert('Debe seleccionar un cliente.');
    }
};

var actualizarDireccionCliente = function() {
    var clienteId = $('#CotizacioneIdcliente').val();
    if (typeof(clienteId) != "undefined" && clienteId != "") {
        $.ajax({
            url: $('#url-proyecto').val() + 'clientes/ajaxActualizarDirCliente',
            data: { clienteId: clienteId, direccion: $('#CotizacioneDircliente').val() },
            type: "POST",
            success: function(data) {
                var respuesta = JSON.parse(data);
                bootbox.alert(respuesta.resp);
            }
        });
    } else {
        bootbox.alert('Debe seleccionar un cliente.');
    }
};


var guardarObservacion = function() {

    var observacion = $(this).val();
    var cotizacionId = $('#cotizacionId').val();

    if (cotizacionId != "") {
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizaciones/ajaxGuardarCotizacion',
            data: { cotizacion: cotizacionId, observacion: observacion },
            type: "POST",
            success: function(data) {
                var respuesta = JSON.parse(data);
                if (respuesta.resp) {} else {
                    bootbox.alert("No fue posible guardar la observación. Por favor, inténtelo nuevamente");
                }
            }
        });
    } else {
        bootbox.alert('Debe seleccionar primero un cliente');
    }
};

/**
 * Se obtienen los datos del vehiculo con la placa ingresada
 * @returns {undefined}
 */
var obtenerDatosVehiculo = function() {
    var cotizacionId = $('#cotizacionId').val();
    var plcV = $(this).val();

    if (cotizacionId != "") {
        if (plcV.length > 1) {
            $.ajax({
                url: $('#url-proyecto').val() + 'vehiculos/ajaxObtenerVehiculo',
                data: { datosVehiculo: plcV },
                type: "POST",
                success: function(data) {

                    var vehiculo = JSON.parse(data);
                    var uls = "";
                    for (var i = 0; i < vehiculo.resp.length; i++) {
                        uls += "<a href='#' class='list-group-item list-group-item-info' name='" + vehiculo.resp[i].Vehiculo.placa + "'";
                        uls += " id='" + vehiculo.resp[i].Vehiculo.id + "'";
                        uls += "onClick ='seleccionarVehiculo(this)'>" + vehiculo.resp[i].Vehiculo.placa + "</a>";
                    }
                    $('#datosVehiculo').show();
                    $('#datosVehiculo').html(uls);
                }
            });
        } else {
            $('#datosVehiculo').html("");
            $('#datosVehiculo').hide();
            $('#vehiculo_id').val("");
        }
    } else {
        bootbox.alert('Debe seleccionar primero un cliente');
    }
};

function seleccionarVehiculo(data) {
    $('#datosVehiculo').html("");
    $('#datosVehiculo').hide();
    $('#CotizacionePlaca').val(data.name);
    $('#vehiculo_id').val(data.id);
    var vehiculoId = data.id;
    var cotizacionId = $('#cotizacionId').val();

    if (cotizacionId != "") {
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizaciones/ajaxGuardarVehiculo',
            data: { vehiculoId: vehiculoId, cotizacionId: cotizacionId },
            type: "POST",
            success: function(data) {

            }
        });
    } else {
        bootbox.alert('Debe seleccionar primero un cliente');
    }
}

var verDatosVehiculo = function() {
    var idVehiculo = $('#vehiculo_id').val();
    var placa = $('#CotizacionePlaca').val();
    if (idVehiculo == "" && placa == "") {
        bootbox.alert('Debe seleccionar un vehículo.');
    } else {
        window.open($('#url-proyecto').val() + 'vehiculos/edit/' + idVehiculo, '_blank');
    }
};

var enviarCotizacion = function() {
    var telCli = $('#CotizacioneTelefonocliente').val();
    if (telCli != "") {
        var link = "https://wa.me/57" + telCli + "?text=adjuntamos%20información%20de%20su%20interés";
        $('.wppSendCot').attr('href', link).attr('target', '_blank');
    } else {
        bootbox.alert('El cliente no tiene número de teléfono para el envío del mensaje.');
    }
};

$(function() {
    datePicker();
    $('#add_product').click(seleccionarProductoCotizacion);
    $('#dv_emp').hide();
    $('#CotizacioneDatoscliente').keyup(fnObtenerDatosCliente);
    $('#CotizacioneProducto').prop('disabled', true);
    $('#CotizacioneProductousuarionuevo').prop('disabled', true);
    $('#FacturaLimitecredcliente').number(true);
    $('#FacturaNuevolimitecredito').number(true);
    $('.numberPrice').number(true);
    $('#CotizacioneNuevolimitecredito').number(true);
    $('#btnGuardarCliente').click(guardarCliente);
    $('#btn_aprobar_cot').click(aprobarCotizacion);
    $('#observacion').blur(guardarObservacion);
    $('#CotizacionePlaca').keyup(obtenerDatosVehiculo);
    $('#ver_vehiculo').click(verDatosVehiculo);
    $('.wppSendCot').click(enviarCotizacion);

    //crer cotizacion rapida
    $('#btnCotizacionRapida').click(crearActualizaCotizacionRapida);

    //actualizacion datos cliente registrado
    $('#CotizacioneNitcliente').change(actualizarNitCliente);
    $('#CotizacioneTelefonocliente').change(actualizarTelefonoCliente);
    $('#CotizacioneDircliente').change(actualizarDireccionCliente);

    //seleccion de producto
    $('#CotizacioneProducto').keyup(fnObtenerProductoCotizacion);

    if ($('#CotizacioneDatoscliente').val() != "") {
        $('#CotizacioneProducto').prop('disabled', false);
        $('#CotizacioneLimitecredcliente').number(true);
    }

    $('#CotizacioneVendedor').change(cambiarResponsableCotiza);

    $('#imprimirCot').click(imprimirCotizacion);
    $('#generarPrefac').click(generarPrefactura);

    /**Se valida si existe la cotización para obtener el detalle */
    if($('#cotizacionId').val()) {
        obtenerDetalleCotizacion($('#cotizacionId').val());
    }

    $('.ttales').number(true);
});