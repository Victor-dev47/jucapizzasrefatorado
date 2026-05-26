<?php
 
class Bebida
{
    private $conn;
    private $tabela = "bebida";
    public $idbebida;
    public $idBebida;
    public $nome;
    public $tipo;
    public $valor;
 
    public function __construct($conexao)
    {
        $this->conn = $conexao;
    }
 
    private function getIdValue()
    {
        return ($this->idBebida !== null) ? $this->idBebida : $this->idbebida;
    }
 
    public function getall()
    {
        $query = "SELECT idBebida AS idbebida, nome, tipo, valor FROM " . $this->tabela;
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

        $query = "SELECT idBebida AS idbebida, nome, tipo, valor FROM " . $this->tabela . " WHERE idBebida = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        $this->idbebida = $row['idbebida'];
        $this->idBebida = $row['idbebida'];
        $this->nome = $row['nome'];
        $this->tipo = $row['tipo'];
        $this->valor = $row['valor'];

        return true;
    }
 
    public function find($id)
    {
        $query = "SELECT idBebida AS idbebida, nome, tipo, valor FROM " . $this->tabela . " WHERE idBebida = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
 
    public function create()
    {
        $query = "INSERT INTO " . $this->tabela . " (nome, tipo, valor) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $success = $stmt->execute(array($this->nome, $this->tipo, $this->valor));

        if ($success) {
            $this->idbebida = $this->conn->lastInsertId();
            $this->idBebida = $this->idbebida;
        }

        return $success;
    }
 
    public function update()
    {
        $id = $this->getIdValue();
        if (!$id) {
            return false;
        }

        $query = "UPDATE " . $this->tabela . " SET nome = ?, tipo = ?, valor = ? WHERE idBebida = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(array($this->nome, $this->tipo, $this->valor, $id));
    }
 
    public function delete() {
        // Query de exclusão
        $query = 'DELETE FROM ' . $this->tabela . ' WHERE idBebida=:id';
 
        // Preparar a query
        $stmt = $this->conn->prepare($query);
 
        // Vincular o ID
        $stmt->bindParam(':id', $this->idBebida);
 
        // Executar a query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
 
}
