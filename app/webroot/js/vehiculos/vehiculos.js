var placa = "";

var validarVehiculoUnico = function(){
    var placaN = $('#VehiculoPlaca').val();
    
    if(placaN != "" && placaN != placa){
        $.ajax({
            url: $('#url-proyecto').val() + 'vehiculos/ajaxValidarVehiculoUnico',
            data: {placaN: placaN},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if(resp.resp == '1'){
                    bootbox.alert('el número de placa ' + placaN + ', ya fue asignada a otro vehículo.');
                    $('#VehiculoPlaca').val(placa);
                }
            }
        });         
    }    
};    

/**
 * Formato a los input tipo date
 * @returns {undefined}
 */
var datePicker = function(){
    $(".date").datepicker({dateFormat: 'yy-mm-dd'});
    $(".date").datepicker("option", "showAnim", "slideDown");    
};

$(function() { 
    
    placa = $('#VehiculoPlaca').val();
    $('#VehiculoPlaca').change(validarVehiculoUnico);
    datePicker();
});