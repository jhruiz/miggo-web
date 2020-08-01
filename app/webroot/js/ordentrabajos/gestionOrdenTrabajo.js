var finalizarOrden = function(){
    var ordenT = $('#ordentrabajo_id').val();
    var estadoId = $('#OrdentrabajoOrdenestadoId').val();
    $.ajax({
        url: $('#url-proyecto').val() + 'ordentrabajos/ajaxFinalizarOrdenTrabajo',
        data: {idOrdenT: ordenT, estadoId: estadoId},
        type: "POST",
        success: function(data) {
            var resp = JSON.parse(data);
            if(resp.resp != '0'){
                if(resp.estadofin == "1"){
                    window.location.href = $('#url-proyecto').val() + 'ordentrabajos/index/';
                }
            }else{
                bootbox.alert('No fue posible actualizar la orden de trabajo. Por favor, inténtelo de nuevo.');
            }
        }
    });    
};

/**
 * Funcion que crea la orden de trabajo
 * @returns {undefined}
 */
var crearOrdenTrabajo = function(){    
    var formData = new FormData($('#OrdentrabajoAddForm')[0]);    
    
    $.ajax({
        url: $('#url-proyecto').val() + 'ordentrabajos/ajaxCrearOrdenTrabajo',
        type: 'POST',        
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            var resp = JSON.parse(data);
            if(resp.resp != '0'){
                $('#ordentrabajo_id').val(resp.resp);
            }else{
                bootbox.alert('No fue posible crear la orden de trabajo. Por favor, inténtelo de nuevo.');
            }
        }
    }); 
};

/**
 * Funcion que actualiza la orden de trabajo
 * @returns {undefined}
 */
var actualizarOrdenTrabajo = function(campo, valor){
    var ordenT = $('#ordentrabajo_id').val();
        $.ajax({
            url: $('#url-proyecto').val() + 'ordentrabajos/ajaxActualizarOrdenTrabajo',
            data: {idOrdenT: ordenT, campo: campo, valor: valor},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if(resp.resp != '0'){
                    
                }else{
                    bootbox.alert('No fue posible actualizar la orden de trabajo. Por favor, inténtelo de nuevo.');
                }
            }
        }); 
};
/**
 * Valida si el formulario ha sido diligenciado completamente
 * @returns {undefined}
 */
var validarFomulario = function(){
    var mensaje = "";
    if($('#OrdentrabajoOrdenestadoId').val() == ""){
        mensaje += "- Debe seleccionar un estado para la orden. <br>";        
    }
    
    if($('#OrdentrabajoKilometraje').val() == ""){
        mensaje += "- Debe ingresar el kilometraje actual del vehículo. <br>";        
    }
    
    if($('#OrdentrabajoPlaca').val() == ""){
        mensaje += "- Debe seleccionar un vehículo. <br>";        
    }
    
    if($('#vehiculo_id').val() == ""){
        mensaje += "- Debe seleccionar un vehículo válido. <br>";
    }
    
    if($('#cliente_id').val() == ""){
        mensaje += "- Debe seleccionar un cliente válido. <br>";
    }
    
    if($('#OrdentrabajoUsuarioId').val() == ""){
        mensaje += "- Debe seleccionar un mecánico. <br>";        
    }
    
    if($('#OrdentrabajoCliente').val() == ""){
        mensaje += "- Debe seleccionar un cliente. <br>";        
    }
    
    if($('#OrdentrabajoPlantaservicioId').val() == ""){
        mensaje += "- Debe seleccionar una planta de servicio. <br>";        
    }
    
    if($('#fecha_ingreso').val() == ""){
        mensaje += "- Debe seleccionar una fecha de ingreso del vehículo. <br>";        
    }
    
    if($('#fecha_salida').val() == ""){
        mensaje += "- Debe seleccionar una fecha de salida del vehículo. <br>";        
    }
    
    if($('#fecha_soat').val() == ""){
        mensaje += "- Debe seleccionar una fecha de vencimiento de Soat. <br>";
    }
    
    if($('#fecha_tecno').val() == ""){
        mensaje += "- Debe seleccionar una fecha de vencimiento de Tecnomecánica. <br>";
    }
    
    return mensaje;
};


