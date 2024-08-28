<?php
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->delete(['nome' => 'Fulano','idade' => 10], ['limit' => 1]);

$result = $mongo->executeBulkWrite('empregos.pessoas', $bulk);

printf("%d documento(s) removidos(s)\n", $result->getDeletedCount());
