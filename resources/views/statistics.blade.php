@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            console.log('draw!!!!');
            var b_date=document.getElementById('begin_date').value;
            var e_date=document.getElementById('end_date').value;
            var rb1=document.getElementById('option1');
            var rb2=document.getElementById('option2');
            var jsonData = $.ajax({
                url: "getStatisticsData/"+b_date+"/"+e_date,
                async: false
            }).responseText;
            if(jsonData!=null){
                jsonArray=JSON.parse(jsonData);
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Платформа');
                data.addColumn('number', 'Количество заказов');
                data.addRows(jsonArray);
                if(rb1.checked===true){
                    var options = {'title':'Количество заказов с различных платформ',
                        'width':800,
                        'height':600,
                        is3D: true};
                    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                    chart.draw(data, options);
                }else {
                    var options = {'title':'Количество заказов с различных платформ',
                        'width':800,
                        'height':500,
                        'legend': { position: "none" }};
                    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
                    chart.draw(data, options);
                }
            }
        }
        $(document).ready (
            drawChart()
        );
    </script>
    <h1>Сводная диаграмма продаж платформ</h1>
    <div class="btn-group">
        <div style="margin-right: 20px">
            Начальная дата:
            <input type="date" class="input-group-text" id="begin_date" name="date" value="{{Carbon\Carbon::now()->startOfMonth()->toDateString()}}" onchange="drawChart()">
        </div>

        <div>
            Конечная дата:
            <input type="date" class="input-group-text" id="end_date" name="date" value="{{Carbon\Carbon::now()->toDateString()}}" onchange="drawChart()">
        </div>
    </div>
    <div class="flex-row" style="display: flex;flex-direction: row">
        <div id="chart_div"></div>
        <div class="btn-group-column" data-toggle="buttons">
            <h4>Тип диаграммы:</h4>
            <label class="btn btn-primary active">
                <input type="radio" name="options" id="option1" checked onchange="drawChart()"> Круговая диаграмма
            </label>
            <label class="btn btn-primary">
                <input type="radio" name="options" id="option2" onchange="drawChart()"> Гистограмма
            </label>
        </div>
    </div>
@endsection


