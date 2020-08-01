var opcDialogNotaTrasladoInventario = {
        autoOpen: false,
        modal: true,
        width: 600,
        height: 400,
        position: [400, 50],
        show: {
            duration: 400    
        },
        hide: function () {
        },
        close: function( event, ui){
        },
        title: 'Nota Aprobación Traslado de Inventario'    
};

var dialogNotaTrasladoInventario;


/*Valida la tabla de traslado y obtine los productos precargados para realizarlo*/
function validarProductosTraslado(){
    var empresaId = $('#empresa_id').val();
    $.ajax({
        url: $('#url-proyecto').val() + 'trasladoinventarios/obtenerProductosTraslado',
        data: {empresaId: empresaId},
        type: "POST",
        success: function(data) {   
            var respuesta = JSON.parse(data);
            if(respuesta.resp){
                $.each(respuesta.detTras, function(idx, obj) {
                    $('#trasInventario').append('<tr id="tr_' + obj['Trasladoinventario']['id'] + '">' + 
                    '<td>' + obj['Producto']['descripcion'] + '</td>' + 
                    '<td><input type="text" name="cant_' + obj['Trasladoinventario']['id'] + '" class="form-control" id="cant_' + obj['Trasladoinventario']['id'] + '" value="' + obj['Trasladoinventario']['cantidadtraslado'] + '" onblur="actualizarCantidadTraslado(this);">&nbsp;</td>' +
                    '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + obj['Trasladoinventario']['id'] + '"onclick="eliminarRegistroTraslado(this)"></td></tr>');
                }); 
            }            
        }
    });      
}

/*valida si se han seleccionado depositos para habilitar el campo de busqueda del producto*/
function habilitarBuscarProducto(){
    var depOrigen = $('#depositoorigen_id').val();
    var depDestino = $('#depositodestino_id').val();

    if(depOrigen === depDestino){
        bootbox.alert('El depósito origen y el depósito destino no pueden ser el mismo.');        
        $('#buscarproducto').prop('disabled', true);
        $('#depositoorigen_id').val("");
        $('#depositodestino_id').val("");
    }else if(depOrigen != "" && depDestino != ""){
        $('#buscarproducto').prop('disabled', false);
    }else{
        $('#buscarproducto').prop('disabled', true);
    }
}

/*Se obtienen los datos del producto con la descripcion o el codigo ingresados en el campo "buscar producto"*/
function fnObtenerDatosProducto(e){ 
    var key = (document.all) ? e.keyCode : e.which;
    if(key == 13){ 
        $.ajax({
           url: $('#url-proyecto').val() + 'trasladoinventarios/ajaxProductoTrasladoBarcode',
           data: {descProducto: $('#buscarproducto').val(), depositoorigenId: $('#depositoorigen_id').val(), usuarioId: $('#usuario_id').val(), depositodestinoId: $('#depositodestino_id').val()},
           type: "POST",
           success: function(data) {
            var productos = JSON.parse(data);
            if(productos.boolResp === '1'){
                bootbox.alert('No hay productos disponibles en Stock.');
                $('#buscarproducto').val("");
                $('#datosProducto').hide();                 
            }else if(productos.boolResp === '2'){
                $('#trasInventario').append('<tr id="tr_' + productos.descId + '">' +
                        '<td>' + productos.resp['0']['Producto']['descripcion'] + '</td>' + 
                        '<td><input type="text" name="cant_' + productos.descId + '" class="form-control" id="cant_' + productos.descId + '" value="1" onblur="actualizarCantidadTraslado(this);">&nbsp;</td>' +
                        '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + productos.descId + '" onclick="eliminarRegistroTraslado(this)"></td></tr>');                
                $('#buscarproducto').val("");
                $('#datosProducto').hide();                 
            }else if(productos.boolResp === '3'){
                bootbox.alert('No se pudo agregar el producto al traslado del inventario. Por favor, inténtelo de nuevo');
            }else{
                bootbox.alert('No se pudo agregar el producto al traslado del inventario. Por favor, inténtelo de nuevo');
            }
            
           }
       });
    }else if($('#buscarproducto').val().length <= '0'){
        $('#datosProducto').hide();
    }else{        
        $.ajax({
            url: $('#url-proyecto').val() + 'trasladoinventarios/ajaxProductoTrasladoInventario',
            data: {descProducto: $('#buscarproducto').val(), empresaId: $('#empresa_id').val(), depositoorigenId: $('#depositoorigen_id').val()},
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

/*Seleccionar el producto que se obtiene en el buscador de productos*/
function seleccionarProducto(producto){
    var productoId = producto.name;
    var usuarioId = $('#usuario_id').val();    
    var depOrigen = $('#depositoorigen_id').val();
    var depDestino = $('#depositodestino_id').val();
    $.ajax({
       url: $('#url-proyecto').val() + 'trasladoinventarios/ajaxOtenerProductoTraslado',
       data: {productoId: productoId, usuarioId: usuarioId, depositoOrigen: depOrigen, depositoDestino:depDestino},
       type: "POST",
       success: function(data) {
            var productos = JSON.parse(data);
            if(productos.boolResp === '1'){
                bootbox.alert('No hay productos disponibles en Stock.');
                $('#buscarproducto').val("");
                $('#datosProducto').hide();                 
            }else if(productos.boolResp === '2'){
                $('#trasInventario').append('<tr id="tr_' + productos.descId + '">' +
                        '<td>' + productos.resp['Producto']['descripcion'] + '</td>' + 
                        '<td><input type="text" name="cant_' + productos.descId + '" class="form-control" id="cant_' + productos.descId + '" value="1" onblur="actualizarCantidadTraslado(this);">&nbsp;</td>' +
                        '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + productos.descId + '" onclick="eliminarRegistroTraslado(this)"></td></tr>');                
                $('#buscarproducto').val("");
                $('#datosProducto').hide();                 
            }else if(productos.boolResp === '3'){
                bootbox.alert('No se pudo agregar el producto al traslado del inventario. Por favor, inténtelo de nuevo');
            }else{
                bootbox.alert('No se pudo agregar el producto al traslado del inventario. Por favor, inténtelo de nuevo');
            }                
       }
   }); 
    
}

function eliminarRegistroTraslado(dato){    

    bootbox.confirm("Está seguro que desea eliminar el registro?", function(result){
        if(result){
            $.ajax({
                url: $('#url-proyecto').val() + 'trasladoinventarios/delete',
                data: {trasladoId: dato.id},
                type: "POST",
                success: function(data) { 
                    var respuesta = JSON.parse(data);
                    if(respuesta.resp){
                        $('#tr_' + dato.id).remove();                
                    }else{
                        bootbox.alert('No se pudo eliminar el registro. Por favor, inténtelo de nuevo.');
                    }
                }
            });            
        } 
    });  
}

/*
 * Se actualiza la cantidad a trasladar indicada en el campo cantidad
 * @param {type} dato
 * @returns {undefined}
 */
function actualizarCantidadTraslado(dato){
    var cantidad = $('#' + dato.name).val();
    var descId = dato.name.split("_");
    
    $.ajax({
        url: $('#url-proyecto').val() + 'trasladoinventarios/actualizarCantidadTraslado',
        data: {descId: descId[1], cantidad: cantidad},
        type: "POST",
        success: function(data) {            
            var respuesta = JSON.parse(data);
            if(respuesta.resp === '1'){
                bootbox.alert('La cantidad a trasladar supera a la existente en el Stock.', function(){
                    location.reload();
                });
            }else if(respuesta.resp === '2'){
                bootbox.alert('La cantidad a trasladar ha sido actualizada.');
            }else if(respuesta.resp === '3'){
                bootbox.alert('La cantidad a trasladar no se pudo actualizar. Por favor, inténtelo de nuevo.');
            }            
        }
    });         
}


/*Se abre el pop up para agregar la nota que se guarda en el traslado de inventario*/
function aprobarTrasladoInventario(){
    var usuarioId = $('#usuario_id').val();
    
    $("#notaTraslado").load(
        $('#url-proyecto').val() + "anotaciones/notatrasladoinventario",
        {usuarioId:usuarioId},
        function(){                                                            
            dialogNotaTrasladoInventario=$("#notaTraslado").dialog(opcDialogNotaTrasladoInventario);
            dialogNotaTrasladoInventario.dialog('open');
        }
    );     
}

/*guardar y aprobar el traslado del inventario realizado*/
function guardarTrasladoInventario(dato){
    var nota = $('#anotacionTraslado').val(); 
    var empresaId = $('#empresa_id').val();
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'trasladoinventarios/guardartrasladoinventarioajax',
        data: {nota: nota, usuarioId: dato.name, empresaId: empresaId},
        success: function(data) {
            var respuesta = JSON.parse(data);
            if(respuesta.resp){
                window.location.href = $('#url-proyecto').val() + 'documentos/view/' + respuesta.documentoId;
            }else{
                bootbox.alert('No se pudo completar el traslado de inventario.');
            }            
        }
    });     
}

$( function() {
    $('#buscarproducto').prop('disabled', true);
    validarProductosTraslado();         
});