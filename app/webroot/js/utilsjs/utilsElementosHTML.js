/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/*
 * Funcion que permite habilitar/deshabilitar un input html 
 * 
 * @param {string} idElemento id del input a habilitar/deshabilitar
 * @param {boolean} valor Indica si se quiere habilitar(false) o deshabilitar(true) el input     
 */
function deshabilitarElemento(idElemento, valor) {
    $("#" + idElemento).prop("disabled", valor);
}



function elementoSoloLectura(idElemento, valor){
    if(valor){
        $("#"+idElemento).prop("readonly","readonly");
    }else{
        $("#"+idElemento).removeAttr("readonly");
    }
}



/*
 * Funcion que permite mostrar un elemento html dado su id, se muestra con el efecto slideDown
 * @param {type} elemento_id  id del elemento a mostrar
 * 
 */
function mostrarElementoEfecto(elemento_id) {
    $("#" + elemento_id).slideDown(300);
}



/*
 * Funcion que permite ocultar un elemento html dado su id, se oculta con el efecto slideUp
 * @param {type} elemento_id  id del elemento a ocultar
 * 
 */
function ocultarElementoEfecto(elemento_id) {
    $("#" + elemento_id).slideUp(300);
}



/*
 * Funcion que valida que un texto solo tenga 
 * @param {type} elemento_id  id del elemento a ocultar
 * 
 */
var fnTextoSinCaracteresEspeciales = function(){
    var original_value = $(this).val();
        var regExpr = new RegExp(/[^A-Za-z0-9\s]/g);

        if (regExpr.test(original_value))
            $(this).val(original_value.replace(/[^A-Za-z0-9\s]/g, ''));
}


/*
 * Funcion que valida que un texto solo tenga numeros, letras y puntos, no se permiten espacios
 * @param {type} elemento_id  id del elemento a ocultar
 * 
 */
var fnTextoSinCaracteresEspecialesPunto = function(){
    var original_value = $(this).val();
        var regExpr = new RegExp(/[^A-Za-z0-9\.]/g);

        if (regExpr.test(original_value))
            $(this).val(original_value.replace(/[^A-Za-z0-9\.]/g, ''));
}



$(function(){
    $("body").on('input change paste', '.alfanumeric', fnTextoSinCaracteresEspeciales);        
    $("body").on('input change paste', '.alfanumeric_punto', fnTextoSinCaracteresEspecialesPunto);      
    
});

////Usados para campos que contienen precios
function agregarFormatoNumeros(){
            
    $(".numbers").each(function(){
        
        $(this).number(true,0);        
    });
}

////Usados para campos que contienen numeros, no hay separacion de miles
function agregarFormatoNumerico(){
    $(".inp_numeric").each(function(){
//        $(this).number(true,0);
        $(this).number(true,0,'','');
    });
}




function agregarDatePickerInput(){
    
    var optDatePick={
        dateFormat : "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        monthNames: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        dayNames: ["Lun","Mar","Mie","Jue","Vie","Sab","Dom"],
        dayNamesShort: ["Lun","Mar","Mie","Jue","Vie","Sab","Dom"],
        dayNamesMin: ["Lun","Mar","Mie","Jue","Vie","Sab","Dom"],
        beforeShow: function(){    
                $(".ui-datepicker").css('font-size', 11) 
         }
    };
    
    $(".inp_date").each(function(){
        $(this).datepicker(optDatePick);
    });
}


function quitarFormatoPrecioInputs(){
    
    
    ////Se elimina el input text de los indices tipo precio, se obtiene el valor y se crea un hidden con ese valor y con el mismo name y id, 
    ///Esto para que no se envie con puntuacion los valores a guardar en la bd.
    $(".numbers").each(function(){
        
        var monto=$(this).val();
        var name=$(this).prop("name");
        var id=$(this).prop("id");
        
        var hidden="<input type='hidden' name='"+name+"' id='"+id+"' value='"+monto+"' />";                        
        var parent=$(this).parent();//        
        $(parent).append(hidden);
                                
    });
}


function agregarClaseCamposRequeridos(){
    //Se agrega la clase a los inputs requeridos
        $("input").each(function(){                        
            
            if($(this).prop("required")){
                
                ///A los campos requeridos se les agrega un asterisco
                var label=$("label[for='" + $(this).prop('id') + "']");
                
                
                if(!$(this).hasClass("cls_required")){
                
                    $(this).addClass("cls_required");
                    label.html("* "+label.html());
                    
                    $(this).css("border-color", "#ff2244");
                }
            }
        });
}

