var opcDialogSeleccionProducto = {
        autoOpen: false,
        modal: true,
        width: 850,
        height: 800,
        position: [400, 50],
        show: {
            duration: 400    
        },
        hide: function () {
        },
        close: function( event, ui){
        },
        title: 'Seleccion de Producto'    
};

var opcCrediContado = {
        autoOpen: false,
        modal: true,
        width: 700,
        height: 650,
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
        title: 'Métodos de pago'    
};

var dialogCrediContado;
var dialogDialogSeleccionProducto;

function validarExisteCliente(){
    var cliente = $('#FacturaIdcliente').val();
    if(typeof(cliente) != 'undefined' && cliente != ""){
        $('#PrefacturaProducto').prop('disabled', false);
        $('#PrefacturaDatoscliente').val($('#PrefacturaNombrecliente').val());
        $('#PrefacturaNitcliente').val($('#PrefacturaNitcccliente').val());     //no me muestra el dato                                      
        $('#PrefacturaTelefonocliente').val($('#PrefacturaTelcliente').val());
        $('#PrefacturaDircliente').val($('#PrefacturaDireccliente').val());
        $('#PrefacturaDiascredcliente').val($('#PrefacturaDiascliente').val());
        $('#PrefacturaLimitecredcliente').val($('#PrefacturaLimitecliente').val());
    }else{
        $('#PrefacturaProducto').prop('disabled', true);
    }
}

function actualizarNitCliente(){
    var clienteId = $('#FacturaIdcliente').val();
    if(typeof(clienteId) != "undefined" && clienteId != ""){
        $.ajax({
                url: $('#url-proyecto').val() + 'clientes/ajaxActualizarNitCliente',
                data: {clienteId: clienteId, nit: $('#PrefacturaNitcliente').val()},
                type: "POST",
                success: function(data) {               
                    var respuesta = JSON.parse(data);
                    bootbox.alert(respuesta.resp);
                }
            });        
    }else{
        bootbox.alert('Debe seleccionar un cliente.');
    }
}

function actualizarTelefonoCliente(){
    var clienteId = $('#FacturaIdcliente').val();
    if(typeof(clienteId) != "undefined" && clienteId != ""){
        $.ajax({
                url: $('#url-proyecto').val() + 'clientes/ajaxActualizarTelCliente',
                data: {clienteId: clienteId, telefono: $('#PrefacturaTelefonocliente').val()},
                type: "POST",
                success: function(data) {               
                    var respuesta = JSON.parse(data);
                    bootbox.alert(respuesta.resp);
                }
            });        
    }else{
        bootbox.alert('Debe seleccionar un cliente.');
    }    
}

function actualizarDireccionCliente(){
    var clienteId = $('#FacturaIdcliente').val();
    if(typeof(clienteId) != "undefined" && clienteId != ""){
        $.ajax({
                url: $('#url-proyecto').val() + 'clientes/ajaxActualizarDirCliente',
                data: {clienteId: clienteId, direccion: $('#PrefacturaDircliente').val()},
                type: "POST",
                success: function(data) {               
                    var respuesta = JSON.parse(data);
                    bootbox.alert(respuesta.resp);
                }
            });        
    }else{
        bootbox.alert('Debe seleccionar un cliente.');
    }    
}

function actualizarDiasLimite(){
    var clienteId = $('#FacturaIdcliente').val();
    if(typeof(clienteId) != "undefined" && clienteId != ""){
        $.ajax({
                url: $('#url-proyecto').val() + 'clientes/ajaxActualizarDiasCliente',
                data: {clienteId: clienteId, diascredito: $('#PrefacturaDiascredcliente').val()},
                type: "POST",
                success: function(data) {               
                    var respuesta = JSON.parse(data);
                    bootbox.alert(respuesta.resp);
                }
            });        
    }else{
        bootbox.alert('Debe seleccionar un cliente.');
    }    
}

function actualizarCreditoLimite(){
    var clienteId = $('#FacturaIdcliente').val();
    if(typeof(clienteId) != "undefined" && clienteId != ""){
        $.ajax({
                url: $('#url-proyecto').val() + 'clientes/ajaxActualizarCredCliente',
                data: {clienteId: clienteId, limitecredito: $('#PrefacturaLimitecredcliente').val()},
                type: "POST",
                success: function(data) {               
                    var respuesta = JSON.parse(data);
                    bootbox.alert(respuesta.resp);
                }
            });        
    }else{
        bootbox.alert('Debe seleccionar un cliente.');
    }    
}

