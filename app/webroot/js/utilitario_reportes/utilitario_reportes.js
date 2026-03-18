// var generarAnalisis = function( settings ) {
//     // 1. Mostrar la modal con el cargando
//     $('#cuerpoModalIA').html('<div class="text-center" style="padding:40px;"><i class="fa fa-spinner fa-spin fa-3x" style="color:#2563eb;"></i><p style="margin-top:20px; font-weight:bold;">Analizando datos del cierre diario...</p></div>');
//     $('#modalAnalisisIA').modal('show');

//     $.ajax({
//         url: settings.url,
//         method: settings.method,
//         data: settings.data,
//         contentType: "application/json",
//         success: function (data) {
//             try {
//                 // --- EL TRUCO ESTÁ AQUÍ ---
//                 // Si data ya es objeto, lo usamos. Si es texto, lo convertimos.
//                 var response = (typeof data === "object") ? data : JSON.parse(data);

//                 if (response.candidates && response.candidates[0].content.parts[0].text) {
//                     var rawText = response.candidates[0].content.parts[0].text;

//                     // --- Tu lógica de limpieza que ya funciona ---
//                     var textoProcesado = rawText
//                         .replace(/###/g, '')
//                         .replace(/^\s*[,.]\s+/gm, '')
//                         .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
//                         .replace(/^\s*[\-\*→]\s+(.*)$/gm, '<li>$1</li>');

//                     var htmlFinal = textoProcesado
//                         .replace(/\n\n/g, '</p><p>')
//                         .replace(/\n/g, '<br>'); 

//                     // Inyectamos con la clase de 18px
//                     $('#cuerpoModalIA').html('<div class="analisis-ia-content"><p>' + htmlFinal + '</p></div>');

//                     // Arreglamos las listas
//                     $('.analisis-ia-content').html(function(i, html) {
//                         return html.replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>').replace(/<\/li><br><li>/g, '</li><li>');
//                     });
//                 } else {
//                     $('#cuerpoModalIA').html('<div class="alert alert-warning">Estructura de respuesta inesperada de la IA.</div>');
//                 }
//             } catch (e) {
//                 console.error("Error al procesar JSON:", e);
//                 $('#cuerpoModalIA').html('<div class="alert alert-danger">Error al procesar la respuesta del servidor.</div>');
//             }
//         },
//         error: function (xhr) {
//             $('#cuerpoModalIA').html('<div class="alert alert-danger">Error de conexión con el puente PHP.</div>');
//         }
//     });
// }

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

    // // El prompt sigue igual, eso no cambia
    // var promptCierreDiario = "Eres el Director Financiero (CFO) de " + datos.nombreEmpresa;
    // promptCierreDiario += "Tu función NO es resumir datos (el usuario ya tiene los totales), sino realizar un análisis crítico. ";
    // promptCierreDiario += "Debes identificar riesgos (como gastos altos, cuentas sin especificar, cómo gestionar abonos, consejos de compras a credito), "; 
    // promptCierreDiario += "evaluar la liquidez según los medios de pago y dar 2 recomendaciones estratégicas de negocio. ";
    // promptCierreDiario += "Usa un tono ejecutivo, directo y analítico. ";

    // // Solo estamos armando el "mapa" para el AJAX
    // var settings = {
    //     "url": $('#url-proyecto').val() + "iaconsultas/obteneranalisisgerencial", // <--- TU CONTROLADOR
    //     "method": "POST",
    //     "data": JSON.stringify({
    //         "system_instruction": { "parts": [{ "text": promptCierreDiario }] },
    //         "contents": [{ "parts": [{ "text": JSON.stringify(datos) }] }],
    //         "generationConfig": {
    //             "temperature": 0.2,
    //             "maxOutputTokens": 1000,
    //             "responseMimeType": "text/plain"
    //         }
    //     })
    // };
    // return settings;

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


