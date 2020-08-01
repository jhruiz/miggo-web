/**
 * Se imprime la factura de venta
 * @returns {undefined}
 */
function imprimirFactura(){
    
    var tipoVenta = $('#tipoVenta').val() == '1' ? " - FV" : " - RMV";
    
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
    mywindow.document.write('<html><head>');
    mywindow.document.write('<style media=screen>body { font-family: Lucidatypewriter, monospace; font-size: 20px; } } </style>');
    mywindow.document.write('<style media=print>@page {margin: 5mm;} @page footer {page-break-after: always;} @page rotated {size: portrait} #tinfop {background-color:#FFF; font-family: Lucidatypewriter, monospace; font-size: 10px; } </style>');    
    mywindow.document.write('</head>');
    mywindow.document.write('<body>');
    mywindow.document.write('<div style="font-family:sans-serif; font-size:15px;">');
    mywindow.document.write($('#dvFacturas').html());  
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
    mywindow.document.write('<div style="margin-top:10px; margin-right:3px; float:left; text-align:justify; width:70%;">');
    mywindow.document.write($('#p_condCont').html());
    mywindow.document.write('</div>');
    mywindow.document.write('<div style="margin-top:5px; float:right; width:25%;">'); 
    mywindow.document.write($('#dvResolucion').html());
    mywindow.document.write('</div>');          
    mywindow.document.write('<div style="margin-top:5px; float:right; width:100%;">'); 
    mywindow.document.write($('#nota_factura').html());
    mywindow.document.write('</div>');          
    mywindow.document.write('</div>');          
    mywindow.document.write('</body></html>');
    mywindow.document.title = $('#cliName').val() + tipoVenta;
    mywindow.document.close(); 
    mywindow.focus();
    mywindow.print();
    mywindow.close();
}

/**
 * Se imprime la orden de trabajo
 * @returns {undefined}
 */
function imprimirOrden(){
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

$(function(){
    $('#conditions').hide();
    $('#contResol').hide();
    $('#conditions_ot').hide();
    $('#dvNota').hide();
});