var guardarObservacion = function(){
    var obs = $(this).val();

    if(obs != ""){
        $.ajax({
            url: $('#url-proyecto').val() + 'facturas/observacionescierre',
            type: "POST",
            data: {obs: obs},
            success: function(data) {    
                var respuesta = JSON.parse(data);
                if(!respuesta.resp){
                    bootbox.alert("No fue posible guardar la observacion. Por favor, inténtelo de nuevo");
                }
            }
        });         
    }
  
}

$(function() {
    $('#obs_cierre').blur(guardarObservacion)    
});