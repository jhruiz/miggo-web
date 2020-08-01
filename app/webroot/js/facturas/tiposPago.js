/**
 * guarda los metodos de pago y su respectivo valor y llama la funcion para facturar
 * @param {type} arrPayMeth
 * @returns {undefined}
 */
var guardarMetodosValores = function(arrPayMeth){
    $('#btn_facturar_m').attr("disabled", true); 
    
    //se obtiene el id de la prefactura para realizar el abono
    var prefactId = "";
    if (typeof($('#prefactId').val()) == "undefined"){
        prefactId = $('#prefacturaId').val();
    }else{
        prefactId = $('#prefactId').val();
    }    
    
    $.ajax({        
        url: $('#url-proyecto').val() + 'facturaCuentaValores/guardarFacturaCuentasValores', 
        async : false,
        data: {arrPayMeth: arrPayMeth, prefactId: prefactId},
        type: "POST",
        success: function(data) {
             var resp = JSON.parse(data);
             submitForm();
        }
   });      
};

/**
 * factura 
 * @returns {undefined}
 */
var facturarMediosPagos = function(){
    
    if(confirm("¿Está seguro que desea facturar?")){
        var arrPayMeth = [];
        var valFacturado = 0;
        $(this).parents('.form_contenedor_pagos').first().find('.contenedor_pagos').first().find('.dv_tip_val_pago').each(function(){

            if($(this).first().find('.valueFact').val() > 0 && $(this).first().find('.valueFact').val() != ""){            
                var objOptPayMet = new Object();
                objOptPayMet.payment_type = $(this).first().find('.method_fact').val();
                objOptPayMet.val_pay_meth = $(this).first().find('.valueFact').val();

                arrPayMeth.push(objOptPayMet);  

                valFacturado += parseFloat($(this).first().find('.valueFact').val());            
            }else{
                alert('El valor del método de pago no puede ser cero (0) o estar vacio.');
                return false;
            }
        });

        if(valFacturado == $('#totalVentaTipos').val()){
            guardarMetodosValores(arrPayMeth);
        }else{
            alert('El valor ingresado es menor al facturado. Cantidad faltante $' + (parseFloat($('#totalVentaTipos').val() - valFacturado)));
        }      
    }
    
};

/**
 * Agrega un nuevo metodo de pago
 * @returns {undefined}
 */
var duplicarMediosPago = function(){
    var dvContainer = $(this).parents().find('.dv_tip_val_pago').first().clone(true);
    dvContainer.find('.valueFact').val("");
    
    $( ".contenedor_pagos").append(dvContainer);
};

/**
 * borra del formulario un metodo de pago
 * @returns {undefined}
 */
var deletePaymentMethod = function(){    
   if($(this).parents('.contenedor_pagos').first().find('.dv_tip_val_pago').length <= 1){
       bootbox.alert('Debe existir al menos un método de pago.');
   }else{
       $(this).parents(".dv_tip_val_pago").first().remove();
       calcularRestante();
   }
    
};

/**
 * Calcula el valor restante de la factura despues de ingresar un pago en un metodo
 * @returns {undefined}
 */
var calcularRestanteFactura = function(){
    
    if($(this).val() == ""){
        $(this).val('0');
    }else{
        var sPayMet = "";
        var vPayMet = "";
        var cPayMet = 0;

        //se obtiene el metodo de pago seleccionado
        sPayMet = $(this).parents('.dv_tip_val_pago').first().find('.method_fact option:selected').text();
        vPayMet = $(this).parents('.dv_tip_val_pago').first().find('.method_fact').val();

        //recorre los inputs de tipo de pago agregados al formulario para verificar cuantos se han ingresado del mismo tipo
        $(this).parents('.contenedor_pagos').first().find('.method_fact').each(function(){
           if($(this).val() == vPayMet){
              cPayMet ++;
           }
        }); 

        if(cPayMet <= 1){

            var ttalFact = 0;
            $(this).parents('.contenedor_pagos').first().find('.valueFact').each(function(){
               ttalFact += parseFloat($(this).val()); 
            });

            if(ttalFact > parseFloat($('#totalVentaTipos').val())){
                alert('El valor ingresado supera el total de la factura');
                $(this).val('');
            }else{
                var ttalRest = ($('#totalVentaTipos').val() - ttalFact).toLocaleString();
                $('#restante').html(ttalRest);
            }   
        }else{
           alert('Ya existe el método de pago ' + sPayMet);
           $(this).val('');
        }
        
    }
};

var calcularRestante = function(){
   var ttalFact = 0;
   //recorre los inputs de tipo de pago agregados al formulario para verificar cuantos se han ingresado del mismo tipo
   $('.contenedor_pagos').first().find('.dv_tip_val_pago').each(function(){            
         ttalFact += parseFloat($(this).first().find('.valueFact').val());
   });  
   
   var ttalRest = ($('#totalVentaTipos').val() - ttalFact).toLocaleString();
   $('#restante').html(ttalRest);   
    
};

$( function() {
    $('.numericPrice').number(true);    
    $('#btn_agregar').click(duplicarMediosPago);
    $('#btn_facturar_m').click(facturarMediosPagos);
    $('.del_pay_meth').click(deletePaymentMethod);
    $('.valueFact').blur(calcularRestanteFactura);
});
