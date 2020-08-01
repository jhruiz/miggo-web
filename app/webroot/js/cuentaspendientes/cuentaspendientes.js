var opcDialogCuentaPorPagar = {
        autoOpen: false,
        modal: true,
        width: 600,
        height: 550,
        position: [400, 50],
        show: {
            duration: 400    
        },
        hide: function () {
        },
        close: function( event, ui){           
        },
        title: 'Cuentas por Pagar'    
};

var dialogCuentaPorPagar;

//funcion que abre un pop up para crear un nuevo producto
function datosCuentasPendientes(cuentapendienteId){
    $("#div_pagarcuenta").load(
        $('#url-proyecto').val() + "cuentaspendientes/obtenercuentapendiente",
        {pagoId:cuentapendienteId},
        function(){                                                            
            dialogCuentaPorPagar=$("#div_pagarcuenta").dialog(opcDialogCuentaPorPagar);
            dialogCuentaPorPagar.dialog('open');
        }
    );   
}

function pagarCuentaPendienteProv(){
    var mensaje = "";    
    mensaje = validarDatosPago();
    if(mensaje != ""){
        bootbox.alert(mensaje);        
    }else{
        var ttalPago = $('#totalPago').val();
        var cuenta = $('#cuentaId').val(); 
        var cuentapendiente = $('#cuentapendiente_id').val();
        
        $.ajax({
            type: 'POST',
            url: $('#url-proyecto').val() + 'cuentaspendientes/pagarcuentapendiente',
            data: {ttalPago: ttalPago, cuenta: cuenta, cuentapendiente:cuentapendiente},
            success: function(data) {
                var respuesta = JSON.parse(data);
                if(respuesta.resp){
                    bootbox.alert('El pago se ha realizado correctamente.', function (){
                        dialogCuentaPorPagar.dialog('close');
                        location.reload(); 
                    });                    
                }else{
                    bootbox.alert('No se pudo realizar el pago. Por favor, inténtelo de nuevo', function(){
                        dialogCuentaPorPagar.dialog('close');
                    });
                }
            }
        });
    }
}

function validarPagoPendiente(){
    var ttalCuenta = $('#totalCuenta').val();
    var ttalPago = $('#totalPago').val();
    $('#cuentaId').val("");
    
    if(Number(ttalCuenta) < Number(ttalPago)){
        bootbox.alert('El total a pagar no puede ser mayor al total de la deuda');
        $('#totalPago').val(ttalCuenta);
        $('#totalRestante').val("0");
    }else if($('#totalPago').val() == ""){
        $('#totalPago').val(ttalCuenta);
        $('#totalRestante').val("0");        
    }else{
        var cuentaFinal = ttalCuenta - ttalPago;
        $('#totalRestante').val(cuentaFinal);
    }    
}

function validarDatosPago(){
    var ttalPago = $('#totalPago').val();
    var cuenta = $('#cuentaId').val();
    var mensaje = "";
    if(ttalPago == ""){
        mensaje = "- Debe ingrear un monto a pagar.<br>";
    }
    
    if(cuenta == ""){
        mensaje += "- Debe seleccionar una cuenta para asignar el pago.";
    }
    return mensaje;
}

function validarSaldoCuenta(){   
    var cuenta = $('#cuentaId').val();
    var ttalPago = $('#totalPago').val();
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'cuentaspendientes/validarsaldoencuenta',
        data: {ttalPago: ttalPago, cuenta: cuenta},
        success: function(data) {
            var respuesta = JSON.parse(data);
            if(!respuesta.resp){
                bootbox.alert('No hay suficiente saldo en la cuenta para realizar el pago.', function(){
                    $('#cuentaId').val("");
                });
                
            }
        }
    });    
}

function eliminarCuentaPendiente(id){
    bootbox.confirm("¿Esta seguro que desea eliminar el registro?", function(result){ 
    	if(result){
	        $.ajax({
	            type: 'POST',
	            url: $('#url-proyecto').val() + 'cuentaspendientes/eliminarcuentapendiente',
	            data: {id:id},
	            success: function(data) {
	            	if(data == '1'){
		            bootbox.alert("La cuenta por pagar ha sido eliminada!", function(){ location.reload(); });			    
			}else{
			    alert("El registro no fue eliminado. Por favor, intentelo de nuevo");
			}
	            }
	        });
    	}
    });
}

$( function() {
    $('.numericPrice').number(true);
});

