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
                    <input type='hidden' name="tabela" id="tabela" value='Emprestimo'/>
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
        xhr.open("GET", "code/pesquisa_emp.php?tabela=" + tabelaSelecionada + "&coluna=" + colunaSelecionada + "&valor=" + valorPesquisa, true);
        xhr.send();
    });
</script>

<div class='container'>
	<?php
if (isset($_POST['delete'])) {
    $emprestimoId = $_POST['emprestimoid'];

    // Remove o empréstimo
    $sqlRemoverEmprestimo = "DELETE FROM Emprestimo WHERE id_emprestimo = $emprestimoId";
    if ($mysqli->query($sqlRemoverEmprestimo) === TRUE) {
        echo "<script>alert('Excluído com sucesso');</script>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao excluir o empréstimo.</div>";
    }
}

$sql = "SELECT E.id_emprestimo, EX.id_exemplar, A.nome, A.id_aluno, F.nome, F.id_funcionario, EX.fk_status, ST.descricao, E.dataEmprestimo, E.dataPrevDevolucao, E.dataDevolucao
        FROM Emprestimo AS E
        INNER JOIN Aluno AS A ON E.fk_aluno = A.id_aluno
        INNER JOIN Exemplar AS EX ON E.fk_exemplar = EX.id_exemplar
        INNER JOIN Brinquedo AS B ON EX.fk_brinquedo = B.id_brinquedo
        INNER JOIN Status AS ST ON EX.fk_status = ST.id_status
        INNER JOIN Funcionario AS F ON E.fk_funcionario = F.id_funcionario
        WHERE NOT EXISTS (SELECT fk_exemplar FROM Saida WHERE EX.id_exemplar = fk_exemplar)
        ORDER BY E.id_emprestimo ASC";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
?>
    <br>
    <h2>Lista de Empréstimos</h2>
    <br>
    <table class="table table-bordered table-striped">
        <tr>
            <th>Código do Empréstimo</th>
            <th>Aluno</th>
            <th>Funcionário</th>
            <th>Exemplar</th>
            <th>Data do Empréstimo</th>
            <th>Data Prevista para Devolução</th>
            <th>Data de Devolução</th>
            <!-- <th width="70px">Excluir</th> -->
            <th width="70px">Editar</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<form action='' method='POST'>";
            echo "<input type='hidden' value='" . $row['id_emprestimo'] . "' name='emprestimoid' />";
            echo "<tr>";
            echo "<td>" . $row['id_emprestimo'] . "</td>";

            echo "<input type='hidden' value='" . $row['id_aluno'] . "' name='alunoid' />";

            $alunoId = $row['id_aluno'];
            $queryAluno = "SELECT nome, id_aluno FROM Aluno WHERE id_aluno = $alunoId";
            $resultAluno = $mysqli->query($queryAluno);
            $rowAluno = $resultAluno->fetch_assoc();
            $nome = $rowAluno['nome'];
            $id_aluno = $rowAluno['id_aluno'];
            echo "<td>" . $nome . " - " . $id_aluno . "</td>";

            $FuncionarioId = $row['id_funcionario'];
            $queryFuncionario = "SELECT nome, id_funcionario FROM Funcionario WHERE id_funcionario = $FuncionarioId";
            $resultFuncionario = $mysqli->query($queryFuncionario);
            $rowFuncionario = $resultFuncionario->fetch_assoc();
            $nomeFuncionario = $rowFuncionario['nome'];
            $id_funcionario = $rowFuncionario['id_funcionario'];
            echo "<td>" . $nomeFuncionario . " - " . $id_funcionario . "</td>";

			$ExemplarId = $row['id_exemplar'];
            $queryExemplar = "SELECT B.nome, S.descricao FROM Exemplar AS E
			INNER JOIN Brinquedo AS B ON B.id_brinquedo = E.fk_brinquedo
			INNER JOIN Status AS S ON S.id_status = E.fk_status
			WHERE id_exemplar = $ExemplarId";
            $resultExemplar = $mysqli->query($queryExemplar);
            $rowExemplar = $resultExemplar->fetch_assoc();
            $nomeBrinquedo = $rowExemplar['nome'];
            $descricao = $rowExemplar['descricao'];
            echo "<td>" . $ExemplarId . " - " . $nomeBrinquedo . " - " . $descricao . "</td>";

            $dataEmprestimoFormatada = date('d/m/Y H:i:s', strtotime($row['dataEmprestimo']));
            echo "<td>" . $dataEmprestimoFormatada . "</td>";

            $dataPrevDevolucaoFormatada = date('d/m/Y H:i:s', strtotime($row['dataPrevDevolucao']));
            echo "<td>" . $dataPrevDevolucaoFormatada . "</td>";

            $dataDevolucaoFormatada = date('d/m/Y H:i:s', strtotime($row['dataDevolucao']));
            if ($row['dataDevolucao'] != '' && $row['dataDevolucao'] != "0000-00-00 00:00:00") {
                echo "<td>" . $dataDevolucaoFormatada . "</td>";
            } else {
                echo "<td>" . "Sem Devolução" . "</td>";
            }

            // echo "<td><input type='submit' name='delete' value='Excluir' class='btn btn-danger' /></td>";
            echo "<td><a href='edit_emp.php?id=" . $row['id_emprestimo'] . "' class='btn btn-info'>Editar</a></td>";
            echo "</tr>";
            echo "</form>";
        }
        ?>
    </table>
</div>
<?php
} else {
    // echo "<br><br>No Record Found";
}
?>
