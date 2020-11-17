
var nuevallamada = function() {

    bootbox.confirm("¿Está seguro que desea actualizar el registro de llamadas?", function(result){
        if(result){            
            var id = $('#alerta_id').val();    
            $.ajax({
                url: $('#url-proyecto').val() + 'alertaordenes/actualizarllamadas',
                data: { alerta_id: id },
                type: "POST",
                success: function(data) {
                    var resp = JSON.parse(data);
        
                    if(resp.resp){
                        bootbox.alert('Se actualizó la fecha de última llamada con éxito.', function(){
                            $('#ult_llamada').html(resp.fecha);
                            $('#cant_llamadas_text').html(resp.cant);
                        })
                    }else{
                        bootbox.alert('No se pudo actualizar la fecha de la última llamada. Por favor, inténtelo de nuevo');
                    }
                }
            }); 
        }
    }); 
}

var actualizarEstado = function() {

    var estadoId = $('#OrdentrabajoEstadoAlertaId').val();  
    var id = $('#alerta_id').val();   
    $.ajax({
        url: $('#url-proyecto').val() + 'alertaordenes/actualizarestado',
        data: { estadoId: estadoId, alertaId: id },
        type: "POST",
        success: function(data) {
            var resp = JSON.parse(data);

            if(resp.resp){
                
            }else{
                bootbox.alert('No se pudo actualizar el estado de la alerta. Por favor, inténtelo de nuevo');
            }
        }
    });                  
}

var actualizarObservacion = function() {
    var id = $('#alerta_id').val();
    var obs = $('#OrdentrabajoObservacionesCliente').val();
    $.ajax({
        url: $('#url-proyecto').val() + 'alertaordenes/actualizarobservaciones',
        data: { observaciones: obs, alertaId: id },
        type: "POST",
        success: function(data) {
            var resp = JSON.parse(data);

            if(resp.resp){
                
            }else{
                bootbox.alert('No se pudo actualizar la observación. Por favor, inténtelo de nuevo');
            }
        }
    }); 
}


var generarAlertaCumple = function() {

    var mensaje = '';
    var usuarioId = $('#usuarioId').val(); 
    if($('#usuarioId').val() == ''){
        bootbox.alert('Debe seleccionar un responsable para la alerta.<br>.');
    }

    if($('#usuarioId').val() != ''){

    bootbox.confirm("¿Está seguro que desea generar una alerta para el cumpleaños del cliente?", function(result){
        if(result){            
            var vehiculoId = $('#vehiculoId').val();
            var clienteId = $('#clienteId').val(); 
            var facturaId = $('#facturaId').val(); 
            var soat = $('#soat').val();         
            var fechacumple = $('#fechacumple').val(); 
                    
            $.ajax({
                url: $('#url-proyecto').val() + 'alertaordenes/generaralertacumpleanos',
                data: { vehiculoId: vehiculoId, fechacumple:fechacumple,clienteId: clienteId,usuarioId:usuarioId, facturaId: facturaId, soat: soat },
                type: "POST",
                success: function(data) {
                    var resp = JSON.parse(data);
        
                    if(resp.resp == '1'){
                        bootbox.alert('Se creó la alerta para el cumpleaños del cliente.');
                    }else if ( resp.resp == '2' ) {
                        bootbox.alert('Ya existe una alerta para el cumpleaños del cliente');
                    }else{
                        bootbox.alert('No fue posible crear la alerta para cumpleaños del cliente. Por favor, inténtelo de nuevo.');
                    }
                }
            }); 
        }
    }); 
}
}


var nuevallamada = function() {

    bootbox.confirm("¿Está seguro que desea actualizar el registro de llamadas?", function(result){
        if(result){            
            var id = $('#alerta_id').val();    
            $.ajax({
                url: $('#url-proyecto').val() + 'alertaordenes/actualizarllamadas',
                data: { alerta_id: id },
                type: "POST",
                success: function(data) {
                    var resp = JSON.parse(data);
        
                    if(resp.resp){
                        bootbox.alert('Se actualizó la fecha de última llamada con éxito.', function(){
                            $('#ult_llamada').html(resp.fecha);
                            $('#cant_llamadas_text').html(resp.cant);
                        })
                    }else{
                        bootbox.alert('No se pudo actualizar la fecha de la última llamada. Por favor, inténtelo de nuevo');
                    }
                }
            }); 
        }
    }); 
}
$(function() {    
    $('#alerta_cumple').click(generarAlertaCumple);
    $('#cant_llamadas').click(nuevallamada);
    $('.onlyview').prop('disabled', true);
});