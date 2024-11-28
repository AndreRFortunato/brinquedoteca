<?php 
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');
?>
<div class="box_pesquisa">
    <div class="prow">
	<h2>Pesquisar</h2> 
        <div class="col-md-6 col-md-offset-3">
            <div class="pbox">
				<i class="glyphicon glyphicon-plus"></i>
                <form action="" method="POST" id="form-pesquisa">
                    <select name="tabela" id="tabela" class="form-control">
                        <?php
                        $sql = "SELECT table_name
						FROM information_schema.tables
						WHERE table_schema = 'banco-php'
						  AND table_name NOT IN ('Brinquedo', 'Tipo', 'AreaDesenvolvimento', 'Status', 'Exemplar', 'Entrada', 'Saida', 'Emprestimo')";
                        $result = $mysqli->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['table_name'] . "'>" . $row['table_name'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>Nenhum dado encontrado</option>";
                        }
                        ?>
                    </select>
                    <br>
                    <select name="colunas" id="colunas" class="form-control">
                        <!-- Opções de colunas serão adicionadas dinamicamente via JavaScript -->
                    </select>
                    <br>
                    <label for="pesquisa">Pesquisa</label> 
                    <input type="text" name="valor-pesquisa" id="valor-pesquisa" class="form-control"><br>
                    <input type="submit" name="Search" class="btn btn-success" value="Pesquisar">
                </form>
            </div>
        </div>
    </div>
</div>

<div id="resultado-pesquisa"></div>

<script type="text/javascript">
    // Função para atualizar as opções de colunas com base na tabela selecionada
    function updateColumns() {
        var tabelaSelecionada = document.getElementById("tabela").value;
        var selectColunas = document.getElementById("colunas");

        // Limpa as opções atuais
        selectColunas.innerHTML = "";

        // Consulta as colunas via requisição AJAX
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var colunas = JSON.parse(xhr.responseText);

                    // Cria as opções de colunas
                    for (var i = 0; i < colunas.length; i++) {
                        var option = document.createElement("option");
                        option.value = colunas[i];
                        option.text = colunas[i];
                        selectColunas.appendChild(option);
                    }
                } else {
                    console.error("Erro na requisição: " + xhr.status);
                }
            }
        };
        xhr.open("GET", "code/get_columns.php?tabela=" + tabelaSelecionada, true);
        xhr.send();
    }

    // Evento que dispara quando a tabela selecionada é alterada
    document.getElementById("tabela").addEventListener("change", updateColumns);

    // Atualiza as opções de colunas quando a página é carregada
    updateColumns();

    // Submissão do formulário de pesquisa
    document.getElementById("form-pesquisa").addEventListener("submit", function(event) {
        event.preventDefault(); // Impede o envio do formulário

        var tabelaSelecionada = document.getElementById("tabela").value;
        var colunaSelecionada = document.getElementById("colunas").value;
        var valorPesquisa = document.getElementById("valor-pesquisa").value;

        // Realiza a pesquisa via requisição AJAX
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var resultado = xhr.responseText;
                    document.getElementById("resultado-pesquisa").innerHTML = resultado;
                } else {
                    console.error("Erro na requisição: " + xhr.status);
                }
            }
        };
        xhr.open("GET", "code/pesquisa_users.php?tabela=" + tabelaSelecionada + "&coluna=" + colunaSelecionada + "&valor=" + valorPesquisa, true);
        xhr.send();
    });
</script>

