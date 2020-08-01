function obtenerDatosGraficos(){

  var dates = new Object();

  if($('#fechaInicial').val() != '' && $('#fechaFinal').val() != ''){
    dates = {
      fechaInicial: $('#fechaInicial').val(),
      fechaFinal: $('#fechaFinal').val()
    }
  }

  $.ajax({
    type: 'POST',
    url: $('#url-proyecto').val() + 'reportes/estadisticastortas',
    data: dates,
    success: function(data) {
      var respuesta = JSON.parse(data);      

      for(const prop in respuesta.resp){
        var divTorta = generarDiv(respuesta.resp[prop].titulo);
        $('#graficos').append(divTorta);
        generarGraficoTorta(respuesta.resp[prop].titulo, respuesta.resp[prop].legend_data, respuesta.resp[prop].series_data);
      }
    }
  });    
}

function generarDiv(idDiv){

  var divHtml = "";

  divHtml = '<div class="col-md-6 col-sm-4 col-xs-12">';
  divHtml += '<div class="x_panel">';
  divHtml += '<div class="x_title">';
  divHtml += '<h2><b>' + idDiv.toUpperCase() + '</b></h2>';
  divHtml += '</div>';
  divHtml += '<div class="x_content">';
  divHtml += '<div id="' + idDiv + '" style="height:450px;"></div>';
  divHtml += '</div>';
  divHtml += '</div>';
  divHtml += '</div>';

  return divHtml;
}

function generarGraficoTorta(idDiv, legend_data, series_data){

  var colorPalette = [
    '#FF0000','#F58634','#FFFF00','#0065D9','#00FFFF',
    '#00FF00','#9900CC','#000000','#e4ef6b','#b5223d',
    '#31ce18','#c9d9ff','#4ba2e5','#5379c6','#f98d66',
    '#9a6bef','#d341b1','#e55242','#fcbfcb','#dd7b39'
];
 
  var myChart = echarts.init(document.getElementById(idDiv), theme);
  myChart.setOption({
      
    color: colorPalette,

      tooltip: {
        trigger: 'item',
        formatter: "CANTIDAD: {c} <br/> {d}%"
      },
      legend: {
        x: 'center',
        y: 'bottom',
        data: legend_data        
      },
      series: [{
        name: idDiv,
        type: 'pie',
        radius: [30, 110],
        center: ['50%', '50%'], //left,top
        roseType: 'radius',
        data: series_data
      }]
    });   

}

var clearDate = function() {
  $('#fechaFinal').val('');
}

var obtenerEstadisticas = function() {
  // valida si la fecha inicial fue ingresada
  if($('#fechaInicial').val() == ''){
    bootbox.alert('Debe ingresar la fecha inicial', function(){
      $('#fechaFinal').val('');    
    });
  } else{
    $('#graficos').html('');
    obtenerDatosGraficos();
  }
}

$( function() {
  $(".date").datepicker({dateFormat: 'yy-mm-dd'});
  $(".date").datepicker("option", "showAnim", "slideDown");  

  obtenerDatosGraficos();

  $('#fechaFinal').change(obtenerEstadisticas);

  $('#fechaInicial').focus(clearDate);

});