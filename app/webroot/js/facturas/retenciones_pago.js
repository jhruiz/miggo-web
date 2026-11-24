/**
 * Línea "Retenciones" en la ventana de medios de pago al facturar (facturas/pagofactura).
 *
 * Si el cliente practica retenciones (cliente configurado), muestra una casilla por
 * tipo (ReteFuente, ReteIVA, ReteICA) con el valor calculado, que el usuario puede
 * cambiar. Las retenciones cubren parte del total sin entrar a ninguna caja: los
 * métodos de pago deben sumar total - retenciones.
 *
 * Expone para tiposPago.js:
 *   window.totalRetencionesPago()          suma de las casillas (0 si no hay bloque)
 *   window.validarRetencionesPago()        mensaje de error o ''
 *   window.agregarRetencionesAlFormulario() copia los valores al formulario de la factura
 */
(function ($) {

    function numero(valor) {
        var n = parseFloat(String(valor == null ? '' : valor).replace(/,/g, ''));
        return isNaN(n) ? 0 : n;
    }

    function textoSeguro(texto) {
        return $('<div>').text(texto == null ? '' : String(texto)).html();
    }

    function totalVenta() {
        return numero($('#totalVentaTipos').val());
    }

    window.totalRetencionesPago = function () {
        var total = 0;
        $('#retencionesPagoFilas .valueRetencion').each(function () {
            total += numero($(this).val());
        });
        return Math.round(total * 100) / 100;
    };

    window.validarRetencionesPago = function () {
        var mensaje = '';
        $('#retencionesPagoFilas .valueRetencion').each(function () {
            var texto = String($(this).val()).replace(/,/g, '').trim();
            if (texto !== '' && (isNaN(parseFloat(texto)) || parseFloat(texto) < 0)) {
                mensaje = 'El valor de ' + $(this).data('etiqueta') + ' no es válido.';
            }
        });
        if (mensaje === '' && window.totalRetencionesPago() > totalVenta() + 0.005) {
            mensaje = 'Las retenciones no pueden superar el valor a pagar.';
        }
        return mensaje;
    };

    window.agregarRetencionesAlFormulario = function () {
        var $form = $('#prefacturaId').closest('form');
        if (!$form.length) {
            $form = $('#FacturaAddForm, #PrefacturaViewForm').first();
        }
        $form.find('.retencion-aplicada-oculta').remove();
        $('#retencionesPagoFilas .valueRetencion').each(function () {
            $('<input>', {
                type: 'hidden',
                'class': 'retencion-aplicada-oculta',
                name: 'data[RetencionesAplicadas][' + $(this).data('tributo') + ']',
                value: numero($(this).val()).toFixed(2)
            }).appendTo($form);
        });
    };

    /** Restante = total - retenciones - métodos de pago ingresados */
    function actualizarRestante() {
        var pagos = 0;
        $('.contenedor_pagos').first().find('.valueFact').each(function () {
            pagos += numero($(this).val());
        });
        var restante = totalVenta() - window.totalRetencionesPago() - pagos;
        $('#restante').html(restante.toLocaleString());
    }

    function pintar(porTributo) {
        var filas = '';
        $.each(porTributo, function (i, r) {
            filas += '<div class="form-inline" style="margin-bottom: 5px;">' +
                '<label style="min-width: 110px;">' + textoSeguro(r.etiqueta) + '</label> ' +
                '<input type="text" class="form-control valueRetencion" style="max-width: 160px;"' +
                ' data-tributo="' + textoSeguro(r.tributo) + '" data-etiqueta="' + textoSeguro(r.etiqueta) + '"' +
                ' value="' + Number(r.valor).toFixed(2) + '">' +
                '</div>';
        });
        $('#retencionesPagoFilas').html(filas);
        $('#bloqueRetencionesPago').show();
        $('#retencionesPagoFilas .valueRetencion').on('change blur', function () {
            var mensaje = window.validarRetencionesPago();
            if (mensaje !== '') {
                bootbox.alert(mensaje);
            }
            actualizarRestante();
        });
        actualizarRestante();
    }

    $(function () {
        if (!$('#bloqueRetencionesPago').length) {
            return;
        }
        var prefacturaId = $('#prefacturaId').val();
        if (!prefacturaId) {
            return;
        }
        $.ajax({
            url: $('#url-proyecto').val() + 'facturas/ajaxRetencionesPrefactura',
            type: 'POST',
            dataType: 'json',
            data: {
                prefacturaId: prefacturaId,
                clienteId: $('#FacturaIdcliente').val(),
                esFactura: $('#esFacturaDV').val()
            },
            success: function (data) {
                if (data && data.resp && data.por_tributo && data.por_tributo.length > 0) {
                    pintar(data.por_tributo);
                }
            }
        });
    });
})(jQuery);
