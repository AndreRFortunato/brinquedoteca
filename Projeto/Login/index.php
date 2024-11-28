<?php 
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>
</head>
<body>
	<div class="box_pesquisa">
	</div>	
	<div class="container">
		<div class="jumbotron">
		<h1>Bem vindo <?php echo $_SESSION['nome']; ?></h1>
		<p>
			
		</p>
	</div>
</body>
</html>
<?php 

 require_once 'code/footer.php';