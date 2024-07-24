<?php

require 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;

$configParams = [       //Array com paramentros de configuração
    'keyFilePath' => '/var/key/firebase_credentials.json',  //Arquivo com chaves de acesso
    'projectId' => 'cstsibda',                              //Banco de dados criado no FireStore
];

$db = new FirestoreClient($configParams);
$collecRef = $db->collection('Provedores');
$docs = $collecRef->orderBy('mensuracao', 'DESC')->limit(1)->documents();
$datamaxima = $docs->rows()[0]['mensuracao'];

$data_prov = $collecRef->where('mensuracao', '=',$datamaxima)->orderBy('empresa')->documents();
$name_prov = '';
$qt_clientes = 0;
?>

<http>
    <head>

    </head>
    <body>
    <table style="border: 2px solid;">
        <tr>
            <th>Empresa</th>
            <th>Quantidade</th>
        </tr>
        <?php
            foreach ($data_prov as $reg_prov){
                if ($name_prov != $reg_prov['empresa']){
                    if ($qt_clientes>0) {
                        echo "<tr></tr><td>" . $name_prov . "</td>" . PHP_EOL;
                        echo "<td>" . $qt_clientes . "</td></tr>" . PHP_EOL;
                    }
                    $name_prov = $reg_prov['empresa'];
                    $qt_clientes = 0;
                }else{ // ($name_prov == $reg_prov['empresa'])
                    $qt_clientes += $reg_prov['qt'];
                }
        ?>
        <?php
            }
        ?>
    </table>
    </body>
</http>
