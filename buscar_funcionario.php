<?php 
session_start();
require_once 'conexao.php';
require 'menu_nav.php';

//VERIFICA SE O USUÁRIO TEM PERMISSÃO DE adm OU secretária
if($_SESSION['perfil'] !=1 && $_SESSION['perfil']!=2){
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

$funcionarios = []; //INICIALIZA A VARIÁVEL PARA EVITAR ERROS

//SE O FORMULÁRIO FOR ENVIADO, BUSCA O USUÁRIO POR ID OU NOME
if($_SERVER["REQUEST_METHOD"]=="POST" && !empty($_POST['busca'])){
    $busca = trim($_POST['busca']);
    
    //VERIFICA SE A BUSCA É UM número OU nome
    if(is_numeric($busca)){
        $sql="SELECT * FROM funcionario WHERE id_funcionario = :busca ORDER BY nome_funcionario ASC";//Busca por o funcionário por ID
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':busca',$busca, PDO::PARAM_INT);
    }else{
        $sql="SELECT * FROM funcionario WHERE nome_funcionario LIKE :busca_nome ORDER BY nome_funcionario ASC";//Busca o funcionário por nome
        $stmt=$pdo->prepare($sql);
        $stmt->bindValue(':busca_nome',"$busca%",PDO::PARAM_STR);
    }
    }else{
        $sql = "SELECT * FROM funcionario order by nome_funcionario ASC";
       $stmt = $pdo->prepare($sql);

    }
    $stmt->execute();
    $funcionarios = $stmt->fetchALL(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Funcionários</title>
    <link rel="stylesheet" href="styles.css"/>
    
    <style>
    table {
        border-collapse: collapse;
        width: 100%;
        max-width: 800px;
        margin-top: 20px;
        font-family: Arial, sans-serif;
        border-radius: 3px;
    }
    th, td {
        border: 1px solid #333;
        padding: 8px 12px;
        text-align: left;
    }
    th {
        background-color:rgb(3, 128, 245);
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    tr:hover {
        background-color: #ddd;
    }
        img{
            max-width:45px;
        }
   
</style>

  
</head>
<body>
    <h2>Lista de Funcionários</h2>
    <form action="buscar_funcionario.php" method="POST">
        <label for="busca">Digite o ID ou NOME(opcional): </label>
        <input type="text" id="busca" name="busca" required><!--Campo onde o usuário irá buscar o funcionário, digitando por ID ou pelo próprio nome.-->

        <button type="submit">Pesquisar</button>
    </form>
    <?php if(!empty($funcionarios)): ?>
        <table border="1" align="center">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
            <?php foreach($funcionarios as $funcionario): ?>
                <tr>
                <td><?=htmlspecialchars($funcionario['id_funcionario'])?></td>
                <td><?=htmlspecialchars($funcionario['nome_funcionario'])?></td>
                <td><?=htmlspecialchars($funcionario['endereco'])?></td>
                <td><?=htmlspecialchars($funcionario['telefone'])?></td>   
                <td><?=htmlspecialchars($funcionario['email'])?></td>
                <td>
                    <a href="alterar_funcionario.php?id=<?=htmlspecialchars($funcionario['id_funcionario'])?>"><button  style="background-color:#39FF1F;">Alterar</button></a>

                    <a href="excluir_funcionario.php?id=<?=htmlspecialchars($funcionario['id_funcionario'])?>"onclick="return confirm('Tem certeza que deseja excluir este funcionário?')"><button style="background-color:#FF311F;">Excluir</button></a>
                </td>
            </tr>
        <?php endforeach;?>
        </table>
        <?php else:?>
            <p>Nenhum funcionário encontrado.</p>
        <?php endif;?>
            <br>
        <a href="principal.php">
    <img src="img/voltar.png">
    </a>
    <br>
    <center>
        <adress>
            Gustavo Tobler - Técnico de desenvolvimento de sistemas
        </adress>
    </center>
</body>
</html>