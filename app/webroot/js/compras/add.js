var opcReteica = {
        autoOpen: false,
        modal: true,
        width: 600,
        height: 400,
        position: [400, 50],
        show: {
            duration: 400    
        },
        hide: function () {
        },
        close: function( event, ui){           
        },
        title: 'Seleccione una opción'    
};

var dialogReteica;

var opcRetefuente = {
        autoOpen: false,
        modal: true,
        width: 600,
        height: 400,
        position: [400, 50],
        show: {
            duration: 400    
        },
        hide: function () {
        },
        close: function( event, ui){           
        },
        title: 'Seleccione una opción'    
};

var dialogRetefuente;
var val_retefuente = 0;
var val_reteica = 0;
var ttalIvaG = 0;
var valueRetefuente = 0;
var valueReteica = 0;
var idRetefuente = "";
var idReteica = "";
var arrCuentas = [];
var valTtal = 0;

/**
 * Agrega una nueva categoria al listado de categorias
 * @returns {undefined}
 */
var agregarCategoría = function(){
    var html = "";
    var cat = $('#categoria_id option:selected').text();
    var idCat = $('#categoria_id').val();
    var val = $('#valCat').val();
    
    if(cat != "" && val != ""){
        
        //se obtiene la fecha actual 
        var fecha= new Date();
        var seg = fecha.getSeconds();
        
        html += "<tr class='tr_" + idCat + "_" + seg + "'><td>" + cat + "<input type='hidden' id='cat_" + idCat + "_" + seg + "' value='" + idCat + "'></td>";
        html += "<td class='val_cat'>$" + formatNumber(val) + "<input type='hidden' class='categoryVal' id='val_" + idCat + "_" + seg + "' value='" + val + "'></td>";
        html += "<td><button type='button' name='del_" + idCat + "_" + seg + "' id='del_" + idCat + "_" + seg + "' ";
        html += "class='btn btn-danger' onClick='deleteCategory(this)' aria-label='Left Align'> ";
        html += "<span class='fa fa-trash-o fa-lg' aria-hidden='true'></span></button></td></tr>"; 
        
        $('#category').append(html);
        $('#categoria_id').val("");
        $('#valCat').val("");        
        sumarValorAntesImpuestos();
        recalcularRetefuente();
        recalcularReteica();
        calcularTotal();
    }else{
        bootbox.alert('Debe seleccionar una categoría e ingresar un valor para la misma');
    }
};

/**
 * Elimina una categoria del listado de categorias
 * @param {type} dat
 * @returns {undefined}
 */
function deleteCategory(dat){
    
    bootbox.confirm('¿Está seguro que desea quitar del listado la categoría?', function(result){
        if(result){
            var idx = (dat.name).split('_');
            $(".tr_" + idx['1'] + "_" + idx['2']).remove();
            sumarValorAntesImpuestos();
            recalcularRetefuente();
            recalcularReteica();   
            calcularTotal();
            addPaymentToTable();
        } else {}
    }); 

};

function deletePaymenth(dat){
    bootbox.confirm('¿Está seguro que desea eliminar este pago?', function(result){
        if(result){
            var idx = (dat.name).split('_');
            var flg = false;
            var pos = '';
            //elimina el pago del arreglo de pagos
            arrCuentas.forEach(function(elem, index){
                if(elem.cuenta == idx['1']){
                    pos = index;
                    flg = true;
                }
            });

            if(flg){
                arrCuentas.splice(pos, 1)            
                $('.tr_' + idx['1']).remove();

                addPaymentToTable();

            }else{
                bootbox.alert('No fue posible eliminar el pago. Por favor, inténtelo de nuevo.');
            }            

        }
    });
}

/**
 * formatea cualquier valor a numeros con separadores de miles
 * @param {type} num
 * @returns {unresolved}
 */
function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
}

/**
 * Agrega el subtotal antes de iva
 * @returns {undefined}
 */
function sumarValorAntesImpuestos(){
    var sumTtalAI = obtenerTtalAntesImpuestos();
    var html = "";   
    html += "<tr><td><b>SUBTOTAL</b></td><td style='border-top:2pt solid black;'><b>$" + formatNumber(sumTtalAI) + "</b></td><td>&nbsp;</td></tr>";
    $('#subTtalAntImp').html(html);    
    
    calcularIvaCompra(sumTtalAI);
}

