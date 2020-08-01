var validarReferenciaUnica = function(){
    var referencia = $(this).val();
    var ProductoId = $('#ProductoId').val();
    
    if(typeof ProductoId == "undefined"){
        $.ajax({
            url: $('#url-proyecto').val() + 'productos/validarReferenciaUnica',
            data: {referencia: referencia},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);                
                if(resp.resp){
                    $('#ProductoReferencia').val("");
                    alert('La referencia ya ha sido asignada a otro producto.');
                }
            }
        });          
    }else{
        $.ajax({
            url: $('#url-proyecto').val() + 'productos/validarReferenciaUnicaEdit',
            data: {referencia: referencia, productoId: ProductoId},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if(resp.resp){
                    $('#ProductoReferencia').val(resp.referencia);
                    alert('La referencia ya ha sido asignada a otro producto.');
                }
            }
        });
    }
};


/**
 * Se valida que codigo sea unico
 * @returns {undefined}
 */
var validarCodigoUnico = function(){
    var codigo = $(this).val();
    var ProductoId = $('#ProductoId').val();
    
    if(typeof ProductoId == "undefined"){
        $.ajax({
            url: $('#url-proyecto').val() + 'productos/validarCodigoUnico',
            data: {codigo: codigo},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if(resp.resp){
                    $('#ProductoCodigo').val("");
                    alert('El código ya ha sido asignado a otro producto.');
                }
            }
        });         
    }else{        
        $.ajax({
            url: $('#url-proyecto').val() + 'productos/validarCodigoUnicoEdit',
            data: {codigo: codigo, productoId: ProductoId},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if(resp.resp){
                    $('#ProductoCodigo').val(resp.codigo);
                    alert('El código ya ha sido asignado a otro producto.');
                }
            }
        });        
    }
   
    
};

$( function() {
    $('.number').number(true);
    $('#ProductoCodigo').blur(validarCodigoUnico);
    $('#ProductoReferencia').blur(validarReferenciaUnica);
});