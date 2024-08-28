<?php

$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

$bulk = new MongoDB\Driver\BulkWrite;

$document1 = [
    'username' => 'admin',
    'email' => 'admin@example.com',
    'nome' => 'Admin User',
];
$_id1 = $bulk->insert($document1);
$result = $manager->executeBulkWrite('empregos.pessoas', $bulk);

printf("%d documento(s) Inserido(s)\n", $result->getInsertedCount());
