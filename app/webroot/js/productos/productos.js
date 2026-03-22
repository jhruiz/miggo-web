var listaTagsMiggo = [];

var validarReferenciaUnica = function(){
    var referencia = $(this).val();
    var ProductoId = $('#ProductoId').val();
    
    if(typeof ProductoId == "undefined"){
        $.ajax({
            url: $('#url-proyecto').val() + 'productos/validarReferenciaUnica',
            data: {referencia: referencia},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);                
                if(resp.resp){
                    $('#ProductoReferencia').val("");
                    bootbox.alert('La referencia ya ha sido asignada a otro producto.');
                }
            }
        });          
    }else{
        $.ajax({
            url: $('#url-proyecto').val() + 'productos/validarReferenciaUnicaEdit',
            data: {referencia: referencia, productoId: ProductoId},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if(resp.resp){
                    $('#ProductoReferencia').val(resp.referencia);
                    bootbox.alert('La referencia ya ha sido asignada a otro producto.');
                }
            }
        });
    }
};


/**
 * Se valida que codigo sea unico
 * @returns {undefined}
 */
var validarCodigoUnico = function(){
    var codigo = $(this).val();
    var ProductoId = $('#ProductoId').val();
    
    if(typeof ProductoId == "undefined"){
        $.ajax({
            url: $('#url-proyecto').val() + 'productos/validarCodigoUnico',
            data: {codigo: codigo},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if(resp.resp){
                    $('#ProductoCodigo').val("");
                    bootbox.alert('El código ya ha sido asignado a otro producto.');
                }
            }
        });         
    }else{        
        $.ajax({
            url: $('#url-proyecto').val() + 'productos/validarCodigoUnicoEdit',
            data: {codigo: codigo, productoId: ProductoId},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if(resp.resp){
                    $('#ProductoCodigo').val(resp.codigo);
                    bootbox.alert('El código ya ha sido asignado a otro producto.');
                }
            }
        });        
    }
};

var agregarTag = function() {
// Evento para el botón Agregar
    $('#addTag').on('click', function(e) {
        e.preventDefault();
        addTagMiggo();
    });

    // Evento para la tecla Enter
    $('#tagInput').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            addTagMiggo();
        }
    });
};

// Función para añadir
function addTagMiggo() {
    var input = $('#tagInput');
    var val = input.val().trim().toUpperCase();

    if (val !== "" && !listaTagsMiggo.includes(val)) {
        listaTagsMiggo.push(val);
        input.val('').focus();
        renderizarTagsMiggo();
    }
}

// Función para eliminar (Global)
function removeTagMiggo(index) {
    // 1. Eliminamos del array global
    listaTagsMiggo.splice(index, 1);
    // 2. Volvemos a dibujar
    renderizarTagsMiggo();
}

// Función para dibujar
function renderizarTagsMiggo() {
    var container = $('#tagsContainer');
    var hiddenInput = $('#hiddenTags');
    
    // Limpieza total del contenedor
    container.html('');
    
    // Recorremos el array global actualizado
    $.each(listaTagsMiggo, function(index, tag) {
        var html = '<span class="badge badge-primary m-1 p-2" style="font-size: 14px; display: inline-flex; align-items: center;">' +
                   tag + 
                   ' <i class="fa fa-times ml-2" style="cursor:pointer; color: #fff;" onclick="removeTagMiggo(' + index + ')"></i>' +
                   '</span>';
        container.append(html);
    });

    // Actualizamos el input oculto para CakePHP
    hiddenInput.val(listaTagsMiggo.join(','));
}

var cargarEtiquetas = function() {
    var initialTags = $('#hiddenTags').val();
    if(initialTags) {
        listaTagsMiggo = initialTags.split(',');
        renderizarTagsMiggo();
    }
}

function eliminarImagenItem(id) {
    if (confirm('¿Estás seguro de eliminar esta imagen? Esta acción no se puede deshacer.')) {
        $.ajax({
            type: "POST",
            url: $('#url-proyecto').val() + "productos/eliminar_foto_item/" + id,
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    // Animación de salida
                    $('#foto-container-' + id).fadeOut(300, function() {
                        $(this).remove();
                    });
                } else {
                    alert("Error: " + res.message);
                }
            },
            error: function() {
                alert("No se pudo conectar con el servidor.");
            }
        });
    }
}

var editorDescripcion = function() {
    $('#summernote').summernote({
        placeholder: 'Escribe aquí las características, beneficios y detalles del producto...',
        tabsize: 2,
        height: 250, // Altura del editor
        lang: 'es-ES', // Idioma en español
        toolbar: [
            // Personalizamos la barra para que no sea gigante
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']], // Quitamos 'picture' para no saturar tu DB con base64
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
}

$( function() {
    $('.number').number(true);
    $('#ProductoCodigo').blur(validarCodigoUnico);
    $('#ProductoReferencia').blur(validarReferenciaUnica);
    $('#addTag').click(agregarTag);

    cargarEtiquetas();

    editorDescripcion();
    
});