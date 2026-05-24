/**
 * Imprimir la prefactura
 * @returns {undefined}
 */

const formato = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
});

var imprimir = function() {

    if ($('#esFacturaDV').val() == '1') {
        imprimirFactura();
    } else {
        imprimirDocumentoEquivalente();
    }

};

var imprimirFactura = function() {
    var prefactId = $('#prefacturaId').val();
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
            mywindow.document.write('table { border-collapse: collapse; width: 100%; margin-bottom: 20px; font-size: 11px; }'); // Estilo de la tabla
            mywindow.document.write('th { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 10px; }'); // Estilo de las celdas de encabezado
            mywindow.document.write('td { border: 1px solid #ddd; padding: 8px; font-size: 10px; }'); // Estilo de las celdas
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
            mywindow.document.write('<div>' + prefact.resp['0'].CIU.nombre + ' (' + prefact.resp['0'].DPTO.descripcion + ')' + ' - ');
            mywindow.document.write(prefact.resp['0'].PAI.nombre + ', ');
            mywindow.document.write(fechaActual + '</div>');
            mywindow.document.write('<div>' + prefact.resp['0'].EM.texto1 + '</div>');
            
            // Información del cliente
            if ($('#PrefacturaDatoscliente').val() != "") {
                var celular  = prefact.resp['0'].C.celular == null ? '' : prefact.resp['0'].C.celular;
                var direccion  = prefact.resp['0'].C.direccion == null ? '' : prefact.resp['0'].C.direccion;
                mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Cliente: </b>' + (prefact.resp['0'].C.nombre ?? 'Consumidor Final') + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Teléfono: </b>' + celular + '</div></div>');

                // --- AGREGAR EMAIL AQUÍ ---
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Email: </b>' + (prefact.resp['0'].C.email ?? 'N/A') + '</div></div></div>'); 
                // --------------------------

                mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Identificación: </b>' + (prefact.resp['0'].C.nit ?? '222222222') + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Dirección: </b>' + direccion + '</div></div></div></div>');
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
            mywindow.document.write('<table style="font-family:sans-serif; border-collapse: collapse; width: 100%;"><thead>');
            mywindow.document.write('<tr><th class="align-left">#</th>');
            mywindow.document.write('<th class="align-left">Nombre</th>');
            mywindow.document.write('<th class="align-left">Código</th>');
            mywindow.document.write('<th class="align-left">Cant.</th>');
            mywindow.document.write('<th class="align-left">Precio unitario</th>');
            mywindow.document.write('<th class="align-left">Descuento</th>');
            mywindow.document.write('<th class="align-left">IVA</th>');
            mywindow.document.write('<th class="align-left">INC</th>');
            mywindow.document.write('<th class="align-left">INC bolsa</th>');
            mywindow.document.write('<th class="align-left">Total línea</th>');
            mywindow.document.write('</tr></thead>');

            if (prefact.resp.length > 0) {
                mywindow.document.write('<tbody>');
            
                var subtotal = 0;
                var descuento = 0;
                var IVA = 0;
                var INC = 0;
                var INCBolsa = 0;
                var ttalImp = 0;
                var contador = 1;
                var ttalFinal = 0;
                var ttalAbonos = 0;
            
                $.each(prefact.resp, function(k, val) {

                    // Usamos 'const' para asegurar que estos valores base no muten accidentalmente.
                    const cantidad = parseFloat(val.PFD.cantidad) || 0;
                    const costoVenta = parseFloat(val.PFD.costoventa) || 0;
                    const porcentajeDesc = parseFloat(val.PFD.porcentaje) || 0;
                    const tasaIVA = parseFloat(val.PFD.impuesto) || 0;
                    const tasaINC = parseFloat(val.PFD.impoconsumo) || 0;
                    const valBolsa = parseFloat(val.PFD.incbolsa) || 0;

                    // Objeto limpio para pasar a tu función helper
                    const objDatosProd = {
                        unidadesProd: cantidad,
                        precioVenta: costoVenta,
                        porcentajeDesc: porcentajeDesc,
                        prcIVA: tasaIVA / 100,
                        prcINC: tasaINC / 100,
                        valBolsa: valBolsa
                    };

                    // 2. CÁLCULOS
                    const res = obtenerValorBaseProducto(objDatosProd); // Renombré objResultados a 'res' por brevedad

                    // Parseamos los resultados por si tu función devuelve strings
                    const valBaseUnitario = parseFloat(res.valorBaseUnitario) || 0;
                    const valDescuento = parseFloat(res.descuento) || 0;
                    const valIVA = parseFloat(res.valorIVA) || 0;
                    const valINC = parseFloat(res.valorINC) || 0;
                    const valBolsaTotal = parseFloat(res.varorINCBolsa) || 0;

                    // Lógica de Negocio:
                    // Precio Unitario (Aparentemente base + descuento prorrateado)
                    const precioUnitario = (valBaseUnitario + valDescuento) / cantidad;

                    // Calculo del valor bolsa unitario para visualización
                    const bolsaUnitario = cantidad > 0 ? (valBolsaTotal / cantidad) : 0;

                    // Total Línea: (Unitario * Cantidad) - Descuento + Impuestos
                    const totalLinea = (precioUnitario * cantidad) - valDescuento + valIVA + valINC;

                    const nombreComplemento = val.PFD.complementonombre != null ? val.PFD.complementonombre : '';    

                    mywindow.document.write('<tr>');
                    mywindow.document.write('<td class="align-left">' + contador + '</td>');
                    mywindow.document.write('<td class="align-left">' + val.P.descripcion + ' ' + nombreComplemento + '</td>');
                    mywindow.document.write('<td class="align-left">' + val.P.codigo + '</td>');
                    mywindow.document.write('<td class="align-right">' + (cantidad) + '</td>');
                    mywindow.document.write('<td class="align-right">' + formato.format(precioUnitario) + '</td>');
                    mywindow.document.write('<td class="align-right">' + formato.format(valDescuento) + '</td>');
                    mywindow.document.write('<td class="align-right">' + formato.format(valIVA) + ' (' + formato.format(tasaIVA) + '%)</td>');
                    mywindow.document.write('<td class="align-right">' + formato.format(valINC) + ' (' + formato.format(tasaINC) + '%)</td>');
                    mywindow.document.write('<td class="align-right">' + formato.format(bolsaUnitario) + '</td>');
                    mywindow.document.write('<td class="align-right">' + formato.format(totalLinea) + '</td>');
                    mywindow.document.write('</tr>');

                    contador++;
                    subtotal += precioUnitario * cantidad;
                    descuento += valDescuento;
                    IVA += valIVA;
                    INC += valINC;
                    INCBolsa += (bolsaUnitario * cantidad);
                    ttalImp += valIVA + valINC + (bolsaUnitario * cantidad);

                });
                mywindow.document.write('</tbody>');
            }
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="7"></td><td colspan="2" class="align-right"><b>Subtotal</b></td>');
            mywindow.document.write('<td class="align-right">' + formato.format(subtotal) + '</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="7"></td><td colspan="2" class="align-right">Descuento</td>');
            mywindow.document.write('<td class="align-right">' + formato.format(descuento) + '</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="7"></td><td colspan="2" class="align-right"><b>Total Bruto Factura</b></td>');
            mywindow.document.write('<td class="align-right">' + formato.format(subtotal - descuento) + '</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="7"></td><td colspan="2" class="align-right">IVA</td>');
            mywindow.document.write('<td class="align-right">' + formato.format(IVA) + '</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="7"></td><td colspan="2" class="align-right">INC</td>');
            mywindow.document.write('<td class="align-right">' + formato.format(INC) + '</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="7"></td><td colspan="2" class="align-right">Bolsas</td>');
            mywindow.document.write('<td class="align-right">' + formato.format(INCBolsa) + '</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="7"></td><td colspan="2" class="align-right"><b>Total impuestos</b></td>');
            mywindow.document.write('<td class="align-right">' + formato.format(ttalImp)  + '</td>');
            mywindow.document.write('</tr>');

            ttalFinal = subtotal - descuento + ttalImp;
            
            if ($('.ttalAbonos').val() != "" && $('.ttalAbonos').val() > 0) {
                ttalAbonos = parseFloat($('.ttalAbonos').val());

                mywindow.document.write('<tr>');
                mywindow.document.write('<td colspan="7"></td><td colspan="2" class="align-right"><b>Total neto factura</b></td>');
                mywindow.document.write('<td class="align-right">' + formato.format(ttalFinal) + '</td>');
                mywindow.document.write('</tr>');

                mywindow.document.write('<tr>');
                mywindow.document.write('<td colspan="7"></td><td colspan="2" class="align-right"><b>Abonos</b></td>');
                mywindow.document.write('<td class="align-right">' + formato.format(parseFloat(ttalAbonos)) + '</td>');
                mywindow.document.write('</tr>');
            }

            ttalFinal = parseFloat(ttalFinal) - parseFloat(ttalAbonos);
            
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="7"></td><td colspan="2" class="align-right"><b>Total factura</b></td>');
            mywindow.document.write('<td class="align-right">' + formato.format(ttalFinal) + '</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('</table></div>');
            
            // EMISOR Y RECEPTOR
            mywindow.document.write('<div style="margin-top:20px; float:left;">');
            mywindow.document.write("<b>EMISOR: </b>" + prefact.resp['0'].EM.nombre + "<br>");
            mywindow.document.write("________________________________<br>");
            mywindow.document.write("<b>Nit: </b>" + prefact.resp['0'].EM.nit);
            mywindow.document.write('</div>');
            mywindow.document.write('<div style="margin-top:20px; float:right;">');
            mywindow.document.write("<b>CLIENTE: </b>" + (prefact.resp['0'].C.nombre ?? 'Consumidor Final') + "<br>");
            mywindow.document.write("________________________________<br>");
            mywindow.document.write("C.C/NIT: " + (prefact.resp['0'].C.nit ?? '222222222'));
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


var imprimirDocumentoEquivalente = function() {
    var prefactId = $('#prefacturaId').val();
    
    $.ajax({
        url: $('#url-proyecto').val() + 'prefacturas/ajaxObtenerDetallesPrefactura',
        data: { prefactId: prefactId },
        type: "POST",
        success: function(data) {
            var prefact = JSON.parse(data);
            var fechaActual = obtenerFechaActual();

            var mywindow = window.open('', 'PRINT', 'height=400,width=600');
            
            mywindow.document.write('<html><head>');
            
            // --------------------------------------------------------------------------
            // 1. ESTILOS (Idénticos a imprimirFactura para homologación visual)
            // --------------------------------------------------------------------------
            mywindow.document.write('<style>');
            mywindow.document.write('@page { size: auto; margin: 0; }');
            mywindow.document.write('body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }'); 
            mywindow.document.write('table { border-collapse: collapse; width: 100%; margin-bottom: 20px; font-size: 11px; }');
            mywindow.document.write('th { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 10px; background-color: #f5f5f5; font-weight: bold; }');
            mywindow.document.write('td { border: 1px solid #ddd; padding: 8px; font-size: 10px; }');
            mywindow.document.write('tr:nth-child(even) { background-color: #f9f9f9; }');
            mywindow.document.write('.align-left { text-align: left; }');
            mywindow.document.write('.align-right { text-align: right; }');
            mywindow.document.write('@media print {');
            mywindow.document.write('  @page { margin: 0; }');
            mywindow.document.write('  body { margin: 1cm; }');
            mywindow.document.write('  .no-print, .no-print * { display: none !important; }');
            mywindow.document.write('}');
            mywindow.document.write('</style>');
            mywindow.document.write('</head>');
            
            mywindow.document.write('<body>');
            mywindow.document.write('<div style="margin:0px; width:100%; font-family:sans-serif; font-size:15px;">');
            
            // LOGO Y CABECERA
            mywindow.document.write($('#dv_img_emp').html());
            mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
            mywindow.document.write('<b>' + prefact.resp['0'].RE.nombre + '</b></div>'); // Usamos RE en lugar de EM
            mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
            mywindow.document.write('<b>' + prefact.resp['0'].RE.representantelegal + '</b></div>');
            mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
            mywindow.document.write('<b>PREFACTURA No. ' + zfill(prefactId, 6) + ' </b></div>');

            // DATOS EMPRESA
            mywindow.document.write('<div><b>Nit:</b>' + prefact.resp['0'].RE.nit + '</div>');
            mywindow.document.write('<div><b>Teléfono:</b>' + prefact.resp['0'].RE.telefono1 + '</div>');
            mywindow.document.write('<div><b>Dirección:</b>' + prefact.resp['0'].RE.direccion + '</div>');

            // FECHA Y LUGAR
            mywindow.document.write('<div style="width:100%; float:left; margin-top:20px; margin-bottom:5px";>');
            mywindow.document.write('<div>' + prefact.resp['0'].CIU.nombre + ' (' + prefact.resp['0'].DPTO.descripcion + ')' + ' - ');
            mywindow.document.write(prefact.resp['0'].PAI.nombre + ', ');
            mywindow.document.write(fechaActual + '</div>');
            
            // DATOS DEL CLIENTE (Lógica original conservada, estilos mejorados)
            if ($('#PrefacturaDatoscliente').val() != "") {
                var clienteNombre = prefact.resp['0'].C.nombre == null ? 'ANONIMO' : prefact.resp['0'].C.nombre;
                var clienteNit = prefact.resp['0'].C.nit == null ? '' : prefact.resp['0'].C.nit;
                var clienteCelular = prefact.resp['0'].C.celular == null ? '' : prefact.resp['0'].C.celular;
                var clienteDireccion = prefact.resp['0'].C.direccion == null ? '' : prefact.resp['0'].C.direccion;
                
                mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Cliente: </b>' + clienteNombre + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Teléfono: </b>' + clienteCelular + '</div></div></div>');
                mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Identificación: </b>' + clienteNit + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Dirección: </b>' + clienteDireccion + '</div></div></div></div>');
            } else if ($('#PrefacturaNuevonombre').val() != "") {
                // ... Bloque Cliente Nuevo ...
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
                // ... Bloque Cliente Rápido ...
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

            // DATOS VEHICULO (Si aplica)
            if (prefact.resp['0'].V.placa != null) {
                mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Placa: </b>' + prefact.resp['0'].V.placa + '</div></div></div>');
                mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Linea: </b>' + prefact.resp['0'].V.linea + '</div></div></div></div>');
            }

            // --------------------------------------------------------------------------
            // 2. TABLA DE PRODUCTOS (Estilizada y Simplificada)
            // --------------------------------------------------------------------------
            mywindow.document.write('<div style="margin-top: 30px; float: left; width: 100%; aling-items: center; justify-content: center">');
            mywindow.document.write('<table style="font-family:sans-serif; border-collapse: collapse; width: 100%;"><thead>');
            
            // Cabeceras simplificadas (Solo 5 columnas)
            mywindow.document.write('<tr><th class="align-left">#</th>');
            mywindow.document.write('<th class="align-left">Nombre</th>');
            mywindow.document.write('<th class="align-left">Código</th>');
            mywindow.document.write('<th class="align-left">Cant.</th>');
            mywindow.document.write('<th class="align-right">Precio unitario</th>');
            mywindow.document.write('<th class="align-right">Descuento</th>');
            mywindow.document.write('<th class="align-right">Total línea</th>');
            mywindow.document.write('</tr></thead>');

            if (prefact.resp.length > 0) {
                mywindow.document.write('<tbody>');

                var ttalBases = 0;
                var contador = 1;
                var ttalDcto = 0;

                $.each(prefact.resp, function(k, val) {
                    var valBase = parseFloat(val.PFD.costoventa);
                    var cantidad = parseFloat(val.PFD.cantidad);
                    var pctDesc = parseFloat(val.PFD.porcentaje);
                    var valDcto = 0;

                    // Cálculos originales
                    valDcto = (valBase * cantidad) * (pctDesc / 100);
                    ttalDcto += (valBase * cantidad) * (pctDesc / 100);
                    ttalBases += valBase * cantidad;

                    const nombreComplemento = val.PFD.complementonombre != null ? val.PFD.complementonombre : ''; 

                    mywindow.document.write('<tr>');
                    mywindow.document.write('<td class="align-left">' + contador + '</td>');
                    mywindow.document.write('<td class="align-left">' + val.P.descripcion + ' ' + nombreComplemento + '</td>');
                    mywindow.document.write('<td class="align-left">' + val.P.codigo  + '</td>');
                    mywindow.document.write('<td class="align-left">' + formato.format(cantidad) + '</td>');
                    mywindow.document.write('<td class="align-right">' + formato.format(valBase) + '</td>');
                    mywindow.document.write('<td class="align-right">' + formato.format(valDcto) + '</td>');
                    mywindow.document.write('<td class="align-right">' + formato.format((valBase * cantidad) - valDcto) + '</td>');
                    mywindow.document.write('</tr>');

                    contador++;
                });
                mywindow.document.write('</tbody>');
            }

            // --------------------------------------------------------------------------
            // 3. PIE DE PÁGINA (Totales con el formato nuevo)
            // --------------------------------------------------------------------------
            
            // Colspan = 4 (Porque hay 5 columnas en total: Cant, Desc, Unit, %Dcto, Subtotal)
            var colSpan = 6;

            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="'+colSpan+'" class="align-right"><b>Subtotal</b></td>');
            mywindow.document.write('<td class="align-right">' + formato.format(ttalBases) + '</td>');
            mywindow.document.write('</tr>');
            
            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="'+colSpan+'" class="align-right">Descuento</td>');
            mywindow.document.write('<td class="align-right">' + formato.format(ttalDcto) + '</td>');
            mywindow.document.write('</tr>');

            var ttalAbonos = 0;
            if ($('.ttalAbonos').val() != "" && $('.ttalAbonos').val() > 0) {
                ttalAbonos = parseFloat($('.ttalAbonos').val());
                mywindow.document.write('<tr>');
                mywindow.document.write('<td colspan="'+colSpan+'" class="align-right"><b>Abonos</b></td>');
                mywindow.document.write('<td class="align-right">' + formato.format(ttalAbonos) + '</td>');
                mywindow.document.write('</tr>');
            }

            var ttalFinal = ttalBases - ttalDcto - ttalAbonos;

            mywindow.document.write('<tr>');
            mywindow.document.write('<td colspan="'+colSpan+'" class="align-right" style="font-weight: bold; font-size: 14px;">TOTAL</td>');
            mywindow.document.write('<td class="align-right" style="font-weight: bold; font-size: 14px;">' + formato.format(ttalFinal) + '</td>');
            mywindow.document.write('</tr>');
            mywindow.document.write('</table></div>');

            // --------------------------------------------------------------------------
            // 4. FIRMAS Y NOTAS (Estilo Factura)
            // --------------------------------------------------------------------------
            var clienteNombre = prefact.resp['0'].C.nombre == null ? 'ANONIMO' : prefact.resp['0'].C.nombre;
            var clienteNit = prefact.resp['0'].C.nit == null ? '' : prefact.resp['0'].C.nit;

            mywindow.document.write('<div style="margin-top:20px; float:left;">');
            mywindow.document.write("<b>EMISOR: </b>" + prefact.resp['0'].RE.representantelegal + "<br>");
            mywindow.document.write("________________________________<br>");
            mywindow.document.write("<b>Nit: </b>" + prefact.resp['0'].RE.nit);
            mywindow.document.write('</div>');
            
            mywindow.document.write('<div style="margin-top:20px; float:right;">');
            mywindow.document.write("<b>CLIENTE: </b>" + clienteNombre + "<br>");
            mywindow.document.write("________________________________<br>");
            mywindow.document.write("C.C/NIT: " + clienteNit);
            mywindow.document.write('</div>');

            // OBSERVACIÓN
            var nota = prefact.resp['0'].Prefactura.observacion != null && prefact.resp['0'].Prefactura.observacion != "" ?
                prefact.resp['0'].Prefactura.observacion : "";
            
            mywindow.document.write('<div style="float:left; margin-top:10px; width:100%" align="left">');
            mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;">');
            mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
            mywindow.document.write('<b>Nota: </b>' + nota + '</div></div></div>');

            mywindow.document.write('</div>');
            // Pie de página de marca de agua o software
            mywindow.document.write('<div style="width:100%; text-align:center; font-size:10px; margin-top:20px; color:#999;">Miggo Solutions S.A.S</div>');
            
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
    return (meses[f.getMonth()] + " " + f.getDate() + ", " + f.getFullYear() + ' ' + f.getHours() + ':' + f.getMinutes() +  ':' + f.getSeconds());
};


var enviarPrefactura = function() {
    var cliente = $('.id_cliente').val();

    console.log(cliente);

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

/**
 * PUNTO DE ENTRADA NUEVO: Invoca esto para imprimir en Tirilla de 8cm
 */
var imprimirEnTicket = function() {
    if ($('#esFacturaDV').val() == '1') {
        imprimirPrefacturaFacturaTicket();
    } else {
        imprimirPrefacturaDocumentoEquivalenteTicket();
    }
};

// ==========================================
// PREFACTURA FACTURA (CON IVA) EN TICKET 80MM - TOTALMENTE CORREGIDO
// ==========================================
var imprimirPrefacturaFacturaTicket = function() {
    var prefactId = $('#prefactId').val();
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
                // Inyectamos dinámicamente el controlador onerror para ocultar el recuadro si falla la imagen
                var fixedImgHtml = rawImgHtml.replace('<img', '<img onerror="this.style.display=\'none\';"');
                mywindow.document.write('<div>' + fixedImgHtml + '</div>');
            }
            
            mywindow.document.write('<div class="text-center" style="font-size:12px;"><b>' + prefact.resp['0'].EM.nombre + '</b></div>');
            mywindow.document.write('<div class="text-center" style="font-size:11px;"><b>PREFACTURA No. ' + zfill(prefactId, 6) + '</b></div>');
            
            mywindow.document.write('<div><b>Nit:</b> ' + prefact.resp['0'].EM.nit + '</div>');
            mywindow.document.write('<div><b>Teléfono:</b> ' + prefact.resp['0'].EM.telefono1 + '</div>');
            mywindow.document.write('<div><b>Dirección:</b> ' + prefact.resp['0'].EM.direccion + '</div>');
            
            mywindow.document.write('<div class="separador"></div>');
            
            var ciudadNom = prefact.resp['0'].CIU && prefact.resp['0'].CIU.descripcion ? prefact.resp['0'].CIU.descripcion : "";
            var paisNom = prefact.resp['0'].PAI && prefact.resp['0'].PAI.descripcion ? prefact.resp['0'].PAI.descripcion : "";
            var ubicacion = [ciudadNom, paisNom, fechaActual].filter(Boolean).join(', ');
            mywindow.document.write('<div>' + ubicacion + '</div>');
            
            if (prefact.resp['0'].EM.texto1) {
                mywindow.document.write('<div>' + prefact.resp['0'].EM.texto1 + '</div>');
            }
            
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

            var ttalBases = 0;
            var ttalImp = 0;
            var ttalDcto = 0;

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

                    ttalBases += subtotalFilaNeto;
                    ttalImp += valorIvaFila;
                    ttalDcto += valDcto;

                    var descTxt = val.PFD.porcentaje && parseFloat(val.PFD.porcentaje) > 0 ? ' (' + val.PFD.porcentaje + '% Dcto)' : '';
                    var marcImp = pctIva > 0 ? '*' : '';

                    mywindow.document.write('<tr>');
                    mywindow.document.write('<td>' + cant + '</td>');
                    mywindow.document.write('<td>' + val.P.descripcion + marcImp + descTxt + '</td>');
                    mywindow.document.write('<td class="text-right">' + vlrUnitarioItem.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>');
                    mywindow.document.write('<td class="text-right">' + subtotalFilaNeto.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>');
                    mywindow.document.write('</tr>');
                });
            }
            mywindow.document.write('</tbody></table>');

            mywindow.document.write('<div class="separador"></div>');
            
            var subtotalConDescuento = ttalBases - ttalDcto;
            var vrAbonos = $('.ttalAbonos').val() != "" ? parseFloat($('.ttalAbonos').val()) : 0;
            var ttalFinal = subtotalConDescuento + ttalImp - vrAbonos;

            mywindow.document.write('<table style="margin-top:0px; width:100%;">');
            mywindow.document.write('<tr><td class="text-right" style="width:60%;"><b>Subtotal:</b></td><td class="text-right" style="width:40%;">' + ttalBases.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>');
            mywindow.document.write('<tr><td class="text-right"><b>Subtotal con Dcto:</b></td><td class="text-right">' + subtotalConDescuento.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>');
            mywindow.document.write('<tr><td class="text-right"><b>IVA:</b></td><td class="text-right">' + ttalImp.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>');
            mywindow.document.write('<tr><td class="text-right"><b>Reteica:</b></td><td class="text-right">0.00</td></tr>');
            mywindow.document.write('<tr><td class="text-right"><b>Retefuente:</b></td><td class="text-right">0.00</td></tr>');
            
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
            
            setTimeout(function() {
                mywindow.focus();
                mywindow.print();
                mywindow.close();
            }, 500);
        }
    });
};

// ==========================================
// PREFACTURA SIN IVA (DOC EQUIVALENTE) EN TICKET 80MM - CORREGIDO IMAGEN
// ==========================================
var imprimirPrefacturaDocumentoEquivalenteTicket = function() {
    var prefactId = $('#prefactId').val();
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
            
            setTimeout(function() {
                mywindow.focus();
                mywindow.print();
                mywindow.close();
            }, 500);
        }
    });
};

$(function() {
    $("#imprimirCot").click(imprimir);
    $("#imprimirTicket").click(imprimirEnTicket);
    $(".wppSendPF").click(enviarPrefactura);
    $('#dv_emp').hide();
});