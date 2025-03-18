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
            mywindow.document.write('<style>');
            mywindow.document.write('@page { size: auto; margin: 0; }');
            mywindow.document.write('body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }');
            mywindow.document.write('table { border-collapse: collapse; width: 100%; margin-bottom: 20px; font-size: 12px; }');
            mywindow.document.write('th { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }');
            mywindow.document.write('td { border: 1px solid #ddd; padding: 8px; text-align: right; font-size: 12px; }'); // Alineación por defecto a la derecha
            mywindow.document.write('th { background-color: #f5f5f5; font-weight: bold; }');
            mywindow.document.write('tr:nth-child(even) { background-color: #f9f9f9; }');
            mywindow.document.write('.align-left { text-align: left; }'); // Clase para alinear a la izquierda
            mywindow.document.write('@media print {');
            mywindow.document.write('  @page { margin: 0; }');
            mywindow.document.write('  body { margin: 1cm; }');
            mywindow.document.write('  .no-print, .no-print * { display: none !important; }');
            mywindow.document.write('}');
            mywindow.document.write('</style>');
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
                    mywindow.document.write('<td class="align-left">' + (val.U.nombre) + '</td>'); // Alineado a la izquierda
                    mywindow.document.write('<td class="align-left">' + (val.CL.nombre) + '</td>'); // Alineado a la izquierda
                    mywindow.document.write('<td class="align-left">' + (val.Abonofactura.created) + '</td>'); // Alineado a la izquierda
                    mywindow.document.write('<td align="right">$' + (val.Abonofactura.valor).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') + '</td>'); // Alineado a la derecha
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