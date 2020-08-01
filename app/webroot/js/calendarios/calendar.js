var calendar = '';
var opcEventos = {
    autoOpen: false,
    modal: true,
    width: 1500,
    height: 1500,
    position: [400, 50],
    show: {
        duration: 400    
    },
    hide: function () {
    },
    close: function( event, ui){        
    },
    title: 'Eventos pendientes'    
};

var dialogEventos;


var obtenerListaEventos = function() {
    $("#div_eventos").load(
        $('#url-proyecto').val() + "calendarios/eventoscalendario",
        {eventDate: $(this).context.dataset.date},
        function(){                                                            
            dialogEventos=$("#div_eventos").dialog(opcEventos);
            dialogEventos.dialog('open');
        }
    );  
}

var getInfoToCalendar = function(objDates) {
    
    $.ajax({        
        url: $('#url-proyecto').val() + 'calendarios/datoscalendario', 
        async : false,
        data: {initDate: objDates.initDate, endDate: objDates.endDate},
        type: "POST",
        success: function(data) {

            var resp = JSON.parse(data);
            Object.keys(resp.events).forEach(key => {                
                addEventsToCalendar(resp.events[key].fecha, resp.events[key].cantidad);
            });                                        
        }
    });    

}


function  getDatesCalendar(infoCalendar){

    //fecha inicial
    var initYear = infoCalendar.start.getFullYear();
    var initMonth = (infoCalendar.start.getMonth() + 1) < 10 ? '0' + (infoCalendar.start.getMonth() + 1) : (infoCalendar.start.getMonth() + 1);
    var initDay = (infoCalendar.start.getDate() - 1) < 10 ? '0' + (infoCalendar.start.getDate() - 1) : (infoCalendar.start.getDate() - 1);

    var initDate = initYear + '-' + initMonth + '-' + initDay;
    
    //fecha final
    var endYear = infoCalendar.end.getFullYear();
    var endMonth = (infoCalendar.end.getMonth() + 1) < 10 ? '0' + (infoCalendar.end.getMonth() + 1) : (infoCalendar.end.getMonth() + 1);
    var endDay = (infoCalendar.end.getDate() - 1) < 10 ? '0' + (infoCalendar.end.getDate() - 1) : (infoCalendar.end.getDate() - 1);

    var endDate = endYear + '-' + endMonth + '-' + endDay;

    return {'initDate': initDate, 'endDate': endDate};
    
}


function addEventsToCalendar(fecha, cantidad){
    setTimeout(() => {
        $('#calendar').find('.fc-view-container').first().find('.fc-widget-content').first().find('.fc-week').each(function(){
            $(this).find('.fc-day').each(function(){
                if($(this)['0']['dataset']['date'] == fecha){
                    var divCantida = "<div style='border-width: 4px; border-radius: 25px; width: 35px; background: #72AEF3; text-align:center; color:#ffffff;'><h1><b><span class='spnListaEventos' data-date=" + fecha + ">" + cantidad + "</span></b></h1></div>"; 
                    $(this).append("<div style='margin-top:60px;'></div>");            
                    $(this).append(divCantida);                                          
                }
            });
        });

        $('.spnListaEventos').click(obtenerListaEventos)
    },1000);
}


function   obtenerFechaInicialFinal() {
    var cont = 1;
    var dateInit = '';
    var dateEnd = '';

    $('#calendar').find('.fc-view-container').first().find('.fc-widget-content').first().find('.fc-week').each(function(){
        $(this).find('.fc-day').each(function(){
            if(cont == 1){
                dateInit = $(this)['0'].dataset.date;
            }
            dateEnd = $(this)['0'].dataset.date;
            cont++;
        });
    });
    
    return {'start': new Date(dateInit), 'end': new Date(dateEnd)};
}


document.addEventListener('DOMContentLoaded', function() {

    var initialLocaleCode = 'es';
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid' ],    
      header: {
        left: 'prev,next,today',
        center: 'title',
        right: ''
      },
      locale: initialLocaleCode,
      navLinks: false,
      editable: true,
      eventLimit: true,
      events: function(start, end, callback) {
            var objDates = getDatesCalendar(start);        
            getInfoToCalendar(objDates);            
      }
    });    

    calendar.render();

    dates = obtenerFechaInicialFinal();
    var objDatesF = getDatesCalendar(dates);
    getInfoToCalendar(objDatesF);
  });
