<?php
// Use este script uma vez para criar o banco e a tabela necessárias.

$host = '127.0.0.1';
$port = '3310';
$username = 'root';
$password = 'usbw';
$db_name = 'jucapizzasdb';

try {
    $dsn = "mysql:host=$host;port=$port;charset=utf8";
    $pdo = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8 COLLATE utf8_general_ci");
    $pdo->exec("USE `$db_name`");
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS `pizzas` (
            `idPizza` INT(11) NOT NULL AUTO_INCREMENT,
            `nome` VARCHAR(255) NOT NULL,
            `ingredientes` TEXT NOT NULL,
            `valor` DECIMAL(10,2) NOT NULL,
            PRIMARY KEY (`idPizza`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
    );

    echo "Banco de dados e tabela criados com sucesso.\n";
} catch (PDOException $e) {
    echo "Erro ao criar o banco de dados: " . $e->getMessage();
}
