
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

var generarAlertaSoat = function() {
    bootbox.confirm("¿Está seguro que desea generar una alerta para el SOAT del vehículo?", function(result){
        if(result){            
            var vehiculoId = $('#vehiculoId').val();
            var clienteId = $('#clienteId').val(); 
            var soat = $('#soat').val();         
            $.ajax({
                url: $('#url-proyecto').val() + 'alertaordenes/generaralertasoat',
                data: { vehiculoId: vehiculoId, clienteId: clienteId, soat: soat },
                type: "POST",
                success: function(data) {
                    var resp = JSON.parse(data);
        
                    if(resp.resp == '1'){
                        bootbox.alert('Se creó la alerta para la renovación del SOAT del vehículo.');
                    }else if ( resp.resp == '2' ) {
                        bootbox.alert('Ya existe una alerta para el SOAT del vehículo');
                    }else{
                        bootbox.alert('No fue posible crear la alerta para la renovación del SOAT. Por favor, inténtelo de nuevo.');
                    }
                }
            }); 
        }
    }); 
}

var generarAlertaTecno = function() {
    bootbox.confirm("¿Está seguro que desea generar una alerta para el Tecnomecánico del vehículo?", function(result){
        if(result){            
            var vehiculoId = $('#vehiculoId').val();
            var clienteId = $('#clienteId').val();   
            var tecnomecanica = $('#tecnomecanica').val();
            $.ajax({
                url: $('#url-proyecto').val() + 'alertaordenes/generaralertatecno',
                data: { vehiculoId: vehiculoId, clienteId: clienteId, tecnomecanica: tecnomecanica },
                type: "POST",
                success: function(data) {
                    var resp = JSON.parse(data);
        
                    if(resp.resp == '1'){
                        bootbox.alert('Se creó la alerta para la renovación del Tecnomecáncio del vehículo.');
                    }else if ( resp.resp == '2' ) {
                        bootbox.alert('Ya existe una alerta para el Tecnomecánico del vehículo');
                    }else{
                        bootbox.alert('No fue posible crear la alerta para la renovación del Tecnomecánico. Por favor, inténtelo de nuevo.');
                    }
                }
            }); 
        }
    }); 

}

$(function() {    
    $('#cant_llamadas').click(nuevallamada);
    $('#OrdentrabajoEstadoAlertaId').change(actualizarEstado);
    $('#OrdentrabajoObservacionesCliente').blur(actualizarObservacion);
    $('#alerta_soat').click(generarAlertaSoat);
    $('#alerta_tecno').click(generarAlertaTecno);

    $('.onlyview').prop('disabled', true);
});