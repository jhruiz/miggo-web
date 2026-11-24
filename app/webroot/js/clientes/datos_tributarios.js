/**
 * Datos tributarios del cliente: muestra el dígito de verificación (DV) mientras
 * se escribe el NIT. El valor definitivo lo calcula el servidor al guardar.
 * Algoritmo DIAN (módulo 11). Aplica a cédula de ciudadanía (3) y NIT (6).
 */
$(function () {
    var $nit = $('#ClienteNit');
    var $tipo = $('#ClienteTipoidentificacioneId');
    var $dv = $('#clienteDv');
    if (!$nit.length || !$dv.length) {
        return;
    }

    var PESOS = [3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71];

    function calcularDv(valor) {
        valor = String(valor || '').trim();
        if (valor.indexOf('-') !== -1) {
            valor = valor.substring(0, valor.indexOf('-'));
        }
        valor = valor.replace(/[.,\s]/g, '');
        if (!/^\d{3,15}$/.test(valor)) {
            return '';
        }
        var suma = 0;
        var digitos = valor.split('').reverse();
        for (var i = 0; i < digitos.length; i++) {
            suma += parseInt(digitos[i], 10) * PESOS[i];
        }
        var resto = suma % 11;
        return String(resto > 1 ? 11 - resto : resto);
    }

    function actualizar() {
        var tipo = parseInt($tipo.val(), 10);
        $dv.val(tipo === 3 || tipo === 6 ? calcularDv($nit.val()) : '');
    }

    $nit.on('input change keyup', actualizar);
    $tipo.on('change', actualizar);
    actualizar();
});
