function fnObtenerDatosProducto(e){    
    var key = (document.all) ? e.keyCode : e.which;
    if(key == 13){ 
        $.ajax({
           url: $('#url-proyecto').val() + 'cargueinventarios/ajaxProductoCargueIndexBarcode',
           data: {descProducto: $('#buscarproducto').val(), empresaId: $('#empresa_id').val()},
           type: "POST",
           success: function(data) {
               var respuesta = JSON.parse(data);              
               if(respuesta.resp == '1'){
                    $('#buscarproducto').val("");
                    $('#datosProducto').hide();
                    bootbox.alert(respuesta.mensaje, function(){                        
                        $('#datosProducto').hide();
                    });                   
               }
               
               if(respuesta.resp == '2'){                   
                    alert('llego aqui');                                       
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
                        uls += "<a href='#' class='list-group-item list-group-item-info' id='" + producto.resp[i].Producto.id + "' name='" + producto.resp[i].Producto.descripcion + " - " + producto.resp[i].Producto.codigo + "' onClick ='seleccionarProducto(this)'>" + producto.resp[i].Producto.descripcion + " - " + producto.resp[i].Producto.codigo + "</a>";
                    }
                    $('#datosProducto').show();
                    $('#datosProducto').html(uls);
                }
            }); 
    }
}

function seleccionarProducto(dato){  
    $('#buscarproducto').val("");
    $('#datosProducto').hide();
    $('#producto_id').val(dato.id);   
    $('#buscarproducto').val(dato.name);
}

