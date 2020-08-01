var currentTab = 0;
var dialog;


$(document).ready(function() {
    var opt = {
        autoOpen: false,
        modal: true,
        show: {
            effect: "blind",
            duration: 1000
        },
        hide: {
            effect: "blind",
            duration: 1000
        },
        title: 'Permiso Campos'
    };

    dialog = $("#divPermisoCampos").dialog(opt);

    $("#cmbMedioEnvio").attr("disabled", "disabled");
});

$(document).on('click', '.clspermisocampo', function() {

    var idThis = this.id;

    $('.clspermisocampo').each(function() {

        if (this.checked && idThis != this.id) {
            $(this).removeAttr('checked');
        }
    })

})

///Cerrar dialog(pop up) para configurar permisos para el campo
function cerrarDialogPermisCampo() {
    dialog.dialog("close");
}

function obtenerUsuariosDeOficina() {
    var oficina = $("#OficinaOficina").val();
    var nombre = $("#NombreUsuario").val();

    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'bandejas/obtenerusuariooficina',
        data: {oficina: oficina, nombre: nombre},
        success: function(data) {
            $("#divUsuarios").html(data);
        }
    });

}




$(function() {
    $("#divConfigBandeja").tabs({
        beforeActivate: function(e, i) {

            currentTab = i.newTab.index();
            var tabs = $('#divConfigBandeja').tabs();
            var c = $('#divConfigBandeja >ul >li').size();

            if (currentTab == (c - 1)) {
                $("#btnNext").hide();
                $("#btnPrevious").show();
            } else if (currentTab == 0) {
                $("#btnNext").show();
                $("#btnPrevious").hide();
            } else if (currentTab < (c - 1)) {
                $("#btnNext").show();
                $("#btnPrevious").show();
            }

        }
    });
});



$(document).on("click", "#btnNext", function() {

    var tabs = $('#divConfigBandeja').tabs();
    var c = $('#divConfigBandeja >ul >li').size();
    currentTab = currentTab == (c - 1) ? currentTab : (currentTab + 1);
    tabs.tabs('option', 'active', currentTab);
    $("#btnPrevious").show();
    if (currentTab == (c - 1)) {
        $("#btnNext").hide();
    } else {
        $("#btnNext").show();
    }
});

$(document).on("click", "#btnPrevious", function() {
    var tabs = $('#divConfigBandeja').tabs();
    //var c = $('#divConfigBandeja').tabs("length");
    var c = $('#divConfigBandeja >ul >li').size();
    currentTab = currentTab == 0 ? currentTab : (currentTab - 1);
    tabs.tabs('option', 'active', currentTab);
    if (currentTab == 0) {
        $("#btnNext").show();
        $("#btnPrevious").hide();
    }
    if (currentTab < (c - 1)) {
        $("#btnNext").show();
    }
});


///Cuando se le cambia el permiso a un usuario se modifica el hidden y se 
//muestra el nuevo permiso en la tabla

function cambiarPermisoUsuario(event) {

    var id = event.id;
    var idUsuario = id.split("_");

    ///Se modifica el campo hidden
    if ($("#" + idUsuario[0] + "_usuariosSelec").val() != undefined && $("#" + idUsuario[0] + "_usuariosSelec").val() != null) {
        $("#" + idUsuario[0] + "_usuariosSelec").val(idUsuario[0] + "_" + $("#" + id).val());
    }

    ///Se muestra el nombre del nuevo permiso en la tabla
    var nombPermiso = $("#" + idUsuario[0] + "_permisousu option:selected").text();
    $("#" + idUsuario[0] + "_colPermUsu").html(nombPermiso);
}

