var clienteNit = "";

var validarClienteUnico = function(){
    var identificacion = $('#ClienteNit').val();
    
    if(identificacion != "" && clienteNit != identificacion){
        $.ajax({
            url: $('#url-proyecto').val() + 'clientes/ajaxValidarClienteUnico',
            data: {identificacion: identificacion},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if(resp.resp == '1'){
                    bootbox.alert('el número de identificación ' + identificacion + ', ya fue asignado al cliente ' + resp.data.Cliente.nombre);
                    $('#ClienteNit').val(clienteNit);
                }
            }
        });         
    }    
};

$( function() {
    clienteNit = $('#ClienteNit').val();
    $('#ClienteNit').blur(validarClienteUnico);
});
