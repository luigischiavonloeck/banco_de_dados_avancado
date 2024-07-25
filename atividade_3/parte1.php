<?php
require 'vendor/autoload.php';
use Google\Cloud\Firestore\FirestoreClient;

if (isset($_POST['submit'])) {
    $selectedDate = $_POST['date'];

    $configParams = [
        'keyFilePath' => '.\firebase_credentials.json',
        'projectId' => 'firstproject-24838',
    ];

    try {
        $db = new FirestoreClient($configParams);
        $collecRef = $db->collection('Provedores');
        $docs = $collecRef->where('mensuracao', '==', $selectedDate)->limit(50)->documents();
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Empresa</th><th>Grupo</th><th>Tecnologia</th><th>Quantidade</th><th>Velocidade</th></tr>";
        foreach ($docs as $doc) {
          echo "<tr><td>" . $doc['empresa'] . "</td><td>" . $doc['grupo'] . "</td><td>" . $doc['tecnologia'] . "</td><td>" . $doc['qt'] . "</td><td>" . $doc['velocidade'] . "</td></tr>";
        }
        echo "</table>";
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visualizar Dados de Acesso</title>
</head>
<body>
    <form action="" method="post">
        <label for="date">Selecione a data:</label>
        <select name="date" id="date">
            <?php
            for ($i = 2023; $i >= 2007; $i--) {
                echo "<option value='$i-09-01'>$i-09-01</option>";
            } ?>
        </select>
        <button type="submit" name="submit">Pesquisar</button>
    </form>
</body>
</html>