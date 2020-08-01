var opcDialogSeleccionProducto = {
        autoOpen: false,
        modal: true,
        width: 750,
        height: 680,
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
        title: 'Seleccion de Producto'    
};

var dialogDialogSeleccionProducto;


/*----------Recibe la informacion ingresada para el producto y su busqueda-----------------*/
function fnObtenerDatosProducto(e){  
    var usuarioId = $('#CotizacioneUsuarioId').val();
    var empresaId = $('#CotizacioneEmpresaId').val();
    var fechaActual = $('#CotizacioneFechaActual').val();
    var fechaVencCot = $('#CotizacioneFechaVencCot').val();
    var nombreCliente = $('#CotizacioneNomcliente').val();
    var identCliente = $('#CotizacioneIdcliente').val();
    var FechaVencCot = $('#CotizacioneFechaVencCot').val();
    
    var key = (document.all) ? e.keyCode : e.which;
    if(key == 13){        
            $.ajax({
               url: $('#url-proyecto').val() + 'cotizaciones/addProductoBarCode',
               data: {usuarioId: usuarioId, descProducto: $('#CotizacioneProducto').val(), fechaActual: fechaActual, fechaVencCot: fechaVencCot, empresaId: empresaId, nombreCliente: nombreCliente, identCliente: identCliente, FechaVencCot: FechaVencCot},
               type: "POST",
               success: function(data) {   
               alert(data);
               die();            
                    var prefactura = JSON.parse(data);
                    if(prefactura.valido){
                        $('#productosFacturas').append('<tr id="tr_' + prefactura.resp + '">' + 
                                '<td>' + prefactura.producto['0']['Producto']['descripcion'] + '</td>' + 
                                '<td>' + prefactura.producto['0']['Producto']['codigo'] + '</td>' + 
                                '<td><input type="text" name="cant_' + prefactura.resp + '" class="form-control" id="cant_' + prefactura.resp + '" value="1" onblur="actualizarCantidadPrefact(this);">&nbsp;</td>' +
                                '<td><input type="text" name="precio_' + prefactura.resp + '" class="form-control numericPrice" id="precio_' + prefactura.resp + '" value="' + prefactura.producto['0']['Cargueinventario']['precioventa'] + '" onblur="actualizarPrecioPrefact(this);">&nbsp;</td>' +
                                '<td><input type="text" name="total_' + prefactura.resp + '" class="form-control ttales numericPrice" id="total_' + prefactura.resp + '" value="' + prefactura.producto['0']['Cargueinventario']['precioventa'] + '" readonly>&nbsp;</td>' +
                                '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + prefactura.resp + '"onclick="eliminarProductoPrefactura(this)"></td></tr>');
                        $('#FacturaProducto').val("");
                        $('#datosProducto').hide();
                        $('.numericPrice').number(true);
                    }else{                        
                        $('#FacturaProducto').val("");
                        $('#datosProducto').hide();                        
                        bootbox.alert(prefactura.mensaje);
                    }                    
               }
           });                
    }else if($('#CotizacioneProducto').val().length <= '0'){
        $('#datosProducto').hide();        
    }else{  
            $.ajax({
                url: $('#url-proyecto').val() + 'cargueinventarios/ajaxProductosVenta',
                data: {usuarioId: usuarioId, descProducto: $('#CotizacioneProducto').val()},
                type: "POST",
                success: function(data) {
                    var producto = JSON.parse(data);
                    var uls = "";
                    for(var i = 0; i < producto.resp.length; i++){
                        uls += "<a href='#' class='list-group-item list-group-item-info' name='" + producto.resp[i].Producto.id + "' onClick ='seleccionarProducto(this)'>" + producto.resp[i].Producto.descripcion + " - " + producto.resp[i].Producto.codigo + "</a>";
                    }
                    $('#datosProducto').show();
                    $('#datosProducto').html(uls);
                }
            });      
    }         
}


function seleccionarProducto(dato){
    var productoId = dato.name;  

    $("#div_producto").load(
            $('#url-proyecto').val() + "cotizaciones/seleccionproductoventa",
            {
                productoId: productoId
            },
            function(){                                                            
                dialogDialogSeleccionProducto=$("#div_producto").dialog(opcDialogSeleccionProducto);
                dialogDialogSeleccionProducto.dialog('open');
                $('#datosProducto').hide();
            }
        );     
}

function validarCantidadStock(){   
    var cantidadActual = $('#cantidadProducto').val();
    var cantidadVenta = $('#cantidadventa').val();
    if(Number(cantidadVenta) > Number(cantidadActual)){
        $('#cantidadventa').val("");
        bootbox.alert('Ha excedido la cantidad actual del Stock');        
    }
}

function validarPrecioMinimo(){
    var precioventa = $('#precioventa').val();
    var precioMinimo = $('#precioMinimo').val();
    
    if(Number(precioventa) < Number(precioMinimo)){
        $('#precioventa').val($('#precioVenta').val());
        bootbox.alert('El precio de venta no puede ser menor al mínimo establecido.');
    }
}

function agregarProductoCotizacion(){

    var usuarioId = $('#CotizacioneUsuarioId').val();
    var empresaId = $('#CotizacioneEmpresaId').val();
    var fechaActual = $('#CotizacioneFechaActual').val();
    var fechaVencCot = $('#CotizacioneFechaVencCot').val();
    var nombreCliente = $('#CotizacioneNomcliente').val();
    var identCliente = $('#CotizacioneIdcliente').val();
    var cantidadventa = $('#cantidadventa').val();
    var precioventa = $('#precioventa').val();
    var cargueinventarioId = $('#cargueinventarioId').val();
    
    var totalVenta = (Number(cantidadventa) * Number(precioventa));
    var mensaje = "";    
    
    mensaje = validarDatosFactura(cantidadventa,precioventa);
    if(mensaje == ""){
        $.ajax({
        url: $('#url-proyecto').val() + 'cotizaciones/agregarProdCotizacion',
        data: {usuarioId: usuarioId, empresaId: empresaId, cargueinventarioId: cargueinventarioId, cantidadventa: cantidadventa, precioventa: precioventa, fechaActual: fechaActual, fechaVencCot: fechaVencCot, nombreCliente: nombreCliente, identCliente: identCliente, totalVenta: totalVenta},
        type: "POST",
        success: function(data) {
            var cotizacion = JSON.parse(data);

            if(cotizacion.resp != '0' && cotizacion.resp != ""){
                $('#productosCotizacion').append('<tr id="tr_' + cotizacion.resp + '">' + 
                        '<td>' + $('#nombreProducto').val() + '</td>' + 
                        '<td>' + $('#codigoProducto').val() + '</td>' + 
                        '<td><input type="text" name="cant_' + cotizacion.resp + '" class="form-control" id="cant_' + cotizacion.resp + '" value="' + cantidadventa + '" onblur="actualizarCantidadCotizacion(this);">&nbsp;</td>' +
                        '<td><input type="text" name="precio_' + cotizacion.resp + '" class="form-control numericPrice" id="precio_' + cotizacion.resp + '" value="' + precioventa + '" onblur="actualizarPrecioCotizacion(this);">&nbsp;</td>' +
                        '<td><input type="text" name="total_' + cotizacion.resp + '" class="form-control ttales numericPrice" id="total_' + cotizacion.resp + '" value="' + totalVenta + '" readonly>&nbsp;</td>' +
                        '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + cotizacion.resp + '"onclick="eliminarProductoCotizacion(this)"></td></tr>');
                $('#CotizacioneProducto').val("");
                $('#CotizacioneSubtotal').val(cotizacion.subTotal['total_val']);
                $('#CotizacioneImpuestos').val(cotizacion.totalImp['total_sum']);
                $('#CotizacioneTotal').val(parseInt(cotizacion.subTotal['total_val']) + parseInt(cotizacion.totalImp['total_sum']));
                $('.numericPrice').number(true);
                dialogDialogSeleccionProducto.dialog('close');
            }else{
                bootbox.alert('No se pudo agregar el producto a la factura de venta. Por favor, inténtelo de nuevo.');
            }
        }
        });         
    }else{
        bootbox.alert(mensaje);
    }
}

function validarDatosFactura(cantidadventa,precioventa){
    var mensaje = "";
    if(typeof(cantidadventa) == 'undefined' || cantidadventa == ""){
        mensaje = "- Debe ingresar una cantidad de productos.<br>";
    }
    
    if(typeof(precioventa) == 'undefined' || precioventa == ""){
        mensaje += "- Debe ingresar un precio de venta para el producto.";
    }
    
    return mensaje;
}


function eliminarProductoPrefactura(dato){
        $.ajax({
            url: $('#url-proyecto').val() + 'prefacturasdetalles/delete',
            data: {detalleId: dato.id},
            type: "POST",
            success: function(data) { 
                var respuesta = JSON.parse(data);
                if(respuesta.resp){
                    $('#tr_' + dato.id).remove();
                    totalCrediContado();
                }else{
                    bootbox.alert('No se pudo eliminar el producto. Por favor, inténtelo de nuevo.');
                }
            }
        });         
}


$( function() {  
    $('.numberPrice').number(true);
});