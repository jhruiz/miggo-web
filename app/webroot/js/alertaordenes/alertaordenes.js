

function validarFormulario() {
    var mensaje = '';

    if($('#OrdentrabajoTipoAlertaId').val() == ''){
        mensaje += '- Debe seleccionar un tipo de alerta.<br>';
    }

    if($('#OrdentrabajoEstadoAlertaId').val() == ''){
        mensaje += '- Debe seleccionar un estado para la alerta.<br>';
    }

    if($('#fecha_alerta').val() == ''){
        mensaje += '- La fecha de la alerta no puede estar vacía.<br>';
    }

    if($('#fecha_mant').val() == ''){
        mensaje += '- La fecha para el manetenimiento no puede estar vacía.<br>';
    }

    if($('#OrdentrabajoUnidadesMedidaId').val() == '2'){
        if($('#OrdentrabajoKmxdia').val() == ''){
            mensaje += '- Los Km promedio por día no pueden estar vacios.<br>';
        }

        if($('#OrdentrabajoKmproxmant').val() == ''){
            mensaje += '- Los Km para el proximo mantenimiento no pueden estar vacios.<br>';
        }
    }

    if($('#OrdentrabajoObservacionesCliente').val() == ''){
        mensaje += '- Debe ingresar una observación para la alerta.<br>';
    }
        
    return mensaje;
}

function obtenerParametros (){
    var params = new Object();

    params.alertaId = $('#OrdentrabajoTipoAlertaId').val();
    params.unidadMedidaId = $('#OrdentrabajoUnidadesMedidaId').val();
    params.ordenTrabajoId = $('#ordenTId').val();
    params.estadoAlertaId = $('#OrdentrabajoEstadoAlertaId').val();
    params.fechaAlerta = $('#fecha_alerta').val();
    params.fechaMantto = $('#fecha_mant').val();
    params.kmxDia = $('#OrdentrabajoKmxdia').val();
    params.kmProxMantto = $('#OrdentrabajoKmproxmant').val();
    params.observaciones = $('#OrdentrabajoObservacionesCliente').val();

    return params;
}

var guardaralerta = function(){

    var mensaje = validarFormulario();
    console.log(mensaje);

    if(mensaje == ''){
        var params = obtenerParametros();
    
        $.ajax({
            url: $('#url-proyecto').val() + 'alertaordenes/guardaralertas',
            data: params,
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
    
                if(resp.resp){
                    bootbox.alert('La alerta se ha generado con éxito.', function(){
                        window.close();
                    });
                }else{
                    bootbox.alert('No fué posible generar la alerta. Por favor, inténtelo de nuevo.')
                }
            }
        });      
    }else{
        bootbox.alert(mensaje);
    }

}

var datePicker = function(){
    $(".date").datepicker({dateFormat: 'yy-mm-dd'});
    $(".date").datepicker("option", "showAnim", "slideDown");    
};

var tipoFormUnidadesMedida = function() {

    if($(this).val() == 2) {
        $('#day_unit').show();
    } else {
        $('#day_unit').hide();
        $('.calckm').val('');
    }
    unitLabel();
}

var calcularFechaMantenimiento = function() {
    var kmActual = $('#km_actual').val();
    var kmProxMant = $('#OrdentrabajoKmproxmant').val();
    var kmPromDia = $('#OrdentrabajoKmxdia').val();

    if(kmPromDia != ''){
        var diffKm = (parseInt(kmProxMant) - parseInt(kmActual)) / parseInt(kmPromDia);

        //fecha actual y fecha mantenimiento
        var fechaAct = new Date();        
        fechaAct.setDate(fechaAct.getDate() + diffKm);

        var anio = '' + fechaAct.getFullYear();
        var mes = ((fechaAct.getMonth() + 1) >= 10 ? '' + (fechaAct.getMonth() + 1) : '0' + (fechaAct.getMonth() + 1));
        var dia = fechaAct.getDate() >= 10 ? '' + fechaAct.getDate() : '0' + fechaAct.getDate();

        fechaMantProx = anio + '-' + mes + '-' + dia;

        //fecha para alerta
        var fechaMant = new Date(fechaMantProx);
        fechaMant.setDate(fechaMant.getDate() - 4);

        var anioFm = '' + fechaMant.getFullYear();
        var mesFm = ((fechaMant.getMonth() + 1) >= 10 ? '' + (fechaMant.getMonth() + 1) : '0' + (fechaMant.getMonth() + 1));
        var diaFm = fechaMant.getDate() >= 10 ? '' + fechaMant.getDate() : '0' + fechaMant.getDate();
        
        var fechaAlerta = anioFm + '-' + mesFm + '-' + diaFm;


        $('#fecha_mant').val(fechaMantProx);
        $('#fecha_alerta').val(fechaAlerta);
    }

}

function unitLabel(){
    var unit = $('#OrdentrabajoUnidadesMedidaId option:selected').html()
    $('#tipo_unidad_medida').html(unit.toLowerCase());
}

var generarAlertaSoat = function() {
    bootbox.confirm("¿Está seguro que desea generar una alerta para el SOAT del vehículo?", function(result){
        if(result){            
            var vehiculoId = $('#vehiculoId').val();
            var clienteId = $('#clienteId').val(); 
            var soat = $('#soat').val();         
            $.ajax({
                url: $('#url-proyecto').val() + 'alertaordenes/generaralertasoat',
                data: { vehiculoId: vehiculoId, clienteId: clienteId, soat: soat },
                type: "POST",
                success: function(data) {
                    var resp = JSON.parse(data);
        
                    if(resp.resp == '1'){
                        bootbox.alert('Se creó la alerta para la renovación del SOAT del vehículo.');
                    }else if ( resp.resp == '2' ) {
                        bootbox.alert('Ya existe una alerta para el SOAT del vehículo');
                    }else{
                        bootbox.alert('No fue posible crear la alerta para la renovación del SOAT. Por favor, inténtelo de nuevo.');
                    }
                }
            }); 
        }
    }); 
}

var generarAlertaTecno = function() {
    bootbox.confirm("¿Está seguro que desea generar una alerta para el Tecnomecánico del vehículo?", function(result){
        if(result){            
            var vehiculoId = $('#vehiculoId').val();
            var clienteId = $('#clienteId').val();   
            var tecnomecanica = $('#tecnomecanica').val();
            $.ajax({
                url: $('#url-proyecto').val() + 'alertaordenes/generaralertatecno',
                data: { vehiculoId: vehiculoId, clienteId: clienteId, tecnomecanica: tecnomecanica },
                type: "POST",
                success: function(data) {
                    var resp = JSON.parse(data);
        
                    if(resp.resp == '1'){
                        bootbox.alert('Se creó la alerta para la renovación del Tecnomecáncio del vehículo.');
                    }else if ( resp.resp == '2' ) {
                        bootbox.alert('Ya existe una alerta para el Tecnomecánico del vehículo');
                    }else{
                        bootbox.alert('No fue posible crear la alerta para la renovación del Tecnomecánico. Por favor, inténtelo de nuevo.');
                    }
                }
            }); 
        }
    }); 

}

$(function() {        
    datePicker();
    $('.numericPrice').number(true);
    $('#day_unit').hide();
    $('#OrdentrabajoUnidadesMedidaId').change(tipoFormUnidadesMedida);
    $('#OrdentrabajoKmxdia').blur(calcularFechaMantenimiento);
    $('#guardarAlerta').click(guardaralerta);
    $('#alerta_soat').click(generarAlertaSoat);    
    $('#alerta_tecno').click(generarAlertaTecno);
    unitLabel();
});