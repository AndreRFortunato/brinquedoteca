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

        // Específico para Aluno
        if ($result->num_rows > 0 && $tabelaSelecionada == "aluno") {
            // Formatação dos resultados em formato de tabela HTML
            echo '<div class="container">';
            echo '<table class="table table-bordered">';
            echo "<tr>";
            $cont = 0;
            // Cabeçalhos das colunas
            foreach ($colunas as $coluna) {
                switch ($cont) {
                    case 0:
                        echo "<th>Código do Aluno</th>";
                        break;
                    case 1:
                        echo "<th>Nome</th>";
                        break;
                    case 2:
                        echo "<th>RA</th>";
                        break;
                    case 3:
                        echo "<th>Turma</th>";
                        echo "<th>Ativo</th>";
                        echo "<th>Editar</th>";
                        echo "<th>Excluir</th>";
                        break;
                }
                $cont = $cont + 1;
            }
            echo "</tr>";
        
            // Dados das linhas
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($colunas as $coluna) {
                    echo "<input type='hidden' value='". $row['id_aluno']."' name='alunoid' />";
                    echo "<td>" . $row[$coluna] . "</td>";
                }
                echo "<td>
                        <form action='code/delete_aluno.php' method='POST'>
                            <input type='hidden' value='". $row['id_aluno']."' name='alunoid' />
                            <input type='submit' name='delete_aluno' value='Excluir' class='btn btn-danger' />
                        </form>
                    </td>";
                echo "<td><a href='edit_aluno.php?id=".$row['id_aluno']."' class='btn btn-info'>Editar</a></td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }
        // Específico para Funcionário
        elseif ($result->num_rows > 0 && $tabelaSelecionada == "funcionario") {
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
                        echo "<th>Código do Funcionário</th>";
                        break;
                    case 1:
                        echo "<th>Nome</th>";
                        break;
                    case 2:
                        echo "<th>CPF</th>";
                        break;
                    case 3:
                        echo "<th>Senha</th>";
                        echo "<th>Ativo</th>";
                        echo "<th>Editar</th>";
                        echo "<th>Excluir</th>";
                        break;
                }
                $cont = $cont + 1;
            }
            echo "</tr>";

            // Dados das linhas
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($colunas as $coluna) {
                    echo "<input type='hidden' value='". $row['id_funcionario']."' name='funcionarioid' />";
                    echo "<td>" . $row[$coluna] . "</td>";
                }
                echo "<td>
                        <form action='code/delete_func.php' method='POST'>
                            <input type='hidden' value='". $row['id_funcionario']."' name='funcionarioid' />
                            <input type='submit' name='delete_func' value='Excluir' class='btn btn-danger' />
                        </form>
                    </td>";
                echo "<td><a href='edit_func.php?id=".$row['id_funcionario']."' class='btn btn-info'>Editar</a></td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }

        // Específico para o Fornecedor
        elseif ($result->num_rows > 0 && $tabelaSelecionada == "fornecedor") {
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
                        echo "<th>Código do Fornecedor</th>";
                        break;
                    case 1:
                        echo "<th>Nome</th>";
                        break;
                    case 2:
                        echo "<th>CPF/CNPJ</th>";
                        echo "<th>Ativo</th>";
                        echo "<th>Editar</th>";
                        echo "<th>Excluir</th>";
                        break;
                }
                $cont = $cont + 1;
            }
            echo "</tr>";

            // Dados das linhas
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($colunas as $coluna) {
                    echo "<input type='hidden' value='". $row['id_fornecedor']."' name='fornecedorid' />";
                    echo "<td>" . $row[$coluna] . "</td>";
                }
                echo "<td>
                        <form action='code/delete_forn.php' method='POST'>
                            <input type='hidden' value='". $row['id_fornecedor']."' name='fornecedorid' />
                            <input type='submit' name='delete_forn' value='Excluir' class='btn btn-danger' />
                        </form>
                    </td>";
                echo "<td><a href='edit_forn.php?id=".$row['id_fornecedor']."' class='btn btn-info'>Editar</a></td>";
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
