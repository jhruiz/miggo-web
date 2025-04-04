/**
 * Imprimir la prefactura
 * @returns {undefined}
 */
var imprimir = function() {
    if ($('#esfactura').prop("checked") == true) {
        imprimirPrefacturaFactura();
    } else {
        imprimirPrefacturaDocumentoEquivalente();
    }

};

var imprimirPrefacturaFactura = function() {
    var prefactId = $('#prefactId').val();
    $.ajax({
        url: $('#url-proyecto').val() + 'prefacturas/ajaxObtenerDetallesPrefactura',
        data: { prefactId: prefactId },
        type: "POST",
        success: function(data) {
            var prefact = JSON.parse(data);

            //se obtiene la fecha actual
            var fechaActual = obtenerFechaActual();

            var mywindow = window.open('', 'PRINT', 'height=400,width=600');
            mywindow.document.write('<html><head>');
            mywindow.document.write('<style>');
            mywindow.document.write('@page { size: auto; margin: 0; }'); // Eliminar márgenes de la página
            mywindow.document.write('body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }'); // Tamaño de fuente base
            mywindow.document.write('table { border-collapse: collapse; width: 100%; margin-bottom: 20px; font-size: 12px; }'); // Estilo de la tabla
            mywindow.document.write('th { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }'); // Estilo de las celdas de encabezado
            mywindow.document.write('td { border: 1px solid #ddd; padding: 8px; font-size: 12px; }'); // Estilo de las celdas
            mywindow.document.write('th { background-color: #f5f5f5; font-weight: bold; }'); // Fondo del encabezado
            mywindow.document.write('tr:nth-child(even) { background-color: #f9f9f9; }'); // Fondo alterno para filas
            mywindow.document.write('.align-left { text-align: left; }'); // Clase para alinear a la izquierda
            mywindow.document.write('.align-right { text-align: right; }'); // Clase para alinear a la derecha
            mywindow.document.write('@media print {');
            mywindow.document.write('  @page { margin: 0; }'); // Eliminar márgenes en la impresión
            mywindow.document.write('  body { margin: 1cm; }'); // Ajustar márgenes del contenido
            mywindow.document.write('  .no-print, .no-print * { display: none !important; }'); // Ocultar elementos no deseados
            mywindow.document.write('}');
            mywindow.document.write('</style>');
            mywindow.document.write('</head>');
            mywindow.document.write('<body>');
            mywindow.document.write('<div style="margin:0px; width:100%; font-family:sans-serif; font-size:15px;">');
            mywindow.document.write($('#dv_img_emp').html());
            mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
            mywindow.document.write('<b>' + prefact.resp['0'].EM.nombre + '</b></div>');
            mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
            mywindow.document.write('<b>PREFACTURA No. ' + zfill(prefactId, 6) + ' </b></div>');
            
            // Información de la empresa
            mywindow.document.write('<div><b>Nit:</b>' + prefact.resp['0'].EM.nit + '</div>');
            mywindow.document.write('<div><b>Teléfono:</b>' + prefact.resp['0'].EM.telefono1 + '</div>');
            mywindow.document.write('<div><b>Dirección:</b>' + prefact.resp['0'].EM.direccion + '</div>');
            
            // Información de la fecha y legal
            mywindow.document.write('<div style="width:100%; float:left; margin-top:20px; margin-bottom:5px";>');
            mywindow.document.write('<div>' + prefact.resp['0'].CIU.descripcion + ', ');
            mywindow.document.write(prefact.resp['0'].PAI.descripcion + ', ');
            mywindow.document.write(fechaActual + '</div>');
            mywindow.document.write('<div>' + prefact.resp['0'].EM.texto1 + '</div>');
            
            // Información del cliente
            if ($('#PrefacturaDatoscliente').val() != "") {
                mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Cliente: </b>' + prefact.resp['0'].C.nombre + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Teléfono: </b>' + prefact.resp['0'].C.celular + '</div></div></div>');
                mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Identificación: </b>' + prefact.resp['0'].C.nit + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Dirección: </b>' + prefact.resp['0'].C.direccion + '</div></div></div></div>');
            } else if ($('#PrefacturaNuevonombre').val() != "") {
                mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Cliente: </b>' + $('#PrefacturaNuevonombre').val() + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Teléfono: </b>' + $('#PrefacturaNuevocelular').val() + '</div></div></div>');
                mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Identificación: </b>' + $('#PrefacturaNuevonit').val() + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Dirección: </b>' + $('#PrefacturaNuevodireccion').val() + '</div></div></div></div>');
            } else {
                mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Cliente: </b>' + $('#PrefacturaRapidanombre').val() + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Teléfono: </b>' + $('#PrefacturaRapidatelefono').val() + '</div></div></div>');
                mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Identificación: </b>' + $('#PrefacturaRapidanit').val() + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Dirección: </b>' + $('#PrefacturaRapidadireccion').val() + '</div></div></div></div>');
            }
            
            if (prefact.resp['0'].V.placa != null) {
                mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Placa: </b>' + prefact.resp['0'].V.placa + '</div></div></div>');
                mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Linea: </b>' + prefact.resp['0'].V.linea + '</div></div></div></div>');
            }
            
            mywindow.document.write('<div style="margin-top: 30px; float: left; width: 100%; aling-items: center; justify-content: center">');
            mywindow.document.write('<table style="font-family:sans-serif; font-size:15px; border-collapse: collapse; width: 100%;"><thead>');
            mywindow.document.write('<tr><th class="align-left">Cant.</th><th class="align-left">Descripción</th>');
            mywindow.document.write('<th class="align-right">Vlr. Unit</th><th class="align-right">%Dcto</th><th class="align-right">Subtotal</th></tr>');
            mywindow.document.write('</thead>');
            if (prefact.resp.length > 0) {
                mywindow.document.write('<tbody>');
            
                var ttalImp = 0;
                var ttalDcto = 0;
                var ttalBases = 0;
            
                $.each(prefact.resp, function(k, val) {
                    var valBase = 0;
                    var valImp = 0;
                    var marcImp = "";
                    var valDcto = 0;
                    var baseSubtotal = 0;
                    // Se obtiene el valor base y el impuesto
                    if (val.PFD.impuesto != null && val.PFD.impuesto != '0') {
                        valBase = Math.ceil((val.PFD.costoventa) / (parseFloat((val.PFD.impuesto / 100) + 1)));
                        valDcto = val.PFD.descuento;
                        valImp = val.PFD.impuesto / 100;
                        baseSubtotal = Math.ceil((valBase * val.PFD.cantidad) - valDcto);
                        ttalImp += Math.ceil(baseSubtotal * valImp);
                        marcImp = "*";
                    } else {
                        valBase = parseInt(val.PFD.costoventa);
                    }
            
                    // Se obtiene el total del descuento
                    ttalDcto += parseFloat(val.PFD.descuento);
            
                    // Se obtienen los totales del valor base del producto
                    ttalBases += valBase * parseInt(val.PFD.cantidad);
            
                    mywindow.document.write('<tr>');
                    mywindow.document.write('<td class="align-left">' + val.PFD.cantidad + '</td>');
                    mywindow.document.write('<td class="align-left">' + val.P.descripcion + marcImp + '</td>');
                    mywindow.document.write('<td class="align-right">' + (valBase).toLocaleString() + '</td>');
                    mywindow.document.write('<td class="align-right">' + val.PFD.porcentaje + '%</td>');
                    mywindow.document.write('<td class="align-right">' + (valBase * parseInt(val.PFD.cantidad)).toLocaleString() + '</td>');
                    mywindow.document.write('</tr>');
                });
                mywindow.document.write('</tbody>');
            }
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="3"></td><td class="align-right"><b>Subtotal</b></td>');
            mywindow.document.write('<td class="align-right">' + (ttalBases).toLocaleString() + '</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="3"></td><td class="align-right"><b>Subtotal con Dcto.</b></td>');
            mywindow.document.write('<td class="align-right">' + (ttalBases - ttalDcto).toLocaleString() + '</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="3"></td><td class="align-right"><b>IVA.</b></td>');
            mywindow.document.write('<td class="align-right">' + (ttalImp).toLocaleString() + '</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="3"></td><td class="align-right"><b>Reteica</b></td>');
            mywindow.document.write('<td class="align-right">0%</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="3"></td><td class="align-right"><b>Retefuente</b></td>');
            mywindow.document.write('<td class="align-right">0%</td>');
            mywindow.document.write('</tr>');
            
            if ($('.ttalAbonos').val() != "" && $('.ttalAbonos').val() > 0) {
                mywindow.document.write('<tr>');
                mywindow.document.write('<td colspan="3"></td><td class="align-right"><b>Abonos</b></td>');
                mywindow.document.write('<td class="align-right">' + (parseInt($('.ttalAbonos').val())).toLocaleString() + '</td>');
                mywindow.document.write('</tr>');
            }
            
            var ttalFinal = ttalBases - ttalDcto + ttalImp - parseInt($('.ttalAbonos').val());
            
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="3"></td><td class="align-right"><b>TOTAL</b></td>');
            mywindow.document.write('<td class="align-right">' + (ttalFinal).toLocaleString() + '</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('</table></div>');
            
            // EMISOR Y RECEPTOR
            mywindow.document.write('<div style="margin-top:20px; float:left;">');
            mywindow.document.write("<b>EMISOR: </b>" + prefact.resp['0'].EM.nombre + "<br>");
            mywindow.document.write("________________________________<br>");
            mywindow.document.write("<b>Nit: </b>" + prefact.resp['0'].EM.nit);
            mywindow.document.write('</div>');
            mywindow.document.write('<div style="margin-top:20px; float:right;">');
            mywindow.document.write("<b>CLIENTE: </b>" + prefact.resp['0'].C.nombre + "<br>");
            mywindow.document.write("________________________________<br>");
            mywindow.document.write("C.C/NIT: " + prefact.resp['0'].C.nit);
            mywindow.document.write('</div>');
            
            // OBSERVACIÓN
            var nota = prefact.resp['0'].Prefactura.observacion != null && prefact.resp['0'].Prefactura.observacion != "" ?
                prefact.resp['0'].Prefactura.observacion : "";
            mywindow.document.write('<div style="float:left; margin-top:10px; width:100%" align="left">');
            mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;">');
            mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
            mywindow.document.write('<b>Nota: </b>' + nota + '</div></div></div>');
            
            mywindow.document.write('</div>');
            mywindow.document.write('</body></html>');
            mywindow.document.title = prefact.resp['0'].C.nombre + " - PREFACTURA";
            mywindow.document.close();
            mywindow.focus();
            mywindow.print();
            mywindow.close();
        }
    });
};


