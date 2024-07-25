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
        
        $docs = $collecRef->orderBy('cnpj')->limit(500)->documents(); // ordenado por cnpj para que o grafico contenha multiplos anos, não apenas 2023

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
            $dataGraph[] = [strval($year),$total];
        }

        // ordena o array por ano
        usort($dataGraph, function($a, $b) {
          return $a[0] <=> $b[0];
      });

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
      <div id="chart_div" style="width: 100%; height: 700px;"></div>
    </body>
    </html>