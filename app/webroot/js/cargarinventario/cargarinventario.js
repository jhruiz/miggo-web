var tipoProveedor = function() {
    var type = $('#proveedore_id option:selected').data('type');

    if (type == '1') {
        $('#val_prod').html('Costo Unitario Subtotal');
    } else {
        $('#val_prod').html('Costo Unitario Total');
    }
}


$(function() {
    $('#proveedore_id').change(tipoProveedor);
    $('.numericPrice').number(true);
});