/**
 * Obtiene el pop up con el listado de retefuentes
 * @returns {undefined}
 */
var agregarRetefuente = function(){
    if($('.categoryVal').length > 0){        
        $(".dv_retefuente").load(
            $('#url-proyecto').val() + "compras/obtenerlistaretefuente",
            {},
            function(){                                                            
                dialogRetefuente=$(".dv_retefuente").dialog(opcRetefuente);
                dialogRetefuente.dialog('open');
            }
        );        
    }else{
        bootbox.alert('Debe seleccionar al menos una opción de impuesto para aplicar.');
    }
};

/**
 * Obtiene el pop up con el listado de reteica
 * @returns {undefined}
 */
var agregarReteica = function(){
    if($('.categoryVal').length > 0){
        $(".dv_reteica").load(
            $('#url-proyecto').val() + "compras/obtenerlistareteica",
            function(){                                                            
                dialogReteica=$(".dv_reteica").dialog(opcReteica);
                dialogReteica.dialog('open');
            }
        );        
    }else{
        bootbox.alert('Debe seleccionar al menos una opción de impuesto para aplicar.');
    }
};

/**
 * Calcula el valor del iva de la compra
 * @param {type} sumTtalAI
 * @returns {undefined}
 */
var calcularIvaCompra = function(sumTtalAI){
    var html = "";
    var iva = parseInt($('#imp_val').val());
    var ivaMod = parseFloat((iva/100)+1).toFixed(2);
    var ttalIva = parseFloat(sumTtalAI * ivaMod) - parseFloat(sumTtalAI);
    html += "<tr><td><b>IVA</b></td><td>$" + formatNumber(ttalIva) + "</td><td>&nbsp;</td></tr>";
    $('#IvaVal').html(html);
    ttalIvaG = ttalIva;
};

/**
 * valida que solo se seleccione un impuesto
 * @param {type} imp
 * @returns {undefined}
 */
function cantidadImp(imp){
    var contador = 0;
    $("." + imp).each(function(){
        if($(this).is(":checked")){
            contador++;
        }            
    });
    
    if(contador > 1){
        bootbox.alert('Solo puede aplicar un impuesto.');
        $('.' + imp).prop('checked', false); 
    }
}

/**
 * obtiene el valor de la retefuente a traves del listado en el pop up
 * @returns {undefined}
 */
function aplicarRetefuente(){
    var value = "";
    $(".chkRtfte").each(function(){
        if($(this).is(":checked")){
            value = $(this).val();
            idRetefuente = $(this).data('id');
        }            
    });   
    
    if(value == ""){
        bootbox.alert("Debe seleccionar al menos una opción de Retefuente.");
    }else{
        var prcImp = parseFloat((parseFloat(value)/100) + 1).toFixed(4);
        val_retefuente = prcImp;
        var ttalAntesImp = obtenerTtalAntesImpuestos();
                
        var rtefteSttal = parseFloat((parseFloat(ttalAntesImp * prcImp)) - parseFloat(ttalAntesImp)).toFixed(0);
        $('.sp_retefuente').text(formatNumber(rtefteSttal));
        valueRetefuente = rtefteSttal;
        calcularTotal();
        dialogRetefuente.dialog('close');
    }
}

/**
 * obtiene el valor del reteica a traves del listado en el pop up
 * @returns {undefined}
 */
function aplicarReteica(){
    var value = "";
    $(".chkRtIca").each(function(){
        if($(this).is(":checked")){
            value = $(this).val();
            idReteica = $(this).data('id');
        }            
    });   
    
    if(value == ""){
        bootbox.alert("Debe seleccionar al menos una opción de Reteica.");
    }else{
        console.log(value);
        var prcImp = parseFloat((parseFloat(value)/100) + 1).toFixed(4);
        console.log(prcImp);
        val_reteica = prcImp;
        var ttalAntesImp = obtenerTtalAntesImpuestos();
        var rteIcaSttal = parseFloat(parseFloat(ttalAntesImp * prcImp) - parseFloat(ttalAntesImp)).toFixed(0);
        $('.sp_reteica').text(formatNumber(rteIcaSttal));
        valueReteica = rteIcaSttal;
        calcularTotal();
        dialogReteica.dialog('close');
    }    
}

