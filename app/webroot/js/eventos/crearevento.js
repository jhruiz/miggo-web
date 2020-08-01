var validateevent = function(){
    var msg = "";

    if($('#date_event').val() == ""){
        msg += "- Debe seleccionar una fecha para el evento.<br>";
    }

    if($('#desc_event').val() == ""){
        msg += "- Debe agregar una descripción para el evento.<br>";
    }

    return msg;
}


var guardarevento = function() {
    var msg = validateevent();

    if(msg == ""){

        datos = {
            empresaId: $('#empresa_id').val(),
            tEvento: $('#typeevent').val(),
            user_id: $('#user_id').val(),
            date_event: $('#date_event').val(),
            client_name: $('#client_name').val(),
            client_tel: $('#client_tel').val(),
            placa: $('#placa').val(),
            desc_event: $('#desc_event').val(),
            estadoId: $('#estado_id').val()
        }

        $.ajax({        
            url: $('#url-proyecto').val() + 'eventos/guardarevento', 
            async : false,
            data: datos,
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);


                console.log(resp);
                if(resp.resp){
                    bootbox.alert('El evento ha sido registrado exitosamente.', function(){
                        location.reload();
                    });
                    
                }else{
                    bootbox.alert('No fue posible registrar el evento. Por favor, inténtelo de nuevo.');
                }
            }
        });         
    }else{
        bootbox.alert(msg);
    }
}

$(function() {
    $(".date").datepicker({dateFormat: 'yy-mm-dd'});
    $(".date").datepicker("option", "showAnim", "slideDown"); 
    
    $('#createEvent').click(guardarevento);
});