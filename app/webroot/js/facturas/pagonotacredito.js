/**
 * Ejecuta la funcion de eliminar factura que restaura el inventario y cambia el
 * estado de la factura
 * @param {*} facturaId 
 */
function generarNotaCreditoFactura() {
    var facturaId = $('#facturaId').val(); 
    $.ajax({        
        url: $('#url-proyecto').val() + 'facturas/delete', 
        async : false,
        data: {facturaId: facturaId},
        type: "POST",
        success: function(data) {
             var resp = JSON.parse(data);
             alert(resp.resp);
             location.reload();
        }
    });  
}

/**
 * guarda los metodos de pago y su respectivo valor y llama la funcion para facturar
 * @param {type} arrPayMeth
 * @returns {undefined}
 */
 var guardarMetodosValores = function(arrPayMeth){
    $('#btn_notacredito_m').attr("disabled", true); 
    
    $.ajax({        
        url: $('#url-proyecto').val() + 'gastos/gastoNotaCredito', 
        async : false,
        data: {arrPayMeth: arrPayMeth},
        type: "POST",
        success: function(data) {
             var resp = JSON.parse(data);
             generarNotaCreditoFactura();
        }
   });      
};

/**
 * factura 
 * @returns {undefined}
 */
 var facturarMediosPagos = function(){
    
    if(confirm("¿Está seguro que desea generar la nota crédito?")){
        var arrPayMeth = [];
        var valFacturado = 0;
        $(this).parents('.form_contenedor_pagos').first().find('.contenedor_pagos').first().find('.dv_tip_val_pago').each(function(){

            // if($(this).first().find('.valueFact').val() > 0 && $(this).first().find('.valueFact').val() != ""){            
                var objOptPayMet = new Object();
                objOptPayMet.payment_type = $(this).first().find('.method_fact').val();
                objOptPayMet.val_pay_meth = $(this).first().find('.valueFact').val();

                arrPayMeth.push(objOptPayMet);  

                valFacturado += parseFloat($(this).first().find('.valueFact').val());            
        });

        if(valFacturado == $('#totalFactura').val()){
            guardarMetodosValores(arrPayMeth);
        }else{
            alert('El valor ingresado es menor a la nota crédito. Cantidad faltante $' + (parseFloat($('#totalFactura').val() - valFacturado)));
        }      
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

            if(ttalFact > parseFloat($('#totalFactura').val())){
                alert('El valor ingresado supera el total de la factura');
                $(this).val('');
                calcularRestante();
            }else{
                var ttalRest = ($('#totalFactura').val() - ttalFact).toLocaleString();
                $('#restante').html(ttalRest);
            }   
        }else{
           alert('Ya existe el método de pago ' + sPayMet);
           $(this).val('');
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
  * Calcula el valor restante del pago de la nota credito
  */
 var calcularRestante = function(){
    var ttalFact = 0;
    //recorre los inputs de tipo de pago agregados al formulario para verificar cuantos se han ingresado del mismo tipo
    $('.contenedor_pagos').first().find('.dv_tip_val_pago').each(function(){            
          ttalFact += parseFloat($(this).first().find('.valueFact').val());
    });  
    
    var ttalRest = (parseFloat($('#totalFactura').val()) - parseFloat(ttalFact)).toLocaleString();
    $('#restante').html(ttalRest);   
     
 }; 

$( function() {
    $('#valorFactura').val('0');
    $('.numericPrice').number(true);    
    $('#btn_agregar').click(duplicarMediosPago);
    $('#btn_notacredito_m').click(facturarMediosPagos);
    $('.del_pay_meth').click(deletePaymentMethod);
    $('.valueFact').blur(calcularRestanteFactura);
});