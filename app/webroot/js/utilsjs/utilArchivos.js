/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var dialogDocumento;

/**
 * Funcion que realiza un llamado ajax, y permita visualizar el documento djvu dada la url del
 * archivo a visualizar en un dialog de jquery
 * @param String url
 * @param String idDiv
 * @returns {undefined}
 */
function verDocumentodjvu(url, idDiv, descargar, imprimir,recargaDJVUAprobar) {

//        var url=$("#urltmppaquete").val();        

//    alert(imprimir+"descargar imprimir "+descargar);

    if (url == null || typeof url === "undefined" || url == "") {
        bootbox.alert("Debe seleccionar y cargar un archivo.");
    } else {
        $.ajax({
            url: $('#url-proyecto').val() + "paquetes/verdocumentodjvu",
            data: {url: url,descargar: descargar, imprimir: imprimir},
            type: "POST",
            success: function (data) {

                var dialogDJvu = {
                    autoOpen: false,
                    modal: false,
                    width: 800,
                    height: 520,
                    position: [100, 30],
                    show: {
                        effect: "blind",
                        duration: 1000
                    },
                    close: function(){
                        
                        ///Si se llama desde la pantalla de gestion al cerrar el dialog se recarga el archivo embebido en la pagina, 
                        //el djvu para aprobar ya que el plugin de djvu oculta la barra de herramientas
                           if(recargaDJVUAprobar){
                               cargarArchivoDJVUAprobar();
                           }                        
                    },
                    
                    title: 'Documento'
                };

                ///Se crea un div y en un pop up se muestra el archivo djvu                
                var nombreNuevoDiv="verDocumento";
                                
                for(var i=0; i<5; i++){                                                               
                    ///Se verifica si ya hay un div que se llame verDocumento se crea otro con un consecutivo
                    if($("#"+nombreNuevoDiv).length>0){
                        nombreNuevoDiv+=i+"";
                    }else{
                        
                        $("#" + idDiv).html("<div id='"+nombreNuevoDiv+"'></div>");
                        $("#"+nombreNuevoDiv).html(data);
                        
                        dialogDocumento = $("#"+nombreNuevoDiv).dialog(dialogDJvu);
                        dialogDocumento.dialog('open');
                        
                        break;
                    }
                }                                                
            }
        });
    }
}


function cargardjvudiv(url, idDiv, descargar, imprimir){
    if (url == null || typeof url === "undefined" || url == "") {
        bootbox.alert("Debe seleccionar y cargar un archivo.");
    } else {
        $.ajax({
            url: $('#url-proyecto').val() + "paquetes/verdocumentodjvu",
            data: {url: url,descargar: descargar, imprimir: imprimir},
            type: "POST",
            success: function (data) {
                 var nombreNuevoDiv="verDocumento";
                $("#" + idDiv).html("<div id='"+nombreNuevoDiv+"'></div>");
                $("#"+nombreNuevoDiv).html(data);
            }
        });
    }
}

/*
 * Permite cargar el archivo a indexar de manera temporal y generar el archivo 
 * djvu, para luego poderlo visualizar
 */
function cargarArchivoTmp(idInputFile,nombArchivo,idBotVistaPrevia,idBotCargarArchivo,idUrlTmpPaq,idExtArchivo,idDivModal) {

    deshabilitarElemento(idBotCargarArchivo, true);
        
    ///Se valida que haya seleccionado previamente un archivo
    if ($("#"+idInputFile).val() == "") {
        deshabilitarElemento(idBotCargarArchivo, false);
        bootbox.alert("Debe seleccionar un archivo.");
        
    } else {
        
        var fileValid=true;
        ///Se valida la extension del archivo que sea pdf
        var arrExtFile=$("#"+idInputFile).val().split(".");
        if(arrExtFile.length>0){
            var extFile=arrExtFile.pop().toLowerCase();
             if(extFile!="pdf"){
                bootbox.alert("Extension del archivo no valida, debe ser un archivo en formato pdf.");
                fileValid= false;
            }
        }else{
            bootbox.alert("Extension del archivo no valida, debe ser un archivo en formato pdf.");
            fileValid= false;
        }
       
       ///Si el archivo seleccionado es valido, se carga el archivo al servidor
        if(fileValid){
            
            mostrarModal(idDivModal);
            
            $("#"+idInputFile).upload(
                $('#url-proyecto').val() + "paquetes/cargueArchivoTmpAjax",
                {
                    iddocindexarform: nombArchivo
                },
                function (respuesta) {
                    var datos = eval(respuesta);

                    //Al finalizar el cargue del documento, se habilita el boton de vista previa
                    if (datos.estado) {
                        var urlArchivoTmp = datos.nombre_tmp;
                        var extensionArchivo = datos.extension;
                        $("#"+idUrlTmpPaq).val(urlArchivoTmp);
                        $("#"+idExtArchivo).val(extensionArchivo);
                        deshabilitarElemento(idBotVistaPrevia, false);
                        deshabilitarElemento(idBotCargarArchivo, true);                                                
                        
                        bootbox.alert("Cargue de archivo realizado correctamente.");
                    } else {
                        deshabilitarElemento(idBotCargarArchivo, false);
                        alert(datos.msj);
                    }
                    
                    ocultarModal(idDivModal);
//                    
                }
            );
        }else{
            deshabilitarElemento(idBotCargarArchivo, false);
        }
    }
        
}

