////////////////////////////////////FUNCIONES DE CALCULOS//////////////////////////////

// Obtiene el valor base del producto
var obtenerValorBaseProducto = function( objValoresBase ) {

    // --- 1. Conversión y Preparación de Variables ---    
    const precioVentaUnitario = parseFloat(objValoresBase.precioVenta * objValoresBase.unidadesProd) || 0;
    const descuentoPorcentaje = parseFloat(objValoresBase.porcentajeDesc) || 0; // Usado si se aplica por %

    // Convertir tasas de porcentaje (%) a decimal (e.g., 19 -> 0.19)
    const prcIVA = (parseFloat(objValoresBase.prcIVA) || 0);
    const prcINC = (parseFloat(objValoresBase.prcINC) || 0);
    
    // Factor para discriminar los impuestos (1 + tIVA + tINC)
    const factorRetiro = 1 + prcIVA + prcINC;

    // --- 2. Descuento de Impuestos (Cálculo Inverso para Base Inicial) ---
    // A. Valor Base Inicial (B): Precio Venta Unitario / Factor de Retiro
    const valorBaseInicial = precioVentaUnitario / factorRetiro; // Base sin impuestos ni descuento

    // --- 3. Aplicar Descuento a la Base Inicial (B) para obtener la Nueva Base (B') ---
    let valorBaseNueva;
    
    // Asumimos que el descuento es aplicado como porcentaje
    let descuento = 0;
    if (descuentoPorcentaje > 0) {
        // B. Aplicar descuento por porcentaje a la Base Inicial
        const factorDescuento = 1 - (descuentoPorcentaje / 100);
        valorBaseNueva = valorBaseInicial * factorDescuento;
        descuento = valorBaseInicial - valorBaseNueva;
    } 
    // Si no hay descuento
    else {
        valorBaseNueva = valorBaseInicial;
    }
    
    // Asegurar que la nueva base no sea negativa
    valorBaseNueva = Math.max(0, valorBaseNueva);
    
    // --- 4. Recalcular Impuestos sobre la Nueva Base (B') ---
    // C. Impuestos Totales (aplicados a la Nueva Base)
    const nuevoValIVA = valorBaseNueva * prcIVA;
    const nuevoValINC = valorBaseNueva * prcINC;
    
    // D. Valor Total Unitario Final (Suma de verificación: B' + nuevo IVA + nuevo INC)
    const precioFinalUnitario = valorBaseNueva + nuevoValIVA + nuevoValINC;

    // --- 5. Retorno (Redondeo para valores contables) ---
    // Redondeamos todos los resultados finales a 2 decimales.
    return {
        valorBaseUnitario: parseFloat(valorBaseNueva.toFixed(2)),
        valorIVA: parseFloat(nuevoValIVA.toFixed(2)),
        valorINC: parseFloat(nuevoValINC.toFixed(2)),
        varorINCBolsa: parseFloat(objValoresBase.valBolsa * objValoresBase.unidadesProd),
        precioUnitarioFinal: parseFloat(precioFinalUnitario.toFixed(2)),
        descuento: parseFloat(descuento.toFixed(2))
    };

};

// Setea los valores en el formulario
var setearValoresFormulario = function( objResultados ) {

    $('#precioventaCI').val(objResultados.valorBaseUnitario);
    $('#valorIva').val(objResultados.valorIVA);
    $('#valorIca').val(objResultados.valorINC);
    $('#valorConIva').val(objResultados.precioUnitarioFinal); 
    $('#descuento').val(objResultados.descuento); 

}

// Obtiene los valores base para recalculo de valores desde la base
var obtenerValoresIniciales = function() {

    return {
        'unidadesProd': $('#cantidadventa').val(), 
        'precioVenta': $('#precioventa').val(),
        'porcentajeDesc': $('#porcentaje').val(),
        'prcIVA': $('#prcIVA').val(),
        'prcINC': $('#prcINC').val(),
        'valBolsa': $('#valINCBolsa').val(),
    }
}


