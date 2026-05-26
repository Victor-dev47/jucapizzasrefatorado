
<?php

//CRIACAO ROTA GETALL.PHP
 
// Headers obrigatórios

header("Access-Control-Allow-Origin: *");

header("Content-Type: application/json; charset=UTF-8");
 
// Incluir arquivos de banco de dados e modelo

//include_once '../../config/Database.php';
//include_once '../../models/Pizza.php';
 use VictorMlima7\Jucapizzasrefatorado\Models\Pizza;
 use VictorMlima7\Jucapizzasrefatorado\Config\Database;
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
 
// Instanciar o objeto Pizza

$pizza = new Pizza($db);
 
    // Chamar o método getall() para buscar as pizzas
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pizza->getall();
    }

    if (!$stmt) {
        //http_response_code(500);
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(array("message" => "Erro ao consultar as pizzas."));
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
        $pizzas_arr = array();

        foreach ($rows as $row) {
            $pizza_item = array(
                "id" => $row['idpizza'],
                "nome" => $row['nome'],
                "ingredientes" => $row['ingredientes'],
                "valor" => $row['valor']
            );

            array_push($pizzas_arr, $pizza_item);
        }
 
        // Definir o código de resposta como 200 OK

        //http_response_code(200);
        header("HTTP/1.1 200 OK");
 
        // Mostrar os dados das pizzas em formato JSON

        echo json_encode($pizzas_arr);

    } else {

        // Se nenhuma pizza for encontrada, definir o código de resposta como 404 Not Found

        //http_response_code(404);
 
        // Informar ao usuário que nenhuma pizza foi encontrada

        echo json_encode(

            array("message" => "Nenhuma pizza encontrada.")

        );

    }

// }

// catch (Exception $e) {

//  echo json_encode(array("erro" => $e->getMessage()));

// }
 
 