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
$docs = $collecRef->documents();
$docs[0]->update([
    ['path' => 'regiao', 'value' => 'Sul']
]);
foreach ($docs as $docItem){
    if ($docItem->exists()) {
        $collecRef->document($docItem->id())->update([
            ['path' => 'uf', 'value' => 'RS']
        ]);
    }
}

