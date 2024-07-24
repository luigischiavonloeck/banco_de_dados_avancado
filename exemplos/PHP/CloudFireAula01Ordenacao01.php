<?php
require 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;

$configParams = [       //Array com paramentros de configuração
    'keyFilePath' => '/var/key/firebase_credentials.json',  //Arquivo com chaves de acesso
    'projectId' => 'cstsibda',                              //Banco de dados criado no FireStore
];

$db = new FirestoreClient($configParams);               // Cria o objeto de conexão
$collecRef = $db->collection('NomeDaColeção');          // Define a conexão a ser utilizada
$docs = $collecRef->orderBy('idade')->documents();
//$docs = $collecRef->orderBy('nome')->orderBy('idade')->documents();
foreach ($docs as $doc) {
    if ($doc->exists()) {
        printf('Nome: %s' . PHP_EOL, $doc['nome']);     //Imprime o campo que vem em um array
        printf('Idade %s' . PHP_EOL, $doc['idade']);
    }else{
        printf("Documento nao existe");
    }
}


