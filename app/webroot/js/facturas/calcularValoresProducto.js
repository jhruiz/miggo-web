//se calculan el total de venta sin iva, el valor del impuesto y total con descuento
var calcularTotales = function(){
    calcularPrecioVentaSinIva();    
    calcularDescuentoPorPorcentaje();    
    calcularValorImpuesto();
    calcularTotalConDescuento();
};

//se valida si hay existencia en el stock del producto que se desea
function validarCantidadStock(){   
    var cantidadActual = $('#cantidadProducto').val();
    var cantidadVenta = $('#cantidadventa').val();
    if(Number(cantidadVenta) > Number(cantidadActual)){
        $('#cantidadventa').val(cantidadActual);
        bootbox.alert('Ha excedido la cantidad actual del Stock');        
    }    
    calcularTotales();
    calcularDescuentoPorPorcentaje();
}


//se calcula el precio de venta 
var calcularPrecioVentaSinIva = function(){
    var cantidad = parseInt($('#cantidadventa').val());
    var precioUnit = parseInt($('#precioventa').val());    
    var prcImpuesto = parseFloat($('#prcImpuesto').val());
    precioTtalAI = 0;
    if(prcImpuesto <= 0){
        var precioTtalAI = (cantidad * precioUnit);
    }else{
        var precioTtalAI = Math.ceil((cantidad * precioUnit)/prcImpuesto);
    }
    
    $('#precioventaCI').val(precioTtalAI);
            
};

//se calcula el valor final: valor sin iva menos descuento mas iva
var calcularTotalConDescuento = function(){
    var precioVentaAI = parseInt($('#precioventaCI').val());  
    var descuento = parseInt($('#descuento').val());
    var valorIva = parseFloat($('#valorIva').val());
    
    var ttalFinalVenta = (precioVentaAI - parseFloat(descuento) + valorIva);
    
    $('#valorConIva').val(ttalFinalVenta);
};

//se calcula el valor del impuesto
var calcularValorImpuesto = function(){
    var precioVentaAI = parseFloat($('#precioventaCI').val());
    var descuento = parseFloat($('#descuento').val());
    var prcImpuesto = parseFloat($('#impuesto').val()/100);
    var baseSubTtal = precioVentaAI - descuento;    
    var ttalIimp = baseSubTtal * prcImpuesto;        
    
//    var precioUnit = parseInt($('#precioventa').val());    
//    var cantidad = parseInt($('#cantidadventa').val());
//    var impuesto = (precioUnit * cantidad) - precioVentaAI;
    
    $('#valorIva').val(ttalIimp);    
};

//a partir del porcentaje de descuento, se calculan los totales
var calcularDescuentoPorPorcentaje = function(){
    var dttoPor = $('#porcentaje').val();
    if(parseFloat(dttoPor) > 100){
        $('#descuento').val("");
        $('#porcentaje').val("");
    }else{
        var valDtto = parseFloat(parseFloat($('#precioventaCI').val())) * parseFloat(dttoPor)/100;       
        $('#descuento').val(valDtto);        
    }    
    calcularValorImpuesto();
    calcularTotalConDescuento();           
};

//a partir del valor de descuento, se calcula el porcentaje de descuento y los totales
var calcularDescuentoPorValor = function(){
    var dttoVal = $('#descuento').val();
    if(parseFloat(dttoVal) > parseFloat($('#precioventa').val())){
        $('#descuento').val("");
        $('#porcentaje').val("");
    }else{
        var porDtto = ((parseFloat(dttoVal) * 100)/parseFloat($('#precioventaCI').val()));
        $('#porcentaje').val(porDtto.toFixed(3));        
    }
    calcularValorImpuesto();
    calcularTotalConDescuento(); 
};





/////////////////////////////ACTUALIZAR VALORES EN LA TABLA////////////////////////////////////////

//se calcula el valor base del producto en la tabla de productos para la factura
var calcularPrecioAntesDeIva = function(campName){
    var cantidad = parseInt($('#cant_' + campName).val());
    var precioUnit = parseInt($('#precio_' + campName).val());    
    var prcImpuesto = parseFloat($('#prcimpuesto_' + campName).val());
    precioTtalAI = 0;
    if(prcImpuesto <= 0){
        var precioTtalAI = (cantidad * precioUnit);
    }else{
        var precioTtalAI = Math.ceil((cantidad * precioUnit)/parseFloat(prcImpuesto));
    }
    
    $('#total_' + campName).val(precioTtalAI);
};

//se calcula el valor del descuento con la base del producto
var calcularValorDescuento = function(campName){
    var dttoPor = parseFloat($('#pordtto_' + campName).val());    
    if(parseInt(dttoPor) > 100){
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
    var baseSubTtal = precioVentaAI - descuento;
    var ttalIimp = baseSubTtal * prcImpuesto;        
    
    $('#valor_iva_' + campName).val(ttalIimp);    
};


//se calcula el total del producto con descuento mas iva
var calcularTotalConDescuentoTabla = function(campName){
    var precioVentaAI = parseInt($('#total_' + campName).val());  
    var descuento = parseInt($('#valdtto_' + campName).val());
    var valorIva = parseFloat($('#valor_iva_' + campName).val());
    
    var ttalFinalVenta = (precioVentaAI - parseFloat(descuento) + valorIva);
    
    $('#valor_con_iva_' + campName).val(ttalFinalVenta);        
};

//se calcula el porcentaje de descuento a partir del valor del descuento
var actualizarDescuentoPorValorTabla = function(campName){
    var dttoVal = $('#valdtto_' + campName).val();
    if(parseInt(dttoVal) > parseInt($('#valor_con_iva_' + campName).val())){
        $('#pordtto_' + campName).val("");
        $('#valdtto_' + campName).val("");
    }else{
        var porDtto = ((parseFloat(dttoVal) * 100)/parseFloat($('#total_' + campName).val())).toFixed(3);
        $('#pordtto_' + campName).val(porDtto);
    }        
};