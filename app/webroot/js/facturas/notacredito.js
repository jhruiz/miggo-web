
var opcNotaCredito = {
    autoOpen: false,
    modal: true,
    width: 900,
    height: 550,
    position: [400, 50],
    show: {
        duration: 400    
    },
    title: 'Métodos de devolución de dinero'    
};

var dialogNotaCredito;

function generarNotaCredito(facturaId){
        $("#div_notacredito").load(
            $('#url-proyecto').val() + "facturas/datosnotacredito",
            {facturaId: facturaId},
            function(){                                                            
                dialogNotaCredito=$("#div_notacredito").dialog(opcNotaCredito);
                dialogNotaCredito.dialog('open');
            }
        );
}

/**
 * Si la respuesta es satifactoria, se actualiza la información de la Dian en la factura
 * @param {*} response 
 */
var  guardarNCInfoDian = function( response, facturaId ) {

    $('#fact_status').text('Nota crédito enviada a la DIAN.');

    // Realizar la solicitud AJAX
    $.ajax({
        url: $('#url-proyecto').val() + 'facturas/guardarNCInfoDian',
        method: 'POST',
        data: {
            statusCode: response.ResponseDian.Envelope.Body.SendBillSyncResponse.SendBillSyncResult.StatusCode,
            cude: response.cude,
            QR: response.QRStr,
            facturaId: facturaId
        },
        success: function(response) {
            // Manejar la respuesta exitosa
            if (response) {
                bootbox.alert('Nota crédito generada exitosamente.', function() {
                    location.reload();
                });
            } else {
                bootbox.alert('No fue posible sincronizar la nota crédito en la Dian. Por favor, realice el debido proceso en el portal de la DIAN.', function() {
                    location.reload();
                });
            }
        },
        error: function(xhr, status, error) {

            bootbox.alert('No fue posible sincronizar la nota crédito en la Dian. Por favor, realice el debido proceso en el portal de la DIAN.', function() {
                location.reload();
            });
            
        }
    });
} 


/**
 * Sincroniza la factura con la Dian
 * @param {*} resp 
 */
var sincronizarNCDian = function(resp, facturaId) {

    $('#fact_status').text('Enviando nota crédito a la DIAN.');

    $.ajax({
        url: 'https://facturaconmiggo.com/api/ubl2.1/credit-note',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + resp.token
        },
        data: JSON.stringify(resp[0]),
        success: function(response) {
            console.log(response);
            if(response.cude) {

                guardarNCInfoDian(response, facturaId);

            } else {
                bootbox.alert('No fue posible sincronizar la nota crédito en la Dian. Por favor, realice el debido proceso en el portal de la DIAN.', function() {
                    location.reload();
                });                
            }
            
        },
        error: function(xhr, status, error) {
            bootbox.alert('No fue posible sincronizar la nota crédito en la Dian. Por favor, realice el debido proceso en el portal de la DIAN.', function() {
                location.reload();
            });
        }
    });

}


/**
 * Genera y obtiene la factura a la cual se le va generar la nota crédito
 * para enviar a la Dian
 * @param {*} facturaId 
 */
function obtenerNCDian(facturaId) {

    $('#fact_status').text('Generando comunicación con la DIAN.');

    $.ajax({
        url: $('#url-proyecto').val() + 'facturas/obtenerNCParaDian',
        data: {facturaId: facturaId},
        type: "POST",
        async: false,
        success: function(data) {
            var resp = JSON.parse(data);
            console.log(resp);
            if(resp.status){

                sincronizarNCDian(resp, facturaId);
                
            }else{
                bootbox.alert('No fue posible sincronizar la nota crédito en la Dian. Por favor, realice el debido proceso en el portal de la DIAN.', function() {
                    location.reload();
                });
            }
        },
        error: function(xhr, status, error) {
            bootbox.alert('No fue posible sincronizar la nota crédito en la Dian. Por favor, realice el debido proceso en el portal de la DIAN.', function() {
                location.reload();
            });
        }
    });
}