<?php
require 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;

$configParams = [
    'keyFilePath' => '.\firebase_credentials.json',
    'projectId' => 'firstproject-24838',
];

try {
    $db = new FirestoreClient($configParams);
    $collecRef = $db->collection('Provedores');
    $docs = $collecRef->limit(1000)->documents();

    $totalsByYear = []; // Estrutura: [ano][porte] => soma

    foreach ($docs as $docItem) {
        if ($docItem->exists()) {
            $year = substr($docItem['mensuracao'], 0, 4); // Formato YYYY
            $porte = $docItem['porte']; // Porte do provedor
            if (!isset($totalsByYear[$year][$porte])) {
                $totalsByYear[$year][$porte] = 0;
            }
            $totalsByYear[$year][$porte] += $docItem['qt'];
        }
    }

    // Preparar os dados para a tabela
    $tableData = [];
    foreach ($totalsByYear as $year => $portes) {
        $grande = $portes[2] ?? 0;
        $pequeno = $portes[3] ?? 0;
        $total = $grande + $pequeno;
        $percentualGrande = $total > 0 ? ($grande / $total) * 100 : 0;
        $tableData[] = [$year, $percentualGrande, $pequeno];
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Comparativo de Porte de Provedores por Ano</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>Ano</th>
                <th>% Clientes de Grande Porte</th>
                <th>Número de Clientes de Pequeno Porte</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tableData as $row): ?>
            <tr>
                <td><?php echo $row[0]; ?></td>
                <td><?php echo number_format($row[1], 2); ?>%</td>
                <td><?php echo $row[2]; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>