/**
 * Obtiene el total de las categorias antes de impuestos
 * @returns {Number}
 */
function obtenerTtalAntesImpuestos(){
    var sumTtalAI = 0;
    $('.categoryVal').each(function(index){
        sumTtalAI = parseInt($(this).val()) + parseInt(sumTtalAI);
    }); 
    
    return sumTtalAI;
}

/////////////RECALCULA LA RETEFUENTE Y EL RETEICA POR AGREGAR O ELIMINAR CATEGORIAS
function recalcularRetefuente(){
    if(val_retefuente > 0){
        var valAntesImp = obtenerTtalAntesImpuestos();
        valueRetefuente = parseFloat(parseFloat(valAntesImp * val_retefuente) - parseFloat(valAntesImp)).toFixed(0);
        $('.sp_retefuente').text(formatNumber(valueRetefuente));  
    }   
}

function recalcularReteica(){
    if(val_reteica > 0){
        var valAntesImp = obtenerTtalAntesImpuestos();
            valueReteica = parseFloat(parseFloat(valAntesImp * val_reteica) - parseFloat(valAntesImp)).toFixed(0);
        $('.sp_reteica').text(formatNumber(valueReteica));       
    }
}
/////////////RECALCULA LA RETEFUENTE Y EL RETEICA POR AGREGAR O ELIMINAR CATEGORIAS

/**
 * Calcula el total final sumando al subtotal el iva, menos reteica, menos retefuente
 */
function calcularTotal(){    
    var valAntesImp = obtenerTtalAntesImpuestos();
    var iva = parseFloat(ttalIvaG);
    var retefuente = parseFloat(valueRetefuente);
    var reteica = parseFloat(valueReteica);
    
    valTtal = valAntesImp + iva - retefuente - reteica;
    $('.ttal').text(formatNumber(valTtal)); 
    $('.ttalFact').text(formatNumber(valTtal)); 
    addPaymentToTable();
}

/**
 * valida que se seleccione una categoria
 * @returns {undefined}
 */
function validarCategoria(){
    if($('#categoria_id').val() == ""){
        bootbox.alert('Debe seleccionar una categoría');
        $(this).val("");
    }
}

/**
 * obtiene los datos generales para guardar la compra
 * @returns {obtenerDatosGenerales.objCompra|Object}
 */
var obtenerDatosGenerales = function(){
    
    var objCompra = new Object();
    objCompra.prcIVA = parseFloat((parseFloat($('#imp_val').val())/100)+1).toFixed(2);
    objCompra.vlrIVA = ttalIvaG;
    objCompra.prcRetefuente = val_retefuente;
    objCompra.vlrRetefuente = valueRetefuente;
    objCompra.prcReteica = val_reteica;
    objCompra.vlrReteica = valueReteica;
    objCompra.usuarioId = $('#usuario_id').val();        
    objCompra.proveedorId = $('#proveedore_id').val();
    objCompra.fechaFact = $('#fechaFactura').val();
    objCompra.numFactura = $('#numFactura').val();   
    objCompra.idRetefuente = idRetefuente;
    objCompra.idReteica = idReteica;
    return objCompra;
};

/**
 * se obtienen las categorias de las compras
 * @returns {Array|obtenerCategoriasCompra.arrCompras}
 */
var obtenerCategoriasCompra = function(){

    var arrCompras = new Array();
    $('.categoryVal').each(function(index){
        var objCategorias = new Object();
        
        var idValCat = $(this).attr('id');
        var arrIdCat = idValCat.split("_");
        
        objCategorias.id = arrIdCat['1'];
        objCategorias.valor = $(this).val();
        
        arrCompras.push(objCategorias);        
    });    
    
    return arrCompras;
    
    
};

/**
 * guarda el registro de la compra
 * @returns {undefined}
 */
