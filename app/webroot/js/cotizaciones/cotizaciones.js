/**
 * Se crea la cotizacion
 * @returns {undefined}
 */
function guardarCotizacionClienteRegistrado(){

    var formData = new FormData($('#CotizacioneAddForm')[0]);    
    
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
function seleccionarCliente(datos){
    var clienteId = datos.name;
 
    $.ajax({
            url: $('#url-proyecto').val() + 'clientes/ajaxObtenerInfoCliente',
            data: {clienteId: clienteId},
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
function activarFiltroProducto(){
    var cliente = $('#CotizacioneDatoscliente').val();
    if(cliente.length <= '0'){
        $('#CotizacioneProducto').prop('disabled', true);
    }else{
        $('#CotizacioneProducto').prop('disabled', false);
    }
}

/**
 * obtiene el cliente que se desea agregar a la cotizacion
 * @returns {undefined}
 */
var fnObtenerDatosCliente = function(){
    
    activarFiltroProducto();
    $('.nuevo').val("");
    $('.rapida').val("");
    var datosCliente = $('#CotizacioneDatoscliente').val();    
    var empresaId = $('#empresaId').val();
    
    if(parseInt(datosCliente.length) > 3){
        $.ajax({
            url: $('#url-proyecto').val() + 'clientes/ajaxObtenerClientes',
            data: {datosCliente: datosCliente, empresaId: empresaId},
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
        
    }else{
        $('#datosCliente').show();
        $('#datosCliente').html("");
    }
};

/**
 * Se valia el formulario para crear un cliente nuevo
 * @returns {String}
 */
var validarDatosCliente = function(){
    var mensaje = "";
    
    if($('#CotizacioneNuevonombre').val() == ""){
        mensaje += "- Debe ingresar el nombre del cliente.<br>";
    }
    
    if($('#CotizacioneNuevonit').val() == ""){
        mensaje += "- Debe ingresar el Nit/C.C del cliente.<br>";
    }
    
    if($('#CotizacioneNuevodireccion').val() == ""){
        mensaje += "- Debe ingresar la dirección del cliente.<br>";
    }
    
    if($('#CotizacioneNuevodiscredito').val() == ""){
        mensaje += "- Debe ingresar los días de crédito para el cliente.<br>";
    }
    
    if($('#CotizacioneNuevolimitecredito').val() == ""){
        mensaje += "- Debe ingresar el límite de crédito para el cliente.<br>";
    }
    
    return mensaje;
};

/**
 * Se crea el cliente nuevo para la cotizacion
 * @returns {undefined}
 */
var guardarCliente = function(){
    var mensaje = "";
    mensaje = validarDatosCliente();
    
    if(mensaje == ""){
        
        var formData = new FormData($('#CotizacioneAddForm')[0]);    

        $.ajax({
            url: $('#url-proyecto').val() + 'clientes/ajaxCrearCliente',
            type: 'POST',        
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                var respuesta = JSON.parse(data);
                if(respuesta.resp != '0'){
                    $('#CotizacioneIdcliente').val(respuesta.cliente);
                    $('#cotizacionId').val(respuesta.resp);   
                    $('#btn_aprobar_cot').prop('disabled', false); 
                    $('#CotizacioneProductousuarionuevo').prop('disabled', false); 
                }else{
                    bootbox.alert('No fue posible crear el cliente y la cotización. Por favor, inténtelo de nuevo.')
                }

            }
        });         
        
    }else{
        bootbox.alert(mensaje);
    }
};

/**
 * se valida si el formulario de venta rapida esta diligenciado
 * @returns {String}
 */
var validarFormCotizacionRapida = function(){
  var mensaje = "";
  
  if($('#CotizacioneRapidanombre').val() == ""){
      mensaje += "- Debe ingresar el nombre del cliente.<br>";
  }
  
  if($('#CotizacioneRapidanit').val() == ""){
      mensaje += "- Debe ingresar el Nit/C.C del cliente.<br>";
  }
  
  return mensaje;
};

/**
 * Crea o actualiza la cotizacion de rapida
 * @returns {undefined}
 */
var crearActualizaCotizacionRapida = function(){
    var mensaje = "";
    mensaje = validarFormCotizacionRapida();
    
    if(mensaje == ""){
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
    }else{
        bootbox.alert(mensaje);
    }
};

var aprobarCotizacion = function(){
    var idCotizacion = $('#cotizacionId').val();
    if(idCotizacion == ""){
        bootbox.alert('No existe cotización para aprobar.');
    }else{
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizaciones/ajaxAprobarCotizacion',
            data: {idCotizacion: idCotizacion},
            type: "POST",
            success: function(data) {

                var result = JSON.parse(data);
                if(result.resp == '1'){
                    window.location.href = $('#url-proyecto').val() + 'ordentrabajos/edit/' + result.idOrden;
                }else if(result.resp == '0'){
                    bootbox.alert('No fue posible aprobar la cotización. Por favor, inténtelo de nuevo.');
                }else if(result.resp == '2'){
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
var actCantPrdCot = function(data){    
    var arrData = data.id.split("_");    
    var nuevaCant = $('#' + data.id).val();
    var valUnit = $('#vUnit_' + arrData['1']).val();
    var valorNuevo = parseInt($('#vUnit_' + arrData['1']).val()) * nuevaCant;
    
    if(nuevaCant != ""){
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizacionesdetalles/ajaxActualizarCantidadCotiza',
            data: {cotDetId: arrData['1'], nuevaCant: nuevaCant, valUnit: valUnit},
            type: "POST",
            success: function(response) {
                var resp = JSON.parse(response);
                if(resp.resp != '1'){
                    bootbox.alert('No fue posible actualizar el registro. Por favor, inténtelo de nuevo.');
                }else{
                    $('#vTtal_' + arrData['1']).val(valorNuevo); 
                    calcularTtalCot();
                }
            }            
        });         
    }else{
        bootbox.alert('La cantidad no puede estar vacía');
    }
    
};

/**
 * Actualiza el valor unitario del producto
 * @param {type} data
 * @returns {undefined}
 */
var actValUnitPrdCot = function(data){
    var arrData = data.id.split("_");
    var cotDetId = arrData['1'];
    var nuevoValor = $('#' + data.id).val();
    var cantidad = $('#cant_' + arrData['1']).val();
    
    if(nuevoValor != ""){
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizacionesdetalles/ajaxActualizarValorUnitarioCotiza',
            data: {cotDetId: cotDetId, nuevoValor: nuevoValor, cantidad: cantidad},
            type: "POST",
            success: function(response) {
                var resp = JSON.parse(response);
                if(resp.resp != '1'){
                    bootbox.alert('No fue posible actualizar el registro. Por favor, inténtelo de nuevo.');
                }else{
                    var nuevoTotal = parseInt(nuevoValor) * parseInt(cantidad);
                    $('#vTtal_' + arrData['1']).val(nuevoTotal); 
                    calcularTtalCot();
                }
            }            
        });          
    }else{
        bootbox.alert('El valor del producto no puede estar vacio');
    }
};


/**
 * Eliminar el registro seleccionado de la cotizacion
 * @param {type} data
 * @returns {undefined}
 */
var eliminarPrdCot = function(data){    
    var idDetCot = data.id;
    
    $.ajax({
        url: $('#url-proyecto').val() + 'cotizacionesdetalles/ajaxEliminarDetalleCotiza',
        data: {idDetCot: idDetCot},
        type: "POST",
        success: function(response) {
            var resp = JSON.parse(response);
            if(resp.resp == '1'){
                $('#tr_' + data.id).remove();
                calcularTtalCot();
            }else{
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
var seleccionarProductoCotizacion= function(data){  
    var nomProduct = "";    
    if (typeof(data.name) != "undefined"){
        nomProduct = data.text;
    }else{
        nomProduct = $('#CotizacioneProducto').val();
    }    
    var cotizacionId = $('#cotizacionId').val();
    
    if(nomProduct != ""){
        //se guarda el registro del producto para la cotizacion
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizacionesdetalles/ajaxGuardarDetalleCotiza',
            data: {catizacionId: cotizacionId, nomProduct: nomProduct},
            type: "POST",
            success: function(response) {
                var resp = JSON.parse(response);
                var inf = "";
                if(resp.valid == '1'){
                    inf += "<tr id='tr_" + resp.resp + "'>";
                    inf += "<td>" + nomProduct + "</td>";
                    inf += '<td><input type="text" id="cant_' + resp.resp + '" class="form-control ttales" value="1" onblur="actCantPrdCot(this)">&nbsp;</td>';
                    inf += '<td><input type="text" id="vUnit_' + resp.resp + '" class="form-control ttales" value="0" onblur="actValUnitPrdCot(this)">&nbsp;</td>';
                    inf += '<td><input type="text" id="vTtal_' + resp.resp + '" class="form-control ttales tfinal" value="0" readonly>&nbsp;</td>';
                    inf += '<td><input type="button" class="btn btn-primary btn-sm" value="Eliminar" id="' + resp.resp + '"onclick="eliminarPrdCot(this)"></td>';
                    inf += "</tr>";                
                    $('#dvTCot').append(inf);
                    $('.ttales').number(true);
                    calcularTtalCot();
                    $('#datosProducto').html("").hide();
                    $('#CotizacioneProducto').val("");
                }else{
                    bootbox.alert('No fue posible agregar el producto. Por favor, inténtelo de nuevo.');
                }
            }            
        });         
    }else{
        bootbox.alert('El campo producto no debe estar vacio');
    }    
};

/**
 * Obtener los productos para cliente registrado
 * @returns {undefined}
 */
var fnObtenerProductoCotizacion = function(){
    var cotizacionId = $('#cotizacionId').val();    
    var usuarioId = $('#CotizacioneVendedor').val();
    var descProd = $(this).val();
    
    if(cotizacionId == ""){
        bootbox.alert("No se ha creado la cotización.");        
        $(this).val("");
    }else{        
        if(descProd.length > 3){
            $.ajax({
                url: $('#url-proyecto').val() + 'cargueinventarios/ajaxObtenerProductoCotizacion',
                data: {descProducto: descProd, usuarioId: usuarioId},
                type: "POST",
                success: function(data) {
                    var producto = JSON.parse(data);
                    var uls = "";
                    for(var i = 0; i < producto.resp.length; i++){
                        uls += "<a href='#' class='list-group-item list-group-item-info' name='" + producto.resp[i].Cargueinventario.id + "' onClick ='seleccionarProductoCotizacion(this)'>" + producto.resp[i].Producto.descripcion + " - " + producto.resp[i].Producto.codigo + "</a>";
                    }
                    $('#datosProducto').show();
                    $('#datosProducto').html(uls);
                    calcularTtalCot();
                }            
            });
        }else{
            $('#datosProducto').hide();
            $('#datosProducto').html("");            
        }         
    }
};

/**
 * Obtener los productos para cliente nuevo
 * @returns {undefined}
 */
var fnObtenerProductoCotCliNuevo = function(){
    var cotizacionId = $('#cotizacionId').val();    
    var usuarioId = $('#CotizacioneVendedor').val();
    var descProd = $(this).val();
    
    if(cotizacionId == ""){
        bootbox.alert("No se ha creado la cotización.");        
        $(this).val("");
    }else{        
        if(descProd.length > 3){
            $.ajax({
                url: $('#url-proyecto').val() + 'cargueinventarios/ajaxObtenerProductoCotizacion',
                data: {descProducto: descProd, usuarioId: usuarioId},
                type: "POST",
                success: function(data) {
                    var producto = JSON.parse(data);
                    var uls = "";
                    for(var i = 0; i < producto.resp.length; i++){
                        uls += "<a href='#' class='list-group-item list-group-item-info' name='" + producto.resp[i].Cargueinventario.id + "' onClick ='seleccionarProductoCotizacion(this)'>" + producto.resp[i].Producto.descripcion + " - " + producto.resp[i].Producto.codigo + "</a>";
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
};

/**
 * Obtener los productos para cotizacion rapida
 * @returns {undefined}
 */
var fnObtenerProductoCotRapida = function(){    
    var cotizacionId = $('#cotizacionId').val();    
    var usuarioId = $('#CotizacioneVendedor').val();
    var descProd = $(this).val();
    
    if(cotizacionId == ""){
        bootbox.alert("No se ha creado la cotización.");        
        $(this).val("");
    }else{        
        if(descProd.length > 3){
            $.ajax({
                url: $('#url-proyecto').val() + 'cargueinventarios/ajaxObtenerProductoCotizacion',
                data: {descProducto: descProd, usuarioId: usuarioId},
                type: "POST",
                success: function(data) {
                    var producto = JSON.parse(data);
                    var uls = "";
                    for(var i = 0; i < producto.resp.length; i++){
                        uls += "<a href='#' class='list-group-item list-group-item-info' name='" + producto.resp[i].Cargueinventario.id + "' onClick ='seleccionarProductoCotizacion(this)'>" + producto.resp[i].Producto.descripcion + " - " + producto.resp[i].Producto.codigo + "</a>";
                    }
                    $('#datosProductoventarapida').show();
                    $('#datosProductoventarapida').html(uls);
                }            
            });
        }else{
            $('#datosProductoventarapida').hide();
            $('#datosProductoventarapida').html("");            
        }         
    }    
};

/**
 * Se calcula el valor total de la cotizacion
 * @returns {undefined}
 */
var calcularTtalCot = function(){
    var tFinal = 0;
    $('.tfinal').each(function(){
        tFinal = parseInt(tFinal) + parseInt($(this).val());
    });
    
    $('#resultCot').html(tFinal).number(true);
};

/**
 * Separadores de miles para los valores de la cotizacion que se van a imprimir
 * @param {type} num
 * @returns {Number|String}
 */
function formatNumber(num) {
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
}

/**
 * Imprime la cotizacion
 * @returns {undefined}
 */
var imprimirCotizacion = function(){
    var cotizacionId = $('#cotizacionId').val(); 
    
    if(cotizacionId == ""){
        bootbox.alert('No se ha creado la cotización.');
    }else{
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizacionesdetalles/ajaxObtenerDetallesCotizacion',
            data: {cotizacionId: cotizacionId},
            type: "POST",
            success: function(data) {
                var productos = JSON.parse(data);                
                var mywindow = window.open('', 'PRINT', 'height=400,width=600');
                mywindow.document.write('<html><head>');
                mywindow.document.write('<style media=screen>body { font-family: Lucidatypewriter, monospace; font-size: 20px; } } </style>');
                mywindow.document.write('<style media=print>@page {margin: 5mm;} @page footer {page-break-after: always;} @page rotated {size: portrait} #tinfop {background-color:#FFF; font-family: Lucidatypewriter, monospace; font-size: 10px; } </style>');    
                mywindow.document.write('</head>');
                mywindow.document.write('<body>');
                mywindow.document.write('<div style="margin:0px; width:100%; font-family:sans-serif; font-size:15px;">');
                mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');    
                mywindow.document.write('<h4><b>' + $('#nombre_empresa').val() +'</b></h4></div>');
                mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
                mywindow.document.write('<h4><b>COTIZACIÓN</b></h4></div>');
                mywindow.document.write($('#dv_info_emp').html());
                mywindow.document.write('<div style="width:100%; float:left; margin-top:20px; margin-bottom:5px";>');    
                mywindow.document.write('<b>Vendedor: </b>' + $('#CotizacioneVendedor option:selected').text() +'</div>');

                if($('#CotizacioneDatoscliente').val() != ""){
                    mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Cliente: </b>' + $('#CotizacioneDatoscliente').val() + '</div></div>');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Teléfono: </b>' + $('#CotizacioneTelefonocliente').val() + '</div></div></div>');
                    mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                    mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Identificación: </b>' + $('#CotizacioneNitcliente').val() + '</div></div>');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Dirección: </b>' + $('#CotizacioneDircliente').val() + '</div></div></div></div>');        
                }else if($('#CotizacioneNuevonombre').val() != ""){
                    mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Cliente: </b>' + $('#CotizacioneNuevonombre').val() + '</div></div>');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Teléfono: </b>' + $('#CotizacioneNuevotelefono').val() + '</div></div></div>');
                    mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                    mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Identificación: </b>' + $('#CotizacioneNuevonit').val() + '</div></div>');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Dirección: </b>' + $('#CotizacioneNuevodireccion').val() + '</div></div></div></div>');                
                }else{
                    mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Cliente: </b>' + $('#CotizacioneRapidanombre').val() + '</div></div>');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Teléfono: </b>' + $('#CotizacioneRapidatelefono').val() + '</div></div></div>');
                    mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                    mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Identificación: </b>' + $('#CotizacioneRapidanit').val() + '</div></div>');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Dirección: </b>' + $('#CotizacioneRapidadireccion').val() + '</div></div></div></div>');         
                }
                
                if($('#CotizacionePlaca').val() != ""){
                    mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Placa Vehículo: </b>' + $('#CotizacionePlaca').val() + '</div></div></div></div>');
                }
                
                mywindow.document.write('<div style="margin-top: 30px; float: left; width: 100%; aling-items: center; justify-content: center">');        
                mywindow.document.write('<table style="font-family:sans-serif; font-size:15px; border-collapse: collapse; width: 100%;"><thead>');
                mywindow.document.write('<tr><th align="left">Detalle</th><th align="left">Cantidad</th><th align="right">Valor Unitario</th><th align="right">Valor Total</th></tr>');
                mywindow.document.write('</thead>');
                if(productos.resp.length > 0){
                    mywindow.document.write('<tbody>');
                    if(productos.resp.length > 0){
                        $.each(productos.resp, function(k, val){
                            mywindow.document.write('<tr>');
                            mywindow.document.write('<td align="left">' + val.Cotizacionesdetalle.nombreproducto + '</td>');
                            mywindow.document.write('<td align="left">' + val.Cotizacionesdetalle.cantidad + '</td>');
                            mywindow.document.write('<td align="right">' + formatNumber(val.Cotizacionesdetalle.costoventa) + '</td>');
                            mywindow.document.write('<td align="right">' + formatNumber(val.Cotizacionesdetalle.costototal) + '</td>');                            
                            mywindow.document.write('</tr>');
                        });
                    }  
                    mywindow.document.write('</tbody>');
                }                                                                   
                mywindow.document.write('<tr><td colspan="2"></td><td align="right"><b>TOTAL</b></td>');
                mywindow.document.write('<td align="right">' + $('#resultCot').html() + '</td>');
                mywindow.document.write('</tr></table></div>');  
                if($('#observacion').val() != ""){
                    mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:100%" align="left">');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Nota: </b><br>' + $('#observacion').val() + '</div></div></div></div>');                    
                }                   
                mywindow.document.write($('#dv_emisor').html());                 
                mywindow.document.write('</div>');          
                mywindow.document.write('</body></html>');
                mywindow.document.title = $('#CotizacioneDatoscliente').val() + " - COTIZACION";
                mywindow.document.close(); 
                mywindow.focus();
                mywindow.print();
                mywindow.close();                    
            }            
        });         
    }
};

var cambiarResponsableCotiza = function(){
    var vendedor = $('#CotizacioneVendedor').val();
    var cotizacionId = $('#cotizacionId').val();
    
    $.ajax({
        url: $('#url-proyecto').val() + 'cotizaciones/ajaxCambiarResponsableCotiza',
        data: {vendedor: vendedor, cotizacionId: cotizacionId},
        type: "POST",
        success: function(data) {
            var resp = JSON.parse(data);
            if(resp.resp != '1'){
                bootbox.alert('No fue posible actualizar el responsable de la cotización. Por favor, inténtelo de nuevo.');
            }
        }            
    });    
    
};

/**
 * Formato a los input tipo date
 * @returns {undefined}
 */
var datePicker = function(){
    $(".date").datepicker({dateFormat: 'yy-mm-dd'});
    $(".date").datepicker("option", "showAnim", "slideDown");    
};

/**
 * Actualizar Datos del cliente registrado
 * @param {type} datos
 * @returns {undefined}
 */

var actualizarNitCliente = function(){
    var clienteId = $('#CotizacioneIdcliente').val();
    if(typeof(clienteId) != "undefined" && clienteId != ""){
        $.ajax({
                url: $('#url-proyecto').val() + 'clientes/ajaxActualizarNitCliente',
                data: {clienteId: clienteId, nit: $('#CotizacioneNitcliente').val()},
                type: "POST",
                success: function(data) {               
                    var respuesta = JSON.parse(data);
                    bootbox.alert(respuesta.resp);
                }
            });        
    }else{
        bootbox.alert('Debe seleccionar un cliente.');
    }
};

var actualizarTelefonoCliente = function(){
    var clienteId = $('#CotizacioneIdcliente').val();
    if(typeof(clienteId) != "undefined" && clienteId != ""){
        $.ajax({
                url: $('#url-proyecto').val() + 'clientes/ajaxActualizarTelCliente',
                data: {clienteId: clienteId, telefono: $('#CotizacioneTelefonocliente').val()},
                type: "POST",
                success: function(data) {               
                    var respuesta = JSON.parse(data);
                    bootbox.alert(respuesta.resp);
                }
            });        
    }else{
        bootbox.alert('Debe seleccionar un cliente.');
    }    
};

var actualizarDireccionCliente = function(){
    var clienteId = $('#CotizacioneIdcliente').val();
    if(typeof(clienteId) != "undefined" && clienteId != ""){
        $.ajax({
                url: $('#url-proyecto').val() + 'clientes/ajaxActualizarDirCliente',
                data: {clienteId: clienteId, direccion: $('#CotizacioneDircliente').val()},
                type: "POST",
                success: function(data) {               
                    var respuesta = JSON.parse(data);
                    bootbox.alert(respuesta.resp);
                }
            });        
    }else{
        bootbox.alert('Debe seleccionar un cliente.');
    }    
};

var actualizarDiasLimite = function(){
    var clienteId = $('#CotizacioneIdcliente').val();
    if(typeof(clienteId) != "undefined" && clienteId != ""){
        $.ajax({
                url: $('#url-proyecto').val() + 'clientes/ajaxActualizarDiasCliente',
                data: {clienteId: clienteId, diascredito: $('#CotizacioneDiascredcliente').val()},
                type: "POST",
                success: function(data) {               
                    var respuesta = JSON.parse(data);
                    bootbox.alert(respuesta.resp);
                }
            });        
    }else{
        bootbox.alert('Debe seleccionar un cliente.');
    }    
};

var actualizarCreditoLimite = function(){
    var clienteId = $('#CotizacioneIdcliente').val();
    if(typeof(clienteId) != "undefined" && clienteId != ""){
        $.ajax({
                url: $('#url-proyecto').val() + 'clientes/ajaxActualizarCredCliente',
                data: {clienteId: clienteId, limitecredito: $('#CotizacioneLimitecredcliente').val()},
                type: "POST",
                success: function(data) {               
                    var respuesta = JSON.parse(data);
                    bootbox.alert(respuesta.resp);
                }
            });        
    }else{
        bootbox.alert('Debe seleccionar un cliente.');
    }    
};

var guardarObservacion = function(){
    
    var observacion = $(this).val();
    var cotizacionId = $('#cotizacionId').val();
    
    if(cotizacionId != ""){
            $.ajax({
                url: $('#url-proyecto').val() + 'cotizaciones/ajaxGuardarCotizacion',
                data: {cotizacion: cotizacionId, observacion: observacion},
                type: "POST",
                success: function(data) {               
                    var respuesta = JSON.parse(data);
                    if(respuesta.resp){
                    }else{
                        bootbox.alert("No fue posible guardar la observación. Por favor, inténtelo nuevamente");
                    }                    
                }
            });             
    }else{
        bootbox.alert('Debe seleccionar primero un cliente');
    }    
};

/**
 * Se obtienen los datos del vehiculo con la placa ingresada
 * @returns {undefined}
 */
var obtenerDatosVehiculo = function(){ 
    var cotizacionId = $('#cotizacionId').val();
    var plcV = $(this).val();    
    
    if(cotizacionId != ""){
        if(plcV.length > 1){
            $.ajax({
                    url: $('#url-proyecto').val() + 'vehiculos/ajaxObtenerVehiculo',
                    data: {datosVehiculo: plcV},
                    type: "POST",
                    success: function(data) {

                        var vehiculo = JSON.parse(data);
                        var uls = "";
                        for(var i = 0; i < vehiculo.resp.length; i++){
                            uls += "<a href='#' class='list-group-item list-group-item-info' name='" + vehiculo.resp[i].Vehiculo.placa + "'";
                            uls += " id='" + vehiculo.resp[i].Vehiculo.id + "'";
                            uls += "onClick ='seleccionarVehiculo(this)'>" + vehiculo.resp[i].Vehiculo.placa + "</a>";
                        }
                        $('#datosVehiculo').show();
                        $('#datosVehiculo').html(uls);
                    }
                });        
        }else{
            $('#datosVehiculo').html("");
            $('#datosVehiculo').hide();
            $('#vehiculo_id').val("");                
        }           
    }else{
        bootbox.alert('Debe seleccionar primero un cliente');
    } 
};

function seleccionarVehiculo(data){    
    $('#datosVehiculo').html("");
    $('#datosVehiculo').hide();
    $('#CotizacionePlaca').val(data.name);
    $('#vehiculo_id').val(data.id);
    var vehiculoId = data.id;
    var cotizacionId = $('#cotizacionId').val();
    
    if(cotizacionId != ""){
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizaciones/ajaxGuardarVehiculo',
            data: {vehiculoId: vehiculoId, cotizacionId: cotizacionId},
            type: "POST",
            success: function(data) {
                
            }
        });             
    }else{
        bootbox.alert('Debe seleccionar primero un cliente');
    }
}

var verDatosVehiculo = function(){
    var idVehiculo = $('#vehiculo_id').val();
    var placa = $('#CotizacionePlaca').val();
    if(idVehiculo == "" && placa == ""){
        bootbox.alert('Debe seleccionar un vehículo.');
    }else{        
        window.open($('#url-proyecto').val() + 'vehiculos/edit/' + idVehiculo, '_blank');
    }    
};

var enviarCotizacion = function(){
    var telCli = $('#CotizacioneTelefonocliente').val();    
    if(telCli != ""){
        var link = "https://wa.me/57" + telCli + "?text=Somos%20el%20%23equipotorque%2c%20adjuntamos%20información%20de%20su%20interés";
        $('.wppSendCot').attr('href', link).attr('target', '_blank');
    }else{
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
    $('#CotizacioneDiascredcliente').change(actualizarDiasLimite);
    $('#CotizacioneLimitecredcliente').change(actualizarCreditoLimite);
    
    //seleccion de producto
    $('#CotizacioneProducto').keyup(fnObtenerProductoCotizacion);    
//    $('#CotizacioneProductousuarionuevo').keyup(fnObtenerProductoCotCliNuevo);    
//    $('#CotizacioneProductoventarapida').keyup(fnObtenerProductoCotRapida);
    
    if($('#CotizacioneDatoscliente').val() != ""){
        $('#CotizacioneProducto').prop('disabled', false);
        $('#CotizacioneLimitecredcliente').number(true);
    }
    
    $('#CotizacioneVendedor').change(cambiarResponsableCotiza);
    
    $('#imprimirCot').click(imprimirCotizacion);
    
    $('.ttales').number(true);
    calcularTtalCot();
});