/**
 * Si la orden de trabajo no exite actualiza el estado; de lo contrario, crea la orden
 * @returns {undefined}
 */
var cambiarEstadoGuardarOrden= function(){
//    var ordenId = $('#ordentrabajo_id').val();
    var estadoId = $('#OrdentrabajoOrdenestadoId').val();
    
    var ordenTId = $('#ordentrabajo_id').val();    
    
    if(estadoId != ""){
        $.ajax({
            url: $('#url-proyecto').val() + 'ordenestados/ajaxValidarEstadoFin',
            data: {estadoId: estadoId, ordenTId: ordenTId},
            type: "POST",
            success: function(data) {
                var estadoFin = JSON.parse(data);
                if(estadoFin.resp != "0"){
                    var mensaje = validarFomulario();
                    
                    if(mensaje == ""){ 
                        finalizarOrden();
                    }else{
                        $('#OrdentrabajoOrdenestadoId').val(estadoFin.estadoAct);
                        bootbox.alert(mensaje);
                    }
                    
                }else{
                    var ordenId = $('#ordentrabajo_id').val();
                    if(ordenId == ""){
                        //se crea la orden de trabajo
                        crearOrdenTrabajo();
                    }else{
                        //actualiza el estado de la orden de trabajo
                        var campo = "ordenestado_id";
                        var valor = $('#OrdentrabajoOrdenestadoId').val();
                        actualizarOrdenTrabajo(campo, valor);
                    }
                }

            }
        });          
    }else{
        bootbox.alert('Debe seleccionar un estado');
    }
       

    
};

/**
 * Cambia el estado de la parte del vehiculo
 * @returns {undefined}
 */
var cambiarEstadoParte = function(){
    var ordenId = $('#ordentrabajo_id').val();
    var estado = $(this).val();
    var parte = $(this).attr('id');
    
    $.ajax({
            url: $('#url-proyecto').val() + 'ordentrabajos_partevehiculos/actualizarEstadoParteOrden',
            data: {ordenId: ordenId, estadoId: estado, parteId: parte},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if(!resp.resp){
                    bootbox.alert('No fue posible actualizar el estado de la parte del vehículo. Por favor, inténtelo de nuevo.');
                }
            }
        });     
};


/**
 * Se obtienen las partes del vehiculo segun su tipo y los estados que se le pueden asignar
 * @param {type} idVehiculo
 * @returns {undefined}
 */
var obtenerPartesVehiculo = function(idVehiculo){  
    var ordenId = $('#ordentrabajo_id').val();

    if(idVehiculo != "" && ordenId != ""){
        $.ajax({
                url: $('#url-proyecto').val() + 'partevehiculos_tipovehiculos/ajaxObtenerPartesVehiculo',
                data: {idVehiculo: idVehiculo, ordenId: ordenId},
                type: "POST",
                success: function(data) {
                    var resp = JSON.parse(data);
                    var uls = "";
                    uls += '<div class="container-fluid">';
                    for(var i = 0; i < resp.partes.length; i++){
                        
                                uls += '<div class="col-md-3" style="margin-bottom:20px;">';
                                uls += '<label>' + resp.partes[i].PV.descripcion.toUpperCase() + '</label><br>';
                                uls += '<select id="' + resp.partes[i].PV.id + '" class="form-control prtVehiculo">';
                                for(var j = 0; j < resp.estados.length; j++){                            
                                    uls += '<option value="' + resp.estados[j].Estadoparte.id + '">' + resp.estados[j].Estadoparte.descripcion + '</option>';                            
                                }                       
                                uls += '</select>';                        
                                uls += '</div>';
                        
                    }
                    uls += '</div>';
                    
                    $('#partesVehiculo').html(uls);
                    $('.prtVehiculo').change(cambiarEstadoParte);
                }
            });         
    }else{
        bootbox.alert('Debe seleccionar un vehículo.');
    }
};

/**
 * Al seleccionar la placa deseada, se obtiene el id del vehiculo y la placa
 * @param {type} data
 * @returns {undefined}
 */
