/**
 * Se imprime ticket de venta
 * @returns {undefined}
 */
function imprimirTicket() {
    var tipoVenta = $('#tipoVenta').val() == '1' ? " - FV" : " - RMV";
    var cliName = $('#cliName').val() || "Cliente";

    // Abrimos la ventana con un ancho que simula el ticket
    var mywindow = window.open('', 'PRINT', 'height=600, width=400');

    mywindow.document.write('<html><head><title>' + cliName + tipoVenta + '</title>');
    mywindow.document.write('<style>');

    
    mywindow.document.write('.linea-ticket { border-top: 1px dashed #000 !important; width: 100%; }');
    
    // Configuración base para el ticket
    mywindow.document.write('body { margin: 0; padding: 0; font-family: sans-serif; font-size: 12px; width: 80mm; color: #000; }');
    
    // Eliminación de encabezados/pies de página del navegador (Fecha, URL, etc.)
    mywindow.document.write('@page { size: 80mm auto; margin: 0mm; }'); 
    
    mywindow.document.write('@media print {');
    // 72mm es el ancho imprimible real ideal para papel de 80mm
    mywindow.document.write('  body { margin: 0; width: 72mm; padding: 2mm; }'); 
    mywindow.document.write('  .no-print { display: none; }');
    mywindow.document.write('}');
    
    // Estilos de la tabla de productos para que use todo el ancho
    mywindow.document.write('table { width: 100%; border-collapse: collapse; margin-bottom: 5px; table-layout: fixed; }');
    mywindow.document.write('th, td { font-size: 11px; padding: 2px 0; word-wrap: break-word; }');
    
    // Estilo para líneas punteadas divisorias
    mywindow.document.write('.separador { border-top: 1px dashed #000; margin: 8px 0; padding-top: 5px; }');
    
    mywindow.document.write('img { max-width: 100%; height: auto; display: block; margin: 0 auto; padding-bottom: 10px; }');
    mywindow.document.write('</style></head><body>');

    // Contenedor principal
    mywindow.document.write('<div style="width: 100%;">');
    
    /* IMPORTANTE: Solo llamamos a #dvTicket. 
       Asegúrate de que en tu PHP el div #dvNota o la info de Miggo 
       estén DENTRO del div #dvTicket al final.
    */
    mywindow.document.write($('#dvTicket').html()); 
    mywindow.document.write($('#dvNota').html()); 
    
    mywindow.document.write('</div>');

    mywindow.document.write('</body></html>');

    mywindow.document.close();
    
    // Tiempo de espera para asegurar que el logo cargue antes de lanzar el cuadro de impresión
    setTimeout(function() {
        mywindow.focus();
        mywindow.print();
        mywindow.close();
    }, 500);
}

/**
 * Se imprime la factura de venta
 * @returns {undefined}
 */
function imprimirFactura() {

    var tipoVenta = $('#tipoVenta').val() == '1' ? " - FV" : " - RMV";

    var mywindow = window.open('', 'PRINT', 'height=400,width=600');

    // Escribir el contenido HTML en la nueva ventana
    mywindow.document.write('<html><head>');
    
    // Estilos para ocultar el título y la URL en la impresión
    mywindow.document.write('<style>');
    mywindow.document.write('@page { size: auto; margin: 0; }'); // Eliminar márgenes de la página
    mywindow.document.write('body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }'); // Tamaño de fuente base
    mywindow.document.write('table { border-collapse: collapse; width: 100%; margin-bottom: 20px; font-size: 8px !important; }'); // Tamaño de fuente para la tabla
    mywindow.document.write('th { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 8px !important; }'); // Tamaño de fuente para celdas
    mywindow.document.write('td { border: 1px solid #ddd; padding: 8px; text-align: right; font-size: 8px !important; }'); // Tamaño de fuente para celdas
    mywindow.document.write('th { background-color: #f5f5f5; font-weight: bold; }');
    mywindow.document.write('tr:nth-child(even) { background-color: #f9f9f9; }');
    mywindow.document.write('@media print {');
    mywindow.document.write('  @page { margin: 0; }'); // Eliminar márgenes en la impresión
    mywindow.document.write('  body { margin: 1cm; }'); // Ajustar márgenes del contenido
    mywindow.document.write('  .no-print, .no-print * { display: none !important; }'); // Ocultar elementos no deseados
    mywindow.document.write('}');
    mywindow.document.write('</style>');
    
    // Configurar el título de la ventana (esto no se imprimirá)
    mywindow.document.write('<title>' + $('#cliName').val() + tipoVenta + '</title>');
    mywindow.document.write('</head><body>');
    
    // Contenido que deseas imprimir
    mywindow.document.write('<div style="font-family: Arial, sans-serif; font-size: 8px !important;">');
    mywindow.document.write($('#dvFacturas').html()); // Contenido de la tabla
    mywindow.document.write('</div>');
    
    mywindow.document.write('<div style="margin-top: 20px; margin-right: 3px; float: left; text-align: justify; width: 70%; font-size: 8px !important;">');
    mywindow.document.write($('#p_condCont').html()); // Contenido adicional
    mywindow.document.write('</div>');
    
    mywindow.document.write('</body></html>');
    
    // Cerrar el documento y enfocar la ventana
    mywindow.document.close();
    mywindow.focus();
    
    // Imprimir y cerrar la ventana
    mywindow.print();
    mywindow.close();
}

