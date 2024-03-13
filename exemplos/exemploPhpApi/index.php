<?php
require __DIR__.'/vendor/autoload.php';
use Kreait\Firebase\Factory;

$factory = (new Factory)
    ->withServiceAccount('./firebase_credentials.json')
    ->withDatabaseUri('https://firstproject-24838-default-rtdb.firebaseio.com');
$database = $factory
    ->withDatabaseAuthVariableOverride(null)
    ->createDatabase();



$database->getReference('users/4')
    ->set([
        'username' => 'Deltano',
        'email' => 'deltano@ifsul.edu.br'
        ]);

$database->getReference('users')
    ->push([
        'username' => 'Deltano',
        'email' => 'deltano@ifsul.edu.br',
        'id' => '4'
    ]);