function seleccionarVehiculo(data){    
    $('#datosVehiculo').html("");
    $('#datosVehiculo').hide();
    $('#OrdentrabajoPlaca').val(data.name);
    $('#vehiculo_id').val(data.id);
    
    //se obtienen los datos del vehiculo
    obtenerTecnoSoat(data.id);
    
    var ordenId = $('#ordentrabajo_id').val();
    if(ordenId == ""){
        //se crea la orden de trabajo
        crearOrdenTrabajoPorVehiculo(data.id);   

    }else{
        //actualiza el estado de la orden de trabajo
        var campo = "vehiculo_id";
        var valor = data.id;
        actualizarOrdenTrabajo(campo, valor);
        //se obtienen las partes del vehiculo
        obtenerPartesVehiculo(data.id);        
    }    
     
}

/**
 * Se crea la orden de trabajo basado en el vehiculo. Se obtienen y se asignan las partes a la orden
 * @param {type} vehiculoId
 * @returns {undefined}
 */
var crearOrdenTrabajoPorVehiculo = function(vehiculoId){
    var formData = new FormData($('#OrdentrabajoAddForm')[0]);    
    
    $.ajax({
        url: $('#url-proyecto').val() + 'ordentrabajos/ajaxCrearOrdenTrabajo',
        type: 'POST',        
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            var resp = JSON.parse(data);
            if(resp.resp != '0'){
                $('#ordentrabajo_id').val(resp.resp);
                obtenerPartesVehiculo(vehiculoId);
            }else{
                bootbox.alert('No fue posible crear la orden de trabajo. Por favor, inténtelo de nuevo.');
            }
        }
    });     

};

/**
 * Obtienen los datos del vehiculo con la coincidencia de la placa
 * @returns {undefined}
 */
var obtenerDatosVehiculo = function(){    
    var plcV = $(this).val();
    
    if(plcV.length > 1){
        $.ajax({
                url: $('#url-proyecto').val() + 'vehiculos/ajaxObtenerVehiculo',
                data: {datosVehiculo: plcV},
                type: "POST",
                success: function(data) {

                    var vehiculo = JSON.parse(data);
                    var uls = "";
                    for(var i = 0; i < vehiculo.resp.length; i++){
                        uls += "<a href='#' class='list-group-item list-group-item-info' name='" + vehiculo.resp[i].Vehiculo.placa + "'";
                        uls += " id='" + vehiculo.resp[i].Vehiculo.id + "'";
                        uls += "onClick ='seleccionarVehiculo(this)'>" + vehiculo.resp[i].Vehiculo.placa + "</a>";
                    }
                    $('#datosVehiculo').show();
                    $('#datosVehiculo').html(uls);
                }
            });        
    }else{
        $('#datosVehiculo').html("");
        $('#datosVehiculo').hide();
        $('#vehiculo_id').val("");                
    }    
};

/**
 * Se obtiene el cliente seleccionado de la lista
 * @param {type} data
 * @returns {undefined}
 */
function seleccionarCliente(data){
    $('#datosCliente').html("");
    $('#datosCliente').hide();    
    $("#OrdentrabajoCliente").val(data.text);
    $("#cliente_id").val(data.name);
    
    var ordenId = $('#ordentrabajo_id').val();
    if(ordenId == ""){
        //se crea la orden de trabajo
        crearOrdenTrabajo();   

    }else{
        //actualiza el estado de la orden de trabajo
        var campo = "cliente_id";
        var valor = data.name;
        actualizarOrdenTrabajo(campo, valor);      
    }     
    
}

/**
 * Se buscan los clientes que coincidan con el nombre o el nit/cc
 * @returns {undefined}
 */
var obtenerDatosCliente = function(){
    var cliente = $(this).val();
    
    if(cliente.length > 3){    
    var empresaId = $('#empresaId').val();
    $.ajax({
            url: $('#url-proyecto').val() + 'clientes/ajaxObtenerClientes',
            data: {datosCliente: cliente, empresaId: empresaId},
            type: "POST",
            success: function(data) {
                
                var cliente = JSON.parse(data);
                var uls = "";
                for(var i = 0; i < cliente.resp.length; i++){
                    uls += "<a href='#' class='list-group-item list-group-item-info' name='" + cliente.resp[i].Cliente.id + "' onClick ='seleccionarCliente(this)'>" + cliente.resp[i].Cliente.nombre + " - " + cliente.resp[i].Cliente.nit + "</a>";
                }
                $('#datosCliente').show();
                $('#datosCliente').html(uls);
            }
        });         
    }else{
        $('#datosCliente').html("");
        $('#datosCliente').hide();
    }
};

