/**Buscar clientes */
function buscarClientes(texto) {
    var baseUrl   = $('#baseUrl').val();
    var empresaId = $('input[name="data[Reciboscaja][empresa_id]"]').val();

    $.ajax({
        type    : 'POST',
        url     : baseUrl + '/clientes/ajaxObtenerClientes',
        data    : { datosCliente: texto, empresaId: empresaId },
        success : function(data) {
            var resp = JSON.parse(data);
            var html = '';
            if (resp.resp && resp.resp.length > 0) {
                $.each(resp.resp, function(i, cli) {
                    html += '<a href="javascript:void(0);" '
                            + 'class="list-group-item list-group-item-action resultCliente" '
                            + 'data-id="'     + cli.Cliente.id      + '" '
                            + 'data-nombre="' + cli.Cliente.nombre   + '" '
                            + 'data-nit="'    + cli.Cliente.nit      + '">'
                            + '<b>' + cli.Cliente.nombre + '</b>'
                            + ' <small class="text-muted">NIT: ' + cli.Cliente.nit + '</small>'
                            + '</a>';
                });
            } else {
                html = '<div class="list-group-item text-muted">No se encontraron clientes.</div>';
            }
            $('#resultadosCliente').html(html).show();
        }
    });
}

function formatCurrency(input) {
    let value = input.value.replace(/[^0-9]/g, '');

    if (value === '') {
        input.value = '';
        return;
    }

    value = parseInt(value, 10);

    input.value = '$' + value.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}


$(document).ready(function() {

    $('.numericPrice').number(true, 2);

    // ── Búsqueda de clientes con debounce ──
    var timerId;
    $('#inputBuscarCliente').on('keyup', function() {
        clearTimeout(timerId);
        var texto = $(this).val().trim();
        if (texto.length < 2) {
            $('#resultadosCliente').hide();
            return;
        }
        timerId = setTimeout(function() {
            buscarClientes(texto);
        }, 350);
    });

    $('#btnBuscarCliente').on('click', function() {
        var texto = $('#inputBuscarCliente').val().trim();
        if (texto.length >= 2) buscarClientes(texto);
    });


    // Seleccionar un cliente del listado
    $(document).on('click', '.resultCliente', function() {
        var id     = $(this).data('id');
        var nombre = $(this).data('nombre');
        var nit    = $(this).data('nit');

        $('#clienteIdSeleccionado').val(id);
        $('#lblNombreCliente').text(nombre);
        $('#lblNitCliente').text(nit);
        $('#panelClienteSeleccionado').show();
        $('#inputBuscarCliente').val('').prop('disabled', true);
        $('#btnBuscarCliente').prop('disabled', true);
        $('#resultadosCliente').hide();
    });

    // Cambiar cliente seleccionado
    $('#lnkCambiarCliente').on('click', function() {
        $('#clienteIdSeleccionado').val('');
        $('#panelClienteSeleccionado').hide();
        $('#inputBuscarCliente').val('').prop('disabled', false);
        $('#btnBuscarCliente').prop('disabled', false);
    });

    // Cerrar resultados al hacer clic fuera
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#inputBuscarCliente, #resultadosCliente, #btnBuscarCliente').length) {
            $('#resultadosCliente').hide();
        }
    });

    // ── Validación y envío ──
    $('#btnGuardarRecibo').on('click', function() {
        var clienteId  = $('#clienteIdSeleccionado').val();
        var valor      = $('#inputValor').val().replace(/,/g, '');
        var tipoPagoId = $('select[name="data[Reciboscaja][tipopago_id]"]').val();

        if (!clienteId || clienteId === '') {
            bootbox.alert('<i class="fa fa-exclamation-circle text-danger"></i> Debe seleccionar un cliente.');
            return;
        }
        if (!valor || parseFloat(valor) <= 0) {
            bootbox.alert('<i class="fa fa-exclamation-circle text-danger"></i> El valor debe ser mayor a cero.');
            return;
        }
        if (!tipoPagoId) {
            bootbox.alert('<i class="fa fa-exclamation-circle text-danger"></i> Debe seleccionar el tipo de pago.');
            return;
        }

        // Todo válido → enviar formulario
        $('#formRecibo').submit();
    });

});
