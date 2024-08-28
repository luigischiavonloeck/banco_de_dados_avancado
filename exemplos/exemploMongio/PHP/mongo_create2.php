<?php

$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

$bulk = new MongoDB\Driver\BulkWrite;

$document1 = [
    'username' => 'admin',
    'email' => 'admin@example.com',
    'nome' => 'Admin User',
];
$bulk->insert($document1);

$document2 = [
    'username' => 'user1',
    'email' => 'user@example.com',
    'nome' => 'Basic User',
];
$bulk->insert($document2);

$result = $manager->executeBulkWrite('empregos.pessoas', $bulk);

printf("%d documento(s) Inserido(s)\n", $result->getInsertedCount());
