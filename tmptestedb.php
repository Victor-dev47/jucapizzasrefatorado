<?php

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/Pizza.php';

echo "<h1>Teste de Conexão com o Banco</h1>";

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    echo "<p style='color: red;'>Falha na conexão com o banco de dados.</p>";
    exit;
}

echo "<p style='color: green;'>Conexão bem-sucedida!</p>";

echo "<h2>Teste do Model Pizza</h2>";

$pizza = new Pizza($db);

$result = $pizza->getall();

if (!$result) {
    echo "<p>Nenhuma pizza encontrada ou erro ao buscar os dados.</p>";
} else {
    echo "<p>Foram encontradas " . count($result) . " pizzas:</p>";
    echo "<pre>" . print_r($result, true) . "</pre>";
}