/**
 * Se crea la orden de trabajo basada en el kilometraje o se actualiza el registro 
 * si ya existe la orden
 * @returns {undefined}
 */
var actualizarKilometrajeGuardarOrden = function(){
    var ordenId = $('#ordentrabajo_id').val();
    if(ordenId == ""){
        //se crea la orden de trabajo
        crearOrdenTrabajo();
    }else{
        //actualiza el estado de la orden de trabajo
        var campo = "kilometraje";
        var valor = $('#OrdentrabajoKilometraje').val();
        actualizarOrdenTrabajo(campo, valor);
    }    
};

/**
 * Se crea la orden de trabajo basado en el mecanico o se actualiza el registro
 * si ya existe la orden
 * @returns {undefined}
 */
var actualizarUsuarioGuardarOrden = function(){
    var ordenId = $('#ordentrabajo_id').val();
    if(ordenId == ""){
        //se crea la orden de trabajo
        crearOrdenTrabajo();
    }else{
        //actualiza el estado de la orden de trabajo
        var campo = "usuario_id";
        var valor = $('#OrdentrabajoUsuarioId').val();
        actualizarOrdenTrabajo(campo, valor);
    }      
};

/**
 * Se crea la orden de trabajo o se actualiza el registro si ya existe
 * la orden
 * @returns {undefined}
 */
var actualizarPlantaGuardarOrden = function(){
    var ordenId = $('#ordentrabajo_id').val();
    if(ordenId == ""){
        //se crea la orden de trabajo
        crearOrdenTrabajo();
    }else{
        //actualiza el estado de la orden de trabajo
        var campo = "plantaservicio_id";
        var valor = $('#OrdentrabajoPlantaservicioId').val();
        actualizarOrdenTrabajo(campo, valor);
    }          
};

/**
 * Se validan las fechas de ingreso y salida del vehiculo
 * @param {type} fechaIng
 * @param {type} fechaFin
 * @returns {String}
 */
var validarFechaIngreso = function(fechaIng, fechaFin){
    var mensaje = "";
    
    //fecha actual
    var fechaActual = new Date();
        
    if(fechaFin != ""){    
        var fechaSalida = new Date(fechaFin); 
        if(fechaIng > fechaSalida){
            mensaje += "- La fecha de ingreso no puede ser mayor a la fecha de salida del vehículo. <br>";
        }        
    }
    
    if(fechaIng > fechaActual){
        mensaje += "- La fecha de ingreso no puede ser mayor a la fecha actual. <br>";
    }
    return mensaje; 
};

/**
 * se actualiza la fecha de ingreso o se crea la orden de trabajo
 * @returns {undefined}
 */
var actualizarFechaIngGuardarOrden = function(){
    
    var ordenId = $('#ordentrabajo_id').val();
    if(ordenId == ""){
        
        var fechaActual = new Date();
        var fechaIng = new Date($('#fecha_ingreso').val());
        
        if(fechaIng < fechaActual){
            //se crea la orden de trabajo
            crearOrdenTrabajo();            
        }else{
            $('#fecha_ingreso').val("");
            bootbox.alert('La fecha de ingreso no puede ser mayor a la fecha actual.');            
        }

    }else{
        //se validan las fechas
        var fechaIng = new Date($('#fecha_ingreso').val());
        var fechaFin = $('#fecha_salida').val(); 
        
        var mensaje = validarFechaIngreso(fechaIng, fechaFin);
        
        if(mensaje == ""){
            //actualiza la fecha de ingreso de la orden de trabajo
            var campo = "fecha_ingreso";
            var valor = $('#fecha_ingreso').val();
            actualizarOrdenTrabajo(campo, valor);            
        }else{
            $('#fecha_ingreso').val("");
            bootbox.alert(mensaje);
        }
    }     
};

