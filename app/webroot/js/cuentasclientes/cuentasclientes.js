var opcDialogCuentaPorCobrar = {
        autoOpen: false,
        modal: true,
        width: 600,
        height: 600,
        position: [400, 50],
        show: {
            duration: 400    
        },
        hide: function () {
        },
        close: function( event, ui){           
        },
        title: 'Cuentas por Cobrar'    
};

var dialogCuentaPorCobrar;

function pagarCuenta(cuentapendienteId){
    $("#div_pagarcuenta").load(
        $('#url-proyecto').val() + "cuentasclientes/obtenercuentacliente",
        {pagoId:cuentapendienteId},
        function(){                                                            
            dialogCuentaPorCobrar=$("#div_pagarcuenta").dialog(opcDialogCuentaPorCobrar);
            dialogCuentaPorCobrar.dialog('open');
        }
    ); 
}

function validarPagoCuenta(){
    var ttalCuenta = $('#totalCuenta').val();
    var ttalPago = $('#totalPago').val();
    
    if(Number(ttalCuenta) < Number(ttalPago)){
        bootbox.alert('El total a pagar no puede ser mayor al total de la deuda');
        $('#totalPago').val(ttalCuenta);
        $('#totalRestante').val("0");
    }else{
        var cuentaFinal = ttalCuenta - ttalPago;
        $('#totalRestante').val(cuentaFinal);
    }
}

function pagarCuentaPendiente(){
    var mensaje = "";
    
    mensaje = validarDatosPago();
    if(mensaje != ""){
        bootbox.alert(mensaje);
    }else{
        var ttalPago = $('#totalPago').val();
        var tipopago_id = $('#tipopagoId').val(); 
        var cuentapendiente = $('#cuentapendiente_id').val();

        $.ajax({
            type: 'POST',
            url: $('#url-proyecto').val() + 'cuentasclientes/pagarcuentacliente',
            data: {ttalPago: ttalPago, tipopago: tipopago_id, cuentapendiente:cuentapendiente},
            success: function(data) {
                var respuesta = JSON.parse(data);
                if(respuesta.resp){
                    bootbox.alert('El pago se ha realizado correctamente.', function (){
                        dialogCuentaPorCobrar.dialog('close');
                        location.reload(); 
                    });                    
                }else{
                    bootbox.alert('No se pudo realizar el pago. Por favor, inténtelo de nuevo', function(){
                        dialogCuentaPorCobrar.dialog('close');
                    });
                }
            }
        });
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

function eliminarCuenta(id){

    bootbox.confirm("¿Esta seguro que desea eliminar el registro?", function(result){ 
    	if(result){
	        $.ajax({
	            type: 'POST',
	            url: $('#url-proyecto').val() + 'cuentasclientes/eliminarcuentacliente',
	            data: {id:id},
	            success: function(data) {
	            	if(data == '1'){
		            bootbox.alert("La cuenta por cobrar ha sido eliminada!", function(){ location.reload(); });			    
			}else{
			    alert("El registro no fue eliminado. Por favor, intentelo de nuevo");
			}
	            }
	        });
    	}
    });
}

function verAbonos(id){
    window.location.href = $('#url-proyecto').val() + 'cuentasclientes/verabonos/' + id;;    
}

$( function() {
    $('.numberPrice').number(true);
});