/**
 * Se imprime la orden de trabajo
 * @returns {undefined}
 */
function imprimirOrden() {

    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
    mywindow.document.write('<html><head>');
    mywindow.document.write('<style media=screen>body { font-family: Lucidatypewriter, monospace; font-size: 20px; } } </style>');
    mywindow.document.write('<style media=print>@page {margin: 5mm;} @page footer {page-break-after: always;} @page rotated {size: portrait} #tinfop {background-color:#FFF; font-family: Lucidatypewriter, monospace; font-size: 10px; } </style>');
    mywindow.document.write('</head>');
    mywindow.document.write('<body>');
    mywindow.document.write('<div style="font-family:sans-serif; font-size:15px;">');
    mywindow.document.write($('#ordenTrabajo').html());
    mywindow.document.write('<div style="width:80%;">');
    mywindow.document.write('<div style="margin-top:20px; float:left;">');
    mywindow.document.write("<b>EMISOR: </b>" + $('#emisor').val() + "<br>");
    mywindow.document.write("________________________________<br>");
    mywindow.document.write("<b>Nit: </b>" + $('#emisorNit').val());
    mywindow.document.write('</div>');
    mywindow.document.write('<div style="margin-top:20px; float:right;">');
    mywindow.document.write("<b>CLIENTE: </b>" + $('#cliName').val() + "<br>");
    mywindow.document.write("________________________________<br>");
    mywindow.document.write("C.C: " + $('#cliNit').val());
    mywindow.document.write('</div>');
    mywindow.document.write('</div>');
    mywindow.document.write('<div style="margin-top:10px; float:left; width:100%;">');
    mywindow.document.write($('#p_condCont_ot').html());
    mywindow.document.write('</div>');
    mywindow.document.write('</div>');
    mywindow.document.write('</body></html>');
    mywindow.document.title = $('#cliName').val() + " - OT";
    mywindow.document.close();
    mywindow.focus();
    mywindow.print();
    mywindow.close();

}
var generarAlerta = function() {
    //valida que tenga todos los datos ingresados
    var vehiculo = $('#vehiculo_id').val();
    var factura = $('#facturaId').val();
    var cliente = $('#cliente_id').val();
    var orden = $('#ordentrabajo_id').val();
    var fechacumple = $('fechacumple').val();

    if (vehiculo != '' && cliente != '' && orden != '') {
        window.open($('#url-proyecto').val() + 'alertaordenes/gestionalertasfac/' + $('#facturaId').val());
    } else {
        bootbox.alert('Asegúrese de gestionar la orden de trabajo completa e inténtelo nuevamente.');
    }

}
var generarAlertaFactura = function() {
    //valida que tenga todos los datos ingresados
    var vehiculo = $('#vehiculo_id').val();
    var factura = $('#facturaId').val();
    var cliente = $('#cliente_id').val();
    var orden = $('#ordentrabajo_id').val();
    var fechacumple = $('fechacumple').val();

    if (vehiculo != '' && cliente != '' && orden != '') {
        window.open($('#url-proyecto').val() + 'alertaordenes/gestionalertasfac/' + $('#facturaId').val());
    } else {
        bootbox.alert('Asegúrese de gestionar la orden de trabajo completa e inténtelo nuevamente.');
    }

}

var generarQRDian = function() {
    $('.qr_imp').qrcode({
        render: 'image',
        minVersion: 1,
        maxVersion: 40,
        ecLevel: 'L',
        left: 0,
        top: 0,
        size: 100,
        fill: '#000',
        background: null,
        text: $("#dianQRStr").val(),
        radius: 0,
        quiet: 0,
        mode: 0,
        mSize: 0.1,
        mPosX: 0.5,
        mPosY: 0.5,
        label: 'no label',
        fontname: 'sans',
        fontcolor: '#000',
        image: null,
        download: 1
    }); 
}

$(function() {
    $('#conditions').hide();
    $('#conditions_ot').hide();
    // $('#dvNota').hide();
    $('#dvTicket').hide();
    $('#btn_alerta').click(generarAlerta);
    $('#btn_alertaFactura').click(generarAlertaFactura);

    generarQRDian();
});