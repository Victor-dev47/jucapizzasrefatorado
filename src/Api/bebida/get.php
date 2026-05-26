<?php
// CRIAÇÃO ROTA GET.PHP
// Headers obrigatórios
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// Incluir arquivos de banco de dados e modelo
include_once '../../config/Database.php';
include_once '../../models/Bebida.php';
 
// Instanciar o objeto Database e obter a conexão
$database = new Database();
$db = $database->getConnection();
 
if (!$db) {
    //http_response_code(500);
     header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array("message" => "Erro de conexão com o banco de dados."));
    exit;
}
 
// Instanciar o objeto Bebida
$bebida = new Bebida($db);
$bebida->idBebida = isset($_GET['id']) ? (int) $_GET['id'] : 0;
 
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    // ID não informado
    if ($bebida->idBebida <= 0) {

        header("HTTP/1.1 400 Bad Request");

        echo json_encode(array(
            "message" => "id não informado."
        ));

    // ID informado e encontrado
    } elseif ($bebida->get()) {

        $bebida_arr = array(
            "id" => $bebida->idBebida,
            "nome" => $bebida->nome,
            "tipo" => $bebida->tipo,
            "valor" => $bebida->valor
        );

        echo json_encode($bebida_arr);

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