<?php
// CRIAÇÃO ROTA GET.PHP

// Headers obrigatórios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Incluir arquivos de banco de dados e modelo
include_once '../../config/Database.php';
include_once '../../models/Pizza.php';

// Instanciar o objeto Database e obter a conexão
$database = new Database();
$db = $database->getConnection();

if (!$db) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array(
        "message" => "Erro de conexão com o banco de dados."
    ));
    exit;
}

// Instanciar o objeto Pizza
$pizza = new Pizza($db);

// Verifica se o ID foi enviado
$pizza->idPizza = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // ID não informado
    if ($pizza->idPizza <= 0) {

        header("HTTP/1.1 400 Bad Request");

        echo json_encode(array(
            "message" => "id não informado."
        ));

    // ID informado e encontrado
    } elseif ($pizza->get()) {

        $pizza_arr = array(
            "id" => $pizza->idPizza,
            "nome" => $pizza->nome,
            "ingredientes" => $pizza->ingredientes,
            "valor" => $pizza->valor
        );

        echo json_encode($pizza_arr);

    // ID informado mas não existe
    } else {

        header("HTTP/1.1 404 Not Found");

        echo json_encode(array(
            "message" => "id inválido."
        ));
    }

} else {

    header("HTTP/1.1 405 Method Not Allowed");

    echo json_encode(array(
        "message" => "Método não permitido."
    ));
}
?>