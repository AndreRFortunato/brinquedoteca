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

        if()
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
                // echo "<th>" . $coluna . $cont. "</th>";
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
                        break;
                }
                $cont = $cont + 1;
            }
            echo "</tr>";

            // Dados das linhas
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($colunas as $coluna) {
                    echo "<td>" . $row[$coluna] . "</td>";
                }
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
                        break;
                }
                $cont = $cont + 1;
            }
            echo "</tr>";

            // Dados das linhas
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($colunas as $coluna) {
                    echo "<td>" . $row[$coluna] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }

        // Específico para Area do Desenvolvimento
        elseif ($result->num_rows > 0 && $tabelaSelecionada == "areadesenvolvimento") {
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
                                echo "<th>Código da Área</th>";
                                break;
                            case 1:
                                echo "<th>Descrição</th>";
                                break;
                        }
                        $cont = $cont + 1;
                    }
                    echo "</tr>";

                    // Dados das linhas
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($colunas as $coluna) {
                            echo "<td>" . $row[$coluna] . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "</div>";
                }

        // Específico para Status
        elseif ($result->num_rows > 0 && $tabelaSelecionada == "status") {
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
                        echo "<th>Código do Status</th>";
                        break;
                    case 1:
                        echo "<th>Descrição</th>";
                        break;
                }
                $cont = $cont + 1;
            }
            echo "</tr>";

            // Dados das linhas
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($colunas as $coluna) {
                    echo "<td>" . $row[$coluna] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }

        // Específico para Tipo
        elseif ($result->num_rows > 0 && $tabelaSelecionada == "tipo") {
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
                        echo "<th>Código do Tipo</th>";
                        break;
                    case 1:
                        echo "<th>Descrição</th>";
                        break;
                }
                $cont = $cont + 1;
            }
            echo "</tr>";

            // Dados das linhas
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($colunas as $coluna) {
                    echo "<td>" . $row[$coluna] . "</td>";
                }
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
                        break;
                }
                $cont = $cont + 1;
            }
            echo "</tr>";

            // Dados das linhas
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($colunas as $coluna) {
                    echo "<td>" . $row[$coluna] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }

        // Específico para Entrada
        elseif ($result->num_rows > 0 && $tabelaSelecionada == "entrada") {
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
                        echo "<th>Código da Entrada</th>";
                        break;
                    case 1:
                        echo "<th>Data da Entrada</th>";
                        break;
                    case 2:
                        echo "<th>Exemplar</th>";
                        break;
                    case 3:
                        echo "<th>Fornecedor</th>";
                        break;
                }
                $cont = $cont + 1;
            }
            echo "</tr>";

            // Dados das linhas
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($colunas as $coluna) {
                    echo "<td>" . $row[$coluna] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }

        // Específico para Saida
        elseif ($result->num_rows > 0 && $tabelaSelecionada == "saida") {
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
                        echo "<th>Código da Saída</th>";
                        break;
                    case 1:
                        echo "<th>Motivo</th>";
                        break;
                    case 2:
                        echo "<th>Data da Saída</th>";
                        break;
                    case 3:
                        echo "<th>Código do Exemplar</th>";
                        break;
                }
                $cont = $cont + 1;
            }
            echo "</tr>";

            // Dados das linhas
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($colunas as $coluna) {
                    echo "<td>" . $row[$coluna] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }

        // Específico para o Exemplar
        elseif ($result->num_rows > 0 && $tabelaSelecionada == "exemplar") {
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
                        echo "<th>Código do Exemplar</th>";
                        break;
                    case 1:
                        echo "<th>Status</th>";
                        break;
                    case 2:
                        echo "<th>Brinquedo</th>";
                        break;
                }
                $cont = $cont + 1;
            }
            echo "</tr>";

            // Dados das linhas
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($colunas as $coluna) {
                    echo "<td>" . $row[$coluna] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }

        // Específico para o Empréstimo
        elseif ($result->num_rows > 0 && $tabelaSelecionada == "emprestimo") {
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
                        echo "<th>Data do Empréstimo</th>";
                        break;
                    case 2:
                        echo "<th>Previsão de Devolução</th>";
                        break;
                    case 3:
                        echo "<th>Data da Devolução</th>";
                        break;
                    case 4:
                        echo "<th>Código do Aluno</th>";
                        break;
                    case 5:
                        echo "<th>Código do Funcionário</th>";
                        break;
                    case 6:
                        echo "<th>Código do Exemplar</th>";
                        break;
                }
                $cont = $cont + 1;
            }
            echo "</tr>";

            // Dados das linhas
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($colunas as $coluna) {
                    echo "<td>" . $row[$coluna] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }

        // Específico para Brinquedos
        elseif ($result->num_rows > 0 && $tabelaSelecionada == "brinquedo") {
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
                        echo "<th>Código do Brinquedo</th>";
                        break;
                    case 1:
                        echo "<th>Nome</th>";
                        break;
                    case 2:
                        echo "<th>Descrição</th>";
                        break;
                    case 3:
                        echo "<th>Faixa Etária</th>";
                        break;
                    case 4:
                        echo "<th>Tipo</th>";
                        break;
                    case 5:
                        echo "<th>Área do Desenvolvimento</th>";
                        break;
                }
                $cont = $cont + 1;
            }
            echo "</tr>";

            // Dados das linhas
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($colunas as $coluna) {
                    echo "<td>" . $row[$coluna] . "</td>";
                }
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
