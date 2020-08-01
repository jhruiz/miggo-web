var actualizarEstadoPrefact = function(){
    var estadoId = $('#PrefacturaEstados').val();
    var prefactId = $('#prefactId').val();
    $.ajax({
        url: $('#url-proyecto').val() + 'prefacturas/ajaxActualizarEstado',
        data: {estadoId: estadoId, prefactId: prefactId},
        type: "POST",
        success: function(data) {
            var estado = JSON.parse(data); 
            
                
            if(!estado.resp){
                bootbox.alert('No fue posible actualizar el estado. Por favor, inténtelo nuevamente.');
            }
        }
    });
};

$(function(){
    $('#PrefacturaEstados').change(actualizarEstadoPrefact);
});

