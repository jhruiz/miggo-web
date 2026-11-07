<?php
/**
 * Modal bloqueante de selección de sede (centro de costo) para facturación.
 *
 * Se muestra al entrar a facturar cuando el usuario tiene 2+ centros de costo.
 * No se puede cerrar sin elegir una sede. Al confirmar, fija la empresa activa
 * en sesión (AJAX a facturas/ajaxFijarSede) y habilita el formulario.
 *
 * Variables esperadas:
 *   $centrosSede            array de ['id','nombre','empresa_id']
 *   $requiereSeleccionSede  bool — si true, el modal se abre y bloquea
 */
?>
<?php if (!empty($requiereSeleccionSede)): ?>

<div class="modal fade" id="modalSeleccionSede" tabindex="-1" role="dialog"
     data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#2A3F54; color:#fff;">
                <h4 class="modal-title">
                    <i class="fa fa-building-o"></i> Seleccione la sede para facturar
                </h4>
            </div>
            <div class="modal-body">
                <p class="text-muted" style="margin-bottom:15px;">
                    Antes de continuar, elija la sede sobre la que va a facturar.
                    El cliente, los productos y los demás datos corresponderán a esa sede.
                </p>
                <div id="listaSedes">
                    <?php foreach ($centrosSede as $sede): ?>
                    <div class="radio" style="margin:6px 0;">
                        <label style="cursor:pointer; font-weight:normal;">
                            <input type="radio" name="seleccionSede" value="<?php echo h($sede['id']); ?>">
                            <b><?php echo h($sede['nombre']); ?></b>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnConfirmarSede" disabled>
                    <i class="fa fa-check"></i> Continuar
                </button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="baseUrlSede" value="<?php echo $this->request->base; ?>">

<script>
$(document).ready(function() {

    // Abrir el modal bloqueante de inmediato
    $('#modalSeleccionSede').modal('show');

    // Habilitar el botón solo cuando se elige una sede
    $(document).on('change', 'input[name="seleccionSede"]', function() {
        $('#btnConfirmarSede').prop('disabled', false);
    });

    // Confirmar la sede → fijar empresa activa en sesión
    $(document).on('click', '#btnConfirmarSede', function() {
        var centroId = $('input[name="seleccionSede"]:checked').val();
        if (!centroId) { return; }

        var base = $('#baseUrlSede').val();
        $('#btnConfirmarSede').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Cargando...');

        $.ajax({
            type: 'POST',
            url:  base + '/facturas/ajaxFijarSede',
            data: { centrocostoId: centroId },
            success: function(data) {
                var resp = JSON.parse(data);
                if (resp.resp) {
                    // Recargar para que el formulario cargue con la empresa activa fijada
                    window.location.reload();
                } else {
                    if (typeof bootbox !== 'undefined') {
                        bootbox.alert(resp.msg);
                    } else {
                        alert(resp.msg);
                    }
                    $('#btnConfirmarSede').prop('disabled', false).html('<i class="fa fa-check"></i> Continuar');
                }
            },
            error: function() {
                alert('Error al fijar la sede. Intente de nuevo.');
                $('#btnConfirmarSede').prop('disabled', false).html('<i class="fa fa-check"></i> Continuar');
            }
        });
    });

});
</script>

<?php endif; ?>
