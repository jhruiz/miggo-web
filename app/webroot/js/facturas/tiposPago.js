/**
 * guarda los metodos de pago y su respectivo valor y llama la funcion para facturar
 * @param {type} arrPayMeth
 * @returns {undefined}
 */
var guardarMetodosValores = function(arrPayMeth){ 
    $('.icon-container').show();
    $('#btn_facturar_m').css('display', 'none');
    $('#btn_agregar').css('display', 'none');
    
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
                bootbox.alert('El valor del método de pago no puede ser cero (0) o estar vacio.');
                return false;
            }
        });

        if(valFacturado == $('#totalVentaTipos').val()){
            guardarMetodosValores(arrPayMeth);
        }else{
            bootbox.alert('El valor ingresado es menor al facturado. Cantidad faltante $' + (parseFloat($('#totalVentaTipos').val() - valFacturado)));
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
    let tipoPago=dvContainer.find('.method_fact');
    tipoPago.val(0);
    mostrarCamposDevoluciones(tipoPago);
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
                bootbox.alert('El valor ingresado supera el total de la factura');
                $(this).val('');
                calcularRestante();
            }else{
                var ttalRest = ($('#totalVentaTipos').val() - ttalFact).toLocaleString();
                $('#restante').html(ttalRest);
            }
        }else{
           bootbox.alert('Ya existe el método de pago ' + sPayMet);
           $(this).val('');
        }
        
    }
};

/**
 * Calcula el valor restante de la factura
 */
var calcularRestante = function(){
   var ttalFact = 0;
   //recorre los inputs de tipo de pago agregados al formulario para verificar cuantos se han ingresado del mismo tipo
   $('.contenedor_pagos').first().find('.dv_tip_val_pago').each(function(){            
         ttalFact += parseFloat($(this).first().find('.valueFact').val());
   });  
   
   var ttalRest = ($('#totalVentaTipos').val() - ttalFact).toLocaleString();
   $('#restante').html(ttalRest);   
    
};

const calcularDevolucion = function(){
    let valorFactura=$(this).parents('.dv_tip_val_pago').find(".valueFact");
    let devolucion=$(this).parents('.dv_tip_val_pago').find('.devolution');
    if ($(this).val()!=0 && (valorFactura.val()==null || valorFactura.val()=="" || valorFactura.val()=="0")) {
        bootbox.alert('Se debe especificar el valor a pagar.');
        $(this).val(0);
    }
    if (parseFloat($(this).val())<parseFloat(valorFactura.val())) {
        bootbox.alert('No le alcanza para pagar el valor especificado.');
        devolucion.html(0);
        return;
    }
    devolucion.html($(this).val()-valorFactura.val());
 };

 const tipoPagoChange=function(){
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
     if(cPayMet > 1){
        bootbox.alert('Ya existe el método de pago ' + sPayMet);
        $(this).val(0);
    }
    mostrarCamposDevoluciones($(this));
 }

 const mostrarCamposDevoluciones = function(tipoPago){
    if (tipoPago.first().find('option:selected').text()!="EFECTIVO" && tipoPago.val()!=0 ) {
        tipoPago.parents('.dv_tip_val_pago').first().find('.divValueClientPaid').hide()
        tipoPago.parents('.dv_tip_val_pago').first().find('.divDevolution').hide();
    }else{
        tipoPago.parents('.dv_tip_val_pago').first().find('.divValueClientPaid').show()
        tipoPago.parents('.dv_tip_val_pago').first().find('.divDevolution').show();
    }
 }


$( function() {
    $('.numericPrice').number(true, 2);   
    $('#btn_agregar').click(duplicarMediosPago);
    $('#btn_facturar_m').click(facturarMediosPagos);
    $('.del_pay_meth').click(deletePaymentMethod);
    $('.valueFact').blur(calcularRestanteFactura);
    $('.valueClientPaid').blur(calcularDevolucion);
    $('.method_fact').on("change",tipoPagoChange);
});
