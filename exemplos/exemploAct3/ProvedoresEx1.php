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

?>

<http>
    <head>

    </head>
    <body>
    <table style="border: 2px solid;">
        <tr>
            <th>Empresa</th>
            <th>Quantidade</th>
            <th>Tecnologia</th>
            <th>T Produto</th>
            <th>Velocidade</th>
        </tr>
        <?php
            foreach ($data_prov as $reg_prov){
        ?>
        <tr>
            <td><?=$reg_prov['empresa'];?></td>
            <td><?=$reg_prov['qt'];?></td>
            <td><?=$reg_prov['tecnologia'];?></td>
            <td><?=$reg_prov['tproduto'];?></td>
            <td><?=$reg_prov['velocidade'];?></td>
        </tr>
        <?php
            }
        ?>
    </table>
    </body>
</http>
