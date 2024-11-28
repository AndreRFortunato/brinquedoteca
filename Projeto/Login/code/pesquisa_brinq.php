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

        if ($tabelaSelecionada == "exemplar"){
            $sql = "SELECT * FROM $tabelaSelecionada WHERE $colunaSelecionada LIKE '%$valorPesquisa%' 
            AND NOT EXISTS (SELECT fk_exemplar FROM Saida WHERE E.id_exemplar = fk_exemplar)";
            $result = $mysqli->query($sql);

            // Específico para o Exemplar
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
                                    echo "<th>Exemplar</th>";
                                    echo "<th>Nome</th>";
                                    echo "<th>Descrição</th>";
                                    echo "<th>Faixa Etária (Anos)</th>";
                                    echo "<th>Tipo</th>";
                                    echo "<th>Área de Desenvolvimento</th>";
                                    break;
                                case 1:
                                    echo "<th>Status</th>";
                                    break;
                                case 2:
                                    echo "<th>Excluir</th>";
                                    echo "<th>Editar</th>";
                                    break;
                            }
                            $cont = $cont + 1;
                        }
                        echo "</tr>";

                        // Dados das linhas
                        while ($row = $result->fetch_assoc()) {
                            $ExemplarId = $row['id_exemplar'];
                            $queryExemplar = "SELECT
                                Exemplar.id_exemplar AS 'Exemplar',
                                Brinquedo.nome AS 'Brinquedo',
                                Brinquedo.descricao AS 'Descricao Brinquedo',
                                Brinquedo.faixaEtaria AS 'Faixa Etaria',
                                Tipo.descricao AS 'Descricao Tipo',
                                AreaDesenvolvimento.descricao AS 'Area Desenvolvimento',
                                Status.descricao AS 'Status'
                            FROM Exemplar
                                JOIN Brinquedo ON Exemplar.fk_brinquedo = Brinquedo.id_brinquedo
                                JOIN Tipo ON Brinquedo.fk_tipo = Tipo.id_tipo
                                JOIN AreaDesenvolvimento ON Brinquedo.fk_areaDesenvolvimento = AreaDesenvolvimento.id_areaDesenvolvimento
                                JOIN Status ON Exemplar.fk_status = Status.id_status
                            WHERE Exemplar.id_exemplar = $ExemplarId";
                            $resultExemplar = $mysqli->query($queryExemplar);
                            $rowExemplar = $resultExemplar->fetch_assoc();
                            
                            echo "<tr>";

                            echo "<input type='hidden' value='". $rowExemplar['Exemplar']."' name='exemplarid' />";
                            echo "<td>" . $rowExemplar['Exemplar'] . "</td>";
                            echo "<td>" . $rowExemplar['Brinquedo'] . "</td>";
                            echo "<td>" . $rowExemplar['Descricao Brinquedo'] . "</td>";
                            echo "<td>" . $rowExemplar['Faixa Etaria'] . "</td>";
                            echo "<td>" . $rowExemplar['Descricao Tipo'] . "</td>";
                            echo "<td>" . $rowExemplar['Area Desenvolvimento'] . "</td>";
                            echo "<td>" . $rowExemplar['Status'] . "</td>";
                            echo "<td>
                                    <form action='code/delete_brinq.php' method='POST'>
                                        <input type='hidden' value='". $rowExemplar['Exemplar']."' name='exemplarid' />
                                        <input type='submit' name='delete_brinq' value='Excluir' class='btn btn-danger' />
                                    </form>
                                </td>";
                            echo "<td><a href='edit_brinq.php?id=".$rowExemplar['Exemplar']."' class='btn btn-info'>Editar</a></td>";
                            echo "</tr>";
                        }
                    echo "</table>";
                echo "</div>";
            }
        }elseif($tabelaSelecionada != "exemplar") {
            $sql = "SELECT * FROM $tabelaSelecionada WHERE $colunaSelecionada LIKE '%$valorPesquisa%'";
            $result = $mysqli->query($sql);

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
                                    echo "<th>Editar</th>";
                                    break;
                            }
                            $cont = $cont + 1;
                        }
                        echo "</tr>";

                        // Dados das linhas
                        while ($row = $result->fetch_assoc()) {
                            $ExemplarId = $row['fk_exemplar'];
                            $queryExemplar = "SELECT
                                Exemplar.id_exemplar AS 'Exemplar',
                                Brinquedo.nome AS 'Brinquedo',
                                Status.descricao AS 'Status'
                            FROM Exemplar
                            INNER JOIN Entrada ON Entrada.fk_exemplar = Exemplar.id_exemplar
                            INNER JOIN Brinquedo ON Exemplar.fk_brinquedo = Brinquedo.id_brinquedo
                            INNER JOIN Status ON Exemplar.fk_status = Status.id_status
                            WHERE Exemplar.id_exemplar = $ExemplarId";
                            $resultExemplar = $mysqli->query($queryExemplar);
                            $rowExemplar = $resultExemplar->fetch_assoc();
                            
                            $FornecedorId = $row['fk_fornecedor'];
                            $queryFornecedor = "SELECT
                                Fornecedor.id_fornecedor AS 'Fornecedor',
                                Fornecedor.nome AS 'Nome'
                            FROM Fornecedor
                            INNER JOIN Entrada ON Entrada.fk_fornecedor = Fornecedor.id_fornecedor
                            WHERE Fornecedor.id_fornecedor = $FornecedorId";
                            $resultFornecedor = $mysqli->query($queryFornecedor);
                            $rowFornecedor = $resultFornecedor->fetch_assoc();
                            
                            echo "<tr>";

                            echo "<input type='hidden' value='". $row['id_entrada']."' name='entradaid' />";
                            echo "<td>" . $row['id_entrada'] . "</td>";
                            echo "<td>" . $row['dataEntrada'] . "</td>";
                            echo "<td>" . $rowExemplar['Exemplar'] . " - " . $rowExemplar['Brinquedo'] . " - " . $rowExemplar['Status'] .  "</td>";
                            echo "<td>" . $rowFornecedor['Fornecedor'] . " - " . $rowFornecedor['Nome'] .  "</td>";
                            echo "<td><a href='edit_entrada.php?id=".$row['id_entrada']."' class='btn btn-info'>Editar</a></td>";
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
                                    echo "<th>Editar</th>";
                                    break;
                            }
                            $cont = $cont + 1;
                        }
                        echo "</tr>";

                        // Dados das linhas
                        while ($row = $result->fetch_assoc()) {
                            $ExemplarId = $row['fk_exemplar'];
                            $queryExemplar = "SELECT
                                Exemplar.id_exemplar AS 'Exemplar',
                                Brinquedo.nome AS 'Brinquedo',
                                Status.descricao AS 'Status'
                            FROM Exemplar
                            INNER JOIN Saida ON Saida.fk_exemplar = Exemplar.id_exemplar
                            INNER JOIN Brinquedo ON Exemplar.fk_brinquedo = Brinquedo.id_brinquedo
                            INNER JOIN Status ON Exemplar.fk_status = Status.id_status
                            WHERE Exemplar.id_exemplar = $ExemplarId";
                            $resultExemplar = $mysqli->query($queryExemplar);
                            $rowExemplar = $resultExemplar->fetch_assoc();

                            echo "<tr>";

                            echo "<input type='hidden' value='". $row['id_saida']."' name='entradaid' />";
                            echo "<td>" . $row['id_saida'] . "</td>";
                            echo "<td>" . $row['motivo'] . "</td>";
                            echo "<td>" . $row['dataSaida'] . "</td>";
                            echo "<td>" . $rowExemplar['Exemplar'] . " - " . $rowExemplar['Brinquedo'] . " - " . $rowExemplar['Status'] .  "</td>";
                            echo "<td><a href='edit_saida.php?id=".$row['id_saida']."' class='btn btn-info'>Editar</a></td>";
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
                            echo "<th>Editar</th>";
                            break;
                    }
                    $cont = $cont + 1;
                }
                echo "</tr>";

                // Dados das linhas
                while ($row = $result->fetch_assoc()) {
                    echo "<input type='hidden' value='". $row['id_brinquedo']."' name='brinquedoid' />";
                    // echo "<input type='hidden' value='". $rowExemplar['Exemplar']."' name='exemplarid' />";

                    $TipoId = $row['fk_tipo'];
                    $queryTipo = "SELECT T.descricao, T.id_tipo FROM Tipo AS T
                    INNER JOIN Brinquedo AS B ON B.fk_tipo = T.id_tipo
                    WHERE id_tipo = $TipoId";
                    $resultTipo = $mysqli->query($queryTipo);
                    $rowTipo = $resultTipo->fetch_assoc();
                    $descricao = $rowTipo['descricao'];
                    $id_Tipo = $rowTipo['id_tipo'];

                    $AreaDId = $row['fk_areaDesenvolvimento'];
                    $queryAreaD = "SELECT A.descricao, A.id_areaDesenvolvimento FROM AreaDesenvolvimento AS A
                    INNER JOIN Brinquedo AS B ON B.fk_areaDesenvolvimento = A.id_areaDesenvolvimento
                    WHERE id_areaDesenvolvimento = $AreaDId";
                    $resultAreaD = $mysqli->query($queryAreaD);
                    $rowAreaD = $resultAreaD->fetch_assoc();
                    $descricaoAreaD = $rowAreaD['descricao'];
                    $id_AreaD = $rowAreaD['id_areaDesenvolvimento'];            

                    echo "<tr>";

                    echo "<td>" . $row['id_brinquedo'] . "</td>";
                    echo "<td>" . $row['nome'] . "</td>";
                    echo "<td>" . $row['descricao'] . "</td>";
                    echo "<td>" . $row['faixaEtaria'] . "</td>";
                    echo "<td>" . $descricao . "</td>";
                    echo "<td>" . $descricaoAreaD . "</td>";
                    echo "<td><a href='edit_brinquedo.php?id=".$row['id_brinquedo']."' class='btn btn-info'>Editar</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";
            }
        }else {
            echo "Nenhum resultado encontrado.";
        }
    } else {
        echo "Erro ao obter as colunas.";
    }
} else {
    echo "Parâmetros inválidos.";
}
?>
