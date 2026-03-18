
var generarAnalisis = function( settings ) {
    // 1. Mostrar modal de carga
    $('#cuerpoModalIA').html('<div class="text-center" style="padding:40px;"><i class="fa fa-spinner fa-spin fa-3x" style="color:#2563eb;"></i><p style="margin-top:20px; font-weight:bold; font-size:18px;">Preparando diagnóstico financiero...</p></div>');
    $('#modalAnalisisIA').modal('show');

    // 2. Petición AJAX a tu propio servidor
    $.ajax({
        url: settings.url,
        type: settings.method,
        data: settings.data,
        contentType: "application/json",
        success: function (data) {
            try {
                // Parseamos la respuesta (que viene de tu PHP)
                var response = (typeof data === "object") ? data : JSON.parse(data);

                // Verificamos si Google respondió con éxito a través de tu PHP
                if (response.candidates && response.candidates[0].content.parts[0].text) {
                    var rawText = response.candidates[0].content.parts[0].text;

                    // Formateo de 18px (tu diseño ganador)
                    var htmlFinal = procesarTextoIA(rawText);

                    $('#cuerpoModalIA').html('<div class="analisis-ia-content">' + htmlFinal + '</div>');

                    // Ajuste de listas
                    $('.analisis-ia-content').html(function(i, html) {
                        return html.replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>').replace(/<\/li><br><li>/g, '</li><li>');
                    });
                } else {
                    $('#cuerpoModalIA').html('<div class="alert alert-warning">El análisis no pudo ser procesado. Intente más tarde.</div>');
                }
            } catch (e) {
                $('#cuerpoModalIA').html('<div class="alert alert-danger">Error de formato en la respuesta del servidor.</div>');
            }
        },
        error: function () {
            $('#cuerpoModalIA').html('<div class="alert alert-danger">No hay conexión con el servicio de análisis.</div>');
        }
    });
}

// Función auxiliar para no ensuciar el success
function procesarTextoIA(texto) {
    return texto
        .replace(/###/g, '')
        .replace(/^\s*[,.]\s+/gm, '')
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/^\s*[\-\*→]\s+(.*)$/gm, '<li>$1</li>')
        .replace(/\n\n/g, '</p><p>')
        .replace(/\n/g, '<br>');
}

var analizarCierreDiarioIA = function() {

    var fechaCierre = $('#ReporteRpfechacierre').val();
    var cuenta = $('#ReporteRpcuenta').val();
    
    $.ajax({
        url: $('#url-proyecto').val() + 'iaconsultas/analisiscierrediario',
        data: {fechaCierre: fechaCierre, cuenta: cuenta},
        type: "POST",
        async: false,
        success: function(data) {
            var resp = JSON.parse(data);

            var settings = generarSettings( resp );

            generarAnalisis(settings);

        },
        error: function(xhr, status, error) {
            alert('Hubo un error al obtener datos. Por favor, inténtelo nuevamente.');
        }
    });
}


var generarSettings = function( datos ) {

    // Solo enviamos lo básico. El PHP se encarga del resto.
    var payload = {
        "empresa": datos.nombreEmpresa,
        "reporte_data": JSON.stringify(datos) 
    };

    var settings = {
        // Asegúrate de que esta URL sea el NUEVO nombre de tu función en PHP
        "url": $('#url-proyecto').val() + "iaconsultas/obteneranalisisgerencial", 
        "method": "POST",
        "data": JSON.stringify(payload),
        "contentType": "application/json"
    };

    return settings;

}