var guardarCompra = function(){    
    
    bootbox.confirm('¿Está seguro que desea guardar la compra?', function(result){
        if(result){
            //se valida que el formulario se encuentre completo
            var mensaje = validarFormulario();

            if(mensaje == ""){

                var objCompra = new Object();
                var arrCatCompra = new Array();

                //obtiene la informacion general de la compra
                objCompra = obtenerDatosGenerales();

                //obtiene las categorias de la compra
                arrCatCompra = obtenerCategoriasCompra();

                $.ajax({
                    type: 'POST',
                    url: $('#url-proyecto').val() + 'compras/guardarcompradetalle',
                    data: {objCompra: objCompra, arrCatCompra: arrCatCompra, arrPago: arrCuentas},
                    success: function(data) {
                        var resp = JSON.parse(data);
                        if(resp.resp){
                            bootbox.alert('La compra ha sido registrada.', function(){
                                location.reload();
                            });
                            
                        }else{
                            bootbox.alert('No fue posible guardar la compra. Por favor, inténtelo nuevamente.');
                        }
                    }
                });                

            }else{
                bootbox.alert(mensaje);
            }
        } else {}
    });     
};

/**
 * valida que el formulario se encuentre diligenciado
 * @returns {unresolved}
 */
var validarFormulario = function(){
    var msj = "";
    
    //valida que se haya seleccionado el proveedor
    if($('#proveedore_id').val() == ""){
        msj += "- Debe seleccionar un proveedor.<br>";
    }
    
    //valida que se haya ingresado la fecha de la factura
    if($('#fechaFactura').val() == ""){
        msj += "- Debe seleccionar la fecha de la factura.<br>"; 
    }
    
    //valida que se haya ingresado el numero de la factura
    if($('#numFactura').val() == ""){
        msj += "- Debe ingresar el número de la factura.<br>";
    }
    
    //valida si se seleccionó al menos una categoria
    if($('.categoryVal').length <= 0){
        msj += "- Debe seleccionar al menos una categoría.<br>";
    }    
    
    return msj;
};


////////////////////////////// CALCULA EL PAGO DE LA FACTURA ///////////////////////////
function addPaymentToTable() {
    var html = "";
    var ttalPago = 0;
    arrCuentas.forEach(function(elem, index){

        ttalPago += parseInt(elem.valor);

        html += '<tr class="tr_' + elem.cuenta + '"><th>' + elem.tipo + '</th>';
        html += '<th>' + elem.nombre + '</th>';
        html += '<th><b> $' + formatNumber(elem.valor) + '</b></th>';
        html += "<th><button type='button' id='delpay_" + elem.cuenta + "' name='delpay_" + elem.cuenta + "' ";
        html += "class='btn btn-danger btn-xs' onClick='deletePaymenth(this)' aria-label='Left Align'> ";
        html += "<span class='fa fa-trash-o fa-lg' aria-hidden='true'></span></button></th></tr>"; 
    });

    $('#metPagos').html(html);
    $('.ttalPay').text(formatNumber(ttalPago));
    $('.ttalDiffPay').text(formatNumber(parseInt(valTtal) - parseInt(ttalPago)));

}

/**
 * Valida si debe habilitar los metodos de pago, valor y selección de cuenta (si es necesario)
 */
var validatePayMeth= function() {
    $valTypPay = $('#tipopago_id').val();

    if($valTypPay == '1'){
        $('#valuePaymenth').prop('disabled', false);
        $('#cuenta_id').prop('disabled', false);
    }else if($valTypPay == '2'){
        $('#valuePaymenth').prop('disabled', false);
        $('#cuenta_id').prop('disabled', true).val('');
    }else{
        $('#valuePaymenth').prop('disabled', true).val('');
        $('#cuenta_id').prop('disabled', true).val('');
    }
}

/**
 * valida la cuenta y/o tipo de pago ya tiene registro para obtener la 
 * sumatoria de descuento real para la cuenta
 */
function getRealValueToDescount() {
    var value = $('#valuePaymenth').val();

    var realValue = value;

    arrCuentas.forEach(function(elem, index){
        if(elem.cuenta == $('#cuenta_id').val()){
            realValue = parseInt(elem.valor) + parseInt(value);
        }
    });    

    return realValue;

}

