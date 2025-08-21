<?php
session_start();
require_once 'conexao.php';

if($_SESSION['perfil']!=1){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php'</script>";
    exit();
}
if($_SERVER['REQUEST_METHOD']=="POST"){
    $id_funcionario = $_POST['id_funcionario'];
    $nome_func = $_POST['nome_funcionario'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email_func = $_POST['email'];
    //ATUALIZA OS DADOS DO USUARIO
    $sql = "UPDATE funcionario SET nome_funcionario=:nome_funcionario,endereco=:endereco,telefone=:telefone,email=:email WHERE id_funcionario =:id";
    $stmt = $pdo->prepare($sql);
  
    $stmt->bindParam(':nome_funcionario',$nome_func);
    $stmt->bindParam(':endereco',$endereco);
    $stmt->bindParam(':telefone',$telefone);
    $stmt->bindParam(':email',$email_func);
    $stmt->bindParam(':id',$id_funcionario);

    if($stmt->execute()){
        echo"<script>alert('Funcionário atualizado com sucesso!');window.location.href='buscar_funcionario.php';</script>";
    }else{
        echo"<script>alert('Erro ao atualizar funcionário!');window.location.href='alterar_funcionario.php?id=$id_funcionario';</script>";
    }
}

?>