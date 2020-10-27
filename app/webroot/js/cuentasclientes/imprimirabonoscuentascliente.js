/**
 * Enviar abonos por wapp
 * @returns {undefined}
 */
var enviarAbonos = function() {
    var telCli = $('#clienteCel').val();
    if (telCli != "") {
        var link = "https://wa.me/57" + telCli + "?text=adjuntamos%20información%20de%20su%20interés";
        $('.wppSendPF').attr('href', link).attr('target', '_blank');
    } else {
        bootbox.alert('El cliente no tiene número de teléfono para el envío del mensaje.');
    }
};


var imprimirAbonos = function() {
    var cuentaClienteId = $('#ccId').val();

    $.ajax({
        url: $('#url-proyecto').val() + 'cuentasclientes/ajaxobtenerabonos',
        data: { cuentaClienteId: cuentaClienteId },
        type: "POST",
        success: function(data) {
            var dataAbono = JSON.parse(data);

            var nombreEmp = "";
            var repLegal = "";
            var nit = "";
            var telefono = "";
            var direccion = "";
            if (dataAbono.abonos['0'].F.factura == '1') {
                nombreEmp = dataAbono.subemp.Empresa.nombre;
                nit = dataAbono.subemp.Empresa.nit;
                telefono = dataAbono.subemp.Empresa.telefono1;
                direccion = dataAbono.subemp.Empresa.direccion;
            } else {
                nombreEmp = dataAbono.subemp.Relacionempresa.nombre;
                repLegal = dataAbono.subemp.Relacionempresa.representantelegal;
                nit = dataAbono.subemp.Relacionempresa.nit;
                telefono = dataAbono.subemp.Relacionempresa.telefono1;
                direccion = dataAbono.subemp.Relacionempresa.direccion;
            }


            var mywindow = window.open('', 'PRINT', 'height=400,width=600');
            mywindow.document.write('<html><head>');
            mywindow.document.write('<style media=screen>body { font-family: Lucidatypewriter, monospace; font-size: 20px; } } </style>');
            mywindow.document.write('<style media=print>@page {margin: 5mm;} @page footer {page-break-after: always;} @page rotated {size: portrait} #tinfop {background-color:#FFF; font-family: Lucidatypewriter, monospace; font-size: 10px; } </style>');
            mywindow.document.write('</head>');
            mywindow.document.write('<body>');
            mywindow.document.write('<div style="margin:0px; width:100%; font-family:sans-serif; font-size:15px;">');
            mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
            mywindow.document.write('<h4><b>' + nombreEmp + '</b></h4></div>');
            mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');

            if (repLegal != "") {
                mywindow.document.write('<h4><b>' + repLegal + '</b></h4>');
            }

            mywindow.document.write('</div>');
            mywindow.document.write('<div style="margin:0px; width:100%; float:left;">');
            mywindow.document.write('<div style="float:left; margin-top: 10px; width:50%" align="left">');
            mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
            mywindow.document.write('<b>Nit: </b>' + nit + '</div></div>');
            mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
            mywindow.document.write('<b>Teléfono: </b>' + telefono + '</div></div>');
            mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
            mywindow.document.write('<b>Dirección: </b>' + direccion + '</div></div></div>');
            mywindow.document.write('</div>');
            mywindow.document.write('<div style="width:100%; float:left; margin-top:10px" align="left">');
            mywindow.document.write('<div style="margin-top: 10px; float: left; width: 90%; aling-items: center; justify-content: center">');
            mywindow.document.write('<div style="width:100%; float:left; margin-top:10px" align="left">');
            mywindow.document.write('<b>Abonos </b></div><br><br>');
            mywindow.document.write('<table style="font-family:sans-serif; font-size:15px; border-collapse: collapse; width: 100%;"><thead>');
            mywindow.document.write('<tr><th align="left">Usuario</th><th align="left">Cliente</th>');
            mywindow.document.write('<th align="left">Fecha</th><th align="left">Valor</th></tr>');
            mywindow.document.write('</thead>');

            if (dataAbono.abonos.length > 0) {
                mywindow.document.write('<tbody>');
                var ttalAbono = 0;
                $.each(dataAbono.abonos, function(k, val) {
                    ttalAbono += parseInt(val.Abonofactura.valor);
                    mywindow.document.write('<tr>');
                    mywindow.document.write('<td align="left">' + (val.U.nombre) + '</td>');
                    mywindow.document.write('<td align="left">' + (val.CL.nombre) + '</td>');
                    mywindow.document.write('<td align="left">' + (val.Abonofactura.created) + '</td>');
                    mywindow.document.write('<td align="right">$' + (val.Abonofactura.valor).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') + '</td>');
                    mywindow.document.write('</tr>');
                });
                mywindow.document.write('<tr><td align="right" colspan="3"><b>Total</b></td><td align="right">$' + ttalAbono.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') + '</td><tr>');
                mywindow.document.write('</tbody>');
            }
            mywindow.document.write('</table>');
            mywindow.document.write('</div>');
            mywindow.document.write('</div>');
            mywindow.document.write('</body></html>');
            mywindow.document.title = dataAbono.abonos['0'].CL.nombre + " - Abonos";
            mywindow.document.close();
            mywindow.focus();
            mywindow.print();
            mywindow.close();

        }
    });

};



$(function() {
    $('.wppSendPF').click(enviarAbonos);
    $('#impAbono').click(imprimirAbonos);
});