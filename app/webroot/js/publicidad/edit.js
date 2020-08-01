function cambiarVisualizacion(data) {
    var idImg = data.name;
    var visible = $('#' + data.id).prop("checked");
    
    $.ajax({
        url: $('#url-proyecto').val() + 'publicidadmoviles/edit',
        data: { idImg: idImg, visible: visible },
        type: "POST",
        success: function(data) {
            var resp = JSON.parse(data);
            if(!resp.resp){
                bootbox.alert('No fue posible actualizar el registro. Por favor, inténtelo de nuevo.');
                location.reload();
            }
        }
    });     
    
}