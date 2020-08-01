//con el onReady se le aplica el datapicker a los campos de tipo feha
$(function() {
    $('#LicenciasEmpresaLicenciaId').val("");
    
    $(".date").datepicker({dateFormat: 'yy-mm-dd'});
    $(".date").datepicker("option", "showAnim", "slideDown");
    
    
    /*Generar fecha fin de la licencia basados en la fecha inicial y los días de la licencia*/
    var cambioLicencia = $('#LicenciasEmpresaLicenciaId');    
    $(cambioLicencia).change(function(e) {
        calcularFechaFinLic();
    });
    
    /*Generar el código con el cual se identifica la licencia del usuario*/
    var generarCodigo = $('#LicenciasEmpresaEmpresaId');
    $(generarCodigo).change(function(){
        generarCodigoLicencia();
    });
    
});

/*Se calcula la fecha en la que caduca la licencia*/
function calcularFechaFinLic(){
    var fechaInicio = $('#fechaInicio').val();
    var mensaje = "";
    mensaje = validarFechaInicioLic(fechaInicio);
    if(mensaje == ""){
        var diasLicencia = $('#LicenciasEmpresaLicenciaId option:selected').html(); 
        var fechaFin = sumarFecha(diasLicencia, fechaInicio);  
        $('#LicenciasEmpresaFechafin').val(fechaFin);
    }else{
        bootbox.alert(mensaje);
        $('#LicenciasEmpresaLicenciaId').val("");
        mensaje == "";            
    }    
}

/*Se valida que el usuario haya seleccionado la fecha inicial*/
function validarFechaInicioLic(fechaInicio){
    var mensaje = "";    
    if(fechaInicio == ""){
        mensaje = "Debe seleccionar la fecha inicial para la licencia.";
    }   
    return mensaje;    
}

//

/*Se genera el código con el cual se va identificar la licencia para un usuario en particular*/
function generarCodigoLicencia(){
    var fechaInicio = $('#fechaInicio').val().replace(/-/g, "");
    var fechaFin = $('#LicenciasEmpresaFechafin').val().replace(/-/g, "");
    var diasLicencia = $('#LicenciasEmpresaLicenciaId option:selected').html();
    var cantidadLic = $('#LicenciasEmpresaCantidad').val();
    var usuarioId = $('#LicenciasEmpresaEmpresaId').val();
    
    var codigo = fechaInicio + "_" + fechaFin + "_" + diasLicencia + "_" + cantidadLic + "_" + usuarioId;
    
    $('#LicenciasEmpresaCodigo').val(codigo);
}


function sumarFecha (dias, fecha){
    var fechaIng = new Date(fecha);
    var fechaFin = new Date(fechaIng.getTime() + (dias * 24 * 3600 * 1000));
    
    var anno = fechaFin.getFullYear();
    var mes = fechaFin.getMonth()+1;
    //var dia = fechaFin.getDate()+1;
    //var mes = ((fechaFin.getMonth() < 10 ? '0' : '') + fechaFin.getMonth());
    var dia = ((fechaFin.getDate() < 10 ? '0' : '') + fechaFin.getDate());    
    
    if (mes < 10){
    	var comp = '0';
    }else{
    	var comp = '';
    }
    
    
    var fechaSalida = anno + "-" + comp + mes + "-" + dia;
    
    return fechaSalida;
 }
 
 //realiza el cierre de las cajas
 function facturarCerrarCajas(){
    $.ajax({
        url: $('#url-proyecto').val() + 'facturas/ajaxcierrecajas',
        type: "POST",
        success: function(data) {    
            var respuesta = JSON.parse(data);
            if(respuesta.resp){
                bootbox.alert("Se realiza el cierre de forma correcta");
            }else{
                bootbox.alert("No fue posible realizar el cierre. Por favor, inténtelo de nuevo");
            }
        }
    });      
 }

