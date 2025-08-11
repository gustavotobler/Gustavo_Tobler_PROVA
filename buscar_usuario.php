<?php 
session_start();
require_once 'conexao.php';

//VERIFICA SE O USUÁRIO TEM PERMISSÃO DE adm OU secretária
if($_SESSION['perfil'] !=1 && $_SESSION['perfil']!=2){
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

$usuario = []; //INICIALIZA A VARIÁVEL PARA EVITAR ERROS

//SE O FORMULÁRIO FOR ENVIADO, BUSCA O USUÁRIO POR ID OU NOME
if($_SERVER["REQUEST_METHOD"]=="POST" && !empty($_POST['buscar'])){
    $busca = trim($_POST['busca']);
    
    //VERIFICA SE A BUSCA É UM número OU nome
    if(is_numeric($busca)){
        $sql="SELECT * FROM usuario WHERE id_usuario = :busca ORDER BY nome ASC";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':busca',$busca, PDO::PARAM_INT);
    }
    if(is_numeric($busca)){
        $sql="SELECT * FROM usuario WHERE nome LIKE :busca_nome ORDER BY nome ASC";
        $stmt=$pdo->prepare($sql);
        $stmt->bindValue(':busca_nome',"%$busca%",PDO::PARAM_STR);
    }
    }else{
        $sql = "SELECT * FROM usuario order by nome ASC";
        $usuarios = $stmt->fetchALL(PDO::FETCH_ASSOC);



}

?>