<div class='container'>
	<?php
	if (isset($_POST['delete_func'])) {
		$funcionarioId = $_POST['funcionarioid'];

		// Consulta para obter os id_emprestimo relacionados ao funcionário
		$sqlGetEmprestimos = "SELECT id_emprestimo FROM Emprestimo WHERE fk_funcionario = $funcionarioId";
		$resultGetEmprestimos = $mysqli->query($sqlGetEmprestimos);

		if ($resultGetEmprestimos->num_rows > 0) {
			header("Location: ../users.php");
			echo "<script> alert('O Funcionário não pode ser excluído por que existe empréstimos relacionados.'); </script>";
			exit;
			// // Exclua os id_emprestimo relacionados ao funcionário
			// while ($row = $resultGetEmprestimos->fetch_assoc()) {
			// 	$emprestimoId = $row['id_emprestimo'];
			// 	$sqlDeleteEmprestimo = "DELETE FROM Emprestimo WHERE id_emprestimo = $emprestimoId";
			// 	$mysqli->query($sqlDeleteEmprestimo);
			// }
		}

		// Exclua o funcionário
		$sqlDeleteFuncionario = "DELETE FROM Funcionario WHERE id_funcionario = $funcionarioId";
		if ($mysqli->query($sqlDeleteFuncionario) === TRUE) {
			echo "<script> alert('Foi excluído com sucesso'); </script>";
		} else {
			echo "<div class='alert alert-danger'>Erro ao excluir usuário.</div>";
		}
	}

	$sql 	= "SELECT * FROM Funcionario";
	$result = $mysqli->query($sql); 

	if( $result->num_rows > 0)
	{ 
		?>
		<br>
		<h2>Lista de Funcionários</h2>
		<br>
		<table class="table table-bordered">
			<tr>
				<th>Códido do Funcionário</th>
				<th>Nome</th>
				<th>Senha</th>
				<th>CPF</th>
				<th width="70px">Excluir</th>
				<th width="70px">Editar</th>
			</tr>
		<?php
		while( $row = $result->fetch_assoc()){ 
			echo "<form action='' method='POST'>";
			echo "<input type='hidden' value='". $row['id_funcionario']."' name='funcionarioid' />";
			echo "<tr>";
			echo "<td>".$row['id_funcionario'] . "</td>";
			echo "<td>".$row['nome'] . "</td>";
			echo "<td>".$row['senha'] . "</td>";
			echo "<td>".$row['cpf'] . "</td>";
			echo "<td><input type='submit' name='delete_func' value='Excluir' class='btn btn-danger' /></td>";  
			echo "<td><a href='edit_func.php?id=".$row['id_funcionario']."' class='btn btn-info'>Editar</a></td>";
			echo "</tr>";
			echo "</form>";
		}
		?>
		</table>
	<?php 
	}
	if (isset($_POST['delete_aluno'])) {
		$alunoid = $_POST['alunoid'];

		// Consulta para obter os id_emprestimo relacionados ao aluno
		$sqlGetEmprestimos = "SELECT id_emprestimo FROM Emprestimo WHERE fk_aluno = $alunoid";
		$resultGetEmprestimos = $mysqli->query($sqlGetEmprestimos);

		if ($resultGetEmprestimos->num_rows > 0) {
			header("Location: ../users.php");
			echo "<script> alert('O Aluno não pode ser excluído por que existe empréstimos relacionados.'); </script>";
			exit;
			// Exclua os id_emprestimo relacionados ao aluno
			// while ($row = $resultGetEmprestimos->fetch_assoc()) {
			// 	$emprestimoId = $row['id_emprestimo'];
			// 	$sqlDeleteEmprestimo = "DELETE FROM Emprestimo WHERE id_emprestimo = $emprestimoId";
			// 	$mysqli->query($sqlDeleteEmprestimo);
			// }
		}

		// Exclua o aluno
		$sqlDeleteAluno = "DELETE FROM Aluno WHERE id_aluno = $alunoid";
		if ($mysqli->query($sqlDeleteAluno) === TRUE) {
			echo "<script> alert('Foi excluído com sucesso'); </script>";
		} else {
			echo "<div class='alert alert-danger'>Erro ao excluir usuário.</div>";
		}
	}

	$sql 	= "SELECT * FROM Aluno";
	$result = $mysqli->query($sql); 

	if( $result->num_rows > 0)
	{ 
		?>
		<h2>Lista de Alunos</h2>
		<br>
		<table class="table table-bordered">
			<tr>
				<th>Código do Aluno</th>
				<th>Nome</th>
				<th>RA</th>
				<th>Turma</th>
				<th width="70px">Excluir</th>
				<th width="70px">Editar</th>
			</tr>
		<?php
		while( $row = $result->fetch_assoc()){ 
			echo "<form action='' method='POST'>";
			echo "<input type='hidden' value='". $row['id_aluno']."' name='alunoid' />";
			echo "<tr>";
			echo "<td>".$row['id_aluno'] . "</td>";
			echo "<td>".$row['nome'] . "</td>";
			echo "<td>".$row['ra'] . "</td>";
			echo "<td>".$row['turma'] . "</td>";
			echo "<td><input type='submit' name='delete_aluno' value='Excluir' class='btn btn-danger' /></td>";  
			echo "<td><a href='edit_aluno.php?id=".$row['id_aluno']."' class='btn btn-info'>Editar</a></td>";
			echo "</tr>";
			echo "</form>";
		}
		?>
		</table>
	<?php 
	}

	if (isset($_POST['delete_forn'])) {
		$fornecedorid = $_POST['fornecedorid'];

		// Verificar se existem registros relacionados na tabela "entrada"
		$sqlVerificarEntrada = "SELECT * FROM entrada WHERE fk_fornecedor = $fornecedorid";
		$resultVerificarEntrada = $mysqli->query($sqlVerificarEntrada);

		// //disabilita foreign_key_checks
		// $disableForeignKey = "SET foreign_key_checks = 0";
		// $mysqli->query($disableForeignKey);

		// if ($resultVerificarEntrada->num_rows > 0) {
		// 	// Atualizar o campo fk_exemplar para um valor nulo
		// 	$sqlUpdateEntrada = "UPDATE entrada SET fk_fornecedor = NULL WHERE fk_fornecedor = $fornecedorid";
		// 	if ($mysqli->query($sqlUpdateEntrada) !== TRUE) {
		// 		echo "<div class='alert alert-danger'>Erro ao atualizar registros de entrada.</div>";
		// 		exit;
		// 	}
		// }

		if ($resultVerificarEntrada->num_rows > 0) {
			header("Location: ../users.php");
			echo "<script> alert('O Fornecedor não pode ser excluído por que existe entradas relacionados.'); </script>";
			exit;
		} elseif($resultVerificarEntrada->num_rows = 0){
			$sql = "DELETE FROM Fornecedor WHERE id_fornecedor=" . $_POST['fornecedorid'];
			if($mysqli->query($sql) === TRUE){
			echo "<script> alert('Foi excluído com sucesso'); </script>";
			} else {
			echo "<div class='alert alert-danger'>Erro ao excluir usuário.</div>";
			}
		}
	}

	$sql 	= "SELECT * FROM Fornecedor";
	$result = $mysqli->query($sql); 

	if( $result->num_rows > 0)
	{ 
		?>
		<h2>Lista de Fornecedores</h2>
		<br>
		<table class="table table-bordered">
			<tr>
				<th>Código do Fornecedor</th>
				<th>Nome</th>
				<th>Documento</th>
				<th width="70px">Excluir</th>
				<th width="70px">Editar</th>
			</tr>
		<?php
		while( $row = $result->fetch_assoc()){ 
			echo "<form action='' method='POST'>";
			echo "<input type='hidden' value='". $row['id_fornecedor']."' name='fornecedorid' />";
			echo "<tr>";
			echo "<td>".$row['id_fornecedor'] . "</td>";
			echo "<td>".$row['nome'] . "</td>";
			echo "<td>".$row['documento'] . "</td>";
			echo "<td><input type='submit' name='delete_forn' value='Excluir' class='btn btn-danger' /></td>";  
			echo "<td><a href='edit_forn.php?id=".$row['id_fornecedor']."' class='btn btn-info'>Editar</a></td>";
			echo "</tr>";
			echo "</form>";
		}
		?>
		</table>
	<?php 
	}

	else
	{
		// echo "<br><br>No Record Found";
	}
	?> 
</div>

<?php 

 require_once 'code/footer.php';