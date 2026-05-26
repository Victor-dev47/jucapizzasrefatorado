<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");



if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(array("message" => "Método não permitido. Use DELETE."));
    exit;
}

include_once '../../config/Database.php';
include_once '../../models/bebida.php';

$database = new Database();
$db = $database->getConnection();
if (!$db) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array("message" => "Erro de conexão com o banco."));
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(array("message" => "Falta o parâmetro id ou ele é inválido."));
    exit;
}

$bebida = new Bebida($db);
if (!$bebida->find($id)) {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(array("message" => "Bebida com id $id não existe."));
    exit;
}

if ($bebida->delete($id)) {
    echo json_encode(array("message" => "Bebida removida com sucesso.", "id" => $id));
} else {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array("message" => "Erro ao excluir a bebida."));
}
