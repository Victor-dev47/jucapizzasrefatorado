<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('default_charset', 'UTF-8');

header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/Pizza.php';

$url = isset($_GET['url']) ? $_GET['url'] : '';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    echo "<!DOCTYPE html>\n<html lang='pt-BR'>\n<head>\n<meta charset='UTF-8'>\n<title>Erro de Conexão</title>\n</head>\n<body>\n<h1>Erro de conexão com o banco de dados</h1>\n<p>Verifique se o MySQL está rodando e se as credenciais estão corretas.</p>\n</body>\n</html>";
    exit;
}

$pizza = new Pizza($db);

echo "<!DOCTYPE html>\n<html lang='pt-BR'>\n<head>\n<meta charset='UTF-8'>\n<title>Pizzaria do Juca</title>\n</head>\n<body>\n<h1>🍕 Pizzaria do Juca</h1><hr>";

switch ($url) {

    // 📋 LISTAR
    case 'listar':

        echo "<a href='./'>Voltar</a><br><br>";

        foreach ($pizza->getall() as $p) {
            echo "
            <p>
                <strong>{$p['nome']}</strong><br>
                {$p['ingredientes']}<br>
                R$ {$p['valor']}<br>
                <a href='editar/{$p['idpizza']}'>✏️ Editar</a> |
                <a href='deletar/{$p['idpizza']}'>🗑️ Deletar</a>
            </p><hr>";
        }
        break;

    // ➕ CRIAR
    case 'criar':

        if ($_POST) {
            $pizza->nome = $_POST['nome'];
            $pizza->ingredientes = $_POST['ingredientes'];
            $pizza->valor = $_POST['valor'];

            $pizza->create();
            header("Location: listar");
            exit;
        }

        echo "
        <h2>Nova Pizza</h2>
        <form method='POST'>
            Nome: <input name='nome'><br>
            Ingredientes: <input name='ingredientes'><br>
            Valor: <input name='valor'><br>
            <button>Salvar</button>
        </form>
        ";
        break;

    // ✏️ EDITAR
    default:

        // editar/ID
        if (preg_match('/editar\/(\d+)/', $url, $matches)) {

            $id = $matches[1];
            $dados = $pizza->find($id);

            if ($_POST) {
                $pizza->idPizza = $id;
                $pizza->nome = $_POST['nome'];
                $pizza->ingredientes = $_POST['ingredientes'];
                $pizza->valor = $_POST['valor'];

                $pizza->update();
                header("Location: listar");
                exit;
            }

            if (!$dados) {
                echo "<p>Pizza não encontrada.</p>";
            } else {
                echo "
                <h2>Editar Pizza</h2>
                <form method='POST'>
                    Nome: <input name='nome' value='{$dados['nome']}'><br>
                    Ingredientes: <input name='ingredientes' value='{$dados['ingredientes']}'><br>
                    Valor: <input name='valor' value='{$dados['valor']}'><br>
                    <button>Atualizar</button>
                </form>
                ";
            }
        }

        // deletar/ID
        elseif (preg_match('/deletar\/(\d+)/', $url, $matches)) {

            $pizza->delete($matches[1]);
            header("Location: listar");
            exit;
        }

        // HOME
        else {
            echo "
            <h2>Menu</h2>
            <a href='listar'>📋 Ver Pizzas</a><br><br>
            <a href='criar'>➕ Nova Pizza</a>
            ";
        }
}

echo "</body>\n</html>";
