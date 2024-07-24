<?php
require 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\Filter;

$configParams = [       //Array com paramentros de configuração
    'keyFilePath' => '/var/key/firebase_credentials.json',  //Arquivo com chaves de acesso
    'projectId' => 'cstsibda',                              //Banco de dados criado no FireStore
];

$db = new FirestoreClient($configParams);
$collecRef = $db->collection('NomeDaColeção');
$qt = $collecRef->count();
$idadeMedia = $collecRef->avg('idade');
$idadeSomada = $collecRef->sum('idade');
printf('Quantidade de registos: %d' . PHP_EOL, $qt);
printf('Idade média: %f' . PHP_EOL, $idadeMedia);
printf('Idade somada: %f' . PHP_EOL, $idadeSomada);

$qtMaiores = $collecRef->where('idade','>',17)->count();
$qtMenores = $collecRef->where('idade','<',18)->count();
printf('Há: %d maiores de idade na coleção e %d menores de idade na coleção' . PHP_EOL, $qtMaiores,$qtMenores);




