
<?php

//CRIACAO ROTA GETALL.PHP
 
// Headers obrigatórios

header("Access-Control-Allow-Origin: *");

header("Content-Type: application/json; charset=UTF-8");
 
// Incluir arquivos de banco de dados e modelo

include_once '../../config/Database.php';

include_once '../../models/Bebida.php';
 
// Instanciar o objeto Database e obter a conexão

$database = new Database();

$db = $database->getConnection();

if (!function_exists('http_response_code')) {
    function http_response_code($code = null) {
        static $current_code = 200;
        if ($code !== null) {
            header('X-PHP-Response-Code: ' . $code, true, $code);
            $current_code = $code;
        }
        return $current_code;
    }
}

if (!$db) {
    //http_response_code(500);
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array("message" => "Erro de conexão com o banco de dados."));
    exit;
}
 
// Instanciar o objeto Bebida

$bebida = new Bebida($db);
 
    // Chamar o método getall() para buscar as bebidas
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $bebida->getall();
    }

    if (!$stmt) {
        //http_response_code(500);
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(array("message" => "Erro ao consultar as bebidas."));
        exit;
    }

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows === false) {
        //http_response_code(500);
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(array("message" => "Erro ao ler os resultados da consulta."));
        exit;
    }

    if (count($rows) > 0) {
        $bebidas_arr = array();

        foreach ($rows as $row) {
            $bebida_item = array(
                "id" => $row['idbebida'],
                "nome" => $row['nome'],
                "tipo" => $row['tipo'],
                "valor" => $row['valor']
            );

            array_push($bebidas_arr, $bebida_item);
        }
 
        // Definir o código de resposta como 200 OK

        //http_response_code(200);
        header("HTTP/1.1 200 OK");
 
        // Mostrar os dados das bebidas em formato JSON

        echo json_encode($bebidas_arr);

    } else {

        // Se nenhuma bebida for encontrada, definir o código de resposta como 404 Not Found

        //http_response_code(404);
 
        // Informar ao usuário que nenhuma bebida foi encontrada

        echo json_encode(

            array("message" => "Nenhuma bebida encontrada.")

        );

    }

// }

// catch (Exception $e) {

//  echo json_encode(array("erro" => $e->getMessage()));

// }
 