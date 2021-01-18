var tipoProveedor = function() {
    var type = $('#proveedore_id option:selected').data('type');

    if (type == '1') {
        $('#val_prod').html('Costo Unitario (sin IVA)');
    } else {
        $('#val_prod').html('Costo Unitario (IVA Incluido)');
    }
}


$(function() {
    $('#proveedore_id').change(tipoProveedor);
    $('.numericPrice').number(true);
});