// Recalculo de valores por cambios en el formulario
var recalculoValores = function() {

    let vtaInventario = $('#vtaInventario').val();

    // se valida si el precio esta por debajo del minimo establecido
    if(parseFloat($('#precioventa').val()) < parseFloat($('#precioMinimo').val())) {
        
        alert('El precio de venta no puede ser menor al mínimo establecido.');        
        $('#precioventa').val($('#precioVenta').val()); 
    
    }else if( vtaInventario && parseFloat($('#cantidadventa').val()) > parseFloat($('#cantidadProducto').val()) ) {

        alert('Ha excedido la cantidad actual del Stock');
        $('#cantidadventa').val($('#cantidadProducto').val());

    } else {

        var objValoresBase = obtenerValoresIniciales();

        // 1. Obtener los resultados del cálculo
        var objResultados = obtenerValorBaseProducto( objValoresBase );

        // 2. Setear los valores en el formulario
        setearValoresFormulario( objResultados );
    
    }
};


/////////////////////////////ACTUALIZAR VALORES EN LA TABLA////////////////////////////////////////


// Setea los valores en el formulario
var setearValoresTabla = function( objResultados, campName ) {

    $('#total_' + campName).val(objResultados.valorBaseUnitario);
    $('#valor_iva_' + campName).val(objResultados.valorIVA);
    $('#valor_ica_' + campName).val(objResultados.valorINC);
    $('#valor_con_iva_' + campName).val(objResultados.precioUnitarioFinal); 
    $('#valdtto_' + campName).val(objResultados.descuento); 

}

// Obtiene los valores base para recalculo de valores desde la base
var obtenerValoresBaseTabla = function(campName) {

    return {
        'unidadesProd': $('#cant_' + campName).val(), 
        'precioVenta': $('#precio_' + campName).val(),
        'porcentajeDesc': $('#pordtto_' + campName).val(),
        'prcIVA': parseFloat($('#porc_iva_' + campName).val() / 100),
        'prcINC': parseFloat($('#porc_ica_' + campName).val() / 100),
        'valBolsa': parseFloat($('#inc_bolsa_' + campName).val())
    }
}

// Recalculo de valores por cambios en el formulario
var recalculoValoresTabla = function(campName) {
        var objValoresBase = obtenerValoresBaseTabla(campName);
        // 1. Obtener los resultados del cálculo
        var objResultados = obtenerValorBaseProducto( objValoresBase );
        // 2. Setear los valores en el formulario
        setearValoresTabla( objResultados, campName );
};


////////////////////////////////////////////////OBTIENE VALORES TABLA POR BARCODE////////////////////////////////

// Organiza la información necesaria basada en los datos que llegan por la implementación del barcode
var obtenerValoresInicialesTablaBarCode = function( prefactura ) {

    return {
        'unidadesProd': '1', 
        'precioVenta': prefactura.producto['0'].Cargueinventario.precioventa,
        'porcentajeDesc': '0',
        'prcIVA': parseFloat(parseFloat(prefactura.tasaIvaPorc) / 100),
        'prcINC': parseFloat(parseFloat(prefactura.tasaIncPorc) / 100),
        'valBolsa': parseFloat(prefactura.tasaBolsaVal),
    }
}

// Orquesta la generación de valores por datos que llegan desde el formulario directo a la tabla por barcode
var obtenerValoresTablaBC = function( prefactura ) {

    var objValoresBase = obtenerValoresInicialesTablaBarCode( prefactura );

    return obtenerValorBaseProducto( objValoresBase );

}

////////////////////////////////////////////////OBTIENE VALORES PARA PREFACTURA////////////////////////////////

// Organiza la información necesaria basada en los datos que llegan para la prefactura
var obtenerValoresInicialesPrefactura = function( prefactura ) {

    return {
        'unidadesProd': prefactura.producto['0'].Prefacturasdetalle.cantidad, 
        'precioVenta': prefactura.producto['0'].Prefacturasdetalle.costoventa,
        'porcentajeDesc': prefactura.producto['0'].Prefacturasdetalle.porcentaje,
        'prcIVA': parseFloat(parseFloat(prefactura.tasaIvaPorc) / 100),
        'prcINC': parseFloat(parseFloat(prefactura.tasaIncPorc) / 100),
        'valBolsa': parseFloat(prefactura.tasaBolsaVal),
    }
}

// Orquesta la generación de valores por datos que llegan desde el formulario directo a la tabla por barcode
var obtenerValoresTablaPrefactura = function( prefactura ) {

    var objValoresBase = obtenerValoresInicialesPrefactura( prefactura );

    return obtenerValorBaseProducto( objValoresBase );

}

