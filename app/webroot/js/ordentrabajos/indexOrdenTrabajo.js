/**
 * Al seleccionar la placa deseada, se obtiene el id del vehiculo y la placa
 * @param {type} data
 * @returns {undefined}
 */
function seleccionarVehiculo(data){
    console.log(data); 
    $('#datosVehiculo').html("");
    $('#datosVehiculo').hide();
    $('#vehiculo_id').val(data.id);        
    $('#OrdentrabajosPlaca').val(data.name);        
}

/**
 * Obtienen los datos del vehiculo con la coincidencia de la placa
 * @returns {undefined}
 */
var obtenerDatosVehiculo = function(){    
    var plcV = $(this).val();
    
    if(plcV.length > 3){
        $.ajax({
                url: $('#url-proyecto').val() + 'vehiculos/ajaxObtenerVehiculo',
                data: {datosVehiculo: plcV},
                type: "POST",
                success: function(data) {

                    var vehiculo = JSON.parse(data);
                    var uls = "";
                    for(var i = 0; i < vehiculo.resp.length; i++){
                        uls += "<a href='#' class='list-group-item list-group-item-info' name='" + vehiculo.resp[i].Vehiculo.placa + "'";
                        uls += " id='" + vehiculo.resp[i].Vehiculo.id + "' onClick ='seleccionarVehiculo(this)'>" + vehiculo.resp[i].Vehiculo.placa + "</a>";
                    }
                    $('#datosVehiculo').show();
                    $('#datosVehiculo').html(uls);
                }
            });        
    }else{
        $('#datosVehiculo').html("");
        $('#datosVehiculo').hide();
        $('#vehiculo_id').val("");                
    }    
};

$(function() {       
    $('#OrdentrabajosPlaca').keyup(obtenerDatosVehiculo);
});
