var opcAbonos = {
    autoOpen: false,
    modal: true,
    width: 1300,
    height: 700,
    // position: [400, 50],
    show: {
        duration: 400    
    },
    hide: function () {
    },
    close: function( event, ui){   
        location.reload();
    },
    title: 'Gestionar abonos'    
};

/**
 * Obtiene los abonos de una prefactura específica y carga en modal
 */
var obtenerAbonos = function() {

    var prefacturaId = $('#prefacturaId').val();

    $("#div_gestionabono").load(
        $('#url-proyecto').val() + "abonofacturas/obtenerabonos",
        {prefacturaId: prefacturaId},
        function(){                                                            
            dialogAbonos=$("#div_gestionabono").dialog(opcAbonos);
            dialogAbonos.dialog('open');
            $('.numericPrice').number(true);
        }
    );          
   
}

/**
 * Convierte un valor en formato numérico
 * @param {*} valor 
 * @returns 
 */
function formatoNumerico(valor) {
    valor = String(valor);
    // Eliminar todos los caracteres que no sean números o puntos
    valor = valor.replace(/[^0-9.]/g, '');
    // Separar la parte entera de la parte decimal
    let partes = valor.split('.');
    let parteEntera = partes[0];
    let parteDecimal = partes.length > 1 ? '.' + partes[1].substring(0, 2) : ''; // Limitar a 2 decimales

    // Formatear la parte entera con separadores de miles
    parteEntera = parteEntera.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    // Combinar parte entera y decimal
    return parteEntera + parteDecimal;
}

/**
 * Setea el formulario con el abono seleccionado
 * @param {*} elemento 
 */
var setearEditarAbono = function(elemento) {
    $('#fechaAbono').val($(elemento).data('fecha'));
    $('#valorAbono').val(formatoNumerico($(elemento).data('valor')));
    $('#valorAbonoHidden').val($(elemento).data('valor'));
    $('#idAbono').val(elemento.id);
    $("#cuenta").val($(elemento).data('cuenta'));

    verOcultarFormEditarAbono(2);

}

/**
 * Recalcular el total de los abonos
 * @param {*} valorAbono 
 */
function recalcularTotal(abonoElim) {

    var textoTotal = $('#totalesAbonos').text();
    var totalAbono = parseFloat(textoTotal.replace('$', '').replace(/,/g, ''));
    var nuevoTotal = totalAbono - abonoElim;
    $('#totalesAbonos').html("$" + nuevoTotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
}

/**
 * Eliminar el registro del abono en la tabla
 * @param {*} elemento 
 */
function eliminarAbono(elemento) {
    // Obtener el ID del abono
    var idAbono = elemento.getAttribute('id');
    var valor = $(elemento).data('valor');
    var cuenta = $(elemento).data('cuenta');
    var prefactura = $(elemento).data('prefactura');

    // Confirmar si el usuario realmente quiere eliminar el registro
    if (confirm('¿Estás seguro de que deseas eliminar este abono?')) {

            $.ajax({
                url: $('#url-proyecto').val() + 'abonofacturas/eliminarabono',
                data: {idAbono: idAbono, valor: valor, cuenta: cuenta, prefactura: prefactura},
                type: "POST",
                async: false,
                success: function(data) {
                    var resp = JSON.parse(data);

                        if(resp) {
                            // Eliminar la fila de la tabla
                            var fila = document.getElementById('fila-' + idAbono);
                            if (fila) {
                            var valAbonoElim = parseFloat(fila.querySelector('.valor').textContent.replace('$', '').replace(/,/g, ''));
                            fila.remove();
    
                            // Recalcular el total
                            recalcularTotal(valAbonoElim);
                        }
                    }

                },
                error: function(xhr, status, error) {
                    alert('Hubo un error al eliminar el abono. Por favor, inténtelo nuevamente.');
                }
            });

    }
}

/**
 * Oculta o muestra el formulario de editar abono
 * @param {*} val 
 */
var verOcultarFormEditarAbono = function(val) {
    if(val == 1) {
        $('#formEditarAbono').hide();
    } else {
        $('#formEditarAbono').show();
    }
}

var actualizarMontoAbono = function() {

    var valorIni = parseFloat($('#valorAbonoHidden').val());
    var valorFin = parseFloat($('#valorAbono').val().replace(',', ''));

    if( valorFin >= valorIni ) {
        alert('Para el ajuste del abono solo se permiten valores menores a $' + formatoNumerico(valorIni));
    } else if( valorFin == 0 ) {
        alert('El ajuste del abono debe ser mayor a cero ($0).')
    }else {

        var idAbono = $('#idAbono').val(); 
        var cuenta = $('#cuenta').val();
        var prefactura = $('#idPrefactura').val();

        $.ajax({
            url: $('#url-proyecto').val() + 'abonofacturas/ajustarabono',
            data: {idAbono: idAbono, valorIni: valorIni, valorFin: valorFin, cuenta: cuenta, prefactura: prefactura},
            type: "POST",
            async: false,
            success: function(data) {
                var resp = JSON.parse(data);

                if(resp) {
                    alert('La actualización del abono se realizó de manera correcta');
                    obtenerAbonos();
                }

            },
            error: function(xhr, status, error) {
                alert('Hubo un error al actualizar el abono. Por favor, inténtelo nuevamente.');
            }
        });
    }
}


$(function(){

    verOcultarFormEditarAbono(1);

    //Se limpia el formulario al dar clic en el botón limpiar y se oculta el formulario
    $('#btnHide').on('click', function() {
        $('#fechaAbono').val("");
        $('#valorAbono').val("");
        $('#valorAbonoHidden').val("");
        $('#idAbono').val("");
        verOcultarFormEditarAbono(1);
    })

    $('#valorAbono').on('keyup', function() {
        // Obtener el valor actual del campo
        let valor = $(this).val();
        // Aplicar el formato
        $(this).val(formatoNumerico(valor));
    });

        //Se limpia el formulario al dar clic en el botón limpiar y se oculta el formulario
        $('#btnActAbono').on('click', function() {
            actualizarMontoAbono();
        })

});

