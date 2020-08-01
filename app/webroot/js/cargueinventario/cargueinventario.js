    var opcDialogCargueInventario = {
        autoOpen: false,
        modal: true,
        width: 900,
        height: 750,
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
        title: 'Cargar Producto al Inventario'    
};

var dialogCargarInventario;

var opcDialogProductoNuevo = {
        autoOpen: false,
        modal: true,
        width: 900,
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
        title: 'Nuevo Producto'    
};

var dialogProductoNuevo;



///Se obtienen todos los checkbox seleccionados del formulario
function cargueInventarioCuadro() {
    var productoId = [];
    var i = 0;
    
    $('.chkPdr').each(function(){
        
        if($(this).is(':checked')){
            productoId[i] = $(this).val();
            i++;
        }
    });
    
    if(productoId.length >= 0 && typeof productoId[0] != 'undefined' && productoId[0] != null){
        cargarProductoInventario(productoId[0]);
    }else{
        bootbox.alert('Se ha finalizado con el cargue de archivos', function(){
            $('#butCargarInventarioUp').attr('disabled', true);
            $('#butCargarInventarioDown').attr('disabled', true);
        }); 
        
    }
}

/*Se valida si existe algun producto seleccionado para habilitar el botón de cargar inventario e iniciar el proceso*/
function habilitarCargueInventario(checCheck){
    /*se valida si el checkbox está seleccionado para o no para cambiar el color*/
    estadoSeleccionProductos(checCheck);
    
    var productoId = [];
    var i = 0;
    $("input:checkbox:checked").each(function(){           
        productoId[i] = $(this).val();
        i++;
    });
    
    
    if(productoId.length <= 0){
        $('#butCargarInventarioUp').attr('disabled', true);
        $('#butCargarInventarioDown').attr('disabled', true);
    }else{
        $('#butCargarInventarioUp').attr('disabled', false);
        $('#butCargarInventarioDown').attr('disabled', false);
    }
}


/*Se crea el pop up con la información del producto seleccionado*/
function cargarProductoInventario(productoId){
    var usuarioId = $('#usuarioId').val();
    var empresaId = $('#empresaId').val();
    var urlImg = $('#urlImg').val();
    
        $("#div_inventario").load(
            $('#url-proyecto').val() + "cargueinventarios/cargarinventario",
            {
                usuario_id: usuarioId, empresa_id: empresaId, producto_id: productoId, urlImg: urlImg
            },
            function(){                                                            
                dialogCargarInventario=$("#div_inventario").dialog(opcDialogCargueInventario);
                dialogCargarInventario.dialog('open');
            }
        );    
}


//Funcion que se usa para guardar la información de cargue de inventario
function guardarInfoProducto(){
    $("#chk_"+$('#producto_id').val()).prop('checked', false);
    $('#dv_' + $('#producto_id').val()).removeClass('panel-primary').addClass("panel-default");
    var formData = new FormData($('#formCargarProductoInventario')[0]);
    
    $.ajax({
        url: $('#url-proyecto').val() + 'precargueinventarios/precargarinventario',
        type: 'POST',        
        data: formData,
        contentType: false,
        processData: false,        
        success: function(data) {      
        }
    });      
    dialogCargarInventario.dialog('close');    
    cargueInventarioCuadro();    
}

function cancelarCargueProducto(){
    $("#chk_"+$('#producto_id').val()).prop('checked', false);  
    $('#dv_' + $('#producto_id').val()).removeClass('panel-primary').addClass("panel-default");
    dialogCargarInventario.dialog('close');    
    cargueInventarioCuadro();     
}

//funcion que abre un pop up para crear un nuevo producto
function nuevoProducto(){
    
        $("#div_producto").load(
            $('#url-proyecto').val() + "productos/productocatalogo",
            {},
            function(){                                                            
                dialogProductoNuevo=$("#div_producto").dialog(opcDialogProductoNuevo);
                dialogProductoNuevo.dialog('open');
            }
        );    
}

