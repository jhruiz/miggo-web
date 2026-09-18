var opcDialogCargueInventario = {
    autoOpen: false,
    modal: true,
    width: 900,
    height: 850,
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

var opcDialogSeriales = {
    autoOpen: false,
    modal: true,
    width: 500,
    height: 500,
    position: [400, 50],
    show: {
        duration: 400    
    },
    hide: function () {
    },
    close: function( event, ui){
        // $(this).dialog('destroy').remove();  
    },
    title: 'Cargar Producto al Inventario'    
};

var dialogSeriales;



///Se obtienen todos los checkbox seleccionados del formulario
function cargueInventarioCuadro(prdId = null) {
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
    }else if(prdId != null){
        cargarProductoInventario(prdId);
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

/*Se crea la modal para agregar los seriales*/
function agregarSerialesProductos(){    
    var cantidad = $('#CargueinventarioCantidad').val();
    var productoId = $('#producto_id').val();
    
    if( cantidad <= 0 || cantidad == '' ) {
        bootbox.alert('El campo cantidad no puede estar vacio o ser menor a cero.');
    } else {
        $("#div_seriales").load(
            $('#url-proyecto').val() + "cargueinventarios/agregarseriales",
            {
                cantidad: cantidad, productoId: productoId
            },
            function(){                                                            
                dialogSeriales=$("#div_seriales").dialog(opcDialogSeriales);
                dialogSeriales.dialog('open');
            }
        );    
    }
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
    }else {
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

function agregarSerialesProducto() {
    var productoId = $('#productoSerialId').val();
    var seriales = [];
    var esValido = true;

    // Iterar sobre todos los campos de chasis presentes en el formulario
    $('[id^="serial_chasis_"]').each(function(index) {
        var i = index + 1;
        var $inputChasis = $('#serial_chasis_' + i);
        var $inputMotor = $('#serial_motor_' + i);

        var chasisVal = $.trim($inputChasis.val());
        var motorVal = $.trim($inputMotor.val());

        // Validación de campo Chasis
        if (chasisVal === '') {
            bootbox.alert('Por favor complete el campo Chasis en el Serial #' + i);
            $inputChasis.focus();
            esValido = false;
            return false; // Detiene el bucle $.each
        }

        // Validación de campo Motor
        if (motorVal === '') {
            bootbox.alert('Por favor complete el campo Motor en el Serial #' + i);
            $inputMotor.focus();
            esValido = false;
            return false; // Detiene el bucle $.each
        }

        // Guardar el ítem ordenado
        seriales.push({
            item: i,
            chasis: chasisVal,
            motor: motorVal
        });
    });

    // Si algún campo no pasó la validación, frena la ejecución
    if (!esValido) {
        return false;
    }

    // Declara el objeto local
    var inventarioSeriales = {};
    inventarioSeriales[productoId] = seriales;

    // Asigna el objeto convertido a JSON String en el input
    $('#serialesChMt').val(JSON.stringify(inventarioSeriales));

    bootbox.alert('Seriales validados y guardados correctamente.');
    dialogSeriales.dialog('close');
    console.log('Estructura global actual:', window.inventarioSeriales);
}



function seleccionarProducto(dato){
    var productoId = dato.name;
    $('#chk_' + productoId).prop('checked',true);
    $('#buscarproducto').val("");
    $('#datosProducto').hide();
    cargueInventarioCuadro(productoId);
    
}




