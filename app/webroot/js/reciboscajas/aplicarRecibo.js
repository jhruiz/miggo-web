/**
 * aplicarRecibo.js
 *
 * Maneja el modal "Aplicar Recibo de Caja" desde la vista de prefactura/factura.
 * Se integra junto a abonos.js y gestionabonos.js sin modificar su funcionamiento.
 *
 * Endpoints usados:
 *   POST reciboscajas/ajaxObtenerRecibosCliente  → recibos activos del cliente
 *   POST reciboscajas/ajaxAplicarRecibo           → aplica el recibo como abono
 */

var opcModalRecibo = {
    autoOpen : false,
    modal    : true,
    width    : 640,
    height   : 'auto',
    show     : { duration: 300 },
    close    : function() {},
    title    : 'Aplicar Recibo de Caja como Abono'
};

var dialogRecibo;

// ─────────────────────────────────────────────────────────────────────────────
// Abrir el modal y cargar los recibos disponibles del cliente
// ─────────────────────────────────────────────────────────────────────────────

var abrirModalRecibo = function() {
    var clienteId = $('#FacturaIdcliente').val();
    var baseUrl   = $('#url-proyecto').val();

    if (!clienteId || clienteId === '') {
        bootbox.alert('<i class="fa fa-exclamation-circle text-warning"></i> Seleccione un cliente antes de aplicar un recibo de caja.');
        return;
    }

    // Calcular el saldo pendiente de la prefactura (igual que abonos.js)
    var ttales = 0;
    $('.valor_con_iva').each(function() {
        ttales += parseFloat($(this).val()) || 0;
    });
    var bolsa     = parseFloat($('#inp_imp_bolsa').val()) || 0;
    var abonados  = parseFloat($('.ttalAbonos').val())    || 0;
    var pendiente = ttales + bolsa - abonados;

    $('#reciboSaldoPendiente').val(pendiente);
    $('#inputValorAplicar').val('');
    $('#selectRecibo').val('');
    $('#panelSaldoRecibo').hide();
    $('#panelSinRecibos').hide();
    $('#panelListaRecibos').hide();

    // Mostrar el modal mientras carga
    dialogRecibo = $('#div_recibo').dialog(opcModalRecibo);
    dialogRecibo.dialog('open');

    $('#divCargandoRecibos').show();

    $.ajax({
        url  : baseUrl + 'reciboscajas/ajaxObtenerRecibosCliente',
        data : { clienteId: clienteId },
        type : 'POST',
        success: function(data) {
            var resp = JSON.parse(data);
            $('#divCargandoRecibos').hide();

            if (!resp.resp || resp.resp.length === 0) {
                $('#panelSinRecibos').show();
                return;
            }

            // Poblar el select de recibos
            var opts = '<option value="">-- Seleccione un recibo --</option>';
            $.each(resp.resp, function(i, r) {
                opts += '<option value="' + r.id + '" '
                      + 'data-saldo="' + r.saldo + '" '
                      + 'data-consecutivo="' + r.consecutivo + '">'
                      + r.consecutivo
                      + ' | Saldo disponible: ' + _fmtNum(r.saldo)
                      + (r.concepto ? ' | ' + r.concepto : '')
                      + '</option>';
            });
            $('#selectRecibo').html(opts);
            $('#panelListaRecibos').show();
        },
        error: function() {
            $('#divCargandoRecibos').hide();
            bootbox.alert('Error al consultar los recibos. Intente de nuevo.');
        }
    });
};

// ─────────────────────────────────────────────────────────────────────────────
// Al cambiar el recibo seleccionado → mostrar su saldo
// ─────────────────────────────────────────────────────────────────────────────

$(document).on('change', '#selectRecibo', function() {
    var opcion = $(this).find('option:selected');
    var saldo  = parseFloat(opcion.data('saldo')) || 0;

    if (!$(this).val()) {
        $('#panelSaldoRecibo').hide();
        return;
    }

    var pendiente = parseFloat($('#reciboSaldoPendiente').val()) || 0;
    // Sugerir el mínimo entre el saldo del recibo y el saldo pendiente de la prefactura
    var sugerido  = Math.min(saldo, pendiente > 0 ? pendiente : saldo);

    $('#lblSaldoRecibo').text(_fmtNum(saldo));
    $('#lblSaldoPendientePF').text(_fmtNum(pendiente > 0 ? pendiente : 0));
    $('#inputValorAplicar').val(_fmtNumInput(sugerido));
    $('#panelSaldoRecibo').show();
});

