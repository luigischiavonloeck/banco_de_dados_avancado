<http>
    <head>
        <?php
include "key.php";

require 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;

$db = new FirestoreClient($configParams);
$collecRef = $db->collection('Provedores');
$docs = $collecRef->orderBy('mensuracao', 'DESC')->limit(1)->offset(1)->documents();
$datamaxima = $docs->rows()[0]['mensuracao'];

$data_prov = $collecRef->where('mensuracao', '=',$datamaxima)->orderBy('empresa')->documents();
$name_prov = '';
$qt_clientes = 0;
?>
        <script>
            var arrProvedores = [];
            arrProvedores.push(['Provedor', 'Clientes'],)
            <?php
            foreach ($data_prov as $reg_prov){
                if ($name_prov != $reg_prov['empresa']){
                    if ($qt_clientes>0) {
                        echo 'arrProvedores.push(["' . $name_prov . '",' . $qt_clientes . ']);' . PHP_EOL;
                    }
                    $name_prov = $reg_prov['empresa'];
                    $qt_clientes = $reg_prov['qt'];
                }else{ // ($name_prov == $reg_prov['empresa'])
                    $qt_clientes += $reg_prov['qt'];
                }
            }
            ?>
        </script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">
        <script type="text/javascript" src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    </head>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(arrProvedores);
            var options = {
                title: 'Maiores provedores de Pelotas',
                sliceVisibilityThreshold: 0.02
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>

    <body>
    <div style="width: 100%; overflow: hidden;">
        <div style="width: 800px; float: left;">
            <table id="datatable"></table>
        </div>
        <div style="margin-left: 800px;">
            <div id="piechart" style="width: 800px; height: 800px;"></div>
        </div>
    </div>

    <script>
        new DataTable("#datatable", {
            data:arrProvedores.slice(1),
            columns: [
                { title: 'Provedor' },
                { title: 'Clientes' }
            ],
            order: [[1, 'desc']]
        });
    </script>
    </body>
</http>