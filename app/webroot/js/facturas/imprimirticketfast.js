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
        imprimirFacturaFacturaRapidaTicket();
    } else {
        imprimirFacturaDocumentoEquivalenteTicket();
    }
};

// ==========================================
// PREFACTURA FACTURA (CON IVA) EN TICKET 80MM - TOTALMENTE CORREGIDO
// ==========================================
var imprimirFacturaFacturaRapidaTicket = function() {
    var prefactId = $('#prefacturaId').val(); // O la variable de control que uses para el ID
    
    $.ajax({
        url: $('#url-proyecto').val() + 'facturas/obtenerFacturaFacturaFast', // Tu URL de destino
        data: { prefactId: prefactId },
        type: "POST",
        success: function(response) {
            // Evaluamos si viene como string o ya es objeto
            var data = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (!data.resp || !data.infoGeneralFactura || data.infoGeneralFactura.length === 0) {
                console.error("Estructura de datos inválida");
                return;
            }

            // Mapeo de objetos principales basados en tu JSON
            var infoG = data.infoGeneralFactura[0];
            var empresa = infoG.E;
            var resolucion = infoG.RF;
            var ciudad = infoG.CIUM;
            var depto = infoG.DEPM;
            var cliente = infoG.CL;
            var vendedor = infoG.U;
            var facturaF = data.infoDetFact[0].F; // Datos generales de la transacción (Número, fechas, etc)

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
            mywindow.write('<html><head><title>FACTURA ' + resolucion.prefijo + ' ' + facturaF.consecutivodian + '</title>');
            mywindow.write('<style>');
            mywindow.write('body { margin: 0; padding: 0; font-family: Arial, sans-serif; font-size: 11px; width: 80mm; color: #000; line-height: 1.3; }');
            mywindow.write('@page { size: 80mm auto; margin: 0mm; }');
            mywindow.write('@media print { body { margin: 0; width: 72mm; padding: 2mm; } .no-print { display: none; } }');
            mywindow.write('table { width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 5px; }');
            mywindow.write('th { font-size: 11px; padding: 5px 0; border-bottom: 1px solid #000; text-align: left; font-weight: bold; }');
            mywindow.write('td { font-size: 11px; padding: 4px 0; vertical-align: top; }');
            mywindow.write('.separador { border-top: 1px dashed #000; margin: 8px 0; }');
            mywindow.write('.text-center { text-align: center; } .text-right { text-align: right; }');
            mywindow.write('.logo-contenedor { width: 100%; text-align: center; font-size: 28px; font-family: "Courier New", Courier, monospace; letter-spacing: 4px; margin-bottom: 10px; }');
            mywindow.write('</style></head><body>');

            mywindow.write('<div style="width: 100%; padding-right: 5px;">');
            
            // --- ENCABEZADO ---
            // Tratamiento del contenedor de imagen para mitigar errores de carga o rutas rotas
            var rawImgHtml = $('#dv_img_emp').html() || "";
            if (rawImgHtml.trim() !== "") {
                // Inyectamos dinámicamente el controlador onerror para ocultar el recuadro si falla la imagen
                var fixedImgHtml = rawImgHtml.replace('<img', '<img onerror="this.style.display=\'none\';"');
                mywindow.write('<div>' + fixedImgHtml + '</div>');
            }
            mywindow.write('<div class="text-center"><b>' + empresa.nombre + '</b></div>');
            mywindow.write('<div class="text-center" style="font-size: 10px;">NIT: ' + empresa.nit + ' - ' + empresa.texto1 + '</div>');
            mywindow.write('<div class="text-center" style="font-size: 10px;">Resolución No. ' + resolucion.resolucion + ' de ' + resolucion.fechainicio + '</div>');
            mywindow.write('<div class="text-center" style="font-size: 10px;">Prefijo: ' + resolucion.prefijo + ', Rango ' + resolucion.resolucioninicia + ' al ' + resolucion.resolucionfin + '</div>');
            mywindow.write('<div class="text-center" style="font-size: 10px;">Vigencia Desde:' + resolucion.fechainicio + ' Hasta: ' + resolucion.fechafin + '</div>');
            mywindow.write('<div class="text-center"><b>REPRESENTACIÓN GRÁFICA DE FACTURA ELECTRÓNICA</b></div>');
            mywindow.write('<div class="text-center">' + empresa.direccion + ' - </div>');
            mywindow.write('<div class="text-center">Tel: ' + empresa.telefono1 + ' | E-mail: ' + empresa.email + '</div>');
            
            mywindow.write('<div class="separador"></div>');
            
            // --- INFO FACTURA ---
            mywindow.write('<div class="text-center" style="font-size: 12px;"><b>FACTURA ELECTRONICA ' + facturaF.prefijo + ' ' + facturaF.consecutivodian + '</b></div>');
            
            // Formatear Fecha (Asumiendo formato: "2026-06-15 16:15:07")
            var fechaPartes = facturaF.created.split(' ');
            var f = fechaPartes[0].split('-');
            var meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            var fechaTexto = ciudad.nombre + ' (' + depto.descripcion + '), ' + meses[parseInt(f[1])-1] + ' ' + f[2] + ', ' + f[0] + ' ' + fechaPartes[1];
            
            mywindow.write('<div class="text-center">' + fechaTexto + '</div>');
            mywindow.write('<div class="text-center">Emitido a la DIAN</div>');
            
            mywindow.write('<div class="separador"></div>');
            
            // --- DATOS DEL CLIENTE Y VENTA ---
            mywindow.write('<div><b>Cliente:</b> ' + cliente.nombre + '</div>');
            mywindow.write('<div><b>CC/NIT:</b> ' + cliente.nit + '</div>');
            mywindow.write('<div><b>Tel:</b> ' + (cliente.telefono || '') + '</div>');
            mywindow.write('<div><b>Dir:</b> ' + (cliente.direccion || '') + '</div>');
            mywindow.write('<div><b>Orden:</b> | <b>Fecha:</b> </div>');
            mywindow.write('<div><b>Vendedor:</b> ' + vendedor.nombre + '</div>');
            
            // Métodos de Pago
            mywindow.write('<div><b>Método(s) de Pago:</b></div>');
            var totalFacturaAcumulado = 0;
            $.each(data.tiposPagos, function(i, pago) {
                var valorPago = parseFloat(pago.FacturaCuentaValore.valor) || 0;
                totalFacturaAcumulado += valorPago;
                mywindow.write('<div> - ' + pago.T.descripcion + ': $' + valorPago.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</div>');
            });
            
            // --- TABLA DE DETALLES ---
            mywindow.write('<table><thead>');
            mywindow.write('<tr><th style="width:65%;">DESCRIPCIÓN</th><th class="text-center" style="width:10%;">CANT</th><th class="text-right" style="width:25%;">TOTAL</th></tr>');
            mywindow.write('</thead><tbody>');

            var subtotalProd = 0;
            var totalIva = 0;

            $.each(data.infoDetFact, function(k, item) {
                var detalle = item.Facturasdetalle;
                var prod = item.P;
                var valores = item.valoresBase;

                var cant = parseFloat(detalle.cantidad) || 0;
                var totalFila = parseFloat(detalle.costototal) || 0;
                
                // Acumuladores basados en los valores calculados por el backend
                subtotalProd += (valores.valorBaseUnitario * cant);
                totalIva += (valores.valorIVA * cant);

                mywindow.write('<tr>');
                mywindow.write('<td>' + prod.descripcion + '<br><span style="font-size:10px; color:#555;">' + prod.codigo + '</span></td>');
                mywindow.write('<td class="text-center">' + cant + '</td>');
                mywindow.write('<td class="text-right">' + totalFila.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>');
                mywindow.write('</tr>');
            });
            
            mywindow.write('</tbody></table>');
            mywindow.write('<div class="separador"></div>');
            
            // --- TOTALES ---
            mywindow.write('<table style="margin-top:0px; width:100%;">');
            mywindow.write('<tr><td class="text-right" style="width:60%;">Subtotal Serv:</td><td class="text-right" style="width:40%;">0.00</td></tr>');
            mywindow.write('<tr><td class="text-right">Subtotal Prod:</td><td class="text-right">' + subtotalProd.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>');
            mywindow.write('<tr><td class="text-right"><b>Subtotal:</b></td><td class="text-right"><b>' + subtotalProd.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</b></td></tr>');
            mywindow.write('<tr><td class="text-right">Descuento:</td><td class="text-right">0.00</td></tr>');
            mywindow.write('<tr><td class="text-right"><b>Total Bruto:</b></td><td class="text-right"><b>' + subtotalProd.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</b></td></tr>');
            mywindow.write('<tr><td class="text-right">IVA:</td><td class="text-right">' + totalIva.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>');
            mywindow.write('<tr><td class="text-right">INC:</td><td class="text-right">0.00</td></tr>');
            mywindow.write('<tr><td class="text-right">Bolsas:</td><td class="text-right">0.00</td></tr>');
            mywindow.write('<tr><td class="text-right"><b>Total Impuestos:</b></td><td class="text-right"><b>' + totalIva.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</b></td></tr>');
            mywindow.write('<tr><td class="text-right" style="font-size:12px; border-top:1px solid #000; padding-top:4px;"><b>TOTAL FACTURA:</b></td><td class="text-right" style="font-size:12px; border-top:1px solid #000; padding-top:4px;"><b>$' + totalFacturaAcumulado.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</b></td></tr>');
            mywindow.write('</table>');
            
            mywindow.write('<div class="separador"></div>');
            
            // --- PIE DE PÁGINA ---
            mywindow.write('<div class="text-center" style="font-size: 9px; line-height: 1.2;">Software de facturación suministrado por Miggo Solutions S.A.S<br>NIT 901629169</div>');
            mywindow.write('<div class="text-center" style="font-size: 9px; line-height: 1.2;">Modalidad software adquirido desarrollador habilitado por la DIAN según concepto 013246 de 2025</div>');
            mywindow.write('<div class="text-center" style="font-size: 9px; line-height: 1.2;">www.miggo.com.co línea de servicio al cliente 3116871320.</div>');
            
            mywindow.write('</div>');
            mywindow.write('</body></html>');
            
            setTimeout(function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                
                setTimeout(function() {
                    $('#' + iframeId).remove(); 
                    location.reload();
                }, 400);
            }, 300);
        },
        error: function(xhr, status, error) {
            console.error("Error al recuperar los datos de la factura: ", error);
        }
    });
};

// =========================================================================
// DOCUMENTO DE VENTA EQUIVALENTE (SIN IMPUESTOS) EN TICKET - REEMPLAZO AJAX
// =========================================================================
var imprimirFacturaDocumentoEquivalenteTicket = function() {
    var facturaId = $('#prefacturaId').val(); // Ajusta este ID según corresponda a tu vista
    
    $.ajax({
        url: $('#url-proyecto').val() + 'facturas/obtenerFacturaFacturaFast', 
        data: { prefactId: facturaId }, // Asegúrate de que el backend reciba la variable esperada
        type: "POST",
        success: function(response) {
            // Validar si la respuesta viene codificada como String o ya es Objeto
            var data = typeof response === 'string' ? JSON.parse(response) : response;
            console.log(data);

            if (!data.resp || !data.infoGeneralFactura || data.infoGeneralFactura.length === 0) {
                console.error("Estructura de JSON inválida o vacía");
                return;
            }

            // Desestructuración de variables según el JSON real enviado
            var infoFactura = data.infoGeneralFactura[0];
            var empresa = infoFactura.E;
            var resolucionDoc = infoFactura.RF;
            var ciudad = infoFactura.CIUM;
            var depto = infoFactura.DEPM;
            var cliente = infoFactura.CL;
            var detalles = data.infoDetFact;
            var pagos = data.tiposPagos;

            // Formatear Fecha: Medellín (Antioquia), Junio 13, 2026 02:14:27
            var meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            var fechaOriginal = detalles[0].F.created ? detalles[0].F.created.replace(/-/g, "/") : "";
            var fechaFormateada = "";
            
            if (fechaOriginal) {
                var fechaObj = new Date(fechaOriginal);
                var mesStr = meses[fechaObj.getMonth()];
                var diaStr = fechaObj.getDate();
                var anioStr = fechaObj.getFullYear();
                var horaStr = fechaObj.toTimeString().split(' ')[0];
                fechaFormateada = ciudad.nombre + " (" + (depto.descripcion ? depto.descripcion.charAt(0) + depto.descripcion.slice(1).toLowerCase() : '') + "), " + mesStr + " " + diaStr + ", " + anioStr + " " + horaStr;
            }

            // Abrir ventana limpia de impresión nativa
            var mywindow = window.open('', 'PRINT', 'height=600,width=400');
            mywindow.document.write('<html><head><title>DOCUMENTO DE VENTA DV</title>');
            
            // Estilos del ticket adaptados al Documento de Venta
            mywindow.document.write('<style>');
            mywindow.document.write('body { margin: 0; padding: 0; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 11px; width: 80mm; color: #000; line-height: 1.3; }');
            mywindow.document.write('@page { size: 80mm auto; margin: 0mm; }');
            mywindow.document.write('@media print { body { margin: 0; width: 72mm; padding: 2mm 4mm; } .no-print { display: none; } }');
            mywindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 5px; }');
            mywindow.document.write('th { font-size: 11px; font-weight: bold; padding: 4px 0; border-bottom: 1px solid #000; text-align: left; text-transform: uppercase; }');
            mywindow.document.write('td { font-size: 11px; padding: 4px 0; vertical-align: top; }');
            mywindow.document.write('.separador { border-top: 1px dashed #000; margin: 8px 0; }');
            mywindow.document.write('.text-center { text-align: center; } .text-right { text-align: right; }');
            mywindow.document.write('img { max-width: 100%; height: auto; display: block; margin: 0 auto; padding-bottom: 5px; }');
            mywindow.document.write('.sku { font-size: 10px; color: #000; font-weight: normal; }');
            mywindow.document.write('</style></head><body>');

            mywindow.document.write('<div style="width: 100%;">');
            
            // Tratamiento del contenedor de imagen para mitigar errores de carga o rutas rotas
            var rawImgHtml = $('#dv_img_emp').html() || "";
            if (rawImgHtml.trim() !== "") {
                // Inyectamos dinámicamente el controlador onerror para ocultar el recuadro si falla la imagen
                var fixedImgHtml = rawImgHtml.replace('<img', '<img onerror="this.style.display=\'none\';"');
                mywindow.document.write('<div>' + fixedImgHtml + '</div>');
            }
            
            // Encabezado Simplificado Legal (Igual a la imagen 2)
            mywindow.document.write('<div class="text-center" style="font-size:11px; font-weight: bold; text-transform: uppercase; line-height: 1.4;">');
            mywindow.document.write('<b>' + empresa.nombre + '</b><br>');
            if (empresa.representantelegal) {
                mywindow.document.write('<b>' + empresa.representantelegal + '</b><br>');
            }
            mywindow.document.write('NIT: ' + empresa.nit + '<br>');
            mywindow.document.write(empresa.direccion + '<br>');
            mywindow.document.write(ciudad.nombre + ' (' + (depto.descripcion ? depto.descripcion.charAt(0) + depto.descripcion.slice(1).toLowerCase() : '') + ') - Colombia<br>');
            mywindow.document.write(empresa.telefono1 + '</div>');
            
            mywindow.document.write('<div class="separador"></div>');
            
            // Título dinámico "DOCUMENTO DE VENTA DV"
            var numConsecutivo = detalles[0].F.consecutivodv ? detalles[0].F.consecutivodv : (detalles[0].F.consecutivodian ? detalles[0].F.consecutivodian : '29');
            mywindow.document.write('<div class="text-center">');
            mywindow.document.write('<div style="font-weight: bold; font-size: 12px; text-transform: uppercase;">DOCUMENTO DE VENTA DV ' + numConsecutivo + '</div>');
            mywindow.document.write('<div style="font-size: 10px; margin-top: 2px;">' + fechaFormateada + '</div>');
            mywindow.document.write('</div>');
            
            mywindow.document.write('<div class="separador"></div>');

            // Datos del Cliente (con mayúsculas)
            mywindow.document.write('<div style="text-transform: uppercase; font-size: 11px; line-height: 1.3;">');
            mywindow.document.write('<div><b>Cliente:</b> ' + (cliente.nombre || '') + '</div>');
            mywindow.document.write('<div><b>CC/NIT:</b> ' + (cliente.nit || '') + '</div>');
            mywindow.document.write('<div><b>Tel:</b> ' + (cliente.telefono || cliente.celular || '') + '</div>');
            mywindow.document.write('<div><b>Dir:</b> ' + (cliente.direccion || '') + '</div>');
            mywindow.document.write('<div><b>Orden:</b> | <b>Fecha:</b> </div>');
            mywindow.document.write('<div><b>Vendedor:</b> ' + empresa.nombre + '</div>');
            
            // Métodos de Pago dinámicos mapeados del array tiposPagos
            if (pagos && pagos.length > 0) {
                mywindow.document.write('<div style="margin-top: 4px;"><b>Método(s) de Pago:</b><br>');
                $.each(pagos, function(i, p) {
                    var valorPago = parseFloat(p.FacturaCuentaValore.valor) || 0;
                    mywindow.document.write(' - ' + p.T.descripcion + ': $' + valorPago.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '<br>');
                });
                mywindow.document.write('</div>');
            }
            mywindow.document.write('</div>');
            
            mywindow.document.write('<div class="separador"></div>');
            
            // Tabla de Ítems
            mywindow.document.write('<table><thead>');
            mywindow.document.write('<tr><th style="width:65%;">DESCRIPCIÓN</th><th class="text-center" style="width:15%;">CANT</th><th class="text-right" style="width:20%;">TOTAL</th></tr>');
            mywindow.document.write('</thead><tbody>');

            var totalCalculado = 0;
            
            $.each(detalles, function(k, item) {
                var cant = parseFloat(item.Facturasdetalle.cantidad) || 0;
                var totalFila = parseFloat(item.Facturasdetalle.costototal) || 0;
                totalCalculado += totalFila;

                mywindow.document.write('<tr>');
                mywindow.document.write('<td><strong>' + item.P.descripcion + '</strong><br><span class="sku">' + item.P.codigo + '</span></td>');
                mywindow.document.write('<td class="text-center" style="vertical-align: middle;">' + cant + '</td>');
                mywindow.document.write('<td class="text-right" style="vertical-align: middle;">' + totalFila.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>');
                mywindow.document.write('</tr>');
            });
            
            mywindow.document.write('</tbody></table>');
            mywindow.document.write('<div class="separador"></div>');
            
            // Sección de bloques de totales (Fuerza impuestos a 0.00 y sube el bruto al total definitivo)
            var totalFormateado = totalCalculado.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            mywindow.document.write('<table style="margin-top:0px; width:100%; font-size: 11px;">');
            mywindow.document.write('<tr><td class="text-right" style="width:60%;">Subtotal Serv:</td><td class="text-right" style="width:40%;">0.00</td></tr>');
            mywindow.document.write('<tr><td class="text-right">Subtotal Prod:</td><td class="text-right">' + totalFormateado + '</td></tr>');
            mywindow.document.write('<tr><td class="text-right"><b>Subtotal:</b></td><td class="text-right"><b>' + totalFormateado + '</b></td></tr>');
            mywindow.document.write('<tr><td class="text-right">Descuento:</td><td class="text-right">0.00</td></tr>');
            mywindow.document.write('<tr><td class="text-right"><b>Total Bruto:</b></td><td class="text-right"><b>' + totalFormateado + '</b></td></tr>');
            mywindow.document.write('<tr><td class="text-right">IVA:</td><td class="text-right">0.00</td></tr>');
            mywindow.document.write('<tr><td class="text-right">INC:</td><td class="text-right">0.00</td></tr>');
            mywindow.document.write('<tr><td class="text-right">Bolsas:</td><td class="text-right">0.00</td></tr>');
            mywindow.document.write('<tr><td class="text-right"><b>Total Impuestos:</b></td><td class="text-right"><b>0.00</b></td></tr>');
            mywindow.document.write('<tr style="font-size:12px;"><td class="text-right" style="padding-top:5px;"><b>TOTAL FACTURA:</b></td><td class="text-right" style="padding-top:5px;"><b>$' + totalFormateado + '</b></td></tr>');
            mywindow.document.write('</table>');
            
            // Notas u Observaciones si existen en el JSON de la Factura "F"
            var observacionFact = detalles[0].F.observacion ? detalles[0].F.observacion : "";
            if (observacionFact.trim() !== "" && observacionFact !== 'null') {
                mywindow.document.write('<div style="margin-top:5px; font-size:10px;"><b>Nota:</b> ' + observacionFact + '</div>');
            }
            
            mywindow.document.write('<div class="separador"></div>');
            
            // Pie de página estático de Miggo Solutions
            mywindow.document.write('<div class="text-center" style="font-size: 8.5px; color: #000; line-height: 1.3; margin-top: 5px;">');
            mywindow.document.write('Software de facturación suministrado por Miggo Solutions S.A.S<br>');
            mywindow.document.write('NIT 901629169<br>');
            mywindow.document.write('Modalidad software adquirido desarrollador habilitado por la DIAN<br>');
            mywindow.document.write('según concepto 013246 de 2025<br>');
            mywindow.document.write('www.miggo.com.co línea de servicio al cliente 3116871320.');
            mywindow.document.write('</div>');
            
            mywindow.document.write('</div>');
            mywindow.document.write('</body></html>');
            mywindow.document.close();
            
            // Ejecución de impresión limpia
            mywindow.focus();
            setTimeout(function() {
                mywindow.print();
                mywindow.close();
                location.reload();
            }, 250);
        },
        error: function(err) {
            console.error("Error en la petición AJAX", err);
        }
    });
};