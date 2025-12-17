////////////////////////////////////FUNCIONES DE CALCULOS//////////////////////////////

// Obtiene el valor base del producto
var obtenerValorBaseProducto = function( objValoresBase ) {
    
    // --- 1. Conversión y Preparación de Variables ---    
    const precioVentaUnitario = parseFloat(objValoresBase.precioVenta * objValoresBase.unidadesProd) || 0;
    const descuentoPorcentaje = parseFloat(objValoresBase.porcentajeDesc) || 0; // Usado si se aplica por %

    // Convertir tasas de porcentaje (%) a decimal (e.g., 19 -> 0.19)
    const prcIVA = (parseFloat(objValoresBase.prcIVA) || 0) / 100;
    const prcINC = (parseFloat(objValoresBase.prcINC) || 0) / 100;
    
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
















// //se calculan el total de venta sin iva, el valor del impuesto y total con descuento
// var calcularTotales = function(){
//     // se valida si el precio esta por debajo del minimo establecido
//     if(parseFloat($('#precioventa').val()) < parseFloat($('#precioMinimo').val())) {
//         $('#precioventa').val($('#precioVenta').val()); 
//         bootbox.alert('El precio de venta no puede ser menor al mínimo establecido.');        
//     }else{
//         calcularPrecioVentaSinIva();    
//         calcularDescuentoPorPorcentaje();    
//         calcularValorImpuesto();
//         calcularTotalConDescuento();
//     }

// };

// //se valida si hay existencia en el stock del producto que se desea
// function validarCantidadStock(){   
//     if($('#vtaInventario').val() == '1'){
//         var cantidadActual = $('#cantidadProducto').val();
//         var cantidadVenta = $('#cantidadventa').val();
//         if(Number(cantidadVenta) > Number(cantidadActual)){
//             $('#cantidadventa').val(cantidadActual);
//             bootbox.alert('Ha excedido la cantidad actual del Stock');        
//         }    
//     }
//     calcularTotales();
//     calcularDescuentoPorPorcentaje();
// }


// //se calcula el precio de venta 
// var calcularPrecioVentaSinIva = function(){
//     var esFactura = $('#esFacturaDV').val();
//     var cantidad = parseFloat($('#cantidadventa').val());
//     var precioUnit = parseFloat($('#precioventa').val());    
//     var prcImpuesto = (esFactura == '1') ? parseFloat($('#impuesto').val()) : parseFloat('0');
//     precioTtalAI = 0;
//     if(prcImpuesto <= 0){
//         var precioTtalAI = (cantidad * precioUnit);
//     }else{
//         var precioTtalAI = (cantidad * precioUnit)/prcImpuesto;
//     }
    
//     $('#precioventaCI').val(precioTtalAI);
            
// };

// //se calcula el valor final: valor sin iva menos descuento mas iva
// var calcularTotalConDescuento = function(){
//     var esFactura = $('#esFacturaDV').val();
//     var precioVentaAI = parseFloat($('#precioventaCI').val());  
//     var descuento = parseFloat($('#descuento').val());
//     var valorIva = (esFactura == '1') ? parseFloat($('#valorIva').val()) : parseFloat('0');
//     var valorIca = (esFactura == '1') ? parseFloat($('#valorIca').val()) : parseFloat('0');
    
//     var ttalFinalVenta = (precioVentaAI - parseFloat(descuento) + valorIva + valorIca);
    
//     $('#valorConIva').val(ttalFinalVenta);
// };

// //se calcula el valor del impuesto
// var calcularValorImpuesto = function(){
//     var esFactura = $('#esFacturaDV').val();
//     var precioVentaAI = parseFloat($('#precioventaCI').val());
//     var descuento = parseFloat($('#descuento').val());
//     var prcImpuesto = (esFactura == '1') ? parseFloat($('#impuesto').val()/100) : parseFloat('0');
//     var prcInc = (esFactura == '1') ? parseFloat($('#prcINC').val()/100) : parseFloat('0');
//     var baseSubTtal = precioVentaAI - descuento;    
//     var ttalIimp = baseSubTtal * prcImpuesto;      
//     var ttalInc =   baseSubTtal * prcInc;
    
//     $('#valorIva').val(ttalIimp);    
//     $('#valorIca').val(ttalInc);    
// };

// //a partir del porcentaje de descuento, se calculan los totales
// var calcularDescuentoPorPorcentaje = function(){
//     var dttoPor = $('#porcentaje').val();
//     if(parseFloat(dttoPor) > 100){
//         $('#descuento').val("");
//         $('#porcentaje').val("");
//     }else{
//         var valDtto = parseFloat(parseFloat($('#precioventaCI').val())) * parseFloat(dttoPor)/100;       
//         $('#descuento').val(valDtto);        
//     }    
//     calcularValorImpuesto();
//     calcularTotalConDescuento();           
// };

// //a partir del valor de descuento, se calcula el porcentaje de descuento y los totales
// var calcularDescuentoPorValor = function(){
//     var dttoVal = $('#descuento').val();
//     if(parseFloat(dttoVal) > parseFloat($('#precioventa').val())){
//         $('#descuento').val("");
//         $('#porcentaje').val("");
//     }else{
//         var porDtto = ((parseFloat(dttoVal) * 100)/parseFloat($('#precioventaCI').val()));
//         $('#porcentaje').val(porDtto.toFixed(3));        
//     }
//     calcularValorImpuesto();
//     calcularTotalConDescuento(); 
// };





/////////////////////////////ACTUALIZAR VALORES EN LA TABLA////////////////////////////////////////

//se calcula el valor base del producto en la tabla de productos para la factura
var calcularPrecioAntesDeIva = function(campName){
    var cantidad = parseFloat($('#cant_' + campName).val());
    var precioUnit = parseFloat($('#precio_' + campName).val());    
    var prcImpuesto = parseFloat($('#prcimpuesto_' + campName).val());
    precioTtalAI = 0;
    if(prcImpuesto <= 0){
        var precioTtalAI = (cantidad * precioUnit);
    }else{
        var precioTtalAI = (cantidad * precioUnit)/parseFloat(prcImpuesto);
    }
    
    $('#total_' + campName).val(precioTtalAI);
};

//se calcula el valor del descuento con la base del producto
var calcularValorDescuento = function(campName){
    var dttoPor = parseFloat($('#pordtto_' + campName).val());   
    
    if(parseFloat(dttoPor) > 100){
        $('#valdtto_' + campName).val("");
        $('#pordtto_' + campName).val("");
    }else{
        var valDtto = parseFloat(parseFloat($('#total_' + campName).val())) * parseFloat(dttoPor)/100;
        $('#valdtto_' + campName).val(valDtto);        
    }
    
};

//calcula el valor del impuesto del iva
var calcularValorIvaTabla = function(campName){
    var precioVentaAI = parseFloat($('#total_' + campName).val());
    var descuento = parseFloat($('#valdtto_' + campName).val()); 
    var prcImpuesto = $('#prcimpuesto_' + campName).val() - 1;            
    var prcInc = parseFloat($('#porc_ica_' + campName).val()/100);
    var baseSubTtal = precioVentaAI - descuento;
    var ttalIimp = baseSubTtal * prcImpuesto;     
    var ttalInc = baseSubTtal * prcInc;
        
    $('#valor_iva_' + campName).val(ttalIimp);    
    $('#valor_ica_' + campName).val(ttalInc);   
};


//se calcula el total del producto con descuento mas iva
var calcularTotalConDescuentoTabla = function(campName){
    var precioVentaAI = parseFloat($('#total_' + campName).val());  
    var descuento = parseFloat($('#valdtto_' + campName).val());
    var valorIva = parseFloat($('#valor_iva_' + campName).val());
    var valorICA = parseFloat($('#valor_ica_' + campName).val());
    
    var ttalFinalVenta = (precioVentaAI - parseFloat(descuento) + valorIva + valorICA);
    
    $('#valor_con_iva_' + campName).val(ttalFinalVenta);        
};

//se calcula el porcentaje de descuento a partir del valor del descuento
var actualizarDescuentoPorValorTabla = function(campName){
    var dttoVal = $('#valdtto_' + campName).val();
    if(parseFloat(dttoVal) > parseFloat($('#valor_con_iva_' + campName).val())){
        $('#pordtto_' + campName).val("");
        $('#valdtto_' + campName).val("");
    }else{
        var porDtto = ((parseFloat(dttoVal) * 100)/parseFloat($('#total_' + campName).val())).toFixed(3);
        $('#pordtto_' + campName).val(porDtto);
    }        
};