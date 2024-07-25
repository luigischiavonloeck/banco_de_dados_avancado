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
    $docs = $collecRef->orderBy('cnpj')->limit(500)->documents();// ordenado por cnpj para que o grafico contenha multiplos anos, não apenas 2023

    $totalsByYear = [];

    foreach ($docs as $docItem) {
        if ($docItem->exists()) {
            $year = substr($docItem['mensuracao'], 0, 4); // Formato YYYY
            if (!isset($totalsByYear[$year][$docItem['porte']])) {
                $totalsByYear[$year][$docItem['porte']] = 0;
            }
            $totalsByYear[$year][$docItem['porte']] += $docItem['qt'];
        }
    }

    // Preparar os dados para a tabela
    $tableData = [];
    foreach ($totalsByYear as $year => $portes) {
        $grande = $portes[2] ?? 0;
        $pequeno = $portes[3] ?? 0;
        $total = $grande + $pequeno;
        $percentGrande = $total > 0 ? ($grande / $total) * 100 : 0;
        $percentPequeno = $total > 0 ? ($pequeno / $total) * 100 : 0;
        $tableData[] = [$year, $percentGrande, $percentPequeno];
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
    <table border="1" cellpadding='5'>
        <thead>
            <tr>
                <th>Ano</th>
                <th>% Clientes de Grande Porte</th>
                <th>% Clientes de Pequeno Porte</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tableData as $row): ?>
            <tr>
                <td><?php echo $row[0]; ?></td>
                <td><?php echo number_format($row[1], 2); ?>%</td>
                <td><?php echo number_format($row[2], 2); ?>%</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>