var imprimirPrefacturaDocumentoEquivalente = function() {
    var prefactId = $('#prefactId').val();
    $.ajax({
        url: $('#url-proyecto').val() + 'prefacturas/ajaxObtenerDetallesPrefactura',
        data: { prefactId: prefactId },
        type: "POST",
        success: function(data) {
            var prefact = JSON.parse(data);

            //se obtiene la fecha actual
            var fechaActual = obtenerFechaActual();

            var mywindow = window.open('', 'PRINT', 'height=400,width=600');
            mywindow.document.write('<html><head>');
            mywindow.document.write('<style>');
            mywindow.document.write('@page { size: auto; margin: 0; }'); // Eliminar márgenes de la página
            mywindow.document.write('body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }'); // Tamaño de fuente base
            mywindow.document.write('table { border-collapse: collapse; width: 100%; margin-bottom: 20px; font-size: 12px; }'); // Estilo de la tabla
            mywindow.document.write('th { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }'); // Estilo de las celdas de encabezado
            mywindow.document.write('td { border: 1px solid #ddd; padding: 8px; font-size: 12px; }'); // Estilo de las celdas
            mywindow.document.write('th { background-color: #f5f5f5; font-weight: bold; }'); // Fondo del encabezado
            mywindow.document.write('tr:nth-child(even) { background-color: #f9f9f9; }'); // Fondo alterno para filas
            mywindow.document.write('.align-left { text-align: left; }'); // Clase para alinear a la izquierda
            mywindow.document.write('.align-right { text-align: right; }'); // Clase para alinear a la derecha
            mywindow.document.write('@media print {');
            mywindow.document.write('  @page { margin: 0; }'); // Eliminar márgenes en la impresión
            mywindow.document.write('  body { margin: 1cm; }'); // Ajustar márgenes del contenido
            mywindow.document.write('  .no-print, .no-print * { display: none !important; }'); // Ocultar elementos no deseados
            mywindow.document.write('}');
            mywindow.document.write('</style>');
            mywindow.document.write('</head>');
            mywindow.document.write('<body>');
            mywindow.document.write('<div style="margin:0px; width:100%; font-family:sans-serif; font-size:15px;">');
            mywindow.document.write($('#dv_img_emp').html());
            mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
            mywindow.document.write('<b>' + prefact.resp['0'].RE.nombre + '</b></div>');
            mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
            mywindow.document.write('<b>' + prefact.resp['0'].RE.representantelegal + '</b></div>');
            mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
            mywindow.document.write('<b>PREFACTURA No. ' + zfill(prefactId, 6) + ' </b></div>');
            
            // Información de la empresa
            mywindow.document.write('<div><b>Nit:</b>' + prefact.resp['0'].RE.nit + '</div>');
            mywindow.document.write('<div><b>Teléfono:</b>' + prefact.resp['0'].RE.telefono1 + '</div>');
            mywindow.document.write('<div><b>Dirección:</b>' + prefact.resp['0'].RE.direccion + '</div>');
            
            // Información de la fecha y legal
            mywindow.document.write('<div style="width:100%; float:left; margin-top:20px; margin-bottom:5px";>');
            mywindow.document.write('<div>' + prefact.resp['0'].CIU.descripcion + ', ');
            mywindow.document.write(prefact.resp['0'].PAI.descripcion + ', ');
            mywindow.document.write(fechaActual + '</div>');
            
            // Información del cliente
            if ($('#PrefacturaDatoscliente').val() != "") {
                mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Cliente: </b>' + prefact.resp['0'].C.nombre + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Teléfono: </b>' + prefact.resp['0'].C.celular + '</div></div></div>');
                mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Identificación: </b>' + prefact.resp['0'].C.nit + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Dirección: </b>' + prefact.resp['0'].C.direccion + '</div></div></div></div>');
            } else if ($('#PrefacturaNuevonombre').val() != "") {
                mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Cliente: </b>' + $('#PrefacturaNuevonombre').val() + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Teléfono: </b>' + $('#PrefacturaNuevocelular').val() + '</div></div></div>');
                mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Identificación: </b>' + $('#PrefacturaNuevonit').val() + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Dirección: </b>' + $('#PrefacturaNuevodireccion').val() + '</div></div></div></div>');
            } else {
                mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Cliente: </b>' + $('#PrefacturaRapidanombre').val() + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Teléfono: </b>' + $('#PrefacturaRapidatelefono').val() + '</div></div></div>');
                mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Identificación: </b>' + $('#PrefacturaRapidanit').val() + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Dirección: </b>' + $('#PrefacturaRapidadireccion').val() + '</div></div></div></div>');
            }
            
            if (prefact.resp['0'].V.placa != null) {
                mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Placa: </b>' + prefact.resp['0'].V.placa + '</div></div></div>');
                mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Linea: </b>' + prefact.resp['0'].V.linea + '</div></div></div></div>');
            }
            
            mywindow.document.write('<div style="margin-top: 30px; float: left; width: 100%; aling-items: center; justify-content: center">');
            mywindow.document.write('<table style="font-family:sans-serif; font-size:15px; border-collapse: collapse; width: 100%;"><thead>');
            mywindow.document.write('<tr><th class="align-left">Cant.</th><th class="align-left">Descripción</th>');
            mywindow.document.write('<th class="align-right">Vlr. Unit</th><th class="align-right">%Dcto</th><th class="align-right">Subtotal</th></tr>');
            mywindow.document.write('</thead>');
            if (prefact.resp.length > 0) {
                mywindow.document.write('<tbody>');
            
                var ttalDcto = 0;
                var ttalBases = 0;
            
                $.each(prefact.resp, function(k, val) {
                    var valBase = 0;
            
                    // Valor base del producto
                    valBase = parseInt(val.PFD.costoventa);
            
                    // Se obtiene el total del descuento
                    ttalDcto += parseFloat((val.PFD.costoventa * val.PFD.cantidad) * (val.PFD.porcentaje / 100));
            
                    // Se obtienen los totales del valor base del producto
                    ttalBases += valBase * parseInt(val.PFD.cantidad);
            
                    mywindow.document.write('<tr>');
                    mywindow.document.write('<td class="align-left">' + val.PFD.cantidad + '</td>');
                    mywindow.document.write('<td class="align-left">' + val.P.descripcion + '</td>');
                    mywindow.document.write('<td class="align-right">' + (valBase).toLocaleString() + '</td>');
                    mywindow.document.write('<td class="align-right">' + val.PFD.porcentaje + '%</td>');
                    mywindow.document.write('<td class="align-right">' + (valBase * parseInt(val.PFD.cantidad)).toLocaleString() + '</td>');
                    mywindow.document.write('</tr>');
                });
                mywindow.document.write('</tbody>');
            }
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="3"></td><td class="align-right"><b>Subtotal</b></td>');
            mywindow.document.write('<td class="align-right">' + (ttalBases).toLocaleString() + '</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="3"></td><td class="align-right"><b>Dcto.</b></td>');
            mywindow.document.write('<td class="align-right">' + (ttalDcto).toLocaleString() + '</td>');
            mywindow.document.write('</tr>');
            
            if ($('.ttalAbonos').val() != "" && $('.ttalAbonos').val() > 0) {
                mywindow.document.write('<tr>');
                mywindow.document.write('<td colspan="3"></td><td class="align-right"><b>Abonos</b></td>');
                mywindow.document.write('<td class="align-right">' + (parseInt($('.ttalAbonos').val())).toLocaleString() + '</td>');
                mywindow.document.write('</tr>');
            }
            
            var ttalFinal = ttalBases - ttalDcto - parseInt($('.ttalAbonos').val());
            
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="3"></td><td class="align-right"><b>TOTAL</b></td>');
            mywindow.document.write('<td class="align-right">' + (ttalFinal).toLocaleString() + '</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('</table></div>');
            
            // EMISOR Y RECEPTOR
            mywindow.document.write('<div style="margin-top:20px; float:left;">');
            mywindow.document.write("<b>EMISOR: </b>" + prefact.resp['0'].RE.representantelegal + "<br>");
            mywindow.document.write("________________________________<br>");
            mywindow.document.write("<b>Nit: </b>" + prefact.resp['0'].RE.nit);
            mywindow.document.write('</div>');
            mywindow.document.write('<div style="margin-top:20px; float:right;">');
            mywindow.document.write("<b>CLIENTE: </b>" + prefact.resp['0'].C.nombre + "<br>");
            mywindow.document.write("________________________________<br>");
            mywindow.document.write("C.C/NIT: " + prefact.resp['0'].C.nit);
            mywindow.document.write('</div>');
            
            // OBSERVACIÓN
            var nota = prefact.resp['0'].Prefactura.observacion != null && prefact.resp['0'].Prefactura.observacion != "" ?
                prefact.resp['0'].Prefactura.observacion : "";
            mywindow.document.write('<div style="float:left; margin-top:10px; width:100%" align="left">');
            mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;">');
            mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
            mywindow.document.write('<b>Nota: </b>' + nota + '</div></div></div>');
            
            mywindow.document.write('</div>');
            mywindow.document.write('</body></html>');
            mywindow.document.title = prefact.resp['0'].C.nombre + " - PREFACTURA";
            mywindow.document.close();
            mywindow.focus();
            mywindow.print();
            mywindow.close();
        }
    });
};

var obtenerFechaActual = function() {
    var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    var f = new Date();
    return (meses[f.getMonth()] + " " + f.getDate() + ", " + f.getFullYear());
};


var enviarPrefactura = function() {

    var cliente = $('#FacturaIdcliente').val();

    if (cliente != '') {
        $.ajax({
            url: $('#url-proyecto').val() + 'clientes/ajaxObtenerInfoCliente',
            data: { clienteId: cliente },
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if (typeof resp.resp.Cliente != 'undefined') {
                    if (resp.resp.Cliente.celular != "") {
                        var link = "https://wa.me/57" + resp.resp.Cliente.celular + "?text=adjuntamos%20información%20de%20su%20interés";
                        window.open(link, '_blank');
                    } else {
                        bootbox.alert('El cliente no tiene número de teléfono celular para el envío del mensaje.');
                    }
                } else {
                    alert('No fue posible obtener la información del cliente. Por favor, inténtelo nuevamente.');
                }
            }
        });
    } else {
        bootbox.alert('Debe seleccionar un cliente.');
    }
};

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

$(function() {
    $("#imprimirCot").click(imprimir);
    $(".wppSendPF").click(enviarPrefactura);
    $("#dv_emp").hide();
});