var opcDialogNotaDescargueInventario = {
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
        title: 'Nota Aprobación Descargue de Inventario'    
};

var dialogNotaDescargueInventario;

function fnObtenerDatosProducto(e){ 
    var key = (document.all) ? e.keyCode : e.which;
    if(key == 13){ 
        $.ajax({
           url: $('#url-proyecto').val() + 'descargueinventarios/ajaxProductoDesargueBarcode',
           data: {descProducto: $('#buscarproducto').val(), depositoId: $('#deposito_id').val(), usuarioId: $('#usuario_id').val()},
           type: "POST",
           success: function(data) {
            var productos = JSON.parse(data);
            if(productos.boolResp === '1'){
                bootbox.alert('No hay productos disponibles en Stock.');
                $('#buscarproducto').val("");
                $('#datosProducto').hide();                 
            }else if(productos.boolResp === '2'){
                $('#descInventario').append('<tr id="tr_' + productos.descId + '">' +
                        '<td>' + productos.resp['0']['Producto']['descripcion'] + '</td>' + 
                        '<td><input type="text" name="cant_' + productos.descId + '" class="form-control" id="cant_' + productos.descId + '" value="1" onblur="actualizarCantidadDescargue(this);">&nbsp;</td>' +
                        '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + productos.descId + '" onclick="eliminarRegistroDescargue(this)"></td></tr>');                
                $('#buscarproducto').val("");
                $('#datosProducto').hide();                 
            }else if(productos.boolResp === '3'){
                bootbox.alert('No se pudo agregar el producto al descargue del inventario. Por favor, inténtelo de nuevo');
            }else{
                bootbox.alert('No se pudo agregar el producto al descargue del inventario. Por favor, inténtelo de nuevo');
            }
            
           }
       });
    }else if($('#buscarproducto').val().length <= '0'){
        $('#datosProducto').hide();
    }else{        
        $.ajax({
            url: $('#url-proyecto').val() + 'descargueinventarios/ajaxProductoDescargueInventario',
            data: {descProducto: $('#buscarproducto').val(), empresaId: $('#empresa_id').val(), depositoId: $('#deposito_id').val()},
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

function habilitarBuscarProducto(){
    var deposito = $('#deposito_id').val();
    if(deposito != null && deposito != ""){
        $('#buscarproducto').val("");
        $('#datosProducto').hide();
        $('#buscarproducto').prop('disabled', false);
    }else{
        $('#buscarproducto').val("");
        $('#datosProducto').hide();
        $('#buscarproducto').prop('disabled', true);
    }
}

function seleccionarProducto(producto){
    var productoId = producto.name;
    var usuarioId = $('#usuario_id').val(); 
    var deposito_id = $('#deposito_id').val();
    $.ajax({
       url: $('#url-proyecto').val() + 'descargueinventarios/ajaxOtenerProductoDescargue',
       data: {productoId: productoId, usuarioId: usuarioId, deposito_id: deposito_id},
       type: "POST",
       success: function(data) {
            var productos = JSON.parse(data);
            if(productos.boolResp === '1'){
                bootbox.alert('No hay productos disponibles en Stock.');
                $('#buscarproducto').val("");
                $('#datosProducto').hide();                 
            }else if(productos.boolResp === '2'){
                $('#descInventario').append('<tr id="tr_' + productos.descId + '">' +
                        '<td>' + productos.resp['Producto']['descripcion'] + '</td>' + 
                        '<td><input type="text" name="cant_' + productos.descId + '" class="form-control" id="cant_' + productos.descId + '" value="1" onblur="actualizarCantidadDescargue(this);">&nbsp;</td>' +
                        '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + productos.descId + '" onclick="eliminarRegistroDescargue(this)"></td></tr>');                
                $('#buscarproducto').val("");
                $('#datosProducto').hide();                 
            }else if(productos.boolResp === '3'){
                bootbox.alert('No se pudo agregar el producto al descargue del inventario. Por favor, inténtelo de nuevo');
            }else{
                bootbox.alert('No se pudo agregar el producto al descargue del inventario. Por favor, inténtelo de nuevo');
            }                
       }
   }); 
    
}

function validarProductosDescargue(){
    var empresaId = $('#empresa_id').val();
    $.ajax({
        url: $('#url-proyecto').val() + 'descargueinventarios/obtenerProductosDescargue',
        data: {empresaId: empresaId},
        type: "POST",
        success: function(data) {   
            var respuesta = JSON.parse(data);
            if(respuesta.resp){
                $.each(respuesta.detDesc, function(idx, obj) {
                    $('#descInventario').append('<tr id="tr_' + obj['Descargueinventario']['id'] + '">' + 
                    '<td>' + obj['Producto']['descripcion'] + '</td>' + 
                    '<td><input type="text" name="cant_' + obj['Descargueinventario']['id'] + '" class="form-control" id="cant_' + obj['Descargueinventario']['id'] + '" value="' + obj['Descargueinventario']['cantidaddescargue'] + '" onblur="actualizarCantidadDescargue(this);">&nbsp;</td>' +
                    '<td><input type="button" class="btn btn-primary" value="Eliminar" id="' + obj['Descargueinventario']['id'] + '"onclick="eliminarRegistroDescargue(this)"></td></tr>');
                }); 
            }            
        }
    });      
}

function actualizarCantidadDescargue(dato){
    var cantidad = $('#' + dato.name).val();
    var descId = dato.name.split("_");
    $.ajax({
        url: $('#url-proyecto').val() + 'descargueinventarios/actualizarCantidadDescargue',
        data: {descId: descId[1], cantidad: cantidad},
        type: "POST",
        success: function(data) {            
            var respuesta = JSON.parse(data);
            if(respuesta.resp === '1'){
                bootbox.alert('La cantidad a descargar supera a la existente en el Stock.', function(){
                    location.reload();
                });
            }else if(respuesta.resp === '2'){
                bootbox.alert('La cantidad a descargar ha sido actualizada.');
            }else if(respuesta.resp === '3'){
                bootbox.alert('La cantidad a descargar no se pudo actualizar. Por favor, inténtelo de nuevo.');
            }            
        }
    });         
}

function eliminarRegistroDescargue(dato){    

    bootbox.confirm("Está seguro que desea eliminar el registro?", function(result){
        if(result){
            $.ajax({
                url: $('#url-proyecto').val() + 'descargueinventarios/delete',
                data: {descargueId: dato.id},
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

function aprobarDescargueInventario(){
    var usuarioId = $('#usuario_id').val();
    
    $("#notaDescargue").load(
        $('#url-proyecto').val() + "anotaciones/notadescargueinventario",
        {usuarioId:usuarioId},
        function(){                                                            
            dialogNotaDescargueInventario=$("#notaDescargue").dialog(opcDialogNotaDescargueInventario);
            dialogNotaDescargueInventario.dialog('open');
        }
    );     
}

function guardarDescargueInventario(dato){
    var nota = $('#anotacionDescargue').val(); 
    var empresaId = $('#empresa_id').val();
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'documentos/guardardescargueinventarioajax',
        data: {nota: nota, usuarioId: dato.name, empresaId: empresaId},
        success: function(data) {
            var respuesta = JSON.parse(data);
            if(respuesta.resp){
                window.location.href = $('#url-proyecto').val() + 'documentos/view/' + respuesta.documentoId;
            }else{
                bootbox.alert('No se pudo completar el descargue de inventario.');
            }            
        }
    });     
}


$( function() {
    $('#buscarproducto').prop('disabled', true);
    validarProductosDescargue();         
});



