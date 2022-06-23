<?php

class Config
{
    //apenas dentro da class
    private $pdo;


    //conexao com banco de dados
    public function __construct($dbname, $host, $user, $senha, $options)
    {
        try {

            $this->pdo = new PDO("mysql:dbname=" . $dbname . ";host=" . $host, $user, $senha);
        } catch (PDOException $e) {
            echo "Erro com banco de dados" . $e->getMessage();
            exit();
        } catch (Exception $e) {
            echo "Erro comum" . $e->getMessage();
            exit();
        }
    }


    public function buscarDados()
    {
        $res = array();
        $cmd = $this->pdo->query("SELECT * FROM pessoa ORDER BY nome desc");
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);

        return $res;
    }


    public function verication_email($email)
    {
        $cmd = $this->pdo->prepare("SELECT * FROM pessoa WHERE email =:e ");
        $cmd->bindValue(":e", $email);
        $cmd->execute();
        if ($cmd->rowCount() === 0) {
            return true;
        }
    }

    public function cadastrar($nome, $telefone, $email)
    {
        $cmd = $this->pdo->prepare("INSERT INTO pessoa (nome,telefone,email) VALUES (:n,:t,:e)");
        $cmd->bindValue(":n", $nome);
        $cmd->bindValue(":t", $telefone);
        $cmd->bindValue(":e", $email);
        $cmd->execute();
        return true;
    }


    public function excluir($id)
    {
        $cmd = $this->pdo->prepare("DELETE FROM pessoa where id=:id");
        $cmd->bindValue(":id", $id);
        $cmd->execute();
    }


    /////

    public function select_one($id)
    {
        $res = array();
        $cmd = $this->pdo->prepare("SELECT * FROM  pessoa where id=:id");
        $cmd->bindValue(":id", $id);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    public function update_date($id, $nome, $telefone, $email)
    {
        $cmd = $this->pdo->prepare("UPDATE pessoa SET nome=:n,telefone=:t,email=:e 
        WHERE id=:id");
        $cmd->bindValue(":id", $id);
        $cmd->bindValue(":n", $nome);
        $cmd->bindValue(":t", $telefone);
        $cmd->bindValue(":e", $email);
        $cmd->execute();
    }
}
