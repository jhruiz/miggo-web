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

function imprimirDocumento() {
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
    mywindow.document.write('<html><head>');
    mywindow.document.write('<style>');
    mywindow.document.write('@page { size: auto; margin: 0; }'); // Eliminar márgenes de la página
    mywindow.document.write('body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }'); // Tamaño de fuente base
    mywindow.document.write('table { border-collapse: collapse; width: 100%; margin-bottom: 20px; font-size: 12px; }'); // Estilo de la tabla
    mywindow.document.write('th { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }'); // Estilo de las celdas de encabezado
    mywindow.document.write('td { border: 1px solid #ddd; padding: 8px; font-size: 12px; }'); // Estilo de las celdas
    mywindow.document.write('th { background-color: #f5f5f5; font-weight: bold; }'); // Fondo del encabezado
    mywindow.document.write('tr:nth-child(even) { background-color: #f9f9f9; }'); // Fondo alterno para filas
    mywindow.document.write('.align-left { text-align: left; }'); // Clase para alinear a la izquierda
    mywindow.document.write('.align-right { text-align: right; }'); // Clase para alinear a la derecha
    mywindow.document.write('@media print {');
    mywindow.document.write('  @page { margin: 0; }'); // Eliminar márgenes en la impresión
    mywindow.document.write('  body { margin: 1cm; }'); // Ajustar márgenes del contenido
    mywindow.document.write('  .no-print, .no-print * { display: none !important; }'); // Ocultar elementos no deseados
    mywindow.document.write('}');
    mywindow.document.write('</style>');
    mywindow.document.write('</head>');
    mywindow.document.write('<body>');
    mywindow.document.write('<div style="font-family:sans-serif; font-size:15px;">');
    mywindow.document.write($('#documentos').html()); // Aquí se incluye el contenido de #documentos
    mywindow.document.write('</div>');
    mywindow.document.write('</body></html>');
    mywindow.document.title = "Documento";
    mywindow.document.close();
    mywindow.focus();
    mywindow.print();
    mywindow.close();      
}