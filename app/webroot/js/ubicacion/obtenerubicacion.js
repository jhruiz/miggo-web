var obtenerDptos = function() {

    var pais = $('.selectPais').val();
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'departamentos/obtenerdepartamentos',
        data: {pais: pais},
        success: function(data) {
            var respuesta = JSON.parse(data);
            var $select = $('.selectDpto'); // O el ID que genere CakePHP (normalmente CamelCase)

            // 1. Limpiar el select actual y dejar la opción vacía
            $select.find('option').remove();
            $select.append('<option value="">Seleccione Departamento</option>');

            // 2. Recorrer la respuesta (objeto) y agregar las opciones
            // Usamos el objeto "resp" que mencionas en tu ejemplo
            $.each(respuesta.resp, function(id, nombre) {
                $select.append('<option value="' + id + '">' + nombre + '</option>');
            });

            // 3. ¡IMPORTANTE! Refrescar Select2
            // Si no haces esto, verás el select vacío aunque el HTML tenga los datos
            if ($select.hasClass('select2')) {
                $select.trigger('change');
            }
        }
    });       
}

var obtenerCiudades = function() {
    var dpto = $('.selectDpto').val();
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'ciudadesmiggos/obtenerciudades',
        data: {dpto: dpto},
        success: function(data) {
            var respuesta = JSON.parse(data);
            var $select = $('.selectCiudad'); // O el ID que genere CakePHP (normalmente CamelCase)

            // 1. Limpiar el select actual y dejar la opción vacía
            $select.find('option').remove();
            $select.append('<option value="">Seleccione Ciudad</option>');

            // 2. Recorrer la respuesta (objeto) y agregar las opciones
            // Usamos el objeto "resp" que mencionas en tu ejemplo
            $.each(respuesta.resp, function(id, nombre) {
                $select.append('<option value="' + id + '">' + nombre + '</option>');
            });

            // 3. ¡IMPORTANTE! Refrescar Select2
            // Si no haces esto, verás el select vacío aunque el HTML tenga los datos
            if ($select.hasClass('select2')) {
                $select.trigger('change');
            }
        }
    }); 
}