var generarAnalisis = function( settings ) {
    // 1. Mostrar la modal con un cargando antes de la petición
    $('#cuerpoModalIA').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i><p>Analizando datos financieros...</p></div>');
    $('#modalAnalisisIA').modal('show');

    $.ajax(settings).done(function (response) {
        var rawText = response.candidates[0].content.parts[0].text;

        // LIMPIEZA MÍNIMA Y EFECTIVA
        var textoProcesado = rawText
            .replace(/###/g, '')                      // Quita los hash de títulos
            .replace(/^\s*[,.]\s+/gm, '')            // Quita puntos o comas locos al inicio de línea
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // Negritas
            .replace(/^\s*[\-\*→]\s+(.*)$/gm, '<li>$1</li>'); // Viñetas

        // Convertimos saltos de línea en espacios para que el texto fluya, 
        // pero mantenemos los párrafos dobles.
        var htmlFinal = textoProcesado
            .replace(/\n\n/g, '</p><p>')
            .replace(/\n/g, '<br>'); 

        $('#cuerpoModalIA').html('<div class="analisis-ia-content"><p>' + htmlFinal + '</p></div>');

        // Forzamos que las listas no tengan saltos de línea extraños
        $('.analisis-ia-content').html(function(i, html) {
            return html.replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>').replace(/<\/li><br><li>/g, '</li><li>');
        });

    }).fail(function () {
        $('#cuerpoModalIA').html('<div class="alert alert-danger">Error en el análisis financiero.</div>');
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


