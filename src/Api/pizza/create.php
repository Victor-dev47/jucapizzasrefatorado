<?php
/**
 * =========================================================================
 * O QUE FAZ ISTO? (bem simples)
 * =========================================================================
 * CRIAR = inserir uma pizza NOVA na base de dados.
 *
 * O browser ou o JavaScript NÃO manda os dados na barra de endereço; manda no
 * "corpo" do pedido em formato JSON. Por isso usamos POST (tradição para "criar")
 * e lemos com php://input — é o sítio onde o texto JSON chega cru.
 *
 * OPTIONS / 204: quando o site está noutro domínio/porta, o browser manda primeiro
 * um pedido "posso?" (preflight). Respondemos 204 sem corpo para dizer "podes".
 */
 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
 
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 204 No Content");
    exit;
}
 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(array("message" => "Método não permitido. Use POST."));
    exit;
}
 
include_once '../../config/Database.php';
include_once '../../models/Pizza.php';
 
// file_get_contents("php://input") lê o corpo bruto; json_decode transforma em objeto PHP.
$data = json_decode(file_get_contents("php://input"));
if (!$data || !isset($data->nome, $data->ingredientes, $data->valor)) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(array("message" => "Envie JSON com nome, ingredientes e valor."));
    exit;
}
 
$database = new Database();
$db = $database->getConnection();
if (!$db) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array("message" => "Erro de conexão com o banco."));
    exit;
}
 
$pizza = new Pizza($db);
$pizza->nome = $data->nome;
$pizza->ingredientes = $data->ingredientes;
$pizza->valor = $data->valor;
 
if ($pizza->create()) {
    // 201 Created = padrão HTTP para "acabei de nascer um recurso novo".
    header("HTTP/1.1 201 Created");
    echo json_encode(array(
        "message" => "Pizza criada.",
        "id" => (int) $pizza->idPizza,
        "nome" => $pizza->nome,
        "ingredientes" => $pizza->ingredientes,
        "valor" => (float) $pizza->valor,
    ));
} else {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array("message" => "Não foi possível criar a pizza."));
}