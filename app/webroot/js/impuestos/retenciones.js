/**
 * retenciones.js
 *
 * Comportamiento de los formularios de retenciones en ventas.
 * Solo ayuda a la persona usuaria: todas las reglas se validan otra vez en el
 * servidor (modelo Retencionventa).
 *
 * Variables globales que define la vista:
 *   RET_TRIBUTOS_CODIGO  { taxId: '05' | '06' | '07' }
 *   RET_UVT              valor de la UVT del año actual (0 si no está configurada)
 *   RET_UVT_ANIO         año de esa UVT
 *   RET_SUGERIR_CUENTA   true para proponer la cuenta contable según el tributo
 */
(function ($) {

    var CUENTAS_SUGERIDAS = { '05': '135517', '06': '135515', '07': '135518' };

    function codigoTributo() {
        var codigos = window.RET_TRIBUTOS_CODIGO || {};
        return codigos[$('#taxId').val()] || '';
    }

    function radioTipo(valor) {
        return $('input[name="data[Retencionventa][tipo_valor]"][value="' + valor + '"]');
    }

    /** Muestra u oculta campos y fija valores según el tributo elegido */
    function aplicarTributo(esCargaInicial) {
        var codigo = codigoTributo();

        $('.solo-reteica').toggle(codigo === '07');
        $('.solo-retefuente').toggle(codigo === '06');

        var calculaSobre = '—';
        if (codigo === '05') {
            calculaSobre = 'IVA de los ítems (se evalúa la base mínima sobre el subtotal)';
        } else if (codigo === '06' || codigo === '07') {
            calculaSobre = 'Subtotal de los ítems, antes de IVA';
        }
        $('#calculaSobre').text(calculaSobre);

        // La ReteICA siempre se expresa por mil
        var porcentaje = radioTipo('PORCENTAJE');
        if (codigo === '07') {
            if (!porcentaje.prop('disabled') || esCargaInicial) {
                radioTipo('POR_MIL').prop('checked', true);
            }
            if (!$('#tipoValorBloqueado').length) {
                porcentaje.prop('disabled', true);
            }
        } else if (!$('#tipoValorBloqueado').length) {
            porcentaje.prop('disabled', false);
        }
        actualizarUnidad();

        // Cuenta contable sugerida (solo si está vacía o tiene otra sugerencia)
        if (window.RET_SUGERIR_CUENTA && !esCargaInicial && codigo) {
            var $cuenta = $('#cuentaContable');
            var actual = $cuenta.val();
            var esSugerida = actual === '' || $.inArray(actual, $.map(CUENTAS_SUGERIDAS, function (v) { return v; })) >= 0;
            if (esSugerida) {
                $cuenta.val(CUENTAS_SUGERIDAS[codigo]);
            }
        }
    }

    /** Muestra "%" o "por mil" junto a la tarifa */
    function actualizarUnidad() {
        var porMil = radioTipo('POR_MIL').is(':checked');
        $('.unidad-tarifa').text(porMil ? 'por mil' : '%');
    }

    /** Equivalente en pesos de la base mínima con la UVT del año */
    function actualizarBaseEnPesos() {
        var $destino = $('#baseEnPesos');
        if (!$destino.length) {
            return;
        }
        var uvt = parseFloat(window.RET_UVT || 0);
        if (!uvt) {
            $destino.text('Falta configurar el valor de la UVT para el año ' + window.RET_UVT_ANIO);
            return;
        }
        var base = parseFloat(String($('#baseMinimaUvt').val() || '0').replace(',', '.'));
        if (isNaN(base) || base <= 0) {
            $destino.text('Sin base mínima: aplica desde el primer peso');
            return;
        }
        $destino.text('= $' + Math.round(base * uvt).toLocaleString('es-CO') + ' en ' + window.RET_UVT_ANIO);
    }

    $(function () {
        if ($('#taxId').length) {
            $('#taxId').on('change', function () { aplicarTributo(false); });
            aplicarTributo(true);
        }
        $(document).on('change', 'input[name="data[Retencionventa][tipo_valor]"]', actualizarUnidad);
        $('#baseMinimaUvt').on('input change', actualizarBaseEnPesos);
        actualizarBaseEnPesos();
    });

})(jQuery);