////////////////////////////////////////////////OBTIENE VALORES TABLA PARA LAS COTIZACIONES////////////////////////////////

// Organiza la información necesaria basada en los datos que llegan por la implementación del barcode
var obtenerValoresInicialesTablaCotizacion = function( precioVenta, prcIva, prcINC, valBolsa, unidades, porcDesc ) {

    return {
        'unidadesProd': unidades, 
        'precioVenta': precioVenta,
        'porcentajeDesc': porcDesc,
        'prcIVA': parseFloat(parseFloat(prcIva) / 100),
        'prcINC': parseFloat(parseFloat(prcINC) / 100),
        'valBolsa': parseFloat(valBolsa),
    }
}

// Orquesta la generación de valores por datos que llegan desde el formulario directo a la tabla por barcode
var obtenerValoresTablaCotizacion = function( precioVenta, prcIva, prcINC, valBolsa, unidades, porcDesc ) {

    var objValoresBase = obtenerValoresInicialesTablaCotizacion( precioVenta, prcIva, prcINC, valBolsa, unidades, porcDesc );

    return obtenerValorBaseProducto( objValoresBase );

}


/*funciones de la tabla prefactura*/
function actualizarCantidadPrefact(dato){  
    var arrName = dato.name.split('_');
    var cantidad = $('#' + dato.name).val();
    if(typeof(cantidad) == "undefined" || cantidad == ""){
        bootbox.alert('- Debe ingresar una cantidad de productos.');
    }else{
        $.ajax({
            url: $('#url-proyecto').val() + 'prefacturasdetalles/actalizarcantidad',
            data: {cantidad: cantidad, id: arrName['1']},
            type: "POST",
            success: function(data) { 
                var respuesta = JSON.parse(data);
                if(respuesta.resp){

                    recalculoValoresTabla(arrName['1']);

                }else{
                    bootbox.alert('Ha excedido la cantidad actual del Stock. ' + respuesta.cantStock); 
                    $('#' + dato.name).val(respuesta.cantidad);
                }
                sumarTotales();            
            }            
        });         
    }
}

/**
 * Actualiza el registro en base de datos del valor y el porcentaje de descuento sobre el producto
 * @param {type} nuevoPor
 * @param {type} valDtto
 * @param {type} idPref
 * @returns {undefined}
 */
var actualizarValorPorcentajeDtto = function( inputId ){

    var nuevoPor = $('#pordtto_' + inputId).val();
    var valDtto = $('#valdtto_' + inputId).val();

    $.ajax({
        url: $('#url-proyecto').val() + 'prefacturas/actualizarPorcentajeValorDtto',
        data: {nuevoPor: nuevoPor, valDtto: valDtto, idPref: inputId},
        type: "POST",
        success: function(data) {
            var resp = JSON.parse(data);
            if(resp == '0'){
                bootbox.alert('No fue posible realizar la actualización del registro. Por favor, inténtelo de nuevo');                
            }
        }
    });  
    
};

/**funciones de la tabla de prefactura */
function actualizarPrecioPrefact(dato){
    var arrName = dato.name.split('_');
    var precioventa = $('#' + dato.name).val();    
    if(typeof(precioventa) == 'undefined' || precioventa == ""){
        bootbox.alert('- Debe ingresar el precio de venta del producto.');
    }else{
        $.ajax({
            url: $('#url-proyecto').val() + 'prefacturasdetalles/actualizarcostoventa',
            data: {precioventa: precioventa, id: arrName['1']},
            type: "POST",
            success: function(data) { 
                var respuesta = JSON.parse(data);
                if(respuesta.resp){

                    recalculoValoresTabla(arrName['1']);

                }else{
                    $('#' + dato.name).val(respuesta.precioventa);
                    bootbox.alert('El precio de venta no puede ser menor al mínimo establecido.');
                }
                sumarTotales();
            }
        });        
    }
}

/**
 * Actualiza el valor del descuento basado en el porcentaje
 * @param {type} data
 * @returns {undefined}
 */
var actualizarPorcentajeDtto = function(data){

    var arrId = (data.id).split("_");
        
    recalculoValoresTabla(arrId['1']);

    sumarTotales();

    actualizarValorPorcentajeDtto( arrId['1'] );

};