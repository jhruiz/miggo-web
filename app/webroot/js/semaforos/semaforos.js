$(function () 
{ 
    $('#SemaforoColor').ColorPicker({
    onSubmit: function(hsb, hex, rgb) {
        $('#SemaforoColor').val(hex); 
        $('#SemaforoColor').ColorPickerHide(); }
    });
});

var nuevoSemaforo = function(){
    window.location.href = $('#url-proyecto').val() + "semaforos/add";
};