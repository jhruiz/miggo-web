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
    async: false,
    success: function(data) {
      var respuesta = JSON.parse(data);     

      for(const prop in respuesta.barras){
        
        if(respuesta.barras[prop].legend_data.length > 0){
          var divTorta = generarDiv(respuesta.barras[prop].titulo);
          $('#graficos').append(divTorta);
          generarGraficoBarras(respuesta.barras[prop].titulo, respuesta.barras[prop].legend_data, respuesta.barras[prop].series_data);
        }

      }

      for(const prop in respuesta.tortas){
        
        if(respuesta.tortas[prop].legend_data.length > 0){
          var divTorta = generarDiv(respuesta.tortas[prop].titulo);
          $('#graficos').append(divTorta);
          generarGraficoTorta(respuesta.tortas[prop].titulo, respuesta.tortas[prop].legend_data, respuesta.tortas[prop].series_data);
        }

      }
    }
  });    
}

function generarDiv(idDiv){
    return `
    <div class="col-md-4 col-sm-6" style="margin-bottom: 15px; padding: 5px;">
        <div class="card shadow-sm" style="border: 1px solid #e0e0e0; border-radius: 8px; background: #fff;">
            <div class="card-header bg-white py-2" style="border-bottom: 1px solid #eee;">
                <h4 class="card-title mb-0" style="font-size: 12px; color: #333;">
                    <b><i class="fa fa-pie-chart text-primary"></i> ${idDiv.toUpperCase()}</b>
                </h4>
            </div>
            <div class="card-body" style="padding: 10px; background-color: #fcfcfc;">
                <div id="${idDiv}" style="height:300px; width: 100%;"></div>
            </div>
        </div>
    </div>`;
}

function generarGraficoTorta(idDiv, legend_data, series_data) {
    var chartDom = document.getElementById(idDiv);
    
    // 1. Limpiar instancia previa si existe (evita errores de duplicados)
    var instance = echarts.getInstanceByDom(chartDom);
    if (instance) {
        instance.dispose();
    }

    var myChart = echarts.init(chartDom);
    
    var option = {
        // Ahora puedes usar paletas mucho más limpias
        color: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
        tooltip: { trigger: 'item' },
        series: [{
            type: 'pie',
            radius: ['50%', '70%'],
            avoidLabelOverlap: true,
            itemStyle: {
                borderRadius: 8, // Bordes mucho más suaves
                borderColor: '#fff',
                borderWidth: 2
            },
            label: {
                show: true,
                formatter: '{b}: {d}%'
            },
            emphasis: {
                label: {
                    show: true,
                    fontSize: '14',
                    fontWeight: 'bold'
                }
            },
            data: series_data
        }]
    };

    myChart.setOption(option);
    
    // 2. Hacer que el gráfico sea responsivo (se ajuste si cambias el tamaño de la ventana)
    window.addEventListener('resize', function() {
        myChart.resize();
    });
}

function generarGraficoBarras(idDiv, legend_data, series_data){
    var colorPalette = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'];
    var myChart = echarts.init(document.getElementById(idDiv));

    myChart.setOption({
        color: colorPalette,
        tooltip: {
            trigger: 'axis',
            axisPointer: { type: 'shadow' }
        },
        grid: {
            left: '3%',
            right: '10%',
            bottom: '5%',
            top: '5%',
            containLabel: true // Esto evita que los nombres largos se corten a la izquierda
        },
        xAxis: {
            type: 'value',
            splitLine: { show: false }, // Quitamos las líneas de fondo para que sea más limpio
            axisLabel: { show: true }
        },
        yAxis: {
            type: 'category',
            data: legend_data, // Aquí van los nombres
            axisTick: { show: false },
            axisLine: { lineStyle: { color: '#ddd' } },
            axisLabel: { 
                fontSize: 11,
                color: '#444'
            }
        },
        series: [{
            name: 'Cantidad',
            type: 'bar',
            data: series_data.map(item => item.value), // Extraemos solo los valores numéricos
            barMaxWidth: 25, // Para que no se vean excesivamente gruesas si hay pocos datos
            itemStyle: {
                // Un degradado sutil y bordes redondeados a la derecha
                borderRadius: [0, 5, 5, 0],
                color: function(params) {
                    // Esto aplica los colores de tu paleta a cada barra individualmente
                    return colorPalette[params.dataIndex % colorPalette.length];
                }
            },
            label: {
                show: true,
                position: 'right', // Muestra el número al final de la barra
                formatter: '{c}',
                textStyle: { fontWeight: 'bold', color: '#555' }
            }
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