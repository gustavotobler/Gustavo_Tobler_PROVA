<?php
session_start();
require_once 'conexao.php';
require 'menu_nav.php';

// Verifica se o usuário tem permissão supondo que o perfil 1 sejá o admin
if($_SESSION['perfil'] != 1){
    echo "Acesso negado!";
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome_func = $_POST['nome_funcionario'];  
    $endereco = $_POST['endereco'];  
    $telefone = $_POST['telefone'];
    $email_func = $_POST['email'];  

    $sql = "INSERT INTO funcionario(nome_funcionario,endereco,telefone,email)
            VALUES (:nome_funcionario,:endereco,:telefone,:email)";//Está variável $sql está guardando um INSERT. Este INSERT é uma das partes que fará com que os dados sejam inseridos no banco
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindParam(":nome_funcionario",$nome_func);
    $stmt -> bindParam(":endereco",$endereco);
    $stmt -> bindParam(":telefone",$telefone);
    $stmt -> bindParam(":email",$email_func);

    if($stmt -> execute()){
        echo "<script>alert('Funcionário cadastrado com sucesso!');</script>";
    } else{
        echo "<script>alert('Erro ao cadastrar funcionário');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"/>
    <title>Cadastrar funcionário</title>
    <style>
          img{
            max-width:45px;
        }
    </style>
</head>
<body>
     
     <h2>Cadastrar funcionário</h2>
    <!--Este formulário será utilizado para inserir os dados de cadastro, que posteriormente serão direcionados ao banco "senai_login"-->
     <form action="cadastro_funcionario.php" method="POST" onsubmit="return validar()">
        <label for="nome">Nome: </label>
        <input type="text" id="nome" name="nome_funcionario" required placeholder="Nome Sobrenome"/>

        <label for="endereco">Endereco: </label>
        <input type="text" id="endereco" name="endereco" required placeholder="rua 25 de março"/>

        <label for="telefone">Telefone: </label>
        <input type="text" id="telefone" name="telefone" required maxlength=15 placeholder="(99) 99999-9999"/>

        <label for="email">E-mail: </label>
        <input type="email" id="email" name="email" required placeholder="user@gmail.com"/>

        <button type="submit" style="background-color:#39FF1F;">Salvar</button>
        <button type="reset"  style="background-color:#FF311F;">Cancelar</button>
     </form>
     
     <a href="principal.php"><img src="img/voltar.png"></a>

    <!--Código Java Script que fará com que campos como telefone e nome sejam validados devidamente-->
     <script>
          const tel = document.getElementById("telefone");
          tel.oninput = e => {
    e.target.value = e.target.value
        .replace(/\D/g,"")
        .replace(/^(\d{2})(\d)/,"($1) $2")
        .replace(/(\d{4,5})(\d{4}).*/,"$1-$2");
};
function validar() {
  const nome = document.getElementById('nome').value.trim();
  const nomeRegex = /^[A-Za-zÀ-ú\s]+$/;

  if (!nomeRegex.test(nome)) {
    alert('Nome inválido! Use apenas letras e espaços.');
    return false;
  }
  return true; // tudo ok, envia form
}

</script>
<center>
        <adress>
            Gustavo Tobler - Técnico de desenvolvimento de sistemas
        </adress>
    </center>
</body>
</html>