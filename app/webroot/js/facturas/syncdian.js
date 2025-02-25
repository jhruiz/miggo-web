
/**
 * Si la respuesta es satifactoria, se actualiza la información de la Dian en la factura
 * @param {*} response 
 */
var  guardarInfoDian = function( response, facturaId, redirectTo ) {

    $('#fact_status').text('Factura enviada a la DIAN.');

    // Realizar la solicitud AJAX
    $.ajax({
        url: $('#url-proyecto').val() + 'facturas/guardarInfoDian',
        method: 'POST',
        data: {
            statusCode: response.ResponseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.StatusCode,
            cufe: response.cufe,
            QR: response.QRStr,
            facturaId: facturaId
        },
        success: function(response) {
            // Manejar la respuesta exitosa
            if (response) {
                if (redirectTo == 1) {
                    window.location.href = $('#url-proyecto').val() + 'facturas/view/' + facturaId;
                }
            } else {
                alert('No fue posible enviar la factura a la DIAN. Por favor rectifique e inténtelo nuevamente.');
                if (redirectTo == 1) {
                    window.location.href = $('#url-proyecto').val() + 'facturas/view/' + facturaId;
                }
            }
        },
        error: function(xhr, status, error) {

            alert('Hubo un error al enviar la factura a la DIAN. Por favor rectifique e inténtelo nuevamente.');
            if (redirectTo == 1) {
                window.location.href = $('#url-proyecto').val() + 'facturas/view/' + facturaId;
            }
            // Manejar errores
            
        }
    });
} 

/**
 * Actualiza los <i> para validar si se está sincronizando la info,
 * si ya se sincronizó de manera correcta o si hubo errores
 * @param {*} facturaId 
 * @param {*} status 
 */
var i_syncData = function( facturaId, status ) {
    if ( status == '1' ) {

        $('#checksync_' + facturaId).css('display', 'none');
        $('#spinner_' + facturaId).css('display', 'inline-block');
    
    } else {

        $('#checksync_' + facturaId).css('display', 'inline-block');
        $('#spinner_' + facturaId).css('display', 'none');

    }
}



/**
 * Envía el correo de confirmación de la factura electrónica
 * @param {*} resp 
 */
var enviarCorreoCliente = function(resp) {

    // Crear el cuerpo de la solicitud (body)
    var body = {
        company_idnumber: resp.nitEmpresa, 
        prefix: resp.prefijo,
        number: resp.consecutivo
    };
    
    // Realizar la solicitud AJAX
    $.ajax({
        url: 'https://facturaconmiggo.com/api/send-email-customer',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + resp.token
        },
        data: JSON.stringify(body),
        success: function(response) {
            // Manejar la respuesta exitosa
            if (response) {
                alert('Correo enviado correctamente.');
            } else {
                alert('No fue posible enviar el correo. Por favor, inténtelo nuevamente.');
            }
        },
        error: function(xhr, status, error) {
            // Manejar errores
            alert('Hubo un error al enviar el correo. Por favor, inténtelo nuevamente.');
        }
    });
}

/**
 * Sincroniza la factura con la Dian
 * @param {*} resp 
 */
var sincronizarFacturaDian = function(resp, facturaId, redirectTo) {

    $('#fact_status').text('Enviando factura a la DIAN.');

    $.ajax({
        url: 'https://facturaconmiggo.com/api/ubl2.1/invoice',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + resp.token
        },
        data: JSON.stringify(resp[0]),
        success: function(response) {

            i_syncData(facturaId, '2');

            if(response.cufe) {

                $('#checksync_' + facturaId).removeClass('fa-times text-danger').addClass('fa-check text-success');

                enviarCorreoCliente(resp);
                guardarInfoDian(response, facturaId, redirectTo);

            } else {
                $('#checksync_' + facturaId).removeClass('fa-check text-success').addClass('fa-times text-danger');
            }
            
        },
        error: function(xhr, status, error) {

            if (redirectTo == 1) {
                window.location.href = $('#url-proyecto').val() + 'facturas/view/' + facturaId;
            }

            i_syncData(facturaId, '2');
            $('#checksync_' + facturaId).removeClass('fa-check text-success').addClass('fa-times text-danger');
            alert('Hubo un error al sincronizar la factura. Por favor, inténtelo nuevamente.');
        }
    });

}

/**
 * Obtiene la información de la factura que se desea enviar a la Dian
 * @param {*} elemento 
 */
function obtenerFacturaDian(elemento, facturaId, redirectTo) {

    var facturaId = $(elemento).data('id') != null ? $(elemento).data('id') : facturaId;

    i_syncData(facturaId, '1');

    $('#fact_status').text('Generando comunicación con la DIAN.');

    $.ajax({
        url: $('#url-proyecto').val() + 'facturas/obtenerFacturaParaDian',
        data: {facturaId: facturaId},
        type: "POST",
        async: false,
        success: function(data) {
            var resp = JSON.parse(data);
            if(resp){

                sincronizarFacturaDian(resp, facturaId, redirectTo);
                
            }else{

                if (redirectTo == 1) {
                    window.location.href = $('#url-proyecto').val() + 'facturas/view/' + facturaId;
                }
                alert('No fue posible sincronizar la factura. Por favor, inténtelo nuevamente.');
            }
        },
        error: function(xhr, status, error) {

            if (redirectTo == 1) {
                window.location.href = $('#url-proyecto').val() + 'facturas/view/' + facturaId;
            }
            
            i_syncData(facturaId, '2');
            $('#checksync_' + facturaId).removeClass('fa-check text-success').addClass('fa-times text-danger');
            alert('Hubo un error al sincronizar la factura. Por favor, inténtelo nuevamente.');
        }
    });
    
}


$(function(){

    // Usar la clase para manejar múltiples elementos
    $('.syncdian').click(function() {
        obtenerFacturaDian(this, null, 2);
    });

});