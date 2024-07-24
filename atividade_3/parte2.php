<?php
require 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\Filter;

    $configParams = [
        'keyFilePath' => '.\firebase_credentials.json',
        'projectId' => 'firstproject-24838',
    ];

    try {
        $db = new FirestoreClient($configParams);
        $collecRef = $db->collection('Provedores');
        $docs = $collecRef->where(Filter::and([
            Filter::field('mensuracao', '=', '2010-09-01'),
            Filter::field('qt', '>', 20),
        ]))->limit(10)->documents();

        foreach ($docs as $docItem) {
            if ($docItem->exists()) {
                $collecRef->document($docItem->id())->update([
                    ['path' => 'qt', 'value' => $docItem['qt'] + 1]
                ]);
            }
        }
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }

    ?>
    
    <!DOCTYPE html>
    <html>
      <head>
        <title>Visualizar Dados de Acesso</title>
      </head>
      <body>
        <h1>Dados Alterados</h1>
        <table border='1' cellpadding='10'>
        <tr><th>Empresa</th><th>Grupo</th><th>Quantidade</th></tr>
          <?php foreach ($docs as $docItem) { ?>
            <tr>
              <td><?php echo $docItem['empresa']; ?></td>
              <td><?php echo $docItem['grupo']; ?></td>
              <td><?php echo $docItem['qt']; ?></td>
            </tr>
          <?php } ?>
      </table>
    </body>
    </html>