var actualizarSoatGuardarOrden = function(){
    var ordenId = $('#ordentrabajo_id').val();
    if(ordenId == ""){
        crearOrdenTrabajo();            
    }else{
        var campo = "soat";
        var valor = $('#fecha_soat').val();
        actualizarOrdenTrabajo(campo, valor);  
        actualizarDatosVehiculo();
    }      
};
    
   
   
var actualizarTecnoGuardarOrden = function(){
    var ordenId = $('#ordentrabajo_id').val();
    if(ordenId == ""){
        //se crea la orden de trabajo
        crearOrdenTrabajo();            
    }else{
        //actualiza la fecha de ingreso de la orden de trabajo
        var campo = "tecnomecanica";
        var valor = $('#fecha_tecno').val();
        actualizarOrdenTrabajo(campo, valor);   
        actualizarDatosVehiculo();
    }      
};
    

/**
 * Se valida el correcto ingreso de la fecha de salida del vehiculo
 * @param {type} fechaIng
 * @param {type} fechaFin
 * @returns {undefined}
 */
var validarFechaFin = function(fechaIng, fechaFin){
    var mensaje = ""; 
    
    if(fechaIng != ""){
        var fechaIngV = new Date(fechaIng);
        if(fechaIngV > fechaFin){
            mensaje += "- La fecha de salida del vehículo no puede ser menor a la fecha de ingreso del mismo. <br>";
        }
    }else{
        mensaje += "- La fecha de ingreso del vehículo no puede estar vacia. <br>";
    }
    
    return mensaje;
    
};

/**
 * se actualiza la fecha de salida del vehiculo
 * @returns {undefined}
 */
var actualizarFechaSalida = function(){
    var fechaIng = $('#fecha_ingreso').val();
    var fechaFin = new Date($('#fecha_salida').val()); 
    
    var mensaje = validarFechaFin(fechaIng, fechaFin);
    if(mensaje == ""){
        //actualiza el estado de la orden de trabajo
        var campo = "fecha_salida";
        var valor = $('#fecha_salida').val();
        actualizarOrdenTrabajo(campo, valor);        
    }else{
        $('#fecha_salida').val("");
        bootbox.alert(mensaje);
    }
    
};

/**
 * Se actualiza el registro de herramientas para la orden de trabajo
 * @returns {undefined}
 */
var actualizarHerramientas = function(){
    var ordenId = $('#ordentrabajo_id').val();
    
    if(ordenId != ""){
        var campo = "herramientas";
        var valor = $('#herramientas').prop('checked') ? '1' : '0';
        actualizarOrdenTrabajo(campo, valor);
    }else{
        $('#herramientas').prop( "checked", false );
        bootbox.alert("Debe seleccionar primero uno de los campos de la sección General.");
    }
}; 

/**
 * Se actualiza el registro de llaves para la orden de trabajo
 * @returns {undefined}
 */
var actualizarLlaves = function(){
    var ordenId = $('#ordentrabajo_id').val();
    
    if(ordenId != ""){
        var campo = "llaves";
        var valor = $('#llaves').prop('checked') ? '1' : '0';
        actualizarOrdenTrabajo(campo, valor);
    }else{
        $('#llaves').prop('checked', false);
        bootbox.alert("Debe seleccionar primero uno de los campos de la sección General.");
    }
};

/**
 * Se actualiza el registro de documentos para la orden de trabajo
 * @returns {undefined}
 */
var actualizarDocumentos = function(){
    var ordenId = $('#ordentrabajo_id').val();
    
    if(ordenId != ""){
        var campo = "documentos";
        var valor = $('#documentos').prop('checked') ? '1' : '0';
        actualizarOrdenTrabajo(campo, valor);
    }else{
        $('#documentos').prop('checked', false);
        bootbox.alert("Debe seleccionar primero uno de los campos de la sección General.");
    }
};

/**
 * se actualizan los comentarios del mecanico
 * @returns {undefined}
 */
var actualizarObsUsr = function(){
    var ordenId = $('#ordentrabajo_id').val();
    
    if(ordenId != ""){
        var campo = "observaciones_usuario";
        var valor = $('#OrdentrabajoObservacionesUsuario').val();
        actualizarOrdenTrabajo(campo, valor);
    }else{
        $('#OrdentrabajoObservacionesUsuario').val("");
        bootbox.alert("Debe seleccionar primero uno de los campos de la sección General.");
    }    
};

