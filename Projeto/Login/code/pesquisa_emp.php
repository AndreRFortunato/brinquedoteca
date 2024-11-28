<?php
require_once 'conexao.php';

if (isset($_GET['tabela']) && isset($_GET['coluna']) && isset($_GET['valor'])) {
    $tabelaSelecionada = $_GET['tabela'];
    $colunaSelecionada = $_GET['coluna'];
    $valorPesquisa = $_GET['valor'];

    // Consulta ao banco de dados para obter as colunas
    $sqlColunas = "SHOW COLUMNS FROM $tabelaSelecionada";
    $resultColunas = $mysqli->query($sqlColunas);

    if ($resultColunas) {
        // Armazena as colunas em um array
        $colunas = array();
        while ($rowColunas = $resultColunas->fetch_assoc()) {
            $colunas[] = $rowColunas['Field'];
        }

        // Consulta ao banco de dados com base nos parâmetros
        $sql = "SELECT * FROM $tabelaSelecionada WHERE $colunaSelecionada LIKE '%$valorPesquisa%'";
        $result = $mysqli->query($sql);

        // Específico para o Empréstimo
        if ($result->num_rows > 0 ) {
            // Formatação dos resultados em formato de tabela HTML
            echo '<div class="container">';
            echo '<table class="table table-bordered">';
            echo "<tr>";
            $cont = 0;
            // Cabeçalhos das colunas
            foreach ($colunas as $coluna) {
                // echo "<th>" . $coluna . $cont. "</th>";
                switch ($cont) {
                    case 0:
                        echo "<th>Código do Empréstimo</th>";
                        break;
                    case 1:
                        echo "<th>Aluno</th>";
                        break;
                    case 2:
                        echo "<th>Funcionário</th>";
                        break;
                    case 3:
                        echo "<th>Exemplar</th>";
                        break;
                    case 4:
                        echo "<th>Data do Empréstimo</th>";
                        break;
                    case 5:
                        echo "<th>Data Prevista para Devolução</th>";
                        break;
                    case 6:
                        echo "<th>Data de Devolução</th>";
                        echo "<th>Editar</th>";
                        // echo "<th>Excluir</th>";
                        break;
                }
                $cont = $cont + 1;
            }
            echo "</tr>";
            
            // Dados das linhas
            while ($row = $result->fetch_assoc()) {
                echo "<input type='hidden' value='". $row['id_emprestimo']."' name='emprestimoid' />";
                // echo "<input type='hidden' value='". $rowExemplar['Exemplar']."' name='exemplarid' />";
                $ExemplarId = $row['fk_exemplar'];
                $queryExemplar = "SELECT B.nome, S.descricao, E.id_exemplar FROM Exemplar AS E
                INNER JOIN Brinquedo AS B ON B.id_brinquedo = E.fk_brinquedo
                INNER JOIN Status AS S ON S.id_status = E.fk_status
                INNER JOIN Emprestimo AS EM ON EM.fk_exemplar = E.id_exemplar
                WHERE fk_exemplar = $ExemplarId";
                $resultExemplar = $mysqli->query($queryExemplar);
                $rowExemplar = $resultExemplar->fetch_assoc();
                $nomeBrinquedo = $rowExemplar['nome'];
                $descricao = $rowExemplar['descricao'];

                $alunoId = $row['fk_aluno'];
                $queryAluno = "SELECT A.nome, A.id_aluno FROM Aluno AS A
                INNER JOIN Emprestimo AS EM ON EM.fk_aluno = A.id_aluno
                WHERE id_aluno = $alunoId";
                $resultAluno = $mysqli->query($queryAluno);
                $rowAluno = $resultAluno->fetch_assoc();
                $nome = $rowAluno['nome'];
                $id_aluno = $rowAluno['id_aluno'];
    
                $FuncionarioId = $row['fk_funcionario'];
                $queryFuncionario = "SELECT F.nome, F.id_funcionario FROM Funcionario AS F
                INNER JOIN Emprestimo AS EM ON EM.fk_funcionario = F.id_funcionario
                WHERE id_funcionario = $FuncionarioId";
                $resultFuncionario = $mysqli->query($queryFuncionario);
                $rowFuncionario = $resultFuncionario->fetch_assoc();
                $nomeFuncionario = $rowFuncionario['nome'];
                $id_funcionario = $rowFuncionario['id_funcionario'];            

                echo "<tr>";

                echo "<td>" . $row['id_emprestimo'] . "</td>";
                echo "<td>" . $nome . " - " . $id_aluno . "</td>";
                echo "<td>" . $nomeFuncionario . " - " . $id_funcionario . "</td>";                
                echo "<td>" . $ExemplarId . " - " . $nomeBrinquedo . " - " . $descricao . "</td>";
                echo "<td>" . $row['dataEmprestimo'] . "</td>";
                echo "<td>" . $row['dataPrevDevolucao'] . "</td>";
                $dataDevolucaoFormatada = date('d/m/Y H:i:s', strtotime($row['dataDevolucao']));
                if ($row['dataDevolucao'] != '' && $row['dataDevolucao'] != "0000-00-00 00:00:00") {
                    echo "<td>" . $dataDevolucaoFormatada . "</td>";
                } else {
                    echo "<td>" . "Sem Devolução" . "</td>";
                }

                // echo "<td>
                //         <form action='code/delete_emp.php' method='POST'>
                //             <input type='hidden' value='". $row['id_emprestimo']."' name='emprestimoid' />
                //             <input type='submit' name='delete_emp' value='Excluir' class='btn btn-danger' />
                //         </form>
                //     </td>";
                echo "<td><a href='edit_emp.php?id=".$row['id_emprestimo']."' class='btn btn-info'>Editar</a></td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }

        else {
            echo "Nenhum resultado encontrado.";
        }
    } else {
        echo "Erro ao obter as colunas.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>
