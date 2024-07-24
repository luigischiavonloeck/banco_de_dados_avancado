<?php

require 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;

$configParams = [       //Array com paramentros de configuração
    'keyFilePath' => '/var/key/firebase_credentials.json',  //Arquivo com chaves de acesso
    'projectId' => 'cstsibda',                              //Banco de dados criado no FireStore
];

$db = new FirestoreClient($configParams);
$batch= $db->bulkWriter();
$collecRef = $db->collection('Provedores');


$json = file_get_contents('provedoresPel.json');
$json_data = json_decode($json,true);

printf("Iniciado as %s". PHP_EOL, date("h:i:s.v"));
$time_start = microtime(true);
$i = 0;
$qt = count($json_data);
foreach ($json_data as &$registro) {
    $docRef = $collecRef->document($i++);
    $batch->set($docRef,$registro);
    if ($i % 100 == 0)
        printf('%d de %d registros processados' . PHP_EOL, $i, $qt);
}
$batch->commit();
$time_end = microtime(true);
printf("Terminado as %s" . PHP_EOL, date("h:i:s.v"));
$execution_time = ($time_end - $time_start);


printf('Processamento finalizado' . PHP_EOL);
printf("Tempo de execução as %f segundos em média %f registros por segundo" . PHP_EOL, $execution_time, $qt/$execution_time);