/**
 * se actualizan los comentarios del cliente
 * @returns {undefined}
 */
var actualizarObsCli = function(){
    var ordenId = $('#ordentrabajo_id').val();
    
    if(ordenId != ""){
        var campo = "observaciones_cliente";
        var valor = $('#OrdentrabajoObservacionesCliente').val();
        actualizarOrdenTrabajo(campo, valor);
    }else{
        $('#OrdentrabajoObservacionesCliente').val("");
        bootbox.alert("Debe seleccionar primero uno de los campos de la sección General.");
    }    
};

/**
 * Actualizar la cantidad del suministro requerida para la orden
 * @param {type} data
 * @returns {undefined}
 */
function actualizarCantidadSum(dat){
    var ordenId = $('#ordentrabajo_id').val();
    var arrInpCant = (dat.id).split('_');
    var cargueInvId = arrInpCant['1'];
    var cantNueva = $('#' + dat.id).val();
    
    //se actualiza la cantidad requerida del suministro, siempre y cuando exista stock
    $.ajax({
        url: $('#url-proyecto').val() + 'cargueinventarios/ajaxActualizarCantidadSumOrd',
        data: {cargueInvId: cargueInvId, cantNueva: cantNueva, ordenId: ordenId},
        type: "POST",
        success: function(data) {
            var resp = JSON.parse(data);
            if(resp.resp == '0'){
                $('#' + dat.id).val(resp.cant);
                bootbox.alert('No hay las suficientes unidades en stock.');
            }else if(resp.resp == '2'){
                $('#' + dat.id).val(resp.cant);
                bootbox.alert('No fue posible actualizar el registro. Por favor, inténtelo de nuevo.');
            }
        }
    });     
}

/**
 * Elimina el suministro asignado a la orden de trabajo
 * @param {type} dat
 * @returns {undefined}
 */
function eliminarSumOrden(dat){
    var ordenId = $('#ordentrabajo_id').val();
    var arrInpCant = (dat.id).split('_');
    var cargueInvId = arrInpCant['1'];
    
    bootbox.confirm("¿Está seguro que desea eliminar el suministro?",function(result){        
        if(result){
            //se actualiza la cantidad requerida del suministro, siempre y cuando exista stock
            $.ajax({
                url: $('#url-proyecto').val() + 'cargueinventarios/ajaxEliminarSuministroOrden',
                data: {cargueInvId: cargueInvId, ordenId: ordenId},
                type: "POST",
                success: function(data) {
                    var resp = JSON.parse(data);
                    if(resp.resp == '1'){
                        $('#tr_' + cargueInvId).remove();
                    }else{
                        bootbox.alert('No fue posible eliminar el registro. Por favor, inténtelo de nuevo.');
                    }
                }
            });        
        }        
    });
}

/**
 * Selecciona el suministro que se asigna a la orden
 * @param {type} data
 * @returns {undefined}
 */
function seleccionarSuministro(data){    
    $('#OrdentrabajoProducto').val("");
    $('#datosProducto').hide();
    $('#datosProducto').html("");
    var ordenId = $('#ordentrabajo_id').val();
    var idSuministro = data.name;
    var cargueInvId = data.id;
    
    if(idSuministro != ""){
        $.ajax({
            url: $('#url-proyecto').val() + 'cargueinventarios/ajaxObtenerSuministroOrden',
            data: {ordenId: ordenId, idSuministro: idSuministro, cargueInvId: cargueInvId},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                var uls = "";
                if(resp.resp = '1'){                    
                    uls += '<tr id="tr_' + resp.prod.Cargueinventario.id + '">';
                    uls += '<td>' + resp.prod.Producto.codigo + '</td>';
                    uls += '<td>' + resp.prod.Producto.descripcion + '</td>';
                    uls += '<td><input type="text" value="1" class="form-control" id="inp_' + resp.prod.Cargueinventario.id + '"'; 
                    uls += ' onblur="actualizarCantidadSum(this);"></td>';
                    uls += '<td><input type="button" class="btn btn-danger" value="Eliminar" id="elim_' + resp.prod.Cargueinventario.id + '"';
                    uls += ' onclick="eliminarSumOrden(this)"</td>';
                    uls += '</tr>';
                }else{
                    bootbox.alert('No fue posible guardar el registro. Por favor, inténtelo de nuevo.');
                }
                $('.tProductos').append(uls);
            }
        });        
    }else{
        bootbox.alert('Debe seleccionar un producto.');
    }
    scrollTop: $("#datosProducto").offset().top;
}

