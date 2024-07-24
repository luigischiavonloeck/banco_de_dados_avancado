<?php
require 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;

$configParams = [       //Array com paramentros de configuração
    'keyFilePath' => '/var/key/firebase_credentials.json',  //Arquivo com chaves de acesso
    'projectId' => 'cstsibda',                              //Banco de dados criado no FireStore
];

$db = new FirestoreClient($configParams);
$collecRef = $db->collection('NomeDaColeção');

//Retorna os 3 primeiros registros com maior idade
$docs = $collecRef->orderBy('idade', 'desc')->limit(3)->documents();

//Retorna do 3 ao 5 maior registro por idade
$docsB = $collecRef->orderBy('idade', 'desc')->offset(2)->limit(3)->documents();
foreach ($docsB as $doc) {
    if ($doc->exists()) {
        printf('Nome: %s' . PHP_EOL, $doc['nome']);     //Imprime o campo que vem em um array
        printf('Idade %s' . PHP_EOL, $doc['idade']);
    }else{
        printf("Documento nao existe");
    }
}


