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
						  AND table_name NOT IN ('Fornecedor', 'Aluno', 'Funcionario', 'Emprestimo')";
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
        xhr.open("GET", "code/pesquisa_brinq.php?tabela=" + tabelaSelecionada + "&coluna=" + colunaSelecionada + "&valor=" + valorPesquisa, true);
        xhr.send();
    });
</script>

<div class='container'>
	<?php
	if (isset($_POST['delete'])) {
		$exemplarId = $_POST['exemplarid'];

		// Verificar se existem registros relacionados na tabela "emprestimo"
		$sqlVerificarEmprestimo = "SELECT * FROM emprestimo WHERE fk_exemplar = $exemplarId";
		$resultVerificarEmprestimo = $mysqli->query($sqlVerificarEmprestimo);

		if ($resultVerificarEmprestimo->num_rows > 0) {
			// Excluir os registros da tabela "emprestimo" relacionados ao exemplar
			$sqlDeleteEmprestimo = "DELETE FROM emprestimo WHERE fk_exemplar = $exemplarId";
			if ($mysqli->query($sqlDeleteEmprestimo) !== TRUE) {
				echo "<div class='alert alert-danger'>Erro ao excluir registros de empréstimo.</div>";
				exit;
			}
		}

		// Verificar se existem registros relacionados na tabela "entrada"
		$sqlVerificarEntrada = "SELECT * FROM entrada WHERE fk_exemplar = $exemplarId";
		$resultVerificarEntrada = $mysqli->query($sqlVerificarEntrada);

		if ($resultVerificarEntrada->num_rows > 0) {
			// Verificar se é o último fk_exemplar relacionado com id_entrada
			$sqlVerificarUltimoExemplar = "SELECT * FROM entrada WHERE id_entrada = (SELECT id_entrada FROM entrada WHERE fk_exemplar = $exemplarId) AND fk_exemplar <> $exemplarId";
			$resultVerificarUltimoExemplar = $mysqli->query($sqlVerificarUltimoExemplar);

			if ($resultVerificarUltimoExemplar->num_rows == 0) {
				// É o último fk_exemplar relacionado, excluir também o id_entrada
				$sqlDeleteEntrada = "DELETE FROM entrada WHERE fk_exemplar = $exemplarId";
				if ($mysqli->query($sqlDeleteEntrada) !== TRUE) {
					echo "<div class='alert alert-danger'>Erro ao excluir entrada.</div>";
					exit;
				}
			}
		}

		// Excluir o fk_exemplar da tabela "entrada"
		$sqlUpdateEntrada = "UPDATE entrada SET fk_exemplar = NULL WHERE fk_exemplar = $exemplarId";
		if ($mysqli->query($sqlUpdateEntrada) === TRUE) {
			echo "<script>alert('Exclusão do exemplar realizado com sucesso');</script>";
		} else {
			echo "<div class='alert alert-danger'>Erro ao excluir fk_exemplar.</div>";
		}
		
		// Excluir o exemplar da tabela "exemplar"
		$sqlDeleteExemplar = "DELETE FROM exemplar WHERE id_exemplar = $exemplarId";
		if ($mysqli->query($sqlDeleteExemplar) === TRUE) {
			echo "<script>alert('Exclusão do exemplar realizado com sucesso');</script>";
		} else {
			echo "<div class='alert alert-danger'>Erro ao excluir exemplar.</div>";
		}
	}

	$sql 	= "SELECT E.id_exemplar, B.id_brinquedo, B.fk_tipo, B.fk_areaDesenvolvimento, E.fk_status
	FROM Exemplar AS E
	INNER JOIN Brinquedo AS B on E.fk_brinquedo = B.id_brinquedo
	INNER JOIN Status AS S on E.fk_status = S.id_status
	WHERE NOT EXISTS (SELECT fk_exemplar FROM Saida WHERE E.id_exemplar = fk_exemplar)
	ORDER BY E.id_exemplar ASC";
	$result = $mysqli->query($sql); 

	$text1 = 'exemplar_status';
	$text2 = 'exemplar_status';
	$text3 = 'form-control';

	if ($result->num_rows > 0) { 
	?>
		<br>
		<h2>Brinquedos</h2>
		<br>
		<table class="table table-bordered">
			<tr>
				<th>Exemplar</th>
				<th>Nome</th>
				<th>Descrição</th>
				<th>Faixa Etária (Anos)</th>
				<th>Tipo</th>
				<th>Área de Desenvolvimento</th>
				<th>Status</th>
				<!-- <th width="70px">Excluir</th> -->
				<th width="70px">Editar</th>
			</tr>
		<?php
		while ($row = $result->fetch_assoc()) { 
			echo "<form action='' method='POST'>";
			echo "<input type='hidden' value='". $row['id_exemplar']."' name='exemplarid' />";
			echo "<tr>";
			echo "<td>".$row['id_exemplar'] . "</td>";

			echo "<input type='hidden' value='". $row['id_brinquedo']."' name='brinquedoid' />";
			$brinquedoId = $row['id_brinquedo'];
			$queryBrinquedo = "SELECT nome, descricao, faixaEtaria FROM Brinquedo WHERE id_brinquedo = $brinquedoId";
			$resultBrinquedo = $mysqli->query($queryBrinquedo);
			$rowBrinquedo = $resultBrinquedo->fetch_assoc();
			$nome = $rowBrinquedo['nome'];	
			$descricao = $rowBrinquedo['descricao'];
			$faixaEtaria = $rowBrinquedo['faixaEtaria'];
			echo "<td>" . $nome . "</td>";
			echo "<td>" . $descricao . "</td>";
			echo "<td>" . $faixaEtaria . "</td>";

			$tipoId = $row['fk_tipo'];
			$queryTipo = "SELECT descricao FROM Tipo WHERE id_tipo = $tipoId";
			$resultTipo = $mysqli->query($queryTipo);
			$rowTipo = $resultTipo->fetch_assoc();
			$tipoNome = $rowTipo['descricao'];		
			echo "<td>" . $tipoNome . "</td>";

			$ADId = $row['fk_areaDesenvolvimento'];
			$queryAD = "SELECT descricao FROM AreaDesenvolvimento WHERE id_areaDesenvolvimento = $ADId";
			$resultAD = $mysqli->query($queryAD);
			$rowAD = $resultAD->fetch_assoc();
			$ADNome = $rowAD['descricao'];
			echo "<td>" . $ADNome . "</td>";

			$statusId = $row['fk_status'];
			$queryStatus = "SELECT descricao FROM Status WHERE id_status = $statusId";
			$resultStatus = $mysqli->query($queryStatus);
			$rowStatus = $resultStatus->fetch_assoc();
			$statusNome = $rowStatus['descricao'];		
			echo "<td>" . $statusNome . "</td>";

			// echo "<td><input type='submit' name='delete' value='Excluir' class='btn btn-danger' /></td>";
			echo "<td><a href='edit_brinq.php?id=".$row['id_exemplar']."' class='btn btn-info'>Editar</a></td>";
			echo "</tr>";
			echo "</form>";
		}
	?>
		</table>
	<?php 
	} else {
		// echo "<br><br>No Record Found";
	}
	?> 

	<?php

	if (isset($_POST['delete_saida'])) {
		$exemplarId = $_POST['exemplarid'];

		// Verificar se existem registros relacionados na tabela "emprestimo"
		$sqlVerificarEmprestimo = "SELECT * FROM emprestimo WHERE fk_exemplar = $exemplarId";
		$resultVerificarEmprestimo = $mysqli->query($sqlVerificarEmprestimo);

		if ($resultVerificarEmprestimo->num_rows > 0) {
			// Excluir os registros da tabela "emprestimo" relacionados ao exemplar
			$sqlDeleteEmprestimo = "DELETE FROM emprestimo WHERE fk_exemplar = $exemplarId";
			if ($mysqli->query($sqlDeleteEmprestimo) !== TRUE) {
				echo "<div class='alert alert-danger'>Erro ao excluir registros de empréstimo.</div>";
				exit;
			}
		}

		// Verificar se existem registros relacionados na tabela "entrada"
		$sqlVerificarEntrada = "SELECT * FROM entrada WHERE fk_exemplar = $exemplarId";
		$resultVerificarEntrada = $mysqli->query($sqlVerificarEntrada);

		if ($resultVerificarEntrada->num_rows > 0) {
			// Verificar se é o último fk_exemplar relacionado com id_entrada
			$sqlVerificarUltimoExemplar = "SELECT * FROM entrada WHERE id_entrada = (SELECT id_entrada FROM entrada WHERE fk_exemplar = $exemplarId) AND fk_exemplar <> $exemplarId";
			$resultVerificarUltimoExemplar = $mysqli->query($sqlVerificarUltimoExemplar);

			if ($resultVerificarUltimoExemplar->num_rows == 0) {
				// É o último fk_exemplar relacionado, excluir também o id_entrada
				$sqlDeleteEntrada = "DELETE FROM entrada WHERE fk_exemplar = $exemplarId";
				if ($mysqli->query($sqlDeleteEntrada) !== TRUE) {
					echo "<div class='alert alert-danger'>Erro ao excluir entrada.</div>";
					exit;
				}
			}
		}

		// Excluir o fk_exemplar da tabela "entrada"
		$sqlUpdateEntrada = "UPDATE entrada SET fk_exemplar = NULL WHERE fk_exemplar = $exemplarId";
		if ($mysqli->query($sqlUpdateEntrada) === TRUE) {
			echo "<script>alert('Exclusão do exemplar realizado com sucesso');</script>";
		} else {
			echo "<div class='alert alert-danger'>Erro ao excluir fk_exemplar.</div>";
		}
		
		// Verificar se existem registros relacionados na tabela "Saida"
		$sqlVerificarSaida = "SELECT * FROM Saida WHERE fk_exemplar = $exemplarId";
		$resultVerificarSaida = $mysqli->query($sqlVerificarSaida);

		if ($resultVerificarSaida->num_rows > 0) {
			// Excluir os registros da tabela "Saida" relacionados ao exemplar
			$sqlDeleteSaida = "DELETE FROM Saida WHERE fk_exemplar = $exemplarId";
			if ($mysqli->query($sqlDeleteSaida) !== TRUE) {
				echo "<div class='alert alert-danger'>Erro ao excluir registros de saída.</div>";
				exit;
			}
		}

		// Excluir o exemplar da tabela "Exemplar"
		$sqlDeleteExemplar = "DELETE FROM Exemplar WHERE id_exemplar = $exemplarId";
		if ($mysqli->query($sqlDeleteExemplar) === TRUE) {
			echo "<script>alert('Excluido com sucesso');</script>";
		} else {
			echo "<div class='alert alert-danger'>Erro ao excluir exemplar.</div>";
		}
	
	}

	$sql 	= "SELECT E.id_exemplar, B.id_brinquedo, SA.id_saida, B.fk_tipo, E.fk_status, SA.motivo, SA.dataSaida
	FROM Exemplar AS E
	INNER JOIN Brinquedo AS B on E.fk_brinquedo = B.id_brinquedo
	INNER JOIN Status AS S on E.fk_status = S.id_status
	INNER JOIN Saida AS SA on SA.fk_exemplar = E.id_exemplar
	WHERE EXISTS (SELECT fk_exemplar FROM Saida WHERE E.id_exemplar = fk_exemplar)
	ORDER BY E.id_exemplar ASC";
	$result = $mysqli->query($sql); 

	if ($result->num_rows > 0) { 
	?>
		<h2>Saída Brinquedos</h2>
		<br>
		<table class="table table-bordered">
			<tr>
				<th>Exemplar</th>
				<th>Nome</th>
				<th>Tipo</th>
				<th>Status</th>
				<th>Motivo Saída</th>
				<th>Data Saída</th>
				<!-- <th width="70px">Excluir</th> -->
				<th width="70px">Editar</th>
			</tr>
		<?php
		while ($row = $result->fetch_assoc()) { 
			echo "<form action='' method='POST'>";
			echo "<input type='hidden' value='". $row['id_exemplar']."' name='exemplarid' />";
			echo "<tr>";
			echo "<td>".$row['id_exemplar'] . "</td>";

			echo "<input type='hidden' value='". $row['id_brinquedo']."' name='brinquedoid' />";
			$brinquedoId = $row['id_brinquedo'];
			$queryBrinquedo = "SELECT nome, descricao, faixaEtaria FROM Brinquedo WHERE id_brinquedo = $brinquedoId";
			$resultBrinquedo = $mysqli->query($queryBrinquedo);
			$rowBrinquedo = $resultBrinquedo->fetch_assoc();
			$nome = $rowBrinquedo['nome'];	
			$descricao = $rowBrinquedo['descricao'];
			$faixaEtaria = $rowBrinquedo['faixaEtaria'];
			echo "<td>" . $nome . "</td>";

			// Buscar o nome do tipo na tabela de Tipos
			$tipoId = $row['fk_tipo'];
			$queryTipo = "SELECT descricao FROM Tipo WHERE id_tipo = $tipoId";
			$resultTipo = $mysqli->query($queryTipo);
			$rowTipo = $resultTipo->fetch_assoc();
			$tipoNome = $rowTipo['descricao'];		
			echo "<td>" . $tipoNome . "</td>";

			// Exibir o status correspondente ao exemplar selecionado
			$statusId = $row['fk_status'];
			$queryStatus = "SELECT descricao FROM Status WHERE id_status = $statusId";
			$resultStatus = $mysqli->query($queryStatus);
			$rowStatus = $resultStatus->fetch_assoc();
			$statusNome = $rowStatus['descricao'];		
			echo "<td>" . $statusNome . "</td>";
			
			echo "<input type='hidden' value='". $row['id_saida']."' name='saidaid' />";
			echo "<td>".$row['motivo'] . "</td>";

			$dataSaidaFormatada = date('d/m/Y H:i:s', strtotime($row['dataSaida']));
			if ($row['dataSaida'] != '' && $row['dataSaida'] != "0000-00-00 00:00:00") {
				echo "<td>" . $dataSaidaFormatada . "</td>";
			} else {
				echo "<td>" . "00-00-0000 00:00:00" . "</td>";
			}

			// echo "<td><input type='submit' name='delete_saida' value='Excluir' class='btn btn-danger' /></td>";
			echo "<td><a href='edit_brinq.php?id=".$row['id_exemplar']."' class='btn btn-info'>Editar</a></td>";
			echo "</tr>";
			echo "</form>";
		}
	?>
		</table>
	<?php 
	} else {
		// echo "<br><br>No Record Found";
	}
	?> 
</div>

<?php 
require_once 'code/footer.php';
?>
