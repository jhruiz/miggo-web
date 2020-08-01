function obtenerPartesVehiculo(){
    var tipoV = $('#PartevehiculoTipovehiculoId').val();
    
    $.ajax({
        url: $('#url-proyecto').val() + 'partevehiculosTipovehiculos/ajaxObtenerPartesTipoVehiculo',
        data: {tipoV: tipoV},
        type: "POST",
        success: function(data) {
            var partes = JSON.parse(data);
            var uls = "";
            uls += "<option value=''>Seleccione Uno</option>";
            $.each(partes.partes,  function(key, val){
                uls += "<option value='" + key + "'>" + val + "</option>";
            });
            $('#PartevehiculoPartevehiculoId').html(uls);
        }
    });     
}


$(function() {    
    $('#PartevehiculoTipovehiculoId').change(obtenerPartesVehiculo);
});