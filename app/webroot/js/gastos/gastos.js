function validarEstadoCuenta(){
    var montoGasto = $('#GastoValor').val();
    if(montoGasto == "" || typeof(montoGasto) == "undefined"){
        bootbox.alert('Debe ingresar un monto para el gasto');
        $('#GastoCuentaId').val("");
    }else{
        var cuentaId = $('#GastoCuentaId').val();
        $.ajax({
            url: $('#url-proyecto').val() + 'cuentas/ajaxvalidarmontocuenta',
            data: {montoGasto: montoGasto, cuentaId: cuentaId},
            type: "POST",
            success: function(data) {    
                var respuesta = JSON.parse(data);
                if(!respuesta.resp){
                    bootbox.alert('No hay suficiente saldo en la cuenta para descontar el gasto.');
                    $('#GastoCuentaId').val("");
                }else if($('#GastoTraslado').is(":checked")){
                    obtenerCuentasDestino();
                }
            }
        }); 
    }
}

var obtenerCuentasDestino = function(){
    var cuentaOrigenId = $('#GastoCuentaId').val();
    var empresa_id = $('#GastoEmpresaId').val();
    console.log(empresa_id);
    if(cuentaOrigenId != ""){
        $.ajax({
            url: $('#url-proyecto').val() + 'cuentas/ajaxobtenercuentadestino',
            data: {cuentaOrigenId: cuentaOrigenId, empresaId: empresa_id},
            type: "POST",
            success: function(data) {    
                var respuesta = JSON.parse(data);
                var resp = "";
                
                for(var i in respuesta.data){
                    resp += "<option value=" + i + ">" + respuesta.data[i] + "</option>";
                }
                $('#GastoCuentadestino').html(resp);
            }
        });           
    }else{
        bootbox.alert("Debe seleccionar una cuenta origen.");
        $('#GastoTraslado').prop("checked", false);
        $('.cuentadestino').hide();
    }
 
};

/**
 * Muestra el campo de cuenta destino y llama la funcion para las select de las cuentas
 * @returns {undefined}
 */
var trasladoCuenta = function(){

    if($('#GastoTraslado').is(":checked")){
        $('.cuentadestino').show();
        obtenerCuentasDestino();
    }else{
        $('.cuentadestino').hide();
    }
};

function restaurarCuenta(){    
    $('#GastoCuentaId').val("");
}

$( function() {
    $('.numberPrice').number(true);
    $('.cuentadestino').hide();
    $('#GastoTraslado').change(trasladoCuenta);
});

