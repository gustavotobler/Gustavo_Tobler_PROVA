<?php 
session_start();
require_once 'conexao.php';

if($_SERVER['REQUEST_METHOD']=="POST"){
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email',$email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if($usuario && password_verify($senha,$usuario['senha'])){
        //LOGIN BEM SUCEDIDO DEFINE VARIÁVEIS DE SESSÃO
        $_SESSION['usuario']=$usuario['nome'];
        $_SESSION['perfil']=$usuario['id_perfil'];
        $_SESSION['id_usuario']=$usuario['id_usuario'];

        //VERIFICA SE A SENHA É TEMPORÁRIA
        if($usuario['senha_temporaria']){
            //REDIRECIONA PARA A TROCA DE SENHA
            header("Location: alterar_senha.php");
            exit();
        }else{
            //REDIRECIONA PARA A PÁGINA PRINCIPAL
            header("Location: principal.php");
            exit();
        }
    }else{
        //LOGIN INVÁLIDO
        echo "<script>alert('E-mail ou senha incorretos');window.location.href='index.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"/>

</head>
<body>
    <h2>Login</h2>
    <form action="index.php" method="POST" onsubmit="return validarLogin()">
        <label for="email">E-mail:</label> 
        <input type="email" id="email" name="email" required> 

        <label for="senha">Senha:</label> 
        <input type="password" id="senha" name="senha" required>  
        
        <button type="submit">Entrar</button>
    </form>

    <p><a href="recuperar_senha.php">Esqueci a minha senha</a></p>
    <br>
    <script>
function validarLogin() {
   
    const senha = document.getElementById('senha').value.trim();
    

    if (senha.length < 6) {
        alert("A senha deve ter pelo menos 6 caracteres.");
        return false;
    }

    return true;
}
</script>

    <center>
        <adress>
            Gustavo Tobler - Técnico de desenvolvimento de sistemas
        </adress>
    </center>
</body>
</html>