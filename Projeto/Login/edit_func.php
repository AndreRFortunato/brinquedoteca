<?php 
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');

function validarCPF($cpf) {
    // Remover caracteres não numéricos
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);

    // Verifica se o CPF possui 11 dígitos
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se todos os dígitos são iguais, o que é inválido
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Validação dos dígitos verificadores
    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
        $soma += intval($cpf[$i]) * (10 - $i);
    }

    $resto = $soma % 11;
    $digito1 = ($resto < 2) ? 0 : 11 - $resto;

    if ($cpf[9] != $digito1) {
        return false;
    }

    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
        $soma += intval($cpf[$i]) * (11 - $i);
    }

    $resto = $soma % 11;
    $digito2 = ($resto < 2) ? 0 : 11 - $resto;

    if ($cpf[10] != $digito2) {
        return false;
    }

    return true;
}

?>
<div class="container">
	<?php 
	
	if(isset($_POST['update'])){
		if( empty($_POST['nome']) || empty($_POST['senha']) || empty($_POST['cpf']) ) {
			echo "<script>alert('Preencha os campos vazios');</script>";
		} else {
			$nome  = $_POST['nome'];
			$senha 	= $_POST['senha'];
			$cpf  	= $_POST['cpf'];
	
			// Verificar se o CPF já existe na tabela Funcionario, exceto para o próprio funcionário sendo alterado
			$query = "SELECT COUNT(*) FROM Funcionario WHERE (cpf = '$cpf' AND id_funcionario <> ".$_POST['userid'].")";
			$result = $mysqli->query($query);
			$count = $result->fetch_row()[0];
	
			if ($count > 0) {
				echo "<script>alert('CPF já cadastrado para outro funcionário');</script>";
			} else {
				if (!validarCPF($cpf)) {
					echo "<script>alert('CPF inválido');</script>";
				} else {
					$sql = "UPDATE Funcionario SET nome='{$nome}',
					senha = '{$senha}', cpf = '{$cpf}' 
					WHERE id_funcionario=" . $_POST['userid'];
	
					if ($mysqli->query($sql) === TRUE) {
						echo "<script>alert('Funcionário alterado com sucesso');</script>";
					} else {
						echo "<div class='alert alert-danger'>Error: There was an error while adding new user</div>";
					}
				}
			}
		}
	}
	$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	$sql = "SELECT * FROM Funcionario WHERE id_funcionario={$id}";
	$result = $mysqli->query($sql);

	if($result->num_rows < 1){
		header('Location: index.php');
		exit;
	}
	$row = $result->fetch_assoc();
	?>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="box">
				<h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Alterar Usuários</h3> 
				<form action="" method="POST" onsubmit="return validarFormulario()">
					<input type="hidden" value="<?php echo $row['id_funcionario']; ?>" name="userid">
					<label for="nome">Nome</label>
					<input type="text" id="nome"  name="nome" value="<?php echo $row['nome']; ?>" class="form-control"><br>
					<label for="senha">Senha</label>
					<input type="text" name="senha" id="senha" value="<?php echo $row['senha']; ?>" class="form-control"><br>
					<label for="cpf">CPF</label> 
					<input type="text"  name="cpf" id="cpf"  value="<?php echo $row['cpf']; ?>" class="form-control"><br>
					<br>
					<input type="submit" name="update" class="btn btn-success" value="Alterar">
					<a href="index.php" class="btn btn-danger">Cancelar</a>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
function validarFormulario() {
    var nome = document.getElementById('nome').value;
    var cpf = document.getElementById('cpf').value;
	var senha = document.getElementById('senha').value;

    if (nome === '') {
        alert('Preencha o campo Nome');
        return false;
    }

    if (cpf === '') {
        alert('Preencha o campo CPF');
        return false;
    }

	if (senha === '') {
        alert('Preencha o campo Senha');
        return false;
    }

	if (!validarCPF(cpf)) {
        alert('CPF inválido');
        return false;
    }

    return true;
}
</script>

<?php 
require_once 'code/footer.php';
?>