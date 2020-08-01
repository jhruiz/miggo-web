function generarGraficoTorta(idDiv, legend_data, series_data){

    var colorPalette = [
      '#A7DBD8','#E0E4CC','#F38630','#a22bd1','#d62102',
      '#e07d7b','#834ed8','#5b9906','#e4ef6b','#b5223d',
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

function generarDiv(idDiv){

    var divHtml = "";
  
    divHtml = '<div class="col-md-12">';
    divHtml += '<div class="x_panel">';
    divHtml += '<div class="x_title">';
    divHtml += '<h2><b>' + idDiv.toUpperCase() + '</b></h2>';
    divHtml += '</div>';
    divHtml += '<div class="x_content">';
    divHtml += '<div id="' + idDiv + '" style="height:300px;"></div>';
    divHtml += '</div>';
    divHtml += '</div>';
    divHtml += '</div>';
  
    return divHtml;
  }

function obtenerDatosGraficos(){
    $.ajax({
        type: 'POST',
        url: $('#url-proyecto').val() + 'alertaordenes/estadisticasfinalizadas',
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

$(function() {    
    obtenerDatosGraficos();
});