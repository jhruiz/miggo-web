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
            mywindow.document.write('<div>' + prefact.resp['0'].CIU.descripcion + ', ');
            mywindow.document.write(prefact.resp['0'].PAI.descripcion + ', ');
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

                    mywindow.document.write('<tr>');
                    mywindow.document.write('<td class="align-left">' + contador + '</td>');
                    mywindow.document.write('<td class="align-left">' + val.P.descripcion + '</td>');
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
            mywindow.document.write('<div>' + prefact.resp['0'].CIU.descripcion + ', ');
            mywindow.document.write(prefact.resp['0'].PAI.descripcion + ', ');
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

                    mywindow.document.write('<tr>');
                    mywindow.document.write('<td class="align-left">' + contador + '</td>');
                    mywindow.document.write('<td class="align-left">' + val.P.descripcion + '</td>');
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

$(function() {
    $("#imprimirCot").click(imprimir);
    $(".wppSendPF").click(enviarPrefactura);
    $('#dv_emp').hide();
});