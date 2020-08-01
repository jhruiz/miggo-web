/**
 * Imprimir la orden de entrada
 * @returns {undefined}
 */
var imprimirOrdenTrabajo = function(){
    var ordenId = $('#ordentrabajo_id').val();
    var vehiculoId = $('#vehiculo_id').val();
    var clienteId = $('#cliente_id').val();
    
    if(ordenId != "" && vehiculoId != "" && clienteId != ""){
        $.ajax({
            url: $('#url-proyecto').val() + 'ordentrabajos_partevehiculos/obtenerPartesOrdenTrabajo',
            data: {ordenId: ordenId},
            type: "POST",
            success: function(data) {
                var dataRem = JSON.parse(data);
                var fechaIngreso = dataRem.resp['0'].OT.fecha_ingreso != null ? dataRem.resp['0'].OT.fecha_ingreso : "";
                var fechaSalida = dataRem.resp['0'].OT.fecha_salida != null ? dataRem.resp['0'].OT.fecha_ingreso : "";
                var mywindow = window.open('', 'PRINT', 'height=400,width=600');
                mywindow.document.write('<html><head>');
                mywindow.document.write('<style media=screen>body { font-family: Lucidatypewriter, monospace; font-size: 20px; } } </style>');
                mywindow.document.write('<style media=print>@page {margin: 5mm;} @page footer {page-break-after: always;} @page rotated {size: portrait} #tinfop {background-color:#FFF; font-family: Lucidatypewriter, monospace; font-size: 10px; } </style>');    
                mywindow.document.write('</head>');
                mywindow.document.write('<body>');
                mywindow.document.write('<div style="margin:0px; width:100%; font-family:sans-serif; font-size:15px;">');
                mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');    
                mywindow.document.write('<h4><b>' + $('#nombre_empresa').val() +'</b></h4></div>');
                mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
                mywindow.document.write('<h4><b>Orden de Trabajo</b></h4></div>');
                mywindow.document.write($('#dv_info_emp').html());
                
                mywindow.document.write('<div style="margin:0px; width:100%; float:left;">');
                mywindow.document.write('<div style="float:left; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Cliente: </b>' + dataRem.resp['0'].C.nombre + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Teléfono: </b>' + dataRem.resp['0'].C.telefono + "/" + dataRem.resp['0'].C.celular + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Moto/Placa: </b>' + dataRem.resp['0'].V.placa + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Kilometraje: </b>' + dataRem.resp['0'].OT.kilometraje + '</div></div>');
                mywindow.document.write('</div>');
                
                
                mywindow.document.write('<div style="float:right; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>C.C: </b>' + dataRem.resp['0'].C.nit + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Dirección: </b>' + dataRem.resp['0'].C.direccion + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Linea: </b>' + dataRem.resp['0'].MV.descripcion + ' - ' + dataRem.resp['0'].V.linea + '</div></div>');                                                               
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Mecánico: </b>' + $("#OrdentrabajoUsuarioId option:selected").text() + '</div></div>');                                
                mywindow.document.write('</div></div>');                  
                                
                mywindow.document.write('<div style="width:100%; float:left; margin-top:20px" align="left">');    
                mywindow.document.write('<b> Orden de Trabajo No. </b>' + dataRem.resp['0'].OT.codigo +'</div>');                 
                mywindow.document.write('<div style="width:100%; float:left; margin-top:10px" align="left">');    
                mywindow.document.write('<b>Ubicación: </b>' + $('#OrdentrabajoPlantaservicioId option:selected').text() + '</div>'); 
                mywindow.document.write('<div style="width:100%; float:left; margin-top:10px" align="left">');    
                mywindow.document.write('<b>Fecha de Ingreso: </b>' + fechaIngreso + " "); 
                mywindow.document.write('<b>Fecha de Salida: </b>' + fechaSalida  + '</div>'); 

                //partes del vehiculo y estado
                mywindow.document.write('<div style="width:100%; float:left; margin-top:20px" align="center">');     
                mywindow.document.write('<b>PARTES DEL VEHICULO</b></div>');                 
                mywindow.document.write('<div style="margin-top: 20px; float: left; width: 100%; aling-items: center; justify-content: center">');                
                mywindow.document.write('<table style="font-family:sans-serif; font-size:15px;"><thead>');                
                mywindow.document.write('<tr><th align="left">PARTE</th><th align="left">ESTADO</th>');                
                mywindow.document.write('<th align="left">PARTE</th><th align="left">ESTADO</th></tr>');                
                
                mywindow.document.write('</thead>');
                
                if(dataRem.resp.length > 0){
                    mywindow.document.write('<tbody>');
                    var j = 0;
                    $.each(dataRem.resp, function(k, val){
                        if(j == 0){
                            mywindow.document.write('<tr><td>');
                        }else{
                            mywindow.document.write('<td>');
                        }
                        
                        mywindow.document.write('<b>' + dataRem.resp[k].PV.descripcion + '</b></td>');
                        mywindow.document.write('<td>' + dataRem.resp[k].EP.descripcion);
                        
                        if(j == 1){
                            mywindow.document.write('</td></tr>');
                            j = 0;
                        }else{
                            mywindow.document.write('</td>');
                            j++;                            
                        }
                    });
                    mywindow.document.write('</tbody>');
                }                  
         
                mywindow.document.write('</table>');                
                mywindow.document.write('</div>');
                
                //observaciones
                mywindow.document.write('<div style="width:100%; float:left; margin-top:20px" align="left">');    
                mywindow.document.write('<b>OBSERVACIONES CLIENTE: </b></div>');                
                mywindow.document.write('<div style="width:100%; float:left; margin-top:0px" align="left"><small>');    
                mywindow.document.write($('#OrdentrabajoObservacionesCliente').val() +'</small></div>'); 
                
                mywindow.document.write('<div style="width:100%; float:left; margin-top:20px" align="left">');    
                mywindow.document.write('<b>OBSERVACIONES MECANICO: </b></div>');                
                mywindow.document.write('<div style="width:100%; float:left; margin-top:0px" align="left"><small>');    
                mywindow.document.write($('#OrdentrabajoObservacionesUsuario').val() +'</small></div>');                 
                
                //suministros
                mywindow.document.write('<div style="width:100%; float:left; margin-top:20px" align="center">');     
                mywindow.document.write('<b>REPUESTOS/SUMINISTROS</b></div>');                 
                mywindow.document.write('<div style="margin-top: 20px; float: left; width: 100%; aling-items: center; justify-content: center">');                
                mywindow.document.write('<table style="font-family:sans-serif; font-size:15px;"><thead>');                
                mywindow.document.write('<tr><th align="left">NOMBRE</th><th align="left">CANTIDAD</th></tr>');                
                
                mywindow.document.write('</thead>');
                
                if(dataRem.resp.length > 0){
                    mywindow.document.write('<tbody>');
                    var j = 0;
                    $.each(dataRem.suministros, function(k, val){                        
                        mywindow.document.write('<tr><td>' + dataRem.suministros[k].P.descripcion + '</td>');
                        mywindow.document.write('<td align="right">' + dataRem.suministros[k].OrdentrabajosSuministro.cantidad + "</td></tr>");                        
                    });
                    mywindow.document.write('</tbody>');
                }                  
         
                mywindow.document.write('</table>');                
                mywindow.document.write('</div>'); 
                
                //emisor y receptor
                mywindow.document.write('<div style="width:80%;">');
                mywindow.document.write('<div style="margin-top:20px; float:left;">');
                mywindow.document.write("<b>EMISOR: </b>" + $('#nombre_empresa').val() + "<br>");
                mywindow.document.write("________________________________<br>");
                mywindow.document.write("<b>Nit: </b>" + $('#nit_empresa').val());
                mywindow.document.write('</div>');
                mywindow.document.write('<div style="margin-top:20px; float:right;">');    
                mywindow.document.write("<b>CLIENTE: </b>" + dataRem.resp['0'].C.nombre + "<br>");
                mywindow.document.write("________________________________<br>");
                mywindow.document.write("<b>C.C: </b>" + dataRem.resp['0'].C.nit);
                mywindow.document.write('</div>'); 
                mywindow.document.write('</div>');   
                
                //condiciones
                mywindow.document.write('<div style="margin-top:30px; float:left; width:100%;">');    
                mywindow.document.write($('#p_condCont_ot').html());
                mywindow.document.write('</div>');                                 
                mywindow.document.write('</div>');          
                mywindow.document.write('</body></html>');
                mywindow.document.title = dataRem.resp['0'].C.nombre + " - RM";
                mywindow.document.close(); 
                mywindow.focus();
                mywindow.print();
                mywindow.close();             
  
            }
        });          
    }else{
        bootbox.alert('Asegúrese de haber seleccionado el vehículo y el cliente.');
    }
};

/**
 * Imprimir la orden de entrada
 * @returns {undefined}
 */
var imprimirOrdenEntrada = function(){
    var ordenId = $('#ordentrabajo_id').val();
    var vehiculoId = $('#vehiculo_id').val();
    var clienteId = $('#cliente_id').val();
    
    if(ordenId != "" && vehiculoId != "" && clienteId != ""){
        $.ajax({
            url: $('#url-proyecto').val() + 'ordentrabajos_partevehiculos/ajaxObtenerExtras',
            data: {ordenId: ordenId},
            type: "POST",
            success: function(data) {
                var dataRem = JSON.parse(data);
                var mywindow = window.open('', 'PRINT', 'height=400,width=600');
                mywindow.document.write('<html><head>');
                mywindow.document.write('<style media=screen>body { font-family: Lucidatypewriter, monospace; font-size: 20px; } } </style>');
                mywindow.document.write('<style media=print>@page {margin: 5mm;} @page footer {page-break-after: always;} @page rotated {size: portrait} #tinfop {background-color:#FFF; font-family: Lucidatypewriter, monospace; font-size: 10px; } </style>');    
                mywindow.document.write('</head>');
                mywindow.document.write('<body>');
                mywindow.document.write('<div style="margin:0px; width:100%; font-family:sans-serif; font-size:15px;">');
                mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');    
                mywindow.document.write('<h4><b>' + $('#nombre_empresa').val() +'</b></h4></div>');
                mywindow.document.write('<div style="width:100%; float:left; margin:0px" align="center">');
                mywindow.document.write('<h4><b>Remisión de Entrada</b></h4></div>');
                mywindow.document.write($('#dv_info_emp').html());
                
                mywindow.document.write('<div style="margin:0px; width:100%; float:left;">');
                mywindow.document.write('<div style="float:left; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Cliente: </b>' + dataRem.resp['0'].C.nombre + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Teléfono: </b>' + dataRem.resp['0'].C.telefono + "/" + dataRem.resp['0'].C.celular + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Placa: </b>' + dataRem.resp['0'].V.placa + '</div></div></div>');                
                mywindow.document.write('<div style="float:right; margin-top: 10px; width:50%" align="left">');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>C.C: </b>' + dataRem.resp['0'].C.nit + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Dirección: </b>' + dataRem.resp['0'].C.direccion + '</div></div>');
                mywindow.document.write('<div style="margin: 2px; float: left; width: 100%;"><div style="margin: 0px; float: left; width: 100%;">');
                mywindow.document.write('<b>Linea: </b>' + dataRem.resp['0'].MV.descripcion + ' - ' + dataRem.resp['0'].V.linea + '</div></div></div>');                
                mywindow.document.write('</div>');                  
                mywindow.document.write('<div style="width:100%; float:left; margin-top:2px" align="left">');    
                mywindow.document.write('<b>Mecánico: </b>' + $("#OrdentrabajoUsuarioId option:selected").text() +'</div>');                 
                mywindow.document.write('<div style="width:100%; float:left; margin-top:20px" align="left">');    
                mywindow.document.write('<b> Remisión de Entrada No. ' + dataRem.resp['0'].OT.codigo +'</b></div>');                 
                mywindow.document.write('<div style="width:100%; float:left; margin-top:10px" align="left">');    
                mywindow.document.write('<b>Ubicación: </b>' + $('#OrdentrabajoPlantaservicioId option:selected').text() + '</div>'); 
                
                mywindow.document.write('<div style="margin-top: 20px; float: left; width: 100%; aling-items: center; justify-content: center">');
                
                mywindow.document.write('<table style="font-family:sans-serif; font-size:15px; border-collapse: collapse; width: 100%;"><thead>');
                
                mywindow.document.write('<tr><th align="left">Parte</th><th align="left">Aplica</th></tr>');                
                
                mywindow.document.write('</thead>');
                
                if(dataRem.resp.length > 0){
                    mywindow.document.write('<tbody>');
                    $.each(dataRem.resp, function(k, val){
                        
                        var apl = "NO";
                        if(val.EP.descripcion != "NO APLICA"){
                            apl = "SI";
                        }
                        
                        mywindow.document.write('<tr>');
                        mywindow.document.write('<td align="left">' + (val.PV.descripcion).toUpperCase() + '</td>');
                        mywindow.document.write('<td align="left">' + apl + '</td>');
                        mywindow.document.write('</tr>');
                    });
                    mywindow.document.write('</tbody>');
                }                  
         
                mywindow.document.write('</table>');                
                mywindow.document.write('</div>');
                
                mywindow.document.write('<div style="width:100%; float:left; margin-top:20px" align="left">');    
                mywindow.document.write('<b>OBSERVACIONES MECANICO: </b></div>');                
                mywindow.document.write('<div style="width:100%; float:left; margin-top:0px" align="left">');    
                mywindow.document.write($('#OrdentrabajoObservacionesUsuario').val() +'</div>');                
                
                mywindow.document.write('<div style="width:100%; float:left; margin-top:20px" align="left">');    
                mywindow.document.write('<b>OBSERVACIONES CLIENTE: </b></div>');                
                mywindow.document.write('<div style="width:100%; float:left; margin-top:0px" align="left">');    
                mywindow.document.write($('#OrdentrabajoObservacionesCliente').val() +'</div>'); 
                
                
                mywindow.document.write('<div style="margin-top: 10px; float: left; width: 90%; aling-items: center; justify-content: center">');                                    
                mywindow.document.write('<div style="width:100%; float:left; margin-top:10px" align="left">');    
                mywindow.document.write('<b>Abonos </b></div>');
                mywindow.document.write('<table style="font-family:sans-serif; font-size:15px; border-collapse: collapse; width: 100%;"><thead>');                
                mywindow.document.write('<tr><th align="left">Usuario</th><th align="left">Cliente</th>');                                
                mywindow.document.write('<th align="left">Fecha</th><th align="left">Valor</th></tr>');                                
                mywindow.document.write('</thead>');                
                
                if(dataRem.abonos.length > 0){                    
                    mywindow.document.write('<tbody>');
                    var ttalAbono = 0;
                    $.each(dataRem.abonos, function(k, val){             
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
                         
                mywindow.document.write($('#aclaraciones').html());          
                mywindow.document.write('</div>');          
                mywindow.document.write('</body></html>');
                mywindow.document.title = dataRem.resp['0'].C.nombre + " - RM";
                mywindow.document.close(); 
                mywindow.focus();
                mywindow.print();
                mywindow.close();             
  
            }
        });          
    }else{
        bootbox.alert('Asegúrese de haber seleccionado el vehículo y el cliente.');
    }
};

$(function(){
    $('#btn_orden_trabajo').click(imprimirOrdenTrabajo);
    $('#btn_orden_entrada').click(imprimirOrdenEntrada);
    $('#conditions_ot').hide();
    
});