function seleccionaUsuario(event) {

    var strsplit = ($(event).val()).split("$");
    var id = strsplit[0];
    var username = strsplit[1];

    //Si chequea el check, verifica si ya existe en la lista, si no, agrega el usuario
    if (event.checked) {
        var exists = false;

        if ($("#" + id + "_usuariosSelec").val() != undefined && $("#" + id + "_usuariosSelec").val() != null) {
            exists = true;
        }

        //Si no existe agrega el usuario a la lista
        if (!exists) {
            var nuevoUsuario = "<option value='" + id + "'  disabled>" + username + "</option>";


            var permisoUsu = $("#" + id + "_permisousu").val();
            var nombPermiso = $("#" + id + "_permisousu option:selected").text();

            ///Se crea la nueva fila de la tabla.
            var filaUsuario = "<tr id='" + id + "_tr_tabUsuarioPerm'><td>" + username + "</td><td id='" + id + "_colPermUsu'>" + nombPermiso + "</td></tr>";
            $("#tabUsuarioPermisos").append(filaUsuario);

            var nuevoUsuarioHidden = "<input type='hidden' id='" + id + "_usuariosSelec' name='usuariosSeleccionados[]' class='usupermband' value='" + id + "_" + permisoUsu + "'></option>";
            $("#divPermisosUsuarios").append(nuevoUsuarioHidden);
        }
        //Si deselecciono el check, quita el usuario de la lista.                      
    } else {
        $("#" + id + "_usuariosSelec").remove();
        $("#" + id + "_tr_tabUsuarioPerm").remove();
    }

}

//Convierte los options en hidden para que se envien los usuarios seleccionados 
function prepararConfig() {

    if ($("#idBandejaAConfigurar").val() == "" || $("#idBandejaAConfigurar").val() == null) {
        alert("Debe seleccionar una bandeja para configurar.");
        return false;
    }

    if ($("#tblEstadosProceso tr").length <= 1) {
        alert("La asignacion de un estado para la bandeja es obligatorio.");
        return false;
    }

    return true;
}

function validarUsuariosGestionadores() {
    //Se valida que se hayan agregado usuarios que gestionen la bandeja.
    var usuariosGestionadores = false;
    $(".usupermband").each(function() {
        var usuario_permiso = ($(this).val()).split("_");
        //Si se le asigno al usuario permisos de gestion sobre la bandeja
        if (usuario_permiso[1] == "2") {
            usuariosGestionadores = true;
        }
    });
    //Si no hay usuarios gestionadores de la bandeja, no se debe dejar configurar los campos
    if (!usuariosGestionadores) {
        return false;
    }

    return true;
}

///Pinta en la tabla de Estados de proceso, el estado de proceso agregado.
function agregarEstadoProc() {

    if ($('#cmbEstadosProceso').val() == null || $('#cmbEstadosProceso').val() == undefined || $('#cmbEstadosProceso').val() == 0) {
        alert("Debe seleccionar un estado de proceso.");
        return;
    }

    if (!validarUsuariosGestionadores()) {
        alert("Para configurar los estados a asignar debe primero configurar los usuarios gestionadores de la bandeja.");
        return;
    }

    //Se obtienen los nombres para mostrar en la nueva fila de la tabla
    var txtEstadoProc = $("#cmbEstadosProceso option:selected").text();

    //Se obtienen los ids para crear el campo hidden 
    var estadosProceso = $("#cmbEstadosProceso").val();

    //Se verifica si el estado proceso ya se ha agregado anteriormente
    var idhiddenExist = $("#" + estadosProceso + "_estadoproc").val();
    if (idhiddenExist == undefined || idhiddenExist == null) {
        //Se crea la fila tr de la tabla para el estado proceso a agregar.
        var nuevaFilaEstado = "<tr id='" + estadosProceso + "_tr'><td>" + txtEstadoProc + "</td>\
                                    <td><button type='button' id='" + estadosProceso + "_rem_estadoproc' onclick='eliminarEstadoProc(this);' class='btn btn-info'>Eliminar</button></td></tr>";

        var nuevoHiddenEstado = "<input type='hidden' name='estadosProcesAgregados[]'  id='" + estadosProceso + "_estadoproc' value='" + estadosProceso + "'>";

        $("#tblEstadosProceso").append(nuevaFilaEstado);
        $("#divEstadosAsignar").append(nuevoHiddenEstado);

    } else {
        alert("El estado de proceso a adicionar, ya existe.");
    }
}

