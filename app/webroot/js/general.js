
 


$(document).ready(function(){

        var configDatepicker ={
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            //numberOfMonths: 3,
            dateFormat: "dd/mm/yy",
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        };

        $.datepicker.setDefaults(configDatepicker);
        
        $('#fecha_desde').datepicker({ 
            
              onClose: function( selectedDate ) {
                $( "#fecha_hasta" ).datepicker( "option", "minDate", selectedDate );
            }
        });


        $('#fecha_hasta').datepicker({ 
              
              onClose: function( selectedDate ) {
                $( "#fecha_desde" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
        
});    