/**
 * Se obtiene el listado de productos que coinciden con el nombre ingresado 
 * @returns {undefined}
 */
var obtenerDatosSuministro = function(){
    var ordenId = $('#ordentrabajo_id').val();
    var prod = $(this).val();
    var usuarioId = $('#usuarioId').val(); 
    
    if(ordenId != ""){
        if(prod.length > 3){
            $.ajax({
                url: $('#url-proyecto').val() + 'cargueinventarios/ajaxProductosVenta',
                data: {usuarioId: usuarioId, descProducto: prod},
                type: "POST",
                success: function(data) {
                    var producto = JSON.parse(data);
                    var uls = "";
                    for(var i = 0; i < producto.resp.length; i++){
                        if(parseInt(producto.resp[i].Cargueinventario.existenciaactual) > parseInt(0)){
                            uls += "<a href='#' class='list-group-item list-group-item-info' ";
                            uls += "name='" + producto.resp[i].Producto.id + "' ";
                            uls += "id='" + producto.resp[i].Cargueinventario.id + "' ";
                            uls += "onClick ='seleccionarSuministro(this)'>" + producto.resp[i].Producto.descripcion;
                            uls += " - " + producto.resp[i].Producto.codigo;
                            uls += " Ref (" + producto.resp[i].Producto.referencia + ") ";
                            uls += producto.resp[i].Deposito.descripcion;
                            uls += "</a>";
                        }                        
                    }
                    $('#datosProducto').show();
                    $('#datosProducto').html(uls);
                }
            });             
        }else{
            $('#datosProducto').hide();
            $('#datosProducto').html("");
        }         
    }else{
        bootbox.alert('Debe seleccionar primero uno de los campos de la seccion General');
    }
};

var obtenerTecnoSoat = function(id){    
    $.ajax({
        url: $('#url-proyecto').val() + 'vehiculos/ajaxObtenerVehiculoPorId',
        data: {vehiculoId: id},
        type: "POST",
        success: function(data) {
            var vehiculo = JSON.parse(data);            
            var soat = "";
            var tecno = "";
            if(vehiculo.resp.Vehiculo.soat != null){
                var arrSoat = vehiculo.resp.Vehiculo.soat.split(' ');
                soat = arrSoat['0'];
            }
            
            if(vehiculo.resp.Vehiculo.tecno != null){
                var arrTecno = vehiculo.resp.Vehiculo.tecno.split(' ');
                tecno = arrTecno['0'];
            }

            $('#fecha_soat').val(soat);
            $('#fecha_tecno').val(tecno);

            //actualiza el estado de la orden de trabajo
            var campo = "soat";
            var valor = soat;
            actualizarOrdenTrabajo(campo, valor);      
            
            //actualiza el estado de la orden de trabajo
            var campo = "tecnomecanica";
            var valor = tecno;
            actualizarOrdenTrabajo(campo, valor);      
        }
    });        
};

/**
 * Ver los datos del vehiculo de la orden de servicio
 * @returns {undefined}
 */
var verDatosVehiculo = function(){
    var idVehiculo = $('#vehiculo_id').val();
    
    if(idVehiculo == ""){
        bootbox.alert('Debe seleccionar un vehículo.');
    }else{        
        window.open($('#url-proyecto').val() + 'vehiculos/edit/' + idVehiculo, '_blank');
    }    
};

var verDatosCliente = function() {
    console.log('da clic hasta aqui');
    var idCliente = $('#cliente_id').val();
    
    if(idCliente == ""){
        bootbox.alert('Debe seleccionar un cliente.');        
    }else{
        window.open($('#url-proyecto').val() + 'clientes/edit/' + idCliente, '_blank');
    }
};

