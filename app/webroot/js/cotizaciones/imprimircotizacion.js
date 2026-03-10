/**
 * Imprimir la prefactura
 * @returns {undefined}
 */

const formato = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
});

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


var imprimirCotizacionConIva = function() {

    var cotizacionId = $('#cotizacionId').val();

    if (cotizacionId == "") {
        bootbox.alert('No se ha creado la cotización.');
    } else {
        
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizacionesdetalles/ajaxObtenerDetallesCotizacion',
            data: { cotizacionId: cotizacionId },
            type: "POST",
            success: function(data) {
                var cotizacion = JSON.parse(data);

                console.log(cotizacion);    

                // se obtiene la fecha actual
                var fechaActual = obtenerFechaActual();

                var mywindow = window.open('', 'PRINT', 'height=400,width=600');
                mywindow.document.write('<html><head>');
                mywindow.document.write('<style>');
                mywindow.document.write('@page { size: auto; margin: 0; }'); 
                mywindow.document.write('body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }'); 
                mywindow.document.write('table { border-collapse: collapse; width: 100%; margin-bottom: 20px; font-size: 11px; }'); 
                mywindow.document.write('th { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 10px; }'); 
                mywindow.document.write('td { border: 1px solid #ddd; padding: 8px; font-size: 10px; }'); 
                mywindow.document.write('th { background-color: #f5f5f5; font-weight: bold; }'); 
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
                // mywindow.document.write($('#dv_img_emp').html());
                mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
                mywindow.document.write('<b>' + cotizacion.resp['0'].EM.nombre + '</b></div>');
                mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
                mywindow.document.write('<b>COTIZACIÓN No. ' + zfill(cotizacionId, 6) + ' </b></div>');
                
                // // Información de la empresa
                mywindow.document.write('<div><b>Nit:</b>' + cotizacion.resp['0'].EM.nit ?? '' + '</div>');
                mywindow.document.write('<div><b>Teléfono:</b>' + cotizacion.resp['0'].EM.telefono1 ?? '' + '</div>');
                mywindow.document.write('<div><b>Dirección:</b>' + cotizacion.resp['0'].EM.direccion ?? '' + '</div>');
                
                // Información de la fecha y legal
                mywindow.document.write('<div style="width:100%; float:left; margin-top:20px; margin-bottom:5px";>');
                mywindow.document.write('<div>' + cotizacion.resp['0'].CIU.descripcion + ', ');
                mywindow.document.write(cotizacion.resp['0'].PAI.descripcion + ', ');
                mywindow.document.write(fechaActual + '</div>');
                mywindow.document.write('<div>' + cotizacion.resp['0'].EM.texto1 ?? '' + '</div>');
                
                // Información del cliente
                if ($('#PrefacturaDatoscliente').val() != "") {
                    var celular  = cotizacion.resp['0'].CL.celular ?? '';
                    var direccion  = cotizacion.resp['0'].CL.direccion ?? '';
                    mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Cliente: </b>' + (cotizacion.resp['0'].CL.nombre ?? 'Consumidor Final') + '</div></div>');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Teléfono: </b>' + celular + '</div></div></div>');
                    mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                    mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Identificación: </b>' + (cotizacion.resp['0'].CL.nit ?? '') + '</div></div>');
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

                if (cotizacion.resp.length > 0) {
                    mywindow.document.write('<tbody>');
                
                    var subtotal = 0;
                    var descuento = 0;
                    var IVA = 0;
                    var INC = 0;
                    var INCBolsa = 0;
                    var ttalImp = 0;
                    var contador = 1;
                    var ttalFinal = 0;
                
                    $.each(cotizacion.resp, function(k, val) {

                        const cantidad = parseFloat(val.Cotizacionesdetalle.cantidad) || 0;
                        const costoVenta = parseFloat(val.Cotizacionesdetalle.costoventa) || 0;
                        const porcentajeDesc = parseFloat(val.Cotizacionesdetalle.porcentaje) || 0;
                        const tasaIVA = parseFloat(val.Cotizacionesdetalle.impuesto) || 0;
                        const tasaINC = parseFloat(val.Cotizacionesdetalle.impoconsumo) || 0;
                        const valBolsa = parseFloat(val.Cotizacionesdetalle.incbolsa) || 0;

                        const objDatosProd = {
                            unidadesProd: cantidad,
                            precioVenta: costoVenta,
                            porcentajeDesc: porcentajeDesc,
                            prcIVA: tasaIVA / 100,
                            prcINC: tasaINC / 100,
                            valBolsa: valBolsa
                        };

                        const res = obtenerValorBaseProducto(objDatosProd); 

                        const valBaseUnitario = parseFloat(res.valorBaseUnitario) || 0;
                        const valDescuento = parseFloat(res.descuento) || 0;
                        const valIVA = parseFloat(res.valorIVA) || 0;
                        const valINC = parseFloat(res.valorINC) || 0;
                        const valBolsaTotal = parseFloat(res.varorINCBolsa) || 0;

                        const precioUnitario = (valBaseUnitario + valDescuento) / cantidad;
                        const bolsaUnitario = cantidad > 0 ? (valBolsaTotal / cantidad) : 0;
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
                mywindow.document.write('<td colspan="9" class="align-right"><b>Subtotal</b></td>');
                mywindow.document.write('<td class="align-right">' + formato.format(subtotal) + '</td>');
                mywindow.document.write('</tr>');
                mywindow.document.write('<tr>');
                mywindow.document.write('<td colspan="9" class="align-right">Descuento</td>');
                mywindow.document.write('<td class="align-right">' + formato.format(descuento) + '</td>');
                mywindow.document.write('</tr>');
                mywindow.document.write('<tr>');
                mywindow.document.write('<td colspan="9" class="align-right"><b>Total Bruto Cotización</b></td>');
                mywindow.document.write('<td class="align-right">' + formato.format(subtotal - descuento) + '</td>');
                mywindow.document.write('</tr>');
                mywindow.document.write('<tr>');
                mywindow.document.write('<td colspan="9" class="align-right">IVA</td>');
                mywindow.document.write('<td class="align-right">' + formato.format(IVA) + '</td>');
                mywindow.document.write('</tr>');
                mywindow.document.write('<tr>');
                mywindow.document.write('<td colspan="9" class="align-right">INC</td>');
                mywindow.document.write('<td class="align-right">' + formato.format(INC) + '</td>');
                mywindow.document.write('</tr>');
                mywindow.document.write('<tr>');
                mywindow.document.write('<td colspan="9" class="align-right">Bolsas</td>');
                mywindow.document.write('<td class="align-right">' + formato.format(INCBolsa) + '</td>');
                mywindow.document.write('</tr>');
                mywindow.document.write('<tr>');
                mywindow.document.write('<td colspan="9" class="align-right"><b>Total impuestos</b></td>');
                mywindow.document.write('<td class="align-right">' + formato.format(ttalImp)  + '</td>');
                mywindow.document.write('</tr>');

                ttalFinal = subtotal - descuento + ttalImp;
                
                mywindow.document.write('<tr>');
                mywindow.document.write('<td colspan="9" class="align-right"><b>Total cotización</b></td>');
                mywindow.document.write('<td class="align-right">' + formato.format(ttalFinal) + '</td>');
                mywindow.document.write('</tr>');
                mywindow.document.write('</table></div>');
                
                // EMISOR Y RECEPTOR
                mywindow.document.write('<div style="margin-top:20px; float:left;">');
                mywindow.document.write("<b>EMISOR: </b>" + cotizacion.resp['0'].EM.nombre + "<br>");
                mywindow.document.write("________________________________<br>");
                mywindow.document.write("<b>Nit: </b>" + cotizacion.resp['0'].EM.nit);
                mywindow.document.write('</div>');
                mywindow.document.write('<div style="margin-top:20px; float:right;">');
                mywindow.document.write("<b>CLIENTE: </b>" + (cotizacion.resp['0'].CL.nombre ?? 'Consumidor Final') + "<br>");
                mywindow.document.write("________________________________<br>");
                mywindow.document.write("C.C/NIT: " + (cotizacion.resp['0'].CL.nit ?? '222222222'));
                mywindow.document.write('</div>');
                
                // OBSERVACIÓN
                var nota = $('#observacion').val();
                mywindow.document.write('<div style="float:left; margin-top:10px; width:100%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;">');
                mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Nota: </b>' + nota + '</div></div></div>');
                
                mywindow.document.write('</div>');
                mywindow.document.write('</body></html>');
                mywindow.document.title = cotizacion.resp['0'].CL.nombre + " - COTIZACIÓN";
                mywindow.document.close();
                mywindow.focus();
                mywindow.print();
                mywindow.close();
            }
        });
    }
};

var imprimirCotizacionSinIva = function() {

    var cotizacionId = $('#cotizacionId').val();

    if (cotizacionId == "") {
        bootbox.alert('No se ha creado la cotización.');
    } else {
        
        $.ajax({
            url: $('#url-proyecto').val() + 'cotizacionesdetalles/ajaxObtenerDetallesCotizacion',
            data: { cotizacionId: cotizacionId },
            type: "POST",
            success: function(data) {
                var cotizacion = JSON.parse(data);

                console.log(cotizacion);    

                // se obtiene la fecha actual
                var fechaActual = obtenerFechaActual();

                var mywindow = window.open('', 'PRINT', 'height=400,width=600');
                mywindow.document.write('<html><head>');
                mywindow.document.write('<style>');
                mywindow.document.write('@page { size: auto; margin: 0; }'); 
                mywindow.document.write('body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }'); 
                mywindow.document.write('table { border-collapse: collapse; width: 100%; margin-bottom: 20px; font-size: 11px; }'); 
                mywindow.document.write('th { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 10px; }'); 
                mywindow.document.write('td { border: 1px solid #ddd; padding: 8px; font-size: 10px; }'); 
                mywindow.document.write('th { background-color: #f5f5f5; font-weight: bold; }'); 
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
                // mywindow.document.write($('#dv_img_emp').html());
                mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
                mywindow.document.write('<b>' + cotizacion.resp['0'].EM.nombre + '</b></div>');
                mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
                mywindow.document.write('<b>COTIZACIÓN No. ' + zfill(cotizacionId, 6) + ' </b></div>');
                
                // // Información de la empresa
                mywindow.document.write('<div><b>Nit:</b>' + cotizacion.resp['0'].EM.nit ?? '' + '</div>');
                mywindow.document.write('<div><b>Teléfono:</b>' + cotizacion.resp['0'].EM.telefono1 ?? '' + '</div>');
                mywindow.document.write('<div><b>Dirección:</b>' + cotizacion.resp['0'].EM.direccion ?? '' + '</div>');
                
                // Información de la fecha y legal
                mywindow.document.write('<div style="width:100%; float:left; margin-top:20px; margin-bottom:5px";>');
                mywindow.document.write('<div>' + cotizacion.resp['0'].CIU.descripcion + ', ');
                mywindow.document.write(cotizacion.resp['0'].PAI.descripcion + ', ');
                mywindow.document.write(fechaActual + '</div>');
                mywindow.document.write('<div>' + cotizacion.resp['0'].EM.texto1 ?? '' + '</div>');
                
                // Información del cliente
                if ($('#PrefacturaDatoscliente').val() != "") {
                    var celular  = cotizacion.resp['0'].CL.celular ?? '';
                    var direccion  = cotizacion.resp['0'].CL.direccion ?? '';
                    mywindow.document.write('<div style="margin:0px; width:100%; float:left;"><div style="float:left; margin-top: 10px; width:50%" align="left">');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Cliente: </b>' + (cotizacion.resp['0'].CL.nombre ?? 'Consumidor Final') + '</div></div>');
                    mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Teléfono: </b>' + celular + '</div></div></div>');
                    mywindow.document.write('<div style="float:right; margin-top:10px; width:50%" align="left"><div style="margin: 2px; float: left; width: 100%;">');
                    mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                    mywindow.document.write('<b>Identificación: </b>' + (cotizacion.resp['0'].CL.nit ?? '') + '</div></div>');
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

                mywindow.document.write('<div style="margin-top: 30px; float: left; width: 100%; aling-items: center; justify-content: center">');
                mywindow.document.write('<table style="font-family:sans-serif; border-collapse: collapse; width: 100%;"><thead>');
                mywindow.document.write('<tr><th class="align-left">#</th>');
                mywindow.document.write('<th class="align-left">Nombre</th>');
                mywindow.document.write('<th class="align-left">Código</th>');
                mywindow.document.write('<th class="align-left">Cant.</th>');
                mywindow.document.write('<th class="align-left">Precio unitario</th>');
                mywindow.document.write('<th class="align-left">Descuento</th>');
                mywindow.document.write('<th class="align-left">Total línea</th>');
                mywindow.document.write('</tr></thead>');

                if (cotizacion.resp.length > 0) {
                    mywindow.document.write('<tbody>');
                
                    var subtotal = 0;
                    var descuento = 0;
                    var IVA = 0;
                    var INC = 0;
                    var INCBolsa = 0;
                    var ttalImp = 0;
                    var contador = 1;
                    var ttalFinal = 0;
                
                    $.each(cotizacion.resp, function(k, val) {

                        const cantidad = parseFloat(val.Cotizacionesdetalle.cantidad) || 0;
                        const costoVenta = parseFloat(val.Cotizacionesdetalle.costoventa) || 0;
                        const porcentajeDesc = parseFloat(val.Cotizacionesdetalle.porcentaje) || 0;
                        const tasaIVA = parseFloat(val.Cotizacionesdetalle.impuesto) || 0;
                        const tasaINC = parseFloat(val.Cotizacionesdetalle.impoconsumo) || 0;
                        const valBolsa = parseFloat(val.Cotizacionesdetalle.incbolsa) || 0;

                        const objDatosProd = {
                            unidadesProd: cantidad,
                            precioVenta: costoVenta,
                            porcentajeDesc: porcentajeDesc,
                            prcIVA: tasaIVA / 100,
                            prcINC: tasaINC / 100,
                            valBolsa: valBolsa
                        };

                        const res = obtenerValorBaseProducto(objDatosProd); 

                        const valBaseUnitario = parseFloat(res.valorBaseUnitario) || 0;
                        const valDescuento = parseFloat(res.descuento) || 0;
                        const valIVA = parseFloat(res.valorIVA) || 0;
                        const valINC = parseFloat(res.valorINC) || 0;
                        const valBolsaTotal = parseFloat(res.varorINCBolsa) || 0;

                        const precioUnitario = (valBaseUnitario + valDescuento) / cantidad;
                        const bolsaUnitario = cantidad > 0 ? (valBolsaTotal / cantidad) : 0;
                        const totalLinea = (precioUnitario * cantidad) - valDescuento + valIVA + valINC;    

                        mywindow.document.write('<tr>');
                        mywindow.document.write('<td class="align-left">' + contador + '</td>');
                        mywindow.document.write('<td class="align-left">' + val.P.descripcion + '</td>');
                        mywindow.document.write('<td class="align-left">' + val.P.codigo + '</td>');
                        mywindow.document.write('<td class="align-right">' + (cantidad) + '</td>');
                        mywindow.document.write('<td class="align-right">' + formato.format(precioUnitario) + '</td>');
                        mywindow.document.write('<td class="align-right">' + formato.format(valDescuento) + '</td>');
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
                mywindow.document.write('<td colspan="6" class="align-right"><b>Subtotal</b></td>');
                mywindow.document.write('<td class="align-right">' + formato.format(subtotal) + '</td>');
                mywindow.document.write('</tr>');
                mywindow.document.write('<tr>');
                mywindow.document.write('<td colspan="6" class="align-right">Descuento</td>');
                mywindow.document.write('<td class="align-right">' + formato.format(descuento) + '</td>');
                mywindow.document.write('</tr>');

                ttalFinal = subtotal - descuento + ttalImp;
                
                mywindow.document.write('<tr>');
                mywindow.document.write('<td colspan="6" class="align-right"><b>Total cotización</b></td>');
                mywindow.document.write('<td class="align-right">' + formato.format(ttalFinal) + '</td>');
                mywindow.document.write('</tr>');
                mywindow.document.write('</table></div>');
                
                // EMISOR Y RECEPTOR
                mywindow.document.write('<div style="margin-top:20px; float:left;">');
                mywindow.document.write("<b>EMISOR: </b>" + cotizacion.resp['0'].EM.nombre + "<br>");
                mywindow.document.write("________________________________<br>");
                mywindow.document.write("<b>Nit: </b>" + cotizacion.resp['0'].EM.nit);
                mywindow.document.write('</div>');
                mywindow.document.write('<div style="margin-top:20px; float:right;">');
                mywindow.document.write("<b>CLIENTE: </b>" + (cotizacion.resp['0'].CL.nombre ?? 'Consumidor Final') + "<br>");
                mywindow.document.write("________________________________<br>");
                mywindow.document.write("C.C/NIT: " + (cotizacion.resp['0'].CL.nit ?? '222222222'));
                mywindow.document.write('</div>');
                
                // OBSERVACIÓN
                var nota = $('#observacion').val();
                mywindow.document.write('<div style="float:left; margin-top:10px; width:100%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;">');
                mywindow.document.write('<div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Nota: </b>' + nota + '</div></div></div>');
                
                mywindow.document.write('</div>');
                mywindow.document.write('</body></html>');
                mywindow.document.title = cotizacion.resp['0'].CL.nombre + " - COTIZACIÓN";
                mywindow.document.close();
                mywindow.focus();
                mywindow.print();
                mywindow.close();
            }
        });
    }
};


var imprimirCotizacion = function() {

    if ($('#CotizacioneTipoCotizacion').val() == '1') {
        imprimirCotizacionConIva();
    } else {
        imprimirCotizacionSinIva();
    }

};