var descargarPlantilla = function(){
    window.location.href = '/files/productos.csv';    
}

function downloadCsvError(archivo) {
    window.location.href = '/' + archivo;
}

$( function() {
    $('#plantilla').click(descargarPlantilla);
    $('#plantilla').css('cursor', 'pointer');
});