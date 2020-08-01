/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function mostrarModal(div) {

//    console.log($("#" + div).css());

    var width = $("#" + div).css("width");
    var height = $("#" + div).css("height");
    var top = $("#" + div).css("top");
    var left = $("#" + div).css("left");

//    console.log("width: " + width + ", height: " + height + ", top: " + top + ", left: " + left);

//    console.log("mostrarModal " + div);
    var nomDiv = "modalCargar_" + div;

    if (!($("#" + nomDiv).length)) {

        var rutaImagen = $("#url-proyecto").val() + "img/ajax-loader-ind-big.gif";

        var $div = $("<div id='modalCargar_" + div + "' />");
        $div.css({
            display: 'block',
//            background: '#DDDDDD',
//            opacity: 0.5,
            position: 'absolute',
            width: width,
            height: height,
            top: top,
            left: left
        });
        $div.attr('align', 'center');
//        $div.attr('vertical-align', 'middle');
        $div.html("<img src='" + rutaImagen + "' />");

        $("#" + div).append($div);

    } else {
        $("#" + nomDiv).show();
//        console.log("Modal " + nomDiv + " ya existe.");
    }

}

function ocultarModal(div) {

//    console.log("ocultarModal " + div);

    var nomDiv = "modalCargar_" + div;

    if ($("#" + nomDiv).length) {
        $("#" + nomDiv).hide();
    } else {
//        console.log("Modal " + nomDiv + " no existe");
    }
}