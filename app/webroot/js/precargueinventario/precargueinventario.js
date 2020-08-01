var opcDialogNotaCargueInventario = {
        autoOpen: false,
        modal: true,
        width: 600,
        height: 400,
        position: [400, 50],
        show: {
            duration: 400    
        },
        hide: function () {
//            alert($(this).dialog());
//            $(this).dialog('destroy').remove();
        },
        close: function( event, ui){
//            $(this).dialog('destroy').remove();            
        },
        title: 'Nota Aprobación Cargue de Inventario'    
};

var dialogNotaCargueInventario;


/*Funcion usada para actualizar el deposito en la tabla precargueinventarios por ajax*/
function actualizarDeposito(dato){
    var depositoId = $('#' + dato.name).val();
    var preCargueId = dato.name.split("_");   
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'precargueinventarios/actualizardepositoajax',
        data: {depositoId: depositoId, preCargueId: preCargueId['1']},
        success: function(data) {
        }
    });    
}

/*Funcion usada para para actualizar el valor en la tabla precargueinventarios por ajax*/
function actualizarValor(dato){
    var valUnit = $('#' + dato.name).val();
    var preCargueId = dato.name.split("_");
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'precargueinventarios/actualizarvalorunitarioajax',
        data: {valUnit: valUnit, preCargueId: preCargueId['1']},
        success: function(data) {
            location.reload();
        }
    });        
}

/*Funcion usada para actualizar la cantidad en la tabla precargueinventarios por ajax*/
function actualizarCantidad(dato){
    var cantidad = $('#' + dato.name).val();
    var preCargueId = dato.name.split("_");
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'precargueinventarios/actualizarcantidadajax',
        data: {cantidad: cantidad, preCargueId: preCargueId['1']},
        success: function(data) {
            location.reload();
        }
    });    
}

/*Funcion usada para actualizar el valor máximo en la tabla precargueinventarios por ajax*/
function actualizarValorMaximo(dato){
    var valMaximo = $('#' + dato.name).val();
    var preCargueId = dato.name.split("_");
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'precargueinventarios/actualizarvalormaximoajax',
        data: {valMaximo: valMaximo, preCargueId: preCargueId['1']},
        success: function(data) {
        }
    });      
}

/*Funcion usada para actualizar el valor mínimo en la tabla precargueinventarios por ajax*/
function actualizarValorMinimo(dato){    
    var valMinimo = $('#' + dato.name).val();
    var preCargueId = dato.name.split("_");
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'precargueinventarios/actualizarvalorminimoajax',
        data: {valMinimo: valMinimo, preCargueId: preCargueId['1']},
        success: function(data) {
        }
    });    
}

/*Funcion usada para actualizar el precio de venta en la tabla precargueinventarios por ajax*/
function actualizarPrecioVenta(dato){
    var precioVenta = $('#' + dato.name).val();
    var preCargueId = dato.name.split("_");
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'precargueinventarios/actualizarprecioventaajax',
        data: {precioVenta: precioVenta, preCargueId: preCargueId['1']},
        success: function(data) {
        }
    });      
}

/*Funcion usada para actualizar el proveedor en la tabla precargueinventarios por ajax*/
function actualizarProveedor(dato){
    var proveedorId = $('#' + dato.name).val();
    var preCargueId = dato.name.split("_");   
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'precargueinventarios/actualizarproveedorajax',
        data: {proveedorId: proveedorId, preCargueId: preCargueId['1']},
        success: function(data) {
        }
    });         
}

/*Funcion usada para actualizar el tipo de pago en la tabla precargueinventarios por ajax*/
function actualizarTipoPago(dato){
    var tipopagoId = $('#' + dato.name).val();
    var preCargueId = dato.name.split("_");   
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'precargueinventarios/actualizartipopagoajax',
        data: {tipopagoId: tipopagoId, preCargueId: preCargueId['1']},
        success: function(data) {
        }
    });
}

/*Funcion usada para actualizar el número de la factura en la tabla precargueinventarios por ajax*/
function actualizarNumeroFactura(dato){
    var numFact = $('#' + dato.name).val();
    var preCargueId = dato.name.split("_");   
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'precargueinventarios/actualizarnumerofacturaajax',
        data: {numFact: numFact, preCargueId: preCargueId['1']},
        success: function(data) {
        }
    });    
}

function aprobarCargueProductos(dato){
    
        $("#div_anotacion").load(
            $('#url-proyecto').val() + "anotaciones/notacargueinventario",
            {usuarioId:dato.name},
            function(){                                                            
                dialogNotaCargueInventario=$("#div_anotacion").dialog(opcDialogNotaCargueInventario);
                dialogNotaCargueInventario.dialog('open');
            }
        );        
}

function guardarCargueInventario(dato){    
    var nota = $('#notaCargue').val();    
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'documentos/guardarcargueinventarioajax',
        data: {nota: nota, usuarioId: dato.name},
        success: function(data) {
            var respuesta = JSON.parse(data);
            if(respuesta.resp){            	
                window.location.href = $('#url-proyecto').val() + 'documentos/view/' + respuesta.documentoId;
            }else{
                bootbox.alert('No se pudo completar el cargue de Productos');
            }            
        }
    });      
    
}

function verCargueCatalogo(){
    if($('#url-proyecto').val()){
        window.open($('#url-proyecto').val() + "cargueinventarios/add","_self");
    }else{
        location.reload();
    }       
}

$( function() {
    $('.numericPrice').number(true);
});