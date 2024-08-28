<?php
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->update(['idade' => 10],
    ['$set' => ['idade' => 11]],
    ['multi' => true, 'upsert' => false]);
$result = $mongo->executeBulkWrite('empregos.pessoas', $bulk);

printf("%d documento(s) Atualizados(s)\n", $result->getModifiedCount());
