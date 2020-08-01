var opcDialogNotaCargueInventario = {
        autoOpen: false,
        modal: true,
        width: 600,
        height: 300,
        position: [400, 50],
        show: {
            duration: 400    
        },
        hide: function () {
        },
        close: function( event, ui){         
        },
        title: 'Nota Para Documento'    
};

var dialogNotaCargueInventario;

function agregarNotaDocumento(dato){
    $("#div_anotacion").load(
        $('#url-proyecto').val() + "anotaciones/notacargueinventario",
        {usuarioId:dato.name},
        function(){                                                            
            dialogNotaCargueInventario=$("#div_anotacion").dialog(opcDialogNotaCargueInventario);
            dialogNotaCargueInventario.dialog('open');
        }
    );      
}

function guardarCargueInventario(dato){    
    
    var nota = $('#notaCargue').val(); 
    var documentoId = $('#documentoId').val();
    
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'documentos/guardarnotadocumentoajax',
        data: {nota: nota, usuarioId: dato.name, documentoId:documentoId},
        success: function(data) {
            var respuesta = JSON.parse(data);
            if(respuesta.resp){
                bootbox.alert('Se guarda la nota para el documento.', function(){
                    location.reload(); 
                });                               
            }else{
                bootbox.alert('No se guarda la nota para el documento. Por favor, inténtelo de nuevo.', function(){
                    location.reload(); 
                });
            }            
        }
    });      
    
}