/**
 * Valida si la cuenta tiene fondos suficientes para el pago
 */
var validateCountAmount = function() {
    //obtiene el valor a pagar
    var valuePaymenth = getRealValueToDescount();
    
    if(valuePaymenth > 0){

        $.ajax({
            type: 'POST',
            url: $('#url-proyecto').val() + 'cuentas/ajaxvalidarmontocuenta',
            data: {montoGasto: valuePaymenth, cuentaId: $('#cuenta_id').val()},
            success: function(data) {
                var resp = JSON.parse(data);
                if(!resp.resp){
                    bootbox.alert('No hay fondos suficientes para realizar el pago.', function(){
                        $('#cuenta_id').val('');                        
                    });                    
                }
            }
        });                      
    }else{
        bootbox.alert('Debe ingresar un valor a pagar', function(){
            $('#cuenta_id').val('');
        });        
    }

}

/**
 * Agrega a un array de objetos la cuenta, el nombre y el valor a pagar,
 * si ya existe lo suma (para cuentas tipo contado)
 */
function agregarCuentaValor(){

    // arrCuentas
    var objCuentaValor = new Object();

    var mensaje = validarPagoFactura();

    if(mensaje == ""){
        var flg = false;
        var cuentaId = '';

        if($('#tipopago_id').val() == '1'){
            objCuentaValor.tipo = $('#tipopago_id option:selected').html();
            objCuentaValor.cuenta = $('#cuenta_id').val();
            objCuentaValor.nombre = $('#cuenta_id option:selected').html();
            cuentaId = $('#cuenta_id').val();
        }else{
            objCuentaValor.tipo = $('#tipopago_id option:selected').html();
            objCuentaValor.cuenta = 'crd';
            objCuentaValor.nombre = '';      
            cuentaId = 'crd';       
        }

        objCuentaValor.valor = parseInt($('#valuePaymenth').val());
        
        if( arrCuentas.length < 1 ) {          
            arrCuentas.push(objCuentaValor);
            flg = true;
        } else {
            arrCuentas.forEach(function(elem, index){
                if(elem.cuenta == cuentaId){
                    var newVal = parseInt(elem.valor) + parseInt($('#valuePaymenth').val());
                    arrCuentas[index].valor = newVal;
                    flg = true;
                }
            });
        }
    
        if(!flg){                
            arrCuentas.push(objCuentaValor);        
        }
    
        $('#valuePaymenth').val("");
        $('#cuenta_id').val("");
    
        addPaymentToTable();
    }else{
        bootbox.alert(mensaje);
    }

}

function validarPagoFactura() {
    var msj = "";
    
    //valida que se haya seleccionado el proveedor
    if(valTtal == "" || valTtal == 0){
        msj += "- Debe ingresar una categoría.<br>";
    }

    if($('#tipopago_id').val() == ''){
        msj += '- Debe seleccionar un método de pago.<br>';
    }

    if($('#valuePaymenth').val() == ''){
        msj += '- Debe ingresar un valor para el pago.<br>';
    }

    if($('#tipopago_id').val() == '1') {
        if($('#cuenta_id').val() == ''){
            msj += '- Debe seleccionar una cuenta para debitar el valor del pago.';
        }
    }
    
    return msj;
}

///////////////////////////////////////////////////////////////////////////////////////


$(function() { 
    $(".date").datepicker({dateFormat: 'yy-mm-dd'});
    $(".date").datepicker("option", "showAnim", "slideDown"); 
    
    $('#addCategory').click(agregarCategoría);
    $('#delCat').click(deleteCategory);
    $('#addRetefuente').click(agregarRetefuente);
    $('#addReteIca').click(agregarReteica);
    
    $('.numericPrice').number(true);
    
    $('#valCat').blur(validarCategoria);
    
    $("#guardarCompra").click(guardarCompra);

    $("#tipopago_id").change(validatePayMeth);

    $('#cuenta_id').change(validateCountAmount);

    $('#addPayment').click(agregarCuentaValor);
});
