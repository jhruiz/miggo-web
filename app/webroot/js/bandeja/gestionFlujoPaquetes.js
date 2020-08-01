
$(function() {
    var MaxInputs = 8; //Número Maximo de Campos
    var contenedor = $("#conten"); //ID del contenedor
    var AddButton = $("#agregarCamp"); //ID del Botón Agregar

    //var x = número de campos existentes en el contenedor
    var x = $("#conten div").length + 1;

    var FieldCount = x - 1; //para el seguimiento de los campos

    $(AddButton).click(function(e) {
        if (x <= MaxInputs)
        {
            FieldCount++;
            //agregar campo            
            $(contenedor).append('<div><br><input type="file" name="' + FieldCount + '" class="ArchivoUpload" id="subirArchivo"><a href="#" class="eliminar">&times;</a><br></div>');
            x++; //text box increment
        }
        return false;
    });

    $("body").on("click", ".eliminar", function(e) { //click en eliminar campo
        if (x > 1) {
            $(this).parent('div').remove(); //eliminar el campo
            x--;
        }
        return false;
    });

    jQuery("#mostrarZona2").attr('checked', true);
    jQuery("#mostrarZona1").attr('checked', false);
    $("#divRegional").hide(2000);
    $("#divCiudades").hide(2000);
    $("#divZonas").hide(2000);
    $("#ZonaZona").attr('required', false);
});

function colocarObser(campoNombre, nombreUsuario) {    
    if (campoNombre == '') {
        $('#observacionesTemp').val('');
    }
    else {
        var nombreArchivo = $("#" + campoNombre).html();
        var date = new Date();
        var month = date.getMonth() + 1;
        var day = date.getDate();
        var year = date.getFullYear();
        var hora = date.getHours();
        var minutos = date.getMinutes();
        var segundos = date.getSeconds();
        var dateF = day + '/' + month + '/' + year + " " + hora + ":" + minutos + ":" + segundos;
        var obsFinal = "Fecha: " + dateF + '\n';
        obsFinal += "Usuario: " + nombreUsuario + '\n';
        var textoAnterior = $('#observacionesTemp').val();
        if (textoAnterior == '')
            $('#observacionesTemp').val(obsFinal + "Documento rechazado: " + nombreArchivo);
        else
            $('#observacionesTemp').val(textoAnterior + "\nDocumento rechazado: " + nombreArchivo);
    }
}
     
function vercatalogoglosa(idPaquete) {
    if (habilitarGestion()) {
        $('#guardar').attr('disabled', 'disabled');
        $.ajax({
            url: "listarcausaglosa",
            data: {idPaquete: idPaquete},
            type: "POST",
            success: function(data) {
                var opt = {
                    autoOpen: false,
                    modal: true,
                    width: 300,
                    height: 200,
                    position: [250, 200],
                    show: {
                        effect: "blind",
                        duration: 1000
                    },
                    hide: function() {
                        $(this).dialog('destroy').remove();
                    },
                    title: 'Seleccione la causa'
                };
                $("#verCatalogoGlosa").html("<div id='verListado'></div>");

                $("#verListado").html(data);
                dialogDocumento = $("#verListado").dialog(opt);
                dialogDocumento.dialog('open');
            }
        });
    }
}

function registrarCausaglosa(idPaquete) {
    var idCausa = $("#BandejaCausaglosa option:selected").val();
    
    $.ajax({
        url: "registrarcausaglosapaquete",
        data: {idPaquete: idPaquete, idCausa: idCausa},
        type: "POST",
        success: function(data) {              
        }
    });
    $('#guardar').removeAttr("disabled");  
    $("#verListado").dialog('destroy').remove();
}

function cerrarListadoCausa() {
    $("#verListado").dialog('destroy').remove();
}    