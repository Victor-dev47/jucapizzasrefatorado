<?php
 
 namespace VictorMlima7\Jucapizzasrefatorado\Models;
 use Pdo;
 use Exception;
class Pizza
{
    private $conn;
    private $tabela = "pizzas";
    public $idpizza;
    public $idPizza;
    public $nome;
    public $ingredientes;
    private float $valor;
    public function getValor(): float
    {
        return $this->valor;
    }
    public function setValor(float $valor): void
    {
        $this->valor = $valor;
    }
 
    public function __construct($conexao)
    {
        $this->conn = $conexao;
    }
 
    private function getIdValue()
    {
        return ($this->idPizza !== null) ? $this->idPizza : $this->idpizza;
    }
 
    public function getall()
    {
        $query = "SELECT idPizza AS idpizza, nome, ingredientes, valor FROM " . $this->tabela;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
 
    public function get()
    {
        $id = $this->getIdValue();
        if (!$id) {
            return false;
        }

        $query = "SELECT idPizza AS idpizza, nome, ingredientes, valor FROM " . $this->tabela . " WHERE idPizza = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        $this->idpizza = $row['idpizza'];
        $this->idPizza = $row['idpizza'];
        $this->nome = $row['nome'];
        $this->ingredientes = $row['ingredientes'];
        $this->valor = $row['valor'];

        return true;
    }
 
    public function find($id)
    {
        $query = "SELECT idPizza AS idpizza, nome, ingredientes, valor FROM " . $this->tabela . " WHERE idPizza = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
 
    public function create()
    {
        $query = "INSERT INTO " . $this->tabela . " (nome, ingredientes, valor) VALUES (:nome, :ingredientes, :valor)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':ingredientes', $this->ingredientes);
        $stmt->bindValue(':valor', $this->valor);
        if (!$stmt->execute()) {
            return false;
        }
        $this->idPizza = $this->conn->lastInsertId();
        return true;
    }
 
    public function update()
    {
        $query = 'UPDATE ' . $this->tabela . ' SET nome=:nome, ingredientes=:ingredientes, valor=:valor WHERE idPizza=:id';

        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->ingredientes = htmlspecialchars(strip_tags($this->ingredientes));
        $this->valor = htmlspecialchars(strip_tags($this->valor));
        $this->idPizza = htmlspecialchars(strip_tags($this->idPizza));

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':ingredientes', $this->ingredientes);
        $stmt->bindParam(':valor', $this->valor);
        $stmt->bindParam(':id', $this->idPizza);

        return $stmt->execute();
    }

    public function delete() {
        // Query de exclusão
        $query = 'DELETE FROM ' . $this->tabela . ' WHERE idPizza=:id';
 
        // Preparar a query
        $stmt = $this->conn->prepare($query);
 
        // Vincular o ID
        $stmt->bindParam(':id', $this->idPizza);
 
        // Executar a query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
 

    public function add()
    {
        $query = "INSERT INTO " . $this->tabela . " (nome, ingredientes, valor) VALUES (:nome, :ingredientes, :valor)";
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->ingredientes = htmlspecialchars(strip_tags($this->ingredientes));
        $this->valor = htmlspecialchars(strip_tags($this->valor));

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':ingredientes', $this->ingredientes);
        $stmt->bindParam(':valor', $this->valor);

        if ($stmt->execute()) {
            $this->idPizza = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }
}
