<?php
require 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;

$configParams = [       //Array com paramentros de configuração
    'keyFilePath' => '/var/key/firebase_credentials.json',  //Arquivo com chaves de acesso
    'projectId' => 'cstsibda',                              //Banco de dados criado no FireStore
];

try {
    $db = new FirestoreClient($configParams);               // Cria o objeto de conexão
    $collecRef = $db->collection('NomeDaColeção');          // Define a conexão a ser utilizada
    $query = $collecRef->where('Idade', '<', 21);
    $docRef = $collecRef->documents();
    foreach ($docRef as $doc) {
        printf('Nome: %s' . PHP_EOL, $doc['nome']);
        printf('Idade %s' . PHP_EOL, $doc['idade']);
    }
}catch (GoogleException $e){                                //Falha na instalação do gRPC
    echo "Falha na biblioteca gRPC" . $e;
}catch (InvalidArgumentException $e){                       //Falha no arquivo ou na configuração
    echo "Erro nos parametros de conexão" . $e;
}