<?php
require 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\Filter;

$configParams = [       //Array com paramentros de configuração
    'keyFilePath' => '/var/key/firebase_credentials.json',  //Arquivo com chaves de acesso
    'projectId' => 'cstsibda',                              //Banco de dados criado no FireStore
];

$db = new FirestoreClient($configParams);               // Cria o objeto de conexão
$collecRef = $db->collection('NomeDaColeção');          // Define a conexão a ser utilizada

$docsQuery = $collecRef->where(Filter::or([
    Filter::field('nome','=','Aluno'),
    Filter::field('idade','<',21) ]));
$docRef = $docsQuery->documents();
foreach ($docRef as $doc) {
    printf('Nome: %s' . PHP_EOL, $doc['nome']);
    printf('Idade %s' . PHP_EOL, $doc['idade']);
}
