/**
 * imprimirRecibo.js
 * Genera e imprime el recibo de caja en una ventana emergente.
 * Sigue el mismo patrón de imprimirFactura.js / imprimirPrefacturas.js
 */

const formatoRecibo = new Intl.NumberFormat('es-CO', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
});

/**
 * Punto de entrada. Recibe el ID del recibo o lo lee del DOM.
 * @param {number|null} reciboId  ID del recibo. Si es null lo lee de #reciboId
 */
var imprimirRecibo = function(reciboId) {
    var id      = reciboId || $('#reciboId').val();
    var baseUrl = $('#baseUrl').val() || $('#url-proyecto').val();

    if (!id) {
        alert('No se pudo determinar el recibo a imprimir.');
        return;
    }

    $.ajax({
        url  : baseUrl + '/reciboscajas/ajaxObtenerDatosImpresion',
        data : { reciboId: id },
        type : 'POST',
        success: function(data) {
            var resp = JSON.parse(data);
            if (!resp.resp) {
                alert('No se pudo obtener la información del recibo.');
                return;
            }
            _renderImpresionRecibo(resp.recibo, resp.empresa, resp.abonos, baseUrl);
        },
        error: function() {
            alert('Error de comunicación al obtener el recibo.');
        }
    });
};

/**
 * Construye el HTML del recibo y abre la ventana de impresión.
 * @private
 */
