<?php
session_start();
require_once 'conexao.php';
require 'menu_nav.php';
//VERIFICA SE O USUÁRIO TEM PERMISSÃO DE ADM
if($_SESSION['perfil'] !=1){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
exit();
}
//INICIALIZA VARIAVEIS
$funcionario = null;

if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(!empty($_POST['busca_funcionario'])){
        $busca = trim($_POST['busca_funcionario']);

        //VERIFICA SE A BUSCA É UM NÚMERO (ID) OU UM NOME
        if(is_numeric($busca)){
            $sql = "SELECT * FROM funcionario WHERE id_funcionario =:busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca',$busca,PDO::PARAM_INT);
        }else{
            $sql = "SELECT * FROM funcionario WHERE nome_funcionario LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome',"$busca%",PDO::PARAM_STR);
        }
        $stmt->execute();
        $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

        //SE O FUNCIONÁRIO NÃO FOR ENCONTRADO, EXIBE UM ALERTA
        if(!$funcionario){
            echo "<script>alert('Funcionário não encontrado!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Funcionário</title>
    <link rel="stylesheet" href="styles.css"/>
    <style>
        img{
            max-width:45px;
        }
    </style>
</head>
<body>
    <h2>Alterar Funcionário</h2>

    <form action="alterar_funcionario.php" method="POST">
        <label for="busca_funcionario">Digite o ID ou o nome do funcionário: </label>
        <input type="text" id="buscar_funcionario" name="busca_funcionario" required onkeyup="buscarSugestoes()"><!--Campo de busca onde será digitado o ID ou nome do Funcionário-->

    <!--DIV PARA EXIBIR SUGESTÕES DE FUNCIONÁRIOS-->
    <div id="sugestoes"></div>
    <button type="submit">Pesquisar</button>

    </form>
    <?php if($funcionario): ?>
        <!--FORMULÁRIO PARA ALTERAR FUNCIONÁRIOS-->
        <form action="processar_alteracao_funcionario.php" method="POST" onsubmit="return validarAlteracao()">
            <input type="hidden" name="id_funcionario" value="<?=htmlspecialchars($funcionario['id_funcionario'])?>">

            <label for="nome">Nome: </label>
            <input type="text" name="nome_funcionario" id="nome" value="<?=htmlspecialchars($funcionario['nome_funcionario'])?>" required placeholder="Nome Sobrenome"> 

            <label for="endereco">Endereço: </label>
            <input type="text" name="endereco" id="endereco" value="<?=htmlspecialchars($funcionario['endereco'])?>" required  placeholder="rua 25 de março"> 

            <label for="telefone">Telefone: </label>
            <input type="tel" name="telefone" id="telefone" value="<?=htmlspecialchars($funcionario['telefone'])?>" required maxlength="15" placeholder="(99) 99999-9999"> 

            <label for="email">Email: </label>
            <input type="email" name="email" id="email" value="<?=htmlspecialchars($funcionario['email'])?>" required placeholder="user@gmail.com">       
    
            
                <button type="submit">Alterar</button>
                <button type="reset">Cancelar</button>
        </form>
    <?php endif;?>
    <br>
    <a href="principal.php">
    <img src="img/voltar.png">
    </a>
    </form>
    <br>
<script>
           const tel = document.getElementById("telefone");
          tel.oninput = e => {
    e.target.value = e.target.value
        .replace(/\D/g,"")
        .replace(/^(\d{2})(\d)/,"($1) $2")
        .replace(/(\d{4,5})(\d{4}).*/,"$1-$2");
};
function validarAlteracao() {
    const nome = document.getElementById('nome').value.trim();

    const nomeRegex = /^[A-Za-zÁ-ÉÍ-ÓÚá-éí-óúÂ-Ûâ-ûÃ-Õã-õÇç\s]+$/;

    if (nome.length < 3) {
        alert('O nome deve ter pelo menos 3 caracteres.');
        return false;
    }

    if (!nomeRegex.test(nome)) {
        alert('Nome inválido! Use apenas letras e espaços.');
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