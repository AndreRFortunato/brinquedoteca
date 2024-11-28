<?php
include('code/conexao.php');

if(isset($_POST['id_funcionario']) || isset($_POST['senha'])) {

    if(strlen($_POST['id_funcionario']) == 0) {
        echo "<script>alert('Preencha seu código de Usuário');</script>";
    } else if(strlen($_POST['senha']) == 0) {
        echo "<script>alert('Preencha sua senha');</script>";
    } else {

        $username = $mysqli->real_escape_string($_POST['id_funcionario']);
        $password = $mysqli->real_escape_string($_POST['senha']);

        $sql_code = "SELECT * FROM Funcionario WHERE id_funcionario = '$username' AND senha = '$password'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if($quantidade == 1) {
            
            $user = $sql_query->fetch_assoc();

            if(!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['id_funcionario'] = $user['id_funcionario'];
            $_SESSION['nome'] = $user['nome'];

            header("Location: index.php");

        } else {
            echo "<script>alert('Falha ao logar! Código de usuário ou senha incorretos');</script>";
        }
    }  
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="code/style.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form action="" method="POST">
            <div class="RA">
                <input type="text" name="id_funcionario"  placeholder="Usuário">
            </div>

            <div class="password">
                <input type="password" name="senha"  placeholder="Senha">
            </div>
            <input type="submit" value="Acessar">
        </form>
        <a>
            <div class="logo">
            <a href="https://www.einsteinlimeira.com.br/portal/public/"> 
                <input type="image" src="img/einstein.png"> 
            </a>
        </a>
    </div>  
</body>
</html>