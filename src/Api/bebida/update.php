<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
 
include_once '../../config/Database.php';
include_once '../../models/Bebida.php';
 
// Instanciar o banco de dados e conectar
$database = new Database();
$db = $database->getConnection();
 
// Instanciar o objeto Bebida
$bebida = new Bebida($db);
 
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    try {
        // Obter os dados postados
        $data = json_decode(file_get_contents("php://input"));
 
        // Verificar se os dados não estão vazios e se o ID foi fornecido
        if (
            !empty($data->id) &&
            !empty($data->nome) &&
            !empty($data->tipo) &&
            !empty($data->valor)
        ) {
            // Atribuir o ID para atualização
            $bebida->idBebida = $data->id; //é o que vem pelo json
 
            // Atribuir os demais valores
            $bebida->nome = $data->nome;
            $bebida->tipo = $data->tipo;
            $bebida->valor = $data->valor;
 
            // Tentar atualizar a bebida
            if ($bebida->update()) {
                header('HTTP/1.1 200 OK');
                // Resposta de sucesso    
                echo json_encode(
                    array('Mensagem' => 'Bebida Atualizada com Sucesso')
                );
            } else {
                header('HTTP/1.1 500 Internal Server Error');
                // Resposta de erro
                echo json_encode(
                    array('Mensagem' => 'Nao foi possivel atualizar a Bebida')
                );
            }
        } else {
            // Resposta se dados estiverem incompletos
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(
                array('Mensagem' => 'Dados Incompletos. Nao foi possivel atualizar a Bebida.')
            );
        }
    } catch (Exception $e) {        
        echo json_encode(array("erro" => $e->getMessage()));
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(array("erro" => "Método não suportado!"));
}