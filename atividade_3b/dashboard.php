<?php
require 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;

$configParams = [
    'keyFilePath' => '.\firebase_credentials.json',
    'projectId' => 'firstproject-24838',
];

try {
    $db = new FirestoreClient($configParams);
    $collecRef = $db->collection('Provedores');
    $docs = $collecRef->orderBy('mensuracao', 'ASC')->limit(100)->documents();

    $dataGraph = [];
    $totalYear = [];
    $allTechs = [];

    foreach ($docs as $docItem) {
        if ($docItem->exists()) {
            $year = substr($docItem['mensuracao'], 0, 4);
            if (!isset($totalYear[$year][$docItem['tecnologia']])) {
                $totalYear[$year][$docItem['tecnologia']] = 0;
            }
            $totalYear[$year][$docItem['tecnologia']] += $docItem['qt'];
            if (!in_array($docItem['tecnologia'], $allTechs)) {
                $allTechs[] = $docItem['tecnologia'];
            }
        }
    }

    foreach ($totalYear as $year => $techs) {
        $row = [(string)$year];
        foreach ($allTechs as $tech) {
            $row[] = $techs[$tech] ?? 0;
        }
        $dataGraph[] = $row;
    }

    usort($dataGraph, function($a, $b) {
        return $a[0] <=> $b[0];
    });

    $dataGraphjson = json_encode($dataGraph);
    $allTechsjson = json_encode($allTechs);

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard de Assinantes de Internet em Pelotas</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        *   {
          box-sizing: border-box;
          margin: 0;
          padding: 0;
        }
        .header {
          text-align: center;
          font-family: Arial, sans-serif;
          border-bottom: 1px solid #0005;
          padding: 15px;
          background-color: #ddd;
        }
        #chart_div {
            width: 100%;
            height: 500px;
        }
        .form-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1 class="header">Dashboard de Assinantes de Internet em Pelotas</h1>
    <div class="form-container">
        <label>Selecione as Tecnologias:</label>
        <div id="techCheckboxes">
            <?php foreach (json_decode($allTechsjson) as $tech): ?>
                <div>
                    <input type="checkbox" id="tech_<?php echo $tech; ?>" value="<?php echo $tech; ?>" onchange="drawPieChart()">
                    <label for="tech_<?php echo $tech; ?>"><?php echo $tech; ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div id="chart_div"></div>
    <select id="yearSelector">
        <?php
        $years = array_unique(array_column(json_decode($dataGraphjson, true), 0));
        foreach ($years as $year) {
            echo "<option value=\"$year\">$year</option>";
        }
        ?>
    </select>
    <div id="piechart_div"></div>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        var allTechs = <?php echo $allTechsjson; ?>; // Definindo allTechs no escopo global

        function drawChart() {
            var selectedTechs = Array.from(document.querySelectorAll('#techCheckboxes input:checked')).map(checkbox => checkbox.value);
            var techsToUse = selectedTechs.length > 0 ? selectedTechs : allTechs;

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Ano');
            techsToUse.forEach(function(tech) {
                data.addColumn('number', tech);
            });
            
            var allData = <?php echo $dataGraphjson; ?>;
            var filteredData = allData.map(row => {
                var newRow = [row[0]];
                techsToUse.forEach(function(tech) {
                    var index = allTechs.indexOf(tech) + 1;
                    newRow.push(row[index]);
                });
                return newRow;
            });

            data.addRows(filteredData);

            var options = {
                title: 'Número de Assinantes por Ano e Tecnologia',
                hAxis: {title: 'Ano', titleTextStyle: {color: '#333'}},
                vAxis: {minValue: 0},
                chartArea: {width: '70%', height: '70%'},
                isStacked: false
            };

            var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }

        function drawPieChart() {
            var selectedTechs = Array.from(document.querySelectorAll('#techCheckboxes input:checked')).map(checkbox => checkbox.value);
            var techsToUse = selectedTechs.length > 0 ? selectedTechs : allTechs;
            var selectedYear = document.getElementById('yearSelector').value;

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Tecnologia');
            data.addColumn('number', 'Assinantes');
            
            var allData = <?php echo $dataGraphjson; ?>;
            var techSums = techsToUse.map(function(tech) {
                var sum = 0;
                allData.forEach(function(row) {
                    if (row[0] == selectedYear) {
                        var index = allTechs.indexOf(tech) + 1;
                        sum += row[index];
                    }
                });
                return [tech, sum];
            });

            data.addRows(techSums);

            var options = {
                title: 'Distribuição de Assinantes por Tecnologia',
                chartArea: {width: '70%', height: '70%'}
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_div'));
            chart.draw(data, options);
        }

        document.querySelectorAll('#techCheckboxes input').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            drawChart();
            drawPieChart();
        });
        });
        document.getElementById('yearSelector').addEventListener('change', drawPieChart);
    </script>
</body>
</html>