var _renderImpresionRecibo = function(recibo, empresa, abonos, baseUrl) {

    var RC  = recibo['Reciboscaja'];
    var CL  = recibo['CL'];
    var TP  = recibo['TP'];
    var CU  = recibo['CU'];
    var USR = recibo['U'];
    var EM  = empresa['Empresa'];

    var valor    = parseFloat(RC.valor)  || 0;
    var saldo    = parseFloat(RC.saldo)  || 0;
    var aplicado = valor - saldo;

    var fechaRecibo = _formatearFecha(RC.created);
    var fechaImpresion = _fechaActual();

    var win = window.open('', 'IMPRIMIR_RECIBO', 'height=700,width=750');

    win.document.write('<html><head><meta charset="utf-8">');
    win.document.write('<title>Recibo ' + RC.consecutivo + '</title>');
    win.document.write('<style>');

    // ── Estilos ──────────────────────────────────────────────────────────────
    win.document.write('@page { size: auto; margin: 0; }');
    win.document.write('* { box-sizing: border-box; }');
    win.document.write('body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; color: #222; }');
    win.document.write('.pagina { width: 100%; max-width: 720px; margin: 0 auto; padding: 20px; }');

    // Encabezado empresa
    win.document.write('.encabezado { width: 100%; border-bottom: 3px solid #2A3F54; padding-bottom: 12px; margin-bottom: 14px; }');
    win.document.write('.encabezado-logo { float: left; width: 22%; }');
    win.document.write('.encabezado-logo img { max-width: 100px; max-height: 80px; }');
    win.document.write('.encabezado-empresa { float: left; width: 50%; padding-left: 10px; }');
    win.document.write('.encabezado-empresa .nombre { font-size: 15px; font-weight: bold; color: #2A3F54; }');
    win.document.write('.encabezado-empresa .dato { font-size: 11px; color: #555; line-height: 1.5; }');
    win.document.write('.encabezado-titulo { float: right; width: 26%; text-align: center; border: 2px solid #2A3F54; border-radius: 6px; padding: 8px 4px; }');
    win.document.write('.encabezado-titulo .tipo { font-size: 11px; font-weight: bold; color: #2A3F54; text-transform: uppercase; letter-spacing: 1px; }');
    win.document.write('.encabezado-titulo .consecutivo { font-size: 18px; font-weight: bold; color: #2A3F54; margin: 2px 0; }');
    win.document.write('.encabezado-titulo .fecha { font-size: 10px; color: #777; }');
    win.document.write('.clearfix::after { content: ""; display: table; clear: both; }');

    // Sección de datos
    win.document.write('.seccion { width: 100%; margin-bottom: 14px; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; }');
    win.document.write('.seccion-titulo { background: #2A3F54; color: #fff; font-size: 10px; font-weight: bold; text-transform: uppercase; padding: 5px 10px; letter-spacing: 0.5px; }');
    win.document.write('.seccion-body { padding: 10px; }');
    win.document.write('.fila { width: 100%; margin-bottom: 4px; overflow: hidden; }');
    win.document.write('.fila .etiqueta { float: left; width: 35%; font-size: 11px; color: #777; font-weight: bold; }');
    win.document.write('.fila .valor { float: left; width: 65%; font-size: 11px; color: #222; }');
    win.document.write('.col-izq { float: left; width: 48%; }');
    win.document.write('.col-der { float: right; width: 48%; }');

    // Tabla de abonos
    win.document.write('table { border-collapse: collapse; width: 100%; margin: 0; }');
    win.document.write('table th { background: #f0f0f0; border: 1px solid #ddd; padding: 6px 8px; font-size: 10px; text-align: left; }');
    win.document.write('table td { border: 1px solid #ddd; padding: 6px 8px; font-size: 10px; }');
    win.document.write('table tr:nth-child(even) { background: #fafafa; }');
    win.document.write('.text-right { text-align: right !important; }');
    win.document.write('.text-center { text-align: center !important; }');

    // Resumen financiero
    win.document.write('.resumen { border: 2px solid #2A3F54; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px; background: #f8f9fa; }');
    win.document.write('.resumen-fila { overflow: hidden; padding: 3px 0; border-bottom: 1px dashed #ddd; }');
    win.document.write('.resumen-fila:last-child { border-bottom: none; }');
    win.document.write('.resumen-fila .res-etiqueta { float: left; font-size: 11px; color: #555; }');
    win.document.write('.resumen-fila .res-valor { float: right; font-size: 11px; font-weight: bold; }');
    win.document.write('.resumen-total { background: #2A3F54; color: #fff; border-radius: 4px; padding: 6px 10px; overflow: hidden; margin-top: 6px; }');
    win.document.write('.resumen-total .res-etiqueta { float: left; font-size: 13px; font-weight: bold; color: #fff; }');
    win.document.write('.resumen-total .res-valor { float: right; font-size: 15px; font-weight: bold; color: #fff; }');

    // Firmas y pie
    win.document.write('.firmas { width: 100%; margin-top: 30px; overflow: hidden; }');
    win.document.write('.firma-box { float: left; width: 44%; text-align: center; margin: 0 3%; }');
    win.document.write('.firma-linea { border-top: 1px solid #333; margin-top: 40px; padding-top: 4px; font-size: 10px; color: #555; }');
    win.document.write('.pie { text-align: center; font-size: 9px; color: #aaa; margin-top: 20px; border-top: 1px solid #eee; padding-top: 8px; }');

    win.document.write('@media print {');
    win.document.write('  @page { margin: 0; }');
    win.document.write('  body { margin: 1cm; }');
    win.document.write('}');
    win.document.write('</style></head><body>');

    // ── Contenido ────────────────────────────────────────────────────────────
    win.document.write('<div class="pagina">');

    // ── ENCABEZADO ──
    win.document.write('<div class="encabezado clearfix">');

    // Logo
    win.document.write('<div class="encabezado-logo">');
    if (EM.imagen) {
        var urlImg = baseUrl + '/img/empresas/' + EM.id + '/' + EM.imagen;
        win.document.write('<img src="' + urlImg + '" alt="Logo">');
    }
    win.document.write('</div>');

    // Datos empresa
    win.document.write('<div class="encabezado-empresa">');
    win.document.write('<div class="nombre">' + _esc(EM.nombre) + '</div>');
    win.document.write('<div class="dato">NIT: ' + _esc(EM.nit) + '</div>');
    win.document.write('<div class="dato">Tel: ' + _esc(EM.telefono1 || '') + '</div>');
    win.document.write('<div class="dato">' + _esc(EM.direccion || '') + '</div>');
    if (EM.email) {
        win.document.write('<div class="dato">' + _esc(EM.email) + '</div>');
    }
    win.document.write('</div>');

    // Bloque consecutivo
    win.document.write('<div class="encabezado-titulo">');
    win.document.write('<div class="tipo">Recibo de Caja</div>');
    win.document.write('<div class="consecutivo">' + _esc(RC.consecutivo) + '</div>');
    win.document.write('<div class="fecha">' + fechaRecibo + '</div>');
    win.document.write('</div>');

    win.document.write('</div>'); // /encabezado

    // ── DATOS CLIENTE Y RECIBO (dos columnas) ──
    win.document.write('<div class="clearfix" style="margin-bottom:14px;">');

    // Columna cliente
    win.document.write('<div class="col-izq">');
    win.document.write('<div class="seccion">');
    win.document.write('<div class="seccion-titulo"><i>&#128100;</i> Cliente</div>');
    win.document.write('<div class="seccion-body">');
    win.document.write('<div class="fila"><span class="etiqueta">Nombre:</span><span class="valor"><b>' + _esc(CL.nombre || '') + '</b></span></div>');
    win.document.write('<div class="fila"><span class="etiqueta">NIT / CC:</span><span class="valor">' + _esc(CL.nit || '') + '</span></div>');
    if (CL.telefono) {
        win.document.write('<div class="fila"><span class="etiqueta">Teléfono:</span><span class="valor">' + _esc(CL.telefono) + '</span></div>');
    }
    if (CL.email) {
        win.document.write('<div class="fila"><span class="etiqueta">Email:</span><span class="valor">' + _esc(CL.email) + '</span></div>');
    }
    win.document.write('</div></div>');
    win.document.write('</div>');

    // Columna datos del recibo
    win.document.write('<div class="col-der">');
    win.document.write('<div class="seccion">');
    win.document.write('<div class="seccion-titulo">&#128203; Datos del Pago</div>');
    win.document.write('<div class="seccion-body">');
    win.document.write('<div class="fila"><span class="etiqueta">Fecha:</span><span class="valor">' + fechaRecibo + '</span></div>');
    win.document.write('<div class="fila"><span class="etiqueta">Tipo de Pago:</span><span class="valor">' + _esc(TP.descripcion || '') + '</span></div>');
    win.document.write('<div class="fila"><span class="etiqueta">Cuenta:</span><span class="valor">' + _esc(CU.descripcion || '') + '</span></div>');
    win.document.write('<div class="fila"><span class="etiqueta">Registrado por:</span><span class="valor">' + _esc(USR.nombre || '') + '</span></div>');
    if (RC.concepto) {
        win.document.write('<div class="fila"><span class="etiqueta">Concepto:</span><span class="valor">' + _esc(RC.concepto) + '</span></div>');
    }
    win.document.write('</div></div>');
    win.document.write('</div>');

    win.document.write('</div>'); // /clearfix dos columnas

    // ── RESUMEN FINANCIERO ──
    win.document.write('<div class="resumen">');
    win.document.write('<div class="resumen-fila">');
    win.document.write('<span class="res-etiqueta">Valor recibido</span>');
    win.document.write('<span class="res-valor">$ ' + formatoRecibo.format(valor) + '</span>');
    win.document.write('</div>');
    if (aplicado > 0) {
        win.document.write('<div class="resumen-fila">');
        win.document.write('<span class="res-etiqueta">Aplicado a documentos</span>');
        win.document.write('<span class="res-valor" style="color:#e67e22;">$ ' + formatoRecibo.format(aplicado) + '</span>');
        win.document.write('</div>');
    }
    win.document.write('<div class="resumen-total clearfix">');
    win.document.write('<span class="res-etiqueta">Saldo disponible</span>');
    win.document.write('<span class="res-valor">$ ' + formatoRecibo.format(saldo) + '</span>');
    win.document.write('</div>');
    win.document.write('</div>'); // /resumen

    // ── TABLA DE ABONOS APLICADOS (si existen) ──
    if (abonos && abonos.length > 0) {
        win.document.write('<div class="seccion">');
        win.document.write('<div class="seccion-titulo">Aplicaciones de este Recibo</div>');
        win.document.write('<div class="seccion-body" style="padding:0;">');
        win.document.write('<table>');
        win.document.write('<thead><tr>');
        win.document.write('<th>Fecha</th>');
        win.document.write('<th>Documento</th>');
        win.document.write('<th class="text-right">Valor Aplicado</th>');
        win.document.write('<th>Registrado por</th>');
        win.document.write('</tr></thead><tbody>');

        var totalTabla = 0;
        $.each(abonos, function(i, ab) {
            var AB  = ab['Abonofactura'];
            var PF  = ab['PF']  || {};
            var F   = ab['F']   || {};
            var UAB = ab['U']   || {};
            var valAb = parseFloat(AB.valor) || 0;
            totalTabla += valAb;

            var docRef = '';
            if (AB.factura_id) {
                docRef = 'Factura ' + (F.consecutivodian || F.codigo || AB.factura_id);
            } else if (AB.prefactura_id) {
                docRef = 'Prefactura #' + (PF.codigo || AB.prefactura_id);
            }

            win.document.write('<tr>');
            win.document.write('<td>' + _formatearFecha(AB.created) + '</td>');
            win.document.write('<td>' + _esc(docRef) + '</td>');
            win.document.write('<td class="text-right">$ ' + formatoRecibo.format(valAb) + '</td>');
            win.document.write('<td>' + _esc(UAB.nombre || '') + '</td>');
            win.document.write('</tr>');
        });

        win.document.write('<tr style="background:#f0f0f0;">');
        win.document.write('<td colspan="2" class="text-right"><b>Total aplicado:</b></td>');
        win.document.write('<td class="text-right"><b>$ ' + formatoRecibo.format(totalTabla) + '</b></td>');
        win.document.write('<td></td>');
        win.document.write('</tr>');
        win.document.write('</tbody></table>');
        win.document.write('</div></div>');
    }

    // ── FIRMAS ──
    win.document.write('<div class="firmas">');
    win.document.write('<div class="firma-box">');
    win.document.write('<div class="firma-linea">');
    win.document.write('<b>ENTREGADO POR</b><br>' + _esc(USR.nombre || '') + '<br>' + _esc(EM.nombre || ''));
    win.document.write('</div></div>');
    win.document.write('<div class="firma-box">');
    win.document.write('<div class="firma-linea">');
    win.document.write('<b>RECIBIDO POR</b><br>' + _esc(CL.nombre || '') + '<br>C.C/NIT: ' + _esc(CL.nit || ''));
    win.document.write('</div></div>');
    win.document.write('</div>'); // /firmas

    // ── PIE DE PÁGINA ──
    win.document.write('<div class="pie">');
    win.document.write('Impreso el ' + fechaImpresion + ' &bull; ' + _esc(EM.nombre || '') + ' &bull; NIT: ' + _esc(EM.nit || ''));
    win.document.write('</div>');

    win.document.write('</div>'); // /pagina
    win.document.write('</body></html>');

    win.document.title = 'Recibo ' + RC.consecutivo + ' - ' + (CL.nombre || '');
    win.document.close();
    win.focus();
    win.print();
    win.close();
};

// ── Utilidades ───────────────────────────────────────────────────────────────

/** Escapa HTML para evitar XSS en el documento generado */
var _esc = function(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
};

/** Formatea 'YYYY-MM-DD HH:MM:SS' → 'DD/MM/YYYY HH:MM' */
var _formatearFecha = function(fechaStr) {
    if (!fechaStr) return '';
    var partes = fechaStr.split(' ');
    if (partes.length < 1) return fechaStr;
    var fecha = partes[0].split('-');
    var hora  = partes.length > 1 ? partes[1].substring(0, 5) : '';
    if (fecha.length === 3) {
        return fecha[2] + '/' + fecha[1] + '/' + fecha[0] + (hora ? ' ' + hora : '');
    }
    return fechaStr;
};

/** Fecha actual formateada */
var _fechaActual = function() {
    var hoy = new Date();
    var d = String(hoy.getDate()).padStart(2, '0');
    var m = String(hoy.getMonth() + 1).padStart(2, '0');
    var y = hoy.getFullYear();
    var h = String(hoy.getHours()).padStart(2, '0');
    var i = String(hoy.getMinutes()).padStart(2, '0');
    return d + '/' + m + '/' + y + ' ' + h + ':' + i;
};
