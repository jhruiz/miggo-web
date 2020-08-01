/**
 * se valida el ingreso de una descripcion y guarda actualiza el nuevo valor
 * @returns {undefined}
 */
var actualizarGasto = function(){
    var nueva_desc = $('#new_descripcion').val();
    if(nueva_desc != ""){
        var usuarioregistra_id = $('#usuarioregistra_id').val();
        var gasto_id = $('#id').val();
        var descripcion = $('#descripcion').val();
        var valor_actual = $('#val_actual').val();
        var valor_nuevo = $('#val_nuevo').val();
        var cuenta_id = $('#cuenta_id').val();
        
        $.ajax({
            url: $('#url-proyecto').val() + 'gastos/actualizargasto',
            data: {usuarioregistra_id : usuarioregistra_id, gasto_id : gasto_id, descripcion : descripcion, 
                valor_actual : valor_actual, valor_nuevo : valor_nuevo, nueva_desc: nueva_desc, cuenta_id: cuenta_id},
            type: "POST",
            success: function(data) {                
                var resp = JSON.parse(data);
                if(resp.resp){
                    window.location.href = $('#url-proyecto').val() + 'gastos/index';
                }else{
                    bootbox.alert('No fue posible realizar la actualización del gasto.');
                }
            }
        });         
        
        
    }else{
        bootbox.alert('Debe ingresar una descripción indicando el motivo por el cual se cambia el valor del gasto.');        
    }
};

/**
 * se valida el nuevo valor del gasto
 * @returns {undefined}
 */
var validarNuevoValor = function(){    
    var valNuevo = $('#val_nuevo').val().replace(",", "");
    var valActual = parseFloat($('#val_actual').val().replace(",", ""));
    
    if(valNuevo != ""){
        if(parseFloat(valActual) != valNuevo){
            $('#act_gasto').modal('show');
        }else{
            bootbox.alert('El nuevo valor es igual al valor actual.');
        }
        
    }else{
        bootbox.alert('Debe ingresar un nuevo valor para el gasto');
    }
    
};

$(function() {
    $('#preguardar').click(validarNuevoValor);
    $('#guardar_gasto').click(actualizarGasto); 
    $('.numericPrice').number(true);
});


