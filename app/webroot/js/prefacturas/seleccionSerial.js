var agregarSerialPreFactura = function(){

    var mensaje = validarSeleccionSerial();

    if( mensaje == '') {

        var $serialSeleccionado = $('input[name="rd_serial_id"]:checked');
        var color = $.trim($('#motoColor').val());
        var modelo = $.trim($('#motoModelo').val());
        var serialId = $serialSeleccionado.val();
        var prefactDetalleId = $('#prefacturasdetalleId').val();

        $.ajax({
            url: $('#url-proyecto').val() + 'prefacturas/asignarserialprefacturaid',
            data: { color: color, modelo: modelo, serialId: serialId, prefactDetalleId: prefactDetalleId },
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
    
                if(resp.resp){
                    bootbox.alert('Se asignaron los seriales de manera correcta.', function(){
                        dialogSerialesRegistrados.dialog('close');
                    });
                }else{
                    bootbox.alert('No fue posible asignar los seriales. Por favor, inténtelo de nuevo.')
                }
            }
        });   

    } else {
        bootbox.alert(mensaje);
    }
}


function validarSeleccionSerial() {
    var color = $.trim($('#motoColor').val());
    var modelo = $.trim($('#motoModelo').val());
    var $serialSeleccionado = $('input[name="rd_serial_id"]:checked');

    var mensajeRetorno = '';

    // 1. Validar Color
    if (color === '') {
        mensajeRetorno += '- Por favor ingrese el color de la moto..<br>';
        $('#motoColor').val('');
    }

    // 2. Validar Modelo (Año de 4 dígitos y rango razonable)
    var regexAnio = /^\d{4}$/;
    var anioActual = new Date().getFullYear();

    if (modelo === '') {
        mensajeRetorno += '- Por favor ingrese el modelo (año) de la moto.<br>';
        $('#motoModelo').val('');
    }

    if (!regexAnio.test(modelo) || parseInt(modelo) < 1900 || parseInt(modelo) > (anioActual + 1)) {
        mensajeRetorno += '- Por favor ingrese un año de modelo válido (ej: ' + anioActual + ').<br>';
        $('#motoModelo').val('');
    }

    // 3. Validar Selección del Radio Button (Chasis - Motor)
    if ($serialSeleccionado.length === 0) {
        mensajeRetorno += '- Debe seleccionar un registro de Chasis y Motor de la lista.<br>';
    }

    return mensajeRetorno;
}