// ─────────────────────────────────────────────────────────────────────────────
// Confirmar y aplicar el recibo como abono
// ─────────────────────────────────────────────────────────────────────────────

var confirmarAplicarRecibo = function() {
    var reciboId    = $('#selectRecibo').val();
    var valor       = parseFloat($('#inputValorAplicar').val().replace(/,/g, '')) || 0;
    var prefactId   = $('#prefactId').val() || $('#prefacturaId').val() || null;
    var facturaId   = $('#facturaIdAbonoRecibo').val() || null;
    var baseUrl     = $('#url-proyecto').val();
    var saldoRecibo = parseFloat($('#selectRecibo option:selected').data('saldo')) || 0;

    if (!reciboId) {
        bootbox.alert('<i class="fa fa-exclamation-circle text-danger"></i> Seleccione un recibo.');
        return;
    }
    if (valor <= 0) {
        bootbox.alert('<i class="fa fa-exclamation-circle text-danger"></i> El valor a aplicar debe ser mayor a cero.');
        return;
    }
    if (valor > saldoRecibo) {
        bootbox.alert('<i class="fa fa-exclamation-circle text-danger"></i> El valor supera el saldo disponible del recibo (' + _fmtNum(saldoRecibo) + ').');
        return;
    }

    $('#btnAplicarRecibo').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Aplicando...');

    $.ajax({
        url  : baseUrl + 'reciboscajas/ajaxAplicarRecibo',
        data : {
            reciboId    : reciboId,
            valor       : valor,
            prefacturaId: prefactId,
            facturaId   : facturaId
        },
        type : 'POST',
        success: function(data) {
            var resp = JSON.parse(data);
            $('#btnAplicarRecibo').prop('disabled', false).html('<i class="fa fa-check"></i> Aplicar');

            if (resp.resp) {
                dialogRecibo.dialog('close');

                // Actualizar el acumulado de abonos en la tabla de totales
                // (mismo comportamiento que realizarAbono() en abonos.js)
                var abonosFact    = parseFloat($('.ttalAbonos').val()) || 0;
                var ttalFinAbono  = abonosFact + valor;
                var consecutivo   = $('#selectRecibo option:selected').data('consecutivo');

                var ttalesLineas = 0;
                $('.valor_con_iva').each(function() {
                    ttalesLineas += parseFloat($(this).val()) || 0;
                });
                var bolsaFin  = parseFloat($('#inp_imp_bolsa').val()) || 0;
                var totalFin  = ttalesLineas + bolsaFin - ttalFinAbono;

                var abn  = '<tr><th colspan="12" class="text-right">Abonos (incl. RC)</th>';
                    abn += '<th class="text-right">' + formatNumber(ttalFinAbono) + '</th>';
                    abn += '<th><input type="button" class="btn btn-primary btn-xs" value="Ver" id="ver_abonos" onclick="obtenerAbonos()"></th></tr>';
                    abn += '<tr><th colspan="12" class="text-right">TOTAL</th>';
                    abn += '<th class="text-right">' + formatNumber(totalFin) + '</th></tr>';

                $('#tBodAbonos').html(abn);
                $('.ttalAbonos').val(ttalFinAbono);

                bootbox.alert('<i class="fa fa-check-circle text-success"></i> Recibo <b>' + consecutivo + '</b> aplicado correctamente por <b>' + _fmtNum(valor) + '</b>.');
            } else {
                bootbox.alert('<i class="fa fa-times-circle text-danger"></i> ' + resp.msg);
            }
        },
        error: function() {
            $('#btnAplicarRecibo').prop('disabled', false).html('<i class="fa fa-check"></i> Aplicar');
            bootbox.alert('Error de comunicación. Intente de nuevo.');
        }
    });
};

// ─────────────────────────────────────────────────────────────────────────────
// Utilidades numéricas locales
// ─────────────────────────────────────────────────────────────────────────────

function _fmtNum(n) {
    return Number(n).toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function _fmtNumInput(n) {
    return Number(n).toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// Bind del botón principal
// ─────────────────────────────────────────────────────────────────────────────

$(function() {
    $(document).on('click', '#btn_aplicar_recibo', function() {
        abrirModalRecibo();
    });

    $(document).on('click', '#btnAplicarRecibo', function() {
        confirmarAplicarRecibo();
    });
});