var actualizarDatosVehiculo = function(){
    var idVehiculo = $('#vehiculo_id').val();
    var soat = $('#fecha_soat').val();
    var tecno = $('#fecha_tecno').val();
    
    if(idVehiculo != ""){
        $.ajax({
            url: $('#url-proyecto').val() + 'vehiculos/ajaxActualizarSoatTecno',
            data: {vehiculoId: idVehiculo, soat: soat, tecno:  tecno},
            type: "POST",
            success: function(data) {
                
            }
        });        
    }
};

var generarAlerta = function() {
    //valida que tenga todos los datos ingresados
    var vehiculo = $('#vehiculo_id').val();
    var cliente = $('#cliente_id').val();
    var orden = $('#ordentrabajo_id').val();

    if(vehiculo != '' && cliente != '' && orden != ''){
        window.open($('#url-proyecto').val() + 'alertaordenes/gestionalertas/' + $('#ordentrabajo_id').val(), '_blank');
    } else {
        bootbox.alert('Asegúrese de gestionar la orden de trabajo completa e inténtelo nuevamente.');
    }
    
}

/**
 * Formato a los input tipo date
 * @returns {undefined}
 */
var datePicker = function(){
    $(".date").datepicker({dateFormat: 'yy-mm-dd'});
    $(".date").datepicker("option", "showAnim", "slideDown");    
};

var enviarOrden = function(){
    //se valida si se ha seleccionado un cliente
    var cliente = $('#cliente_id').val();
    
    if(cliente != ''){
        $.ajax({
            url: $('#url-proyecto').val() + 'clientes/ajaxObtenerInfoCliente',
            data: {clienteId: cliente},
            type: "POST",
            success: function(data) {
                var resp = JSON.parse(data);
                if(typeof resp.resp.Cliente != 'undefined'){
                    if(resp.resp.Cliente.celular != ""){
                        var link = "https://wa.me/57" + resp.resp.Cliente.celular + "?text=Somos%20el%20%23equipotorque%2c%20adjuntamos%20información%20de%20su%20interés";
                        window.open(link, '_blank');
                    }else{
                        bootbox.alert('El cliente no tiene número de teléfono celular para el envío del mensaje.');
                    }                    
                }else{
                    alert('No fue posible obtener la información del cliente. Por favor, inténtelo nuevamente.');
                }
            }
        }); 
    }else{
        bootbox.alert('Debe seleccionar un cliente.');
    }    
}

$(function() {    
    datePicker();
    
    $('#OrdentrabajoPlaca').keyup(obtenerDatosVehiculo); 
    
    $('#OrdentrabajoCliente').keyup(obtenerDatosCliente);
    
    $('#OrdentrabajoOrdenestadoId').change(cambiarEstadoGuardarOrden);
    
    $('#OrdentrabajoKilometraje').change(actualizarKilometrajeGuardarOrden);
    
    $('#OrdentrabajoUsuarioId').change(actualizarUsuarioGuardarOrden); 
    
    $('#OrdentrabajoPlantaservicioId').change(actualizarPlantaGuardarOrden);
    
    $('#fecha_ingreso').change(actualizarFechaIngGuardarOrden);
    
    $('#fecha_salida').change(actualizarFechaSalida);
    
    $('#herramientas').change(actualizarHerramientas);
    
    $('#llaves').change(actualizarLlaves);
    
    $('#documentos').change(actualizarDocumentos);
    
    $('#OrdentrabajoObservacionesUsuario').change(actualizarObsUsr);
    
    $('#OrdentrabajoObservacionesCliente').change(actualizarObsCli);
    
    $('#OrdentrabajoProducto').keyup(obtenerDatosSuministro);
    
    $('.prtVehiculo').change(cambiarEstadoParte);
    
    $('#fecha_soat').change(actualizarSoatGuardarOrden);
    
    $('#fecha_tecno').change(actualizarTecnoGuardarOrden);   
    
    $('#dv_emp').hide();
    
    $('#ver_vehiculo').click(verDatosVehiculo);
    
    $('#ver_cliente').click(verDatosCliente);

    $('#btn_alerta').click(generarAlerta);

    $('.wppSendPF').click(enviarOrden);
    
});