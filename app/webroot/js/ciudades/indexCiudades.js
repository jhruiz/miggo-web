var descargarCiudades = function(){
    $.ajax({
        url: $('#url-proyecto').val() + 'ciudades/descargarListaCiudades2',
        type: "POST",
        success: function(data) {
        }
    });    
};

$(function() {       
    $('#descargar').click(descargarCiudades);
});

