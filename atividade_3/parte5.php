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
        
        $docs = $collecRef->orderBy('mensuracao','ASC')->limit(1000)->documents(); // ordenado por ano asc para que o grafico contenha multiplos anos, não apenas 2023

        $totalYear = []; 
        $allTechs = [];

        foreach ($docs as $docItem) {
            if ($docItem->exists()) {
              $year = substr($docItem['mensuracao'], 0, 4); // formato YYYY
              if (!isset($totalYear[$year][$docItem['tecnologia']])) {
                $totalYear[$year][$docItem['tecnologia']] = 0;
              }
              $totalYear[$year][$docItem['tecnologia']] += $docItem['qt'];
              if (!in_array($docItem['tecnologia'], $allTechs)) {
                $allTechs[] = $docItem['tecnologia'];
              }
            }
        }

        //charts sao tipo ['Year', 'tecnologia', 'tecnologia']
       //                 ['2013',  1000,      400]

       
       foreach ($totalYear as $year => $techs) {
         $row = [(string)$year];
         foreach ($allTechs as $tech) {
           $row[] = $techs[$tech] ?? 0;
          }
          $dataGraph[] = $row;
        }
        
        // ordena o array por ano
        usort($dataGraph, function($a, $b) {
          return $a[0] <=> $b[0];
        });
        
        array_unshift($dataGraph, ['Ano', ...$allTechs]);
        
        
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
            var data = google.visualization.arrayToDataTable(<?php echo $dataGraphjson; ?>);

            var options = {
                title: 'Quantidade de clientes X Anos, divididos por tecnologia',
                hAxis: {title: 'Ano',  titleTextStyle: {color: '#333'}},
                vAxis: {minValue: 0}
            };
    
            var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
            chart.draw(data, options);
            }
        </script>
      </head>
      <body>
      <div id="chart_div" style="width: 100%; height: 700px;"></div>
    </body>
    </html>