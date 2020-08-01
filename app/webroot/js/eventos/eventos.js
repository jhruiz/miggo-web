var events = '';
var opcNewEventos = {
    autoOpen: false,
    modal: true,
    width: 400,
    height: 800,
    position: [400, 50],
    show: {
        duration: 400    
    },
    hide: function () {
    },
    close: function( event, ui){        
    },
    title: 'Nuevo Evento'    
};

var dialogNuevoEventos;

var modalEvento = function() {
    $("#new_event").load(
        $('#url-proyecto').val() + "eventos/crearevento",
        function(){                                                            
            dialogNuevoEventos=$("#new_event").dialog(opcNewEventos);
            dialogNuevoEventos.dialog('open');
        }
    );  
};

$(function() {
    $('#newEvent').click(modalEvento);    
});