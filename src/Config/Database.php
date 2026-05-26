<?php
 
// config/Database.php

 namespace VictorMlima7\Jucapizzasrefatorado\Config;
class Database # definição da classe Database
{
    // variáveis privadas para armazenar as informações de conexão com o banco de dados
    private $host = 'localhost';
    private $db_name = 'pizzadoJuca';
    private $username = 'root';
    private $password = 'usbw';
    private $port = '3310';
 
    // variável pública para armazenar a conexão com o banco de dados
    public $conn;
 
    // método para obter a conexão com o banco de dados
    public function getConnection()
    {
        // inicializa a variável de conexão como nula
        $this->conn = null;
 
        //tenta executar um codigo que pode gerar um erro
        try {
 
            # string de conexão com o banco de dados usando o formato DSN (Data Source Name)
            $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->db_name . ';charset=utf8'; #adiciona o charset utf8 à string de conexão
 
            # Instancia a classe PDO para estabelecer a conexão com o banco de dados usando as informações fornecidas
            $this->conn = new PDO($dsn, $this->username, $this->password);
 
                # Configura o modo de erro do PDO para lançar exceções em caso de erros de conexão ou consulta
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
        } catch (PDOException $e) #captura o erro e armazena na variavel $e | PDOException é um tipo de erro específico para conexões com banco de dados
 
        {
            echo "Erro de Conexão: " . $e->getMessage(); #exibe a mensagem de erro
        } catch (Exception $e) #captura qualquer outro tipo de erro
        {
            echo "Erro: " . $e->getMessage(); #exibe a mensagem de erro
        }
 
        return $this->conn; #retorna a conexão com o banco de dados
    }
}