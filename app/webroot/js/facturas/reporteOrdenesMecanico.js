/**
 * Formato a los input tipo date
 * @returns {undefined}
 */
var datePicker = function(){
    $(".date").datepicker({dateFormat: 'yy-mm-dd'});
    $(".date").datepicker("option", "showAnim", "slideDown");    
};

var cambiarestadopago = function(){    
    var estado = this.checked ? '2' : '1';
    var id = this.id;
    
        $.ajax({
            url: $('#url-proyecto').val() + 'facturas/actestadopagomec',
            data: {factura_id: id, estado: estado},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if(resp.resp){
                    $('#fecpag_'+id).append(resp.fecha);
                    bootbox.alert('Se guarda el estado de pago del servicio.');
                }else{
                    alert('No fue posible guardar el estado de pago del servicio. Por favor, inténtelo nuevamente.');
                }
            }
        });  
};

$(function() {    
    datePicker();
    
    $('.estadopago').click(cambiarestadopago);
});