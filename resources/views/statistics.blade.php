@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">


        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.

        function drawChart() {
            console.log('draw!!!!');
            var b_date=document.getElementById('begin_date').value;
            var e_date=document.getElementById('end_date').value;
            var rb1=document.getElementById('option1');
            var rb2=document.getElementById('option2');
            var jsonData = $.ajax({
                url: "getStatisticsData/"+b_date+"/"+e_date,
                // dataType: "json",
                async: false
            }).responseText;
            jsonArray=JSON.parse(jsonData);
            // Create our data table out of JSON data loaded from server.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Платформа');
            data.addColumn('number', 'Количество заказов');
            data.addRows(jsonArray);

            // Instantiate and draw our chart, passing in some options.
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


