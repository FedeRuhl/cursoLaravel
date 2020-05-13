const chart = Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Medicos más activos'
    },
    xAxis: {
        categories: [],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Turnos atendidos'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    series: []
});

let $start, $end;
$start = $('#startDate');
$end = $('#endDate');

function fetchData(){
    const startDate = $start.val();
    const endDate = $end.val();
    // Fetch API
    const url = `/charts/doctors/column/data?start=${startDate}&end=${endDate}`;
    fetch(url)
    .then(response => response.json()) //parametro => return
    .then(data => { //parametro => funcion callback
        chart.xAxis[0].setCategories(data.categories);
        var seriesLength = chart.series.length;
        if (seriesLength > 0){
            for (var i = seriesLength -1; i > -1; i--){
                chart.series[i].remove();
            }
        }
        chart.addSeries(data.series[0]); //Atendidas
        chart.addSeries(data.series[1]); //Canceladas
    });
}

$(function() {
    fetchData();
    $start.change(fetchData);
    $end.change(fetchData);
});