///Elimina un estado proceso agregado anteriormente.
function eliminarEstadoProc(event) {

    var strSplit = (event.id).split("_");
    var idEstadoProc = strSplit[0];

    //Se elimina de la tabla la fila del estado proceso correspondiente
    $("#" + idEstadoProc + "_tr").remove();
    //Se elimina el campo hidden del estado proceso correspondiente
    $("#" + idEstadoProc + "_estadoproc").remove();
}

function array_unique(arr) {
    var i,
            len = arr.length,
            out = [],
            obj = {};

    for (i = 0; i < len; i++) {
        obj[arr[i]] = 0;
    }
    for (i in obj) {
        out.push(i);
    }
    return out;
}


function seleccionarNotificacion() {
    if ($("#cmbNotificacion").val() == 0) {
        $("#cmbMedioEnvio").attr("disabled", "disabled");
    } else {
        $("#cmbMedioEnvio").removeAttr("disabled");
    }
}

///Se llama cuando se da click en Adicionar, para adicionar un campo a la bandeja.
function agregarCampoBand() {

    if (!validarUsuariosGestionadores()) {
        alert("Para configurar los campos debe primero configurar los usuarios gestionadores de la bandeja.");
        return;
    }

    //Se valida que maximo se agreguen 15 campos a la bandeja.
    var numCamposAgregados = $(".camposAgregados").size();
    if (numCamposAgregados >= 15) {
        alert("Cumplio con el limite de 15 campos por bandeja.");
        return;
    }

    //Se obtienen los nombres para mostrar en la nueva fila de la tabla
    var txtNombCampo = $("#cmbCampos option:selected").text();
    var chkObligatorio = $("#chkCampoRquerido").is(':checked');
    var indObligatorio = "No";

    if (chkObligatorio) {
        indObligatorio = "Si";
    }

    //Se obtienen los ids para crear el campo hidden 
    var idCampo = $("#cmbCampos").val();

    //Se verifica si el campo ya se ha agregado anteriormente
    var idhiddenExist = $("#" + idCampo + "_campo").val();

    var nuevoHiddenCampo = "";

    if (idhiddenExist == undefined || idhiddenExist == null) {
        //Se crea la fila tr de la tabla para el campo a agregar.
        var nuevaFilaCampo = "<tr id='" + idCampo + "_tr_campo'><td>" + txtNombCampo + "</td>\
                                    <td>" + indObligatorio + "</td>\
                                    <td colspan='2'><button type='button' id='" + idCampo + "_rem_campo' onclick='eliminarCampo(this);' class='btn btn-info'>Eliminar</button></td></tr>";

        ///Se crea el hidden para saber si el campo ya se ha agregado
        nuevoHiddenCampo = "<input type='hidden' name='camposAgregados[]' id='" + idCampo + "_campo' value='" + idCampo + "_" + indObligatorio + "' class='camposAgregados' />";
        $("#tblCampos").append(nuevaFilaCampo);
        $("#divCampos").append(nuevoHiddenCampo);

    } else {
        alert("El campo a adicionar, ya existe.");
    }
}

///Elimina un campo agregado anteriormente.
function eliminarCampo(event) {

    var strSplit = (event.id).split("_");
    var idCampo = strSplit[0];

    //Se elimina de la tabla la fila del campo correspondiente
    $("#" + idCampo + "_tr_campo").remove();
    //Se elimina el campo hidden del campo correspondiente
    $("#" + idCampo + "_campo").remove();

    ///Se eliminan todos los permisos configurados para el campo a eliminar
    $("." + idCampo + "_clsCampoPermiso").each(function() {
        $(this).remove();
    });
}







