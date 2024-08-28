<?php

$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

$query = new MongoDB\Driver\Query([],[]);
$documents = $mongo->executeQuery('empregos.municipios', $query); // $mongo contains the connection object to MongoDB

foreach($documents as $document){
    $document = json_decode(json_encode($document),true);
    print_r($document);
}
