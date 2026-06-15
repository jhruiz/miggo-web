function zfill(number, width) {
    var numberOutput = Math.abs(number); /* Valor absoluto del número */
    var length = number.toString().length; /* Largo del número */
    var zero = "0"; /* String de cero */

    if (width <= length) {
        if (number < 0) {
            return ("-" + numberOutput.toString());
        } else {
            return numberOutput.toString();
        }
    } else {
        if (number < 0) {
            return ("-" + (zero.repeat(width - length)) + numberOutput.toString());
        } else {
            return ((zero.repeat(width - length)) + numberOutput.toString());
        }
    }
}

var obtenerFechaActual = function() {
    var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    var f = new Date();
    return (meses[f.getMonth()] + " " + f.getDate() + ", " + f.getFullYear() + ' ' + f.getHours() + ':' + f.getMinutes() +  ':' + f.getSeconds());
};

/**
 * PUNTO DE ENTRADA NUEVO: Invoca esto para imprimir en Tirilla de 8cm
 */
var imprimirEnTicketFacturaRapida = function() {
    if ($('#esFacturaDV').val() == '1') {
        imprimirPrefacturaFacturaRapidaTicket();
    } else {
        imprimirPrefacturaDocumentoEquivalenteTicket();
    }
};

// ==========================================
// PREFACTURA FACTURA (CON IVA) EN TICKET 80MM - TOTALMENTE CORREGIDO
// ==========================================
var imprimirPrefacturaFacturaRapidaTicket = function() {
    var prefactId = $('#prefacturaId').val();
    
    // BLINDAJE 1: Guardamos la marca de persistencia INMEDIATAMENTE al iniciar la impresión
    // por si cualquier error posterior intenta tumbar el Fullscreen.
    if (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement) {
        localStorage.setItem('pos_debe_ser_fullscreen', '1');
    }
    
    $.ajax({
        url: $('#url-proyecto').val() + 'prefacturas/ajaxObtenerDetallesPrefactura',
        data: { prefactId: prefactId },
        type: "POST",
        success: function(data) {
            var prefact = JSON.parse(data);
            var fechaActual = obtenerFechaActual();
            
            var iframeId = 'iframe_print_pos_silent';
            $('#' + iframeId).remove(); 
            
            var iframe = document.createElement('iframe');
            iframe.id = iframeId;
            iframe.style.position = 'absolute';
            iframe.style.width = '0px';
            iframe.style.height = '0px';
            iframe.style.border = 'none';
            
            document.body.appendChild(iframe);
            
            var mywindow = iframe.contentWindow || iframe.contentDocument;
            if (mywindow.document) { mywindow = mywindow.document; }
            
            mywindow.open();
            mywindow.write('<html><head><title>' + (prefact.resp['0'].C.nombre || 'PREFACTURA') + '</title>');
            mywindow.write('<style>');
            mywindow.write('body { margin: 0; padding: 0; font-family: Arial, sans-serif; font-size: 11px; width: 80mm; color: #000; line-height: 1.2; }');
            mywindow.write('@page { size: 80mm auto; margin: 0mm; }');
            mywindow.write('@media print { body { margin: 0; width: 72mm; padding: 2mm; } .no-print { display: none; } }');
            mywindow.write('table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 5px; }');
            mywindow.write('th { font-size: 10px; padding: 4px 0; border-bottom: 1px solid #000; border-top: 1px solid #000; text-align: left; }');
            mywindow.write('td { font-size: 10px; padding: 4px 0; vertical-align: top; }');
            mywindow.write('.separador { border-top: 1px dashed #000; margin: 6px 0; }');
            mywindow.write('.text-center { text-align: center; } .text-right { text-align: right; }');
            mywindow.write('img { max-width: 100%; height: auto; display: block; margin: 0 auto; padding-bottom: 5px; }');
            mywindow.write('</style></head><body>');

            mywindow.write('<div style="width: 100%;">');
            
            var rawImgHtml = $('#dv_img_emp').html() || "";
            if (rawImgHtml.trim() !== "") {
                var fixedImgHtml = rawImgHtml.replace('<img', '<img onerror="this.style.display=\'none\';"');
                mywindow.write('<div>' + fixedImgHtml + '</div>');
            }
            
            mywindow.write('<div class="text-center" style="font-size:12px;"><b>' + prefact.resp['0'].EM.nombre + '</b></div>');
            mywindow.write('<div class="text-center" style="font-size:11px;"><b>PREFACTURA No. ' + zfill(prefactId, 6) + '</b></div>');
            
            mywindow.write('<div><b>Nit:</b> ' + prefact.resp['0'].EM.nit + '</div>');
            mywindow.write('<div><b>Teléfono:</b> ' + prefact.resp['0'].EM.telefono1 + '</div>');
            mywindow.write('<div><b>Dirección:</b> ' + prefact.resp['0'].EM.direccion + '</div>');
            mywindow.write('<div class="separador"></div>');
            
            var ciudadNom = prefact.resp['0'].CIU && prefact.resp['0'].CIU.descripcion ? prefact.resp['0'].CIU.descripcion : "";
            var paisNom = prefact.resp['0'].PAI && prefact.resp['0'].PAI.descripcion ? prefact.resp['0'].PAI.descripcion : "";
            var ubicacion = [ciudadNom, paisNom, fechaActual].filter(Boolean).join(', ');
            mywindow.write('<div>' + ubicacion + '</div>');
            
            if (prefact.resp['0'].EM.texto1) { mywindow.write('<div>' + prefact.resp['0'].EM.texto1 + '</div>'); }
            mywindow.write('<div class="separador"></div>');

            var clienteNombre = "", clienteNit = "", clienteCelular = "", clienteDireccion = "";
            if ($('#PrefacturaDatoscliente').val() != "") {
                clienteNombre = prefact.resp['0'].C.nombre; clienteNit = prefact.resp['0'].C.nit; clienteCelular = prefact.resp['0'].C.celular; clienteDireccion = prefact.resp['0'].C.direccion;
            } else if ($('#PrefacturaNuevonombre').val() != "") {
                clienteNombre = $('#PrefacturaNuevonombre').val(); clienteNit = $('#PrefacturaNuevonit').val(); clienteCelular = $('#PrefacturaNuevocelular').val(); clienteDireccion = $('#PrefacturaNuevodireccion').val();
            } else {
                clienteNombre = $('#PrefacturaRapidanombre').val(); clienteNit = $('#PrefacturaRapidanit').val(); clienteCelular = $('#PrefacturaRapidatelefono').val(); clienteDireccion = $('#PrefacturaRapidadireccion').val();
            }
            
            mywindow.write('<div><b>Cliente:</b> ' + (clienteNombre || '') + '</div>');
            mywindow.write('<div><b>Identificación:</b> ' + (clienteNit || '') + '</div>');
            if (clienteCelular && clienteCelular !== 'null') mywindow.write('<div><b>Teléfono:</b> ' + clienteCelular + '</div>');
            if (clienteDireccion && clienteDireccion !== 'null') mywindow.write('<div><b>Dirección:</b> ' + clienteDireccion + '</div>');
            
            if (prefact.resp['0'].V && prefact.resp['0'].V.placa != null) {
                mywindow.write('<div class="separador"></div>');
                mywindow.write('<div><b>Placa:</b> ' + prefact.resp['0'].V.placa + '</div>');
                mywindow.write('<div><b>Línea:</b> ' + prefact.resp['0'].V.linea + '</div>');
            }
            
            mywindow.write('<table><thead>');
            mywindow.write('<tr><th style="width:10%;">CANT</th><th style="width:38%;">DESCRIPCIÓN</th><th class="text-right" style="width:26%;">VLR. UNIT</th><th class="text-right" style="width:26%;">TOTAL</th></tr>');
            mywindow.write('</thead><tbody>');

            var ttalBases = 0, ttalImp = 0, ttalDcto = 0;

            if (prefact.resp.length > 0) {
                $.each(prefact.resp, function(k, val) {
                    var cant = parseFloat(val.PFD.cantidad) || 0;
                    var costoVentaTotal = parseFloat(val.PFD.costoventa) || 0; 
                    var pctIva = parseFloat(val.PFD.impuesto) || 0;
                    var valDcto = parseFloat(val.PFD.descuento) || 0;
                    
                    var vlrUnitarioItem = costoVentaTotal;
                    var subtotalFilaNeto = vlrUnitarioItem * cant;
                    var valorIvaFila = 0;

                    if (pctIva > 0) {
                        vlrUnitarioItem = costoVentaTotal / (1 + (pctIva / 100));
                        subtotalFilaNeto = vlrUnitarioItem * cant;
                        valorIvaFila = subtotalFilaNeto * (pctIva / 100);
                    }

                    ttalBases += subtotalFilaNeto; ttalImp += valorIvaFila; ttalDcto += valDcto;
                    var descTxt = val.PFD.porcentaje && parseFloat(val.PFD.porcentaje) > 0 ? ' (' + val.PFD.porcentaje + '% Dcto)' : '';
                    var marcImp = pctIva > 0 ? '*' : '';

                    mywindow.write('<tr>');
                    mywindow.write('<td>' + cant + '</td>');
                    mywindow.write('<td>' + val.P.descripcion + marcImp + descTxt + '</td>');
                    mywindow.write('<td class="text-right">' + vlrUnitarioItem.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>');
                    mywindow.write('<td class="text-right">' + subtotalFilaNeto.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>');
                    mywindow.write('</tr>');
                });
            }
            mywindow.write('</tbody></table>');
            mywindow.write('<div class="separador"></div>');
            
            var subtotalConDescuento = ttalBases - ttalDcto;
            var vrAbonos = $('.ttalAbonos').val() != "" ? parseFloat($('.ttalAbonos').val()) : 0;
            var ttalFinal = subtotalConDescuento + ttalImp - vrAbonos;

            mywindow.write('<table style="margin-top:0px; width:100%;">');
            mywindow.write('<tr><td class="text-right" style="width:60%;"><b>Subtotal:</b></td><td class="text-right" style="width:40%;">' + ttalBases.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>');
            mywindow.write('<tr><td class="text-right"><b>Subtotal con Dcto:</b></td><td class="text-right">' + subtotalConDescuento.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>');
            mywindow.write('<tr><td class="text-right"><b>IVA:</b></td><td class="text-right">' + ttalImp.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>');
            
            if (vrAbonos > 0) {
                mywindow.write('<tr><td class="text-right"><b>Abonos:</b></td><td class="text-right">' + vrAbonos.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>');
            }
            
            mywindow.write('<tr><td class="text-right" style="font-size:12px; border-top:1px solid #000; padding-top:4px;"><b>TOTAL:</b></td><td class="text-right" style="font-size:12px; border-top:1px solid #000; padding-top:4px;"><b>' + ttalFinal.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</b></td></tr>');
            mywindow.write('</table>');
            
            var nota = prefact.resp['0'].Prefactura && prefact.resp['0'].Prefactura.observacion ? prefact.resp['0'].Prefactura.observacion : "";
            if (nota != "" && nota !== 'null') { mywindow.write('<div style="margin-top:10px; font-size:10px;"><b>Nota:</b> ' + nota + '</div>'); }
            
            mywindow.write('</div>');
            mywindow.write('</body></html>');
            
            // CORRECCIÓN CLAVE 1: Eliminamos la llamada a mywindow.close() para que no rompa el foco del POS principal.

            setTimeout(function() {
                // Enfocamos e imprimimos el contenido del iframe
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                
                setTimeout(function() {
                    // CORRECCIÓN CLAVE 2: En lugar de cerrar la ventana, destruimos el nodo iframe del DOM de forma segura
                    $('#' + iframeId).remove(); 
                    
                    // Ejecutamos la recarga mandatoria de tu ERP
                    location.reload();
                }, 400);

            }, 300);
        }
    });
};

// ==========================================
// PREFACTURA SIN IVA (DOC EQUIVALENTE) EN TICKET 80MM - CORREGIDO IMAGEN
// ==========================================
var imprimirPrefacturaDocumentoEquivalenteTicket = function() {
    var prefactId = $('#prefacturaId').val();
    $.ajax({
        url: $('#url-proyecto').val() + 'prefacturas/ajaxObtenerDetallesPrefactura',
        data: { prefactId: prefactId },
        type: "POST",
        success: function(data) {
            var prefact = JSON.parse(data);
            var fechaActual = obtenerFechaActual();

            var mywindow = window.open('', 'PRINT', 'height=600,width=400');
            mywindow.document.write('<html><head><title>' + (prefact.resp['0'].C.nombre || 'PREFACTURA') + '</title>');
            mywindow.document.write('<style>');
            mywindow.document.write('body { margin: 0; padding: 0; font-family: Arial, sans-serif; font-size: 11px; width: 80mm; color: #000; line-height: 1.2; }');
            mywindow.document.write('@page { size: 80mm auto; margin: 0mm; }');
            mywindow.document.write('@media print { body { margin: 0; width: 72mm; padding: 2mm; } .no-print { display: none; } }');
            mywindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 5px; }');
            mywindow.document.write('th { font-size: 10px; padding: 4px 0; border-bottom: 1px solid #000; border-top: 1px solid #000; text-align: left; }');
            mywindow.document.write('td { font-size: 10px; padding: 4px 0; vertical-align: top; }');
            mywindow.document.write('.separador { border-top: 1px dashed #000; margin: 6px 0; }');
            mywindow.document.write('.text-center { text-align: center; } .text-right { text-align: right; }');
            mywindow.document.write('img { max-width: 100%; height: auto; display: block; margin: 0 auto; padding-bottom: 5px; }');
            mywindow.document.write('</style></head><body>');

            mywindow.document.write('<div style="width: 100%;">');
            
            // Tratamiento del contenedor de imagen para mitigar errores de carga o rutas rotas
            var rawImgHtml = $('#dv_img_emp').html() || "";
            if (rawImgHtml.trim() !== "") {
                var fixedImgHtml = rawImgHtml.replace('<img', '<img onerror="this.style.display=\'none\';"');
                mywindow.document.write('<div>' + fixedImgHtml + '</div>');
            }
            
            var repNombre = prefact.resp['0'].RE && prefact.resp['0'].RE.nombre ? prefact.resp['0'].RE.nombre : "";
            var repLegal = prefact.resp['0'].RE && prefact.resp['0'].RE.representantelegal ? prefact.resp['0'].RE.representantelegal : "";
            
            if (repNombre) mywindow.document.write('<div class="text-center" style="font-size:12px;"><b>' + repNombre + '</b></div>');
            if (repLegal) mywindow.document.write('<div class="text-center" style="font-size:11px;"><b>' + repLegal + '</b></div>');
            mywindow.document.write('<div class="text-center" style="font-size:11px;"><b>PREFACTURA No. ' + zfill(prefactId, 6) + '</b></div>');
            
            if (prefact.resp['0'].RE) {
                mywindow.document.write('<div><b>Nit:</b> ' + prefact.resp['0'].RE.nit + '</div>');
                mywindow.document.write('<div><b>Teléfono:</b> ' + prefact.resp['0'].RE.telefono1 + '</div>');
                mywindow.document.write('<div><b>Dirección:</b> ' + prefact.resp['0'].RE.direccion + '</div>');
            }
            
            mywindow.document.write('<div class="separador"></div>');
            
            var ciudadNom = prefact.resp['0'].CIU && prefact.resp['0'].CIU.descripcion ? prefact.resp['0'].CIU.descripcion : "";
            var paisNom = prefact.resp['0'].PAI && prefact.resp['0'].PAI.descripcion ? prefact.resp['0'].PAI.descripcion : "";
            var ubicacion = [ciudadNom, paisNom, fechaActual].filter(Boolean).join(', ');
            mywindow.document.write('<div>' + ubicacion + '</div>');
            
            mywindow.document.write('<div class="separador"></div>');

            var clienteNombre = "", clienteNit = "", clienteCelular = "", clienteDireccion = "";
            if ($('#PrefacturaDatoscliente').val() != "") {
                clienteNombre = prefact.resp['0'].C.nombre; clienteNit = prefact.resp['0'].C.nit; clienteCelular = prefact.resp['0'].C.celular; clienteDireccion = prefact.resp['0'].C.direccion;
            } else if ($('#PrefacturaNuevonombre').val() != "") {
                clienteNombre = $('#PrefacturaNuevonombre').val(); clienteNit = $('#PrefacturaNuevonit').val(); clienteCelular = $('#PrefacturaNuevocelular').val(); clienteDireccion = $('#PrefacturaNuevodireccion').val();
            } else {
                clienteNombre = $('#PrefacturaRapidanombre').val(); clienteNit = $('#PrefacturaRapidanit').val(); clienteCelular = $('#PrefacturaRapidatelefono').val(); clienteDireccion = $('#PrefacturaRapidadireccion').val();
            }
            
            mywindow.document.write('<div><b>Cliente:</b> ' + (clienteNombre || '') + '</div>');
            mywindow.document.write('<div><b>Identificación:</b> ' + (clienteNit || '') + '</div>');
            if (clienteCelular && clienteCelular !== 'null') mywindow.document.write('<div><b>Teléfono:</b> ' + clienteCelular + '</div>');
            if (clienteDireccion && clienteDireccion !== 'null') mywindow.document.write('<div><b>Dirección:</b> ' + clienteDireccion + '</div>');
            
            if (prefact.resp['0'].V && prefact.resp['0'].V.placa != null) {
                mywindow.document.write('<div class="separador"></div>');
                mywindow.document.write('<div><b>Placa:</b> ' + prefact.resp['0'].V.placa + '</div>');
                mywindow.document.write('<div><b>Línea:</b> ' + prefact.resp['0'].V.linea + '</div>');
            }
            
            mywindow.document.write('<table><thead>');
            mywindow.document.write('<tr><th style="width:10%;">CANT</th><th style="width:38%;">DESCRIPCIÓN</th><th class="text-right" style="width:26%;">VLR. UNIT</th><th class="text-right" style="width:26%;">TOTAL</th></tr>');
            mywindow.document.write('</thead><tbody>');

            var ttalDcto = 0;
            var ttalBases = 0;
            
            if (prefact.resp.length > 0) {
                $.each(prefact.resp, function(k, val) {
                    var cant = parseFloat(val.PFD.cantidad) || 0;
                    var valBase = parseFloat(val.PFD.costoventa) || 0;
                    var pctDcto = parseFloat(val.PFD.porcentaje) || 0;
                    
                    var subtotalFila = valBase * cant;
                    var valDcto = subtotalFila * (pctDcto / 100);
                    
                    ttalBases += subtotalFila;
                    ttalDcto += valDcto;
            
                    var descTxt = pctDcto > 0 ? ' (' + pctDcto + '% Dcto)' : '';

                    mywindow.document.write('<tr>');
                    mywindow.document.write('<td>' + cant + '</td>');
                    mywindow.document.write('<td>' + val.P.descripcion + descTxt + '</td>');
                    mywindow.document.write('<td class="text-right">' + valBase.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>');
                    mywindow.document.write('<td class="text-right">' + subtotalFila.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>');
                    mywindow.document.write('</tr>');
                });
            }
            mywindow.document.write('</tbody></table>');

            mywindow.document.write('<div class="separador"></div>');
            
            var vrAbonos = $('.ttalAbonos').val() != "" ? parseFloat($('.ttalAbonos').val()) : 0;
            var ttalFinal = ttalBases - ttalDcto - vrAbonos;

            mywindow.document.write('<table style="margin-top:0px; width:100%;">');
            mywindow.document.write('<tr><td class="text-right" style="width:60%;"><b>Subtotal:</b></td><td class="text-right" style="width:40%;">' + ttalBases.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>');
            mywindow.document.write('<tr><td class="text-right"><b>Dcto:</b></td><td class="text-right">' + ttalDcto.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>');
            
            if (vrAbonos > 0) {
                mywindow.document.write('<tr><td class="text-right"><b>Abonos:</b></td><td class="text-right">' + vrAbonos.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>');
            }
            
            mywindow.document.write('<tr><td class="text-right" style="font-size:12px; border-top:1px solid #000; padding-top:4px;"><b>TOTAL:</b></td><td class="text-right" style="font-size:12px; border-top:1px solid #000; padding-top:4px;"><b>' + ttalFinal.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</b></td></tr>');
            mywindow.document.write('</table>');
            
            var nota = prefact.resp['0'].Prefactura && prefact.resp['0'].Prefactura.observacion ? prefact.resp['0'].Prefactura.observacion : "";
            if (nota != "" && nota !== 'null') {
                mywindow.document.write('<div style="margin-top:10px; font-size:10px;"><b>Nota:</b> ' + nota + '</div>');
            }
            
            mywindow.document.write('</div>');
            mywindow.document.write('</body></html>');
            mywindow.document.close();
            
            mywindow.focus();
            mywindow.print();
            mywindow.close();
            location.reload();
        }
    });
};