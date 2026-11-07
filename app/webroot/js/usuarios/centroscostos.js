/**
 * centroscostos.js
 *
 * Maneja el modal de asignación de centros de costo a un usuario,
 * desde el index de usuarios.
 *
 * Endpoints:
 *   POST usuarios/ajaxObtenerCentrosUsuario  → centros disponibles + asignados
 *   POST usuarios/ajaxGuardarCentrosUsuario  → guarda la asignación
 */

var baseUrlCC = function() {
    // El layout inyecta #url-proyecto (con slash final) y/o #baseUrl (sin slash).
    var u = $('#url-proyecto').val();
    if (u) {
        return u.replace(/\/$/, ''); // quitar slash final para unificar
    }
    return $('#baseUrl').val() || '';
};

/**
 * Abre el modal y carga los centros de costo del usuario indicado.
 * @param {number} usuarioId
 */
function abrirModalCentros(usuarioId) {
    var base = baseUrlCC();

    $('#ccUsuarioId').val(usuarioId);
    $('#ccListaCentros').html('<div class="text-center" style="padding:20px;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><small>Cargando...</small></div>');
    $('#modalCentrosCosto').modal('show');

    $.ajax({
        type: 'POST',
        url:  base + '/usuarios/ajaxObtenerCentrosUsuario',
        data: { usuarioId: usuarioId },
        success: function(data) {
            var resp = JSON.parse(data);

            if (!resp.resp) {
                $('#ccListaCentros').html('<div class="alert alert-warning">' + resp.msg + '</div>');
                return;
            }

            // Título con el nombre del usuario
            $('#ccNombreUsuario').text(resp.usuario.nombre);

            if (!resp.centros || resp.centros.length === 0) {
                $('#ccListaCentros').html(
                    '<div class="alert alert-info">No hay centros de costo disponibles para asignar. ' +
                    'Créalos primero en el módulo de Centros de Costo.</div>'
                );
                $('#ccBtnGuardar').prop('disabled', true);
                return;
            }

            $('#ccBtnGuardar').prop('disabled', false);

            // Construir una lista de checkboxes, marcando los asignados
            var html = '';

            // Buscador en vivo (útil si hay muchas sedes)
            html += '<div class="form-group" style="margin-bottom:10px;">';
            html += '<input type="text" id="ccBuscar" class="form-control" placeholder="Buscar sede...">';
            html += '</div>';

            // Atajos: marcar / desmarcar todos
            html += '<div style="margin-bottom:8px;">';
            html += '<a href="javascript:void(0);" id="ccMarcarTodos" style="margin-right:12px;">Marcar todos</a>';
            html += '<a href="javascript:void(0);" id="ccDesmarcarTodos">Desmarcar todos</a>';
            html += '</div>';

            html += '<div id="ccListaCheckboxes" style="max-height:320px; overflow-y:auto; border:1px solid #e0e0e0; border-radius:4px; padding:8px;">';
            $.each(resp.centros, function(i, c) {
                var chk = c.asignado ? ' checked="checked"' : '';
                html += '<div class="checkbox cc-item" style="margin:4px 0;" data-nombre="' + c.nombre.toLowerCase() + '">';
                html += '<label style="display:block; cursor:pointer; font-weight:normal;">';
                html += '<input type="checkbox" class="cc-check" value="' + c.id + '"' + chk + '> ';
                html += c.nombre;
                html += '</label>';
                html += '</div>';
            });
            html += '</div>';
            html += '<small class="text-muted">Marque las sedes a las que el usuario puede facturar.</small>';

            $('#ccListaCentros').html(html);
        },
        error: function() {
            $('#ccListaCentros').html('<div class="alert alert-danger">Error al cargar los centros de costo.</div>');
        }
    });
}

/**
 * Guarda la asignación seleccionada.
 */
function guardarCentrosUsuario() {
    var base      = baseUrlCC();
    var usuarioId = $('#ccUsuarioId').val();

    // Recolectar los ids de los checkboxes marcados
    var centros = [];
    $('#ccListaCheckboxes .cc-check:checked').each(function() {
        centros.push($(this).val());
    });

    $('#ccBtnGuardar').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

    $.ajax({
        type: 'POST',
        url:  base + '/usuarios/ajaxGuardarCentrosUsuario',
        data: { usuarioId: usuarioId, centros: centros },
        success: function(data) {
            var resp = JSON.parse(data);
            $('#ccBtnGuardar').prop('disabled', false).html('<i class="fa fa-save"></i> Guardar');

            if (resp.resp) {
                $('#modalCentrosCosto').modal('hide');
                if (typeof bootbox !== 'undefined') {
                    bootbox.alert('<i class="fa fa-check-circle text-success"></i> ' + resp.msg);
                } else {
                    alert(resp.msg);
                }
            } else {
                if (typeof bootbox !== 'undefined') {
                    bootbox.alert('<i class="fa fa-exclamation-circle text-danger"></i> ' + resp.msg);
                } else {
                    alert(resp.msg);
                }
            }
        },
        error: function() {
            $('#ccBtnGuardar').prop('disabled', false).html('<i class="fa fa-save"></i> Guardar');
            alert('Error al guardar la asignación. Intente de nuevo.');
        }
    });
}

$(function() {
    $(document).on('click', '#ccBtnGuardar', function() {
        guardarCentrosUsuario();
    });

    // Buscador en vivo: filtra las filas de checkboxes por nombre
    $(document).on('keyup', '#ccBuscar', function() {
        var texto = $(this).val().toLowerCase().trim();
        $('#ccListaCheckboxes .cc-item').each(function() {
            var nombre = $(this).data('nombre') || '';
            $(this).toggle(nombre.indexOf(texto) !== -1);
        });
    });

    // Marcar todos (solo los visibles según el filtro)
    $(document).on('click', '#ccMarcarTodos', function() {
        $('#ccListaCheckboxes .cc-item:visible .cc-check').prop('checked', true);
    });

    // Desmarcar todos (solo los visibles según el filtro)
    $(document).on('click', '#ccDesmarcarTodos', function() {
        $('#ccListaCheckboxes .cc-item:visible .cc-check').prop('checked', false);
    });
});
