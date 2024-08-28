<?php
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

$filter = ["empregos"=>['$gt'=>500],"ano"=>2021];
$options = ['projection' => ['_id' => 0]];
$query = new MongoDB\Driver\Query($filter,$options);

$documents = $mongo->executeQuery('empregos.empregosti', $query);
foreach($documents as $document){
    print_r(json_decode(json_encode($document),true));
}