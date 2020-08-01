var opcAbonos = {
        autoOpen: false,
        modal: true,
        width: 500,
        height: 500,
        position: [400, 50],
        show: {
            duration: 400    
        },
        hide: function () {
        },
        close: function( event, ui){        
        },
        title: 'Abonos a factura'    
};

var dialogAbonos;

/**
 * se carga pop up para realizar abono
 * @returns {undefined}
 */
var abonosPrefactura = function(){
    var ttales = 0;
    $(".valor_con_iva").each(function() {
        ttales = (Number(ttales) + Number($(this).val()));
    });       
    
    ttales = ttales - parseInt($('.ttalAbonos').val());

    if(parseInt(ttales) > 0){
        $("#div_abono").load(
            $('#url-proyecto').val() + "abonofacturas/abonofactura",
            {ttales: ttales},
            function(){                                                            
                dialogAbonos=$("#div_abono").dialog(opcAbonos);
                dialogAbonos.dialog('open');
                $('.numericPrice').number(true);
            }
        );          
    }else{
        alert("No es posible realizar abonos.");
    }
   
};

/**
 * guarda el abono registrado por el usuario
 * @returns {undefined}
 */
function realizarAbono(){
    var ttalAbono = $('#ttalAbono').val();
    var tipopagoId = $('#tipopagos').val();
        
    //se obtiene el id de la prefactura para realizar el abono
    var prefacturaId = "";
    if (typeof($('#prefactId').val()) == "undefined"){
        prefacturaId = $('#prefacturaId').val();
    }else{
        prefacturaId = $('#prefactId').val();
    }
    
    if(parseInt(ttalAbono) > 0){
        $.ajax({
            url: $('#url-proyecto').val() + 'abonofacturas/abonoCliente',
            data: {prefacturaId: prefacturaId, ttalAbono: ttalAbono, tipopagoId: tipopagoId},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if(resp.resp){
                    
                    var abn = "";
                    var ttales = 0;
                    $(".valor_con_iva").each(function() {
                        ttales = Number(ttales) + Number($(this).val());
                    });
                    
                    var abonosFact = $('.ttalAbonos').val();
                    var ttalFinAbono = (parseInt(ttalAbono) + parseInt(abonosFact));
                    
                    abn += "<tr><th colspan='8' class='text-right'>Abonos</th>";
                    abn += "<th class='text-right'>" + formatNumber(ttalFinAbono) + "</th></tr>";
                    abn += "<tr><th colspan='8' class='text-right'>TOTAL</th>";
                    abn += "<th class='text-right'>" + formatNumber(ttales - parseInt(ttalFinAbono)) + "</th></tr>";
                    $('#tBodAbonos').html(abn);                                        
                    $('.ttalAbonos').attr('value', ttalFinAbono);
                    alert('Abono realizado de forma correcta.');
                    dialogAbonos.dialog('close');
                }else{
                    alert('No fue posible realizar el abono. Por favor, inténtelo nuevamente.');
                }
            }
        });          
    }else{
        alert("No ha ingresado un valor para el abono.");
    }    
}

$(function(){
    $('#btn_abonos').click(abonosPrefactura);
});