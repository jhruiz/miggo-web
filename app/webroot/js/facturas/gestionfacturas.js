/**
 * Se imprime ticket de venta
 * @returns {undefined}
 */
function imprimirTicket() {

    console.log('entra por aqui');

    var tipoVenta = $('#tipoVenta').val() == '1' ? " - FV" : " - RMV";
    var mywindow = window.open('', 'PRINT', 'height=400, width=600');
    mywindow.document.write('<html><head>');
    mywindow.document.write('<style media=screen>body { font-family: Lucidatypewriter, monospace; font-size: 12px; } } </style>');
    mywindow.document.write('<style media=print>@page {margin: 5mm;} @page footer {page-break-after: always;} @page rotated {size: portrait} #tinfop {background-color:#FFF; font-family: Lucidatypewriter, monospace; font-size: 3px; } </style>');
    mywindow.document.write('</head>');
    mywindow.document.write('<body>');
    mywindow.document.write('<div style="font-family:sans-serif; font-size:10px;">');
    mywindow.document.write($('#dvTicket').html());   
    mywindow.document.write('<div style="margin-top:5px; float:right; width:100%;">');
    mywindow.document.write($('#dvNota').html());
    mywindow.document.write('</div>');
    mywindow.document.write('</body></html>');
    mywindow.document.title = $('#cliName').val() + tipoVenta;
    mywindow.document.close();
    mywindow.focus();
    mywindow.print();
    mywindow.close();
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