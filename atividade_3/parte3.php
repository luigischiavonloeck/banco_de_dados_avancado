<?php
require 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\Filter;

    $configParams = [
        'keyFilePath' => '.\firebase_credentials.json',
        'projectId' => 'firstproject-24838',
      ];

      $dataGraph = [];
    try {
        $db = new FirestoreClient($configParams);
        $collecRef = $db->collection('Provedores');
        //$datamaxima = $docs->rows()[0]['mensuracao'];
        $docs = $collecRef->limit(1000)->documents();

        $totalYear = []; 

        foreach ($docs as $docItem) {
            if ($docItem->exists()) {
              $year = substr($docItem['mensuracao'], 0, 4); // formato YYYY
              if (!isset($totalYear[$year])) {
                $totalYear[$year] = 0;
              }
              $totalYear[$year] += $docItem['qt'];
            }
        }

        foreach ($totalYear as $year => $total) {
            $dataGraph[] = [$year,$total];
        }

    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }

    $dataGraphjson = json_encode($dataGraph);
    ?>
    
    <!DOCTYPE html>
    <html>
      <head>
        <title>Visualizar Quantidade de clientes X Anos</title>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);
    
            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Ano');
                data.addColumn('number', 'Clientes');
                data.addRows(<?php echo $dataGraphjson; ?>);
    
                var options = {
                  title: 'Número de Assinantes por Ano',
                  hAxis: {title: 'Ano',  titleTextStyle: {color: '#333'}},
                  seriesType: 'line'
            };
    
            var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
            chart.draw(data, options);
            }
        </script>
      </head>
      <body>
      <div id="chart_div" style="width: 100%; height: 500px;"></div>
    </body>
    </html>