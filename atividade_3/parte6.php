<?php
require 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\Filter;

    $configParams = [
        'keyFilePath' => '.\firebase_credentials.json',
        'projectId' => 'firstproject-24838',
      ];

      $dataGraph = [];
    try {
        $db = new FirestoreClient($configParams);
        $collecRef = $db->collection('Provedores');
        $docs = $collecRef->limit(1000)->documents();

        $provedores = [];

        foreach ($docs as $doc) {
            $provedor = $doc->data();
            $provedor['id'] = $doc->id();
            $provedores[] = $provedor;
        }

        $uniqueProvedores = array_map("unserialize", array_unique(array_map("serialize", $provedores)));

        $uniqueIds = array_column($uniqueProvedores, 'id');
        $duplicateDocs = array_filter($provedores, function($provedor) use ($uniqueIds) {
          return !in_array($provedor['id'], $uniqueIds);
        });

        echo "<h1>Duplicatas</h1>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Empresa</th><th>Grupo</th><th>Tecnologia</th><th>Quantidade</th><th>Velocidade</th></tr>";
        foreach ($duplicateDocs as $duplicateDoc) {
          echo "<tr><td>" . $duplicateDoc['empresa'] . "</td><td>" . $duplicateDoc['grupo'] . "</td><td>" . $duplicateDoc['tecnologia'] . "</td><td>" . $duplicateDoc['qt'] . "</td><td>" . $duplicateDoc['velocidade'] . "</td></tr>";
          $db->collection('Provedores')->document($duplicateDoc['id'])->delete();
        }
        echo "</table>";
        
        
      } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
      }
      
      $deleteDocsson = json_encode($deleteDocs);
      ?>
    
    <!DOCTYPE html>
    <html>
      <head>
        <title>Exclusão de duplicatas</title>
        
      </head>
      <body>
    </body>
    </html>