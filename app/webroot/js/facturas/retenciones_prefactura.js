/**
 * Retenciones informativas en facturas/add.
 *
 * Se recalculan en el servidor (facturas/ajaxRetencionesPrefactura) cada vez que
 * cambian los totales (sumarTotales) o el cliente seleccionado. Solo muestran
 * información: no modifican el total, los pagos ni nada de lo que se guarda.
 * Las retenciones definitivas se guardan al facturar.
 *
 * No modifica facturas.js: envuelve sumarTotales y escucha la carga del cliente.
 */
(function ($) {
    var temporizador = null;
    var peticion = null;

    function formatoPesos(valor) {
        var n = Number(valor) || 0;
        return '$' + n.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function textoSeguro(texto) {
        return $('<div>').text(texto == null ? '' : String(texto)).html();
    }

    function ocultar() {
        $('#bloqueRetenciones').hide();
        $('#retencionesFilas').empty();
        $('#retencionesAvisos').hide().empty();
    }

    function pintar(data) {
        var hayRetenciones = data.retenciones && data.retenciones.length > 0;
        var hayAvisos = data.avisos && data.avisos.length > 0;
        if (!hayRetenciones && !hayAvisos) {
            ocultar();
            return;
        }

        var filas = '';
        $.each(data.retenciones || [], function (i, r) {
            filas += '<tr><td>' + textoSeguro(r.nombre) +
                ' <small class="text-muted">(base ' + formatoPesos(r.base) + ')</small></td>' +
                '<td class="text-right">-' + formatoPesos(r.valor) + '</td></tr>';
        });
        $('#retencionesFilas').html(filas);
        $('#retencionesTotal').text(hayRetenciones ? '-' + formatoPesos(data.total_retenciones) : formatoPesos(0));
        $('#retencionesNeto').text(data.neto_a_pagar != null ? formatoPesos(data.neto_a_pagar) : '');

        if (hayAvisos) {
            var avisos = '';
            $.each(data.avisos, function (i, a) {
                avisos += '<div><i class="fa fa-exclamation-triangle"></i> ' + textoSeguro(a) + '</div>';
            });
            $('#retencionesAvisos').html(avisos).show();
        } else {
            $('#retencionesAvisos').hide().empty();
        }
        $('#bloqueRetenciones').show();
    }

    function refrescar() {
        var prefacturaId = $('#prefacturaId').val();
        if (!prefacturaId) {
            ocultar();
            return;
        }
        if (peticion) {
            peticion.abort();
        }
        peticion = $.ajax({
            url: $('#url-proyecto').val() + 'facturas/ajaxRetencionesPrefactura',
            type: 'POST',
            dataType: 'json',
            data: {
                prefacturaId: prefacturaId,
                clienteId: $('#FacturaIdcliente').val(),
                esFactura: $('#esFacturaDV').val()
            },
            success: function (data) {
                if (data && data.resp) {
                    pintar(data);
                } else {
                    ocultar();
                }
            },
            error: function (xhr, estado) {
                // Abortadas por una petición más nueva: no hacer nada
                if (estado !== 'abort') {
                    ocultar();
                }
            }
        });
    }

    /** Espera a que terminen los cambios seguidos antes de consultar */
    function programarRefresco() {
        clearTimeout(temporizador);
        temporizador = setTimeout(refrescar, 400);
    }

    $(function () {
        if (!$('#bloqueRetenciones').length) {
            return;
        }

        // Cada vez que se recalculan los totales de la factura
        if (typeof window.sumarTotales === 'function') {
            var sumarTotalesOriginal = window.sumarTotales;
            window.sumarTotales = function () {
                var resultado = sumarTotalesOriginal.apply(this, arguments);
                programarRefresco();
                return resultado;
            };
        }

        // Cuando termina de guardarse un cambio en la base de datos. sumarTotales se
        // llama a veces ANTES de guardar (por ejemplo, el descuento), y el cálculo
        // del servidor usa lo guardado; por eso también se refresca aquí.
        var urlsQueCambianLaPrefactura = [
            'clientes/ajaxObtenerInfoCliente',              // se elige o cambia el cliente
            'prefacturas/ajaxActualizarClientePrefactura',  // se cambia el cliente de la prefactura
            'prefacturasdetalles/actalizarcantidad',        // cantidad
            'prefacturasdetalles/actualizarcostoventa',     // precio de venta
            'prefacturas/actualizarPorcentajeValorDtto',    // descuento
            'prefacturasdetalles/delete'                    // se quita un producto
        ];
        $(document).ajaxComplete(function (evento, xhr, opciones) {
            if (!opciones || !opciones.url) {
                return;
            }
            for (var i = 0; i < urlsQueCambianLaPrefactura.length; i++) {
                if (opciones.url.indexOf(urlsQueCambianLaPrefactura[i]) !== -1) {
                    programarRefresco();
                    return;
                }
            }
        });

        programarRefresco();
    });
})(jQuery);