function validarProductosPrefacturados(){
    var prefacId = $('#prefacturadoId').val();
    $.ajax({
        url: $('#url-proyecto').val() + 'prefacturasdetalles/obtenerPrefacturasDetalles',
        data: {prefacId: prefacId},
        type: "POST",
        success: function(data) {    
            var respuesta = JSON.parse(data);
            if(respuesta.resp){
                $.each(respuesta.detFact, function(idx, obj) {
                    var costoConIva = Number(obj['Prefacturasdetalle']['cantidad']) * Number(obj['Prefacturasdetalle']['costoventa']);
                    var costoSinIva = Math.ceil(parseFloat(costoConIva) / (parseFloat(obj['Prefacturasdetalle']['impuesto']/100) + 1));
                    var valDcto = parseFloat(obj['Prefacturasdetalle']['descuento']);
                    var prcImp = obj['Prefacturasdetalle']['impuesto']/100;
                    var valorIva = Math.ceil((costoSinIva - valDcto) * prcImp);                                         
                    var valorConIva = Math.ceil(parseFloat(costoSinIva) + parseFloat(valorIva) - obj['Prefacturasdetalle']['descuento']);
                    $('#productosPrefacturas').append('<tr id="tr_' + obj['Prefacturasdetalle']['id'] + '">' + 
                    '<td>' + obj['Cargueinventario']['descprod']  + '<input type="hidden" name="prcimpuesto_' + obj['Prefacturasdetalle']['id'] + '" id="prcimpuesto_' + obj['Prefacturasdetalle']['id'] + '" value="' + ((obj['Prefacturasdetalle']['impuesto']/100) + 1) + '">' + '</td>' + 
                    '<td>' + obj['Cargueinventario']['codprod'] + '</td>' + 
                    '<td><input type="text" name="cant_' + obj['Prefacturasdetalle']['id'] + '" class="form-control" id="cant_' + obj['Prefacturasdetalle']['id'] + '" value="' + obj['Prefacturasdetalle']['cantidad'] + '" onblur="actualizarCantidadPrefact(this);">&nbsp;</td>' +
                    '<td><input type="text" name="precio_' + obj['Prefacturasdetalle']['id'] + '" class="form-control numericPrice ttalUnit" id="precio_' + obj['Prefacturasdetalle']['id'] + '" value="' + obj['Prefacturasdetalle']['costoventa'] + '" onblur="actualizarPrecioPrefact(this);">&nbsp;</td>' +
                    '<td><input type="text" name="total_' + obj['Prefacturasdetalle']['id'] + '" class="form-control ttales numericPrice ttalTotal" id="total_' + obj['Prefacturasdetalle']['id'] + '" value="' + costoSinIva + '" readonly>&nbsp;</td>' +
                    '<td><input type="text" name="pordtto_' + obj['Prefacturasdetalle']['id'] + '" class="form-control ttalPorDtto" id="pordtto_' + obj['Prefacturasdetalle']['id'] + '" value="' + obj['Prefacturasdetalle']['porcentaje'] + '" onblur="actualizarPorcentajeDtto(this);">&nbsp;</td>' +
                    '<td><input type="text" name="valdtto_' + obj['Prefacturasdetalle']['id'] + '" class="form-control ttalValDtto numericPrice" id="valdtto_' + obj['Prefacturasdetalle']['id'] + '" value="' + valDcto + '" onblur="actualizarValorDtto(this);">&nbsp;</td>' +
                    '<td><input type="text" name="valor_iva_' + obj['Prefacturasdetalle']['id'] + '" class="form-control valor_iva numericPrice" id="valor_iva_' + obj['Prefacturasdetalle']['id'] + '" value="' + valorIva + '" readonly>&nbsp;</td>' +                                            
                    '<td><input type="text" name="valor_con_iva_' + obj['Prefacturasdetalle']['id'] + '" class="form-control valor_con_iva numericPrice" id="valor_con_iva_' + obj['Prefacturasdetalle']['id'] + '" value="' + Math.ceil(valorConIva) + '" readonly>&nbsp;</td>' +                                            
                    '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + obj['Prefacturasdetalle']['id'] + '"onclick="eliminarProductoPrefactura(this)"></td></tr>');
                });
                $('.numericPrice').number(true);
                calcularTotalConAbonos();
            }
            sumarTotales();
        }
    });      
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
                    calcularPrecioAntesDeIva(arrName['1']);
                    calcularValorDescuento(arrName['1']);
                    calcularValorIvaTabla(arrName['1']);
                    calcularTotalConDescuentoTabla(arrName['1']);                    
//                    totalCrediContado();

                    //se actualiza el porcentaje y valor de descuento
                    var nuevoPor = $('#pordtto_' + arrName['1']).val();
                    var valDtto = $('#valdtto_' + arrName['1']).val();
                    actualizarValorPorcentajeDtto(nuevoPor, valDtto, arrName['1']);
                }else{
                    bootbox.alert('Ha excedido la cantidad actual del Stock. ' + respuesta.cantStock); 
                    $('#' + dato.name).val(respuesta.cantidad);
                }                
                //actualiza los descuentos por porcentaje con los datos de la tabla
                sumarTotales();
                calcularTotalConAbonos();  
            }            
        });         
    }
}

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
                    calcularPrecioAntesDeIva(arrName['1']);
                    calcularValorDescuento(arrName['1']);
                    calcularValorIvaTabla(arrName['1']);
                    calcularTotalConDescuentoTabla(arrName['1']);                   
                    
                    //se actualiza el porcentaje y valor de descuento
                    var nuevoPor = $('#pordtto_' + arrName['1']).val();
                    var valDtto = $('#valdtto_' + arrName['1']).val();
                    actualizarValorPorcentajeDtto(nuevoPor, valDtto, arrName['1']);                                    
//                    totalCrediContado();
                }else{
                    $('#' + dato.name).val(respuesta.precioventa);
                    bootbox.alert('El precio de venta no puede ser menor al mínimo establecido.');
                }
                sumarTotales();
                calcularTotalConAbonos();
            }
        });        
    }
}

function totalCrediContado(){
    var ttales = 0;
//    if ($('#esfactura').prop('checked') == true) {
        $(".valor_con_iva").each(function() {
            ttales = Number(ttales) + Number($(this).val());
        });
//    }else{
//        $(".ttalesFinal").each(function() {
//            ttales = Number(ttales) + Number($(this).val());
//        });        
//    }    
    return ttales;
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
                    calcularTotalConAbonos();
                }else{
                    bootbox.alert('No se pudo eliminar el producto. Por favor, inténtelo de nuevo.');
                }
                
                sumarTotales();
            }
        });         
}

