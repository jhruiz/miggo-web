/**
 * Formato a los input tipo date
 * @returns {undefined}
 */
var datePicker = function(){
    $(".date").datepicker({dateFormat: 'yy-mm-dd'});
    $(".date").datepicker("option", "showAnim", "slideDown");    
};

$(function() {    
    datePicker();
});