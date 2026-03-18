var generarAnalisis = function( settings ) {
    // 1. Mostrar la modal con un cargando antes de la petición
    $('#cuerpoModalIA').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i><p>Analizando datos financieros...</p></div>');
    $('#modalAnalisisIA').modal('show');

    $.ajax(settings).done(function (response) {
        var textoMarkdown = response.candidates[0].content.parts[0].text;

        // Limpiamos el texto antes de mostrarlo
        var htmlConvertido = textoMarkdown
            .replace(/### /g, '') // Quitamos los ###
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // Negritas
            .replace(/^\s*[\-\*]\s+(.*)$/gm, '<li>$1</li>')    // Listas
            .replace(/\n\n/g, '<br>') 
            .replace(/\n/g, '<br>');

        // Envolver listas
        htmlConvertido = htmlConvertido.replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>');

        $('#cuerpoModalIA').html('<div class="analisis-ia-content">' + htmlConvertido + '</div>');
    }).fail(function () {
        $('#cuerpoModalIA').html('<div class="alert alert-danger">No se pudo generar el análisis en este momento.</div>');
    });
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

    var promptCierreDiario = "Eres el Director Financiero (CFO) de " + datos.nombreEmpresa;
    promptCierreDiario += "Tu función NO es resumir datos (el usuario ya tiene los totales), sino realizar un análisis crítico. ";
    promptCierreDiario += "Debes identificar riesgos (como gastos altos, cuentas sin especificar, cómo gestionar abonos, consejos de compras a credito), "; 
    promptCierreDiario += "evaluar la liquidez según los medios de pago y dar 2 recomendaciones estratégicas de negocio. ";
    promptCierreDiario += "Usa un tono ejecutivo, directo y analítico. ";

    var settings = {
    "url": "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=AIzaSyDpvXIyqZ_CmU5LQm-jqVqIQZCJpCyNlNE",
    "method": "POST",
    "timeout": 0,
    "headers": {
        "Content-Type": "application/json"
    },
    "data": JSON.stringify({
        "system_instruction": {
        "parts": [
            {
            "text": promptCierreDiario
            }
        ]
        },
        "contents": [
        {
            "parts": [
            {
                "text": JSON.stringify(datos)
            }
            ]
        }
        ],
        "generationConfig": {
        "temperature": 0.1,
        "maxOutputTokens": 1000,
        "responseMimeType": "text/plain"
        }
    }),
    };

    return settings;

}