function fnObtenerDatosProducto(e){
    var usuarioId = $('#usuarioId').val();
    var clienteId = $('#FacturaIdcliente').val();    
    
    var key = (document.all) ? e.keyCode : e.which;
    if(key == 13){       
        if(typeof(clienteId) != "undefined" && clienteId != ""){
            $.ajax({
               url: $('#url-proyecto').val() + 'prefacturas/addProductoBarCode',
               data: {usuarioId: usuarioId, descProducto: $('#PrefacturaProducto').val(), clienteId: clienteId},
               type: "POST",
               success: function(data) {
                    var prefactura = JSON.parse(data);
                    if(prefactura.valido){
                        $('#productosPrefacturas').append('<tr id="tr_' + prefactura.resp + '">' + 
                                '<td>' + prefactura.producto['0']['Producto']['descripcion'] + '</td>' + 
                                '<td>' + prefactura.producto['0']['Producto']['codigo'] + '</td>' + 
                                '<td><input type="text" name="cant_' + prefactura.resp + '" class="form-control" id="cant_' + prefactura.resp + '" value="1" onblur="actualizarCantidadPrefact(this);">&nbsp;</td>' +
                                '<td><input type="text" name="precio_' + prefactura.resp + '" class="form-control ttalUnit" id="precio_' + prefactura.resp + '" value="' + prefactura.producto['0']['Cargueinventario']['precioventa'] + '" onblur="actualizarPrecioPrefact(this);">&nbsp;</td>' +
                                '<td><input type="text" name="total_' + prefactura.resp + '" class="form-control ttales ttalTotal" id="total_' + prefactura.resp + '" value="' + prefactura.producto['0']['Cargueinventario']['precioventa'] + '" readonly>&nbsp;</td>' +
                                '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + prefactura.resp + '"onclick="eliminarProductoPrefactura(this)"></td></tr>');
                        $('#PrefacturaProducto').val("");
                        $('#datosProducto').hide();
                    }else{                        
                        $('#PrefacturaProducto').val("");
                        $('#datosProducto').hide();                    
                        bootbox.alert(prefactura.mensaje);
                    }  
                    sumarTotales();
               }
           });                
        }else{
            bootbox.alert('Debe seleccionar un cliente.');
            $('#FacturaProducto').val("");                
        }
    }else if($('#PrefacturaProducto').val().length <= '0'){
        $('#datosProducto').hide();        
    }else{    
        if(typeof(clienteId) != "undefined" && clienteId != ""){
            if($('#PrefacturaProducto').val().length > 3){
                $.ajax({
                    url: $('#url-proyecto').val() + 'cargueinventarios/ajaxProductosVenta',
                    data: {usuarioId: usuarioId, descProducto: $('#PrefacturaProducto').val()},
                    type: "POST",
                    success: function(data) {
                        var producto = JSON.parse(data);
                        var uls = "";
                        for(var i = 0; i < producto.resp.length; i++){
                            if(parseInt(producto.resp[i].Cargueinventario.existenciaactual) >= parseInt(1)){
                                uls += "<a href='#' class='list-group-item list-group-item-info' ";
                                uls += "name='" + producto.resp[i].Producto.id + "' ";
                                uls += "id='" + producto.resp[i].Cargueinventario.id + "' ";
                                uls += "onClick ='seleccionarProducto(this)'>" + producto.resp[i].Producto.descripcion;
                                uls += " - " + producto.resp[i].Producto.codigo;
                                uls += " Ref (" + producto.resp[i].Producto.referencia + ") ";
                                uls += producto.resp[i].Deposito.descripcion;
                                uls += "</a>";
                            }
                        } 
                        $('#datosProducto').show();
                        $('#datosProducto').html(uls);
                    }
                }); 
            }else{
                $('#datosProducto').hide();
                $('#datosProducto').html("");                
            }
        }else{
            bootbox.alert('Debe seleccionar un cliente.');
            $('#PrefacturaProducto').val("");
        }  
    }
}

function fnObtenerDatosProductoUsuarioNuevo(e){
    var usuarioId = $('#usuarioId').val();
    var prefacturaId = $('#prefacturadoId').val();
    var mensaje = "";
    
    var mensaje = "";
    var key = (document.all) ? e.keyCode : e.which;
    if(key == 13){      
        mensaje = validarDatosUsuarioNuevo();
        if(mensaje == ""){
            $.ajax({
               url: $('#url-proyecto').val() + 'prefacturas/addProductoClienteNuevoBarCode',
               data: {usuarioId: usuarioId, descProducto: $('#PrefacturaProductousuarionuevo').val(), prefacturaId: prefacturaId},
               type: "POST",
               success: function(data) {
                    var prefactura = JSON.parse(data);
                    if(prefactura.valido){
                        $('#productosPrefacturas').append('<tr id="tr_' + prefactura.resp + '">' + 
                        '<td>' + prefactura.producto['0']['Producto']['descripcion'] + '</td>' + 
                        '<td>' + prefactura.producto['0']['Producto']['codigo'] + '</td>' +                                
                        '<td><input type="text" name="cant_' + prefactura.resp + '" class="form-control" id="cant_' + prefactura.resp + '" value="1" onblur="actualizarCantidadPrefact(this);">&nbsp;</td>' +
                        '<td><input type="text" name="precio_' + prefactura.resp + '" class="form-control ttalUnit" id="precio_' + prefactura.resp + '" value="' + prefactura.producto['0']['Cargueinventario']['precioventa'] + '" onblur="actualizarPrecioPrefact(this);">&nbsp;</td>' +
                        '<td><input type="text" name="total_' + prefactura.resp + '" class="form-control ttales ttalTotal" id="total_' + prefactura.resp + '" value="' + prefactura.producto['0']['Cargueinventario']['precioventa'] + '" readonly>&nbsp;</td>' +
                        '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + prefactura.resp + '"onclick="eliminarProductoPrefactura(this)"></td></tr>');                                                
                        $('#PrefacturaProductousuarionuevo').val("");                              
                        $('#datosProductoclientenuevo').hide(); 
                   }else{
                        $('#PrefacturaProductousuarionuevo').val("");                              
                        $('#datosProductoclientenuevo').hide();     
                        $('#PrefacturaProductoventarapida').val("");   
                        bootbox.alert(prefactura.mensaje);                        
                    }
                    sumarTotales();
               }
           });                
        }else{
            bootbox.alert(mensaje);
            $('#FacturaProducto').val("");                
        }
    }else{        
        mensaje = validarDatosUsuarioNuevo();
        if(mensaje != ""){
            bootbox.alert(mensaje);
            $('#PrefacturaProductousuarionuevo').val("");
        }else{     
            if($('#PrefacturaProductousuarionuevo').val().length > 3){
                $.ajax({
                    url: $('#url-proyecto').val() + 'cargueinventarios/ajaxProductosVenta',
                    data: {usuarioId: usuarioId, descProducto: $('#PrefacturaProductousuarionuevo').val()},
                    type: "POST",
                    success: function(data) {
                        var producto = JSON.parse(data);
                        var uls = "";
                        for(var i = 0; i < producto.resp.length; i++){
                            if(parseInt(producto.resp[i].Cargueinventario.existenciaactual) >= parseInt(1)){
                                uls += "<a href='#' class='list-group-item list-group-item-info' ";
                                uls += "name='" + producto.resp[i].Producto.id + "' ";
                                uls += "id='" + producto.resp[i].Cargueinventario.id + "' ";
                                uls += "onClick ='seleccionarProductoClienteNuevo(this)'>" + producto.resp[i].Producto.descripcion;
                                uls += " - " + producto.resp[i].Producto.codigo;
                                uls += " Ref (" + producto.resp[i].Producto.referencia + ") ";
                                uls += producto.resp[i].Deposito.descripcion;
                                uls += "</a>";
                            }
                        }                        
                        $('#datosProductoclientenuevo').show();
                        $('#datosProductoclientenuevo').html(uls);
                    }
                });                 
            }else{
                $('#datosProductoclientenuevo').hide();
                $('#datosProductoclientenuevo').html("");                
            }        
        }
    }
}


function seleccionarProducto(dato){
    var productoId = dato.name;    
    var cargueInvId = dato.id;
    
        $("#div_producto").load(
            $('#url-proyecto').val() + "cargueinventarios/seleccionproductoventa",
            {
                productoId: productoId, cargueInvId: cargueInvId
            },
            function(){                                                            
                dialogDialogSeleccionProducto=$("#div_producto").dialog(opcDialogSeleccionProducto);
                dialogDialogSeleccionProducto.dialog('open');
                $('#datosProducto').hide();
            }
        );     
}

//function validarCantidadStock(){   
//    var cantidadActual = $('#cantidadProducto').val();
//    var cantidadVenta = $('#cantidadventa').val();
//    if(Number(cantidadVenta) > Number(cantidadActual)){
//        $('#cantidadventa').val("");
//        bootbox.alert('Ha excedido la cantidad actual del Stock');        
//    }
//    
//    calcularDescuentoPorPorcentaje();
//}

function validarPrecioMinimo(){
    var precioventa = $('#precioventa').val();
    var precioMinimo = $('#precioMinimo').val();
    
    if(Number(precioventa) < Number(precioMinimo)){
        $('#precioventa').val($('#precioVenta').val());
        bootbox.alert('El precio de venta no puede ser menor al mínimo establecido.');
    }
    
    calcularDescuentoPorPorcentaje();
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

function facturarProductos(){
    if(typeof($('.ttales').val()) == 'undefined'){
        bootbox.alert('Debe seleccionar al menos un producto.');
        
    }else{
        var tipoPago = $('#PrefacturaTipopago').val();
        var ttalAbonos = $('.ttalAbonos').val();
         var contado = totalCrediContado();
         
         $('#pagocontado').val(contado);  
         
//        //funcion detallar el pago entre credito y contado
//        if(tipoPago == '5'){
            var valorCompra = totalCrediContado() - ttalAbonos;
            $("#div_facturar").load(
                $('#url-proyecto').val() + "facturas/pagofactura",
                {valorCompra: valorCompra},
                function(){                                                            
                    dialogCrediContado=$("#div_facturar").dialog(opcCrediContado);
                    dialogCrediContado.dialog('open');
                }
            );        
//        }else if(tipoPago == '4'){
//            var credito = totalCrediContado();
//            $('#pagocredito').val(credito);
//            $('#pagocontado').val('0');
//            submitForm();
//        }else if(tipoPago == '2'){
//            var contado = totalCrediContado();
//            $('#pagocontado').val(contado);
//            $('#pagocredito').val('0');
//            submitForm();
//        }else{
//            var contado = totalCrediContado();
//            $('#pagocontado').val(contado);
//            $('#pagocredito').val('0');
//            submitForm();            
//        }  
    }
        
}

function submitForm(){
    $('#btn_facturar').attr("disabled", true);
    var formData = new FormData($('#PrefacturaViewForm')[0]);    
    
    $.ajax({
        url: $('#url-proyecto').val() + 'facturas/facturarProductos',
        type: 'POST',        
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) { 
            var respuesta = JSON.parse(data);
            window.location.href = $('#url-proyecto').val() + 'facturas/view/' + respuesta.resp;
        }
    });    
}

function calcularPagoContado(){
    var contado = $('#pagoContado').val();    
    var totalFacturar = $('#totalVenta').val();
    
    if(Number(contado) > Number(totalFacturar)){
        bootbox.alert('El valor que se paga de contado no puede ser mayor al valor total de la factura.');
    }else{
        var result = Number(totalFacturar) - Number(contado);
        $('#pagoCredito').val(result);
        
        $('#pagocredito').val(result);        
        $('#pagocontado').val(contado);        
    }        
}

function calcularPagoCredito(){    
    var credito = $('#pagoCredito').val();
    var totalFacturar = $('#totalVenta').val();
    
    if(Number(credito) > Number(totalFacturar)){
        bootbox.alert('El valor que se paga a crédito no puede ser mayor al valor total de la factura.');
    }else{
        var result = Number(totalFacturar) - Number(credito);
        $('#pagoContado').val(result);  
        $('#pagocredito').val(credito);        
        $('#pagocontado').val(result);          
    }
}

//esta funcion ya no se usa
//function validarCrediContado(){
//
//    var mensaje = "";
//    mensaje = validarDatosContadoCredito();
//    
//    if(mensaje != ""){
//        bootbox.alert(mensaje);
//    }else{
//        submitForm();
//    }
//}

function validarDatosContadoCredito(){
    var credito = $('#pagocredito').val();        
    var contado = $('#pagocontado').val(); 
    var mensaje = "";
    if(credito == "" || credito == "0"){
        mensaje = '- Debe ingresar el valor a pagar a crédito. <br>';
    }    
    
    if(contado == "" || contado == "0"){
        mensaje += '- Debe ingresar el valor a pagar de contado.';
    }
    return mensaje;
}

var fnObtenerDatosCliente = function(){
    
    $('.nuevo').val("");
    $('.rapida').val("");

    var empresaId = $('#empresaId').val();
    $.ajax({
            url: $('#url-proyecto').val() + 'clientes/ajaxObtenerClientes',
            data: {datosCliente: $('#PrefacturaDatoscliente').val(), empresaId: empresaId},
            type: "POST",
            success: function(data) {
                
                var cliente = JSON.parse(data);
                var uls = "";
                for(var i = 0; i < cliente.resp.length; i++){
                    uls += "<a href='#' class='list-group-item list-group-item-info' name='" + cliente.resp[i].Cliente.id + "' onClick ='seleccionarCliente(this)'>" + cliente.resp[i].Cliente.nombre + " - " + cliente.resp[i].Cliente.nit + "</a>";
                }
                $('#datosCliente').show();
                $('#datosCliente').html(uls);
            }
        });    
};

function seleccionarCliente(datos){
    var clienteId = datos.name;
    var prefacturaId = $('#prefactId').val();

    $.ajax({
            url: $('#url-proyecto').val() + 'clientes/ajaxObtenerInfoCliente',
            data: {clienteId: clienteId},
            type: "POST",
            success: function(data) {               
                var cliente = JSON.parse(data);
                $('#PrefacturaDatoscliente').val(cliente.resp.Cliente.nombre);
                $('#PrefacturaNitcliente').val(cliente.resp.Cliente.nit);
                $('#PrefacturaTelefonocliente').val(cliente.resp.Cliente.celular);
                $('#PrefacturaDircliente').val(cliente.resp.Cliente.direccion);
                $('#PrefacturaDiascredcliente').val(cliente.resp.Cliente.diascredito);
                $('#PrefacturaLimitecredcliente').val(cliente.resp.Cliente.limitecredito);
                $('#FacturaIdcliente').val(cliente.resp.Cliente.id);
                $('#datosCliente').hide();    
                $('#PrefacturaProducto').prop('disabled', false);
                
                //se setean los hidden
                $('#PrefacturaNombrecliente').val(cliente.resp.Cliente.nombre);
                $('#PrefacturaDireccliente').val(cliente.resp.Cliente.direccion);
                $('#PrefacturaNitcccliente').val(cliente.resp.Cliente.nit);
                $('#PrefacturaDiascliente').val(cliente.resp.Cliente.diascredito);
                $('#PrefacturaTelcliente').val(cliente.resp.Cliente.celular);
                $('#PrefacturaLimitecliente').val(cliente.resp.Cliente.limitecredito);
                
                actualizarInfoClientePrefactura(cliente.resp.Cliente.id, prefacturaId);
            }
        });    

}

function actualizarInfoClientePrefactura(clienteId, prefacturaId){
    $.ajax({
            url: $('#url-proyecto').val() + 'prefacturas/ajaxActualizarClientePrefactura',
            data: {prefact: prefacturaId, cliente_id: clienteId},
            type: "POST",
            success: function(data) {               
                var cliente = JSON.parse(data);
            }
        });  
}

function limpirarFormularios(){
    activarFiltroProductoClienteNuevo();
    $('.registrado').val("");
    $('.rapida').val("");
}

function limpirarFormulariosRegistrados(){
    activarFiltroProductoVentaRapida();
    $('.registrado').val("");
    $('.nuevo').val("");   
}


function validarDatosUsuarioNuevo(){
    var mensaje = "";
    /*se obtienen los campos del formulario del cliente*/
    var nombre = $('#PrefacturaNuevonombre').val();
    var nit = $('#PrefacturaNuevonit').val();
    var direccion = $('#PrefacturaNuevodireccion').val();
    var diascredito = $('#PrefacturaNuevodiscredito').val();
    var limitecredito = $('#PrefacturaNuevolimitecredito').val();

    if(typeof(nombre) == "undefined" || nombre == ''){
        mensaje += "- Debe ingresar el nombre del cliente.<br>";
    }
    
    if(typeof(nit) == "undefined" || nit == ''){
        mensaje += "- Debe ingresar el Nit/C.C del cliente.<br>";
    }
    
    if(typeof(direccion) == "undefined" || direccion == ''){
        mensaje += "- Debe ingresar la dirección del cliente.<br>";
    }
    
    if(typeof(diascredito) == "undefined" || diascredito == ''){
        mensaje += "- Debe ingresar los días de crédito para el cliente.<br>";
    }
    
    if(typeof(limitecredito) == "undefined" || limitecredito == ''){
        mensaje += "- Debe ingresar el límite de crédito para el cliente.<br>";
    }
    
    return mensaje;
}

function fnObtenerDatosProductoVentaRapida(e){
    var usuarioId = $('#usuarioId').val();
    var mensaje = "";

    var key = (document.all) ? e.keyCode : e.which;
    if(key == 13){      
        mensaje = validarDatosVentaRapida();
        if(mensaje == ""){
            $.ajax({
               url: $('#url-proyecto').val() + 'prefacturas/addProductoClienteNuevoBarCode',
               data: {usuarioId: usuarioId, descProducto: $('#PrefacturaProductoventarapida').val(), prefacturaId: null},
               type: "POST",
               success: function(data) {
                    var prefactura = JSON.parse(data);
                    if(prefactura.valido){
                        $('#productosPrefacturas').append('<tr id="tr_' + prefactura.resp + '">' + 
                        '<td>' + prefactura.producto['0']['Producto']['descripcion'] + '</td>' + 
                        '<td>' + prefactura.producto['0']['Producto']['codigo'] + '</td>' +                                
                        '<td><input type="text" name="cant_' + prefactura.resp + '" class="form-control" id="cant_' + prefactura.resp + '" value="1" onblur="actualizarCantidadPrefact(this);">&nbsp;</td>' +
                        '<td><input type="text" name="precio_' + prefactura.resp + '" class="form-control ttalUnit" id="precio_' + prefactura.resp + '" value="' + prefactura.producto['0']['Cargueinventario']['precioventa'] + '" onblur="actualizarPrecioPrefact(this);">&nbsp;</td>' +
                        '<td><input type="text" name="total_' + prefactura.resp + '" class="form-control ttales ttalTotal" id="total_' + prefactura.resp + '" value="' + prefactura.producto['0']['Cargueinventario']['precioventa'] + '" readonly>&nbsp;</td>' +
                        '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + prefactura.resp + '"onclick="eliminarProductoPrefactura(this)"></td></tr>');                                                
                        $('#PrefacturaProductoventarapida').val("");
                        $('#datosProductoventarapida').hide();                         
                   }else{
                        $('#PrefacturaProductousuarionuevo').val("");
                        $('#PrefacturaProductoventarapida').val("");
                        $('#datosProductoventarapida').hide(); 
                        bootbox.alert(prefactura.mensaje);                        
                    }
                    sumarTotales();
               }
           });                
        }else{
            bootbox.alert(mensaje);
            $('#FacturaProducto').val("");                
        }
    }else{     
        mensaje = validarDatosVentaRapida();
        if(mensaje != ""){
            bootbox.alert(mensaje);
            $('#PrefacturaProductoventarapida').val("");
        }else{
            if($('#PrefacturaProductoventarapida').val().length > 3){
                $.ajax({
                    url: $('#url-proyecto').val() + 'cargueinventarios/ajaxProductosVenta',
                    data: {usuarioId: usuarioId, descProducto: $('#PrefacturaProductoventarapida').val()},
                    type: "POST",
                    success: function(data) {
                        var producto = JSON.parse(data);
                        var uls = "";
                        for(var i = 0; i < producto.resp.length; i++){
                            if(parseInt(producto.resp[i].Cargueinventario.existenciaactual) >= parseInt(1)){
                                uls += "<a href='#' class='list-group-item list-group-item-info' ";
                                uls += "name='" + producto.resp[i].Producto.id + "' ";
                                uls += "id='" + producto.resp[i].Cargueinventario.id + "' ";
                                uls += "onClick ='seleccionarProductoVentaRapida(this)'>" + producto.resp[i].Producto.descripcion;
                                uls += " - " + producto.resp[i].Producto.codigo;
                                uls += " Ref (" + producto.resp[i].Producto.referencia + ") ";
                                uls += producto.resp[i].Deposito.descripcion;
                                uls += "</a>";
                            }
                        }   
                        $('#datosProductoventarapida').show();
                        $('#datosProductoventarapida').html(uls);
                    }
                });                 
            }else{
                $('#datosProductoventarapida').hide();
                $('#datosProductoventarapida').html();                
            }        
        }
    }
}

function validarDatosVentaRapida(){
    var mensaje = "";
    /*se obtienen los campos del formulario del cliente*/
    var nombre = $('#PrefacturaRapidanombre').val();
    var nit = $('#PrefacturaRapidanit').val();

    if(typeof(nombre) == "undefined" || nombre == ''){
        mensaje += "- Debe ingresar el nombre del cliente.<br>";
    }    
    
    if(typeof(nit) == "undefined" || nit == ''){
        mensaje += "- Debe ingresar el Nit/C.C del cliente.<br>";
    }        
    
    return mensaje;
}

function seleccionarProductoClienteNuevo(dato){
    var productoId = dato.name; 
    var cargueInvId = dato.id;
    
    $("#div_producto").load(
        $('#url-proyecto').val() + "cargueinventarios/seleccionproductoordencompra",
        {
            productoId: productoId, cargueInvId: cargueInvId
        },
        function(){                                                            
            dialogDialogSeleccionProducto=$("#div_producto").dialog(opcDialogSeleccionProducto);
            dialogDialogSeleccionProducto.dialog('open');
            $('#datosProductoclientenuevo').hide();
        }
    );     
}

function agregarProductoFactura(){
    var cargueinventarioId = $('#cargueinventarioId').val();
    var cantidadventa = $('#cantidadventa').val();
    var precioventa = $('#precioventa').val();
    var usuarioId = $('#usuarioId').val();
    var porcentajeDescuento = Number($('#porcentaje').val());
    var valorDescuento = Number($('#descuento').val());
    var valorIva = $('#valorIva').val();
    var valorConIva = $('#valorConIva').val();
    var impuesto = $('#impuesto').val();
    var mensaje = "";    
    var precioventaCI = $('#precioventaCI').val();  // se obtiene el precio de venta antes de impuesto
    var clienteId = $('#FacturaIdcliente').val();
    var prefactId = $('#prefactId').val();

    mensaje = validarDatosFactura(cantidadventa,precioventa);

    if(mensaje == ""){
        $.ajax({
        url: $('#url-proyecto').val() + 'prefacturas/add',
        data: {usuarioId: usuarioId, clienteId: clienteId, cargueinventarioId: cargueinventarioId, 
            cantidadventa: cantidadventa, precioventa: precioventa, valorDescuento: valorDescuento,
            porcentajeDescuento: porcentajeDescuento, impuesto: impuesto, prefactId: prefactId
        },
        type: "POST",
        success: function(data) {
            var prefactura = JSON.parse(data);
            if(prefactura.resp != '0' && prefactura.resp != ""){
                $('#productosPrefacturas').append(
                        '<tr id="tr_' + prefactura.resp + '">' + '<input type="hidden" name="prcimpuesto_' + prefactura.resp + '" id="prcimpuesto_' + prefactura.resp + '" value="' + ((impuesto/100) + 1) + '">' + '</td>' + 
                        '<td>' + $('#nombreProducto').val() + '</td>' + 
                        '<td>' + $('#codigoProducto').val() + '</td>' + 
                        '<td><input type="text" name="cant_' + prefactura.resp + '" class="form-control" id="cant_' + prefactura.resp + '" value="' + cantidadventa + '" onblur="actualizarCantidadPrefact(this);">&nbsp;</td>' +
                        '<td><input type="text" name="precio_' + prefactura.resp + '" class="form-control numericPrice ttalUnit" id="precio_' + prefactura.resp + '" value="' + precioventa + '" onblur="actualizarPrecioPrefact(this);">&nbsp;</td>' +
                        '<td><input type="text" name="total_' + prefactura.resp + '" class="form-control ttales numericPrice ttalTotal" id="total_' + prefactura.resp + '" value="' + precioventaCI + '" readonly>&nbsp;</td>' +
                        '<td><input type="text" name="pordtto_' + prefactura.resp + '" class="form-control ttalPorDtto" id="pordtto_' + prefactura.resp + '" value="' + porcentajeDescuento + '" onblur="actualizarPorcentajeDtto(this);">&nbsp;</td>' +                        
                        '<td><input type="text" name="valdtto_' + prefactura.resp + '" class="form-control ttalValDtto numericPrice" id="valdtto_' + prefactura.resp + '" value="' + valorDescuento + '" onblur="actualizarValorDtto(this);">&nbsp;</td>' +                       
                        '<td><input type="text" name="valor_iva_' + prefactura.resp + '" class="form-control valor_iva numericPrice" id="valor_iva_' + prefactura.resp + '" value="' + valorIva + '" readonly>&nbsp;</td>' +                        
                        '<td><input type="text" name="valor_con_iva_' + prefactura.resp + '" class="form-control valor_con_iva numericPrice" id="valor_con_iva_' + prefactura.resp + '" value="' + valorConIva + '" readonly>&nbsp;</td>' +
                        '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + prefactura.resp + '"onclick="eliminarProductoPrefactura(this)"></td></tr>');
                $('#PrefacturaProducto').val("");
                $('.numericPrice').number(true);
                calcularTotalConAbonos();
                dialogDialogSeleccionProducto.dialog('close');                
            }else{
                bootbox.alert('No se pudo agregar el producto a la factura de venta. Por favor, inténtelo de nuevo.');
            }
                sumarTotales();
        }
        });         
    }else{
        bootbox.alert(mensaje);
    }
}

function agregarProductoOrdenCompra(){
    var impuesto = $('#impuesto').val();
    var usuarioId = $('#usuarioId').val();    
    var cargueinventarioId = $('#cargueinventarioId').val();
    var cantidadventa = $('#cantidadventa').val();
    var precioventa = $('#precioventa').val();
    var porcentajeDescuento = Number($('#porcentaje').val());
    var valorDescuento = Number($('#descuento').val());      
    var valorIva = $('#valorIva').val();
    var valorConIva = $('#valorConIva').val();    
    var precioventaCI = $('#precioventaCI').val();  // se obtiene el precio de venta antes de impuesto    
    var prefacturaId = $('#prefactId').val();   
    
    var mensaje = "";    

    mensaje = validarDatosFactura(cantidadventa,precioventa);

    if(mensaje == ""){
        
        $.ajax({
        url: $('#url-proyecto').val() + 'facturas/facturacionclientenuevo',
        data: {prefacturaId: prefacturaId, cargueinventarioId: cargueinventarioId, 
            cantidadventa: cantidadventa, precioventa: precioventa, usuarioId:usuarioId, valorDescuento: valorDescuento, 
            porcentajeDescuento: porcentajeDescuento, impuesto: impuesto
        },
        type: "POST",
        success: function(data) {
            var prefactura = JSON.parse(data);
            if(prefactura.resp != '0' && prefactura.resp != ""){
                $('#productosPrefacturas').append(
                        '<tr id="tr_' + prefactura.resp + '">' + 
                        '<td>' + $('#nombreProducto').val() + '<input type="hidden" name="prcimpuesto_' + prefactura.resp + '" id="prcimpuesto_' + prefactura.resp + '" value="' + ((impuesto/100) + 1) + '">' + '</td>' + 
                        '<td>' + $('#codigoProducto').val() + '</td>' + 
                        '<td><input type="text" name="cant_' + prefactura.resp + '" class="form-control" id="cant_' + prefactura.resp + '" value="' + cantidadventa + '" onblur="actualizarCantidadPrefact(this);">&nbsp;</td>' +                        
                        '<td><input type="text" name="precio_' + prefactura.resp + '" class="form-control ttalUnit numericPrice" id="precio_' + prefactura.resp + '" value="' + precioventa + '" onblur="actualizarPrecioPrefact(this);">&nbsp;</td>' +
                        '<td><input type="text" name="total_' + prefactura.resp + '" class="form-control ttales numericPrice ttalTotal" id="total_' + prefactura.resp + '" value="' + precioventaCI + '" readonly>&nbsp;</td>' +
                        '<td><input type="text" name="pordtto_' + prefactura.resp + '" class="form-control ttalPorDtto" id="pordtto_' + prefactura.resp + '" value="' + porcentajeDescuento + '" onblur="actualizarPorcentajeDtto(this);">&nbsp;</td>' +
                        '<td><input type="text" name="valdtto_' + prefactura.resp + '" class="form-control ttalValDtto numericPrice" id="valdtto_' + prefactura.resp + '" value="' + valorDescuento + '" onblur="actualizarValorDtto(this);">&nbsp;</td>' +
                        '<td><input type="text" name="valor_iva_' + prefactura.resp + '" class="form-control valor_iva numericPrice" id="valor_iva_' + prefactura.resp + '" value="' + valorIva + '" readonly>&nbsp;</td>' +
                        '<td><input type="text" name="valor_con_iva_' + prefactura.resp + '" class="form-control valor_con_iva numericPrice" id="valor_con_iva_' + prefactura.resp + '" value="' + valorConIva + '" readonly>&nbsp;</td>' +                        
                        '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + prefactura.resp + '"onclick="eliminarProductoPrefactura(this)"></td></tr>');   
                $('#PrefacturaProductousuarionuevo').val("");
                $('#PrefacturaProductoventarapida').val("");                
                $('.numericPrice').number(true);
                $('#prefacturadoId').val(prefactura.prefact);
                calcularTotalConAbonos();
                dialogDialogSeleccionProducto.dialog('close');
            }else{
                bootbox.alert('No se pudo agregar el producto a la factura de venta. Por favor, inténtelo de nuevo.');
            }
                sumarTotales();
        }
        });         
    }else{
        bootbox.alert(mensaje);
    }
}


function agregarProductoFacturaClienteNuevo(){
    var impuesto = $('#impuesto').val();
    var usuarioId = $('#usuarioId').val();    
    var cargueinventarioId = $('#cargueinventarioId').val();    
    var cantidadventa = $('#cantidadventa').val();
    var precioventa = $('#precioventa').val();
    var porcentajeDescuento = Number($('#porcentaje').val());
    var valorDescuento = Number($('#descuento').val());      
    var valorIva = $('#valorIva').val();
    var valorConIva = $('#valorConIva').val();    
    var precioventaCI = $('#precioventaCI').val();  // se obtiene el precio de venta antes de impuesto
    var prefacturaId = $('#prefacturadoId').val();
    var prefactId = $('#prefactId').val();
   
    var mensaje = "";    
    
    mensaje = validarDatosFactura(cantidadventa,precioventa);

    if(mensaje == ""){
        $.ajax({
        url: $('#url-proyecto').val() + 'facturas/facturacionclientenuevo',
        data: {prefacturaId: prefacturaId, cargueinventarioId: cargueinventarioId, 
            cantidadventa: cantidadventa, precioventa: precioventa, usuarioId:usuarioId, valorDescuento: valorDescuento, 
            porcentajeDescuento: porcentajeDescuento, impuesto: impuesto},
        type: "POST",
        success: function(data) {
            var prefactura = JSON.parse(data);
            if(prefactura.resp != '0' && prefactura.resp != ""){
                $('#productosPrefacturas').append(
                        '<tr id="tr_' + prefactura.resp + '">' + 
                        '<td>' + $('#nombreProducto').val() + '<input type="hidden" name="prcimpuesto_' + prefactura.resp + '" id="prcimpuesto_' + prefactura.resp + '" value="' + ((impuesto/100) + 1) + '">' + '</td>' + 
                        '<td>' + $('#codigoProducto').val() + '</td>' + 
                        '<td><input type="text" name="cant_' + prefactura.resp + '" class="form-control" id="cant_' + prefactura.resp + '" value="' + cantidadventa + '" onblur="actualizarCantidadPrefact(this);">&nbsp;</td>' +
                        '<td><input type="text" name="precio_' + prefactura.resp + '" class="form-control ttalUnit numericPrice" id="precio_' + prefactura.resp + '" value="' + precioventa + '" onblur="actualizarPrecioPrefact(this);">&nbsp;</td>' +
                        '<td><input type="text" name="total_' + prefactura.resp + '" class="form-control ttales numericPrice ttalTotal" id="total_' + prefactura.resp + '" value="' + precioventaCI + '" readonly>&nbsp;</td>' +
                        '<td><input type="text" name="pordtto_' + prefactura.resp + '" class="form-control ttalPorDtto" id="pordtto_' + prefactura.resp + '" value="' + porcentajeDescuento + '" onblur="actualizarPorcentajeDtto(this);">&nbsp;</td>' +
                        '<td><input type="text" name="valdtto_' + prefactura.resp + '" class="form-control ttalValDtto numericPrice" id="valdtto_' + prefactura.resp + '" value="' + valorDescuento + '" onblur="actualizarValorDtto(this);">&nbsp;</td>' +
                        '<td><input type="text" name="valor_iva_' + prefactura.resp + '" class="form-control valor_iva numericPrice" id="valor_iva_' + prefactura.resp + '" value="' + valorIva + '" readonly>&nbsp;</td>' +
                        '<td><input type="text" name="valor_con_iva_' + prefactura.resp + '" class="form-control valor_con_iva numericPrice" id="valor_con_iva_' + prefactura.resp + '" value="' + valorConIva + '" readonly>&nbsp;</td>' +                        
                        '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + prefactura.resp + '"onclick="eliminarProductoPrefactura(this)"></td></tr>');
                $('#FacturaProductousuarionuevo').val("");
                $('#FacturaProductoventarapida').val("");
                $('.numericPrice').number(true);
                $('#prefacturadoId').val(prefactura.prefact);
                calcularTotalConAbonos();
                dialogDialogSeleccionProducto.dialog('close');
            }else{
                bootbox.alert('No se pudo agregar el producto a la factura de venta. Por favor, inténtelo de nuevo.');
            }
                sumarTotales();
        }
        });         
    }else{
        bootbox.alert(mensaje);
    }
}

function seleccionarProductoVentaRapida(dato){
    var productoId = dato.name;  
    var cargueInvId = dato.id;
    
    $("#div_producto").load(
        $('#url-proyecto').val() + "cargueinventarios/seleccionproductoventaclientenuevo",
        {
            productoId: productoId, cargueInvId: cargueInvId
        },
        function(){                                                            
            dialogDialogSeleccionProducto=$("#div_producto").dialog(opcDialogSeleccionProducto);
            dialogDialogSeleccionProducto.dialog('open');
            $('#datosProductoventarapida').hide();
        }
    );    
}


function activarFiltroProductoClienteNuevo(){
    var cliente = $('#PrefacturaNuevonombre').val();
    var nit = $('#PrefacturaNuevonit').val();
    var dir = $('#PrefacturaNuevodireccion').val();
    var diasCred = $('#PrefacturaNuevodiscredito').val();
    var limCred = $('#PrefacturaNuevolimitecredito').val();
    
    if(cliente.length == '0' || nit.length == '0' || dir.length == '0' || diasCred.length == '0' || limCred.length == '0'){
        $('#PrefacturaProductousuarionuevo').prop('disabled', true);
    }else{
        $('#PrefacturaProductousuarionuevo').prop('disabled', false);
    }
}

function activarFiltroProductoVentaRapida(){
    var nombre = $('#PrefacturaRapidanombre').val();
    var nit = $('#PrefacturaRapidanit').val();
    if(nombre.length == '0' || nit.length == '0'){
        $('#PrefacturaProductoventarapida').prop('disabled', true);
    }else{
        $('#PrefacturaProductoventarapida').prop('disabled', false);
    }    
}

/**
 * editar la orden de trabajo
 * @returns {undefined}
 */
var editarOrdenTrabajo = function(){
    var idOrden = $('#ordentrabajoId').val();
    window.location.href = $('#url-proyecto').val() + "ordentrabajos/edit/" + idOrden;
};

/**
 * Totalizar los valores de la venta
 * @returns {undefined}
 */
var sumarTotales = function (){
    var ttalUnit = 0;
    var ttalTotal = 0;
    var ttalValDtto = 0;
    var ttalesFinal = 0;        
    var ttalesIva = 0;
    var ttalesConIva = 0;    
    
    //se suman todos los valores unitarios
    $( ".ttalUnit" ).each(function( index, val ) {
        ttalUnit += parseInt($(this).val());
    });    
    
    $('.thTUnit').html(formatNumber(ttalUnit));

    //se suman todos los valores totales
    $( ".ttalTotal" ).each(function( index, val ) {
        ttalTotal += parseInt($(this).val());
    });        
    
    $('.thTTotal').html(formatNumber(ttalTotal));
    
    //se suman todos los valores de descuento
    $( ".ttalValDtto" ).each(function( index, val ) {
        ttalValDtto += parseInt($(this).val());
    });        
    
    $('.thDtto').html(formatNumber(ttalValDtto));
    
    //se suman todos los valores de los totales finales
    $( ".ttalesFinal" ).each(function( index, val ) {
        ttalesFinal += parseInt($(this).val());
    });        
    
    $('.thTFinal').html(formatNumber(ttalesFinal)); 
    
    //se suman los totales del iva
    $(".valor_iva").each(function(index, val){
       ttalesIva += parseInt($(this).val());
    });
    
    $('.thIVA').html(formatNumber(ttalesIva));
    
    //se suman los totales con iva incluido
    $('.valor_con_iva').each(function(index, val){
        ttalesConIva += parseInt($(this).val());
    });
    
    $('.thTFCIVA').html(formatNumber(ttalesConIva));
    
};

/**
 * Separadores de miles para los valores de la cotizacion que se van a imprimir
 * @param {type} num
 * @returns {Number|String}
 */
var formatNumber = function(num) {
    if (!num || num == 'NaN') return '-';
    if (num == 'Infinity') return '&#x221e;';
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
        num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();
    if (cents < 10)
        cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
        num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
    return (((sign) ? '' : '-') + num);
};

/**
 * Actualiza el valor del descuento basado en el porcentaje
 * @param {type} data
 * @returns {undefined}
 */
var actualizarPorcentajeDtto = function(data){
    var arrId = (data.id).split("_");
        
    calcularValorDescuento(arrId['1']);
    calcularTotalConDescuentoTabla(arrId['1']);      
    sumarTotales();
    calcularTotalConAbonos();
    
    //se actualizan registros en la bd
    var nuevoPor = $('#pordtto_' + arrId['1']).val();
    var valDtto = $('#valdtto_' + arrId['1']).val();
    actualizarValorPorcentajeDtto(nuevoPor, valDtto, arrId['1']);
    calcularValorIvaTabla(arrId['1']);
};

/**
 * Actualiza el registro en base de datos del valor y el porcentaje de descuento sobre el producto
 * @param {type} nuevoPor
 * @param {type} valDtto
 * @param {type} idPref
 * @returns {undefined}
 */
var actualizarValorPorcentajeDtto = function(nuevoPor, valDtto, idPref){
    $.ajax({
        url: $('#url-proyecto').val() + 'prefacturas/actualizarPorcentajeValorDtto',
        data: {nuevoPor: nuevoPor, valDtto: valDtto, idPref: idPref},
        type: "POST",
        success: function(data) {
            var resp = JSON.parse(data);
            if(resp == '0'){
                bootbox.alert('No fue posible realizar la actualización del registro. Por favor, inténtelo de nuevo');                
            }
        }
    });    
};

/**
 * Actualiza el valor del porcentaje basado en el monto de descuento
 * @param {type} data
 * @returns {undefined}
 */
var actualizarValorDtto = function(data){
    var arrId = (data.id).split("_");
  
    actualizarDescuentoPorValorTabla(arrId['1']);
    calcularTotalConDescuentoTabla(arrId['1']); 
    calcularValorIvaTabla(arrId['1']);
    sumarTotales();
    calcularTotalConAbonos();
    
    //se actualiza el registro en la bd
    var porDtto = $('#pordtto_' + arrId['1']).val();
    var nuevoVal = $('#valdtto_' + arrId['1']).val();
    actualizarValorPorcentajeDtto(porDtto, nuevoVal, arrId['1']);
};

var calcularValorIva = function(){
    var prcIva = $('#impuesto').val();   
    var subTtal = $('#ttaldescuento').val();
    
    var valIva = subTtal * parseFloat("0." + prcIva); 
    $('#valorIva').val(Math.ceil(valIva));
    $('#valorConIva').val(Math.ceil(parseFloat(subTtal) + parseFloat(valIva)));
    
};


var actualizarIVA = function(data){
    var arrId = (data.id).split("_");
    var ttalConDto = $('#totalFinal_' + arrId['1']).val();
    var prcIVA = $('#porc_iva_' + arrId['1']).val();
    
    //se calcula el nuevo valor del iva
    var nuevoIVA = ttalConDto * parseFloat(parseFloat(prcIVA)/100);
    
    //se calcula el valor con iva
    var nuevoValConIva = parseFloat(ttalConDto) + parseFloat(nuevoIVA);
    
    $('#valor_iva_' + arrId['1']).val(formatNumber(Math.ceil(nuevoIVA)));
    $('#valor_con_iva_' + arrId['1']).val(formatNumber(Math.ceil(nuevoValConIva)));
    
    sumarTotales();    
};

/**
 * se calculan los abonos y el total final de la cuenta
 * @returns {undefined}
 */
function calcularTotalConAbonos(){
    var ttalCompras = 0;
    var ttalAbonos = $('.ttalAbonos').val();
    
    if(parseInt(ttalAbonos) > 0){
        $(".valor_con_iva").each(function() {
            ttalCompras = Number(ttalCompras) + Number($(this).val());
        });            

        var abn = "<tr><th colspan='8' class='text-right'>Abonos</th>";
        abn += "<th class='text-right'>" + formatNumber(ttalAbonos) + "</th></tr>";
        abn += "<tr><th colspan='8' class='text-right'>TOTAL</th>";
        abn += "<th class='text-right'>" + formatNumber(ttalCompras - parseInt(ttalAbonos)) + "</th></tr>";
        $('#tBodAbonos').html(abn);        
    }         
}

/**
 * Guarda las observaciones de la factura
 * @returns {undefined}
 */
var guardarObsFactura = function(){
    var prefacturaId = $('#prefacturadoId').val();
    if(prefacturaId != ""){        
        $.ajax({
            url: $('#url-proyecto').val() + 'prefacturas/guardarObservacionPrefact',
            data: {prefact: prefacturaId, observacion: $(this).val()},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if(resp == '0'){
                    bootbox.alert('No fue posible guardar la observacion. Por favor, inténtelo de nuevo.');                
                }
            }
        });         
    }else{        
        bootbox.alert('Debe seleccionar un cliente y agregar un producto para ingresar la observación.');
        $(this).val("");
    }
};

$( function() {
    validarExisteCliente();
    validarProductosPrefacturados();      
    $('#PrefacturaDatoscliente').keyup(fnObtenerDatosCliente);   
    $('#PrefacturaProductousuarionuevo').prop('disabled', true); //keyup(fnObtenerDatosProductoUsuarioNuevo);
    $('#PrefacturaProductoventarapida').prop('disabled', true); //keyup(fnObtenerDatosProductoVentaRapida); 
    $('#editOrden').click(editarOrdenTrabajo);    
    $('#obs_fact').blur(guardarObsFactura);
});