function verCargueParcial(){
    if($('#url-proyecto').val()){
        window.open($('#url-proyecto').val() + "precargueinventarios/index","_self");
    }else{
        location.reload();
    }            
}

//Se guarda el nuevo producto para el inventario
function guardarNuevoProducto(){
    var formData = new FormData($('#ProductoProductocatalogoForm')[0]);
    
    $.ajax({
        url: $('#url-proyecto').val() + 'productos/guardarproductoinventario',
        type: 'POST',        
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            var respuesta = JSON.parse(data);
                if(respuesta.bool){  
                    dialogProductoNuevo.dialog('close');        
                    location.reload();                      
                    alert("Se guardó con éxito el Producto");
                 
//                    });                                        
                }else{
                    dialogProductoNuevo.dialog('close'); 
                    alert("No se guardó el producto. Por favor, inténtelo de nuevo.");
//                    , function (){
                       
//                    });                     
                }
        }
    });                
}

//Se valida si el checkbox ha sido seleccionado para cambiar su color
function estadoSeleccionProductos(checCheck){
    if( $('#' + checCheck.id).prop('checked') ) {
        $('#dv_' + checCheck.value).removeClass('panel-default').addClass("panel-primary");
    }else{
        $('#dv_' + checCheck.value).removeClass('panel-primary').addClass("panel-default");
    }    
}

//Se calcula el valor total del producto
function calcularValorTotal(){
    var cantidad = $('#CargueinventarioCantidad').val();
    var valUnitario = $('#CargueinventarioCostoproducto').val();
    var valTotal = (cantidad * valUnitario);
    $('#CargueinventarioCostototal').val(valTotal);
}

function fnObtenerDatosProducto(e){    
    var key = (document.all) ? e.keyCode : e.which;
    if(key == 13){      
        $.ajax({
           url: $('#url-proyecto').val() + 'cargueinventarios/ajaxProductoCargueBarcode',
           data: {descProducto: $('#buscarproducto').val(), empresaId: $('#empresa_id').val()},
           type: "POST",
           success: function(data) {
               
               var respuesta = JSON.parse(data);
               if(respuesta.resp == '1'){
                    $('#buscarproducto').val("");
                    $('#datosProducto').hide();
                    bootbox.confirm(respuesta.mensaje + ". Desea Crearlo?", function(result){
                       if(result){
                           nuevoProducto();
                       } else {}
                    });                   
               }
               
               if(respuesta.resp == '2'){                   
                    $('#chk_' + respuesta.prod.Producto.id).prop('checked',true);
                    $('#buscarproducto').val("");
                    $('#datosProducto').hide();
                    cargueInventarioCuadro();                                        
               } 
               
               if(respuesta.resp == '3'){ 
                   
                        $("#div_exito").text(respuesta.mensaje); 
                        $('#buscarproducto').val("");
                        $('#datosProducto').hide();                                                       
                        
                        setTimeout(function() {
                            $("#div_exito").fadeIn(1000);
                        },1000);     
                        $('#buscarproducto').focus();
                        setTimeout(function() {
                            $("#div_exito").fadeOut(1000);
                        },4000);
               }                
               
               if(respuesta.resp == '4'){ 
                   
                        $("#div_error").text(respuesta.mensaje); 
                        $('#buscarproducto').val("");
                        $('#datosProducto').hide();                                                       
                        
                        setTimeout(function() {
                            $("#div_exito").fadeIn(1000);
                        },1000);     
                        $('#buscarproducto').focus();
                        setTimeout(function() {
                            $("#div_exito").fadeOut(1000);
                        },4000);
               }                
               
               
           }
       });
    }else if($('#buscarproducto').val().length <= '0'){
        $('#datosProducto').hide();
    }else{
            $.ajax({
                url: $('#url-proyecto').val() + 'cargueinventarios/ajaxProductoCargueInventario',
                data: {descProducto: $('#buscarproducto').val(), empresaId: $('#empresa_id').val()},
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
    $('#chk_' + productoId).prop('checked',true);
    $('#buscarproducto').val("");
    $('#datosProducto').hide();
    cargueInventarioCuadro();
    
}




